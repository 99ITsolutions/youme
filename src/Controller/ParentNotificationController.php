<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ParentNotificationController  extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     **/
     
     
    public function index()
    { 
        $pid = $this->Cookie->read('pid'); 
        $sessionid = $this->Cookie->read('sessionid'); 
         $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($pid)) {
            $notification_table = TableRegistry::get('notification');
            $parnt_table = TableRegistry::get('parent_logindetails');
            $comp_table = TableRegistry::get('company');
            $cls_table = TableRegistry::get('class');
            $clssubjcts_table = TableRegistry::get('class_subjects');
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            $emp_table = TableRegistry::get('employee');
            $empcls_table = TableRegistry::get('employee_class_subjects');
            $stud_table = TableRegistry::get('student');
            
            $retrieve_parnt = $parnt_table->find()->where([ 'md5(id)' => $pid])->first() ;
            $parntid = $retrieve_parnt['id']; 
            
            $retrieve_stud = $stud_table->find()->where([ 'md5(parent_id)' => $pid, 'session_id' => $sessionid])->toArray() ;
            $schoolid= [];
            $studid = [];
            $studclass =[];
            foreach($retrieve_stud as $stud)
            {
                $schoolid[]  = $stud['school_id'];
                $studid[] = $stud['id'];
                $studclass[]  = $stud['class'];
            }
            
            $retrieve_notify = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $retrieve_parnt['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']]  ])->order(['id' => 'desc'])->toArray() ;
            
            foreach($retrieve_notify as $notify)
            {
               // echo $notify['id'].","; 
                $notify->tchrscholid ='';
                $notify->sentlogo = 'you-meheaderlogo.png';
                if($notify['added_by'] == "teachers")
                {
                    $tchrid = $notify['teacher_id'];
                    $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                    $notify->tchrscholid = $retrieve_emp['school_id'];
                    
                    $notify->sentby = $retrieve_emp['l_name']." ". $retrieve_emp['f_name'] ;
                    $retrieve_company = $comp_table->find()->where([ 'id' => $retrieve_emp['school_id']])->first() ;
                    $notify->sentlogo = $retrieve_company['comp_logo'];
                    
                    $tchrcls = [];
                    $tchrsubj = [];
                    $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->group(['class_id'])->toArray() ;
                    foreach($retrieve_empcls as $empcls)
                    {
                        $tchrcls[] = $empcls['class_id'];
                        $tchrsubj[] = $empcls['subject_id'];
                    }
                    $notify->tchrcls = implode(",", $tchrcls);
                    $notify->tchrsubj = implode(",", $tchrsubj);
                    
                    $snames = [];
                    foreach($retrieve_stud as $stud)
                    {
                        if(in_array($stud['class'], $tchrcls))
                        {
                           $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                            //$notify->sentlogo = $retrieve_company['comp_logo'];
                        }
                    }
                    $studname = implode(",", $snames);
                    $notify->studentname = $studname;
                    
                }
                elseif($notify['added_by'] == "school")
                {
                     $retrieve_company = $comp_table->find()->where([ 'id' => $notify['school_id']])->first() ;
                     
                     $notify->sentby = $retrieve_company['comp_name'] ;
                     $notify->sentlogo = $retrieve_company['comp_logo'];
                     
                     
                    $clsids = explode(",", $notify['class_ids']);
                    $snames = [];
                    foreach($retrieve_stud as $stud)
                    {
                        if(in_array($stud['class'], $clsids))
                        {
                           $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                            //$notify->sentlogo = $retrieve_company['comp_logo'];
                        }
                    }
                    $studname = implode(",", $snames);
                    $notify->studentname = $studname;
                     
                }
            }
            
            foreach($retrieve_notify as $value)
            {
                if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
                { 
                    $display = 1;
                }
                elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
                { 
                    $display = 1;
                }
                elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
                { 
                    $tchrscls = explode(",", $value['tchrcls']);
                    
                    if(array_intersect($studclass, $tchrscls))
                    {
                        $display = 1;
                    }
                    else
                    {
                        $display = 0;
                    }
                }
                else
                {
                    $display = 0;
                    if($value['class_opt'] == "multiple")
                    {
                        $incls = [];
                        $clsids = explode(",", $value['class_ids']);
                        foreach($studclass as $sdcls)
                        {
                            if(in_array($sdcls, $clsids))
                            {
                                $incls[] = 1;
                            }
                            else
                            {
                                $incls[] = 0;
                            }
                        }
                        $display = in_array("1", $incls) ? "1" : "0";
                    }
                    elseif($value['class_opt'] == "single")
                    {
                        $parids = explode(",", $value['parent_ids']);
                      
                        if(in_array($parntid, $parids))
                        {
                            $display = 1;
                        }
                        else
                        {
                            $display = 0;
                        }
                    }
                    
                }
            
                //echo $value['id']."--".$display.","; 
                if($display == 1)
                {
                    $notify_recv_tbl = TableRegistry::get('received_notfiy');
                    $notifyrecvb = $notify_recv_tbl->find()->where(['md5(role_id)' => $pid, 'role_type' => 'parent' , 'notify_id' => $value['id'] ])->first() ;
                    $value->notifybold = $notifyrecvb['status'];
                }
            }
            
            $this->set("notify_details", $retrieve_notify); 
            $this->set("parntid", $parntid); 
            $this->set("studclass", $studclass); 
            $this->set("schoolid", $schoolid); 
            $this->set('notify_recv',$notify_recv1);
            $this->viewBuilder()->setLayout('usersa');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    
    public function update()
    {   
        $pid = $this->Cookie->read('pid'); 
        if(!empty($stid)) {
            $parnt_table = TableRegistry::get('parent_logindetails');
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            $role_type  = "parent";
             $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_parent = $parnt_table->find()->select(['id'])->where(['md5(id)' => $pid ])->first() ;
            $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $retrieve_parent['id'], 'role_type' => 'parent' ])->count() ;
            
            if($notify_recv == 0)
            {
                $chcked_notfy = $notify_recv_tbl->newEntity();
                $chcked_notfy->role_id = "parent";
                $chcked_notfy->role_type = $retrieve_parent['id'];
                $chcked_notfy->created_date = time();
                if($saved = $notify_recv_tbl->save($chcked_notfy) )
                {   
                    $res = [ 'result' => 'success'];
                }
                else
                {
                    $res = [ 'result' => 'failed'  ];
                }
            }
            else
            {
                $now = time();
                $update = $notify_recv_tbl->query()->update()->set([ 'created_date' => $now ])->where(['role_id' => $retrieve_parent['id'], 'role_type' => 'parent' ])->execute();
            
                if($update)
                {   
                    $res = [ 'result' => 'success'];
                }
                else
                {
                    $res = [ 'result' => 'failed'  ];
                }
            }
            return $this->json($res);
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
			
}

  


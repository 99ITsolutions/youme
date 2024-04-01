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
class AnnouncementController  extends AppController
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
        if(!empty($this->Cookie->read('stid')))
        {
            $studid = $this->Cookie->read('stid'); 
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $studid = $this->Cookie->read('pid'); 
            $stid = $this->Cookie->read('pid'); 
        }
        
        if(!empty($studid) && !empty($stid)) {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $notification_table = TableRegistry::get('notification');
            $stud_table = TableRegistry::get('student');
            
            $cls_table = TableRegistry::get('class');
            $clssubjcts_table = TableRegistry::get('class_subjects');
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            
            $retrieve_stud = $stud_table->find()->where([ 'md5(id)' => $studid])->toArray() ;
            $schoolid  = $retrieve_stud[0]['school_id'];
            $studid  = $retrieve_stud[0]['id'];
            $studclass  = $retrieve_stud[0]['class'];
            //$sessid = $this->Cookie->read('sessionid');
            $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'created_date >=' => $retrieve_stud[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']]  ])->order(['id' => 'desc'])->toArray() ;
            
            $emp_table = TableRegistry::get('employee');
            $empcls_table = TableRegistry::get('employee_class_subjects');
            foreach($retrieve_notify as $notify)
            {
                
                $notify->tchrscholid ='';
                if($notify['notify_to'] == "all" && $notify['added_by'] == "teachers")
                {
                    //print_r($notify['id']);
                    $tchrid = $notify['teacher_id'];
                    $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                    $notify->tchrscholid = $retrieve_emp['school_id'];
                    $tchrcls = [];
                    $tchrsubj = [];
                    $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->toArray() ;
                    foreach($retrieve_empcls as $empcls)
                    {
                        $tchrcls[] = $empcls['class_id'];
                        $tchrsubj[] = $empcls['subject_id'];
                    }
                    $notify->tchrcls = implode(",", $tchrcls);
                    $notify->tchrsubj = implode(",", $tchrsubj);
                    
                }
            }
            
           
            $role_type  = "student";
    
            $retrieve_student = $stud_table->find()->select(['id', 'class'])->where(['md5(id)' => $stid ])->toArray() ;
            $retrieve_clssub = $clssubjcts_table->find()->where(['class_id' => $retrieve_student[0]['class'] ])->first() ;
             
            
            foreach($retrieve_notify as $value)
            {
               
                if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
                { 
                    $display = 1;
                }
                elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && $value['school_id'] == $schoolid)
                { 
                    $display = 1;
                }
                elseif(($value['notify_to'] == "all") && ($value['added_by'] == "teachers") &&  ($value['tchrscholid'] == $schoolid))
                { 
                    $display = 1;
                    /*$tchrscls = explode(",", $value['tchrcls']);
                    if(in_array($retrieve_clssub['class_id'], $tchrscls))
                    {
                        $display = 1;
                    }
                    else
                    {
                        $display = 0;
                    }*/
                }
                else
                {
                    $display = 0;
                    if($value['class_opt'] == "multiple")
                    {
                        $clsids = explode(",", $value['class_ids']);
                        if(in_array($studclass, $clsids))
                        {
                            $display = 1;
                        }
                    }
                    else
                    {
                        $studids = explode(",", $value['student_ids']);
                  
                        if(in_array($studid, $studids))
                        {
                            $display = 1;
                        }
                    }
                }
                if($display == 1)
                {
                   // print_r($value['id'].",");
                    $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $retrieve_student[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'student' ])->first() ;
                    $value->notifybold = $notifyrecvb['status'];
                }
            }
            //die();
            $this->set("clssub", $retrieve_clssub);
            $this->set("notify_details", $retrieve_notify); 
            $this->set("studid", $studid); 
            $this->set("studclass", $studclass); 
            $this->set("schoolid", $schoolid);
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function view()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $employee_table = TableRegistry::get('employee');
		$subjectid = $this->request->data('classid');
		$classid = $this->request->data('subjectid');
        $retrieve_employee = $employee_table->find()->where(['subjects LIKE' => "%".$subjectid."%", 'grades LIKE' => "%".$classid."%" ])->toArray() ;
		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid];
        return $this->json($data);
    }
    
    public function update()
    {   
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
        if(!empty($stid)) {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $stud_table = TableRegistry::get('student');
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            $role_type  = "student";
    
            $retrieve_student = $stud_table->find()->select(['id'])->where(['md5(id)' => $stid ])->toArray() ;
            $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $retrieve_student[0]['id'], 'role_type' => 'student' ])->count() ;
            
            if($notify_recv == 0)
            {
                $chcked_notfy = $notify_recv_tbl->newEntity();
                $chcked_notfy->role_id = "teachers";
                $chcked_notfy->role_type = $retrieve_emp[0]['id'];
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
                $update = $notify_recv_tbl->query()->update()->set([ 'created_date' => $now ])->where(['role_id' => $retrieve_student[0]['id'], 'role_type' => 'student' ])->execute();
            
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

  


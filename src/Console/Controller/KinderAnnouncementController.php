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
class KinderAnnouncementController  extends AppController
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
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            
            $retrieve_stud = $stud_table->find()->where([ 'md5(id)' => $studid])->toArray() ;
            $schoolid  = $retrieve_stud[0]['school_id'];
            $studid  = $retrieve_stud[0]['id'];
            $studclass  = $retrieve_stud[0]['class'];
            
            $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']]  ])->order(['id' => 'desc'])->toArray() ;
            
            $this->set("notify_details", $retrieve_notify); 
            $this->set("studid", $studid); 
            $this->set("studclass", $studclass); 
            
            $role_type  = "student";
    
            $retrieve_student = $stud_table->find()->select(['id'])->where(['md5(id)' => $stid ])->toArray() ;
            
            $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $retrieve_student[0]['id'], 'role_type' => 'student' ])->count() ;
            
            if($notify_recv == 0)
            {
                $chcked_notfy = $notify_recv_tbl->newEntity();
                
                $chcked_notfy->role_id = $retrieve_student[0]['id'];
                $chcked_notfy->role_type = $role_type;
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
            $this->viewBuilder()->setLayout('kinder');
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

  


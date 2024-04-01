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
class SchoolNotificationController  extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
        public function index()
        {
            $schoolid = $this->Cookie->read('id')   ;
            $notification_table = TableRegistry::get('notification');
            $scl_table = TableRegistry::get('company');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_scl = $scl_table->find()->select(['id'])->where([ 'md5(id)' => $schoolid])->toArray() ;
            $sclid  = $retrieve_scl[0]['id'];
            $retrieve_notify = $notification_table->find()->where(['sent_notify' => 0,  'added_by' => 'school', 'md5(school_id)' => $schoolid])->order(['id' => 'desc'])->toArray() ;
            
            $rcve_nottzCount = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1,  'added_by' => 'superadmin', 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
             
            if(!empty($rcve_nottzCount))
            {
                $countNotifctn = [];
                foreach($rcve_nottzCount as $notzcount)
                {
                    if($notzcount['notify_to'] == "all")
                    {
                        $countNotifctn[] = 1;
                    }
                    else
                    {
                        $schoolids = explode(",", $notzcount['school_ids']);
                      
                        if(in_array($sclid, $schoolids))
                        {
                            $countNotifctn[] = 1;
                        }
                        else
                        {
                            $countNotifctn[] = 0;
                        }
                    }
                }
                $countNoti = array_sum($countNotifctn);
                
            }
            else
            {
                $countNoti = 0;
            }
            //$retrieve_schl = $scl_table->find()->select(['id', 'comp_name'])->where(['status' => 1])->toArray() ;
            $this->set("notify_details", $retrieve_notify); 
            $this->set("total_count", $countNoti); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function archive()
        {
            $schoolid = $this->Cookie->read('id')   ;
            $notification_table = TableRegistry::get('notification');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'added_by' => 'school', 'md5(school_id)' => $schoolid])->order(['id' => 'desc'])->toArray() ;
            //$retrieve_schl = $scl_table->find()->select(['id', 'comp_name'])->where(['status' => 1])->toArray() ;
            $this->set("notify_details", $retrieve_notify); 
            //$this->set("scl_details", $retrieve_schl); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function receive()
        {
            $schoolid = $this->Cookie->read('id')   ;
            $notification_table = TableRegistry::get('notification');
            $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'teachers']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->order(['id' => 'DESC'])->toArray() ;
            
            $scl_table = TableRegistry::get('company');
            $retrieve_scl = $scl_table->find()->select(['id'])->where([ 'md5(id)' => $schoolid])->toArray() ;
            
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $scl_table = TableRegistry::get('company');
            $notify_recv_tbl = TableRegistry::get('received_notfiy');
            $role_type  = "school";
    
            $retrieve_scl = $scl_table->find()->select(['id'])->where(['md5(id)' => $schoolid ])->toArray() ;
            
            $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $retrieve_scl[0]['id'], 'role_type' => 'school' ])->count() ;
            
            if($notify_recv == 0)
            {
                $chcked_notfy = $notify_recv_tbl->newEntity();
                
                $chcked_notfy->role_id = $retrieve_scl[0]['id'];
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
                $update = $notify_recv_tbl->query()->update()->set([ 'created_date' => $now ])->where(['role_id' => $retrieve_scl[0]['id'], 'role_type' => 'school' ])->execute();
            
                if($update)
                {   
                    $res = [ 'result' => 'success'];
                }
                else
                {
                    $res = [ 'result' => 'failed'  ];
                }
                
            }
                
            $sclid = $retrieve_scl[0]['id'];
            $this->set("notify_details", $retrieve_notify); 
            $this->set("sclid", $sclid); 
            $this->viewBuilder()->setLayout('user');
        }

        public function add()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclid = $this->Cookie->read('id')   ;
            $emp_table = TableRegistry::get('employee');
            $retrieve_teachers = $emp_table->find()->select(['id', 'f_name', 'l_name' , 'email'])->where(['status' => 1, 'md5(school_id)' => $sclid])->toArray() ;
            $this->set("empdetails", $retrieve_teachers); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function teacherlist()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclid = $this->Cookie->read('id')   ;
            $emp_table = TableRegistry::get('employee');
            $retrieve_teachers = $emp_table->find()->select(['id', 'f_name', 'l_name' , 'email'])->where(['status' => 1, 'md5(school_id)' => $sclid])->toArray() ;
            $html = [];
            $html[] = '<option value="all">All</option>';
            foreach($retrieve_teachers as $tchrs)
            {
                $html[] .= "<option value='".$tchrs['id']."'>".$tchrs['f_name']." ". $tchrs['l_name'] ."(".$tchrs['email'].")</option>";
            }
            
            $res['data'] = $html;
            
            return $this->json($res);
        }
        
        public function classlist()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclid = $this->Cookie->read('id')   ;
            $class_table = TableRegistry::get('class');
            $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section', 'school_sections' ])->where(['active' => 1, 'md5(school_id)' => $sclid])->toArray() ;
            $html = [];
            $clsids = [];
            foreach($retrieve_class as $cls)
            {
                $clsids[] = $cls['id'];
            }
            $clssids = implode(",", $clsids);
            $html[] .= "<option value='".$clssids."'>All</option>";
            foreach($retrieve_class as $cls)
            {
                $html[] .= "<option value='".$cls['id']."'>".$cls['c_name']."-". $cls['c_section'] ."(". $cls['school_sections'] .")</option>";
            }
            
            $res['data'] = $html;
            
            return $this->json($res);
        }
        
        public function studentlist()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclid = $this->Cookie->read('id')   ;
            $clsId = $this->request->data('classid');
            $student_table = TableRegistry::get('student');
            $sessionid = $this->Cookie->read('sessionid');
            $retrieve_student = $student_table->find()->select(['id', 'f_name', 'l_name' , 'email'])->where(['session_id' => $sessionid, 'status' => 1, 'md5(school_id)' => $sclid, 'class' => $clsId])->toArray() ;
            $html = [];
            $stuids = [];
            foreach($retrieve_student as $studnt)
            {
                $stuids[] = $studnt['id'];
            }
            $studids = implode(",", $stuids);
            $html[] .= "<option value='".$studids."'>All</option>";
            foreach($retrieve_student as $studnt)
            {
                $html[] .= "<option value='".$studnt['id']."'>".$studnt['f_name']." ". $studnt['l_name'] ."(".$studnt['email'].")</option>";
            }
            
            $res['data'] = $html;
            
            return $this->json($res);
        }
        
        public function parentlist()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclid = $this->Cookie->read('id')   ;
            $clsId = $this->request->data('classid');
            $student_table = TableRegistry::get('student');
            $sessionid = $this->Cookie->read('sessionid');
            $retrieve_student = $student_table->find()->select(['student.id', 'student.f_name', 'student.l_name', 'student.adm_no', 'parent_logindetails.id', 'parent_logindetails.parent_email'])->join(['parent_logindetails' => 
							[
							'table' => 'parent_logindetails',
							'type' => 'LEFT',
							'conditions' => 'parent_logindetails.id = student.parent_id'
						]
                
                ])->where(['student.session_id' => $sessionid,'student.status' => 1, 'md5(student.school_id)' => $sclid, 'student.class' => $clsId])->group(['student.parent_id'])->toArray() ;
            $html = [];
            $stuids = [];
            foreach($retrieve_student as $studnt)
            {
                $stuids[] = $studnt['parent_logindetails']['id'];
            }
            $studids = implode(",", $stuids);
            $html[] .= "<option value='".$studids."'>All</option>";
            foreach($retrieve_student as $studnt)
            {
                $html[] .= "<option value='".$studnt['parent_logindetails']['id']."'>".$studnt['f_name']." ".$studnt['l_name'] ." (".$studnt['parent_logindetails']['parent_email'].")</option>";
            }
            
            $res['data'] = $html;
            
            return $this->json($res);
        }

        public function addnotify()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $notification_table = TableRegistry::get('notification');
                $scl_table = TableRegistry::get('company');
                $activ_table = TableRegistry::get('activity');
                $notifyto = $this->request->data('notify_to');
                
                $notification = $notification_table->newEntity();
                
                if(!empty($this->request->data['attachmnt']['name']))
                {   
                    if($this->request->data['attachmnt']['type'] == "application/pdf" || $this->request->data['attachmnt']['type'] == "image/jpeg" || $this->request->data['attachmnt']['type'] == "image/jpg" || $this->request->data['attachmnt']['type'] == "image/png" )
                    {
                        $filename = $this->request->data['attachmnt']['name'];
                        $uploadpath = 'notifyattachmnt/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['attachmnt']['tmp_name'], $uploadfile))
                        {
                            $this->request->data['attachmnt'] = $filename; 
                        }
                    }    
                }
                else
                {
                    $filename = "";
                }
                
                if($notifyto == "teachers")
                {
                    //$notification->school_ids = $this->request->data('schoollist');
                    $notification->teacher_ids = implode(",", $this->request->data['teacherlist']);
                }
                elseif($notifyto == "students" )
                {
                   // print_r($_POST);
                    //$notification->school_ids = $this->request->data('schoollist');
                    $notification->class_opt = $this->request->data('classoptn');
                    $class_opt = $this->request->data('classoptn');
                    
                    if($class_opt == "multiple")
                    {
                        $notification->class_ids = implode(",", $this->request->data['m_classlist']);
                    }
                    elseif($class_opt == "single")
                    {
                        $notification->class_ids = $this->request->data('s_classlist');
                        $notification->student_ids = implode(",", $this->request->data['studentlist']);
                    }
                    
                }
                elseif($notifyto == "parents")
                {
                    //print_r($_POST);
                    //$notification->school_ids = $this->request->data('schoollist');
                    $notification->class_opt = $this->request->data('classoptn');
                    $class_opt = $this->request->data('classoptn');
                    
                    if($class_opt == "multiple")
                    {
                        $notification->class_ids = implode(",", $this->request->data['m_classlist']);
                    }
                    elseif($class_opt == "single")
                    {
                        $notification->class_ids = $this->request->data('s_classlist');
                        $notification->parent_ids = implode(",", $this->request->data['parentlist']);
                    }
                    
                }
                $schoolid = $this->Cookie->read('id');
                $retrieve_school = $scl_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                
                $notification->title = $this->request->data('title');
                $notification->notify_to = $this->request->data('notify_to');
                $notification->description = $this->request->data('descnotify');
				$notification->status = 1;
				$notification->sent_notify = 0;
                $notification->added_by = "school";
                $notification->school_id = $retrieve_school[0]['id'];
                $notification->created_date = time();
                $notification->schedule_date = $this->request->data('schedule_date');
                $notification->attachment = $filename;
                $notification->sc_date_time = strtotime($this->request->data('schedule_date'));
                                      
                if($saved = $notification_table->save($notification) )
                {   
                    $galleryid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Notification Added"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('uid');
                    $activity->value = md5($galleryid); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;
                    
                    if($save)
                    {   
                        $res = [ 'result' => 'success'];
                    }
                    else
                    { 
                        $res = [ 'result' => 'activity'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'failed'  ];
                }
                 
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];

            }

            return $this->json($res);
        }
            

        public function delete()
        {
            $sid = $this->request->data('val') ;
            $notify_table = TableRegistry::get('notification');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $subid = $notify_table->find()->select(['id'])->where(['id'=> $sid ])->first();
            if($subid)
            {   
                
                if($notify_table->delete($notify_table->get($sid)))
                {
                    //$del = $galleryimg_table->query()->delete()->where([ 'gallery_id' => $sid ])->execute();
                    
                    $activity = $activ_table->newEntity();
                    $activity->action =  "notification Deleted"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = $sid    ;
                    $activity->origin = $this->Cookie->read('sid')   ;
                    $activity->created = strtotime('now');

                    if($saved = $activ_table->save($activity) )
                    {
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'not delete'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }
		
	    public function edit($id)
        {   
            $sclid = $this->Cookie->read('id')   ;
            $notify_table = TableRegistry::get('notification');
            $getnotify = $notify_table->find()->where(['md5(id)' => $id])->toArray();
            $scl_table = TableRegistry::get('company');
            $sessionid = $this->Cookie->read('sessionid');
            $retrieve_schl = $scl_table->find()->select(['id', 'comp_name'])->where(['status' => 1])->toArray() ;
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $emp_table = TableRegistry::get('employee');
            $retrieve_teachers = $emp_table->find()->select(['id', 'f_name', 'l_name' , 'email'])->where(['status' => 1, 'md5(school_id)' => $sclid])->toArray() ;
            $this->set("empdetails", $retrieve_teachers); 
            
            
            if($getnotify[0]['notify_to'] == "students")
            { 
                //$sclid = $getnotify[0]['school_ids'];
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section', 'school_sections' ])->where(['active' => 1, 'md5(school_id)' => $sclid])->toArray() ;
                $this->set("cls_details", $retrieve_class); 
                
                $clsId = $getnotify[0]['class_ids'];
                $student_table = TableRegistry::get('student');
                $retrieve_student = $student_table->find()->select(['id', 'f_name', 'l_name' , 'email', 's_f_name', 'emergency_number'])->where(['session_id' => $sessionid, 'status' => 1, 'md5(school_id)' => $sclid, 'class' => $clsId])->toArray() ;
                $this->set("std_details", $retrieve_student); 
                    
            }
            if($getnotify[0]['notify_to'] == "parents") 
            {
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section', 'school_sections' ])->where(['active' => 1, 'md5(school_id)' => $sclid])->toArray() ;
                $this->set("cls_details", $retrieve_class); 
                
                $clsId = $getnotify[0]['class_ids'];
                $student_table = TableRegistry::get('student');
                //$retrieve_student = $student_table->find()->select(['id', 'f_name', 'l_name' , 'email', 's_f_name', 'emergency_number'])->where(['status' => 1, 'md5(school_id)' => $sclid, 'class' => $clsId])->toArray() ;
                
                
                
                $retrieve_parent = $student_table->find()->select(['student.id', 'student.f_name', 'student.l_name', 'student.adm_no', 'parent_logindetails.id', 'parent_logindetails.parent_email'])->join(['parent_logindetails' => 
					[
					'table' => 'parent_logindetails',
					'type' => 'LEFT',
					'conditions' => 'parent_logindetails.id = student.parent_id'
				]
                
                ])->where(['student.session_id' => $sessionid,'student.status' => 1, 'md5(student.school_id)' => $sclid, 'student.class' => $clsId])->group(['student.parent_id'])->toArray() ;

                $this->set("par_details", $retrieve_parent); 

            }
            $this->set("scl_details", $retrieve_schl); 
            $this->set("notify_details", $getnotify); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function status()
        {   

            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $notify_table = TableRegistry::get('notification');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $notify_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $notify_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Notification status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($uid)    ;
                    $activity->origin = $this->Cookie->read('sid')   ;
                    $activity->created = strtotime('now');

                    if($saved = $activ_table->save($activity) )
                    {
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }

        public function editnotify()
        {   
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $notification_table = TableRegistry::get('notification');
                $activ_table = TableRegistry::get('activity');
                $notifyto = $this->request->data('notify_to');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                //$notification = $notification_table->newEntity();
                $notifyid = $this->request->data('notify_id');
                $title = $this->request->data('title');
                $notify_to = $this->request->data('notify_to');
                $description = $this->request->data('descnotify');
                $schedule_date = $this->request->data('schedule_date');
                $sc_date_time = strtotime($this->request->data('schedule_date'));
				$status = 1;
                $added_by = "school";
                $created_date = time();
                
                if(!empty($this->request->data['attachmnt']['name']))
                {   
                    if($this->request->data['attachmnt']['type'] == "application/pdf" || $this->request->data['attachmnt']['type'] == "image/jpeg" || $this->request->data['attachmnt']['type'] == "image/jpg" || $this->request->data['attachmnt']['type'] == "image/png" )
                    {
                        $filename = $this->request->data['attachmnt']['name'];
                        $uploadpath = 'notifyattachmnt/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['attachmnt']['tmp_name'], $uploadfile))
                        {
                            $this->request->data['attachmnt'] = $filename; 
                            
                           
                        }
                    }    
                }
                else
                {
                    $filename = $this->request->data('preattachmnt');
                }
                
                
                $schoolid = $this->Cookie->read('id');
                $scl_table = TableRegistry::get('company');
                $retrieve_scl = $scl_table->find()->select(['id'])->where([ 'md5(id)' => $schoolid])->toArray() ;
                $sclid  = $retrieve_scl[0]['id'];
                
                if($notifyto == "teachers")
                {
                    //$school_ids = $this->request->data('schoollist');
                    $teacher_ids = implode(",", $this->request->data['teacherlist']);
                    $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'title' => $title , 'attachment' => $filename , 'schedule_date' => $schedule_date, 'description' => $description , 'teacher_ids' => $teacher_ids , 'school_id' => $sclid , 'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                
                }
                elseif($notifyto == "students")
                {
                    //print_r($_POST);
                    //$school_ids = $this->request->data('schoollist');
                    $class_opt = $this->request->data('classoptn');
                    
                    if($class_opt == "multiple")
                    {
                        $class_ids = implode(",", $this->request->data['m_classlist']);
                        $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'title' => $title ,  'attachment' => $filename ,  'schedule_date' => $schedule_date, 'description' => $description , 'class_ids' => $class_ids , 'class_opt' => $class_opt , 'school_id' => $sclid , 'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                    }
                    elseif($class_opt == "single")
                    {
                        $class_ids = $this->request->data('s_classlist');
                        $student_ids = implode(",", $this->request->data['studentlist']);
                        $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'title' => $title ,  'attachment' => $filename ,  'schedule_date' => $schedule_date, 'description' => $description , 'student_ids' => $student_ids , 'class_ids' => $class_ids , 'class_opt' => $class_opt , 'school_id' => $sclid , 'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                    }
                    
                }
                elseif($notifyto == "parents")
                {
                    $class_opt = $this->request->data('classoptn');
                    /*$school_ids = $this->request->data('schoollist');
                    $class_ids = $this->request->data('s_classlist');*/
                   
                    if($class_opt == "multiple")
                    {
                        $class_ids = implode(",", $this->request->data['m_classlist']);
                        $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'title' => $title ,  'attachment' => $filename ,  'schedule_date' => $schedule_date, 'description' => $description , 'class_ids' => $class_ids , 'class_opt' => $class_opt , 'school_id' => $sclid , 'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                    }
                    elseif($class_opt == "single")
                    {
                        $class_ids = $this->request->data('s_classlist');
                        $parent_ids = implode(",", $this->request->data['parentlist']);
                        $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'class_opt' => $class_opt,  'title' => $title ,  'attachment' => $filename ,  'schedule_date' => $schedule_date, 'description' => $description , 'parent_ids' => $parent_ids , 'class_ids' => $class_ids , 'school_id' => $sclid , 'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                    }
                    
                    
                    
                }
                else
                {
                    $update = $notification_table->query()->update()->set(['sc_date_time' => $sc_date_time , 'title' => $title , 'schedule_date' => $schedule_date, 'description' => $description ,  'added_by' => $added_by , 'created_date' => $created_date , 'notify_to' => $notify_to , 'status' => $status ])->where([ 'id' => $notifyid  ])->execute();
                }
                
                if($update)
                {   
                    $notifyId = $notifyid;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Notification Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('id');
                    $activity->value = md5($notifyId); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;
                    
                    if($save)
                    {   
                        $res = [ 'result' => 'success'];
                    }
                    else
                    { 
                        $res = [ 'result' => 'activity'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'failed'  ];
                }
                 
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];

            }

            return $this->json($res);
        }
            
    
               
	   
}

  




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
class SchoolattendanceController  extends AppController
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
                $sclid = $this->Cookie->read('id');
                if($sclid == "")
                {
                    $compid = $this->request->session()->read('company_id');
                    $sclid = md5($compid);
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$class_table = TableRegistry::get('class');
				$attndnc_table = TableRegistry::get('attendance');
				$student_table = TableRegistry::get('student');
				$holiday_table = TableRegistry::get('holidays');
				$sessionid = $this->Cookie->read('sessionid');
				$retrieve_holiday = $holiday_table->find()->where([ 'md5(school_id) '=> $sclid])->toArray();
				if(!empty($sclid))
				{
    				$retrieve_class = $class_table->find()->where([ 'md5(school_id) '=> $sclid, 'active' => 1 ])->toArray();
    				
    				if(!empty($_POST))
                    {
                        $choosedate = $this->request->data('choosedate');
                        $classid = $this->request->data('class_list'); 
                        $studid = $this->request->data('studentdef'); 
                        $attdate = date("Y-m-d", strtotime($this->request->data('choosedate')));
                        
                            $retrieve_stdntlist = $student_table->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['session_id' => $sessionid,'md5(school_id)' => $sclid, 'class' => $classid])->toArray();
                            $retrieve_stdnt = $student_table->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['id' => $studid ])->toArray();
                            foreach($retrieve_stdnt as $stdnt)
                            {
                                $retrieve_attndnc = $attndnc_table->find()->where(['attendance.added_by' => 'school', 'attendance.class_id' => $classid, 'attendance.attdate' => $attdate, 'attendance.student_id' => $stdnt['id'] ])->first() ;
				        
        				        $stdnt->attendance = $retrieve_attndnc->attendance;
        				        $stdnt->attdate = $retrieve_attndnc->attdate;
        				        $stdnt->reason = $retrieve_attndnc->reason;
        				        $stdnt->added_by = $retrieve_attndnc->added_by;
                            }
					    
                    }
                    else
                    {
                        $choosedate = "";
                        $classid = "";
                        $retrieve_attndnc = "";
                        $retrieve_stdnt = "";
                        $studid = "";
                        $retrieve_stdntlist= "";
                    }
    				$this->set("holiday_details", $retrieve_holiday); 
    				$this->set("stud_details", $retrieve_stdnt); 
    				$this->set("studlist_details", $retrieve_stdntlist); 
    				$this->set("class_details", $retrieve_class); 
    				$this->set("studid", $studid); 
    				$this->set("classid", $classid); 
    				$this->set("chosedate", $choosedate); 
    				$this->set("attndnc_details", $retrieve_attndnc); 
    				$this->set("sessionid", $sessionid); 
    				$this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }
	
		    public function getsubjects()
            {   
                $sclid = $this->Cookie->read('id')   ;
                if($sclid == "")
                {
                    $compid = $this->request->session()->read('company_id');
                    $sclid = md5($compid);
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $clsid = $this->request->data('id')   ;
                $clssub_table = TableRegistry::get('class_subjects');
                $sub_table = TableRegistry::get('subjects');
                if(!empty($sclid))
                {
                    $retrieve_clssub = $clssub_table->find()->select(['subject_id'])->where(['status' => 1, 'md5(school_id)' => $sclid, 'class_id' => $clsid])->toArray() ;
                    $html = [];
                    //$html[] = '<option value="all">All</option>';
                    foreach($retrieve_clssub as $subget)
                    {
                        $subid = explode(",", $subget['subject_id']);
                        foreach($subid as $sub)
                        {
                            $retrieve_sub = $sub_table->find()->select(['subject_name', 'id'])->where(['id' => $sub])->toArray() ;
                            foreach($retrieve_sub as $getsub):
                                $html[] .= "<option value='".$getsub['id']."'>".$getsub['subject_name']."</option>";
                            endforeach;
                        }
                    }
                    
                    $res['data'] = $html;
                    
                    return $this->json($res);
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }
            
            public function studentlist()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
               	$sclid = $this->Cookie->read('id');
                if($sclid == "")
                {
                    $compid = $this->request->session()->read('company_id');
                    $sclid = md5($compid);
                }
                $student_list = TableRegistry::get('student');
                if(!empty($sclid))
                {
    				$classsub = explode(",",$this->request->data('classid'));
    				$classid = $classsub[0];
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['md5(school_id)' => $sclid, 'class' => $classid ])->toArray();
    				$data =  '<option value="">Choose Student</option>';
    				$stuids = [];
    				foreach($retrieve_stdnt as $stdntids)
    				{				
    					$stuids[] = $stdntids->id;				
    				}
    				$allstuids = implode(",", $stuids);
    				$data .=  '<option value="all'.$allstuids.'">All</option>';
    				foreach($retrieve_stdnt as $stdnt)
    				{				
    					$data .= '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';				
    				}
    				
    				return $this->json($data);  
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }
            
            public function imstudentlist()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
               	$tid = $this->Cookie->read('tid');
                $student_list = TableRegistry::get('student');
				$employee_list = TableRegistry::get('employee');
				if(!empty($tid))
				{
    				$classsub = explode(",",$this->request->data('classid'));
    				$classid = $classsub[0];
    				$retrieve_emp = $employee_list->find()->select([ 'id', 'school_id'])->where(['md5(id)' => $tid  ])->toArray();
    				$school_id = $retrieve_emp[0]['school_id'];
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['school_id' => $school_id, 'class' => $classid ])->toArray();
    				$data =  '<option selected="selected">Choose Student</option>';
    				foreach($retrieve_stdnt as $stdnt)
    				{				
    					$data .= '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';				
    				}
    				
    				return $this->json($data);   
                }
                else
                {
                    return $this->redirect('/login/') ;  
                } 
            }
            
           
            
    public function updateattandance()
    {
        if ($this->request->is('post') &&  $this->request->is('ajax'))
        {
            $sclid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $attndnc_table = TableRegistry::get('attendance');
            $cls_table = TableRegistry::get('class');
            $classsub_table = TableRegistry::get('class_subjects');
            $activ_table = TableRegistry::get('activity');
            if(($this->request->data('selecteddate') != "") && ($this->request->data('selectedclass') != "") && ($this->request->data('selectedstudent') != ""))
            {
                $classid = $this->request->data('selectedclass');
                $seldate = date("Y-m-d", strtotime($this->request->data('selecteddate')));
                $attendcount = $this->request->data['attendcount'];
                //print_r($attendcount);
                $retrieve_clssub = $classsub_table->find()->where(['class_id' => $classid, 'school_id' => $sclid ])->first();
                $subjectids = explode(",", $retrieve_clssub['subject_id']);
                //print_r($subjectids); die;
               
                foreach($attendcount as $key => $val)
                {
                    $studid = $this->request->data('studentid'.$key);
                    $reason = $this->request->data('reason'.$key);
                    foreach($subjectids as $subids)
                    {
                        //print_r($subids); die;
                        $retrieve_studattendance = $attndnc_table->find()->where(['class_id' => $classid, 'subject_id' => $subids, 'date' => $seldate, 'student_id' => $studid ])->first();
                        
                        if(empty($retrieve_studattendance))
                        {
                            $data = $attndnc_table->newEntity();
                                        
                            /*$data['reason'] = $reason;
                            $data['date'] = $seldate;
                            $data['title'] = $this->request->data('attendance'.$key);
                            $data['student_id'] = $studid;
                            $data['class_id'] = $classid;
                            $data['school_id'] = $this->request->session()->read('company_id');
                            $data['created_date'] = time();*/
                            
                            $data['reason'] = $reason;
                            $data['attdate'] = $seldate;
                            $data['date'] = $seldate;
                            $data['attendance'] = $this->request->data('attendance'.$key);
                            $data['title'] = $this->request->data('attendance'.$key);
                            $data['student_id'] = $studid;
                            $data['class_id'] = $classid;
                            $data['subject_id'] = $subids;
                            $data['added_by'] = "school";
                            $data['school_id'] = $this->request->session()->read('company_id');
                            $data['created_date'] = time();
                            
                            
                            if($saved = $attndnc_table->save($data) )
                            {
                                $updatecls = $cls_table->query()->update()->set(['attendance_status' => 1 ])->where(['id' => $classid ])->execute();
        					
                                $activity = $activ_table->newEntity();
                                $activity->action =  "successfully added attendance!"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->value = md5($saved->id)    ;
                                $activity->origin = $this->Cookie->read('tid')   ;
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
                                $res = ['result' => 'notsaved'];
                            }
                        } 
                        else
                        {
                            $res = [ 'result' => 'success' ];
                            /*$attnd = $this->request->data('attendance'.$key);
                            //echo $retrieve_studattendance['id']; 
                            $update = $attndnc_table->query()->update()->set(['reason' => $reason , 'title' => $attnd , 'date' => $seldate ])->where(['id' => $retrieve_studattendance['id'] ])->execute();
        					if($update)
        					{
            					$activity = $activ_table->newEntity();
                                $activity->action =  "successfully updated attendance!"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->value = md5($saved->id)    ;
                                $activity->origin = $this->Cookie->read('tid')   ;
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
                                $res = ['result' => 'notupdated'];
                            }*/
                        }
                    }
                } 
                
            }
            else
            {
                $res = ['result' => 'seldate'];
            }
        
        
        }
        else
        {
            $res = ['result' => 'invalid operation'];
        }
        return $this->json($res);
    }

            

}

  


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
use Dompdf\Dompdf;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ClassAttendanceController  extends AppController
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
        if(!empty($_GET))
        {
            $classid = $this->request->query('gradeid');
            $subjectid = $this->request->query('subid');
            $class_table = TableRegistry::get('class');
    		$subj_table = TableRegistry::get('subjects');
    		$attndnc_table = TableRegistry::get('attendance');
    		$student_list = TableRegistry::get('student');
            $tid = $this->Cookie->read('tid');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $holiday_table = TableRegistry::get('holidays');
			$sessionid = $this->Cookie->read('sessionid');
			$sclid = $this->request->session()->read('company_id');
			$retrieve_holiday = $holiday_table->find()->where([ 'school_id'=> $sclid])->toArray();
            if(!empty($tid))
            {
                $employee_table = TableRegistry::get('employee');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
			    $schoolid  = $retrieve_employees[0]['school_id'];
                $tchrid  = $retrieve_employees[0]['id'];
                
                
				$classSub = $classid.",".$subjectid;
    			foreach($retrieve_employees as $key =>$emp_coll)
        		{
        			$gradeid = explode(",",$emp_coll['grades']);
        			$subid = explode(",",$emp_coll['subjects']);
        			$i = 0;
        			$empgrades = [];
        			foreach($gradeid as $gid)
        			{
        			    $retrieve_class = $class_table->find()->where([ 'id '=> $gid ])->toArray();
        				foreach($retrieve_class as $grad)
        				{
        					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section']."(".$grad['school_sections'].")";				
        				}
        				$i++;
        				$gradenames = implode(",", $empgrades);
        				
        			}
        			$j = 0;
        			$empsub = [];
        			foreach($subid as $sid)
        			{
        			    $retrieve_subject = $subj_table->find()->where([ 'id '=> $sid ])->toArray();
        				foreach($retrieve_subject as $subj)
        				{
        					$empsub[$j] = $subj['subject_name'];				
        				}
        				$j++;
        				$subjectnames = implode(",", $empsub);
        				
        			}
        			
        			$emp_coll->subjectName = $subjectnames;
        			
        			$emp_coll->gradesName = $gradenames;
        		}	

				$this->set("employees_details", $retrieve_employees); 
            
                $compid = $this->request->session()->read('company_id');
                
        		$sessionid = $this->Cookie->read('sessionid');
        		
                $retrieve_class = $class_table->find()->where(['id' => $classid])->first() ;
                $retrieve_sub = $subj_table->find()->where(['id' => $subjectid])->first() ;
                $sclid = $retrieve_class['school_id'];
                $choosedate= "";		
                if(!empty($_POST))
                {
                    
                    $choosedate = $this->request->data('choosedate');
                    $attdate = date("Y-m-d", strtotime($this->request->data('choosedate')));
                    /*$retrieve_attndnc = $attndnc_table->find()->select(['attendance.id', 'attendance.class_id', 'attendance.reason',  'attendance.subject_id', 'attendance.student_id' , 'attendance.attdate', 'attendance.attendance', 'student.f_name', 'student.l_name', 'student.adm_no'])->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.id = attendance.student_id'
						]
					])->where(['attendance.subject_id' => $subjectid, 'attendance.attdate' => $attdate, 'attendance.class_id' => $classid, 'attendance.school_id' => $sclid])->order(['attendance.id' => 'desc'])->toArray() ;
                    */
                    
                    $retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['session_id' => $sessionid,'school_id' => $sclid, 'class' => $classid ])->toArray();
				    
				    foreach($retrieve_stdnt as $stdnt)
				    {
				        $retrieve_attndnc = $attndnc_table->find()->where(['attendance.subject_id' => $subjectid, 'attendance.attdate' => $attdate, 'attendance.student_id' => $stdnt['id'] ])->first() ;
				        
				        $stdnt->attendance = $retrieve_attndnc->attendance;
				        $stdnt->attdate = $retrieve_attndnc->attdate;
				        $stdnt->reason = $retrieve_attndnc->reason;
				        $stdnt->added_by = $retrieve_attndnc->added_by;
				    }
				    
				    //print_r($retrieve_stdnt); die;
                }
                else
                {
                   // $retrieve_attndnc ="";
                    $retrieve_stdnt = '';
                }
               
			    $this->set("holiday_details", $retrieve_holiday);
			    $this->set("classSub", $classSub);
			    $this->set("stud_details", $retrieve_stdnt);
                $this->set("class_details", $retrieve_class);
                $this->set("sub_details", $retrieve_sub);
                $this->set("chosedate", $choosedate);
                $this->set("classid", $classid);
                $this->set("subjectid", $subjectid);
                //$this->set("attndnc_details", $retrieve_attndnc);
                $this->viewBuilder()->setLayout('user');
            }
            else
            {
                return $this->redirect('/login/') ;    
            }
        }
    }
    
    public function updateattandance()
    {
        //echo "hi"; die;
        if ($this->request->is('post') &&  $this->request->is('ajax'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $cls_table = TableRegistry::get('class');
        $attndnc_table = TableRegistry::get('attendance');
        $sclattndnc_table = TableRegistry::get('attendance_school');
        $activ_table = TableRegistry::get('activity');
        if(!empty($_GET))
        {
            $classid = $this->request->query('gradeid');
            $subjectid = $this->request->query('subid');
            if(!empty($this->request->data('selecteddate')))
            {
                $seldate = date("Y-m-d", strtotime($this->request->data('selecteddate')));
                $attendcount = $this->request->data['attendcount'];
               
                foreach($attendcount as $key => $val)
                {
                    $studid = $this->request->data('studentid'.$key);
                    $reason = $this->request->data('reason'.$key);
                    
                    $retrieve_sclattendance = $sclattndnc_table->find()->where(['class_id' => $classid, 'date' => $seldate, 'student_id' => $studid ])->first();
                    if(empty($retrieve_sclattendance))
                    {
                        $datascl = $sclattndnc_table->newEntity();
                                        
                        $datascl['reason'] = $reason;
                        $datascl['date'] = $seldate;
                        $datascl['title'] = $this->request->data('attendance'.$key);
                        $datascl['student_id'] = $studid;
                        $datascl['class_id'] = $classid;
                        $datascl['school_id'] = $this->request->session()->read('company_id');
                        $datascl['created_date'] = time();
                        
                        $sclsaved = $sclattndnc_table->save($datascl);
                    }
                    
                    $retrieve_studattendance = $attndnc_table->find()->where(['class_id' => $classid, 'subject_id' => $subjectid, 'attdate' => $seldate, 'student_id' => $studid ])->first();
                   //print_r($retrieve_studattendance); die;
                    if(empty($retrieve_studattendance))
                    {
                         //echo "hi"; die;
                        $data = $attndnc_table->newEntity();
                                    
                        $data['reason'] = $reason;
                        $data['attdate'] = $seldate;
                        $data['date'] = $seldate;
                        $data['attendance'] = $this->request->data('attendance'.$key);
                        $data['title'] = $this->request->data('attendance'.$key);
                        $data['student_id'] = $studid;
                        $data['class_id'] = $classid;
                        $data['subject_id'] = $subjectid;
                        $data['school_id'] = $this->request->session()->read('company_id');
                        $data['created_date'] = time();
                        
                        //print_r($data);die;
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
                        if($retrieve_studattendance['added_by'] == "school")
                        {
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        {
                            
                            $attnd = $this->request->data('attendance'.$key);
                            //echo $retrieve_studattendance['id']; 
                            $update = $attndnc_table->query()->update()->set(['reason' => $reason,'attendance' => $attnd , 'title' => $attnd , 'attdate' => $seldate, 'date' => $seldate ])->where(['id' => $retrieve_studattendance['id'] ])->execute();
        				    
        					if($update)
        					{
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
                                $res = ['result' => 'notupdated'];
                            }
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
            return $this->redirect('/teacherattendance/') ;    
        }
        }
        else
        {
            $res = ['result' => 'invalid operation'];
        }
        
        //echo $res;
        return $this->json($res);
    }
            
            

}

  


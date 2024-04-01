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
class AttendanceReportController  extends AppController
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
                $class_table = TableRegistry::get('class');
        		$subj_table = TableRegistry::get('subjects');
        		$attndnc_table = TableRegistry::get('attendance');
        		$sclattndnc_table = TableRegistry::get('attendance_school');
        		$student_list = TableRegistry::get('student');
        		$session_table = TableRegistry::get('session');
        		$retrieve_session = $session_table->find()->toArray() ;
        		if(!empty($sclid)) {
            		$retrieve_class = $class_table->find()->where(['md5(school_id)' => $sclid])->toArray() ;
                    if(!empty($_POST))
                    {
                        $report_att = $this->request->data('report');
                        if($report_att == "class")
                        {
                            $todate = date("Y-m-d", strtotime($this->request->data('todate')));
                            $fromdate = date("Y-m-d", strtotime($this->request->data('fromdate')));
                            $chooseclass = $this->request->data('chooseclass');
                            $retrieve_attndnc = $sclattndnc_table->find()->select(['attendance_school.id', 'attendance_school.class_id', 'attendance_school.student_id' , 'attendance_school.date', 'attendance_school.title', 'student.f_name', 'student.l_name', 'student.adm_no', 'student.id'])->join(['student' => 
        							[
        							'table' => 'student',
        							'type' => 'LEFT',
        							'conditions' => 'student.id = attendance_school.student_id'
        						]
        					])->where(['attendance_school.date >=' => $fromdate, 'attendance_school.date <=' => $todate, 'attendance_school.class_id' => $chooseclass, 'md5(attendance_school.school_id)' => $sclid])->order(['attendance_school.date' => 'ASC'])->toArray() ;
        					
        					$studentid = "";
                        }
                        else
                        {
                            $todate = date("Y-m-d", strtotime($this->request->data('todate')));
                            $fromdate = date("Y-m-d", strtotime($this->request->data('fromdate')));
                            $chooseclass = $this->request->data('chooseclass');
                            $studentid = $this->request->data('studentdef');
                            $retrieve_attndnc = $sclattndnc_table->find()->select(['attendance_school.id', 'attendance_school.class_id', 'attendance_school.student_id' , 'attendance_school.date', 'attendance_school.title', 'student.f_name', 'student.l_name', 'student.adm_no', 'student.id'])->join(['student' => 
        							[
        							'table' => 'student',
        							'type' => 'LEFT',
        							'conditions' => 'student.id = attendance_school.student_id'
        						]
        					])->where(['attendance_school.student_id' => $studentid, 'attendance_school.date >=' => $fromdate, 'attendance_school.date <=' => $todate, 'attendance_school.class_id' => $chooseclass, 'md5(attendance_school.school_id)' => $sclid])->order(['attendance_school.date' => 'ASC'])->toArray() ;
                        }
                        
                        $retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['md5(school_id)' => $sclid, 'class' => $chooseclass ])->toArray();
                        
                    }
                    else
                    {
                        $todate = "";
                        $fromdate = "";
                        $chooseclass = "";
                        $studentid ="";
                        $retrieve_attndnc ="";
                        $retrieve_stdnt = "";
                        $report_att = "";
                    }
    
                    $this->set("todate", $todate);
                    $this->set("fromdate", $fromdate);
                    $this->set("classchoose", $chooseclass);
                    $this->set("studentid", $studentid);
                    $this->set("student_dtls", $retrieve_stdnt);
                    $this->set("attndnc_details", $retrieve_attndnc);
                    $this->set("class_details", $retrieve_class);
                    $this->set("report_att", $report_att);
                    $this->set("session_details", $retrieve_session);
                    $this->viewBuilder()->setLayout('user');
        		}
        		else
        		{
        		    return $this->redirect('/login/') ;    
        		}
            }
            
           

            
            public function studentlist()
            {
               	$sclid = $this->Cookie->read('id');
                $student_list = TableRegistry::get('student');
				$classid = $this->request->data('classid');
				$start_year = $this->request->data('start_year');
				if(!empty($sclid)) 
				{
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['md5(school_id)' => $sclid, 'class' => $classid, 'session_id' => $start_year ])->toArray();
    				$data = "";
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
            
            
            public function subject()
            {   
                
                $student_table = TableRegistry::get('student');
                $subjects_table = TableRegistry::get('subjects');
                $attndnc_table = TableRegistry::get('attendance');
                $clssub_table = TableRegistry::get('class_subjects');
                $cls_table = TableRegistry::get('class');
                
                $sid =$this->request->session()->read('student_id');
        		$session_id = $this->Cookie->read('sessionid');
        		$sclid = $this->Cookie->read('id'); 
        		if(!empty($sclid)) {
            		$classid = $this->request->data('classid');
            		$studentid = $this->request->data('studentid');
            		$date = $this->request->data('atdate');
            		
                    $retrieve_clssub = $clssub_table->find()->select(['id' ,'subject_id' ])->where(['class_id' => $classid, 'status' => 1, 'md5(school_id)' => $sclid])->toArray() ;	
                    $subid = explode(",",$retrieve_clssub[0]['subject_id']);
                 
        			$i = 0;
        			$subjectsname = [];
        			$attendance = [];
        			foreach($subid as $sid)
        			{
        			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;	
        				foreach($retrieve_subjects as $rstd)
        				{
        					$subjectsname[$i] = $rstd['subject_name'];				
        				}
        				$i++;
        				$snames = implode(",", $subjectsname);
        				$retrieve_attndnc = $attndnc_table->find()->where(['subject_id' => $sid, 'class_id' => $classid, 'student_id' => $studentid, 'attdate' => $date])->first() ;
        				if(!empty($retrieve_attndnc))
        				{
        				    $attendance[$i] = $retrieve_attndnc['attendance'];
        				}
        				else
        				{
        				    $attendance[$i] = "N/A";
        				}
        				$sattendnce = implode(",", $attendance);
        				
        				
        			}
        			$data['subjects'] = $snames;
        			$data['subattendnc'] = $sattendnce;
        			$retrieve_stdnt = $student_table->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['id' => $studentid])->toArray();
        			$retrieve_class = $cls_table->find()->select([ 'id', 'c_name', 'c_section'])->where(['id' => $classid])->toArray();
        			$data['studentname'] = $retrieve_stdnt[0]['f_name']." ".$retrieve_stdnt[0]['l_name'];
        			$data['classname'] = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
        			return $this->json($data); 
        		}
        		else
        		{
        		    return $this->redirect('/login/') ;    
        		}
        		
            }
            
            public function exportReport()
            {   
                $sclid = $this->Cookie->read('id');
                $class_table = TableRegistry::get('class');
        		$subj_table = TableRegistry::get('subjects');
        		$attndnc_table = TableRegistry::get('attendance');
        		$sclattndnc_table = TableRegistry::get('attendance_school');
        		$student_list = TableRegistry::get('student');
        		$subjects_table = TableRegistry::get('subjects');
        		
        		if(!empty($sclid)) {
        		$retrieve_class = $class_table->find()->where(['md5(school_id)' => $sclid])->toArray() ;
                if(!empty($_GET))
                {
                    if(!empty($_GET['report']) && !empty($_GET['todate']) && !empty($_GET['fromdate']) && !empty($_GET['classid']))
                    {
                        $report_att = $this->request->query('report');
                        if($report_att == "class")
                        {
                            $todate = date("Y-m-d", strtotime($this->request->query('todate')));
                            $fromdate = date("Y-m-d", strtotime($this->request->query('fromdate')));
                            $chooseclass = $this->request->query('classid');
                            $retrieve_attndnc = $sclattndnc_table->find()->select(['class.c_name', 'class.c_section', 'attendance_school.id', 'attendance_school.class_id', 'attendance_school.student_id' , 'attendance_school.date', 'attendance_school.title', 'student.f_name', 'student.l_name', 'student.adm_no'])->join(['student' => 
        							[
        							'table' => 'student',
        							'type' => 'LEFT',
        							'conditions' => 'student.id = attendance_school.student_id'
        						], 
        					'class' => 
    							[
        							'table' => 'class',
        							'type' => 'LEFT',
        							'conditions' => 'attendance_school.class_id = class.id'
        						]
        					])->where(['attendance_school.date >=' => $fromdate, 'attendance_school.date <=' => $todate, 'attendance_school.class_id' => $chooseclass, 'md5(attendance_school.school_id)' => $sclid])->order(['attendance_school.id' => 'desc'])->toArray() ;
        					
        					$studentid = "";
                        }
                        else
                        {
                            $todate = date("Y-m-d", strtotime($this->request->query('todate')));
                            $fromdate = date("Y-m-d", strtotime($this->request->query('fromdate')));
                            $chooseclass = $this->request->query('classid');
                            $studentid = $this->request->query('studentid');
                            $retrieve_attndnc = $sclattndnc_table->find()->select(['class.c_name', 'class.c_section', 'attendance_school.id', 'attendance_school.class_id', 'attendance_school.student_id' , 'attendance_school.date', 'attendance_school.title', 'student.f_name', 'student.l_name', 'student.adm_no'])->join([
                            'student' => 
    							[
        							'table' => 'student',
        							'type' => 'LEFT',
        							'conditions' => 'student.id = attendance_school.student_id'
        						], 
        					'class' => 
    							[
        							'table' => 'class',
        							'type' => 'LEFT',
        							'conditions' => 'attendance_school.class_id = class.id'
        						]
        					])->where(['attendance_school.student_id' => $studentid, 'attendance_school.date >=' => $fromdate, 'attendance_school.date <=' => $todate, 'attendance_school.class_id' => $chooseclass, 'md5(attendance_school.school_id)' => $sclid])->order(['attendance_school.id' => 'desc'])->toArray() ;
                        
                        }
                        
                        $clssub_table = TableRegistry::get('class_subjects');
                        $retrieve_clssub = $clssub_table->find()->select(['id' ,'subject_id' ])->where(['class_id' => $chooseclass, 'status' => 1, 'md5(school_id)' => $sclid])->toArray() ;	
                        $subid = explode(",",$retrieve_clssub[0]['subject_id']);
                        
                        $data = array();
                		foreach ($retrieve_attndnc as $key => $value) 
                		{
                            $date = $value['date'];
                			$i = 0;
                			$subjectsname = [];
                			$attendance = [];
                			foreach($subid as $sid)
                			{
                			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;	
                				foreach($retrieve_subjects as $rstd)
                				{
                					$subjectsname[$i] = $rstd['subject_name'];				
                				}
                				$studid = $value['student_id'];
                				$retrieve_attndnce = $attndnc_table->find()->where(['subject_id' => $sid, 'class_id' => $chooseclass, 'student_id' => $studid, 'attdate' => $date])->toArray() ;
                			    //print_r($retrieve_attndnce); die; 
                				if(!empty($retrieve_attndnce))
                				{
                				    foreach($retrieve_attndnce as $aa => $asd)
                				    {
                				        $attendance[$i] = $asd['attendance'];
                				    }
                				}
                				else
                				{
                				    $attendance[$i] = "N/A";
                				}
                				$i++;
                			}
                			
                			$headingdata = array('adm_no' => $value['student']['adm_no'], 'f_name' => $value['student']['f_name'], 'l_name' => $value['student']['l_name'], 'class' => $value['class']['c_name'] ." " .$value['class']['c_section'], 'attdate' => $value['date'], 'attendance' => $value['title']);
                        
                		    $data[] =  array_merge($headingdata,$attendance);
                		    
                		    //echo "<br>";
                        }
                        
                        //die;
            			
                		    //$filename  = $value['subjects']['subject_name']."(".$value['exams_assessments']['exam_type'].")_".$value['class']['c_name']."-".$value['class']['c_section']."_".$value['company']['comp_name'];
                		    
                	
        		
                        $filename = "Attendance_report.csv";
                        $this->setResponse($this->getResponse()->withDownload($filename));
                       
                        $headings = array('Student Id', 'First Name' , 'Last Name', 'Class', 'Attendance Date'  ,'Day Attendance');
                        $_header = array_merge($headings,$subjectsname);
                        $_serialize = 'data';
                        $this->viewBuilder()->setClassName('CsvView.Csv');
                        $this->set(compact('data', '_header' , '_serialize'));
        			
                    }
                    else
                    {
                        
                        $filename = "Attendance_report.csv";
                        $this->setResponse($this->getResponse()->withDownload($filename));
                        $data = array();
                        $_header = array('Student Id', 'First Name' , 'Last Name', 'Class', 'Attendance Date'  ,'Day Attendance', 'Subjects');
                        //$_header = array_merge($headings,$subjectsname);
                        $_serialize = 'data';
                        $this->viewBuilder()->setClassName('CsvView.Csv');
                        $this->set(compact('data', '_header' , '_serialize'));
                    }
                }
        		}
        		else
        		{
        		    return $this->redirect('/login/') ;    
        		}
                
            }
            
            

}

  


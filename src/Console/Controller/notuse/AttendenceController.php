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
class AttendenceController  extends AppController
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
				if ( $this->request->is('post'))
                {
					$class = $this->request->data('attclass');
					$date = $this->request->data('attdate');
					$sel_date = date("Y-m-d", strtotime($date));
					$student_table = TableRegistry::get('student');
					$schoolid =$this->request->session()->read('company_id');
					$attendence_table = TableRegistry::get('attendence');
					$class_list = TableRegistry::get('class');
				
					$session_id = $this->Cookie->read('sessionid');
				
					$retrieve_attendence = $attendence_table->find()->select([ 'id', 'c_name', 'section', 'attendence', 'att_date', 'adm_no', 'roll_num', 'student.s_name'])->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.adm_no = attendence.adm_no'
						]
					])->where(['attendence.school_id' => $schoolid, 'attendence.session_id'=>$session_id ,  'attendence.classId' => $class, 'attendence.att_date' => $sel_date])->toArray(); 
					
					$retrieve_class = $class_list->find()->select([ 'id', 'c_name', 'c_section'])->where([ 'session_id'=>$session_id ,  'school_id' => $schoolid, 'active' => 1   ])->toArray();
				

					//$retrieve_student_table = $student_table->find()->select(['id', 'adm_no' ,'s_name','mobile_no' ,'s_f_name' ,'s_m_name' , 'dob' ])->where(['school_id' => $compid ])->toArray() ;
					$this->set("class_list", $retrieve_class); 
					$this->set("attendence_list", $retrieve_attendence); 
					//$this->set("student_details", $retrieve_student_table); 
					$this->viewBuilder()->setLayout('user');
				}
				else
				{
					$student_table = TableRegistry::get('student');
					$schoolid =$this->request->session()->read('company_id');
					$attendence_table = TableRegistry::get('attendence');
					$class_list = TableRegistry::get('class');
					
					$session_id = $this->Cookie->read('sessionid');
					
					$retrieve_attendence = $attendence_table->find()->select([ 'id', 'c_name', 'section', 'attendence', 'att_date', 'adm_no', 'roll_num', 'student.s_name'])->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.adm_no = attendence.adm_no'
						]
					])->where(['attendence.school_id' => $schoolid , 'attendence.session_id'=>$session_id   ])->toArray(); 
				

					$retrieve_class = $class_list->find()->select([ 'id', 'c_name', 'c_section'])->where(['session_id'=>$session_id , 'school_id' => $schoolid, 'active' => 1   ])->toArray();
				

					//$retrieve_student_table = $student_table->find()->select(['id', 'adm_no' ,'s_name','mobile_no' ,'s_f_name' ,'s_m_name' , 'dob' ])->where(['school_id' => $compid ])->toArray() ;
					$this->set("class_list", $retrieve_class); 
					$this->set("attendence_list", $retrieve_attendence); 
					//$this->set("student_details", $retrieve_student_table); 
					$this->viewBuilder()->setLayout('user');
				}
                
            }
			
			public function importstudentattndn()
            {   
                if ( $this->request->is('post'))
                {   
			$schoolid = $this->request->session()->read('company_id');
			
			$session_id = $this->Cookie->read('sessionid');
					
                    $attendance_table = TableRegistry::get('attendence');
					$class_table = TableRegistry::get('class');
                    $activ_table = TableRegistry::get('activity');
                    //$school_table = TableRegistry::get('company');
                    
					/*$promote_class = $this->request->data('proclass');
					$session = $this->request->data('session');
					$retrieve_section = $class_table->find()->select(['c_section'])->where(['id' => $promote_class ])->toArray();
					foreach($retrieve_section as $section)
					{
						$section = $section->c_section;
					} */
					
                    if(!empty($this->request->data['file']['name']))
                    {
                        $fileexe = explode('.', $this->request->data['file']['name']);
                    
                        if($fileexe[1] =='csv')
                        {

                            $filename = $this->request->data['file']['tmp_name'];
                            
                            $handle = fopen($filename, "r");
                            
                            $header = fgetcsv($handle);
                            $i = 0;
                            
							while (($row = fgetcsv($handle)) !== FALSE) 
                            {		
						
                                $i++;
                                //$adm_no++;
                                $data = array();

                                $adm_no = $row[0];								
								$roll_no = $row[1];
								$attendence = $row[2];  
								$c_name = $row[3];  
								$section = $row[4]; 
								
								$attdncdate = date("Y-m-d", strtotime($row[5])); 
								//$attdncdate = date("Y-m-d", strtotime($row[6])); 
								$retrieve_classId = $class_table->find()->select(['id'])->where(['c_name' => $c_name, 'c_section' => $section , 'session_id'=>$session_id , 'school_id' => $schoolid  ])->toArray();
								foreach($retrieve_classId as $classId)
								{
									$classid = $classId->id;
								}
					
								/* if($adm_no != "" && $roll_no != "" && $attendence != "" && $class != "" && $section != "" && $session != "" && $date != "")
								{ */
									
									$attndnc = $attendance_table->newEntity();
									
									$attndnc->adm_no =  $adm_no;
									$attndnc->roll_num =  $roll_no;
									$attndnc->school_id =  $schoolid;
									$attndnc->classId =  $classid;
									$attndnc->c_name =  $c_name;
									$attndnc->section =  $section;
									
									$attndnc->attendence =  $attendence;
									$attndnc->att_date =  $attdncdate;
									$attndnc->created_date =  time();
									$attndnc->session_id = $session_id;
									
									$saved = $attendance_table->save($attndnc);
									
									//$promoted = $student_table->query()->update()->set([  'class' => $promote_class , 'c_section' => $section, 'c_sess_name' => $session ])->where(['adm_no' =>  $adm_no ])->execute();
									
									if($saved)
									{  
								
										$id = $saved->id;
										$updated = $attendance_table->query()->update()->set([  'c_name' => $c_name , 'classId' => $classid, 'att_date' => $attdncdate ])->where(['id' =>  $id ])->execute();
										$res = [ 'result' => 'success'  ];
										//return $this->redirect('/promotion');
									}
									else{
										$res = [ 'result' => 'Error! File not uploaded.'  ];
									}
								/*} 
								else
								{
									$res = [ 'result' => 'Error! File not uploaded. (Tabs should not blank)'  ];
								} */ 
                                 
								

								


                            }
							
                        fclose($handle);
                        
                        }
                        else
                        {
                            $res = [ 'result' => 'Only csv format file are allowed'];

                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Empty file'  ];

                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);    

            }
			
			public function view($id)
            {   
                $schoolid = $this->request->session()->read('company_id');
					
				$attendence_table = TableRegistry::get('attendence');
				$class_table = TableRegistry::get('class');
				$activ_table = TableRegistry::get('activity');
                

                $retrieve_attendence = $attendence_table->find()->select([ 'id', 'c_name', 'section', 'attendence', 'att_date', 'adm_no', 'roll_num', 'student.s_name'])->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.adm_no = attendence.adm_no'
						]
					])->where(['attendence.school_id' => $schoolid, ' md5(attendence.id)' => $id  ])->toArray(); 			

				$this->set("attendence_view", $retrieve_attendence); 
				$this->viewBuilder()->setLayout('user');

                
                
            } 
			public function edit($id)
            {   
                $schoolid = $this->request->session()->read('company_id');
					
				$attendence_table = TableRegistry::get('attendence');
				$class_table = TableRegistry::get('class');
				$activ_table = TableRegistry::get('activity');
				$student_tbl = TableRegistry::get('student');

                		$session_id = $this->Cookie->read('sessionid');

				$retrieve_class = $class_table->find()->select([ 'id', 'c_name', 'c_section'])->where(['session_id'=>$session_id  , 'school_id' => $schoolid, 'active' => 1   ])->group(['c_name'])->toArray();
				
                $retrieve_attendence = $attendence_table->find()->select([ 'id', 'c_name', 'classId', 'section', 'attendence', 'att_date', 'adm_no', 'roll_num', 'student.s_name'])->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.adm_no = attendence.adm_no'
						]
					])->where(['attendence.school_id' => $schoolid, ' md5(attendence.id)' => $id  ])->toArray(); 

				foreach($retrieve_attendence as $sectn)
				{
					$classsectns = $sectn->c_name;
					$classId = $sectn->classId;
				}
				
				$retrieve_section = $class_table->find()->select([ 'id', 'c_name', 'c_section'])->where(['session_id'=>$session_id  , 'school_id' => $schoolid, 'c_name' => $classsectns   ])->toArray();
				
				$retrieve_stdnt = $student_tbl->find()->select([ 'id', 'adm_no', 's_name'])->where(['session_id'=>$session_id  , 'school_id' => $schoolid, 'class' => $classId   ])->toArray();
				
				$this->set("class_list", $retrieve_class); 
				$this->set("student_list", $retrieve_stdnt); 
				$this->set("class_sec", $retrieve_section); 
				$this->set("attendence_view", $retrieve_attendence); 
				$this->viewBuilder()->setLayout('user');

                
                
            } 
			
			public function section()
			{
				
				$this->autorender = false;
				$this->layout = 'ajax';
				$school_id =$this->request->session()->read('company_id');
				
				$session_id = $this->Cookie->read('sessionid');
				
				$attendence_tbl = TableRegistry::get('attendence');
				$class_table = TableRegistry::get('class');
				$clName = $this->request->data('className');
				$retrieve_sectn = $class_table->find()->select([ 'id', 'c_section'])->where([ 'session_id'=>$session_id ,  'school_id' => $school_id, 'c_name' => $clName ])->toArray();
				echo '<option selected="selected">Choose Section</option>';
				foreach($retrieve_sectn as $sectn)
				{				
					echo '<option value="'.$sectn->id.'">'.$sectn->c_section.'</option>';				
				}
				
				
			}
			
			public function student(){
				$this->autorender = false;
				$this->layout = 'ajax';
				$school_id =$this->request->session()->read('company_id');

				$session_id = $this->Cookie->read('sessionid');

				$student_list = TableRegistry::get('student');
				
				$classid = $this->request->data('classId');
				$retrieve_stdnt = $student_list->find()->select([ 'id', 's_name'])->where(['session_id'=>$session_id ,'school_id' => $school_id, 'class' => $classid ])->toArray();
				echo '<option selected="selected">Choose Student</option>';
				foreach($retrieve_stdnt as $stdnt)
				{				
					echo '<option value="'.$stdnt->id.'">'.$stdnt->s_name.'</option>';				
				}
				
				
			}
			
			public function studentinfo(){
				$this->autorender = false;
				$this->layout = 'ajax';
				
				$school_id =$this->request->session()->read('company_id');
				
				$student_list = TableRegistry::get('student');

				$session_id = $this->Cookie->read('sessionid');
				
				$stdId = $this->request->data('stdId');
				$retrieve_stdnt = $student_list->find()->select([ 'id', 'adm_no', 'roll_no'])->where(['session_id'=>$session_id ,'school_id' => $school_id, 'id' => $stdId ])->toArray();
				
				foreach($retrieve_stdnt as $stdnt)
				{		
						$res['roll_no'] =  $stdnt->roll_no;	
						$res['adm_no'] =  $stdnt->adm_no;
					//echo '<option value="'.$stdnt->id.'">'.$stdnt->s_name.'</option>';				
				}
				
				 return $this->json($res);
				
				
			}
			
			public function update()
			{
				if ($this->request->is('post'))
				{
					
					$schoolid = $this->request->session()->read('company_id');
					
					$session_id = $this->Cookie->read('sessionid');
					
					$attendence_table = TableRegistry::get('attendence');			
					$class_table = TableRegistry::get('class');
					$attendnce = $attendence_table->newEntity();
					
					 $attId = $this->request->data('attendnc_id') ;
					
					//$defaulter->school_id = $school_id;
					 $school_id = $schoolid;
					
					 $class_id = $this->request->data('attsection') ;
					 $adm_no =  $this->request->data('adm_no')  ;
					 $roll_num =  $this->request->data('roll_num')  ;
					 $attendance =  $this->request->data('attendance')  ;
					 $att_date =  $this->request->data('att_date')  ;
					 $class = $this->request->data('classatt')  ;
					
					$retrieve_sectn = $class_table->find()->select([ 'id', 'c_section'])->where(['session_id'=>$session_id ,'school_id' => $school_id, 'id' => $class_id ])->toArray();
					
					foreach($retrieve_sectn as $sec)
					{		
						 $sectn = $sec->c_section;
					}
					$update = $attendence_table->query()->update()->set(['classId' => $class_id , 'adm_no' => $adm_no, 'roll_num' => $roll_num, 'attendence' => $attendance,  'att_date' => $att_date,  'c_name' => $class , 'section' => $sectn ])->where(['id' =>  $attId ])->execute();
					
					
					if($update )
					{						
						$res = [ 'result' => 'success'  ];
						//return $this->redirect('/defaulter');			

					}
					else{
						$res = [ 'result' => 'Attendance not updated'  ];
					}                  

				}
				else
				{
					$res = [ 'result' => 'invalid operation'  ];

				}


				return $this->json($res);	
			}
            

            

}

  


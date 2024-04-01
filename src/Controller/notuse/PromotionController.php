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
class PromotionController  extends AppController
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
                $student_table = TableRegistry::get('student');
                $compid =$this->request->session()->read('company_id');
				$class_list = TableRegistry::get('class');
				$session_list = TableRegistry::get('session');

				$session_id = $this->Cookie->read('sessionid');
					
				$retrieve_class = $class_list->find()->select([ 'id', 'c_name', 'c_section'])->where(['school_id' => $compid,  'session_id' => $session_id ,  'active' => 1   ])->toArray(); 
			

                $retrieve_student_table = $student_table->find()->select(['id', 'adm_no' ,'s_name','mobile_no' ,'s_f_name' ,'s_m_name' , 'dob' ])->where(['school_id' => $compid ,  'session_id' => $session_id])->toArray() ;
                
				$retrieve_session = $session_list->find()->select([ 'id', 'startyear', 'endyear'])->toArray(); 

				$this->set("class_list", $retrieve_class); 
						$this->set("student_details", $retrieve_student_table); 
						$this->set("session_details", $retrieve_session);
				$this->set('session_id', $this->Cookie->read('sessionid')); 
				$this->viewBuilder()->setLayout('user');
            }
			
			public function importpromotestdnt()
            {   
                if ( $this->request->is('post'))
                {   
                    $student_table = TableRegistry::get('student');
					$class_table = TableRegistry::get('class');
                    $activ_table = TableRegistry::get('activity');
                    $school_table = TableRegistry::get('company');
					$balance_table = TableRegistry::get('balance');
                    $compid = $this->request->session()->read('company_id');
					$promote_class = $this->request->data('proclass');
					$new_session = $this->request->data('session_id'); 
					$retrieve_section = $class_table->find()->select(['c_section'])->where(['id' => $promote_class ])->toArray();
					foreach($retrieve_section as $section)
					{
						$section = $section->c_section;
					}
					
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
								//print_r($row);
					 
                                $i++;
                                //$adm_no++;
                                $data = array();

                                $adm_no = $row[0];                                 
								if($adm_no != "")
								{
									
									
									/*$promoted = $student_table->query()->update()->set([  'class' => $promote_class , 'c_section' => $section, 'c_sess_name' => $session ])->where(['adm_no' =>  $adm_no ])->execute();
									
									if($promoted )
									{  
										$res = [ 'result' => 'success'  ];
										//return $this->redirect('/promotion');
									}
									else{
										$res = [ 'result' => 'Error! File not uploaded.'  ];
									} */
									
									
									$retrieve_student_detail = $student_table->find()->select(['roll_no','adm_no', 'acc_no' , 's_name' , 's_f_name', 's_m_name' ,'resi_add1', 'mobile_no' , 'guardian_name' , 'email', 'password', 'aadhar' , 'bank_acc_no', 'ifsc', 'bloodgroup' , 'stopage' , 'dob' , 'national' , 'state' , 'city' ,'pic' , 'gender' , 'cat' , 'n_cat' , 'stud_left' , 'left_dt' , 's_age' , 'dis_code' , 'boarder' , 'a_route_no' , 'd_route_no' , 'sibl_in_scl' , 'adm_dt', 'mobile_for_sms' , 'dnd', 'srn_no' , 'school_id' , 'gr1_path' , 'gr2_path' , 'gr3_path'  ])->where(['adm_no' => $adm_no ])->last();
                    
									$retrieve_student = $student_table->find()->select(['id'])->where(['email' => $retrieve_student_detail['email'] , 'school_id'=> $retrieve_student_detail['school_id'] , 'session_id'=> $new_session ])->count() ;
							
										if($retrieve_student == 0)
										{    

											if($adm_no != "")
											{
												$newrollno = $student_table->find()->select(['roll_no'])->where(['class' => $this->request->data('promclass') , 'school_id'=>$compid , 'session_id'=> $new_session ])->first();

												if(!empty($newrollno)){
												  $student_roll_no = $newrollno['roll_no'];
												}
												else{
												   $student_roll_no = 0;    
												}
												$student_roll_no++;

												$student = $student_table->newEntity();
												$student->adm_no =  $retrieve_student_detail['adm_no'];
												$student->s_name =  $retrieve_student_detail['s_name']  ;
												$student->pic =  $retrieve_student_detail['pic']  ;
												$student->s_f_name = $retrieve_student_detail['s_f_name'] ;
												$student->s_m_name = $retrieve_student_detail['s_m_name'] ;
												$student->guardian_name = $retrieve_student_detail['guardian_name'] ;
												$student->dob = date('Y-m-d', strtotime($retrieve_student_detail['dob']));
												$student->adm_dt = date('Y-m-d', strtotime($retrieve_student_detail['adm_dt'])) ;
												$student->bloodgroup = $retrieve_student_detail['bloodgroup'] ;
												$student->cat = $retrieve_student_detail['cat'] ;
												$student->n_cat = $retrieve_student_detail['n_cat'] ;
												$student->f_occ = $retrieve_student_detail['f_occ'] ;
												$student->acc_no = $retrieve_student_detail['acc_no'] ;
												$student->class = $promote_class;
												$student->roll_no = $retrieve_student_detail['roll_no'] ;  
												$student->national = $retrieve_student_detail['national'] ;
												$student->resi_add1 = $retrieve_student_detail['resi_add1'] ;
												$student->state = $retrieve_student_detail['state'] ;
												$student->city = $retrieve_student_detail['city'] ;
												$student->srn_no = $retrieve_student_detail['srn_no'] ;
												$student->gender =  $retrieve_student_detail['gender']  ;
												$student->mobile_for_sms = $retrieve_student_detail['mobile_for_sms']  ;
												$student->aadhar =  $retrieve_student_detail['aadhar']  ;
												$student->dis_code =  $retrieve_student_detail['dis_code']  ;
												$student->boarder =  $retrieve_student_detail['boarder']  ;
												$student->s_age =  $retrieve_student_detail['s_age']  ;
												$student->a_route_no =  $retrieve_student_detail['a_route_no']  ;
												$student->d_route_no =  $retrieve_student_detail['d_route_no']  ;
												$student->stopage =  $retrieve_student_detail['stopage']  ;
												$student->sibl_in_scl =  $retrieve_student_detail['sibl_in_scl']  ;
												$student->email =  $retrieve_student_detail['email']  ;
												$student->password = $retrieve_student_detail['password'] ;
												$student->bank_acc_no =  $retrieve_student_detail['bank_acc_no']  ;
												$student->ifsc =  $retrieve_student_detail['ifsc']  ;
												$student->dnd =  date('Y-m-d', strtotime($retrieve_student_detail['dnd'])) ;
												$student->stud_left =  $retrieve_student_detail['stud_left']  ;
												$student->left_dt =  date('Y-m-d', strtotime($retrieve_student_detail['left_dt']))  ;
												$student->school_id = $retrieve_student_detail['school_id'] ;
												$student->gr1_path = $retrieve_student_detail['gr1_path'];
												$student->gr2_path = $retrieve_student_detail['gr2_path'];
												$student->gr3_path = $retrieve_student_detail['gr3_path'];
												$student->session_id = $new_session;
												$student->prefix = "Old";
												
												

												if($saved = $student_table->save($student) )
												{   
													$newstdntid = $saved->id;

													$remainbal = $balance_table->find()->select(['bal_amt'])->where(['acc_no' => $retrieve_student_detail['acc_no'] , 'school_id'=>$compid , 'session_id'=> $new_session ])->first();

													if(empty($remainbal)){
														$remainbal['bal_amt'] = 0;
													}    
														$balance = $balance_table->newEntity();

														$balance->acc_no =  $retrieve_student_detail['acc_no'] ;
														$balance->s_name =  $retrieve_student_detail['s_name'];
														$balance->s_f_name = $retrieve_student_detail['s_f_name'] ;
														$balance->bal_amt = $remainbal['bal_amt'] ;
														$balance->school_id = $retrieve_student_detail['school_id'];
														$balance->session_id = $new_session;

														if($savedbal = $balance_table->save($balance) )
														{
														
															$activity = $activ_table->newEntity();
															$activity->action =  "Student Promoted"  ;
															$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
										
															$activity->value = md5($newstdntid)   ;
															$activity->origin = $this->Cookie->read('id')   ;
															$activity->created = strtotime('now');

															if($saved = $activ_table->save($activity) ){
																$res = [ 'result' => 'success'  ];
									
															}
															else{
																$res = [ 'result' => 'activity not saved'  ];
									
															}
														}
														else{
															$res = [ 'result' => 'balance not update'  ];
														}
													   
							
												}
												else{
													$res = [ 'result' => 'student not saved'  ];
												}

											}
											else{
												$res = [ 'result' => 'error'  ];
											}     

										}
										else{
											$res = [ 'result' => 'exist'  ];
										}    
								} 

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

	    
	    public function addpromote()
            {   
                if ( $this->request->is('post'))
                {   
                    $student_table = TableRegistry::get('student');
                    $class_table = TableRegistry::get('class');
                    $activ_table = TableRegistry::get('activity');
                    $school_table = TableRegistry::get('company');
                    $balance_table = TableRegistry::get('balance');
                    $compid = $this->request->session()->read('company_id');
               
                    $session_id = $this->Cookie->read('sessionid');
               	
                    $studentId =  $this->request->data('studentdef');
                    $promote_class = $this->request->data('promclass');
                    $new_session_id = $this->request->data('session_id');
                

                    $retrieve_student_detail = $student_table->find()->select(['adm_no', 'acc_no' , 's_name' , 's_f_name', 's_m_name' ,'resi_add1', 'mobile_no' , 'guardian_name' , 'email', 'password', 'aadhar' , 'bank_acc_no', 'ifsc', 'bloodgroup' , 'stopage' , 'dob' , 'national' , 'state' , 'city' ,'pic' , 'gender' , 'cat' , 'n_cat' , 'stud_left' , 'left_dt' , 's_age' , 'dis_code' , 'boarder' , 'a_route_no' , 'd_route_no' , 'sibl_in_scl' , 'adm_dt', 'mobile_for_sms' , 'dnd', 'srn_no' , 'school_id' , 'gr1_path' , 'gr2_path' , 'gr3_path'  ])->where(['id' => $studentId ])->first();
                    
	            $retrieve_student = $student_table->find()->select(['id'])->where(['email' => $retrieve_student_detail['email'] , 'school_id'=> $retrieve_student_detail['school_id'] , 'session_id'=> $new_session_id ])->count() ;
		
                    if($retrieve_student == 0)
                    {    

                        if($studentId != "")
                        {
                            $newrollno = $student_table->find()->select(['roll_no'])->where(['class' => $this->request->data('promclass') , 'school_id'=>$compid , 'session_id'=> $new_session_id ])->first();

                            if(!empty($newrollno)){
                              $student_roll_no = $newrollno['roll_no'];
                            }
                            else{
                               $student_roll_no = 0;    
                            }
                            $student_roll_no++;

                            $student = $student_table->newEntity();
                            $student->adm_no =  $retrieve_student_detail['adm_no'];
                            $student->s_name =  $retrieve_student_detail['s_name']  ;
                            $student->pic =  $retrieve_student_detail['pic']  ;
                            $student->s_f_name = $retrieve_student_detail['s_f_name'] ;
                            $student->s_m_name = $retrieve_student_detail['s_m_name'] ;
                            $student->guardian_name = $retrieve_student_detail['guardian_name'] ;
                            $student->dob = date('Y-m-d', strtotime($retrieve_student_detail['dob']));
                            $student->adm_dt = date('Y-m-d', strtotime($retrieve_student_detail['adm_dt'])) ;
                            $student->bloodgroup = $retrieve_student_detail['bloodgroup'] ;
                            $student->cat = $retrieve_student_detail['cat'] ;
                            $student->n_cat = $retrieve_student_detail['n_cat'] ;
                            $student->f_occ = $retrieve_student_detail['f_occ'] ;
                            $student->acc_no = $retrieve_student_detail['acc_no'] ;
                            $student->class = $this->request->data('promclass');
                            $student->roll_no = $student_roll_no ;    
                            $student->national = $retrieve_student_detail['national'] ;
                            $student->resi_add1 = $retrieve_student_detail['resi_add1'] ;
                            $student->state = $retrieve_student_detail['state'] ;
                            $student->city = $retrieve_student_detail['city'] ;
                            $student->srn_no = $retrieve_student_detail['srn_no'] ;
                            $student->gender =  $retrieve_student_detail['gender']  ;
                            $student->mobile_for_sms = $retrieve_student_detail['mobile_for_sms']  ;
                            $student->aadhar =  $retrieve_student_detail['aadhar']  ;
                            $student->dis_code =  $retrieve_student_detail['dis_code']  ;
                            $student->boarder =  $retrieve_student_detail['boarder']  ;
                            $student->s_age =  $retrieve_student_detail['s_age']  ;
                            $student->a_route_no =  $retrieve_student_detail['a_route_no']  ;
                            $student->d_route_no =  $retrieve_student_detail['d_route_no']  ;
                            $student->stopage =  $retrieve_student_detail['stopage']  ;
                            $student->sibl_in_scl =  $retrieve_student_detail['sibl_in_scl']  ;
                            $student->email =  $retrieve_student_detail['email']  ;
                            $student->password = $retrieve_student_detail['password'] ;
                            $student->bank_acc_no =  $retrieve_student_detail['bank_acc_no']  ;
                            $student->ifsc =  $retrieve_student_detail['ifsc']  ;
                            $student->dnd =  date('Y-m-d', strtotime($retrieve_student_detail['dnd'])) ;
                            $student->stud_left =  $retrieve_student_detail['stud_left']  ;
                            $student->left_dt =  date('Y-m-d', strtotime($retrieve_student_detail['left_dt']))  ;
                            $student->school_id = $retrieve_student_detail['school_id'] ;
                            $student->gr1_path = $retrieve_student_detail['gr1_path'];
                            $student->gr2_path = $retrieve_student_detail['gr2_path'];
                            $student->gr3_path = $retrieve_student_detail['gr3_path'];
                            $student->session_id = $new_session_id;
							$student->prefix = "Old";

                            if($saved = $student_table->save($student) )
                            {   
                                $newstdntid = $saved->id;

                                $remainbal = $balance_table->find()->select(['bal_amt'])->where(['acc_no' => $retrieve_student_detail['acc_no'] , 'school_id'=>$compid , 'session_id'=> $session_id ])->first();

                                if(empty($remainbal)){
                                    $remainbal['bal_amt'] = 0;
                                }    
                                    $balance = $balance_table->newEntity();

                                    $balance->acc_no =  $retrieve_student_detail['acc_no'] ;
                                    $balance->s_name =  $retrieve_student_detail['s_name'];
                                    $balance->s_f_name = $retrieve_student_detail['s_f_name'] ;
                                    $balance->bal_amt = $remainbal['bal_amt'] ;
                                    $balance->school_id = $retrieve_student_detail['school_id'];
                                    $balance->session_id = $new_session_id;

                                    if($savedbal = $balance_table->save($balance) )
                                    {
                                    
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Student Promoted"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($newstdntid)   ;
                                        $activity->origin = $this->Cookie->read('id')   ;
                                        $activity->created = strtotime('now');

                                        if($saved = $activ_table->save($activity) ){
                                            $res = [ 'result' => 'success'  ];
                
                                        }
                                        else{
                                            $res = [ 'result' => 'activity not saved'  ];
                
                                        }
                                    }
                                    else{
                                        $res = [ 'result' => 'balance not update'  ];
                                    }
                                   
        
                            }
                            else{
                                $res = [ 'result' => 'student not saved'  ];
                            }

                        }
                        else{
                            $res = [ 'result' => 'error'  ];
                        }     

                    }
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }    
                        
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);  
            }           

            public function edit($id)
            {   
                $student_table = TableRegistry::get('student');
                $school_table = TableRegistry::get('company');
                
                $retrieve_student = $student_table->find()->select(['id' ,'adm_no' , 's_name' , 's_f_name' , 's_m_name' , 'resi_add1' , 'mobile_no' , 'email' , 'password' , 'dob' , 'national' , 'gender' , 's_age' ,  'pic' , 'school_id' ])->where([ 'md5(id)' => $id  ])->toArray() ;


                $retrieve_school = $school_table->find()->select(['id' , 'comp_name'   ])->where([ 'status' => '1'  ])->toArray() ;

                
                $this->set("student_details", $retrieve_student);  
                $this->set("school_details", $retrieve_school);
                $this->viewBuilder()->setLayout('user');
            } 


            public function editstdnt()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $student_table = TableRegistry::get('student');
                    $activ_table = TableRegistry::get('activity');

                    $retrieve_student = $student_table->find()->select(['id'  ])->where(['email' => $this->request->data('email') , 'id <>' => $this->request->data('id') , 'school_id'=> $this->request->data('school_id') ])->count() ;
                    
                    if($retrieve_student == 0 )
                    {   
                        if(!empty($this->request->data['picture']['name']))
                        {   
                            if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                            {
                                $picture =  $this->request->data['picture']['name'];
                                $filename = $this->request->data['picture']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['picture']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['picture'] = $filename; 
                                }
                            }    
                        }else
                        {
                            $picture =  $this->request->data('apicture');
                        }
                        
                        if(!empty($picture))
                        {

                            $stdntid = $this->request->data('id');
                            $adm_no = $this->request->data('adm_no');
                            $s_name = $this->request->data('s_name');
                            $resi_add1 = $this->request->data('resi_add1');
                            $national = $this->request->data('national');
                            $s_f_name = $this->request->data('s_f_name');
                            $s_m_name = $this->request->data('s_m_name');
                            $email = $this->request->data('email');
                            $password = $this->request->data('password');
                            $mobile_no = $this->request->data('mobile_no');
                            $dob = date('Y-m-d', strtotime($this->request->data('dob')));
                            $gender = $this->request->data('gender');
                            $s_age = $this->request->data('s_age');
                            $birth_plac = $this->request->data('birth_plac');
                       
                            
                            if( $student_table->query()->update()->set([ 'adm_no' => $adm_no , 's_name' => $s_name, 'resi_add1' => $resi_add1, 'email' => $email,  'mobile_no' => $mobile_no, 'password' => $password , 'dob' => $dob , 'national' => $national , 'pic'=> $picture , 's_f_name' => $s_f_name , 's_m_name' => $s_m_name ,'gender'=> $gender, 's_age'=>$s_age , 'birth_plac'=>$birth_plac ])->where([ 'id' => $stdntid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Student update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($stdntid); 
                                $activity->created = strtotime('now');
                                $save = $activ_table->save($activity) ;

                                if($save)
                                {   
                                    $res = [ 'result' => 'success'  ];
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
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }    
                    }
                    else
                    {
                        $res = [ 'result' => 'A student with this email address is already exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            } 

             public function export()
            {   
                $student_table = TableRegistry::get('student');
                $compid =$this->request->session()->read('company_id');

                $data = $student_table->find()->select(['id' ,'adm_no' ,'s_name', 's_f_name', 's_m_name', 'resi_add1' ,'mobile_no', 'email' , 'national' , 'gender' ])->where(['school_id' => $compid ])->toArray() ;  
                
                
                $this->setResponse($this->getResponse()->withDownload('file.csv'));
                $_header = array('Id', 'Admission Number' , 'Student Name' , 'Father Name' , 'Mother Name' , 'Address' ,'Mobile Number' , 'Email' , 'Nationality' , 'Gender');
                $_serialize = 'data';
                $this->viewBuilder()->setClassName('CsvView.csv');
                $this->set(compact('data', '_header' , '_serialize'));
            }
			
			public function delete()
            {
				
                $sid = $this->request->data('val') ;
                $student_table = TableRegistry::get('student');
                
                
                $sclid = $student_table->find()->select(['id'])->where(['id'=> $sid ])->first();
                if($sclid)
                {   
                    
					$del = $student_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                    if($del)
                    {
                        $res = [ 'result' => 'success'  ];
                        
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


            public function view($id)
            {   
                $student_table = TableRegistry::get('student');
                $role_table = TableRegistry::get('roles');
                $school_table = TableRegistry::get('company');
                

                $retrieve_student = $student_table->find()->select(['id' ,'adm_no' , 's_name' , 's_f_name' , 's_m_name' , 'resi_add1' , 'mobile_no' , 'email' , 'password' , 'dob' , 'national' , 'gender' , 's_age' , 'birth_plac' , 'pic' , 'school_id' ])->where([ 'md5(id)' => $id  ])->toArray() ;

                $retrieve_role = $role_table->find()->select(['id' , 'name' ])->where([ 'status' => 1  ])->toArray() ;

                $retrieve_school = $school_table->find()->select(['id' , 'comp_name'   ])->where([ 'status' => '1'  ])->toArray() ;

                
                $this->set("role_details", $retrieve_role); 
                $this->set("student_details", $retrieve_student);  
                $this->set("school_details", $retrieve_school);
                $this->viewBuilder()->setLayout('user');
            } 

            public function getcity()
            {   
                if($this->request->is('post'))
                {

                $id = $this->request->data['id'];

                $city_table = TableRegistry::get('cities');

                $get_city = $city_table->find()->select([ 'id' , 'name']) ->where(['state_id' => $id])->toArray(); 
 
                return $this->json($get_city);

                }  
            }

            

}

  


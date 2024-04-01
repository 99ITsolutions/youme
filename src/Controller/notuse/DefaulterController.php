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
//use \stdClass;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class DefaulterController  extends AppController
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
		public function index(){		
			
			
			$school_id =$this->request->session()->read('company_id');
			
			$class_list = TableRegistry::get('class');
			$defaulter_list = TableRegistry::get('defaulter_list');
			$student_list = TableRegistry::get('student');
			
			$session_id = $this->Cookie->read('sessionid');
	
			$retrieve_def = $defaulter_list->find()->select(['class.c_name' , 'class.c_section' , 'student.s_name', 'student.s_f_name', 'student.mobile_no' , 'student.adm_no', 'defaulter_list.default_reason'  , 'defaulter_list.id' , 'student.id', 'class.id', 'defaulter_list.created_date' ])->join(['student' => 
                        [
                        'table' => 'student',
                        'type' => 'LEFT',
                        'conditions' => 'student.id = defaulter_list.student_id'
                    ]
                ])->join(['class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = defaulter_list.class_id'
                    ]
                ])->where([ 'defaulter_list.school_id '=> $school_id , 'defaulter_list.session_id '=> $session_id ])->toArray() ;
				
				
			//$retrieve_def = $defaulter_list->find()->select([ 'id', 'c_name', 'c_section'])->where(['school_id' => $school_id, 'active' => 1   ])->toArray(); 

			$this->set("default_list", $retrieve_def);   
			$this->viewBuilder()->setLayout('user');

		}        
            

        public function add(){
			$school_id =$this->request->session()->read('company_id');
			$class_list = TableRegistry::get('class');
			
			$session_id = $this->Cookie->read('sessionid');

			$retrieve_class = $class_list->find()->select([ 'id', 'c_name', 'c_section'])->where(['session_id' => $session_id ,'school_id' => $school_id, 'active' => 1   ])->toArray(); 
			$this->set("class_list", $retrieve_class); 
			$this->viewBuilder()->setLayout('user');
			
		}
		public function student(){
			$this->autorender = false;
			$this->layout = 'ajax';
			$school_id =$this->request->session()->read('company_id');
			
			$session_id = $this->Cookie->read('sessionid');

			$student_list = TableRegistry::get('student');
			
			$classid = $this->request->data('classId');
			$retrieve_stdnt = $student_list->find()->select([ 'id', 's_name'])->where(['session_id' => $session_id ,'school_id' => $school_id, 'class' => $classid ])->toArray();
			echo '<option selected="selected">Choose Student</option>';
			foreach($retrieve_stdnt as $stdnt)
			{				
				echo '<option value="'.$stdnt->id.'">'.$stdnt->s_name.'</option>';				
			}
			
			
		}
		
		
        
        public function adddefstdnt(){
			if ($this->request->is('post'))
			{
				$school_id =$this->request->session()->read('company_id');
				$defaulter_list = TableRegistry::get('defaulter_list');		
			
				$session_id = $this->Cookie->read('sessionid');			

				$defaulter = $defaulter_list->newEntity();
				
				$defaulter->school_id = $school_id;
				
				$defaulter->class_id = $this->request->data('classdef') ;
				$defaulter->student_id =  $this->request->data('studentdef')  ;
				$defaulter->default_reason =  $this->request->data('defreason')  ;
				$defaulter->created_date =  time()  ;
				$defaulter->session_id = $session_id;
				
				if($saved = $defaulter_list->save($defaulter) )
				{						
					$res = [ 'result' => 'success'  ];
					//return $this->redirect('/defaulter');			

				}
				else{
					$res = [ 'result' => 'Defaulter not saved'  ];
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
                $school_id =$this->request->session()->read('company_id');
			
		$session_id = $this->Cookie->read('sessionid');	

				$class_list = TableRegistry::get('class');
				$defaulter_list = TableRegistry::get('defaulter_list');
				$student_list = TableRegistry::get('student');
				
				$data = $defaulter_list->find()->select(['defaulter_list.id', 'student.adm_no', 'student.s_name', 'class.c_name' , 'class.c_section' , 'student.s_f_name', 'student.s_m_name', 'student.email', 'student.mobile_no' ,  'defaulter_list.default_reason'  ])->join(['student' => 
                        [
                        'table' => 'student',
                        'type' => 'LEFT',
                        'conditions' => 'student.id = defaulter_list.student_id'
                    ]
                ])->join(['class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = defaulter_list.class_id'
                    ]
                ])->where([ 'defaulter_list.school_id' => $school_id , 'defaulter_list.session_id' => $session_id])->toArray() ;

                //$data = $student_table->find()->select(['id' ,'adm_no' ,'s_name', 's_f_name', 's_m_name', 'resi_add1' ,'mobile_no', 'email' , 'national' , 'gender' ])->where(['school_id' => $compid ])->toArray() ; 

				/*foreach($data as $value)
				{
					$default = $value['id']. ",". $value['student']['adm_no']. "," . $value['student']['s_name']. ",". $value['class']['c_name']. ",". $value['class']['c_section']. "," . $value['student']['s_f_name']. "," . $value['student']['s_m_name']. "," . $value['student']['email']. "," . $value['student']['mobile_no']. "," . $value['default_reason'];
					
					$def_data = explode(",", $default);
				}*/
				
					
                $def_data[] = $data;
				print_r($def_data);
                
                $this->setResponse($this->getResponse()->withDownload('file.csv'));
                $_header = array('Id', 'Admission Number' , 'Student Name' , 'Class' , 'Section', 'Father Name' , 'Mother Name' , 'Email' , 'Mobile Number' , 'Defaulter Reason' );
                $_serialize = 'def_data';
                $this->viewBuilder()->setClassName('CsvView.csv');
                $this->set(compact('def_data', '_header' , '_serialize'));
				
        }


		public function edit($defid){

			$school_id =$this->request->session()->read('company_id');
			$class_list = TableRegistry::get('class');
			$defaulter_list = TableRegistry::get('defaulter_list');
			$student_list = TableRegistry::get('student');
			
			$session_id = $this->Cookie->read('sessionid');

			$retrieve_def = $defaulter_list->find()->select([ 'id', 'school_id', 'student_id', 'class_id', 'default_reason'])->where(['md5(id)' => $defid ])->toArray();
			
			foreach($retrieve_def as $def)
			{
				$classId = $def->class_id;
			}
			$retrieve_class = $class_list->find()->select([ 'id', 'c_name', 'c_section'])->where(['session_id' => $session_id , 'school_id' => $school_id, 'active' => 1   ])->toArray();
			$retrieve_stdnt = $student_list->find()->select([ 'id', 's_name'])->where(['school_id' => $school_id, 'session_id' => $session_id , 'class' => $classId ])->toArray();
			//print_r($retrieve_def);
			
			$this->set("class_list", $retrieve_class); 
			$this->set("def_list", $retrieve_def); 
			$this->set("stdnt_list", $retrieve_stdnt); 
			$this->viewBuilder()->setLayout('user');
		}            

        public function editdefstdnt(){
			
            if ($this->request->is('post') && $this->request->is('ajax'))
			{
				$school_id =$this->request->session()->read('company_id');
				$defaulter_list = TableRegistry::get('defaulter_list');					
				
				$session_id = $this->Cookie->read('sessionid');

				$defaulter = $defaulter_list->newEntity();
				$defid = $this->request->data('def_id') ;
				
				//$defaulter->school_id = $school_id;
				$class_id = $this->request->data('classdef') ;
				$student_id =  $this->request->data('studentdef')  ;
				$default_reason =  $this->request->data('defreason')  ;
				
					
				$update = $defaulter_list->query()->update()->set([  'class_id' => $class_id , 'student_id' => $student_id, 'default_reason' => $default_reason ])->where(['id' =>  $defid ])->execute();
				
				
				if($update )
				{						
					$res = [ 'result' => 'success'  ];
					//return $this->redirect('/defaulter');			

				}
				else{
					$res = [ 'result' => 'Defaulter not saved'  ];
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

                $defid = $this->request->data('val') ;
                $default_table = TableRegistry::get('defaulter_list');
				//$enquiry_table = TableRegistry::get('enquiry_form'); 
                $activ_table = TableRegistry::get('activity');

                $userid = $default_table->find()->select(['id'])->where(['id'=> $defid ])->first();

                if($userid->id)
                {   
					$del = $default_table->query()->delete()->where([ 'id' => $defid ])->execute(); 
                    if($del)
                    {
						
						$activity = $activ_table->newEntity();
						$activity->action =  "Defaulter student Deleted"  ;
						$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
						$activity->value = $defid    ;
						$activity->origin = $this->Cookie->read('id')   ;
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


        public function edituserprofile(){
            if ($this->request->is('ajax') && $this->request->is('post') ){
                    
                    $company_table = TableRegistry::get('company');
                    $activ_table = TableRegistry::get('activity');
                    
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
                    }
                    else
                    {
                        $picture =  $this->request->data('apicture');
                    }

                    

                    $comp_name =  $this->request->data('name')  ;
                    $email =  $this->request->data('email')  ;
                    $phone = $this->request->data('phone') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->Cookie->read('id');
                    $modified = strtotime('now');

                    if(!empty($picture))
                    {
                        if($comp_name != ""   || $email != ""  && $phone != ""  )
                        {
                            if($update_company = $company_table->query()->update()->set([  'comp_name' => $comp_name , 'email' => $email, 'ph_no' => $phone , 'comp_logo' => $picture  ])->where(['md5(id)' =>  $userid ])->execute()){
                                $activity = $activ_table->newEntity();
                                $activity->action =  "User Data Updated"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($userid)   ;
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
                                $res = [ 'result' => 'profile not updated'  ];
    
                            }
    
                            if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword ){
                                if($update_task = $company_table->query()->update()->set([  'password' => $password ])->where(['md5(id)' =>  $userid ])->execute()){
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "User Password Updated"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($userid)   ;
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
                                    $res = [ 'result' => 'password not updated'  ];
        
                                }
                            }
                            else if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
                                if($password == ""){
                                    $res = [ 'result' => 'password is required'  ];
                                }
                                elseif($cpassword == ""){
                                    $res = [ 'result' => 'confirm password is required'  ];
                                }
                                elseif($password != $cpassword){
                                    $res = [ 'result' => 'password and confirm password doesn\'t match'  ];
                                }
                            }
    
                        }
                        else
                        {
                            $res = [ 'result' => 'empty'  ];
    
                        }
                    }
                    else
                    { 
                        $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                    }    
            }
            else{
                $res = [ 'result' => 'Invalid Operation'  ];
            }


           return $this->json($res);

        }

}

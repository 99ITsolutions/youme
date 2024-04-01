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
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SchoolsController  extends AppController
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
                $school_table = TableRegistry::get('company');
                $student_table = TableRegistry::get('student');
                $class_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $emp_table = TableRegistry::get('employee');
                $subcls_table = TableRegistry::get('class_subjects');
                $know_table = TableRegistry::get('knowledge_base');
                $gallery_table = TableRegistry::get('gallery');
                $examass_table = TableRegistry::get('exams_assessments');
                $contactyoume_table = TableRegistry::get('contactyoume');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $que_table = TableRegistry::get('exam_ass_question');
                //print_r($_COOKIE); die;

                $retrieve_school = $school_table->find()->distinct('company.id')->select(['company.id' ,'company.comp_name', 'company.status_reason','company.ph_no' ,'company.city' , 'company.email', 'company.password', 'company.status' , 'company.www' , 'company.expiry_date',  'students' => 'COUNT(student.school_id)' ])
		            ->join(['student' => 
							[
							'table' => 'student',
							'type' => 'LEFT',
							'conditions' => 'student.school_id = company.id'
						]
					])->toArray() ; 
					
				foreach($retrieve_school as $key =>$schoolcoll)
        		{
    				$retrieve_student = $student_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->student_sts = $retrieve_student;
        			
        			$retrieve_studentdel = $student_table->find()->select(['id'])->where(['delete_request' => 1 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->stud_delreq = $retrieve_studentdel;
        			
        			$retrieve_examass = $examass_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$totalminus = 0;
        			if($retrieve_examass != 0)
            		{
            		    $retrieve_exams = $examass_table->find()->select(['id', 'exam_format'])->where(['status' => 0 ,'school_id'=> $schoolcoll['id']  ])->toArray();
            		    $examminus = [];
            		    foreach($retrieve_exams as $val)
            		    {
            		        if($val['exam_format'] == "custom")
            		        {
    					        $retrieve_quecount = $que_table->find()->where(['exam_id' => $val['id']  ])->count();
    					        if($retrieve_quecount == 0)
    					        {
    					            $examminus[] = 1;
    					        }
    					        else
    					        {
    					            $examminus[] = 0;
    					        }
            		        }
            		    }
            		     $totalminus = array_sum($examminus);
            		}
            		
            		$retrieve_examass = $retrieve_examass-$totalminus;
        			
        			$schoolcoll->examass_sts = $retrieve_examass;
        			
        			$retrieve_subject = $sub_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->subject_sts = $retrieve_subject;
        			
        			$retrieve_class = $class_table->find()->select(['id'])->where(['active' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->class_sts = $retrieve_class;
        			
        			$retrieve_teacher = $emp_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->teacher_sts = $retrieve_teacher;
        			
        			$retrieve_subjcls = $subcls_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->subjcls_sts = $retrieve_subjcls;
        			
        			$retrieve_knowledge = $know_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->knowledge_sts = $retrieve_knowledge;
        			
        		
                    $retrieve_contactyoume = $contactyoume_table->find()->where(['school_id' => $schoolcoll['id'], 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                    //print_r($retrieve_contactyoume); die;
                    if(!empty($retrieve_contactyoume))
                    { 
                        foreach($retrieve_contactyoume as $key =>$msg)
                		{
                		    $count_read = $contactyoume_table->find()->where([ 'school_id' => $schoolcoll['id'], 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'superadmin', 'from_type'=>'school'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$countyoume = $msg->read_count;
                    }
                    else
                    {
                        $countyoume = 0;
                    }
                    $schoolcoll->contactyoume_sts = $countyoume; 
        			
        			$retrieve_gallery = $gallery_table->find()->select(['id'])->where(['status' => 0 , 'school_id'=> $schoolcoll['id'] ])->count();
        			$schoolcoll->gallery_sts = $retrieve_gallery;
        		}
					
				
				
	            $this->set("school_details", $retrieve_school); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function viewcases($id){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = base64_decode($id);
                $message_table = TableRegistry::get('contactyoume');
                $retrieve_unreadmsg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id, 'to_type'=> 'superadmin'])->toArray();
                foreach($retrieve_unreadmsg as $unread)
                {
                    //print_r($unread); 
                    if($unread['read_msg'] == 0)
                    {
                        $message_table->query()->update()->set([ 'read_msg'=> '1', 'read_datetime' => time() ])->where([ 'id' => $unread['id']  ])->execute();
                
                    }
                }
                //die;
                $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
        		$all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();
        		
        		$company_table = TableRegistry::get('company');
        		$users_table = TableRegistry::get('users');
        		
        		foreach($all_msg as $key =>$msg){
        		    if($msg->from_type == 'school'){
        		        $retrieve_comp = $company_table->find()->where(['id'=> $msg->from_id ])->first();
        		        $msg->sender = $retrieve_comp->comp_name; 
        		    }else if($msg->to_type == 'school'){
        		        $retrieve_users = $users_table->find()->where(['id'=>  $msg->from_id ])->first();
        		        $msg->sender = $retrieve_users->fname." ". $retrieve_users->lname; 
        		    }
        		}
        		$this->set("id", $id); 
                $this->set("all_messages", $all_msg); 
                $this->set("get_messages", $retrieve_msg); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addmessage(){   
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $message_table = TableRegistry::get('contactyoume');
                    $activ_table = TableRegistry::get('activity');
                    $users_table = TableRegistry::get('users');
                    $superadminid = $this->Cookie->read('sid'); 
                    if(!empty($superadminid))
                    {
                        $retrieve_users = $users_table->find()->where(['md5(id)'=> $superadminid ])->first();
                         $superid = $retrieve_users['id'];
                        
                        $msg = $message_table->newEntity();
                        if($this->request->data['id']){
                        
                        $id=$this->request->data('id');
                        $msg->parent = $this->request->data('id');	 
                        $msg->subject = '' ;
                       
                        $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
                       
                        $msg->to_type = $retrieve_msg->from_type;
                        $msg->to_id = $retrieve_msg->from_id;
                        $msg->from_id = $superid;
                        $msg->from_type ='superadmin';
                        $msg->message = $this->request->data('message');
                        $msg->school_id = $retrieve_msg->from_id;;
                        $msg->created_date = time();
                        
                        /*$subject = $retrieve_msg->subject;
                        $company_table = TableRegistry::get('company');
                        
                        $retrieve_company = $company_table->find()->where(['id'=> $msg->to_id ])->first();
                        if($msg->to_type == 'school'){
                            $to = $retrieve_company->email; 
                            $username = $retrieve_company->comp_name;
                        }else if($msg->to_type == 'superadmin'){
                            $to = $retrieve_users->email; 
                            $username = $retrieve_users->fname;
                        }
                        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
                        $from = $retrieve_comp->email; 
                        $name = $retrieve_comp->comp_name;
                        $all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();  
                        $htmlContent = '';*/
                        
                        /*foreach($all_msg as $key =>$msg12){
                            if($msg12->from_type == 'superadmin'){
                                $sender = $retrieve_users->fname.' '.$retrieve_users->lname; 
                            }else if($msg12->from_type == 'school'){
                                $sender = $retrieve_comp->comp_name; 
                            }
                            
                            $n_date = date('M d, Y h:i A', $msg12->created_date);
                            $htmlContent .= '
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline><b>'.$sender.' </b><span style="float:right">"'.$n_date.'</span></multiline></td>
                                            </tr><tr>
                                                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline>'.$msg12->message.'</multiline></td>
                                            </tr><tr >
                                                <td class="text" style="border-top:1px solid #ccc"><multiline></multiline></td>
                                            </tr>';
                        }
                        $attach = array();*/
                        //$this->sendUserEmail($to,$from,$name,$subject,$htmlContent,$username,$attach);
                    }
                    
                    
                    
                   /* if(!empty($this->request->data['upload_file'])){   
                        $filename = time().$this->request->data['upload_file']['name'];
                        $uploadpath = 'messages/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['upload_file']['tmp_name'], $uploadfile))
                        {
                            $msg->attachment = $filename; 
                        }
                    }*/
                    //print_r($msg);
                    if($saved = $message_table->save($msg) ){     
                        $strucid = $saved->id;
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Message Send"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($strucid); 
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity) ;

                        if($save)
                        {   
                            $res = [ 'result' => 'success'  , 'msgid' => $strucid ];
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
                         return $this->redirect('/login/') ;  
                    }
                     
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
            public function deleterequest($sclid)
            {   
                $student_table = TableRegistry::get('student');
                //$compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.status', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['md5(student.school_id)' => $sclid, 'delete_request' => 1 ])->toArray() ;
					
				
                
                 $this->set("studentdetails", $retrieve_student_table); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveStatus($schoolId)
            {
                $student_table = TableRegistry::get('student');
                $class_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $emp_table = TableRegistry::get('employee');
                $subcls_table = TableRegistry::get('class_subjects');
                $know_table = TableRegistry::get('knowledge_base');
                $gallery_table = TableRegistry::get('gallery');
                $examass_table = TableRegistry::get('exams_assessments');
                $notify_table = TableRegistry::get('notification');
                $que_table = TableRegistry::get('exam_ass_question');
        		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        		$retrieve_examass = $examass_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
        		if($retrieve_examass != 0)
        		{
        		    $retrieve_exams = $examass_table->find()->select(['id', 'exam_format'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->toArray();
        		    $examminus = [];
        		    foreach($retrieve_exams as $val)
        		    {
        		        if($val['exam_format'] == "custom")
        		        {
					        $retrieve_quecount = $que_table->find()->where(['exam_id' => $val['id']  ])->count();
					        if($retrieve_quecount == 0)
					        {
					            $examminus[] = 1;
					        }
					        else
					        {
					            $examminus[] = 0;
					        }
        		        }
        		    }
        		    $totalminus = array_sum($examminus);
        		}
        		
        		$retrieve_examass = $retrieve_examass-$totalminus;
        		
                //$schoolId = $this->request->data('id');
                
				$retrieve_student = $student_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_subject = $sub_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_class = $class_table->find()->select(['id'])->where(['active' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_teacher = $emp_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_subjcls = $subcls_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_knowledge = $know_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_gallery = $gallery_table->find()->select(['id'])->where(['status' => 0 , 'md5(school_id)'=> $schoolId ])->count();
    			$retrieve_notify = $notify_table->find()->select(['id'])->where(['status' => 0, 'md5(school_id)'=> $schoolId  ])->count();
    		
			    $this->set("notify_sts", $retrieve_notify); 
	            $this->set("student_sts", $retrieve_student); 
	            $this->set("gallery_sts", $retrieve_gallery); 
	            $this->set("knowledge_sts", $retrieve_knowledge); 
	            $this->set("subject_sts", $retrieve_subject); 
	            $this->set("class_sts", $retrieve_class); 
	            $this->set("teacher_sts", $retrieve_teacher); 
	            $this->set("subjcls_sts", $retrieve_subjcls); 
	            $this->set("examass_sts", $retrieve_examass); 
	            $this->set("sclid", $schoolId);
	            
                $this->viewBuilder()->setLayout('usersa');
            }

            public function add()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $school_table = TableRegistry::get('company');
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $retrieve_school = $school_table->find()->select(['id' ])->last();
                $this->set("school_details", $retrieve_school);
                $this->set("country_details", $retrieve_countries);
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addscl()
            {   
                if ($this->request->is('post') )
                {	
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $school_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $activ_table = TableRegistry::get('activity');
		            $session_table = TableRegistry::get('session');	
                    $compid = $this->request->session()->read('company_id');
                    $logo ="";
                   
                    if(!empty($this->request->data['logo']['name']))
                    {   
                        if($this->request->data['logo']['type'] == "image/jpeg" || $this->request->data['logo']['type'] == "image/jpg" || $this->request->data['logo']['type'] == "image/png" )
                        {
                            $logo =  $this->request->data['logo']['name'];
                            $filename = $this->request->data['logo']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $filename; 
                            }
                        }    
                    }
                    
                    if(!empty($logo))
                    {
                        $retrieve_school = $school_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                        $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                        $retrieve_teacher = $teacher_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                        $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                        $retrieve_supersub = $supersubad_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                        $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                         
                        if($retrieve_school == 0 && $retrieve_student == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                        {
                            $comp_name = $this->request->data('comp_name');
        					$email = trim($this->request->data('email'));
                            //$password = trim($this->request->data('password'));
                            
                            
                            
                            $phone = $this->request->data('phone');
        					
                            $primary = $this->request->data('navbar');
                            $button = $this->request->data('buttoncolor');
                            $status = $this->request->data('status');
                            if(!empty($this->request->data('city')))
                            {
            					$city = $this->request->data('city');
                            }
                            else
                            {
                                $city = "";
                            }
            				if(!empty($this->request->data('country')))
            				{
            					$country = $this->request->data('country');
            				}
                            else
                            {
                                $country = "";
                            }
            				if(!empty($this->request->data('state')))
            				{
                                $state = $this->request->data('state');
                            }
                            else
                            {
                                $state = "";
                            }
        					if(!empty($this->request->data('add_1')))
        					{
        						$add_1 = $this->request->data('add_1');
        					}
        					else
        					{
        					    $add_1 ="";
        					}
        					if(!empty($this->request->data('zipcode')))
        					{
        						$zipcode = $this->request->data('zipcode');
        					}
        					else
        					{
        					    $zipcode ="";
        					}
        					
        					if($lang != "")
                            {
                                $lang = $lang;
                            }
                            else
                            {
                                $lang = 2;
                            }
                            
                            $language_table = TableRegistry::get('language_translation');
                            if($lang == 1)
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                                
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['english_label'];
                                }
                            }
                            else
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['french_label'];
                                }
                            }
                            
                            foreach($retrieve_langlabel as $langlbl) 
                            { 
                                if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1616') { $wrkngdays = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1617') { $breakname = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                                
                                if($langlbl['id'] == '1611') { $dategreat = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1629') { $sclemail = $langlbl['title'] ; } 
                            } 
        						
        						
        					$school = $school_table->newEntity();
                            $school->comp_name = $comp_name;
                            $school->add_1 = $add_1 ;
                            $school->email = $email ;
                            $school->city = $city ;
                            $school->country = $country ;
                            $school->ph_no = $phone ;
                            $school->comp_logo = $logo;
                            $school->status = $status;
                            $school->primary_color = $primary;
                            $school->button_color = $button;
                            $added_date = date('Y-m-d',strtotime("now"));
                            $school->present_days = $this->request->data('working_days');
                            $school->start_timings = $this->request->data('event_start_time');
                            $school->end_timings = $this->request->data('event_end_time');
                            $school->time_slot = $this->request->data('timeslot');
                            
                            $school->break_name = $this->request->data('breakname');
                            $school->break_time = $this->request->data('breaktime');
                            
                            if(!empty($this->request->data['scl_privilages'])) {
                                $school->scl_privilages = implode(",", $this->request->data['scl_privilages']);
                            }
                            else {
                                $school->scl_privilages = "Senior";
                            }
                            
                            if($this->request->data('working_days') > 0) {
                            if($this->request->data('breakname') != "" && $this->request->data('breaktime') != "") {
                            if (preg_match ("/^[0-9]*$/", $phone) )
                            {
                                $start_timings = $this->request->data('event_start_time');
                                $end_timings = $this->request->data('event_end_time');
                                $ee = strtotime($end_timings);
                                $ss = strtotime($start_timings);
                                if($ee > $ss)
                                {
                                    if($saved = $school_table->save($school) )
                                    {   
                                        $sclid = $saved->id;
                                        $password = trim(substr($comp_name,0, 3).$sclid.uniqid());
                                        $insert = $school_table->query()->update()->set([ 'state'=>$state, 'city'=>$city , 'zipcode'=>$zipcode , 'password'=>$password, 'added_date'=> $added_date ])->where([ 'id' => $sclid  ])->execute();
                                    
                                        $class_table = TableRegistry::get('class');
                                        $fileName = "https://you-me-globaleducation.org/school/webroot/classes.csv";
                                        $csvData = file_get_contents($fileName);
                                        $lines = explode(PHP_EOL, $csvData);
                                        $array = array();
                                        foreach ($lines as $row) 
                                        {
                                            $data = str_getcsv($row);
                                        	$class = $class_table->newEntity();
                                        	$class->c_name = $data[0] ;
                                        	$class->school_sections = $data[1] ;                                    
                                        	$class->active = 1 ;
                                        	$class->school_id = $sclid;
                                        	
                                        	$clsadd = $class_table->save($class);
                                        }
                                        
                                        $subject_table = TableRegistry::get('subjects');
                                        $fileNameSub = "https://you-me-globaleducation.org/school/webroot/subjectss.csv";
                                        $csvDataSub = file_get_contents($fileNameSub);
                                        $linesSub = explode(PHP_EOL, $csvDataSub);
                                        foreach ($linesSub as $rowsub) 
                                        {
                                            $dataSub = str_getcsv($rowsub);
                                            
                                        	$subject = $subject_table->newEntity();
                                        	$subject->subject_name = $dataSub[0] ;
                                        	$subject->created_date = time() ;                                    
                                        	$subject->status = 1 ;
                                        	$subject->school_id = $sclid;
                                        	
                                        	$subadd = $subject_table->save($subject);
                                        }
                                        
                                        $classsub_table = TableRegistry::get('class_subjects');
                                        $fileNameclssub = "https://you-me-globaleducation.org/school/webroot/classes_subjectss.csv";
                                        $csvDataclssub = file_get_contents($fileNameclssub);
                                        $linesclssub = explode(PHP_EOL, $csvDataclssub);
                                        foreach ($linesclssub as $rowclssub) 
                                        {
                                        	$dataclssub = str_getcsv($rowclssub);
                                        	
                                        	$clsname = $dataclssub[0];
                                        	$sclsection = $dataclssub[1];
                                        	
                                        	$subjctss = explode(",", $dataclssub[2]);
                                        	
                                        	$retrieve_cls1 = $class_table->find()->select(['id'])->where(['c_name' => $clsname , 'school_sections'=> $sclsection, 'school_id'=> $sclid ])->first();
                                        	$clsid = 	$retrieve_cls1['id'];
                                        	$sub = [];
                                        	foreach($subjctss as $subj)
                                        	{
                                        	    $subj = trim($subj);
                                        		$retrieve_sub = $subject_table->find()->select(['id'])->where(['subject_name' => $subj , 'school_id'=> $sclid ])->first();
                                        		$sub[] = 	$retrieve_sub['id'];
                                        	}
                                        	
                                        	$subid = implode(",", $sub);
                                        	
                                        	$classsub = $classsub_table->newEntity();
                                        	$classsub->class_id = $clsid;
                                        	$classsub->subject_id = $subid;
                                        	$classsub->status = 1;
                                        	$classsub->school_id = $sclid;
                                        	$classsub->created_date = time();
                                        						  
                                        	$clssub = $classsub_table->save($classsub);
                                        }
                                        
                                        $kinderdash_table = TableRegistry::get('kinderdash');
                                        $fileNamekinder = "https://you-me-globaleducation.org/school/webroot/kinder.csv";
                                        $csvDatakinder = file_get_contents($fileNamekinder);
                                        $lineskinder = explode(PHP_EOL, $csvDatakinder);
                                        foreach ($lineskinder as $kinder) 
                                        {
                                            $datakinder = str_getcsv($kinder);
                                            
                                        	$kinder = $kinderdash_table->newEntity();
                                        	$kinder->dash_name = $datakinder[0] ;
                                        	$kinder->created_date = time() ;                                    
                                        	$kinder->image = $datakinder[1] ;
                                        	$kinder->school_id = $sclid;
                                        	
                                        	$kinderdata = $kinderdash_table->save($kinder);
                                        }
                                    
                                        if($saved)
                                	    {
                                	        $name = $comp_name;
                                            $subject = 'New Registration as a school in You-Me Global Education';
                                            $to =  $email;
                                            $rname = "You-Me Global Education Team";
                                            $from = "support@you-me-globaleducation.org";
                                            $link = 'https://you-me-globaleducation.org/school/login';
                                            $htmlContent = '
                                            <tr>
                                            <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                                <multiline>
                                                    <p>Welcome to You-Me Global Education! We\'re here to transform your education digitally.</p>
                                                    <p>You-Me Global Education is a platform that provides a complete digital resource management system. Below are the details of your account.</p>
                                                </multiline>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                    <p>Login Link: '.$link.' </p>
                                                    <p>Username: '.$email.' </p>
                                                    <p>Password: '.$password.' </p>
                                                    </multiline>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                        <p>Regards,</p>
                                                        <p>'.$rname.'</p>
                                                    </multiline>
                                                </td>
                                            </tr>';
                                            $username = $comp_name;
                                          
                                            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                                            $res = [ 'result' => 'success'];
                                	    }
                                        else
                                        { 
                                            $res = [ 'result' => 'activity'  ];
                                        }
                                        
                                    }
                                    else
                                    {
                                        $res = [ 'result' => 'Error Occured'  ];
                                    }
                                }
                                else 
                                {
                                    $res = [ 'result' => $dategreat ];
                                }
                            }
                            else
                            {
                                 $res = [ 'result' => $insertdigit  ];
                            }
                            } else {  $res = [ 'result' => $breakname ]; }
                            } else {  $res = [ 'result' => $wrkngdays  ]; }
                        }
                        else
                        {
                             $res = [ 'result' => $sclemail ];
                        }
                    }
                    else
                    { 
                        $res = [ 'result' => $imgjpg  ];
                    }   
                }
                return $this->json($res);
            }

            public function update($sid)
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $school_table = TableRegistry::get('company');
                $module_table = TableRegistry::get('modules');
                $state_table = TableRegistry::get('states');
                /*$city_table = TableRegistry::get('cities');*/
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $retrieve_school = $school_table->find()->where([ 'md5(id)' => $sid ])->toArray() ;
                $retrieve_module = $module_table->find()->select(['id' ,'name'])->where([ 'status' => 1  ])->toArray() ;
                
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_school[0]['country']  ])->toArray() ;

                /*$retrieve_city = $city_table->find()->select(['id' ,'name'])->where([ 'state_id' => $retrieve_school[0]['state'] ])->toArray() ;*/

                $this->set("country_details", $retrieve_countries);
                $this->set("school_details", $retrieve_school);
                $this->set("module_details", $retrieve_module);
                $this->set("state_details", $retrieve_state);
                /*$this->set("city_details", $retrieve_city);*/
                $this->viewBuilder()->setLayout('usersa');
            }


           public function updatescl()
            {   
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {   
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $school_table = TableRegistry::get('company');
                    $activ_table = TableRegistry::get('activity');
                   
		            $session_table = TableRegistry::get('session');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $student_table = TableRegistry::get('student');
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    

                    if(!empty($this->request->data['logo']['name']))
                    {   
                        if($this->request->data['logo']['type'] == "image/jpeg" || $this->request->data['logo']['type'] == "image/jpg" || $this->request->data['logo']['type'] == "image/png" )
                        {
                            $logo =  $this->request->data['logo']['name'];
                            $filename = $this->request->data['logo']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $filename; 
                            }
                        }    
                    }else
                    {
                        $logo =  $this->request->data('lpicture');
                    }

                    
					
					$add_1 ="";
					$zipcode = "";
				

                    $retrieve_school = $school_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')), 'id IS NOT'=> $this->request->data('sclid')])->count() ;
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                    
                    $sclid = $this->request->data('sclid');
                    $comp_name = $this->request->data('comp_name');
		            $email = trim($this->request->data('email'));
                    $password = $this->request->data('password');
                    $phone = $this->request->data('phone');
                    $status = $this->request->data('status');
					$primary = $this->request->data('navbar');
                    $button = $this->request->data('buttoncolor');
					$country = $this->request->data('country');
					$scl_privilages = implode(",", $this->request->data['scl_privilages']);
                    
					
					if(!empty($this->request->data('city')))
					{
						$city = $this->request->data('city');   
					}
					else
					{
					    $city ="";
					}
					
					if(!empty($this->request->data('state')))
					{
						$state = $this->request->data('state');
					}
					else
					{
					    $state ="";
					}
					
					if(!empty($this->request->data('add_1')))
					{
						$add_1 = $this->request->data('add_1');   
					}
					else
					{
					    $add_1 ="";
					}
					if(!empty($this->request->data('zipcode')))
					{
						$zipcode = $this->request->data('zipcode');
					}
					else
					{
					    $zipcode ="";
					}
                     
                    $working_days = $this->request->data('working_days');
                    $start_timings = $this->request->data('event_start_time');
                    $end_timings = $this->request->data('event_end_time');
                    $timeslot = $this->request->data('timeslot');
                    $break_name = $this->request->data('breakname');
                    $break_time = $this->request->data('breaktime');
                    
                    if($lang != "")
                            {
                                $lang = $lang;
                            }
                            else
                            {
                                $lang = 2;
                            }
                            
                            $language_table = TableRegistry::get('language_translation');
                            if($lang == 1)
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                                
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['english_label'];
                                }
                            }
                            else
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['french_label'];
                                }
                            }
                            
                            foreach($retrieve_langlabel as $langlbl) 
                            { 
                                if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1616') { $wrkngdays = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1617') { $breakname = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                                
                                if($langlbl['id'] == '1611') { $dategreat = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1629') { $sclemail = $langlbl['title'] ; } 
                            } 
        						
        						
        						
                    if(!empty($logo))
                    {  
                        if($retrieve_school == 0 && $retrieve_student == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                        {
                            if (preg_match ("/^[0-9]*$/", $phone) )
                            {
                                $ee = strtotime($end_timings);
                                $ss = strtotime($start_timings);
                                if($ee > $ss)
                                {
                                    
                                    if($working_days > 0) {
                                    if($this->request->data('breakname') != "" && $this->request->data('breaktime') != "") {
    						        $update = $school_table->query()->update()->set(['scl_privilages' => $scl_privilages ,'break_name' => $break_name, 'break_time' => $break_time, 'time_slot' => $timeslot, 'start_timings' => $start_timings , 'end_timings' => $end_timings, 'present_days' => $working_days , 'primary_color' => $primary, 'button_color' => $button, 'country' => $country,  'comp_name' => $comp_name, 'add_1' => $add_1,'state'=>$state, 'city'=>$city, 'comp_logo'=>$logo,  'email' => $email, 'ph_no' => $phone, 'password' => $password, 'status' => $status  ])->where([ 'id' => $sclid  ])->execute();
                            
                            
                            
                                    if($update)
                                    {   
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "School update"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                            $activity->origin = $this->Cookie->read('id');
                                            $activity->value = md5($sclid); 
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
                                    { $res = [ 'result' => 'failed'  ]; }
                                    } else {  $res = [ 'result' => $breakname ]; }
                                    } else {  $res = [ 'result' => $wrkngdays  ]; }
                                }
                                else
                                {
                                    $res = [ 'result' => $dategreat  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $insertdigit ];
                            }
                        } 
                        else
                        {
                            $res = [ 'result' => $sclemail  ];
                        }    
                    }
                    else
                    { 
                        $res = [ 'result' => $imgjpg  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }
            
            public function view($sid)
            {   
                $school_table = TableRegistry::get('company');
                $module_table = TableRegistry::get('modules');
                $state_table = TableRegistry::get('states');
                $city_table = TableRegistry::get('cities');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_school = $school_table->find()->select(['id', 'principal_name' ,'user_name' ,'comp_no','comp_name' ,'comp_logo','password','comp_logo1','state','city' ,'ph_no' ,'site_title','site_name','pan_no', 'tin_no', 'email' , 'zipcode' ,'www', 'logout_url', 'expiry_date' ,'module' , 'add_1' ,'status' , 'favicon' ,'work_key' , 'sender', 'send_sms', 'sms_temp' , 'send_time', 'mail_host', 'email_host', 'mail_password' , 'port' ,'send_email', 'primary_color'])->where([ 'md5(id)' => $sid ])->toArray() ;

                $retrieve_module = $module_table->find()->select(['id' ,'name'])->where([ 'status' => 1  ])->toArray() ;
                
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => 101  ])->toArray() ;

                $retrieve_city = $city_table->find()->select(['id' ,'name'])->where([ 'state_id' => $retrieve_school[0]['state'] ])->toArray() ;


                $this->set("school_details", $retrieve_school);
                $this->set("module_details", $retrieve_module);
                $this->set("state_details", $retrieve_state);
                $this->set("city_details", $retrieve_city);
                $this->viewBuilder()->setLayout('usersa');

            }

            public function delete()
            {
                $sid = (int) $this->request->data('val') ; 
                $school_table = TableRegistry::get('company');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sclid = $school_table->find()->select(['id'])->where(['id'=> $sid ])->first();
				
                if($sclid)
                {   
                    $del = $school_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                    if($del)
                    { 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "School Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($sid)    ;
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
            
            public function deleteallschools()
            {
    			$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                $school_table = TableRegistry::get('company');
                
                foreach($uid as $ids)
                {
                    $stats = $school_table->query()->delete()->where([ 'id' => $ids ])->execute();
                }
            
                if($stats)
                {
                    $res = [ 'result' => 'success'  ];
                }
                else
                {
                    $res = [ 'result' => 'not delete'  ];
                }    
                
    
                return $this->json($res);
            }

            public function export($examid=null)
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $submit_exams_table = TableRegistry::get('submit_exams');
                $student_table = TableRegistry::get('student');
                $examass_table = TableRegistry::get('exams_assessments');

                if(!empty($examid))
                {
                    $data_main = $submit_exams_table->find()->select(['student.adm_no' , 'submit_exams.passcode' ,'student.f_name', 'student.l_name','student.email'  ,'student.emergency_number', 
			            'exams_assessments.exam_type','exams_assessments.title','subjects.subject_name' , 'class.c_name', 'class.c_section' , 'company.comp_name'])->join(
			                [
        			            'student' => 
                                    [
                                        'table' => 'student',
                                        'type' => 'LEFT',
                                        'conditions' => 'student.id = submit_exams.student_id'
                                    ],
        			            'exams_assessments' => 
                                    [
                                        'table' => 'exams_assessments',
                                        'type' => 'LEFT',
                                        'conditions' => 'exams_assessments.id = submit_exams.exam_id'
                                    ],
        			            'subjects' => 
                                    [
                                        'table' => 'subjects',
                                        'type' => 'LEFT',
                                        'conditions' => 'subjects.id = exams_assessments.subject_id'
                                    ],
                                'class' => 
                                    [
                                        'table' => 'class',
                                        'type' => 'LEFT',
                                        'conditions' => 'class.id = exams_assessments.class_id'
                                    ],
                                'company' => 
                                    [
                                        'table' => 'company',
                                        'type' => 'LEFT',
                                        'conditions' => 'company.id = exams_assessments.school_id'
                                    ]

                            ])->where(['submit_exams.exam_id' => $examid ])->toArray() ;  
                            
                            
                    $getfile = $examass_table->find()->select([ 'exams_assessments.exam_type','exams_assessments.title','subjects.subject_name' , 'class.c_name', 'class.c_section' , 'company.comp_name'])->join(
        		                [
            			            'subjects' => 
                                        [
                                            'table' => 'subjects',
                                            'type' => 'LEFT',
                                            'conditions' => 'subjects.id = exams_assessments.subject_id'
                                        ],
                                    'class' => 
                                        [
                                            'table' => 'class',
                                            'type' => 'LEFT',
                                            'conditions' => 'class.id = exams_assessments.class_id'
                                        ],
                                    'company' => 
                                        [
                                            'table' => 'company',
                                            'type' => 'LEFT',
                                            'conditions' => 'company.id = exams_assessments.school_id'
                                        ]
        
                                ])->where(['exams_assessments.id' => $examid ])->first() ;             
                            
                               
                           
                }
        		$data = array();
        		//print_r($data_main); die;
        		$filename  = $getfile['subjects']['subject_name']."(".$getfile['exam_type'].")_".$getfile['class']['c_name']."-".$getfile['class']['c_section']."_".$getfile['company']['comp_name'].".csv";
    		        
        		if(!empty($data_main))
        		{
            		foreach ($data_main as $value) 
    		        {
        		        $data[] = array('adm_no' => $value['student']['adm_no'], 'f_name' => $value['student']['f_name'], 'l_name' => $value['student']['l_name'], 'email' => $value['student']['email'], 'number' => $value['student']['emergency_number'], 'passcode' => $value['passcode'], 'title' => $value['exams_assessments']['title'], 'exam_type' => $value['exams_assessments']['exam_type'], 'subject_name' => $value['subjects']['subject_name'], 'class' => $value['class']['c_name'] ." " .$value['class']['c_section']  );
    		        }
        		}

                //$filename = $examid. "GeneratePasscode.csv";
                $this->setResponse($this->getResponse()->withDownload($filename));
                $_header = array('Student Id', 'First Name' , 'Last Name', 'Email', 'Mobile Number'  ,'Passcode', 'Title' , 'Exam Type', 'Subject', 'Class');
                $_serialize = 'data';
                $this->viewBuilder()->setClassName('CsvView.Csv');
                $this->set(compact('data', '_header' , '_serialize'));
            }

            public function getstate()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($this->request->is('post'))
                {
                    $id = $this->request->data['id'];
                    $state_table = TableRegistry::get('states');
                    $get_state = $state_table->find()->select([ 'id' , 'name']) ->where(['country_id' => $id])->toArray(); 
                    return $this->json($get_state);
                }  
            }
            
            public function approvesubjects($sclid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $subjects_table = TableRegistry::get('subjects');
                //$compid = $this->request->data('scl_id');
                $retrieve_subjects = $subjects_table->find()->where(['md5(school_id)' => $sclid, 'status' => 0])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                
                 $this->set("sclid", $sclid); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveclasses($sclid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $class_table = TableRegistry::get('class');
                //$compid = $this->request->data('scl_id');
                $retrieve_class = $class_table->find()->where(['md5(school_id)' => $sclid, 'active' => 0])->toArray() ;
                $this->set("class_details", $retrieve_class); 
                $this->set("sclid", $sclid); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveclasssubject($sclid)
            {
                $classsub_table = TableRegistry::get('class_subjects');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $subjects_table = TableRegistry::get('subjects');
				//$session_id = $this->Cookie->read('sessionid');
		
                $retrieve_classsub = $classsub_table->find()->select(['class_subjects.id' ,'class.c_name','class.c_section' ,'class_subjects.subject_id', 'class_subjects.status' ])->join([
                    'class' => 
						[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = class_subjects.class_id'
						]
					])->where([ 'md5(class_subjects.school_id)' => $sclid, 'class_subjects.status' => 0])->order(['class_subjects.id' => 'ASC'])->toArray() ;
					
				foreach($retrieve_classsub as $key =>$subcoll)
        		{
        			$subid = explode(",",$subcoll['subject_id']);
        			$i = 0;
        			$subjectsname = [];
        			foreach($subid as $sid)
        			{
        			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['md5(school_id)' => $sclid, 'id' => $sid])->toArray() ;	
        				foreach($retrieve_subjects as $rstd)
        				{
        					$subjectsname[$i] = $rstd['subject_name'];				
        				}
        				$i++;
        				$snames = implode(",", $subjectsname);
        				
        			}
        			 $subcoll->subject_name = $snames;
        			
        			
        		}
        		
        	    $this->set("sclid", $sclid); 
                $this->set("classsub_details", $retrieve_classsub);
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveteachers($compid)
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $teacher_table = TableRegistry::get('employee');
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                
                
                $retrieve_teacher_table = $teacher_table->find()->where([ 'md5(employee.school_id) '=> $compid, 'employee.status' => 0])->toArray() ;
                foreach($retrieve_teacher_table as $key =>$grd_sub_coll)
        		{
        			$subid = explode(",",$grd_sub_coll['subjects']);
        			$i = 0;
        			$subjectsname = [];
        			foreach($subid as $sid)
        			{
        			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['md5(school_id)' => $compid, 'id' => $sid])->toArray() ;	
        				foreach($retrieve_subjects as $rstd)
        				{
        					$subjectsname[$i] = $rstd['subject_name'];				
        				}
        				$i++;
        				$snames = implode(",", $subjectsname);
        				
        			}
        			$grdeid = explode(",",$grd_sub_coll['grades']);
        			$i = 0;
        			$gradesname = [];
        			foreach($grdeid as $gid)
        			{
                        $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['md5(school_id)' => $compid, 'id' => $gid])->toArray() ;
        				foreach($retrieve_class as $cname)
        				{
        					$gradesname[$i] = $cname['c_name']. "-".$cname['c_section'];				
        				}
        				$i++;
        				$cnames = implode(",", $gradesname);
        				
        			}
        			 $grd_sub_coll->subjects_name = $snames;
        			 $grd_sub_coll->grades_name = $cnames;
        			
        			
        		}
                $this->set("sclid", $compid); 
                $this->set("teacher_details", $retrieve_teacher_table); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approvestudents($compid)
            {   
                $student_table = TableRegistry::get('student');
				$session_id = $this->Cookie->read('sessionid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section'])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['md5(student.school_id)' => $compid , 'student.status' => 0])->toArray() ;
                $this->set("sclid", $compid); 
                $this->set("student_details", $retrieve_student_table); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveknowledgebase($compid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('knowledge_base');
				$session_id = $this->Cookie->read('sessionid');

                $retrieve_knowledge = $knowledge_table->find()->where(['md5(school_id)' => $compid, 'status' => 0])->toArray() ;
                $this->set("sclid", $compid); 
                $this->set("knowledge_details", $retrieve_knowledge); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveallknowledgebase()
            {
                
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $aid = implode(",", $uid);
                $knowledge_table = TableRegistry::get('knowledge_base');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $knowledge_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "knowlegde base status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function approveallsubjects()
            {
                
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $aid = implode(",", $uid);
                $subjects_table = TableRegistry::get('subjects');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $subjects_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Subject status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function approveallclasses()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $class_table = TableRegistry::get('class');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $class_table->query()->update()->set([ 'active' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Classes status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function approveallclasssub()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $classsub_table = TableRegistry::get('class_subjects');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $classsub_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Class Subject status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function profile(){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }
            
            public function edituserprofile(){
            if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
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

                    $comp_name =  $this->request->data('fname')  ;
                    $email =  trim($this->request->data('email'))  ;
                    $phone = $this->request->data('phone') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->Cookie->read('id');
                    $modified = strtotime('now');
					
                    if($lang != "")
                            {
                                $lang = $lang;
                            }
                            else
                            {
                                $lang = 2;
                            }
                            
                            $language_table = TableRegistry::get('language_translation');
                            if($lang == 1)
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                                
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['english_label'];
                                }
                            }
                            else
                            {
                                $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                                foreach($retrieve_langlabel as $langlabel)
                                {
                                    $langlabel->title = $langlabel['french_label'];
                                }
                            }
                            
                            foreach($retrieve_langlabel as $langlbl) 
                            { 
                                if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1616') { $wrkngdays = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1617') { $breakname = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                                
                                if($langlbl['id'] == '1611') { $dategreat = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1629') { $sclemail = $langlbl['title'] ; } 
                                
                                if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1628') { $passntup = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                                if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                            } 
        						

                    $retrieve_users = $company_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email')), 'md5(id) !=' =>  $userid  ])->count() ;
                    
                    if($retrieve_users == 0 )
                    {
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
                                $res = [ 'result' => $activityntadded  ];
            
                                    }
                                } 
                                else{
                                    $res = [ 'result' => $profilentup ];
        
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
                                    $res = [ 'result' => $activityntadded  ];
                
                                        }
                                    } 
                                    else{
                                        $res = [ 'result' => $passntup  ];
            
                                    }
                                }
                                else if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
                                    if($password == ""){
                                        $res = [ 'result' => $pass ];
                                    }
                                    elseif($cpassword == ""){
                                        $res = [ 'result' => $cpass  ];
                                    }
                                    elseif($password != $cpassword){
                                        $res = [ 'result' => $passnt  ];
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
                            $res = [ 'result' => $imgjpg  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => $sclemail  ];
                    }        
            }
            else{
                $res = [ 'result' => 'Invalid Operation'  ];
            }

           return $this->json($res);

        }
            
            public function approveallstudents()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $student_table = TableRegistry::get('student');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $student_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Student status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function approveallteachers()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $employee_table = TableRegistry::get('employee');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $employee_table->query()->update()->set([ 'status' => 1, 'lefts' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Teachers status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function subjectapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                 $uid = $this->request->data('val') ;
                 $sts = $this->request->data('sts') ; 
                $subjects_table = TableRegistry::get('subjects');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $subjects_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $subjects_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Subject status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function knowledgeapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $knowledge_table = TableRegistry::get('knowledge_base');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $knowledge_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $knowledge_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Knowledge base status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function studentapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $student_table = TableRegistry::get('student');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $student_table->find()->select(['id'])->where(['id'=> $uid ])->first();
                //echo $sid; die;
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $student_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Student status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function teacherapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $employee_table = TableRegistry::get('employee');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $employee_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $employee_table->query()->update()->set([ 'status' => $status, 'lefts' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Teacher status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function classsubapprovestatus()
            {   
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $classSub_table = TableRegistry::get('class_subjects');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sid = $classSub_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $classSub_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Class Sub status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function classapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $class_table = TableRegistry::get('class');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $class_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $class_table->query()->update()->set([ 'active' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Class status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function approveexamsass($schoolid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                //$schoolid = $this->Cookie->read('id');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $subjects_table = TableRegistry::get('subjects');
                $employee_table = TableRegistry::get('employee');
                $class_table = TableRegistry::get('class');
                $que_table = TableRegistry::get('exam_ass_question');
                $retrieve_examsassessment = $exams_assessment_table->find()->where(['status' => 0 , 'md5(school_id)'=> $schoolid ])->toArray() ; 
					
				foreach($retrieve_examsassessment as $key =>$approvecoll)
        		{
        			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examsassessment[$key]['subject_id'] ])->toArray();
        			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
        			
        			$retrieve_class = $class_table->find()->select(['c_name', 'c_section'])->where(['id' => $retrieve_examsassessment[$key]['class_id']  ])->toArray();
        			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
        			
        			if(!empty($retrieve_examsassessment[$key]['teacher_id']))
        			{
            			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examsassessment[$key]['teacher_id']  ])->toArray();
            			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']." ".$retrieve_employee[0]['l_name'];
        			}
        			else
        			{
        			    $approvecoll->teacher_name = "School";
        			}
        			
        			if($retrieve_examsassessment[$key]['exam_format'] == "custom")
        			{
        			    $retrieve_quecount = $que_table->find()->where(['exam_id' => $retrieve_examsassessment[$key]['id']  ])->count();
        			    $approvecoll->addquestion = $retrieve_quecount;
        			}
        			else
        			{
        			    $approvecoll->addquestion = 1;
        			}
        		}
					
				
				$this->set("sclid", $schoolid);
	            $this->set("approvedetails", $retrieve_examsassessment);
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function pdf($exmid =null)
            {
                
                $que_table = TableRegistry::get('exam_ass_question');
                $exam_table = TableRegistry::get('exams_assessments');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $school_table = TableRegistry::get('company');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_exam = $exam_table->find()->where([ 'id' => $exmid ])->toArray();
                $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam[0]['class_id'] ])->toArray();
                $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam[0]['subject_id'] ])->toArray();
                $retrieve_questions = $que_table->find()->where([ 'exam_id' => $exmid ])->toArray();
                $schoolid = $retrieve_exam[0]['school_id'];
                $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
                $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
			    $n =1;
			    $questns = '';
			    foreach($retrieve_questions as $ques)
			    {
			        $questns .= '<tr><td style="width: 100%;"><table style="width: 100%; "><tr>';
			        
                	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px; font-style:normal; font-weight:16px !important; margin-bottom:30px !important;">';
                	
                	$questns .= "<div  style='margin-left:20px; ' >
                	<span id='ques' style=' width:95%;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
                	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
                	
			        if($ques['ques_type'] == "objective")
			        {
			            $questns .= "<div style='margin:7px 0 7px 0;'>";
			            $options = explode("~^", $ques['options']);
			            $m = 1;
			            $chckbox = "<input type='radio' style='margin-left:25px; '>";
			            foreach($options as $opt)
			            {
			                $questns .= "<div>".$chckbox." <span id='options' style='margin-left:5px;'>".$opt."</span></div>";
			                $m++;
			            }
			            $questns .= "</div>";
			            
			        };
                	$questns .= "</div>";
			            
                	$questns .= '</th>';
                	
                	$questns .= "</tr></table</td></tr>";
                	
			        
			        
			        $n++;
			    }
			    if($retrieve_exam[0]['type'] == "Assessment")
			    {
			        $type = "Assignment";
			    }
			    else
			    {
			        $type = $retrieve_exam[0]['type'];
			    }
			    
			    if($retrieve_exam[0]['type'] == "Exams")
			    {
			        $extype = "(".$retrieve_exam[0]['exam_type'].")";
			    }
			    else
			    {
			        $extype = "";
			    }
			    $type_request = $type." ".$extype;
			    $header = '<table style=" width: 100%;">
                		    <tbody>
                			    <tr>
                			        <td  style="width: 100%;">
                			            <table style="width: 100%;  ">
                    			        <tr>
                            			    <td  style="width: 30%; float:left; ">
                            			        <table style="width: 100%;  ">
                            			            <tr>
                            						    <th style="width: 100%; text-align:center;"><span> '.$school_logo.' </span></th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($retrieve_school[0]['comp_name']).'</th>
                            						</tr>
                            						
                            						<tr>
                        						        <th></th>
                        						    </tr>
                            					</table>
                            			    </td>
                            				<td style="width: 33%; float:left; text-align:center;">
                            					<table style="width: 100%;  ">
                            						<tr>
                            						    <th style="width: 100%; text-align:center;  font-size: 16px;">'.$type_request .'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>Subject: </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                            						</tr>
                            						<tr>
                        						        <th style=" font-size: 14px;"> <span><b>Class: </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'</span></th>
                        						    </tr>
                            					</table>
                            				</td>
                            				<td style="width: 37%;  float:left; text-align:right;">
                            					<table style="width: 100%;  ">
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>Maximum Marks: </span>'.$retrieve_exam[0]['max_marks'].'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>Start Date/Time: </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>End Date/Time: </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
                            						</tr>
                            					</table>
                            				</td>
                			            </tr>
                			            </table>
                			        </td>
                			</tr>
                			<tr>
                    			<td style="width: 100%;">
                    			    <table style="width: 100%;">
                						<tr>
                						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;"><span> Special Instructions: </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span></th>
                						</tr>
                					</table>
                    			</td>
                			</tr>
                			'.$questns.'
                		</tbody>
                		</table>';
			    
			    $title = $retrieve_sub[0]['subject_name'];
			    
			    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
			    //print_r($viewpdf); die;
			    $dompdf = new Dompdf();
        		$dompdf->loadHtml($viewpdf);	
        		
        		$dompdf->setPaper('A4', 'Portrait');
        		$dompdf->render();
        		$dompdf->stream($title.".pdf", array("Attachment" => false));

                exit(0);
            }
            
            public function examassapprovestatus($examassid)
            {
                $exams_assessment_table = TableRegistry::get('exams_assessments');  
                $student_table = TableRegistry::get('student');  
                $retrieve_examsassessment = $exams_assessment_table->find()->select(['class_id', 'show_exmfrmt', 'school_id', 'type', 'exam_format'])->where(['id'=> $examassid ])->toArray() ;
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                    
                if($retrieve_examsassessment[0]['type'] == "Exams" )
                {
                    $submitexams_table = TableRegistry::get('submit_exams');
                    
                    $del = $submitexams_table->query()->delete()->where([ 'exam_id' => $examassid ])->execute(); 
                    
                    $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $retrieve_examsassessment[0]['class_id'] ])->toArray() ;
                    $schoolId = $retrieve_examsassessment[0]['school_id'];
                    $exam_formt = $retrieve_examsassessment[0]['exam_format'];
                    $examformt = $retrieve_examsassessment[0]['show_exmfrmt'];
                    /*if($exam_formt == "pdf")
                    {
                        $examformt = "pdf";
                    }*/
                    foreach($retrieve_students as $students)
                    {
                        $stud_id = $students['id'];
                        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
                        $passcode = substr(str_shuffle($str_result), 0, 6); 
                        
                        
        		
                		    $submitexams = $submitexams_table->newEntity();
                            $submitexams->exam_id = $examassid;
                            $submitexams->student_id = $stud_id;
                            $submitexams->passcode = $passcode;
                            $submitexams->examformt = $examformt;
                    		$submitexams->status = 0;
                            $submitexams->school_id = $schoolId;
                            $submitexams->passcode_created_date = time();
                                                  
                            $saved = $submitexams_table->save($submitexams);
                        
                    }
                }
                
                
				$update = $exams_assessment_table->query()->update()->set([ 'status' => 1 ])->where([ 'id' => $examassid  ])->execute();
				
				
                
                if($update)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                {
                    $res = [ 'result' => 'Failed Operation'  ];
                }

                return $this->json($res);
            }
            
            public function approveexamassall()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $exams_assessment_table = TableRegistry::get('exams_assessments');  
                $student_table = TableRegistry::get('student'); 
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $examassid)
                {
                     
                    $retrieve_examsassessment = $exams_assessment_table->find()->select(['class_id', 'show_exmfrmt', 'exam_format', 'school_id', 'type'])->where(['id'=> $examassid ])->toArray() ;
                    
                    if($retrieve_examsassessment[0]['type'] == "Exams" )
                    {
                        
                        $submitexams_table = TableRegistry::get('submit_exams');
                        $del = $submitexams_table->query()->delete()->where([ 'exam_id' => $examassid ])->execute(); 
                    
                        $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $retrieve_examsassessment[0]['class_id'] ])->toArray() ;
                        $schoolId = $retrieve_examsassessment[0]['school_id'];
                        $exam_formt = $retrieve_examsassessment[0]['exam_format'];
                        $examformt =$retrieve_examsassessment[0]['show_exmfrmt'];
                        /*if($exam_formt == "pdf")
                        {
                            $examformt = "pdf";
                        }*/
                        foreach($retrieve_students as $students)
                        {
                            $stud_id = $students['id'];
                            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
                            $passcode = substr(str_shuffle($str_result), 0, 6); 
                            
                            $submitexams_table = TableRegistry::get('submit_exams');
            		
                    		    $submitexams = $submitexams_table->newEntity();
                                $submitexams->exam_id = $examassid;
                                $submitexams->student_id = $stud_id;
                                $submitexams->passcode = $passcode;
                        		$submitexams->status = 0;
                        		$submitexams->examformt = $examformt;
                                $submitexams->school_id = $schoolId;
                                $submitexams->passcode_created_date = time();
                                                      
                                $saved = $submitexams_table->save($submitexams);
                            
                        }
                    }
                    $stats = $exams_assessment_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $examassid  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "exam/assessment status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function exams($schoolid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $subjects_table = TableRegistry::get('subjects');
                $employee_table = TableRegistry::get('employee');
                $comp_table = TableRegistry::get('company');
                $class_table = TableRegistry::get('class');
                $que_table = TableRegistry::get('exam_ass_question');
                $retrieve_examsassessment = $exams_assessment_table->find()->where(['md5(school_id)'=> $schoolid , 'type' => 'Exams', 'status' => 1])->order(['id' => 'DESC'])->toArray() ; 
                /*$retrieve_classes = $class_table->find()->where(['md5(school_id)' => $schoolid , 'active' => 1 ])->toArray();
                $retrieve_subjects = $subjects_table->find()->where(['md5(school_id)' => $schoolid , 'status' => 1 ])->toArray();*/
                $retrieve_school = $comp_table->find()->where(['md5(id)' => $schoolid , 'status' => 1 ])->toArray();
					
					
					
				foreach($retrieve_examsassessment as $key =>$approvecoll)
        		{
        			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examsassessment[$key]['subject_id'] ])->toArray();
        			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
        			
        			$retrieve_class = $class_table->find()->select(['c_name', 'c_section'])->where(['id' => $retrieve_examsassessment[$key]['class_id']  ])->toArray();
        			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
        			
        			if(!empty($retrieve_examsassessment[$key]['teacher_id']))
        			{
            			/*$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examsassessment[$key]['teacher_id']  ])->toArray();
            			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']." ".$retrieve_employee[0]['l_name'];*/
            			$approvecoll->teacher_name = "Teacher";
        			}
        			else
        			{
        			    $approvecoll->teacher_name = "School";
        			}
        			if($retrieve_examsassessment[$key]['exam_format'] == "custom")
        			{
        			    $retrieve_quecount = $que_table->find()->where(['exam_id' => $retrieve_examsassessment[$key]['id']  ])->count();
        			    $approvecoll->addquestion = $retrieve_quecount;
        			}
        			else
        			{
        			    $approvecoll->addquestion = 1;
        			}
        		}
					
				/*$this->set("classDetails", $retrieve_classes);*/
				$this->set("schoolDetails", $retrieve_school);
	            $this->set("approvedetails", $retrieve_examsassessment);
                $this->viewBuilder()->setLayout('usersa');
            }
            public function instruction()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['id'];
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $get_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                    return $this->json($get_data);
                    print_r($get_data);
                    return $this->json($get_data);
                }  
            }
            
            public function approvegallery($schoolid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                //$schoolid = $this->Cookie->read('id');
                $gallery_table = TableRegistry::get('gallery');
                $retrieve_gallery = $gallery_table->find()->where(['md5(school_id)' => $schoolid, 'status' => 0])->toArray() ;
                $this->set("gallery_details", $retrieve_gallery);
                $this->set("sclid", $schoolid);
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function approveallgallery()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $gallery_table = TableRegistry::get('gallery');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $gallery_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Gallery status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function galleryapprovestatus()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $gallery_table = TableRegistry::get('gallery');
                $activ_table = TableRegistry::get('activity');
    
                $sid = $gallery_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $gallery_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Gallery status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }

            public function approvenotify($schoolid)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $notify_table = TableRegistry::get('notification');
                $retrieve_notify = $notify_table->find()->where(['status' => 0])->toArray() ;
                $this->set("notify_details", $retrieve_notify); 
                $this->set("sclid", $schoolid); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function casesyoume($schoolid)
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $superadminid = $this->Cookie->read('sid'); 
                $company_table = TableRegistry::get('company');
                $users_table = TableRegistry::get('users');
                $message_table = TableRegistry::get('contactyoume');
                if(!empty($superadminid)) 
                {
                    $retrieve_msg_table = $message_table->find()->where(['md5(school_id)' => $schoolid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                    
                   
                    foreach($retrieve_msg_table as $key =>$msg)
            		{
            		    $msg->sender = ''; 
            		    if($msg->from_type == 'school'){
            		        $retrieve_company = $company_table->find()->where(['id'=> $msg->from_id ])->first();
            		        if(!empty($retrieve_company))
            		        {
                		        $msg->sender = $retrieve_company->comp_name; 
            		        }
            		        
            		    }else if($msg->from_type == 'superadmin'){
            		        $retrieve_users = $users_table->find()->where(['id'=> $msg->from_id ])->first();
            		        if(!empty($retrieve_users)) {
            		            $msg->sender = $retrieve_users->fname." ". $retrieve_users->lname; 
            		        }
            		    }
            		    
            		    $count_read = $message_table->find()->where(['parent'=> $msg->id, 'to_type'=>'superadmin', 'read_msg'=>'0'])->count();
            		    $msg->read_count = $count_read; 
            		}
                    $this->set("message_details", $retrieve_msg_table);  
                    $this->viewBuilder()->setLayout('usersa');
                }
                else
                {
                     return $this->redirect('/login/') ;  
                }
            }
            
            public function approveallnotify()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data['val'] ; 
                if(empty($uid[0]))
                {
                    array_splice($uid, 0, 1);
                }
                
                $aid = implode(",", $uid);
                $notify_table = TableRegistry::get('notification');
                $activ_table = TableRegistry::get('activity');
    
                foreach($uid as $ids)
                {
                    $stats = $notify_table->query()->update()->set([ 'status' => 1])->where([ 'id ' => $ids  ])->execute();
                }
                
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Notification status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value =    $aid;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
               
    
                return $this->json($res);
            }
            
            public function notifyapprovestatus()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ; 
                $notify_table = TableRegistry::get('notification');
                $activ_table = TableRegistry::get('activity');
    
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function status()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('sclid') ;
                $sts = $this->request->data('sclsts') ;
                $stsrsn = $this->request->data('statusrsn') ;
                $company_table = TableRegistry::get('company');
                $activ_table = TableRegistry::get('activity');

                $userid = $company_table->find()->select(['id'])->where(['id'=> $uid ])->first();
				$status = $sts == 1 ? 0 : 1;
                if($userid)
                {   
                    $stats = $company_table->query()->update()->set([ 'status' => $status, 'status_reason' => $stsrsn])->where([ 'id' => $uid  ])->execute();
					
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "School status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
        
        public function csvread()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $class_table = TableRegistry::get('class');
            $subject_table = TableRegistry::get('subjects');
            $classsub_table = TableRegistry::get('class_subjects');
            $fileNameclssub = "https://you-me-globaleducation.org/school/webroot/classes_subjectss.csv";
            $csvDataclssub = file_get_contents($fileNameclssub);
            $linesclssub = explode(PHP_EOL, $csvDataclssub);
            
            $sclid = 32;
            foreach ($linesclssub as $rowclssub) 
            {
            	$dataclssub = str_getcsv($rowclssub);
            	
            	$clsname = $dataclssub[0];
            	$sclsection = $dataclssub[1];
            	
            	$subjctss = explode(",", $dataclssub[2]);
            	
            	//print_r($subjctss); 
            	
            	$retrieve_cls1 = $class_table->find()->select(['id'])->where(['c_name' => $clsname , 'school_sections'=> $sclsection, 'school_id'=> $sclid ])->first();
            	$clsid = 	$retrieve_cls1['id'];
            	$sub = [];
            	foreach($subjctss as $subj)
            	{
            	    $subj = trim($subj);
            		$retrieve_sub = $subject_table->find()->select(['id'])->where(['subject_name' => $subj , 'school_id'=> $sclid ])->first();
            		echo $retrieve_sub['id'];
            		$sub[] = $retrieve_sub['id'];
            	}
            	
            	echo $subid = implode(",", $sub);
            	
            
            	$classsub = $classsub_table->newEntity();
            	$classsub->class_id = $clsid;
            	$classsub->subject_id = $subid;
            	$classsub->status = 1;
            	$classsub->school_id = $sclid;
            	$classsub->created_date = time();
            						  
            	//$clssub = $classsub_table->save($classsub);
            }
            
        }

        public function timeSlotsPrepare(){
	        $starttime = $this->request->data('start'); 
	        $endtime = $this->request->data('end');  
	        $duration = $this->request->data('duration');  
	        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        	$time_slots = array();
        	$start_time    = strtotime($starttime); //change to strtotime
        	$end_time      = strtotime($endtime); //change to strtotime
        	 
        	$add_mins  = $duration * 60;
        	 
        	while ($start_time <= $end_time) // loop between time
        	{
        	   $time_slots[] = date("H:i", $start_time);
        	   $start_time += $add_mins; // to check endtime
        	}
        
        	return $this->json($time_slots);
        }   
         
        public function studentdelete()
        {
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $this->request->data('val') ;
            $student_table = TableRegistry::get('student');
            
            $comments_table1 = TableRegistry::get('application_data_comments');
            $comments_table2 = TableRegistry::get('howitworks_comments');
            $comments_table3 = TableRegistry::get('intensive_english_comments');
            $comments_table4 = TableRegistry::get('internship_comments');
            $comments_table5 = TableRegistry::get('kindergarten_library_comments');
            $comments_table6 = TableRegistry::get('knowledge_center_comments');
            $comments_table7 = TableRegistry::get('knowledge_comments');
            $comments_table8 = TableRegistry::get('leadership_comments');
            $comments_table9 = TableRegistry::get('library_comments');
            $comments_table10 = TableRegistry::get('machine_learning_comments');
            $comments_table11 = TableRegistry::get('mentorship_comments');
            $comments_table12 = TableRegistry::get('newtechnologies_comments');
            $comments_table13 = TableRegistry::get('scholarship_comments'); 
            $comments_table14 = TableRegistry::get('state_exam_comments');
            $comments_table15 = TableRegistry::get('tutorial_comments');
            $comments_table16 = TableRegistry::get('discussion');
            $messages_table = TableRegistry::get('messages'); 
            $attendance_table = TableRegistry::get('attendance');
            $sclattendance_table = TableRegistry::get('attendance_school');
            $studentfee_table = TableRegistry::get('student_fee');
            $student_tutfee_table = TableRegistry::get('student_tutorial_fee'); 
            $submit_exams_table = TableRegistry::get('submit_exams');
            $student_tutlogins_table = TableRegistry::get('student_tutorial_logins');
            
            $stuid = $student_table->find()->select(['id'])->where(['id'=> $sid ])->first();
            
            if($stuid)
            {   
                
				$del = $student_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                if($del)
                {
                    $stid = $sid;
				    $del_comm1 = $comments_table1->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm2 = $comments_table2->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm3 = $comments_table3->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm4 = $comments_table4->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm5 = $comments_table5->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm6 = $comments_table6->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm7 = $comments_table7->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm8 = $comments_table8->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm9 = $comments_table9->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm10 = $comments_table10->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm11 = $comments_table11->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm12 = $comments_table12->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm13 = $comments_table13->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm14 = $comments_table14->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm15 = $comments_table15->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
                	$del_comm16 = $comments_table16->query()->delete()->where([ 'student_id' => $stid ])->execute();
                	$del_msg1 = $messages_table->query()->delete()->where([ 'from_id' => $stid, 'from_type' => 'student' ])->execute(); 
                	$del_msg2 = $messages_table->query()->delete()->where([ 'to_id' => $stid, 'to_type' => 'student' ])->execute();
                	$del_att = $attendance_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
                	$del_attscl = $sclattendance_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
                	$del_fee = $studentfee_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
                	$del_fee_tut = $student_tutfee_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
                	$del_exams_stud = $submit_exams_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
                	$del_fee_tutlogins = $student_tutlogins_table->query()->delete()->where([ 'student_id' => $stid ])->execute();
                	
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
        
        public function deleteallstudents()
        {
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data['val'] ; 
            $student_table = TableRegistry::get('student');
            $comments_table1 = TableRegistry::get('application_data_comments');
            $comments_table2 = TableRegistry::get('howitworks_comments');
            $comments_table3 = TableRegistry::get('intensive_english_comments');
            $comments_table4 = TableRegistry::get('internship_comments');
            $comments_table5 = TableRegistry::get('kindergarten_library_comments');
            $comments_table6 = TableRegistry::get('knowledge_center_comments');
            $comments_table7 = TableRegistry::get('knowledge_comments');
            $comments_table8 = TableRegistry::get('leadership_comments');
            $comments_table9 = TableRegistry::get('library_comments');
            $comments_table10 = TableRegistry::get('machine_learning_comments');
            $comments_table11 = TableRegistry::get('mentorship_comments');
            $comments_table12 = TableRegistry::get('newtechnologies_comments');
            $comments_table13 = TableRegistry::get('scholarship_comments'); 
            $comments_table14 = TableRegistry::get('state_exam_comments');
            $comments_table15 = TableRegistry::get('tutorial_comments');
            $comments_table16 = TableRegistry::get('discussion');
            $messages_table = TableRegistry::get('messages'); 
            $attendance_table = TableRegistry::get('attendance');
            $sclattendance_table = TableRegistry::get('attendance_school');
            $studentfee_table = TableRegistry::get('student_fee');
            $student_tutfee_table = TableRegistry::get('student_tutorial_fee'); 
            $submit_exams_table = TableRegistry::get('submit_exams');
            $student_tutlogins_table = TableRegistry::get('student_tutorial_logins');
                
            foreach($uid as $ids)
            {
                
                $stats = $student_table->query()->delete()->where([ 'id' => $ids ])->execute();
                $stid = $ids ;
                $del_comm1 = $comments_table1->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm2 = $comments_table2->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm3 = $comments_table3->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm4 = $comments_table4->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm5 = $comments_table5->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm6 = $comments_table6->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm7 = $comments_table7->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm8 = $comments_table8->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm9 = $comments_table9->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm10 = $comments_table10->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm11 = $comments_table11->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm12 = $comments_table12->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm13 = $comments_table13->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm14 = $comments_table14->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm15 = $comments_table15->query()->delete()->where([ 'user_id' => $stid ])->execute(); 
            	$del_comm16 = $comments_table16->query()->delete()->where([ 'student_id' => $stid ])->execute();
            	$del_msg1 = $messages_table->query()->delete()->where([ 'from_id' => $stid, 'from_type' => 'student' ])->execute(); 
            	$del_msg2 = $messages_table->query()->delete()->where([ 'to_id' => $stid, 'to_type' => 'student' ])->execute();
            	$del_att = $attendance_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
            	$del_attscl = $sclattendance_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
            	$del_fee = $studentfee_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
            	$del_fee_tut = $student_tutfee_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
            	$del_exams_stud = $submit_exams_table->query()->delete()->where([ 'student_id' => $stid ])->execute(); 
            	$del_fee_tutlogins = $student_tutlogins_table->query()->delete()->where([ 'student_id' => $stid ])->execute();
            }
            
            
            if($stats)
            {
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = [ 'result' => 'not delete'  ];
            }    
            

            return $this->json($res);
        }
}

  




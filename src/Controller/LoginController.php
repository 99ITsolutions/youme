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
class LoginController  extends AppController
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
            unset($_COOKIE['sid']);
            unset($_COOKIE['id']);
            unset($_COOKIE['sesscode']);
            unset($_COOKIE['sessionid']);
            unset($_COOKIE['cid']);
            unset($_COOKIE['subid']);
            unset($_COOKIE['tid']);
            unset($_COOKIE['pid']);
            unset($_COOKIE['stid']);
            
            $this->viewBuilder()->setLayout('login');
        }

        public function logincheck()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $users_table = TableRegistry::get('users');
                $company_table = TableRegistry::get('company');
                $emp_table = TableRegistry::get('employee');
                $student_table = TableRegistry::get('student');
                $class_table = TableRegistry::get('class');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $cvendor_table = TableRegistry::get('canteen_vendor');
                $activ_table = TableRegistry::get('activity');
                
	            $session_table = TableRegistry::get('session');
	            $parentlogin_table = TableRegistry::get('parent_logindetails');
	            $logtrack_table = TableRegistry::get('track_logged');
	            $language = $this->request->data('language');
	            $scllanguage = $this->request->data('langu');
	            if(!empty($language))
	            {
	                $language = $language;
	            }
	            elseif(!empty($scllanguage))
	            {
	                $language = $scllanguage;
	            }
	            else
	            {
	                $language = '';
	            }

                if($this->request->data('email') != "" && $this->request->data('password') != ""  )
                {
			        /*$this->Cookie->config([
                        'expires' => '+300 minute',
                    ]);*/
		                $mon = date('M',strtotime('now'));  
            
                        if($mon == "Jan" || $mon == "Feb" || $mon == "Mar" ){
                            $endyear = date('Y',strtotime('now'));
                                $startyear = $endyear - 1;
                        }
                        else{ 
                            $startyear = date('Y',strtotime('now'));
                                $endyear = $startyear + 1;
                        }
                            
                        $session_table = TableRegistry::get('session');
    					$now = strtotime('now');
        
                        $currentyear = date("Y", $now);
                        $currentmonth = date("m", $now);
                        
                        $retrieve_session = $session_table->find()->toArray() ;
                        foreach($retrieve_session as $getsession) 
                        {
                            $getmonthids_start = date("m", strtotime($getsession['startmonth']));
                            $getmonthids_end = date("m", strtotime($getsession['endmonth']));
                            $strtyr =  $getsession['startyear'];
                            $endyr =  $getsession['endyear'];
                            
                            if( (($currentyear == $strtyr) && ($currentmonth <= 12 && $currentmonth >= $getmonthids_start)) || (($currentyear == $endyr) && ($currentmonth <= $getmonthids_end && $currentmonth >= 1)) )
                            {
                                $session_id = $getsession['id'];
                            }
                        }
                        
                        $email = trim($this->request->data('email'));
                        $pass = trim($this->request->data('password'));
                        $retrieve_users = $users_table->find()->select(['id' , 'password', 'role' ])->where([ 'email' =>$email , 'password' =>  $pass ]) ;
                        $user_details = $retrieve_users->toArray() ; 
                        
                        $retrieve_company = $company_table->find()->select(['id' , 'password' ,'www'])->where([ 'email' => $email , 'password' =>  $pass]) ;
                        $company_details = $retrieve_company->toArray() ;
                        
                        $retrieve_sclsub = $sclsub_table->find()->where([ 'email' => $email , 'password' =>  $pass , 'status' => '1'  ]) ;
                        $sclsub_details = $retrieve_sclsub->toArray() ;

		                $retrieve_emp = $emp_table->find()->select(['id' , 'password', 'school_id'])->where(['email' => $email , 'password' =>  $pass, 'status' => '1' ]) ;
                        $emp_details = $retrieve_emp->toArray() ;

	                    $retrieve_student = $student_table->find()->select(['id' , 'password', 'school_id', 'class' ])->where(['adm_no' => $email])->orWhere(['email' => $email])->andWhere(['password' => $pass , 'session_id' => $session_id , 'status' => '1' ]);
                        $student_details = $retrieve_student->toArray() ; 
                        
                        $retrieve_parent = $parentlogin_table->find()->where([ 'parent_email' => $email , 'parent_password' => $pass ]) ;
                        $parent_details = $retrieve_parent->toArray() ;
                        
                        $retrieve_cvendor = $cvendor_table->find()->where([ 'email' => $email , 'password' => $pass ]) ;
                        $cvendor_details = $retrieve_cvendor->toArray() ;

                        $sess_code = $_SERVER['REMOTE_ADDR'];
                        $genactivity = '';
                        
                        if(empty($company_details[0]) && empty($cvendor_details[0]) && empty($emp_details[0]) && empty($student_details[0]) && empty($sclsub_details[0]) && empty($parent_details[0]) && empty($user_details[0]))
                        { 
                            $res = [ 'result' => 'email' ];
                        }
                        else
                        {
                            $rem = $this->request->data('remember');
                            
                            
                            if(!empty($user_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($user_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "Superadmin Logout") {*/
		                        $rem = $this->request->data('remember') ;
                                if($rem == "on")
                                {
                                    $this->Cookie->write('sid',  md5($user_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
				                }
                                else
                                {
                                    $this->Cookie->write('sid',  md5($user_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                /*session_start();
                                $_SESSION["supid"] = $user_details[0]['id'];*/
                                $this->request->session()->write('superid', $user_details[0]['id']);
                                $this->Cookie->write('sesscode',$sess_code );
                                //print_r($_SESSION); die;
                                
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Admin Login"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->value = $this->Cookie->read('sid')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "super_subadmin";
                                $track->login_id =  $user_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('supersub_tracklogintime', $trcksave->id);
                                
                                $sess_code = $sess_code."_".$saved->id;
                                $update_user = $users_table->query()->update()->set(['sesscode' => $sess_code , 'login_logout_key' => 1])->where(['id' => $user_details[0]['id'] ])->execute()  ;
                                $actid = $saved->id;
                                $this->Cookie->write('superad_activitiesid',$actid);
                                $res = [ 'result' => 'success_superadmin'  ];
                                /*}
                                else
                                {
                                    $res = ['result' => 'This user is already logged in on another device or browser'];
                                }*/
                            }
                            elseif(!empty($company_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($company_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "school logout") {*/
                                $genactivity = $this->request->data('genactvity');
                                if($rem == "on")
                                {
                                    $this->Cookie->write('id',  md5($company_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('www',  md5($company_details[0]['www']) ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Company") ,  time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('id',  md5($company_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('www',  md5($company_details[0]['www']) ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Company") ,  time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600);
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                            
                                //$sess_code = uniqid() ; 
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                $activity = $activ_table->newEntity();
                                if($genactivity == "super")
                                {
                                    $activity->action =  "Super Company Login"  ;
                                }
                                else
                                {
                                    $activity->action =  "Company Login"  ;
                                }
                                
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->value = $this->Cookie->read('id')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                
                                $sess_code = $sess_code."_".$saved->id;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "school";
                                $track->login_id =  $company_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('scl_tracklogintime', $trcksave->id);
                                
                                if($genactivity == "super")
                                {
                                    //$update_company = $company_table->query()->update()->set(['sesscode_super' => $sess_code, 'login_logout_key' => 1 ])->where(['id' => $company_details[0]['id'] ])->execute()  ;
                                    $actid = $saved->id;
                                    $this->Cookie->write('superschool_activitiesid',$actid);
                                }
                                else
                                {
                                    $update_company = $company_table->query()->update()->set(['sesscode' => $sess_code, 'login_logout_key' => 1 ])->where(['id' => $company_details[0]['id'] ])->execute()  ;
                                    $actid = $saved->id;
                                    $this->Cookie->write('school_activitiesid',$actid);
                                }
                               
                                $res = [ 'result' => 'success'  ];
                                /*}
                                else
                                {
                                    $res = ['result' => 'This user is already logged in on another device or browser'];
                                }*/
                            }
                            elseif(!empty($cvendor_details[0]))
                            {
                                $genactivity = $this->request->data('genactvity');
                                if($rem == "on")
                                {
                                    $this->Cookie->write('cid',  md5($cvendor_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Canteen Vendor") ,  time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('cid',  md5($cvendor_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Canteen Vendor") ,  time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600);
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                            
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Canteen Vendor Login" ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->value = $this->Cookie->read('id')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                
                                $sess_code = $sess_code."_".$saved->id;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "canteen vendor";
                                $track->login_id =  $cvendor_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('canteen_tracklogintime', $trcksave->id);
                                $actid = $saved->id;
                                $this->Cookie->write('cvendor_activitiesid',$actid);
                                $res = [ 'result' => 'success_cvendor'  ];
                                
                            }
                            else if(!empty($sclsub_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($sclsub_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "school subadmin logout") {*/
                                if($rem == "on")
                                {
                                    $this->Cookie->write('subid',  md5($sclsub_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('www',  md5("School Subadmin") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("School Subadmin") ,  time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('subid',  md5($sclsub_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('www',  md5("School Subadmin") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("School Subadmin") ,  time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                 $this->Cookie->write('sessionid',  $session_id  ,   time()+3600);
                                //$sess_code = uniqid() ; 
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                

                                $activity = $activ_table->newEntity();
                                $activity->action =  "School Subadmin Login"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->value = $this->Cookie->read('subid')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                $actid = $saved->id;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "school_subadmin";
                                $track->login_id =  $sclsub_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('sclsub_tracklogintime', $trcksave->id);
                                
                                $sess_code = $sess_code."_".$saved->id;
                                $update_company = $sclsub_table->query()->update()->set(['sesscode' => $sess_code , 'login_logout_key' => 1])->where(['id' => $sclsub_details[0]['id'] ])->execute()  ;
                                
                                $this->Cookie->write('sclsub_activitiesid',$actid);
                                $res = [ 'result' => 'success_subadmin'  ];
                                /*}
                                else
                                {
                                    $res = ['result' => 'This user is already logged in on another device or browser'];
                                }*/
                            }
                            else if(!empty($emp_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($emp_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "employee logout") {*/
                                if($rem == "on")
                                {
                                    $this->Cookie->write('tid',  md5($emp_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('www',  md5("Employee") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Employee") ,  time()+3600 );
				                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
				                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('tid',  md5($emp_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('www',  md5("Employee") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Employee") ,  time()+3600 );
                					$this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                					$this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                
                                //$sess_code = uniqid() ;  
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Employee Login"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->value = $this->Cookie->read('tid')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "teacher";
                                $track->login_id =  $emp_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('teacher_tracklogintime', $trcksave->id);
                                
                                $sess_code = $sess_code."_".$saved->id;
                                $update_company = $emp_table->query()->update()->set(['sesscode' => $sess_code , 'login_logout_key' => 1])->where(['id' => $emp_details[0]['id'] ])->execute()  ;

                                $actid = $saved->id;
                                $this->Cookie->write('tchr_activitiesid',$actid);
                                $res = [ 'result' => 'success_teacher'  ];
                                
                                //print_r($res); die;
                                /*}
                                else
                                {
                                    $res = ['result' => 'This user is already logged in on another device or browser'];
                                }*/
                            }
                            else if(!empty($parent_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($parent_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "parent logout") {*/
                                
                                $retrieve_students = $student_table->find()->select(['id'])->where(['parent_id' => $parent_details[0]['id'] , 'session_id' => $session_id , 'status' => '1' ]);
                                $student_detailss = $retrieve_students->toArray() ; 
                                if(!empty($student_detailss))
                                {
                                if($rem == "on")
                                {
                                    $this->Cookie->write('pid',  md5($parent_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('www',  md5("Parent") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Parent") ,  time()+3600 );
				                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
				                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('pid',  md5($parent_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('www',  md5("Parent") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Parent") ,  time()+3600 );
                					$this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                					$this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                
                                //$sess_code = uniqid() ;  
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Parent Login"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->value = $this->Cookie->read('pid')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "parent";
                                $track->login_id =  $parent_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('parent_tracklogintime', $trcksave->id);
                                
                                $sess_code = $sess_code."_".$saved->id;
                                $update_company = $parentlogin_table->query()->update()->set(['sesscode' => $sess_code , 'login_logout_key' => 1])->where(['id' => $parent_details[0]['id'] ])->execute()  ;


                                $actid = $saved->id;
                                $this->Cookie->write('parnt_activitiesid',$actid);
                                $res = [ 'result' => 'success_parent'  ];
                                }
                                else
                                {
                                    $res = [ 'result' => 'No student exist in this session'  ];
                                }
                                
                            }
                            else if(!empty($student_details[0]))
                            {
                                /*$getactivity = $activ_table->find()->where(['value' => md5($student_details[0]['id'])])->order(['id' => 'DESC'])->first();
                                if(empty($getactivity) || strtolower($getactivity['action']) == "student logout") {*/
                                if($rem == "on")
                                {
                                    $this->Cookie->write('stid',  md5($student_details[0]['id']) ,   time()+3600 );
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('www',  md5("Student") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Student") ,  time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                else
                                {
                                    $this->Cookie->write('stid',  md5($student_details[0]['id'])  ,   time()+3600);
                                    $this->Cookie->write('sessionid',  $session_id  ,   time()+3600 );
                                    $this->Cookie->write('www',  md5("Student") ,  time()+3600 );
                                    $this->Cookie->write('logtype',  md5("Student") ,  time()+3600 );
                                    $this->Cookie->write('language',  $language ,   time()+3600 );
                                }
                                
                                //$sess_code = uniqid() ; 
                                $this->Cookie->write('sesscode',$sess_code );
                                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                                
                                $studentid = $student_details[0]['id'] ;
                                $classid = $student_details[0]['class']; 


                                $activity = $activ_table->newEntity();
                                $activity->action =  "Student Login"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->value = $this->Cookie->read('stid')   ;
                                $activity->created = strtotime('now');
                                $saved = $activ_table->save($activity) ;
                                $actid = $saved->id;
                                $this->Cookie->write('stud_activitiesid',$actid);
                                
                                $track = $logtrack_table->newEntity();
                                $track->type =  "student";
                                $track->login_id =  $student_details[0]['id'] ;
                                $track->login_time = time();
                                $track->created_date = time();
                                $trcksave = $logtrack_table->save($track) ;
                                $this->request->session()->write('student_tracklogintime', $trcksave->id);
                                
                                $sess_code = $sess_code."_".$saved->id;
                                $update_student = $student_table->query()->update()->set(['sesscode' => $sess_code , 'login_logout_key' => 1])->where(['id' => $student_details[0]['id'] ])->execute()  ;

                                
                                $cls_sec = $class_table->find()->where(['id' => $classid])->first();
                                if((strtolower($cls_sec['school_sections']) == "creche") || (strtolower($cls_sec['school_sections']) == "maternelle"))
                                {
                                    $dash = "kinder";
                                }
                                else
                                {
                                    $dash = "senior";
                                }
                                $this->request->session()->write('dashb', $dash);
                                $res = [ 'result' => 'success_student' , 'dash' =>$dash ];
                                /*}
                                else
                                {
                                    $res = ['result' => 'This user is already logged in on another device or browser'];
                                }*/
                            }
                            
                            
                        }
                }
                else{
                    $res = [ 'result' => 'empty'  ];
                }
            }
            else{
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);
        }
            
        public function fpass()
        {
            $users_table = TableRegistry::get('users');
            $company_table = TableRegistry::get('company');
            $emp_table = TableRegistry::get('employee');
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $session_table = TableRegistry::get('session');
            $sclsubadmin_table = TableRegistry::get('school_subadmin');
            $parent_table = TableRegistry::get('parent_logindetails');
            

            if($this->request->data('email') != "")
            {
                $email = $this->request->data('email');
                   
                $retrieve_users = $users_table->find()->select(['id' , 'password' , 'fname', 'lname'])->where(['email' => $this->request->data('email') ]) ;
                $user_details = $retrieve_users->toArray() ; 
                
                $retrieve_company = $company_table->find()->select(['id' , 'password' ,'comp_name'])->where(['email' => $this->request->data('email')]) ;
                $company_details = $retrieve_company->toArray() ;
                
                $retrieve_sclsub = $sclsubadmin_table->find()->select(['id' , 'password' , 'fname', 'lname'])->where(['email' => $this->request->data('email')]) ;
                $sclsub_details = $retrieve_sclsub->toArray() ;

                $retrieve_emp = $emp_table->find()->select(['id' , 'password', 'f_name', 'l_name'])->where(['email' => $this->request->data('email')]) ;
                $emp_details = $retrieve_emp->toArray() ;

                $retrieve_student = $student_table->find()->select(['id' , 'password', 'f_name', 'l_name' ])->where(['email' => $this->request->data('email') ]) ;
                $student_details = $retrieve_student->toArray() ; 
                
                $retrieve_parent = $parent_table->find()->select(['id' , 'parent_password', 'f_name', 'l_name' ])->where(['parent_email' => $this->request->data('email') ]) ;
                $parent_details = $retrieve_parent->toArray() ; 

                if(empty($company_details[0]) && empty($emp_details[0]) && empty($student_details[0]) && empty($user_details[0]) && empty($parent_details[0]) && empty($sclsub_details[0]))
                {
                    $res = [ 'result' => 'No Email Exist.' ];
                }
                else
                {
                    if(!empty($user_details))
                    {
                        $to      = $this->request->data('email');
                        $username = $user_details[0]['fname']. " ". $user_details[0]['lname'];
                        $pass = $user_details[0]['password'];
                    }
                    else if(!empty($sclsub_details))
                    {
                        $to      = $this->request->data('email');
                        $username = $sclsub_details[0]['fname']. " ". $sclsub_details[0]['lname'];
                        $pass = $sclsub_details[0]['password'];
                    }
                    else if(!empty($company_details))
                    {
                        $to      = $this->request->data('email');
                        $username = $user_details[0]['comp_name'];
                        $pass = $user_details[0]['password'];
                    }
                    else if(!empty($emp_details))
                    {
                        $to      = $this->request->data('email');
                        $username = $emp_details[0]['fname']. " ". $emp_details[0]['lname'];
                        $pass = $emp_details[0]['password'];
                    }
                    else if(!empty($student_details))
                    {
                        $to      = $this->request->data('email');
                        $username = $student_details[0]['fname']. " ". $student_details[0]['lname'];
                        $pass = $student_details[0]['password'];
                    }
                                
                	$subject = "Forgot Password - You-me Global Education";	
                	
                	$headers = "MIME-Version: 1.0" . "\r\n";
                	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                	$headers .= 'From: Support <noreply@youmetechnologies.com>'. "\r\n";
                	$headers .= 'X-Priority: 3'. "\r\n";
                    $headers .= 'X-Mailer: PHP'. phpversion() ."\r\n";
                	//$headers .= 'Cc: yasir@outsourcingservicesusa.com, yasirkhancse@gmail.com' . "\r\n";
                	//$headers .= 'Bcc: myboss3@example.com, myboss4@example.com' . "\r\n";
                
                	$message = "<p>Hi $username,</p><br>
            	
                    <p>You requested for us to email your password to you as you had forgotten it. </p>
                    <p>Your email id is $email.</p>
                    <p>Your password is $pass.</p>
                    <p>If this request did not come from you, please disregards this email.</p><br>
                    	
                    <p>Thanks </p>
                    <p>Support Team, </p>
                    <p>You-Me Global Education</p>";
            
            	    $send = mail($to, $subject, $message, $headers);
	                if($send)
	                {
	                    $res = [ 'result' => 'success'  ];
	                }
	                else
	                {
	                    $res = [ 'result' => 'Email Not sent. Please Try Again!'  ];
	                }
                }
		    }
            else
            {
                $res = [ 'result' => 'Enter Valid Email Address'  ];
            }
        
            return $this->json($res);
        }
            
        /*public function getsesscheck()
        {
            //echo $_COOKIE['sesscode'];
            $sess_code = $_SERVER['REMOTE_ADDR'];
            $loginas = $this->request->data('loginas');
            
            $users_table = TableRegistry::get('users');
            $company_table = TableRegistry::get('company');
            $emp_table = TableRegistry::get('employee');
            $student_table = TableRegistry::get('student');
            $class_table = TableRegistry::get('class');
            $sclsub_table = TableRegistry::get('school_subadmin');
            $activ_table = TableRegistry::get('activity');
            $session_table = TableRegistry::get('session');
            $parentlogin_table = TableRegistry::get('parent_logindetails');
            
            $baseurl=$this->request->session()->read('baseurl');
            if($loginas == "superadmin")
            {
                $activitiesid = $this->Cookie->read('superad_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $sid = $this->Cookie->read('sid'); 
                $retrieve_users = $users_table->find()->select(['id' , 'sesscode' ])->where([ 'md5(id)' => $sid ])->first() ; 
                $sesscode = $retrieve_users['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('sid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['sid']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "superschool")
            {
                $activitiesid = $this->Cookie->read('superschool_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $uid = $this->Cookie->read('id');
                $retrieve_school = $company_table->find()->select(['id' , 'sesscode', 'sesscode_super' ])->where([ 'md5(id)' => $uid ])->first() ; 
                //print_r($retrieve_users);
                
                $sesscode = $retrieve_school['sesscode_super'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('id', '', time()-1000  , $baseurl );
                    unset($_COOKIE['id']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "school")
            {
                $activitiesid = $this->Cookie->read('school_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $uid = $this->Cookie->read('id');
                $retrieve_school = $company_table->find()->select(['id' , 'sesscode', 'sesscode_super' ])->where([ 'md5(id)' => $uid ])->first() ; 
                print_r($retrieve_school);
                
                $sesscode = $retrieve_school['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('id', '', time()-1000  , $baseurl );
                    unset($_COOKIE['id']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "student")
            {
                $activitiesid = $this->Cookie->read('stud_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $stid = $this->Cookie->read('stid');
                $retrieve_student = $student_table->find()->select(['id' , 'sesscode' ])->where([ 'md5(id)' => $stid ])->first() ; 
                //print_r($retrieve_users);
                
                $sesscode = $retrieve_student['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('stid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['stid']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "teacher")
            {
                $activitiesid = $this->Cookie->read('tchr_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $tid = $this->Cookie->read('tid');
                $retrieve_teacher = $emp_table->find()->select(['id' , 'sesscode' ])->where([ 'md5(id)' => $tid ])->first() ; 
                //print_r($retrieve_users);
                
                $sesscode = $retrieve_teacher['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('tid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['tid']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "parent") 
            {
                $activitiesid = $this->Cookie->read('parnt_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $pid = $this->Cookie->read('pid');
                $retrieve_parent = $parentlogin_table->find()->select(['id' , 'sesscode' ])->where([ 'md5(id)' => $pid ])->first() ; 
                //print_r($retrieve_users);
                
                $sesscode = $retrieve_parent['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('pid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['pid']);
                    $res = "notmatch";
                }
            }
            elseif($loginas == "schoolsubadmin")
            {
                //echo "hi";
                $activitiesid = $this->Cookie->read('sclsub_activitiesid'); 
                $sess_code = $sess_code."_".$activitiesid;
                $subid = $this->Cookie->read('subid');
                $retrieve_sclsubadmin = $sclsub_table->find()->select(['id' , 'sesscode' ])->where([ 'md5(id)' => $subid ])->first() ; 
                //print_r($retrieve_users);
                
                $sesscode = $retrieve_sclsubadmin['sesscode'];
                if($sess_code == $sesscode)
                {
                    $res = "match";
                }
                else
                {
                    setcookie('subid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['subid']);
                    $res = "notmatch";
                }
            }
            
            return $this->json($res);
        }*/
        
        public function nhelp()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {  
                //$to = "nancy@outsourcingservicesusa.com";
                $to = "needhelpyoume@gmail.com,a.bitulu1@gmail.com,kennedy.jvis@gmail.com,Membership@you-me-globaleducation.org,contactus@you-me-globaleducation.org";
                $username =  $this->request->data('lname')." ".$this->request->data('fname');
                $from =  $this->request->data('email');
                $phone =  $this->request->data('phone');
                $schoolname =  $this->request->data('schoolname');
                $whocontact = $this->request->data('whocontact');
                $reason = $this->request->data('reason');
                $message = $this->request->data('message');
                
                $needhelp_table = TableRegistry::get('need_help');
                
                $name = "You-Me Global Education";
                $subject = 'Need Help Form - You-Me Global Education';
                
                $htmlContent = '
                <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline><p>
                        This mail is to notify you that one of your users need help. Below are the details. 
                    </p></multiline>
                </td>
                </tr>
                <tr>
                    <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                        <multiline>
                        <p>First Name: '.$this->request->data('fname').' </p>
                        <p>Last Name: '.$this->request->data('lname').' </p>
                        <p>Email: '.$from.' </p>
                        <p>Phone Number: '.$phone.' </p>
                        <p>School Name: '.$schoolname.' </p>
                        <p>Who Contact: '.$whocontact.' </p>
                        <p>Reason: '.$reason.' </p>
                        <p>Message: '.$message.' </p>
                        </multiline>
                    </td>
                </tr>
                <tr>
                    <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                        <multiline>
                            <p>Thanks <br>
                            '.$username.'</p>
                        </multiline>
                    </td>
                </tr>';
                
                $to_email = explode(",", $to);
                $sendmail = $this->sendEmailwithoutattach($to_email,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                
                $helpform = $needhelp_table->newEntity();
                $helpform->name =  $this->request->data('fname');
                $helpform->lname =  $this->request->data('lname');
                $helpform->email =  $this->request->data('email');
                $helpform->phone =  $this->request->data('phone');
                $helpform->school_name =  $this->request->data('schoolname');
                $helpform->who_contact = $this->request->data('whocontact');
                $helpform->reason = $this->request->data('reason');
                $helpform->message = $this->request->data('message');
                $helpform->created_date = strtotime('now');
                $save = $needhelp_table->save($helpform) ;
                if($save)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                {
                    $res = ['result' => 'failed'];
                }
                
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);
        }
        
        public function checkuser()
        {
            if (isset($_POST['type']) && $_POST['type'] == 'logout') 
            {
                $baseurl=$this->request->session()->read('baseurl');
                $lastactive = time() - $_SESSION['LAST_ACTIVE_TIME'];
                
                $users_table = TableRegistry::get('users');
                $company_table = TableRegistry::get('company');
                $emp_table = TableRegistry::get('employee');
                $student_table = TableRegistry::get('student');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                $logtrack_table = TableRegistry::get('track_logged');
                
	            if ((time() - $_SESSION['LAST_ACTIVE_TIME']) > 1800) {  // 60*30 Time in Seconds
                   
	                $subid = $this->Cookie->read('subid');
                    if(!empty($subid))
                    {
                        $update_company = $sclsub_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $subid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "School Subadmin logout"  ;
                        $activity->value = $this->Cookie->read('subid')   ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('sclsub_activitiesid', '', time()-1000  , $baseurl );
                        setcookie('subid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['subid']);
                        $this->Cookie->delete('subid');
                        
                        $trackingid =$this->request->session()->read('sclsub_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time(), 'logout_through' => 'system'])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['sclsub_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //parent
                    $parentid = $this->Cookie->read('pid');
                    if(!empty($parentid))
                    {
                        $update_company = $parentlogin_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $pid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "Parent logout"  ;
                        $activity->value = $this->Cookie->read('pid');
                        $saved = $activ_table->save($activity) ;
                        setcookie('parnt_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('pid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['pid']);
                        $this->Cookie->delete('pid');
                        
                        $trackingid =$this->request->session()->read('parent_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time()])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['parent_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //teacher
                    $tid = $this->Cookie->read('tid');
                    if(!empty($tid))
                    {
                        $update_company = $emp_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $tid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "Employee Logout"  ;
                        $activity->value = $this->Cookie->read('tid')   ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('tchr_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('tid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['tid']);
                        $this->Cookie->delete('tid');
                        
                        $trackingid =$this->request->session()->read('teacher_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time(), 'logout_through' => 'system'])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['teacher_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //superadmin
                    $sid = $this->Cookie->read('sid');
                    if(!empty($sid))
                    {
                        $update_company = $users_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $sid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "Superadmin logout"  ;
                        $activity->value = $this->Cookie->read('sid')   ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('superad_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('sid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['sid']);
                        $this->Cookie->delete('sid');
                        
                        $trackingid =$this->request->session()->read('supersub_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time(), 'logout_through' => 'system'])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['supersub_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //student
                    $stid = $this->Cookie->read('stid');
                    if(!empty($stid))
                    {
                        $update_company = $student_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $stid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "Student Logout"  ;
                        $activity->value = $this->Cookie->read('stid')   ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('stud_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('stid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['stid']);
                        $this->Cookie->delete('stid');
                        
                        $trackingid =$this->request->session()->read('student_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time(), 'logout_through' => 'system'])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['student_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //School
                    $uid = $this->Cookie->read('id');
                    if(!empty($uid))
                    {
                        $update_company = $company_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $uid ])->execute()  ;
                        $activ_table = TableRegistry::get('activity');
                        $activity = $activ_table->newEntity();
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->created = strtotime('now');
                        $activity->action =  "School Logout"  ;
                        $activity->value = $this->Cookie->read('id')   ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('school_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('superschool_activitiesid', '', time()-1000  , $baseurl ); 
                        setcookie('uid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['uid']);
                        $this->Cookie->delete('id');
                        
                        $trackingid =$this->request->session()->read('scl_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time(), 'logout_through' => 'system'])->where(['id' => $trackingid ])->execute()  ;
                        unset($_SESSION['scl_tracklogintime']);
                        
                        $res = "logout";
                    }
                    //print_r($activity);
                    
                    
                	
                }
                else
                {
                    $res = $lastactive. "seconds";
                }
            }
            return $this->json($res);
        }
        
        public function usertime()
        {
            $baseurl=$this->request->session()->read('baseurl');
            $subid = $this->Cookie->read('subid');
            if(!empty($subid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "School Subadmin login"  ;
                $activity->value = $this->Cookie->read('subid')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'school subadmin'];
            }
            //parent
            $parentid = $this->Cookie->read('pid');
            if(!empty($parentid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "Parent login"  ;
                $activity->value = $this->Cookie->read('pid')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'parent'];
            }
            //teacher
            $tid = $this->Cookie->read('tid');
            if(!empty($tid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "Employee Login"  ;
                $activity->value = $this->Cookie->read('tid')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'teacher'];
            }
            //superadmin
            $sid = $this->Cookie->read('sid');
            if(!empty($sid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "Superadmin login"  ;
                $activity->value = $this->Cookie->read('sid')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'superadmin'];
            }
            //student
            $stid = $this->Cookie->read('stid');
            if(!empty($stid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "Student Login"  ;
                $activity->value = $this->Cookie->read('stid')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'student'];
            }
            //School
            $uid = $this->Cookie->read('id');
            if(!empty($uid))
            {
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                $activity->action =  "Company Login"  ;
                $activity->value = $this->Cookie->read('id')   ;
                $saved = $activ_table->save($activity) ;
                $res = ['result' => 'school'];
            }
        
            return $this->json($res);
        }
            
            
}

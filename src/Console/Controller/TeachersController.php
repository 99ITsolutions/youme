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
class TeachersController  extends AppController
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
                $teacher_table = TableRegistry::get('employee');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                
                
                $retrieve_teacher_table = $teacher_table->find()->where([ 'employee.school_id '=> $compid])->toArray() ;
                foreach($retrieve_teacher_table as $key =>$grd_sub_coll)
        		{
        			$subid = explode(",",$grd_sub_coll['subjects']);
        			$i = 0;
        			$subjectsname = [];
        			foreach($subid as $sid)
        			{
        			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $sid])->toArray() ;	
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
                        $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'id' => $gid])->toArray() ;
        				foreach($retrieve_class as $cname)
        				{
        					$gradesname[$i] = $cname['c_name']. "-".$cname['c_section']. " (".$cname['school_sections']. ")";				
        				}
        				$i++;
        				//print_r($gradesname); die;
        				//$cnames = implode(",", $gradesname);
        				
        			}
        			$gradess = array_unique($gradesname);
        			$cnames = implode(",", $gradess);
        			
        			 $grd_sub_coll->subjects_name = $snames;
        			 $grd_sub_coll->grades_name = $cnames;
        			
        			
        		}
                
                $this->set("teacher_details", $retrieve_teacher_table); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function assignClass()
            {
                $teacher_dropdown = '';
                $grade_dropdwon = '';
                $tchrid = '';
                $grdesid = '';
                if(!empty($_GET))
                {
                  $tchrid = $_GET['teacherval'];
                  $grdesid = $_GET['gradesval'];
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $compid = $this->request->session()->read('company_id');
                $teacher_table = TableRegistry::get('employee');
                $class_table = TableRegistry::get('class');
                $assinedteacher = TableRegistry::get('classteacher');

                $assgntecdetail = $assinedteacher->find()->toArray();

                $drop_teachers = $teacher_table->find()->where([ 'school_id '=> $compid])->order(["f_name" => "asc"])->toArray();
               
               if($tchrid == ""){
                   $retrieve_teachers = $teacher_table->find()->where([ 'school_id '=> $compid])->order(["f_name" => "asc"])->toArray();
               }else{
                   $retrieve_teachers = $teacher_table->find()->where([ 'school_id '=> $compid, 'id '=> base64_decode($_GET['teacherval'])])->order(["f_name" => "asc"])->toArray();
               }
                
               
               if($grdesid == ""){
                   $retrieve_class = $class_table->find()->where([ 'school_id '=> $compid])->order(["c_name" => "asc"])->toArray() ;
               }else{
                   $retrieve_class = $class_table->find()->where([ 'school_id '=> $compid, 'c_name' => base64_decode($_GET['gradesval'])])->order(["c_name" => "asc"])->toArray() ;
               }
                

                $drop_grades = $class_table->find('all',array('group' => 'c_name'))->where([ 'school_id '=> $compid])->order(["c_name" => "asc"])->toArray() ;

                
                $this->set("teacher_dropdown", $drop_teachers);
                $this->set("teacher_details", $retrieve_teachers); 
                $this->set("class_details", $retrieve_class); 
                $this->set("grade_dropdwon", $drop_grades); 
                $this->set("assgntecdetailval", $assgntecdetail);
                $this->viewBuilder()->setLayout('user');
            }

            public function excutedata()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $school_table = TableRegistry::get('classteacher');
                $teacher = $school_table->newEntity();
                $retrieve_class = $school_table->find()->where(['classid' => $this->request->data('classids')])->count();
                if($retrieve_class == 0){
                $teacher->classid = $this->request->data('classids');
                $teacher->teacherid = $this->request->data('teacerids');
                $teacher->addates = time();
                if($saved = $school_table->save($teacher)){ 
                    $res = ['result' => 'success'];
                  }
                }else{
                   $school_table->query()->update()->set(['teacherid' => $this->request->data('teacerids') ])->where([ 'classid' => $this->request->data('classids')])->execute();
                   $res = ['result' => 'success'];
                }  
                return $this->json($res);
            }
            public function view($id)
            {   
                $teacher_table = TableRegistry::get('employee');
                $empcls_table = TableRegistry::get('employee_class_subjects');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $compid = $this->request->session()->read('company_id');  
                $retrieve_teacher = $teacher_table->find()->where([ 'md5(id)' => $id  ])->toArray() ;
                
                $retrieve_empclses = $empcls_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
                    [
                        'class' => 
                        [
                            'table' => 'class',
                            'type' => 'LEFT',
                            'conditions' => 'class.id = employee_class_subjects.class_id'
                        ],
                        'subjects' => 
                        [
                            'table' => 'subjects',
                            'type' => 'LEFT',
                            'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                        ]
                    ])->where([ 'md5(employee_class_subjects.emp_id)' => $id ])->toArray();
                    
                $this->set("empcls_details", $retrieve_empclses);
                
                $country_table = TableRegistry::get('countries');
                $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $this->set("country_details", $retrieve_country);
                
                $state_table = TableRegistry::get('states');
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_teacher[0]['country']  ])->toArray() ;
                $this->set("state_details", $retrieve_state);
                
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                $this->set("class_details", $retrieve_class);
                
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                
                $this->set("teacher_details", $retrieve_teacher);  
                $this->viewBuilder()->setLayout('user');
            } 

            public function add()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $school_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $country_table = TableRegistry::get('countries');
                $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $this->set("country_details", $retrieve_country);
                $this->set("country_details", $retrieve_country);
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                $this->set("class_details", $retrieve_class);
                $this->viewBuilder()->setLayout('user');
            }

            public function addtchr()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    
                    $compid = $this->request->session()->read('company_id');  
                    $teacher_table = TableRegistry::get('employee');
                    $teacher_subcls_table = TableRegistry::get('employee_class_subjects');
                    $activ_table = TableRegistry::get('activity');
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $clssub_table = TableRegistry::get('class_subjects');
                    
                    
                    
                    $retrieve_school = $comp_table->find()->where(['id'=> $compid ])->first() ;
                    $schoolname = $retrieve_school['comp_name'];
                    $link = "http://you-me-globaleducation.org/school/login";
                    
                    $email = trim($this->request->data('email'));
                    
                    $retrieve_email = $teacher_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email])->count() ;
                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $email])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $email ])->count() ;
                
                    $picture = '';
                    
                        if($retrieve_email == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0 )
                        {
                        
                        if(!empty($this->request->data['picture']))
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

                        if(!empty($picture))
                        {
                            $teacher = $teacher_table->newEntity();

                            $f_name =  trim($this->request->data('f_name'));
                            $l_name =  trim($this->request->data('l_name'));
                            
                            $year = date("Y", time());
                            $lnaam  = implode("", explode(" ", $l_name));
                            $password = $f_name[0].$lnaam.$compid."00".$year;
                            $teacher->f_name =  $this->request->data('f_name')  ;
                            $teacher->l_name =  $this->request->data('l_name')  ;
                            if(!empty($this->request->data('father_name')))
                            {
                                $teacher->fathers_name = $this->request->data('father_name') ;    
                            }
                            if(!empty($this->request->data('address')))
                            {
                                $teacher->address = $this->request->data('address') ;    
                            }
                            
                            $teacher->quali = $this->request->data('qualification') ;
                            if(!empty($this->request->data('dob')))
                            {
                                $teacher->dob = date('Y-m-d', strtotime($this->request->data('dob'))) ;
                                $tdob = date('Y', strtotime($this->request->data('dob'))) ;
                                $cyear = date('Y', time()) ;
                                $yeardiff = $cyear-$tdob;
                                
                            }
                            
                            
                            
                            $teacher->doj = date('Y-m-d', strtotime($this->request->data('doj'))) ;
                            
                            
                            $teacher->lefts = 0 ;
                            $teacher->mobile_no =  implode(",", $this->request->data['phone']) ;
                            $teacher->country =  $this->request->data('country')  ;
                            $teacher->state =  $this->request->data('state')  ;
                            $teacher->city =  $this->request->data('city')  ;
                            $teacher->status =  0 ;
                            $teacher->pict =  $picture  ;
                            $teacher->grades =  implode(",", $this->request->data['grades']);
                            /*$teacher->subjects =   implode(",", $this->request->data['subjects']);*/
                            $teacher->school_id = $compid;
                            $teacher->email =  $email  ;
                            $teacher->password = $password;
                            $teacher->meeting_link = $this->request->data('meeting_link') ;
                            $teacher->contactyoume = $this->request->data('contactyoume') ;
                            
                            
                            $ph = $this->request->data['phone'];
                                $numsonly = [];
                                foreach($ph as $val)
                                {
                                    
                                    if (preg_match ("/^[0-9]*$/", $val) )
                                    {
                                        $numsonly[] = 1;
                                    }
                                    else
                                    {
                                        $numsonly[] = 0;
                                    }
                                }
                                
                                if(!empty($this->request->data('contactyoume')))
                                    {
                                        if(preg_match("/^[0-9]*$/", $this->request->data('contactyoume')))
                                        {
                                            $contctyoume = 1;
                                        }
                                        else
                                        {
                                            $contctyoume = 0;
                                        }
                                    }
                                    else
                                    {
                                        $contctyoume = 1;
                                    }
                            
                            if( (!empty($this->request->data('dob'))) && $yeardiff >= 18 )
                            {
                                if (!in_array("0", $numsonly))
                                {
                                    if ($contctyoume == 1 )
                                {
                                    if($saved = $teacher_table->save($teacher) )
                                    {   
                                        $teacherid = $saved->id;
                                        
                                        $grades = $this->request->data['grades'];
                                        
                                        
                                        foreach($grades as $key=> $val)
                                        {
                                            $sid = $key+1;
                                            $subjects = $this->request->data['subjects'.$sid];
                                            if(in_array("all", $subjects))
                                            {
                                                $retrieve_clssub = $clssub_table->find()->where(['school_id'=> $compid, 'class_id' => $val ])->first() ;
                                                $subjects = explode(",",$retrieve_clssub['subject_id']);
                                            }
                                            
                                            foreach($subjects as $subkey => $subval)
                                            {     
                                                
                                                if($subval != "")
                                                {
                                                    $clsSub = $teacher_subcls_table->newEntity();
                                                    
                                                    $clsSub->emp_id =  $teacherid  ;
                                                    $clsSub->class_id =  $val  ;
                                                    $clsSub->subject_id =  $subval ;
                                                    
                                                    $savedclssub = $teacher_subcls_table->save($clsSub);
                                                }
                                            }
                                        }
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Teacher Created"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($saved->id)   ;
                                        $activity->origin = $this->Cookie->read('id')   ;
                                        $activity->created = strtotime('now');
                                        if($saved = $activ_table->save($activity) ){
                                            $subject = 'New Registration in You-Me Global Education';
                                            $to =  trim($this->request->data('email'));
                                            //$password =  trim($this->request->data('password'));
                                            $from = "support@you-me-globaleducation.org";
                                            $first =  $this->request->data('f_name')  ;
                                            $last =  $this->request->data('l_name')  ;
                                            $name = $first.' '.$last;
                                            
                                            $htmlContent = '
                                            <tr>
                                            <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                                <multiline>
                                                    <p>Welcome to You-Me Global Education ('.$schoolname.')! We\'re here to transform your education digitally.</p>
                                                    <p>You-Me Global Education is a platform that provides a complete digital resource management system. Below are the details of your account.</p>
                                                </multiline>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                    <p>Login Link: '.$link.' </p>
                                                    <p>Username: '.$to.' </p>
                                                    <p>Password: '.$password.' </p>
                                                    </multiline>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                        <p>Regards,</p>
                                                        <p>You-Me Global Education Team</p>
                                                    </multiline>
                                                </td>
                                            </tr>';
                                            $username = $first.' '.$last;
                                          
                                            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                    
                                            $res = [ 'result' => 'success'  ];
                
                                        }
                                        else{
                                            $res = [ 'result' => 'activity not saved'  ];
                                        }
                                    }
                                    else{
                                        $res = [ 'result' => 'Teacher not saved'  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Kindly insert digit only in You-Me phone number'  ];
                                }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Kindly insert digit only in mobile number'  ];
                                }
                            }
                            elseif( (!empty($this->request->data('dob'))) && $yeardiff <= 18 )
                            {
                                $res = [ 'result' => 'Teacher Age should be more than 18 years.'  ];
                            }
                            else
                            {
                                if ($contctyoume == 1 )
                                {
                                if (!in_array("0", $numsonly))
                                {
                                    if($saved = $teacher_table->save($teacher) )
                                    {   
                                        $teacherid = $saved->id;
                                        /*
                                        $grades = $this->request->data['grades'];
                                        $subjects = $this->request->data['subjects'];
                                        
                                        foreach($grades as $key=> $val)
                                        {
                                            $clsSub = $teacher_subcls_table->newEntity();
                                            
                                            $clsSub->emp_id =  $teacherid  ;
                                            $clsSub->class_id =  $val  ;
                                            $clsSub->subject_id =  $subjects[$key] ;
                                            
                                            $savedclssub = $teacher_subcls_table->save($clsSub);
                                        }
                                        */
                                        
                                        $teacherid = $saved->id;
                                        $grades = $this->request->data['grades'];
                                        foreach($grades as $key=> $val)
                                        {
                                            $sid = $key+1;
                                            $subjects = $this->request->data['subjects'.$sid];
                                            if(in_array("all", $subjects))
                                            {
                                                $retrieve_clssub = $clssub_table->find()->where(['school_id'=> $compid, 'class_id' => $val ])->first() ;
                                                $subjects = explode(",",$retrieve_clssub['subject_id']);
                                            }
                                            
                                            foreach($subjects as $subkey => $subval)
                                            {     
                                                
                                                if($subval != "")
                                                {
                                                    $clsSub = $teacher_subcls_table->newEntity();
                                                    
                                                    $clsSub->emp_id =  $teacherid  ;
                                                    $clsSub->class_id =  $val  ;
                                                    $clsSub->subject_id =  $subval ;
                                                   
                                                    $savedclssub = $teacher_subcls_table->save($clsSub);
                                                }
                                            }
                                        }
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Teacher Created"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($saved->id)   ;
                                        $activity->origin = $this->Cookie->read('id')   ;
                                        $activity->created = strtotime('now');
                                        if($saved = $activ_table->save($activity) ){
                                            $subject = 'New Registration in You-Me Global Education';
                                            $to =  trim($this->request->data('email'));
                                            //$password =  trim($this->request->data('password'));
                                            $from = "support@you-me-globaleducation.org";
                                            $first =  $this->request->data('f_name')  ;
                                            $last =  $this->request->data('l_name')  ;
                                            $name = $first.' '.$last;
                                            
                                            $htmlContent = '
                                            <tr>
                                            <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                                <multiline>
                                                    <p>Welcome to You-Me Global Education ('.$schoolname.')! We\'re here to transform your education digitally.</p>
                                                    <p>You-Me Global Education is a platform that provides a complete digital resource management system. Below are the details of your account.</p>
                                                </multiline>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                    <p>Login Link: '.$link.' </p>
                                                    <p>Username: '.$to.' </p>
                                                    <p>Password: '.$password.' </p>
                                                    </multiline>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                    <multiline>
                                                        <p>Regards,</p>
                                                        <p>You-Me Global Education Team</p>
                                                    </multiline>
                                                </td>
                                            </tr>';
                                            $username = $first.' '.$last;
                                          
                                            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                    
                                            $res = [ 'result' => 'success'  ];
                
                                        }
                                        else{
                                            $res = [ 'result' => 'activity not saved'  ];
                                        }
                                    }
                                    else{
                                        $res = [ 'result' => 'Teacher not saved'  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Kindly insert digit only in You-Me phone number'  ];
                                }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Kindly insert digit only in mobile number'  ];
                                }
                            }
                            
                        }
                        else
                        { 
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }     
                    } 
                    else
                    {
                        $res = [ 'result' => 'A teacher with this email is already exist'  ];
                    }
                    

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }

            public function edit($id)
            {   
                $teacher_table = TableRegistry::get('employee');
                $empcls_table = TableRegistry::get('employee_class_subjects');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $compid = $this->request->session()->read('company_id');  
                $retrieve_teacher = $teacher_table->find()->where([ 'md5(id)' => $id  ])->toArray() ;
                
                $retrieve_empclses = $empcls_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
                    [
                        'class' => 
                        [
                            'table' => 'class',
                            'type' => 'LEFT',
                            'conditions' => 'class.id = employee_class_subjects.class_id'
                        ],
                        'subjects' => 
                        [
                            'table' => 'subjects',
                            'type' => 'LEFT',
                            'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                        ]
                    ])->where([ 'md5(employee_class_subjects.emp_id)' => $id ])->toArray();
                    
                $this->set("empcls_details", $retrieve_empclses);
                
                $country_table = TableRegistry::get('countries');
                $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $this->set("country_details", $retrieve_country);
                
                $state_table = TableRegistry::get('states');
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_teacher[0]['country']  ])->toArray() ;
                $this->set("state_details", $retrieve_state);
                
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                $this->set("class_details", $retrieve_class);
                
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                
                $this->set("teacher_details", $retrieve_teacher);  
                $this->viewBuilder()->setLayout('user');
            } 


            public function edittchr()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $teacher_table = TableRegistry::get('employee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $clssub_table = TableRegistry::get('class_subjects');
                    $teacher_subcls_table = TableRegistry::get('employee_class_subjects');
                    $email =  trim($this->request->data('email')) ;

                    $retrieve_email = $teacher_table->find()->select(['id'  ])->where(['email' => $email , 'id <>' => $this->request->data('id')  ])->count() ;
                    
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email])->count() ;
                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $email])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $email ])->count() ;
                
                    
                    /*if($retrieve_mobile == 0 )
                    {*/
                        if($retrieve_email == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0 )
                        {
                        if(!empty($this->request->data['picture']))
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
                        
                        $tid = $this->request->data('id');
                        
                        $f_name = $this->request->data('f_name');
                        $l_name = $this->request->data('l_name');
                        if(!empty($this->request->data('father_name')))
                        {
                            $fathers_name = $this->request->data('father_name') ;    
                        }
                        else
                        {
                            $fathers_name   = "";
                        }
                        if(!empty($this->request->data('address')))
                        {
                            $address = $this->request->data('address') ;    
                        }
                        else
                        {
                            $address ="";
                        }
                        
                        $quali = $this->request->data('qualification') ;
                        if(!empty($this->request->data('address')))
                        {
                            $dob = date('Y-m-d', strtotime($this->request->data('dob'))) ;
                        }
                        else
                        {
                            $dob ="";
                        }
                        $doj = date('Y-m-d', strtotime($this->request->data('doj'))) ;
                        
                        
                        if(!empty($this->request->data('state')))
                        {
                            $state =  $this->request->data('state')  ;
                        }
                        else
                        {
                           $state =  ""  ;
                        }
                        
                        if(!empty($this->request->data('city')))
                        {
                            $city =  $this->request->data('city')  ;
                        }
                        else
                        {
                           $city =  ""  ;
                        }
                        $left = 0 ;
                        $mobile_no =  implode(",", $this->request->data['phone']);
                        $country =  $this->request->data('country')  ;
                        //$city =  $this->request->data('city')  ;
                        $status =  0 ;
                        $pict =  $picture  ;
                        $grades =  implode(",", $this->request->data['grades']);
                        $subjects =   implode(",", $this->request->data['subjects']);
                        $school_id = $compid;
                        
                        $password = $this->request->data('password') ;
                        $contactyoume = $this->request->data('contactyoume') ;

                        $ph = $this->request->data['phone'];
                        $numsonly = [];
                        foreach($ph as $val)
                        {
                            
                            if (preg_match ("/^[0-9]*$/", $val) )
                            {
                                $numsonly[] = 1;
                            }
                            else
                            {
                                $numsonly[] = 0;
                            }
                        }
                        
                        if(!empty($this->request->data('contactyoume')))
                        {
                            if(preg_match("/^[0-9]*$/", $this->request->data('contactyoume')))
                            {
                                $contctyoume = 1;
                            }
                            else
                            {
                                $contctyoume = 0;
                            }
                        }
                        else
                        {
                            $contctyoume = 1;
                        }
                        
                        
                        if(!empty($picture))
                        {   
                            if($contctyoume == 1 ) {
                            if (!in_array("0", $numsonly))
                            {
                                if( $teacher_table->query()->update()->set([ 'contactyoume' => $contactyoume, 'f_name' => $f_name,  'l_name' => $l_name, 'address' => $address, 'quali'=>$quali, 'fathers_name'=>$fathers_name, 'email' => $email,  'mobile_no' => $mobile_no, 'password' => $password , 'dob' => $dob ,'doj'=>$doj , 'pict'=> $picture ,  'country' => $country , 'state' => $state , 'city'=> $city , 'lefts' => $left , 'grades'=> $grades ])->where([ 'id' => $tid  ])->execute())
                                {   
                                    $del = $teacher_subcls_table->query()->delete()->where([ 'emp_id' => $tid ])->execute(); 
                                    $grades = $this->request->data['grades'];
                                    //$subjects = $this->request->data['subjects'];
                                    foreach($grades as $key=> $val)
                                    {
                                        //echo $val;
                                        $sid = $key+1;
                                        $subjects = $this->request->data['subjects'.$sid];
                                        if(in_array("all", $subjects))
                                        {
                                            $retrieve_clssub = $clssub_table->find()->where(['school_id'=> $compid, 'class_id' => $val ])->first() ;
                                            $subjects = explode(",",$retrieve_clssub['subject_id']);
                                        }
                                        
                                        foreach($subjects as $subkey => $subval)
                                        {      
                                            if($subval != "")
                                            {
                                                $clsSub = $teacher_subcls_table->newEntity();
                                                
                                                $clsSub->emp_id =  $tid  ;
                                                $clsSub->class_id =  $val  ;
                                                $clsSub->subject_id =  $subval ;
                                                
                                                $savedclssub = $teacher_subcls_table->save($clsSub);
                                            }
                                        }
                                    }
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Teacher update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($tid); 
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
                                $res = [ 'result' => 'Kindly insert digit only in mobile number'  ];
                            }
                            }
                            else
                            {
                                $res = [ 'result' => 'Kindly insert digit only in You-Me Contact number'  ];
                            }
                            
                        }
                        else
                        { 
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }    
                    }
                    else
                    {
                        $res = [ 'result' => 'A teacher with this email is already exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }  

            public function import()
            {   
                
                if ($this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $teacher_table = TableRegistry::get('employee');
                    $class_table = TableRegistry::get('class');
                    $subjects_table = TableRegistry::get('subjects');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $teacher_subcls_table = TableRegistry::get('employee_class_subjects');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    
                    $retrieve_school = $comp_table->find()->where(['id'=> $compid ])->first() ;
                    $schoolname = $retrieve_school['comp_name'];
                    $link = "http://you-me-globaleducation.org/school/login";

                    if(!empty($this->request->data['file']['name']))
                    {
                        $fileexe = explode('.', $this->request->data['file']['name']);
                        //print_r($fileexe);
                        if($fileexe[1] =='csv')
                        {

                            $filename = $this->request->data['file']['tmp_name'];
                            
                            $handle = fopen($filename, "r");
                            setlocale(LC_CTYPE, "en_US.UTF-8");
                            $header = fgetcsv($handle);
                            
                            
                            $i = 0;
                            while (($row = fgetcsv($handle)) !== FALSE) 
                            {
                                $i++;
                                $data = array();
                                
                                    $f_name = trim($row[0]);
                                    $l_name = trim($row[1]); 
                                    $phone = $row[2];
                                    $qualification = implode("," , explode("#",$row[3]));
                                    $email = trim($row[4]);
                                    $year = date("Y", time());
                                    $lnaam  = implode("", explode(" ", $l_name));
                                    $password = $f_name[0].$lnaam.$compid."00".$year;
                                    
                                    $address = implode(",",explode("#", $row[5]));
                                    /*$grades = explode("#", trim(mb_convert_encoding($row[6],"UTF-8","HTML-ENTITIES")));
                                    $subjects = explode("#", trim(mb_convert_encoding($row[7],"UTF-8","HTML-ENTITIES")));*/
                                    
                                    $grades = explode("#", $row[6]);
                                    $subjects = explode("#", $row[7]);
                                 
                                    $classid = [];
                                    $subs = [];
                                    foreach($grades as $cl_sec)
                                    {
                                        $cs = explode("-", $cl_sec);
                                        //print_r($cs);
                                    /*echo $classes=     trim(mb_convert_encoding($cs[0],"UTF-8","HTML-ENTITIES"));
								    echo $section = trim(mb_convert_encoding($cs[2],"UTF-8","HTML-ENTITIES"));
								    echo $sclsectn = trim(mb_convert_encoding($cs[2],"UTF-8","HTML-ENTITIES"));*/
								    
                                        $classes=     trim($cs[0]);
								        $section = trim($cs[1]);
								        $sclsectn = trim($cs[2]);
							            
							            if($section != "")
                                        {
                                            $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'c_section' => $section , 'school_sections'=> $sclsectn, 'school_id'=> $compid  ])->first();
                                        }
                                        else
                                        {
                                            $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections'=> $sclsectn, 'school_id'=> $compid  ])->first();
                                        }
                                        
                                        if(empty($retrieve_classid['id']))
                                        {
                                            $classes=     trim(mb_convert_encoding($cs[0],"UTF-8","HTML-ENTITIES"));
        								    $section = trim(mb_convert_encoding($cs[2],"UTF-8","HTML-ENTITIES"));
        								    $sclsectn = trim(mb_convert_encoding($cs[2],"UTF-8","HTML-ENTITIES"));
        								    
                                            if($section != "")
                                            {
                                                $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'c_section' => $section , 'school_sections'=> $sclsectn, 'school_id'=> $compid  ])->first();
                                            }
                                            else
                                            {
                                                $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections'=> $sclsectn, 'school_id'=> $compid  ])->first();
                                            }
                                        }
                                        $classid[] = $retrieve_classid['id'];  
								       
                                    }
                                    $gradess = implode(",", $classid);
                                    
                                    foreach($subjects as $sub)
                                    {
                                        
									    $retrieve_class_subjects = $subjects_table->find()->select(['id'])->where(['subject_name' => trim($sub), 'school_id'=> $compid])->first();
									    $subs[] = $retrieve_class_subjects['id'];
                                    }
                                    
                                    //print_r($subs);
                                    
                                    $subjectss = implode(",", $subs);
                                    
                                    $retrieve_email = $teacher_table->find()->select(['id'  ])->where(['email' => $email , 'school_id'=> $compid ])->count() ;
                                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email])->count() ;
                                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $email])->count() ;
                                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $email ])->count() ;
                
                    
                                    
                                    if($retrieve_email == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent== 0)
                                    //if($retrieve_email == 0)
                                    {
                                        /*$data = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                        $id =  substr(str_shuffle($data), 0, 30);
                                        
                                        $meeting_link = "meet.jit.si/".$id;*/
                                    
                                        $teacher = $teacher_table->newEntity();
                                        $teacher->f_name =  $f_name  ;
                                        $teacher->l_name = $l_name ;
                                        $teacher->quali = str_replace("#", ", ", $qualification);
                                        // $teacher->fathers_name = $fathers_name;
                                        $teacher->dob = date('Y-m-d', time()) ;
                                        $teacher->doj = date('Y-m-d', time()) ;
                                        $teacher->mobile_no =  $phone ;
                                        $teacher->email =  $email ;
                                        $teacher->password =  $password ;
                                        $teacher->subjects =  $subjectss ;
                                        $teacher->grades = $gradess;
                                        $teacher->school_id = $compid;
                                        $teacher->status = 0;
                                        $teacher->lefts = 0;
                                        $teacher->address = $address;
                                        //$teacher->meeting_link = $meeting_link;
                                        //print_r($teacher); 
                                      
                                        $em =  filter_var($email, FILTER_VALIDATE_EMAIL) ;
                                        $ph =  preg_match ("/^[0-9]*$/",$phone);
                                        
                                        if($f_name != "" && $l_name !="" && $ph == 1 && $em != "" )
                                        {
                                            if($saved = $teacher_table->save($teacher) )
                                            {   
                                                $activity = $activ_table->newEntity();
                                                $activity->action =  "Teachers Created"  ;
                                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            
                                                $activity->value = md5($saved->id)   ;
                                                $activity->origin = $this->Cookie->read('id')   ;
                                                $activity->created = strtotime('now');
                                                
                                                foreach($classid as $key=> $val)
                                                {
                                                    $tid = $saved->id;
                                                    $clsSub = $teacher_subcls_table->newEntity();
                                                   
                                                    $clsSub->emp_id =  $tid  ;
                                                    $clsSub->class_id =  $val  ;
                                                    $clsSub->subject_id =  $subs[$key] ;
                                                    
                                                    $savedclssub = $teacher_subcls_table->save($clsSub);
                                                }
                                    
                                                if($saved = $activ_table->save($activity) ){
                                                   
                                                    $subject = 'New Registration in You-Me Global Education';
                                                    $to =  $email;
                                                    $password =  trim($password);
                                                    $from = "support@you-me-globaleducation.org";
                                                    
                                                    $name = $f_name.' '.$l_name;
                                                    
                                                    $htmlContent = '
                                                    <tr>
                                                    <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                                        <multiline>
                                                            <p>Welcome to You-Me Global Education ('.$schoolname.')! We\'re here to transform your education digitally.</p>
                                                            <p>You-Me Global Education is a platform that provides a complete digital resource management system. Below are the details of your account.</p>
                                                        </multiline>
                                                    </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                            <multiline>
                                                            <p>Login Link: '.$link.' </p>
                                                            <p>Username: '.$to.' </p>
                                                            <p>Password: '.$password.' </p>
                                                            </multiline>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                            <multiline>
                                                                <p>Regards,</p>
                                                                <p>You-Me Global Education Team</p>
                                                            </multiline>
                                                        </td>
                                                    </tr>';
                                                    $username = $f_name.' '.$l_name;
                                                  
                                                    $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                            
                                                    $res = [ 'result' => 'success'  ];
                        
                                                }
                                                else{
                                                    $res = [ 'result' => 'activity not saved'  ];
                                                }   
                            
                                            }
                                            else{
                                                $res = [ 'result' => 'Teacher not saved'  ];
                                            }
                                        }
                                        else
                                        {
                                            $err = 'Please check row '.$i.' make sure that First Name, Last Name are not empty, Enter valid Phone Number, Email address and Enter Grades & Subjects according to Sample sheet. ';
                                            $res = [ 'result' => $err  ];
                                        }
                                    }
                                    /*elseif($retrieve_email == 0 )
                                    {
                                        $retrieve_id = $teacher_table->find()->select(['id'])->where(['email' => $email , 'school_id'=> $compid ])->toArray() ;
                                        $tid = $retrieve_id[0]['id'];
                                        $f_name =  $f_name  ;
                                        $l_name = $l_name ;
                                        $quali = str_replace("#", ", ", $qualification);
                                        $fathers_name = $fathers_name;
                                        $doj = date('Y-m-d', strtotime($doj)) ;
                                        $mobile_no =  $phone ;
                                        $email =  $email ;
                                        $password =  $password ;
                                        $school_id = $compid;
                                        
                                        
                                        if($update = $teacher_table->query()->update()->set(['f_name' => $f_name,  'l_name' => $l_name, 'quali'=>$quali, 'fathers_name'=>$fathers_name, 'email' => $email,  'mobile_no' => $mobile_no, 'password' => $password , 'doj'=>$doj, 'subjects' =>  $subjectss, 'grades' => $gradess ])->where([ 'id' => $tid  ])->execute() )
                                        {   
                                            foreach($classid as $key=> $val)
                                            {
                                                $tid = $saved->id;
                                                $clsSub = $teacher_subcls_table->newEntity();
                                                
                                                $clsSub->emp_id =  $tid  ;
                                                $clsSub->class_id =  $val  ;
                                                $clsSub->subject_id =  $subs[$key] ;
                                                
                                                $savedclssub = $teacher_subcls_table->save($clsSub);
                                            }
                                            
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Teachers Updated"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($tid)   ;
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
                                            $res = [ 'result' => 'Teacher not saved'  ];
                                        }
                                    }*/
                                    else
                                    {
                                        $res = [ 'result' => 'Email Id already exist.'  ];
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

        public function status()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $teacher_table = TableRegistry::get('employee');
            $activ_table = TableRegistry::get('activity');

            $sid = $teacher_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $teacher_table->query()->update()->set([ 'status' => $status, 'lefts' => $status])->where([ 'id' => $uid  ])->execute();
				
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


            public function delete()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $rid = $this->request->data('val') ;
                $employee_table = TableRegistry::get('employee');
                $employeecls_table = TableRegistry::get('employee_class_subjects');
                $activ_table = TableRegistry::get('activity');
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
    			$emp_cls_table = TableRegistry::get('employee_class_subjects'); 
    			$meeting_link_table = TableRegistry::get('meeting_link');
    			$virtual_cls_table = TableRegistry::get('kinder_virtualclass');
    			$exam_ass_table = TableRegistry::get('exams_assessments');
    			$tutorial_content_table = TableRegistry::get('tutorial_content');
                
                $roleid = $employee_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($roleid)
                {   
                    
                    $del = $employeecls_table->query()->delete()->where([ 'emp_id' => $rid ])->execute(); 
                    if($del)
					{
					    $deel = $employee_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
					    $tchrid = $rid;
    				$del_comm1 = $comments_table1->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm2 = $comments_table2->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm3 = $comments_table3->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm4 = $comments_table4->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm5 = $comments_table5->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm6 = $comments_table6->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm7 = $comments_table7->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm8 = $comments_table8->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm9 = $comments_table9->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm10 = $comments_table10->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm11 = $comments_table11->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm12 = $comments_table12->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm13 = $comments_table13->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm14 = $comments_table14->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm15 = $comments_table15->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_comm16 = $comments_table16->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
    				$del_msg1 = $emp_cls_table->query()->delete()->where([ 'emp_id' => $tchrid])->execute(); 
    				$del_msg2 = $meeting_link_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute(); 	
    				$del_msg3 = $virtual_cls_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute();
    				
    				$exams = $exam_ass_table->find()->select(['id'])->where([ 'teacher_id' => $tchrid  ])->toArray() ;  
    				foreach( $exams as $eid) {       
    					$exam_ass_table->query()->update()->set(['teacher_id' => '' ])->where([ 'id' => $eid['id']  ])->execute();
    				}
    				$del_tutcontent = $tutorial_content_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute();
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Teacher Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($rid)    ;
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
            
           
        public function deletealltchrs()
        {
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data['val'] ; 
            $emp_table = TableRegistry::get('employee');
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
			$emp_cls_table = TableRegistry::get('employee_class_subjects'); 
			$meeting_link_table = TableRegistry::get('meeting_link');
			$virtual_cls_table = TableRegistry::get('kinder_virtualclass');
			$exam_ass_table = TableRegistry::get('exams_assessments');
			$tutorial_content_table = TableRegistry::get('tutorial_content');
			
			
            foreach($uid as $ids)
            {
                $stats = $emp_table->query()->delete()->where([ 'id' => $ids ])->execute();
				$tchrid = $ids;
				$del_comm1 = $comments_table1->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm2 = $comments_table2->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm3 = $comments_table3->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm4 = $comments_table4->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm5 = $comments_table5->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm6 = $comments_table6->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm7 = $comments_table7->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm8 = $comments_table8->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm9 = $comments_table9->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm10 = $comments_table10->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm11 = $comments_table11->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm12 = $comments_table12->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm13 = $comments_table13->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm14 = $comments_table14->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm15 = $comments_table15->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_comm16 = $comments_table16->query()->delete()->where([ 'teacher_id' => $tchrid ])->execute(); 
				$del_msg1 = $emp_cls_table->query()->delete()->where([ 'emp_id' => $tchrid])->execute(); 
				$del_msg2 = $meeting_link_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute(); 	
				$del_msg3 = $virtual_cls_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute();
				
				$exams = $exam_ass_table->find()->select(['id'])->where([ 'teacher_id' => $tchrid  ])->toArray() ;  
				foreach( $exams as $eid) {       
					$exam_ass_table->query()->update()->set(['teacher_id' => '' ])->where([ 'id' => $eid['id']  ])->execute();
				}
				$del_tutcontent = $tutorial_content_table->query()->delete()->where([ 'teacher_id' => $tchrid])->execute();
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


        public function export()
        {   
            $employee_table = TableRegistry::get('employee');
            $scl_table = TableRegistry::get('company');
            $compid =$this->request->session()->read('company_id');
            $datascl = $scl_table->find()->where(['id' => $compid])->first() ;  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sclname = $datascl['comp_name'].'_teachers.csv';
            $data = $employee_table->find()->select(['f_name','l_name','mobile_no','email','password' ])->where(['status' => 1, 'school_id' => $compid ])->toArray() ;  
            
            
            $this->setResponse($this->getResponse()->withDownload($sclname));
            $_header = array('First Name', 'Last Name' , 'Mobile Number' , 'Email' , 'Password' );
            $_serialize = 'data';
            $this->viewBuilder()->setClassName('CsvView.Csv');
            $this->set(compact('data', '_header' , '_serialize'));
        }





}

  


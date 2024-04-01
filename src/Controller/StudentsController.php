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
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class StudentsController  extends AppController
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
    
    public function qrcode()
    {
        $student_table = TableRegistry::get('student');
        $compid =$this->request->session()->read('company_id');
        $retrieve_student = $student_table->find()->select(['id', 'adm_no', 'qrcode_img' ])->where(['school_id' => $compid])->toArray() ;
        //$retrieve_student = $student_table->find()->select(['id', 'adm_no', 'qrcode_img' ])->toArray() ;
        foreach($retrieve_student as $stud)
        {
            $studentno = $stud['adm_no'];
            $newstdntid = $stud['id'];
            if($stud['qrcode_img'] == "")
            {
                $renderer = new ImageRenderer(
                    new RendererStyle(400),
                    new ImagickImageBackEnd()
                );
                $qrcodeimg = uniqid()."_".$newstdntid;
                $qrcodeimgs = $qrcodeimg.".png";
                $writer = new Writer($renderer);
                $qr_image = base64_encode($writer->writeString($newstdntid.$studentno));
                
                $encode_stuid = base64_encode($newstdntid);
                $urlqrcode = 'https://you-me-globaleducation.org/idcard.php?studid='.$newstdntid;
                $writer->writeFile($urlqrcode, 'codeqr/'.$qrcodeimg.'.png'); 
                
                $student_table->query()->update()->set([ 'qrcode_img' => $qrcodeimgs, 'qrcode_link' => $urlqrcode ])->where([ 'id' => $newstdntid  ])->execute();
            }    
        }
        $data = "success";
        return $this->json($data);
        /* var_dump($qr_image); die;
        $this->viewBuilder()->setLayout('user');*/
    }
            public function index()
            {   
                $student_table = TableRegistry::get('student');
                $session_table = TableRegistry::get('session');
                $class_table = TableRegistry::get('class');
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sclsub_table = TableRegistry::get('school_subadmin');
                
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
					$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
				}
				
                $retrieve_student_table = $student_table->find()->select(['session.startyear', 'session.endyear', 'student.id' , 'student.qrcode_img' , 'class.id' ,'student.email' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						],
						[
							'table' => 'session',
							'type' => 'LEFT',
							'conditions' => 'session.id = student.session_id'
						]
					])->where(['student.school_id' => $compid, 'student.session_id' => $session_id ])->toArray() ;
					
				foreach($retrieve_student_table as $content)
                {
                    //print_r($content);
                    $clsid = $content['class']['id'];
                    $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $clsid])->first() ;
                    
                    if($this->Cookie->read('logtype') == md5('School Subadmin'))
					{
                        if(strtolower($retclass['school_sections']) == "creche" || strtolower($retclass['school_sections']) == "maternelle") {
                            $clsmsg = "kindergarten";
                        }
                        elseif(strtolower($retclass['school_sections']) == "primaire") {
                            $clsmsg = "primaire";
                        }
                        else
                        {
                            $clsmsg = "secondaire";
                        }
                        
                        if(in_array($clsmsg, $subpriv)) { 
                            $content->show = 1;
                        }
                        else
                        {
                            $content->show = 0;
                        }
					}
					else
					{
					     $content->show = 1;
					}
                }
                //print_r($retrieve_student_table); die;
					
				$retrieve_session = $session_table->find()->toArray() ;
                
                $this->set("session_details", $retrieve_session); 
                $this->set("studentdetails", $retrieve_student_table); 
                $this->viewBuilder()->setLayout('user');
            }

            public function add()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $state_table = TableRegistry::get('states');
                $class_table = TableRegistry::get('class');
                $student_table = TableRegistry::get('student');
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');
                $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
                $this->set("country_details", $retrieve_country);
                $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                $this->set("class_details", $retrieve_class);
                $this->viewBuilder()->setLayout('user');
            }

            public function addstdnt()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $student_table = TableRegistry::get('student');
                    $activ_table = TableRegistry::get('activity');
                    $comp_table = TableRegistry::get('company');
                    $session_table = TableRegistry::get('session');
                    $compid =$this->request->session()->read('company_id');
				
					$session_table = TableRegistry::get('session');
					$now = strtotime('now');
    
                    $currentyear = date("Y", $now);
                    $currentmonth = date("m", $now);
                    
                    $retrieve_session = $session_table->find()->toArray() ;
                    $retrieve_school = $comp_table->find()->where(['id'=> $compid ])->first() ;
                    $schoolname = $retrieve_school['comp_name'];
                    $link = "http://you-me-globaleducation.org/school/login";
                    
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

                    $retrieve_school = $comp_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id' ])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                    
                    $retrieve_parentemail = $parentlogin_table->find()->select(['id' ])->where(['parent_email' => trim($this->request->data('pemail')) ])->count() ;
                    $parentid = '';
                    if($retrieve_parentemail != 0)
                    {
                        $retrieve_parentemail = $parentlogin_table->find()->select(['id' ])->where(['parent_email' => trim($this->request->data('pemail')) ])->first() ;
                        $parentid = $retrieve_parentemail['id'];
                    }
                    
                    $retrieve_school1 = $comp_table->find()->select(['id' ])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_student1 = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_teacher1 = $teacher_table->find()->select(['id' ])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_schoolsub1 = $sclsubadmin_table->find()->select(['id' ])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_supersub1 = $supersubad_table->find()->select(['id' ])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    
		            
		            $ph_sms_length= strlen($this->request->data('mobile_for_sms'));
		            $em_phn_length= strlen($this->request->data('emergency_contact'));
		            if($retrieve_student1 == 0 && $retrieve_school1 == 0 && $retrieve_teacher1 == 0 && $retrieve_schoolsub1 == 0 && $retrieve_supersub1 == 0)
                    {
                    if($retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                    {   
                        if(strtolower($this->request->data('gender')) == "female")
                        {
                            $picture = "female.jpg";
                        }
                        else
                        {
                            $picture = "male.jpg";
                        }
                        
                        $gr1_path = "";
                        $gr2_path = "";
                        $gr3_path = "";
                        
                        if($em_phn_length > 9)
                        {
                            if (preg_match ("/^[0-9]*$/", $this->request->data('emergency_contact')) )
                            {
                                if($ph_sms_length > 9)
                                {
                                    if (preg_match ("/^[0-9]*$/", $this->request->data('mobile_for_sms')) )
                                    {
                                        if(!empty($this->request->data['picture']['name']))
                                        {   
                                            if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                                            {
                                                $picture =  time().$this->request->data['picture']['name'];
                                                $filename = $picture;
                                                $uploadpath = 'img/';
                                                $uploadfile = $uploadpath.$filename;
                                                if(move_uploaded_file($this->request->data['picture']['tmp_name'], $uploadfile))
                                                {
                                                    $this->request->data['picture'] = $filename; 
                                                }
                                            }    
                                        }

                                    if(!empty($this->request->data['gr1_path']['name']))
                                    {   
                                        if($this->request->data['gr1_path']['type'] == "image/jpeg" || $this->request->data['gr1_path']['type'] == "image/jpg" || $this->request->data['gr1_path']['type'] == "image/png" )
                                        {
                                            $gr1_path =  time().$this->request->data['gr1_path']['name'];
                                            $filename = $gr1_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr1_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr1_path'] = $filename; 
                                            }
                                        }    
                                    }

                                    if(!empty($this->request->data['gr2_path']['name']))
                                    {   
                                        if($this->request->data['gr2_path']['type'] == "image/jpeg" || $this->request->data['gr2_path']['type'] == "image/jpg" || $this->request->data['gr2_path']['type'] == "image/png" )
                                        {
                                            $gr2_path =  time().$this->request->data['gr2_path']['name'];
                                            $filename = $gr2_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr2_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr2_path'] = $filename; 
                                            }
                                        }    
                                    }

                                    if(!empty($this->request->data['gr3_path']['name']))
                                    {   
                                        if($this->request->data['gr3_path']['type'] == "image/jpeg" || $this->request->data['gr3_path']['type'] == "image/jpg" || $this->request->data['gr3_path']['type'] == "image/png" )
                                        {
                                            $gr3_path =  time().$this->request->data['gr3_path']['name'];
                                            $filename = $gr3_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr3_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr3_path'] = $filename; 
                                            }
                                        }    
                                    }
                                    $student = $student_table->newEntity();
                                    $student->f_name =  $this->request->data('f_name')  ;
                                    $student->l_name =  $this->request->data('l_name')  ;
                                    $student->pic =  $picture  ;
                                    $student->mobile_no = $this->request->data('mobile_no') ;
                                    $student->s_f_name = $this->request->data('s_f_name') ;
                                    $student->s_m_name = $this->request->data('s_m_name') ;
                                    $student->guardian_name = $this->request->data('guardian_name') ;
                                    $student->dob = date('Y-m-d', strtotime($this->request->data('dob')));
                                    $student->bloodgroup = $this->request->data('bloodgroup') ;
                                    $student->f_occ = $this->request->data('f_occ') ;
                                    $student->class = $this->request->data('class') ;
                                    $student->national = $this->request->data('national') ;
                                    $student->resi_add1 = $this->request->data('resi_add1') ;
                                    $student->resi_add2 = $this->request->data('resi_add2') ;
                                    $student->country = $this->request->data('country') ;
                                    $student->state = $this->request->data('state') ;
                                    $student->city = $this->request->data('city') ;
                                    $student->phone_resi = $this->request->data('phone_resi') ;
                                    $student->phone_off = $this->request->data('phone_off') ;
                                    $student->gender =  $this->request->data('gender')  ;
                                    $student->mobile_for_sms =  $this->request->data('mobile_for_sms')  ;
                                    $student->email =  $this->request->data('email')  ;
                                    $student->stud_left =  0 ;
                                    $student->status =  0  ;
                                    $student->school_id = $this->request->data('school_id') ;
                                    $student->gr1_path = $gr1_path;
                                    $student->gr2_path = $gr2_path;
                                    $student->gr3_path = $gr3_path;
                                    $student->s_age =  $this->request->data('s_age')  ;
                                    $student->emergency_number =  $this->request->data('emergency_contact')  ;
                                    $student->emergency_name =  $this->request->data('emergency_name')  ;
                                    $student->created_date = time();
                                    $student->subjects = implode(",", $this->request->data['subjects']);
                                    $student->session_id = $session_id;
                                    if(!empty($this->request->data('lib_access')))
                                    {
                                        $student->library_access = $this->request->data('lib_access');
                                    }
                                    $student->contactyoume =  $this->request->data('contactyoume')  ;
                                    $student->motherphn =  implode(",",$this->request->data('motherphone'));
                                    $student->fatherphn =  implode(",",$this->request->data('fatherphone'));
                                    
                                    $first = trim($this->request->data('f_name'))  ;
                                    $last = trim($this->request->data('l_name'))  ;
                                    $sclid = $this->request->data('school_id') ;
                                    
                                    $fathername = trim($this->request->data('s_f_name'));
                                    $mothername = trim($this->request->data('s_m_name'));
                                    
                                    $dobb = explode(" year", $this->request->data('s_age'));
                                    $fathnumsonly = [];
                                    $mothnumsonly = [];
                                    if(!empty($this->request->data['motherphone']))
                                    {
                                        $mph = $this->request->data['motherphone'];
                                       
                                        foreach($mph as $val)
                                        {
                                            
                                            if (preg_match ("/^[0-9]*$/", $val) )
                                            {
                                                $mothnumsonly[] = 1;
                                            }
                                            else
                                            {
                                                $mothnumsonly[] = 0;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $mothnumsonly[] = 1;
                                    }
                                    
                                    if(!empty($this->request->data['fatherphone']))
                                    {
                                        $fph = $this->request->data['fatherphone'];
                                        
                                        foreach($fph as $val)
                                        {
                                            
                                            if (preg_match ("/^[0-9]*$/", $val) )
                                            {
                                                $fathnumsonly[] = 1;
                                            }
                                            else
                                            {
                                                $fathnumsonly[] = 0;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $fathnumsonly[] = 1;
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
                                    if(!in_array("0", $fathnumsonly))
                                    {
                                        
                                    if(!in_array("0", $mothnumsonly))
                                    {
                                    if ($contctyoume == 1)
                                    {
                                        if($saved = $student_table->save($student) )
                                        {   
                                            $newstdntid = $saved->id;
                                            $lasttt  = implode("", explode(" ", $last));
                                            $password = $first[0].$lasttt.$newstdntid;
                                            $studentno = $first.$sclid."00".$newstdntid;
                                            $parentpass = $newstdntid."P00".$fathername[0].$mothername[0].$sclid;
                                            
                                            if($retrieve_parentemail == 0)
                                            {
                                                $parentlogin = $parentlogin_table->newEntity();
                                                
                                                $parentlogin->parent_email =  trim($this->request->data('pemail'))  ;
                                                $parentlogin->parent_password =  trim($parentpass)  ;
                                                $parentlogin->created_date = time();
                                                
                                                $savedparentemail = $parentlogin_table->save($parentlogin);
                                                $parentid = $savedparentemail->id;
                                            }
                                            
                                            $renderer = new ImageRenderer(
                                                new RendererStyle(400),
                                                new ImagickImageBackEnd()
                                            );
                                            $qrcodeimg = uniqid()."_".$newstdntid;
                                            $writer = new Writer($renderer);
                                            $qr_image = base64_encode($writer->writeString($newstdntid.$studentno));
                                            
                                            $encode_stuid = base64_encode($newstdntid);
                                            $urlqrcode = 'https://you-me-globaleducation.org/idcard.php?studid='.$newstdntid;
                                            $writer->writeFile($urlqrcode, 'codeqr/'.$qrcodeimg.'.png'); 
                                            
                                            $qrcodeimg1 = $qrcodeimg.".png";
                                            $student_table->query()->update()->set(['adm_no' => $studentno , 'qrcode_img' => $qrcodeimg1, 'qrcode_link' => $urlqrcode,  'password' => $password , 'parent_id' => $parentid ])->where([ 'id' => $newstdntid  ])->execute();
                                            
                                            
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Student Created"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($newstdntid)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
    
                                            if($saved = $activ_table->save($activity) ){
                                                
                                                $rname = "You-Me Global Education Team";
                                                $name = $first.' '.$last;
                                                $subject = 'New Registration as a Student/Parent in You-Me Global Education';
                                                $to =  trim($this->request->data('email'));
                                                $from = "support@you-me-globaleducation.org";
                                                $pemail = trim($this->request->data('pemail'));
                                                
                                                $htmlContent = '
                                                <tr>
                                                <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                                    <multiline>
                                                        <p>Welcome to You-Me Global Education ('.$schoolname.')! We\'re here to transform your education digitally.</p>
                                                        <p>You-Me Global Education is a platform that provides a complete digital resource management system. Below are the details of your account.
</p>
                                                    </multiline>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                        <multiline>
                                                        <p><b>Student Login Details:</b></p>
                                                        <p>Login Link: '.$link.' </p>
                                                        <p>Username: '.$studentno.' </p>
                                                        <p>Password: '.$password.' </p>
                                                        </multiline>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                        <multiline>
                                                        <p><b>Parent Login Details:</b></p>
                                                        <p>Login Link: '.$link.' </p>
                                                        <p>Parent Email: '.$pemail.' </p>
                                                        <p>Password: '.$parentpass.' </p>
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
                                                $username = $first.' '.$last;
                                              
                                                $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                        
                                                $res = [ 'result' => 'success'  ];
                                            }
                                            else{
                                                $res = [ 'result' => 'activity not saved'  ];
                                            }
                    
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'student not saved'  ];
                                        }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Kindly insert only numbers in You-Me Contact number.'  ];
                                    }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Kindly insert only numbers in Mother Phone number.'  ];
                                    }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Kindly insert only numbers in Father Phone number.'  ];
                                    }
                                }
                                else{
                                $res = [ 'result' => 'number'  ];
                                }    
                            }
                            else{
                            $res = [ 'result' => 'mlength'  ];
                            }
                            
                          }
                            else{
                                $res = [ 'result' => 'Emergency Contact Number should be numeric digit only'  ];
                            }    
                        }
                        else{
                            $res = [ 'result' => 'Emergency Contact Number should be minimum of 10 digits'  ];
                        }
                    } 
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    }
                    } 
                    else
                    {
                        $res = [ 'result' => 'Parent email already exist.'  ];
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_table = TableRegistry::get('student');
                $state_table = TableRegistry::get('states');
                $countries_table = TableRegistry::get('countries');
                $class_table = TableRegistry::get('class');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');

                $retrieve_student = $student_table->find()->where([ 'md5(id)' => $id  ])->toArray() ;
                foreach($retrieve_student as $student)
                {
                    $parent_id = $student['parent_id'];
                    
                    $retrieve_parent = $parentlogin_table->find()->where([ 'id' => $parent_id  ])->first();
                    $student->parentemail = $retrieve_parent['parent_email'];
                    $student->parentpass = $retrieve_parent['parent_password'];
                }
                
                
                
                $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray();
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_student[0]['country']  ])->toArray() ;
                $retrieve_country = $countries_table->find()->select(['id' ,'name'])->toArray() ;
                
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects);

                $this->set("studentdetails", $retrieve_student);
                $this->set("state_details", $retrieve_state);
                $this->set("class_details", $retrieve_class);
                $this->set("country_details", $retrieve_country);
				
                $this->viewBuilder()->setLayout('user');
            }

            public function editstdnt()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {   
                    $student_table = TableRegistry::get('student');
                    $activ_table = TableRegistry::get('activity');
		            $session_id = $this->Cookie->read('sessionid');
		            $this->request->session()->write('LAST_ACTIVE_TIME', time());
		            $school_table = TableRegistry::get('company');
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    
	
                    $retrieve_student = $student_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) , 'id IS NOT' => $this->request->data('id') , 'session_id' => $session_id ])->count();
                    $retrieve_school = $school_table->find()->select(['id'])->where(['email' => trim($this->request->data('email'))])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                    
                    $retrieve_parentemail = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('pemail')) ])->count() ;
                    
                    $retrieve_student1 = $student_table->find()->select(['id'])->where(['email' => trim($this->request->data('pemail')) , 'id IS NOT' => $this->request->data('id') , 'session_id' => $session_id ])->count();
                    $retrieve_school1 = $school_table->find()->select(['id'])->where(['email' => trim($this->request->data('pemail'))])->count() ;
                    $retrieve_teacher1 = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_schoolsub1 = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    $retrieve_supersub1 = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('pemail')) ])->count() ;
                    
                    
                    $parentid = '';
                    if($retrieve_parentemail != 0)
                    {
                        $retrieve_parentemail = $parentlogin_table->find()->select(['id' ])->where(['parent_email' => trim($this->request->data('pemail')) ])->first() ;
                        $parentid = $retrieve_parentemail['id'];
                    }
                    
                    $ph_sms_length = strlen($this->request->data('mobile_for_sms'));
                    $em_ph_length = strlen($this->request->data('emergency_contact'));
                    if($retrieve_student1 == 0 && $retrieve_school1 == 0 && $retrieve_teacher1 == 0 && $retrieve_schoolsub1 == 0 && $retrieve_supersub1 == 0 )
                    {
                    if($retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                    {
                        if (preg_match ("/^[0-9]*$/", $this->request->data('emergency_contact')))
                        { 
                                if (preg_match ("/^[0-9]*$/", $this->request->data('mobile_for_sms')))
                                {   
                                    if(!empty($this->request->data['picture']['name']))
                                    {   
                                        if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                                        {
                                            $picture =  time().$this->request->data['picture']['name'];
                                            $filename = $picture;
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
                                        $picture =  $this->request->data('hpicture');
                                    }

                                    if(!empty($this->request->data['gr1_path']['name']))
                                    {   
                                        if($this->request->data['gr1_path']['type'] == "image/jpeg" || $this->request->data['gr1_path']['type'] == "image/jpg" || $this->request->data['gr1_path']['type'] == "image/png" )
                                        {
                                            $gr1_path = time().$this->request->data['gr1_path']['name'];
                                            $filename = $gr1_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr1_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr1_path'] = $filename; 
                                            }
                                        }    
                                    }
                                    else
                                    {
                                        $gr1_path =  $this->request->data('hgr1_path');
                                    }

                                    if(!empty($this->request->data['gr2_path']['name']))
                                    {   
                                        if($this->request->data['gr2_path']['type'] == "image/jpeg" || $this->request->data['gr2_path']['type'] == "image/jpg" || $this->request->data['gr2_path']['type'] == "image/png" )
                                        {
                                            $gr2_path = time().$this->request->data['gr2_path']['name'];
                                            $filename = $gr2_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr2_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr2_path'] = $filename; 
                                            }
                                        }    
                                    }
                                    else
                                    {
                                        $gr2_path =  $this->request->data('hgr2_path');
                                    }

                                    if(!empty($this->request->data['gr3_path']['name']))
                                    {   
                                        if($this->request->data['gr3_path']['type'] == "image/jpeg" || $this->request->data['gr3_path']['type'] == "image/jpg" || $this->request->data['gr3_path']['type'] == "image/png" )
                                        {
                                            $gr3_path = time().$this->request->data['gr3_path']['name'];
                                            $filename = $gr3_path;
                                            $uploadpath = 'img/';
                                            $uploadfile = $uploadpath.$filename;
                                            if(move_uploaded_file($this->request->data['gr3_path']['tmp_name'], $uploadfile))
                                            {
                                                $this->request->data['gr3_path'] = $filename; 
                                            }
                                        }    
                                    }
                                    else
                                    {
                                        $gr3_path =  $this->request->data('hgr3_path');
                                    }
                                

                                    $stdntid =  $this->request->data('id')  ;
                                    $f_name =  $this->request->data('f_name')  ;
                                    $l_name =  $this->request->data('l_name')  ;
                                    $pic =  $picture  ;
                                    $mobile_no = $this->request->data('mobile_no') ;
                                    $s_f_name = $this->request->data('s_f_name') ;
                                    $s_m_name = $this->request->data('s_m_name') ;
                                    $guardian_name = $this->request->data('guardian_name') ;
                                    $dob = date('Y-m-d', strtotime($this->request->data('dob')));
                                    $roll_no = $this->request->data('roll_no') ;
                                    $bloodgroup = $this->request->data('bloodgroup') ;
                                    $f_occ = $this->request->data('f_occ') ;
                                    $acc_no = $this->request->data('acc_no') ;
                                    $class = $this->request->data('class') ;
                                    $national = $this->request->data('national') ;
                                    $resi_add1 = $this->request->data('resi_add1') ;
                                    $resi_add2 = $this->request->data('resi_add2') ;
                                    $state = $this->request->data('state') ;
                                    $country = $this->request->data('country') ;
                                    $city = $this->request->data('city') ;
                                    $phone_resi = $this->request->data('phone_resi') ;
                                    $phone_off = $this->request->data('phone_off') ;
                                    $gender =  $this->request->data('gender')  ;
                                    $mobile_for_sms =  $this->request->data('mobile_for_sms')  ;
                                    $s_age =  $this->request->data('s_age')  ;
                                    $email =  trim($this->request->data('email'))  ;
                                    $password = $this->request->data('password') ;
                                    $canteenpin = $this->request->data('canteenpin') ;

                                    $emergency_number =  $this->request->data('emergency_contact')  ;
                                    $emergency_name =  $this->request->data('emergency_name')  ;
                                    $subjects = implode(",", $this->request->data['subjects']);
                                    $gr1_path = $gr1_path;
                                    $gr2_path = $gr2_path;
                                    $gr3_path = $gr3_path;
                                    
                                    $contactyoume =  $this->request->data('contactyoume')  ;
                                    $motherphn =  implode(",",$this->request->data['motherphone']);
                                    $fatherphn =  implode(",",$this->request->data['fatherphone']);
                                    
                                    $library_access = $this->request->data('lib_access');
                                    
                                    $dobb = explode(" year", $this->request->data('s_age'));
                                    /*if($dobb[0] > 2)
                                    {*/
                                    $fathnumsonly = [];
                                    $mothnumsonly = [];
                                    if(!empty($this->request->data['motherphone']))
                                    {
                                        $mph = $this->request->data['motherphone'];
                                       
                                        foreach($mph as $val)
                                        {
                                            
                                            if (preg_match ("/^[0-9]*$/", $val) )
                                            {
                                                $mothnumsonly[] = 1;
                                            }
                                            else
                                            {
                                                $mothnumsonly[] = 0;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $mothnumsonly[] = 1;
                                    }
                                    
                                    if(!empty($this->request->data['fatherphone']))
                                    {
                                        $fph = $this->request->data['fatherphone'];
                                        
                                        foreach($fph as $val)
                                        {
                                            
                                            if (preg_match ("/^[0-9]*$/", $val) )
                                            {
                                                $fathnumsonly[] = 1;
                                            }
                                            else
                                            {
                                                $fathnumsonly[] = 0;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $fathnumsonly[] = 1;
                                    }
                                    
                                    if(!empty($this->request->data('contactyoume')))
                                    {
                                        if(preg_match("/^[0-9]*$/", $this->request->data('contactyoume')))
                                        {
                                            $contctyoume = 1;
                                            $contactyoume = $this->request->data('contactyoume');
                                        }
                                        else
                                        {
                                            $contactyoume ='';
                                            $contctyoume = 0;
                                        }
                                    }
                                    else
                                    {
                                        $contactyoume = '';
                                        $contctyoume = 1;
                                    }
                                    if(!in_array("0", $fathnumsonly))
                                    {
                                        
                                    if(!in_array("0", $mothnumsonly))
                                    {
                                    if ($contctyoume == 1)
                                    {
                                    if( $student_table->query()->update()->set(['fatherphn' => $fatherphn, 'canteen_pincode' => $canteenpin,  'motherphn' => $motherphn , 'contactyoume' => $contactyoume,'library_access' => $library_access, 'subjects' => $subjects, 'emergency_number' => $emergency_number, 'emergency_name' => $emergency_name ,'f_name' => $f_name, 'l_name' => $l_name, 's_f_name' => $s_f_name , 's_m_name' => $s_m_name , 'guardian_name' => $guardian_name , 'roll_no'=>$roll_no , 'bloodgroup'=>$bloodgroup,  'f_occ'=>$f_occ, 'acc_no'=>$acc_no , 'class'=>$class, 'resi_add1' => $resi_add1, 'resi_add2'=>$resi_add2 , 'country' => $country, 'state'=>$state, 'city'=>$city,  'mobile_no' => $mobile_no, 'phone_resi'=>$phone_resi , 'phone_off'=>$phone_off,  'mobile_for_sms'=>$mobile_for_sms, 'email' => $email, 'password' => $password ,'dob' => $dob , 'national' => $national , 'pic'=> $picture  ,'gender'=> $gender, 's_age'=>$s_age , 'gr1_path'=>$gr1_path , 'gr2_path'=>$gr2_path , 'gr3_path'=>$gr3_path ])->where([ 'id' => $stdntid  ])->execute())
                                    {   
                                        
                                        $parentemail =  trim($this->request->data('pemail'))  ;
                                        $parentpassword = trim($this->request->data('parentpassword'));
                                    
                                        if($retrieve_parentemail == 0)
                                        {
                                            $parentlogin = $parentlogin_table->newEntity();
                                            
                                            $parentlogin->parent_email =  trim($parentemail)  ;
                                            $parentlogin->parent_password =  trim($parentpassword)  ;
                                            $parentlogin->created_date = time();
                                            
                                            $savedparentemail = $parentlogin_table->save($parentlogin);
                                            $parentid = $savedparentemail->id;
                                        }
                                        else
                                        {
                                            $parentlogin_table->query()->update()->set(['parent_email' => $parentemail, 'parent_password' => $parentpassword ])->where([ 'id' => $parentid  ])->execute();
                                        }
                                        $student_table->query()->update()->set(['parent_id' => $parentid ])->where([ 'id' => $stdntid  ])->execute();
                                            
                                    
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
                                         $res = [ 'result' => 'Kindly insert only numbers in You-Me Contact number.'  ];
                                    }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Kindly insert only numbers in Mother Phone number.'  ];
                                    }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Kindly insert only numbers in Father Phone number.'  ];
                                    }
                                    /*}
                                    else
                                    {
                                        $res = [ 'result' => 'age'  ];
                                    }*/
                                }
                                else
                                {
                                    $res = [ 'result' => 'number'  ];
                                }   
                        }
                        else
                        {
                            $res = [ 'result' => 'Emergency Contact Number should be numeric digit only'  ];
                        }    
                            
                                
                       
                    }
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    }
                    }
                    else
                    {
                        $res = [ 'result' => 'Parent email already exist.'  ];
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
                if ($this->request->is('ajax') && $this->request->is('post') )
                {   
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_table = TableRegistry::get('student');
                    $activ_table = TableRegistry::get('activity');
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    $classsubjects_table = TableRegistry::get('class_subjects');
                    $compid = $this->request->session()->read('company_id');
    		        //$session_id = $this->Cookie->read('sessionid');	
    		        $class_table = TableRegistry::get('class');	
    		        $parentlogin_table = TableRegistry::get('parent_logindetails');
    		        
    		        $session_table = TableRegistry::get('session');
					$now = strtotime('now');
    
                    $currentyear = date("Y", $now);
                    $currentmonth = date("m", $now);
                    
                    $retrieve_session = $session_table->find()->toArray() ;
                    $retrieve_school = $comp_table->find()->where(['id'=> $compid ])->first() ;
                    $schoolname = $retrieve_school['comp_name'];
                    $link = "http://you-me-globaleducation.org/school/login";
                    
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

                    if(!empty($this->request->data['file']['name']))
                    {
                        $fileexe = explode('.', $this->request->data['file']['name']);
                    
                        if($fileexe[1] =='csv')
                        {

                            $filename = $this->request->data['file']['tmp_name'];
                            
                            $handle = fopen($filename, "r");
                            setlocale(LC_CTYPE, "en_US.UTF-8");
                            $header = fgetcsv($handle);
                            $i = 0;
                            
                           // $retrieve_student_id = $student_table->find()->select(['id'])->where(['school_id' => $compid ])->last();


                            while (($row = fgetcsv($handle)) !== FALSE) 
                            {
                                $i++;

                                $data = array();
									
                                $f_name = $row[0];
                                $l_name = $row[1];
                        
                                $dob = $row[2];
                                $email = trim($row[3]);
                                $pemail = trim($row[4]);
                                
                                
								
								  $classes = trim(mb_convert_encoding($row[5],"UTF-8","HTML-ENTITIES"));
								  $section = trim($row[6]);
								  $sclsection = trim(mb_convert_encoding($row[7],"UTF-8","HTML-ENTITIES"));
								 
								$dateOfBirth = date("d-m-Y", strtotime($dob));
								$today = date("Y-m-d");
                                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                                $age = $diff->format('%y')."years".$diff->format('%m')."months";
								
								if(!empty($section))
								{
								    $retrieve_class = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'c_section'=> $section, 'school_id'=> $compid ])->count();
								    $classid = "";
								}
								else
								{
								    $retrieve_class = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'school_id'=> $compid ])->count();
								    $classid = "";
								}
								
								if($retrieve_class == 0)
								{
								     $classes = trim($row[5]);
    								 $section = trim($row[6]);
    								  $sclsection = trim($row[7]);
    							    if(!empty($section))
    								{
    								    $retrieve_class = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'c_section'=> $section, 'school_id'=> $compid ])->count();
    								    $classid = "";
    								}
    								else
    								{
    								    $retrieve_class = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'school_id'=> $compid ])->count();
    								    $classid = "";
    								}
								}
								
								
								$emergency_contact_no = $row[8];
							    $address = implode(",", explode("#", $row[9]));
                            
								//$retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email , 'school_id'=> $compid, 'session_id' => $session_id ])->count();
								$retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email  ])->count();
                                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $email])->count() ;
                                $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                                $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $email])->count() ;
                                $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                                $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $email ])->count() ;
                                
                                $retrieve_student1 = $student_table->find()->select(['id' ])->where(['email' => $pemail  ])->count();
                                $retrieve_school1 = $comp_table->find()->select(['id'])->where(['email' => $pemail])->count() ;
                                $retrieve_teacher1 = $teacher_table->find()->select(['id'])->where(['email' => $pemail ])->count() ;
                                $retrieve_schoolsub1 = $sclsubadmin_table->find()->select(['id'])->where(['email' => $pemail])->count() ;
                                $retrieve_supersub1 = $supersubad_table->find()->select(['id'])->where(['email' => $pemail ])->count() ;
                                
                                $retrieve_parentemail = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $pemail ])->count() ;
                                $parentid = '';
                                if($retrieve_parentemail != 0)
                                {
                                    $retrieve_paremail = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $pemail ])->first() ;
                                    $parentid = $retrieve_paremail['id'];
                                }
                    
                                
                   
                                $subjects = '';
								if($retrieve_class != 0)
								{
								    if(!empty($section))
    								{
    								    $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes ,  'school_sections' => $sclsection, 'c_section'=> $section, 'school_id'=> $compid  ])->first();
									}
    								else
    								{
    								    $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes ,  'school_sections' => $sclsection, 'school_id'=> $compid  ])->first();
									}
    								if(empty($retrieve_classid['id']))
    								{
    								     $classes = trim($row[5]);
        								 $section = trim($row[6]);
        								 $sclsection = trim($row[7]);
    									if(!empty($section))
        								{
        								    $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'c_section'=> $section, 'school_id'=> $compid ])->first();
        								    $classid = "";
        								}
        								else
        								{
        								    $retrieve_classid = $class_table->find()->select(['id'])->where(['c_name' => $classes , 'school_sections' => $sclsection, 'school_id'=> $compid ])->first();
        								    $classid = "";
        								}
    								}
									
								
									$classid = $retrieve_classid['id']; 
									
									$retrieve_class_subjects = $classsubjects_table->find()->where(['class_id' => $classid])->first();
									
									$subjects = $retrieve_class_subjects['subject_id'];
								}
								
								if($retrieve_student1 == 0 && $retrieve_school1 == 0 && $retrieve_teacher1 == 0 && $retrieve_schoolsub1 == 0 && $retrieve_supersub1 == 0)
                                { 
							    if($retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                                {   
                                    
                                       
                                    $student = $student_table->newEntity();
                                    $student->f_name = $f_name ;
                                    $student->l_name = $l_name ;
                                    $student->dob = date('Y-m-d', strtotime($dob)) ;
                                    $student->email =  $email ;
                                    //$student->parent_email =  $email ;
                                    //$student->password =  $password ;
                                    $student->school_id = $compid;
                					$student->class = $classid;
                					$student->session_id = $session_id; 
                					$student->status = 0 ;
                					$student->s_age = $age ;
                					$student->emergency_number = $emergency_contact_no ;
                					$student->subjects = $subjects ;
                					$student->resi_add1 = $address;
                					$student->created_date = time();
							//print_r($student);
    					            $em =  filter_var($email, FILTER_VALIDATE_EMAIL) ;
    					            $pem =  filter_var($pemail, FILTER_VALIDATE_EMAIL) ;
                                    $ph =  preg_match ("/^[0-9]*$/",$emergency_contact_no);
                                   
    								if($classid != ""  && $f_name != "" && $l_name != "" && $em != "" && $pem != "" && $ph == 1)
    								{
                                        if($saved = $student_table->save($student) )
                                        {  
                                            $newstdntid = $saved->id; 
                                            $lasttt  = implode("", explode(" ", $l_name));
                                            $password = $f_name[0].$lasttt.$newstdntid;
                                            $studentno = $f_name.$compid."00".$newstdntid;
                                            $parentpass = $newstdntid."P00FM".$compid;
                                            
                                            if($retrieve_parentemail == 0)
                                            {
                                                $parentlogin = $parentlogin_table->newEntity();
                                                
                                                $parentlogin->parent_email =  $pemail  ;
                                                $parentlogin->parent_password =  trim($parentpass)  ;
                                                $parentlogin->created_date = time();
                                                
                                                $savedparentemail = $parentlogin_table->save($parentlogin);
                                                $parentid = $savedparentemail->id;
                                            }
                                            
                                            $renderer = new ImageRenderer(
                                                new RendererStyle(400),
                                                new ImagickImageBackEnd()
                                            );
                                            $qrcodeimg = uniqid()."_".$newstdntid;
                                            $writer = new Writer($renderer);
                                            $qr_image = base64_encode($writer->writeString($newstdntid.$studentno));
                                            
                                            $encode_stuid = base64_encode($newstdntid);
                                            $urlqrcode = 'https://you-me-globaleducation.org/idcard.php?studid='.$newstdntid;
                                            $writer->writeFile($urlqrcode, 'codeqr/'.$qrcodeimg.'.png'); 
                                            
                                            $qrcodeimg1 = $qrcodeimg.".png";
                                            $student_table->query()->update()->set(['adm_no' => $studentno , 'qrcode_img' => $qrcodeimg1, 'qrcode_link' => $urlqrcode,  'password' => $password , 'parent_id' => $parentid ])->where([ 'id' => $newstdntid  ])->execute();
                                            
                                            
                                            
                                            //$student_table->query()->update()->set(['adm_no' => $studentno , 'password' => $password, 'parent_id' => $parentid])->where([ 'id' => $newstdntid  ])->execute();
                                        
                                            
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Student Imported Successfully"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($saved->id)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity) )
                                            {
                                                $first = $f_name;
                                                $last = $l_name;
                                                $rname = "You-Me Global Education Team";
                                                $name = $first.' '.$last;
                                                $subject = 'New Registration in You-Me Global Education ';
                                                $to =  $email;
                                                
                                                $from = "support@you-me-globaleducation.org";
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
                                                        <p><b>Student Login Details:</b></p>
                                                        <p>Login Link: '.$link.' </p>
                                                        <p>Username: '.$studentno.' </p>
                                                        <p>Password: '.$password.' </p>
                                                        </multiline>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                                                        <multiline>
                                                        <p><b>Parent Login Details:</b></p>
                                                        <p>Login Link: '.$link.' </p>
                                                        <p>Username: '.$pemail.' </p>
                                                        <p>Password: '.$parentpass.' </p>
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
                                                $username = $first.' '.$last;
                                                
                                               
                                                $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                                                
                                                $res = [ 'result' => 'success'  ];
                                                
                                            }
                                            else{
                                                $res = [ 'result' => 'activity not saved'  ];
                                            } 
                                                 
                        
                                        }
                                        else{
                                            $res = [ 'result' => 'student not saved'  ];
                                        }
        							}
                                    else
                                    {
                                        $err = 'Please check the row id '.$i.'. First Name, Last Name is mandatory. Please enter valid Class, Email and Phone Number';
                                        $res = ['result' => $err];
                                    }
                                }
								else
								{
								    $result = 'Student Email Id ('. $email.') already exist.';
                                    $res = [ 'result' => $result ];
                                }
                                }
                                else
								{
								    $result = 'Parent Email Id ('. $email.') already exist.';
                                    $res = [ 'result' => $result ];
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
            
function findage($dobbirth)
{
    $localtime = getdate();
    $today = $localtime['mday']."-".$localtime['mon']."-".$localtime['year'];
    $dob_a = explode("-", $dobirth);
    $today_a = explode("-", $today);
    $dob_d = $dob_a[0];$dob_m = $dob_a[1];$dob_y = $dob_a[2];
    $today_d = $today_a[0];$today_m = $today_a[1];$today_y = $today_a[2];
    $years = $today_y - $dob_y;
    $months = $today_m - $dob_m;
    if ($today_m.$today_d < $dob_m.$dob_d) 
    {
        $years--;
        $months = 12 + $today_m - $dob_m;
    }

    if ($today_d < $dob_d) 
    {
        $months--;
    }

    $firstMonths=array(1,3,5,7,8,10,12);
    $secondMonths=array(4,6,9,11);
    $thirdMonths=array(2);

    if($today_m - $dob_m == 1) 
    {
        if(in_array($dob_m, $firstMonths)) 
        {
            array_push($firstMonths, 0);
        }
        elseif(in_array($dob_m, $secondMonths)) 
        {
            array_push($secondMonths, 0);
        }elseif(in_array($dob_m, $thirdMonths)) 
        {
            array_push($thirdMonths, 0);
        }
    }
    echo "$years years $months months.";
}
			
			public function delete()
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
                
                $sclid = $student_table->find()->select(['id'])->where(['id'=> $sid ])->first();
                if($sclid)
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


      
            public function getsubjects()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $classsub_table = TableRegistry::get('class_subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id])->toArray(); 
                    return $this->json($get_subjects);
                }  
            }

            public function profile(){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }
            
            public function editstdntprofile()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $sessionid = $this->Cookie->read('sessionid');
                    $student_table = TableRegistry::get('student');
                    $activ_table = TableRegistry::get('activity');
                    $comp_table = TableRegistry::get('company');
                    $teacher_table = TableRegistry::get('employee');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $supersubad_table = TableRegistry::get('users');
                    
                    if(!empty($this->request->data['picture']['name']))
                    {   
                        if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                        {    
                            $picture =  time().$this->request->data['picture']['name'];
                            $filename = $picture;
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

                    $f_name =  $this->request->data('f_name')  ;
                    $l_name =  $this->request->data('l_name')  ;
                    $email =  $this->request->data('email')  ;
                    $mobile_no = $this->request->data('mobile_no') ;
                    $school_id =  $this->request->data('school_id') ;
                    $stdnt_id =  $this->request->data('id') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->Cookie->read('stid');
                    $modified = strtotime('now');
                    //$canteenpin = $this->request->data('canteenpin') ;
                    
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['session_id' => $sessionid, 'email' => $this->request->data('email'), 'id IS NOT' =>  $stdnt_id , 'school_id'=> $school_id ])->count() ;
                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $this->request->data('email')])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                    
                    
                    if($retrieve_student == 0 )
                    {
                        if(!empty($picture))
                        {
                            if($f_name != ""   || $email != ""  && $mobile_no != ""  )
                            {
                                if(!empty($this->request->data('contactyoume')))
                                {
                                    if(preg_match("/^[0-9]*$/", $this->request->data('contactyoume')))
                                    {
                                        $contctyoume = 1;
                                        $contactyoume = $this->request->data('contactyoume');
                                    }
                                    else
                                    {
                                        $contactyoume ='';
                                        $contctyoume = 0;
                                    }
                                }
                                else
                                {
                                    $contactyoume = '';
                                    $contctyoume = 1;
                                }
                                
                            if($contctyoume == 1) {
                                if($update_student = $student_table->query()->update()->set(['contactyoume' => $contactyoume, 'f_name' => $f_name ,  'l_name' => $l_name , 'email' => $email, 'mobile_for_sms' => $mobile_no , 'pic' => $picture  ])->where(['md5(id)' =>  $userid ])->execute()){
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Student Data Updated"  ;
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
                            }
                            else
                            {
                                 $res = [ 'result' => 'Kindly insert only numbers in You-Me Contact number.'  ];
                            }
        
                                if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword ){
                                    if($update_student = $student_table->query()->update()->set([  'password' => $password ])->where(['md5(id)' =>  $userid ])->execute()){
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Student Password Updated"  ;
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
                            else{
                                $res = [ 'result' => 'empty'  ];
        
                            }
                        }
                        else{ 
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }
                    }
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }        
                }
                else{
                    $res = [ 'result' => 'Invalid Operation'  ];
                }


                return $this->json($res);

            }
	    
		public function status()
        {   
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $student_table->find()->select(['id'])->where(['id'=> $uid ])->first();
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

		public function deleteallstud()
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
        
        public function export()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $student_table = TableRegistry::get('student');
            $scl_table = TableRegistry::get('company');
            $compid =$this->request->session()->read('company_id');
            $sessionid = $this->Cookie->read('sessionid');
            $datascl = $scl_table->find()->where(['id' => $compid])->first() ;  
            
            $sclname = $datascl['comp_name'].'_students.csv';
            
            $data = $student_table->find()->select(['student.f_name','student.l_name','student.emergency_number','student.email','student.adm_no','student.password','parent_logindetails.parent_email', 'parent_logindetails.parent_password', 'class.c_name', 'class.c_section', 'class.school_sections'])->join([
                        'parent_logindetails' => 
						[
							'table' => 'parent_logindetails',
							'type' => 'LEFT',
							'conditions' => 'parent_logindetails.id = student.parent_id'
						], 
						'class' => 
						[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.school_id' => $compid, 'student.session_id' => $sessionid])->toArray() ;  
            $studnt = [];
            $dbdata = [];
            foreach($data as $expdata)
            {
                $studnt['class'] = $expdata['class']['c_name']."-".$expdata['class']['c_section']." (".$expdata['class']['school_sections'].")";
                $studnt['name'] = $expdata['l_name']." ".$expdata['f_name'];
                
                $studnt['email'] = $expdata['email'];
                $studnt['adm_no'] = $expdata['adm_no'];
                $studnt['password'] = $expdata['password'];
                $studnt['emergency_number'] = $expdata['emergency_number'];
                $studnt['parent_email'] = $expdata['parent_logindetails']['parent_email'];
                $studnt['parent_password'] = $expdata['parent_logindetails']['parent_password'];
                
                $dbdata[] = $studnt;
            }
            //print_r($dbdata); die;
            
            
            $this->setResponse($this->getResponse()->withDownload($sclname));
            $_header = array('Class','Name', 'Student Email' ,'Student Number' , 'Student Password' , 'Mobile Number' , 'Parent Email' ,'Parent Password');
            $_serialize = 'dbdata';
            $this->viewBuilder()->setClassName('CsvView.Csv');
            $this->set(compact('dbdata', '_header' , '_serialize'));
        }
	
        public function deleterequest()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data('stuid') ;
            $sts = $this->request->data('studelreqsts') ;
            $stsrsn = $this->request->data('delrsn') ;
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');

            $userid = $student_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($userid)
            {   
                $stats = $student_table->query()->update()->set([ 'delete_request' => $status, 'del_req_reason' => $stsrsn])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Student Delete request"  ;
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
        
        public function getstudsess()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $student_table = TableRegistry::get('student');
            $compid = $this->request->session()->read('company_id');
			$session_id = $this->request->data('sessionid') ;
			
			$sclsub_table = TableRegistry::get('school_subadmin');
			if($this->Cookie->read('logtype') == md5('School Subadmin'))
			{
				$sclsub_id = $this->Cookie->read('subid');
				$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
				$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
			}

            $retrieve_student_table = $student_table->find()->select(['session.startyear', 'session.endyear', 'student.qrcode_img', 'student.id' , 'student.email' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
						[
						'table' => 'class',
						'type' => 'LEFT',
						'conditions' => 'class.id = student.class'
					],
					[
						'table' => 'session',
						'type' => 'LEFT',
						'conditions' => 'session.id = student.session_id'
					]
				])->where(['student.school_id' => $compid, 'student.session_id' => $session_id ])->toArray() ;
				
			$data ='';
            foreach($retrieve_student_table as $value)
            {
                $libacc = ($value['library_access'] == 0) ? "No" : "Yes";
                
                /*if(!empty($user_details[0]['id']))
                {*/
                    if( $value['status'] == 0)
                    {
                       $sts = '<a href="javascript:void()" data-url="students/status" data-id="'.$value['id'] .'" data-status="'. $value['status'].'" data-str="Student Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                    }
                    else 
                    { 
                       $sts = '<a href="javascript:void()" data-url="students/status" data-id="'.$value['id'].'" data-status="'. $value['status'].'" data-str="Student Status" class="btn btn-sm js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                    }
                /*}
                else
                {
                    if( $value['status'] == 0)
                    {
                        $sts ='<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                    }
                    else 
                    { 
                        $sts = '<label class="switch">
                          <input type="checkbox" checked disabled >
                          <span class="slider round"></span>
                        </label>';
                    
                    }
                }*/
                
                if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
                    
                    if(strtolower($value['class']['school_sections']) == "creche" || strtolower($value['class']['school_sections']) == "maternelle") {
                        $clsmsg = "kindergarten";
                    }
                    elseif(strtolower($value['class']['school_sections']) == "primaire") {
                        $clsmsg = "primaire";
                    }
                    else
                    {
                        $clsmsg = "secondaire";
                    }
                    
                    if(in_array($clsmsg, $subpriv)) { 
                        $show = 1;
                    }
                    else
                    {
                        $show = 0;
                    }
				}
				else
				{
				     $show = 1;
				}
                
                $reqsent = "Delete Request Sent" ;
                $req = "Delete Request";
                
                if($value['delete_request'] == 0) { 
                $delreq = '<a href="javascript:void()" data-url="student/deleterequest" data-id="'.$value['id'] .'" data-delreq="'.$value['delete_request'].'" data-str="Student" class="btn btn-sm btn-outline-success deleterequeststu" title="Delete Request" data-type="status_change">'.str_replace("0",$req,$value['delete_request']) .' </a></span>';
                 } else { 
                $delreq = '<a href="javascript:void()" data-url="student/deleterequest" data-id="'.$value['id'] .'" data-delreq="'.$value['delete_request'].'" data-str="Student" class="btn btn-sm btn-outline-danger deleterequeststu" title="Delete Request" data-type="status_change">'. str_replace("1",$reqsent,$value['delete_request']) .' </a></span>';
                } 
                   
                if($show == 1) {             
                    if($value['qrcode_img'] == "" ){
                        $img = "" ;
                        $imgqr = "#";
                    }
                    else{
                        $img = '<img src="'.$baseurl.'codeqr/'.$value['qrcode_img'].'" class="rounded-circle avatar" alt="">';
                        $imgqr = $baseurl.'codeqr/'.$value['qrcode_img'];

                    }                                             
                    $data .= '<tr>
                            <td>
                                <span><a class="example-image-link" href="'. $imgqr .'" data-lightbox="example-1">'. $img .'</a></span>
                            </td>
                            <td>
                                <span>'. $value['class']['c_name']. "-".$value['class']['c_section']. " (". $value['class']['school_sections']. ")" .'</span>
                            </td>
                            
                            <td>
                                <span>'.$value['email'].'</span>
                            </td>
                            <td>
                                <span>'.$value['password'].'</span>
                            </td>
                            <td>
                                <span class="font-weight-bold"> '. $value['f_name']. " ".$value['l_name'].'</span>
                            </td>
                            <td>
                                <span>'. $libacc .'</span>
                            </td>
                            <td>
                                <span>'. $value['session']['startyear']."-".$value['session']['endyear'] .'</span>
                            </td>
                            <td>
                                <span>'. $value['del_req_reason'] .'</span>
                            </td>
                            <td>
                                <span>'. $sts .'</span>
                            </td>
                            <td>
                                <a href="'.$this->base_url .'students/edit/'. md5($value['id']) .'" title="Edit" id="'. $value['id'] .'" class="btn btn-sm btn-outline-secondary" ><i class="fa fa-edit"></i></a>
                                '.$delreq.'
                            </td>
                        </tr>';
                }
                        
            }
            return $this->json($data);
				
		
        }
            
        public function view($id)
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_table = TableRegistry::get('student');
                $state_table = TableRegistry::get('states');
                $countries_table = TableRegistry::get('countries');
                $class_table = TableRegistry::get('class');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');

                $retrieve_student = $student_table->find()->where([ 'md5(id)' => $id  ])->toArray() ;
                foreach($retrieve_student as $student)
                {
                    $parent_id = $student['parent_id'];
                    
                    $retrieve_parent = $parentlogin_table->find()->where([ 'id' => $parent_id  ])->first();
                    $student->parentemail = $retrieve_parent['parent_email'];
                    $student->parentpass = $retrieve_parent['parent_password'];
                }
                
                
                
                $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray();
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_student[0]['country']  ])->toArray() ;
                $retrieve_country = $countries_table->find()->select(['id' ,'name'])->toArray() ;
                
                $subjects_table = TableRegistry::get('subjects');
                $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                $this->set("subject_details", $retrieve_subjects);

                $this->set("studentdetails", $retrieve_student);
                $this->set("state_details", $retrieve_state);
                $this->set("class_details", $retrieve_class);
                $this->set("country_details", $retrieve_country);
				
                $this->viewBuilder()->setLayout('user');
            } 
        
            
}

  


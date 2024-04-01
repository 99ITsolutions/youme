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
class AdmissionsController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
    **/
    
    public function index()
    {   
        $country_table = TableRegistry::get('countries');
        $state_table = TableRegistry::get('states');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session');
        $student_table = TableRegistry::get('student');
        $subjects_table = TableRegistry::get('subjects');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		if(!empty($compid)) {
            $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
            $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
            $retrieve_session = $session_table->find()->toArray() ;
            $this->set("country_details", $retrieve_country);
            $this->set("subject_details", $retrieve_subjects); 
            $this->set("class_details", $retrieve_class);
            
            $this->set("session_details", $retrieve_session);
            $this->viewBuilder()->setLayout('user');
		}
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function addadm()
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
            
            /*foreach($retrieve_session as $getsession) 
            {
                $getmonthids_start = date("m", strtotime($getsession['startmonth']));
                $getmonthids_end = date("m", strtotime($getsession['endmonth']));
                $strtyr =  $getsession['startyear'];
                $endyr =  $getsession['endyear'];
                
                if( (($currentyear == $strtyr) && ($currentmonth <= 12 && $currentmonth >= $getmonthids_start)) || (($currentyear == $endyr) && ($currentmonth <= $getmonthids_end && $currentmonth >= 1)) )
                {
                    $session_id = $getsession['id'];
                }
            }*/
            
             $session_id = $this->request->data('sessionid');

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
                                    $filename = time()."_".$this->request->data['picture']['name'];
                                    $picture = $filename;
                                    
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
                                    $filename = time()."_".$this->request->data['gr1_path']['name'];
                                    $gr1_path = $filename;
                                    
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
                                    $filename = time()."_".$this->request->data['gr2_path']['name'];
                                    $gr2_path = $filename;
                                    
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
                                    $filename = time()."_".$this->request->data['gr3_path']['name'];
                                    $gr3_path = $filename;
                                    
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
                            $student->country = $this->request->data('admcountry') ;
                            $student->state = $this->request->data('admstate') ;
                            $student->city = $this->request->data('admcity') ;
                            $student->phone_resi = $this->request->data('phone_resi') ;
                            $student->phone_off = $this->request->data('phone_off') ;
                            $student->gender =  $this->request->data('gender')  ;
                            $student->mobile_for_sms =  $this->request->data('mobile_for_sms')  ;
                            $student->email =  $this->request->data('email')  ;
                           // $student->parent_email =  $this->request->data('email')  ;
                         
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
                            $student->motherphn =  implode(",",$this->request->data['motherphone']);
                            $student->fatherphn =  implode(",",$this->request->data['fatherphone']);
                            
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
                                    
                                    //$student_table->query()->update()->set(['adm_no' => $studentno , 'password' => $password , 'parent_id' => $parentid ])->where([ 'id' => $newstdntid  ])->execute();
                                    
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
                
                                        $res = [ 'result' => 'success' , 'studid' => md5($newstdntid) ];
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
/*	  } 
                 else{
                    $res = [ 'result' => 'roll'  ];
                 }*/       
                  
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
    
    public function print($studid)
    {
        $lang = $this->Cookie->read('language');
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
            
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->request->session()->read('company_id');; 
        if(!empty($sclid)) 
        {
            $school_table = TableRegistry::get('company');
            $class_table = TableRegistry::get('class');
            $session_table = TableRegistry::get('session');
            $student_table = TableRegistry::get('student');
            $parntlogin_table = TableRegistry::get('parent_logindetails');
            
            $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
            $school_logo = '<img src="../../img/'. $retrieve_school[0]['comp_logo'].'" style="width:85px !important;">';
            
            $school_name =  $retrieve_school[0]['comp_name'].", ".$retrieve_school[0]['city'];
           
            $retrieve_student = $student_table->find()->where(['md5(id)' => $studid])->first();
            $studentid = $retrieve_student['id'];
            $classid = $retrieve_student['class'];
            $sessionid = $retrieve_student['session_id'];
        
            
            $cls = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
            $retrieve_student = $student_table->find()->where(['id' => $studentid])->first();
            $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
            $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                                
            $admno = $retrieve_student['adm_no'];
            $studentname = $retrieve_student['f_name']." ".$retrieve_student['l_name'];
            
            if(!empty($retrieve_student['created_date']))
            {
                $cdate = date("d M, Y", $retrieve_student['created_date']);
            }
            else
            {
                $cdate = '';
            }
            $parent_dtl = $parntlogin_table->find()->where(['id' => $retrieve_student['parent_id']])->first();
            
            if(!empty($retrieve_student['pic']))
            {
                $stupic = $retrieve_student['pic'];
            }
            else
            {
                $stupic = "male.jpg";
            }
            $image = '<img src="../../img/'. $stupic.'" style="width:90px !important; height:80px !important;">';
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'] ." (". $retrieve_class['school_sections'] .")";
            $session_year = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
            
            foreach($retrieve_langlabel as $langlbl) { 
                                        if($langlbl['id'] == '130') { $studno = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '150') { $grade = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '147') { $stuname = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '231') { $email = $langlbl['title'] ; }
                                        if($langlbl['id'] == '2154') { $pass = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '135') { $mobile = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '145') { $dob = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '151') { $blood = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '301') { $addr = $langlbl['title'] ; } 
                                        if($langlbl['id'] == '2102') { $princ = $langlbl['title'] ; } 
                                         if($langlbl['id'] == '175') { $pemail = $langlbl['title'] ; }
                                        if($langlbl['id'] == '1537') { $ppass = $langlbl['title'] ; } 
                                    }
                            
            $header = '<style> td p { margin-bottom:5px; } </style><table style=" width: 100%;">
                    <tbody>
                        <tr>
                            <td  style="width: 100%;">
                                <table style="width: 100%;  ">
                                <tr>
                                    <td  style="width: 100%; float:left; ">
                                        <table style="width: 100%;  ">
                                            <tr style="border-bottom:1px solid #000;">
                                                <td style="padding-bottom:10px;">
                                                    <table style="width: 100%;">
                                                    <tr>
                                                        <td  style="width: 30%; text-align:left; padding: 0px 5px;"><span> '.$school_logo.' </span></td>
                                                        <td  style="width: 40%; text-align:left; padding: 0px 5px;">
                                                            <span style="font-size: 16px; font-weight:bold; font-family:unset;">'.ucwords($school_name).'</span>
                                                        </td>
                                                        <td  style="width: 30%; text-align:right;"><span> '.$image.' </span></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td>
                                                    <table style="width: 100%; ">
                                                    <tr>
                                                    <td  style="text-align:center;font-weight:bold;background: #292929; color:#ffffff;font-family: sans-serif;"><span> School Year : '.$session_year.' </span></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td style="margin-bottom:5px;">
                                                    <table style="width: 100%;" id="column_td">
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 30%; text-align:left">'. $stuname.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($studentname).' </span></td>
                                                    </tr> 
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left">Date Admission/Readimisson</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.$cdate.' </span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left">'. $studno.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($admno).' </span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left"> '.$grade.' </td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.$classname.' </span></td>
                                                    </tr>
                                                </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 0; border-top:1px dashed">
                                                    <table style="width: 100%; ">
                                                    <tr>
                                                    <td  style="font-weight:bold;font-family: sans-serif;"><span>Student You-Me Username & Password - </span></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table style="width: 100%;" id="column_td">
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left"> '.$email.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span>'.$retrieve_student['email'].' </span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left"> '.$pass.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: arial;"><span>'.$retrieve_student['password'].' </span></td>
                                                    </tr>
                                                </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 0; border-top:1px dashed">
                                                    <table style="width: 100%; ">
                                                    <tr>
                                                    <td  style="font-weight:bold;font-family: sans-serif;"><span>Parent You-Me credentials - </span></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table style="width: 100%;" id="column_td">
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left"> '.$pemail.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span>'.$parent_dtl['parent_email'].' </span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 5%;"></td>
                                                        <td style="width: 40%; text-align:left"> '.$ppass.'</td>
                                                        <td style="width: 5%; text-align:left">: </td>
                                                        <td style="text-align:left; width: 45%; font-family: arial;"><span>'.$parent_dtl['parent_password'].' </span></td>
                                                    </tr>
                                                </table>
                                                </td>
                                            </tr>
                                            <tr >
                                                <td>
                                                    <table style="width: 100%; ">
                                                    <tr>
                                                    <td  style="text-align:right;font-weight:bold;font-family: sans-serif; padding-top:30px; padding-right:10px;"><span>School Stamp</span></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                </table>';
                
            $viewpage = '<div style=" width:70%; margin:0 auto; font-weight: bold; font-size: 15px; padding-bottom:20px; border:1px solid #ccc">'.$header.'</div>';
            $style = 'style="display:none;"';
            $dnloadreport = 'style="display:block;"';
            $error = "";
            $adm_no = $admno;
            
            $this->set("error", $error); 
            $this->set("downloadreport", $dnloadreport); 
            $this->set("adm_no", $adm_no); 
            $this->set("style", $style); 
            $this->set("viewpage", $viewpage); 
            $this->set("class_details", $retrieve_classes); 
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
}

  


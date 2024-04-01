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
class ReadmissionsController   extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->request->session()->read('company_id');
        $school_table = TableRegistry::get('company');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session');
        $country_table = TableRegistry::get('countries');
        $parentlogin_table = TableRegistry::get('parent_logindetails');
        $retrieve_session = $session_table->find()->toArray() ;
        $this->set("session_details", $retrieve_session);
        if(!empty($sclid))
        {
        $retrieve_classes = $class_table->find()->where(['school_id' => $sclid, 'active' => 1])->toArray();
        $school_id =$this->request->session()->read('company_id');
        $subjects_table = TableRegistry::get('subjects');
        $retrieve_subjects = $subjects_table->find()->where(['school_id' => $school_id, 'status' => 1])->toArray() ;
        $this->set("subject_details", $retrieve_subjects); 
        
        //$retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
        
        if($this->Cookie->read('logtype') == md5('School Subadmin'))
		{
		    $sclsub_table = TableRegistry::get('school_subadmin');
			$sclsub_id = $this->Cookie->read('subid');
			$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $sclid ])->first() ; 
			$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
			$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
		}
       
        $lang = $this->Cookie->read('language');
        if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
            $lang = 2;
        }
        
        //echo $lang;
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
            if($langlbl['id'] == '1976') { $studnodoesntexist = $langlbl['title'] ; } 
        } 
        
        
        if(!empty($_POST))
        {
            $searchid = $this->request->data('searchform');
            
            $subject_table = TableRegistry::get('subjects');
            $school_table = TableRegistry::get('company');
            $class_table = TableRegistry::get('class');
            $student_table = TableRegistry::get('student');
            $session_table = TableRegistry::get('session');
          
        
            $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
            $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important;">';
            $working_days = $retrieve_school[0]['present_days'];
            if($searchid  == 1)
            {
                $student_no = $this->request->data('student_no');
                $sessionid = $this->request->data('start_year');
                $student_exist = $student_table->find()->where(['adm_no' => $student_no, 'school_id' => $sclid, 'session_id' => $sessionid, 'status' => 1])->count();
                $retrieve_student = $student_table->find()->where(['adm_no' => $student_no, 'school_id' => $sclid, 'session_id' => $sessionid, 'status' => 1])->first();
                $studentid = $retrieve_student['id'];
                $classid = $retrieve_student['class'];
                
            }
            else
            {
                $studentid = $this->request->data('student');
                $classid = $this->request->data('class');
                $sessionid = $this->request->data('start_year');
                //$student_exist = 1;
                $student_exist = $student_table->find()->where(['id' => $studentid, 'session_id' => $sessionid, 'status' => 1])->count();
            }
            
            $cls = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
            if($this->Cookie->read('logtype') == md5('School Subadmin'))
			{
			    //print_r($cls);
                if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                    $clsmsg = "kindergarten";
                }
                elseif(strtolower($cls['school_sections']) == "primaire") {
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
		    if($show == 1) {
           
            if($student_exist == 1)
            {
            
                $retrieve_student = $student_table->find()->where(['id' => $studentid, 'session_id' => $sessionid, 'status' => 1])->first();
                $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                
                $state_table = TableRegistry::get('states');
                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_student['country']  ])->toArray() ;
                $retrieve_country = $country_table->find()->toArray() ;
                
                $sessionyr = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                
                $parent_id = $retrieve_student['parent_id'];
                    
                $retrieve_parent = $parentlogin_table->find()->where([ 'id' => $parent_id  ])->first();
                $retrieve_student['parentemail'] = $retrieve_parent['parent_email'];
                $retrieve_student['parentpass'] = $retrieve_parent['parent_password'];
               
                $style = 'style="display:none;"';
                $reportdata = 'style="display:block;"';
                $searchicon = 'style="display:block;"';
                $closeicon = 'style="display:none;"';
                $error = "";
                $downloadreport = 'style="display:block;"';
            }
            else
            {
                $error = $studnodoesntexist;
                $style = '';
                $searchicon = 'style="display:none;"';
                $closeicon = 'style="display:none;"';
                $reportdata = 'style="display:none;"';
                $retrieve_student = '';
                $retrieve_state ='';
                $retrieve_country = '';
                $downloadreport = 'style="display:none;"';
                $sessionyr = '';
                $classname = '';
            }
		    }
		    else
            {
                $error = "You don't have access of this class students.";
                $style = '';
                $searchicon = 'style="display:none;"';
                $closeicon = 'style="display:none;"';
                $reportdata = 'style="display:none;"';
                $retrieve_student = '';
                $retrieve_state ='';
                $retrieve_country = '';
                $downloadreport = 'style="display:none;"';
                $sessionyr = '';
                $classname = '';
            }
        }
        elseif(!empty($_GET))
        {
            $searchid = $this->request->data('searchform');
            
            $subject_table = TableRegistry::get('subjects');
            $school_table = TableRegistry::get('company');
            $class_table = TableRegistry::get('class');
            $student_table = TableRegistry::get('student');
            $session_table = TableRegistry::get('session');
        
            $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
            $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important;">';
            $working_days = $retrieve_school[0]['present_days'];
            
            $studentid = $_GET['id'];
            $student_exist = $student_table->find()->where(['id' => $studentid])->count();
            $student_data = $student_table->find()->where(['id' => $studentid])->first();
            $classid = $student_data['class'];
            $sessionid = $student_data['session_id'];
            
            $cls = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
            if($this->Cookie->read('logtype') == md5('School Subadmin'))
			{
                if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                    $clsmsg = "kindergarten";
                }
                elseif(strtolower($cls['school_sections']) == "primaire") {
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
		    if($show == 1) {
                if($student_exist == 1)
                {
                
                    $retrieve_student = $student_table->find()->where(['id' => $studentid, 'session_id' => $sessionid, 'status' => 1])->first();
                    
                    //print_r($retrieve_student); die;
                    $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                    $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                    
                    $state_table = TableRegistry::get('states');
                    $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_student['country']  ])->toArray() ;
                    $retrieve_country = $country_table->find()->toArray() ;
                    
                    $sessionyr = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                    $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                    
                    /*foreach($retrieve_student as $student)
                    {*/
                        $parent_id = $retrieve_student['parent_id'];
                        
                        $retrieve_parent = $parentlogin_table->find()->where([ 'id' => $parent_id  ])->first();
                        $retrieve_student['parentemail'] = $retrieve_parent['parent_email'];
                        $retrieve_student['parentpass'] = $retrieve_parent['parent_password'];
                    //}
                   
                    $style = 'style="display:none;"';
                    $reportdata = 'style="display:block;"';
                    $searchicon = 'style="display:block;"';
                    $closeicon = 'style="display:none;"';
                    $error = "";
                    $downloadreport = 'style="display:block;"';
                }
                else
                {
                    $error = $studnodoesntexist;
                    $style = '';
                    $searchicon = 'style="display:none;"';
                    $closeicon = 'style="display:none;"';
                    $reportdata = 'style="display:none;"';
                    $retrieve_student = '';
                    $retrieve_state ='';
                    $retrieve_country = '';
                    $downloadreport = 'style="display:none;"';
                    $sessionyr = '';
                    $classname = '';
                }
    		}
            else
            {
                $error = "You don't have access of this class students.";
                $style = '';
                $searchicon = 'style="display:none;"';
                $closeicon = 'style="display:none;"';
                $reportdata = 'style="display:none;"';
                $retrieve_student = '';
                $retrieve_state ='';
                $retrieve_country = '';
                $downloadreport = 'style="display:none;"';
                $sessionyr = '';
                $classname = '';
            }
        }
        else
        {
            $style = '';
            $searchicon = 'style="display:none;"';
            $downloadreport = 'style="display:none;"';
            $closeicon = 'style="display:none;"';
            $error = "";
            $reportdata = 'style="display:none;"';
            $retrieve_student= '';
            $retrieve_state = '';
            $retrieve_country = '';
            $sessionyr = '';
            $classname = '';
        }
        
        //print_r($retrieve_student); die;
        $this->set("sessionyr", $sessionyr);
        $this->set("classname", $classname);
        $this->set("downloadreport", $downloadreport);
        $this->set("school_id", $school_id);
        $this->set("error", $error); 
        $this->set("student_details", $retrieve_student); 
        $this->set("style", $style); 
        $this->set("searchicon", $searchicon); 
        $this->set("closeicon", $closeicon); 
        $this->set("viewpage", $reportdata);
        $this->set("state_details", $retrieve_state);
        $this->set("class_details", $retrieve_classes); 
        $this->set("country_details", $retrieve_country);
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
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $comp_table = TableRegistry::get('company');
            $class_table = TableRegistry::get('class');
            $session_table = TableRegistry::get('session');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $compid =$this->request->session()->read('company_id');
		    if(!empty($compid))
		    {
            $retrieve_school = $comp_table->find()->where(['id'=> $compid ])->first() ;
            $schoolname = $retrieve_school['comp_name'];
            $link = "http://you-me-globaleducation.org/school/login?slug=".$retrieve_school['www'];
            
            $session_id = $this->request->data('sessionid');
            $retrieve_student = $student_table->find()->select(['id'  ])->where(['email' => $this->request->data('email') , 'session_id' => $session_id, 'school_id'=> $compid ])->count() ;
           
            $retrieve_class = $class_table->find()->where(['id' => $this->request->data('class_s')])->first() ;
           
           
            $ph_sms_length= strlen($this->request->data('mobile_for_sms'));
            $em_phn_length= strlen($this->request->data('emergency_contact'));
            if($retrieve_student == 0 )
            {   
                $picture = "";
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
                                            $filename = $filename; 
                                        }
                                    }    
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
                                            $filename = $filename; 
                                        }
                                    }    
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
                                            $filename = $filename; 
                                        }
                                    }    
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
                                            $filename = $filename; 
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
                            $student->roll_no = $this->request->data('roll_no') ;
                            $student->bloodgroup = $this->request->data('bloodgroup') ;
                            $student->f_occ = $this->request->data('f_occ') ;
                            $student->class = $this->request->data('class_s') ;
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
                            $student->email =  trim($this->request->data('email'))  ;
                            //$student->parent_email =  trim($this->request->data('email'))  ;
                            $student->stud_left =  0 ;
                            $student->status =  1  ;
                            $student->school_id = $this->request->data('school_id') ;
                            $student->gr1_path = $gr1_path;
                            $student->gr2_path = $gr2_path;
                            $student->gr3_path = $gr3_path;
                            $student->s_age =  $this->request->data('s_age')  ;
                            $student->emergency_number =  $this->request->data('emergency_contact')  ;
                            $student->emergency_name =  $this->request->data('emergency_name')  ;
                            $student->subjects = implode(",", $this->request->data('subjects') ) ;
                            $student->session_id = $session_id;
                            $student->password =  $this->request->data('password')  ;
                            //$student->parent_password = $this->request->data('parentpassword');
                            $student->adm_no =  $this->request->data('adm_no')  ;
                            $student->parent_id = $this->request->data('parentid');
                            $student->created_date = time();
                            
                            $first = trim($this->request->data('f_name'));
                            $last = trim($this->request->data('l_name'))  ;
                            $sclid = $this->request->data('school_id') ;
                            $password = $this->request->data('password')  ;
                            $studentno = $this->request->data('adm_no')  ;
                            $parentpass = $this->request->data('parentpassword');
                            
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
                                /*$password = $first[0].$last.$newstdntid;
                                $studentno = $first.$sclid."00".$newstdntid;
                                $student_table->query()->update()->set(['adm_no' => $studentno , 'password' => $password])->where([ 'id' => $newstdntid  ])->execute();*/
                                
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
                                $student_table->query()->update()->set([ 'qrcode_img' => $qrcodeimg1, 'qrcode_link' => $urlqrcode])->where([ 'id' => $newstdntid  ])->execute();
                                            
                                            
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Student Created"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($newstdntid)   ;
                                $activity->origin = $this->Cookie->read('id')   ;
                                $activity->created = strtotime('now');
    
                                    if($saved = $activ_table->save($activity) ){
                                        
                                        $rname = "You-Me Global-Education Team";
                                        $name = $first.' '.$last;
                                        $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                                        $subject = 'Congrats! Promoted to New Class in You-Me Global Education';
                                        $to =  trim($this->request->data('email'));
                                        $from = "support@you-me-globaleducation.org";
                                        $htmlContent = '
                                        <tr>
                                            <td class="text" style="color:#191919; font-size:14px;  text-align:left;">
                                                <multiline>
                                                    <p>Congratulations '.$name.', You are successfully promoted to new class ('.$classname.').</p>
                                                </multiline>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text" style="color:#191919; font-size:14px;  text-align:left;">
                                                <multiline>
                                                    <p>You-Me Global-Education ('.$schoolname.'), are here to transform your education digitally.</p>
                                                </multiline>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text" style="color:#191919; font-size:14px; text-align:left;">
                                                <multiline>You-Me Global-Education is a platform that provides a complete digital resource management system. Below are the details of your account.</multiline>
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
                                                <p>Username: '.$to.' </p>
                                                <p>Password: '.$parentpass.' </p>
                                                </multiline>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text" style="color:#191919; font-size:14px;  text-align:left;">
                                                <multiline>
                                                <p>Regrads,</p>
                                                <p>'.$rname.'</p>
                                                </multiline>
                                            </td>
                                        </tr>';
                                        
                                        
                                        $username = $first.' '.$last;
                                      
                                        $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                
                                        $res = [ 'result' => 'success' , 'studid' => md5($newstdntid) ];
                                    }
                                    else{
                                        $res = [ 'result' => 'Activity not saved'  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Student not saved'  ];
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
                            else
                            {
                                $res = [ 'result' => 'number'  ];
                            }    
                        }
                        else
                        {
                            $res = [ 'result' => 'mlength'  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Emergency Contact Number should be numeric digit only'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'Emergency Contact Number should be minimum of 10 digits'  ];
                }
            } 
            else
            {
                $res = [ 'result' => 'Student With email already exist in selected session'  ];
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
    
    public function report($id)
    { 
        $school_table = TableRegistry::get('company');
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $retrieve_students = $student_table->find()->where(['id' => $id, 'status' => 1])->first();
        $studentid   =  $retrieve_students['id'];
        $student_exist = $student_table->find()->where(['id' => $id, 'status' => 1])->count();
        $sclid = $retrieve_students['school_id'];   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $session_table = TableRegistry::get('session');
        $retrieve_classes = $class_table->find()->where(['school_id' => $sclid, 'active' => 1])->toArray();
        
        
        $searchid = $this->request->data('searchform');
        
        $subject_table = TableRegistry::get('subjects');
        $school_table = TableRegistry::get('company');
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $session_table = TableRegistry::get('session');
        $feestruct_table = TableRegistry::get('fee_structure');
        $studentfee_table = TableRegistry::get('student_fee');
        $studentfee_table = TableRegistry::get('student_fee');
        $sclattendance_table = TableRegistry::get('attendance_school');
        $submitexm_table = TableRegistry::get('submit_exams');
        $examsass_table = TableRegistry::get('exams_assessments');
    
        $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
        $school_logo = '<img src="../../img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important;">';
        $working_days = $retrieve_school[0]['present_days'];
        
        
                    $lang = $this->Cookie->read('language');
                    if($lang != "")
                    {
                        $lang = $lang;
                    }
                    else
                    {
                        $lang = 2;
                    }
                    
                    //echo $lang;
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
                        if($langlbl['id'] == '49') { $contctlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '136') { $claslabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '238') { $sesslabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1955') { $feesrep = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1956') { $attndreport = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1958') { $workinglabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1959') { $presentlabel = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1960') { $absentlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1957') { $instalmntlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '345') { $amtlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '359') { $paidlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1967') { $duelabel = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1961') { $gradereportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1962') { $examreportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '388') { $titlelabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1963') { $assignreportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1964') { $maxmrklabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1965') { $obtmarklabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1966') { $gradelabel = $langlbl['title'] ; } 
                    } 
       
            
                $retrieve_student = $student_table->find()->where(['id' => $studentid, 'status' => 1])->first();
                $classid = $retrieve_student['class'];
                $sessionid = $retrieve_student['session_id'];
                $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                
                $admno = $retrieve_student['adm_no'];
                $studentname = $retrieve_student['f_name']." ".$retrieve_student['l_name']. " (".$retrieve_student['adm_no'].") ";
                $emergency_contact = $retrieve_student['emergency_number'];
                $image = '<img src="../../img/'. $retrieve_student['pic'].'" style="width:60px !important;">';
                $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                $session_year = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                
                
                /**************Grades************/
                
                $retrieve_submitexm = $submitexm_table->find()->where(['student_id' => $studentid,  'school_id' => $sclid])->toArray();

                $examreport = "";
                $assreport = "";
                foreach($retrieve_submitexm as $submitexm)
                {
                    
                     
                    $examid = $submitexm['exam_id'];
                    $retrieve_examass = $examsass_table->find()->where(['id' => $examid])->first();
                    $retrieve_subjectname = $subject_table->find()->where(['id' => $retrieve_examass['subject_id']])->first();
                    //print_r($retrieve_examass);
                    if($retrieve_examass['type'] == "Exams")
                    {
                        $title = $retrieve_subjectname['subject_name']. " ". $retrieve_examass['type']. " (". $retrieve_examass['exam_type'].")";
                        $max_marks = $retrieve_examass['max_marks'];
                        $get_marks = $submitexm['marks'];
                        $grade = $submitexm['grade'];
                        
                        $examreport .= '<tr>
                            <td style="width: 40%;">'.$title.'</td>
                            <td style="width: 20%; text-align:center;">'.$max_marks.'</td>
                            <td style="width: 20%; text-align:center;">'.$get_marks.'</td>
                            <td style="width: 20%; text-align:center;">'.$grade.'</td>
                        </tr>';
                    }
                    elseif($retrieve_examass['type'] == "Assessment")
                    {
                        $title = $retrieve_subjectname['subject_name']. " ". $retrieve_examass['type'];
                        $max_marks = $retrieve_examass['max_marks'];
                        $get_marks = $submitexm['marks'];
                        $grade = $submitexm['grade'];
                        
                        $assreport .= '<tr>
                            <td style="width: 40%;">'.$title.'</td>
                            <td style="width: 20%; text-align:center;">'.$max_marks.'</td>
                            <td style="width: 20%; text-align:center;">'.$get_marks.'</td>
                            <td style="width: 20%; text-align:center;">'.$grade.'</td>
                        </tr>';
                    }
                }
            
               // die;
                /**************Fees**************/
                
                $retrieve_totalfee = $feestruct_table->find()->where(['class_id' => $classid, 'start_year' => $sessionid, 'school_id' => $sclid,  'active' => 1])->first();
                $retrieve_feepaid = $studentfee_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'start_year' => $sessionid, 'school_id' => $sclid])->toArray();
                
                $fees = array();
                foreach($retrieve_feepaid as $stu_fee)
                {
                    $fees[] = $stu_fee['amount'];
                }
                $months_fee = ucfirst($retrieve_session['startmonth'])."-".ucfirst($retrieve_session['endmonth']);
                $total_fees = $retrieve_totalfee['amount'];
                $fee_paid = array_sum($fees);
                $due_fees = $total_fees-$fee_paid;
                
                /***********Attendance**************/
                $frmdate = date('m', strtotime($retrieve_session['startmonth']));
                $tdate = date('m', strtotime($retrieve_session['endmonth']));
                $month = date('m', strtotime($retrieve_session['endmonth']));
                $fromdate = $retrieve_session['startyear']."-".$frmdate."-01";
                if($month == "01" || $month == "03"  || $month == "05" || $month == "07" || $month == "08" || $month == "10"  || $month == "12")
                {
                    $todate = $retrieve_session['endyear']."-".$tdate."-31";
                }
                if($month == "04" || $month == "06"  || $month == "09" || $month == "11")
                {
                    $todate = $retrieve_session['endyear']."-".$tdate."-30";
                }
                if($month == "02")
                {
                    $todate = $retrieve_session['endyear']."-".$tdate."-28";
                }
                
                
                $present_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Present' ])->count();
                $absent_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Absent' ])->count();
                $leave_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Leave' ])->count();
                
                
                $feesreport = '
                    <tr>
                        <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$feesrep.'</th>
                    </tr>
                    <tr>
                        <td style="padding:0px !important;">
                            <table style="width: 100%; border:1px solid #ccc;">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$instalmntlbl.'</th>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$amtlabel.'</th>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$paidlabel.'</th>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$duelabel.'</th>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;" >'.$months_fee.'</td>
                                                <td style="text-align:center;">$'.$total_fees.'</td>
                                                <td style="text-align:center;">$'.$fee_paid.'</td>
                                                <td style="text-align:center;">$'.$due_fees.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p id="feesreport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
                    
                $attendancereport = '
                    <tr>
                        <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$attndreport.'</th>
                    </tr>
                    <tr>
                        <td style="padding:0px !important;">
                            <table style="width: 100%; border:1px solid #ccc;">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$workinglabel.'</th>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$presentlabel.'</th>
                                                <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$absentlabel.'</th>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;">'.$working_days.'</td>
                                                <td style="text-align:center;" >'.$present_attendance.' </td>
                                                <td style="text-align:center;">'.$absent_attendance.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p id="attendancereport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
                    
                $gradereport = '
                    <tr>
                        <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradereportlabel.'</th>
                    </tr>
                    <tr>
                        <td style="padding:0px !important;">
                            <table style="width: 100%; border:1px solid #ccc;">
                                <tr>
                                    <th style="width: 50%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$examreportlabel.'</th>
                                    <th style="width: 50%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$assignreportlabel.'</th>
                                </tr>
                                <tr>
                                    <td style="width: 50%;">
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <th style="width: 40%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$titlelabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$maxmrklabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$obtmarklabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradelabel.'</th>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 50%;">
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <th style="width: 40%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$titlelabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$maxmrklabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$obtmarklabel.'</th>
                                                <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradelabel.'</th>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%;">
                                        <table style="width: 100%; border:1px solid #ccc;">'
                                            .$examreport.
                                        '</table>
                                    </td>
                                    <td style="width: 50%;">
                                        <table style="width: 100%; border:1px solid #ccc;">'
                                            .$assreport.
                                        '</table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
                
                $header = '<style> td p { margin-bottom:5px; } </style><table style=" width: 100%;">
                        <tbody>
                            <tr>
                                <td  style="width: 100%;">
                                    <table style="width: 100%;  ">
                                    <tr>
                                        <td  style="width: 100%; float:left; ">
                                            <table style="width: 100%;  ">
                                                <tr>
                                                    <td>
                                                        <table style="width: 100%;">
                                                        <tr>
                                                        <td  style="width: 35%; text-align:left; padding: 5px;"><span> '.$school_logo.' </span></td>
                                                        <td  style="width: 50%; text-align:left; padding: 5px;">
                                                            <span style="width: 17%; float:left;"> '.$image.' </span>
                                                            <span style="width: 73%;"> 
                                                            <p style=" font-size: 17px; font-weight:bold; margin:10px 0px 0px 0px !important;">'.ucwords($studentname).'</p>
                                                            <p> <b> '.$contctlabel.': </b>'.$emergency_contact.' </p>
                                                            </span>
                                                        </td>
                                                        <td  style="width: 25%; text-align:left; padding: 5px;">
                                                            <p style="  margin:0px !important;"> <b> '.$claslabel.': </b>'.$classname.' </p>
                                                            <p> <b> '.$sesslabel.': </b>'.$session_year.' </p>
                                                        </td>
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
                            <tr>
                                <td style="width:100%">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width:50%">
                                                <table style="width: 100%;">'
                                                .$feesreport.
                                                '</table>
                                            </td>
                                            <td style="width:50%">
                                                <table style="width: 100%;">'
                                                .$attendancereport.
                                                '</table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            <tr>'
                            .$gradereport.
                        '</tbody>
                    </table>';
                    
                    //print_r($header); die;
            
                $viewpage = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc">'.$header.'</div>';
                $style = 'style="display:none;"';
                $searchicon = 'style="display:block;"';
                $dnloadreport = 'style="display:block;"';
                $closeicon = 'style="display:none;"';
                $error = "";
           
        $this->set("stuid", $id); 
        $this->set("error", $error); 
        $this->set("downloadreport", $dnloadreport); 
        $this->set("present", $present_attendance); 
        $this->set("absent", $absent_attendance); 
        $this->set("paid", $fee_paid); 
        $this->set("due", $due_fees); 
        $this->set("style", $style); 
        $this->set("searchicon", $searchicon); 
        $this->set("closeicon", $closeicon); 
        $this->set("viewpage", $viewpage); 
        $this->set("class_details", $retrieve_classes); 
        $this->viewBuilder()->setLayout('user');
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

  


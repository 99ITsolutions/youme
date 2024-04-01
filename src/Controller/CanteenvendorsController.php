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
class CanteenvendorsController  extends AppController
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
        $vendor_table = TableRegistry::get('canteen_vendor');
        //print_r($_COOKIE); die;
        $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
        $retrieve_vendor = $vendor_table->find()->toArray(); 
        $this->set("vendor_details", $retrieve_vendor); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function add()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $country_table = TableRegistry::get('countries');
        $retrieve_countries = $country_table->find()->select(['id' ,'name'])->toArray() ;
        $this->set("country_details", $retrieve_countries);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function schoollist($vendorid)
    {   
        $vas_table = TableRegistry::get('vendor_assign_school');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
		$school_table = TableRegistry::get('company');
		//$vendorid = $this->request->session()->read('vendor_id');
		
		$retrieve_vendrdtl = $vas_table->find()->where(['vendor_id' => $vendorid ])->first();
		$scl_ids = explode(",", $retrieve_vendrdtl['school_ids']);
	    $retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id IN' => $scl_ids ])->toArray();
	    
	    //print_r($retrieve_scldtl); die;
	    
		$this->set("sclinfo", $retrieve_scldtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function addvendor()
    {   
        if ($this->request->is('post') )
        {	
           // print_r($_FILES); die;
            $lang = $this->Cookie->read('language');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $vendor_table = TableRegistry::get('canteen_vendor');
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
                if($langlbl['id'] == '1616') { $wrkngdays = $langlbl['title'] ; } 
                if($langlbl['id'] == '1617') { $breakname = $langlbl['title'] ; } 
                if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                if($langlbl['id'] == '1611') { $dategreat = $langlbl['title'] ; } 
                if($langlbl['id'] == '2201') { $alemail = $langlbl['title'] ; } 
            } 
           
            $retrieve_vendor = $vendor_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_school = $school_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_student = $student_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                 
            if($retrieve_vendor == 0 && $retrieve_school == 0 && $retrieve_student == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
            {
                if($this->request->data['logo']['type'] == "image/jpeg" || $this->request->data['logo']['type'] == "image/jpg" || $this->request->data['logo']['type'] == "image/png" )
                {
                    $logo =  $this->request->data['logo']['name'];
                    $filename = $this->request->data['logo']['name'];
                    $uploadpath = 'canteen/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['logo'] = $filename; 
                    }
                }    
                $vendor = $vendor_table->newEntity();
                
             
				$vendor->f_name = trim($this->request->data('f_name'));
				$vendor->l_name = trim($this->request->data('l_name'));
                $vendor->vendor_company = $this->request->data('comp_name');
				$vendor->email = trim($this->request->data('email'));
                $vendor->contact_no = $this->request->data('phone');
                $vendor->button_color = $this->request->data('navbar');
                $vendor->nav_color = $this->request->data('buttoncolor');
                $vendor->status = $this->request->data('status');
                $vendor->deliver_starttime = $this->request->data('open_time');
                $vendor->deliver_endtime = $this->request->data('close_time');
                $vendor->slot_close = $this->request->data('booking_closed');
                $vendor->logo = $filename;
                if(!empty($this->request->data('city')))
                {
					$vendor->city = $this->request->data('city');
                }
				if(!empty($this->request->data('country')))
				{
					$vendor->country = $this->request->data('country');
				}
				if(!empty($this->request->data('state')))
				{
                    $vendor->state = $this->request->data('state');
                }
				if(!empty($this->request->data('add_1')))
				{
					$vendor->addr = $this->request->data('add_1');
				}
				if(!empty($this->request->data('zipcode')))
				{
					$vendor->zipcode = $this->request->data('zipcode');
				}
				$vendor->created_date = time();
               
                $phone = $this->request->data('phone');
                if (preg_match ("/^[0-9]*$/", $phone) )
                {
                    if($saved = $vendor_table->save($vendor) )
                    {   
                        $sclid = $saved->id;
                        $password = trim(substr($comp_name,0, 3).$sclid.uniqid());
                        $insert = $vendor_table->query()->update()->set(['password'=>$password])->where([ 'id' => $sclid  ])->execute();
                    
                        if($saved)
                	    {
                	        $name = $comp_name;
                            $subj = 'New Registration as a Canteen Vendor in You-Me Global Education';
                            $to =  $email;
                            $rname = "You-Me Global Education Team";
                            $from = "support@you-me-globaleducation.org";
                            $link = 'https://you-me-globaleducation.org/school/login';
                            $htmlContent = '
                            <tr>
                            <td class="text" style="color:#191919; font-size:16px;   text-align:left;">
                                <multiline>
                                    <p>Welcome to You-Me Global Education! We\'re here to transform your education/ digitally.</p>
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
                            $username = trim($this->request->data('comp_name'));
                          
                            //$sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subj,$htmlContent,$name,'formalmessage');
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
                    $res = [ 'result' => $insertdigit  ];
                }
            }
            else
            {
                $res = [ 'result' => $alemail ];
            }
            
        }
        return $this->json($res);
    }

    public function update($sid)
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $vendor_table = TableRegistry::get('canteen_vendor');
        //$module_table = TableRegistry::get('modules');
        $state_table = TableRegistry::get('states');
        $country_table = TableRegistry::get('countries');
        $retrieve_countries = $country_table->find()->select(['id' ,'name'])->toArray() ;
        $retrieve_vendor= $vendor_table->find()->where([ 'md5(id)' => $sid ])->first() ;
        
        $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_vendor['country']  ])->toArray() ;
        $this->set("country_details", $retrieve_countries);
        $this->set("vendor_details", $retrieve_vendor);
        //$this->set("module_details", $retrieve_module);
        $this->set("state_details", $retrieve_state);
        $this->viewBuilder()->setLayout('usersa');
    }

    public function editvendor()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
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
            
            foreach($retrieve_langlabel as $langlbl) 
            { 
                if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                if($langlbl['id'] == '1616') { $wrkngdays = $langlbl['title'] ; } 
                if($langlbl['id'] == '1617') { $breakname = $langlbl['title'] ; } 
                if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                
                if($langlbl['id'] == '1611') { $dategreat = $langlbl['title'] ; } 
                if($langlbl['id'] == '1629') { $sclemail = $langlbl['title'] ; } 
            } 
            
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $school_table = TableRegistry::get('company');
            $activ_table = TableRegistry::get('activity');
            $scltymtbl_table = TableRegistry::get('school_timetable');
            $vendor_table = TableRegistry::get('canteen_vendor');
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
                    $uploadpath = 'canteen/';
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


            $retrieve_school = $school_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email'))])->count() ;
            $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_teacher = $teacher_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_supersub = $supersubad_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
            $retrieve_vendor = $vendor_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')), 'id IS NOT'=> $this->request->data('vendorid') ])->count() ;
            
            $sclid = $this->request->data('vendorid');
            
			
			$f_name = trim($this->request->data('f_name'));
			$l_name = trim($this->request->data('l_name'));
            $vendor_company = $this->request->data('comp_name');
			$email = trim($this->request->data('email'));
            $contact_no = $this->request->data('phone');
            $button_color = $this->request->data('buttoncolor');
            $nav_color = $this->request->data('navbar');
            $status = $this->request->data('status');
            $deliver_starttime = $this->request->data('open_time');
            $deliver_endtime = $this->request->data('close_time');
            $slot_close = $this->request->data('booking_closed');
            $logo = $logo;
            if(!empty($this->request->data('city')))
            {
				$city = $this->request->data('city');
            }
			if(!empty($this->request->data('country')))
			{
				$country = $this->request->data('country');
			}
			if(!empty($this->request->data('state')))
			{
                $state = $this->request->data('state');
            }
			if(!empty($this->request->data('add_1')))
			{
				$addr = $this->request->data('add_1');
			}
			if(!empty($this->request->data('zipcode')))
			{
				$zipcode = $this->request->data('zipcode');
			}
			$password = $this->request->data('password');
            
             
            if(!empty($logo))
            {  
                if($retrieve_school == 0 && $retrieve_student == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                {
                    if (preg_match ("/^[0-9]*$/", $contact_no) )
                    {
					        $update = $vendor_table->query()->update()->set(['zipcode' => $zipcode, 'slot_close' => $slot_close, 'deliver_starttime' => $deliver_starttime, 'deliver_endtime' => $deliver_endtime, 'f_name' => $f_name, 'l_name' => $l_name, 'nav_color' => $nav_color, 'button_color' => $button_color, 'country' => $country, 'vendor_company' => $vendor_company, 'addr' => $addr,'state'=>$state, 'city'=>$city, 'logo'=>$logo,  'email' => $email, 'contact_no' => $contact_no, 'password' => $password, 'status' => $status  ])->where([ 'id' => $sclid  ])->execute();
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
   
    public function delete()
    {
        $sid = (int) $this->request->data('val') ; 
        $vendor_table = TableRegistry::get('canteen_vendor');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $vendor_table->find()->select(['id'])->where(['id'=> $sid ])->first();
		
        if($sclid)
        {   
            $del = $vendor_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
            if($del)
            { 
                $activity = $activ_table->newEntity();
                $activity->action =  "Vendor Deleted"  ;
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
    
    public function profile(){
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function edituserprofile(){
        if ($this->request->is('ajax') && $this->request->is('post') ){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $cvndr_table = TableRegistry::get('canteen_vendor');
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

            $f_name =  $this->request->data('fname')  ;
            $l_name =  $this->request->data('lname')  ;
            $vendor_company =  $this->request->data('compname')  ;
            $email =  trim($this->request->data('email'))  ;
            $phone = $this->request->data('phone') ;
            $slot_close = $this->request->data('booking_closed') ;
            $open_time = $this->request->data('open_time') ;
            $close_time = $this->request->data('close_time') ;
            $opassword = $this->request->data('opassword') ;
            $password = $this->request->data('password') ;
            $cpassword = $this->request->data('cpassword') ;
            $userid = $this->Cookie->read('cid');
           // $modified = strtotime('now');
			 
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
						

            $retrieve_cvndr = $cvndr_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email')), 'md5(id) !=' =>  $userid  ])->count() ;
            
            if($retrieve_cvndr == 0 )
            {
                if(!empty($picture))
                {
                    if($vendor_company != ""   || $email != ""  && $phone != ""  )
                    {
                        if($update_company = $cvndr_table->query()->update()->set(['slot_close' => $slot_close, 'deliver_starttime' => $open_time, 'deliver_endtime' => $close_time, 'f_name' => $f_name, 'l_name' => $l_name, 'vendor_company' => $vendor_company, 'email' => $email, 'contact_no' => $phone , 'logo' => $picture  ])->where(['md5(id)' =>  $userid ])->execute()){
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
                            if($update_task = $cvndr_table->query()->update()->set([  'password' => $password ])->where(['md5(id)' =>  $userid ])->execute()){
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
   
    public function status()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $uid = $this->request->data('val') ;
        $sts = $this->request->data('sts') ;
        
        $vendor_table = TableRegistry::get('canteen_vendor');
        $activ_table = TableRegistry::get('activity');

        $userid = $vendor_table->find()->select(['id'])->where(['id'=> $uid ])->first();
		$status = $sts == 1 ? 0 : 1;
		
	
        if($userid)
        {   
            $stats = $vendor_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
			
            if($stats)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Vendor status changed"  ;
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

    public function fooditems()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $fooditem_table = TableRegistry::get('food_item');
        $food_data = $fooditem_table->find()->toArray();
        $this->set("food_details", $food_data); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function addfood()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $fooditem_table = TableRegistry::get('food_item');
            $activ_table = TableRegistry::get('activity');
            
            $retrieve_food = $fooditem_table->find()->select(['id' ])->where(['food_name' => trim($this->request->data('food_name')) ])->count() ;

            if($retrieve_food == 0 )
            {
                if(!empty($this->request->data['food_img']))
                {   
                    $filename = '';
                    if($this->request->data['food_img']['type'] == "image/jpeg" || $this->request->data['food_img']['type'] == "image/jpg" || $this->request->data['food_img']['type'] == "image/png" )
                    {
                        $picture =  $this->request->data['food_img']['name'];
                        $filename = time().$this->request->data['food_img']['name'];
                        $uploadpath = 'c_food/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['food_img']['tmp_name'], $uploadfile))
                        {
                            $this->request->data['food_img'] = $filename; 
                        }
                    }  
                }
            
                $fooditem = $fooditem_table->newEntity();
                $fooditem->food_name = trim($this->request->data('food_name'));
                $fooditem->details = trim($this->request->data('food_detail'));
				$fooditem->food_img = $filename;
                $fooditem->created_date = time();
                                      
                if($saved = $fooditem_table->save($fooditem) )
                {   
                    $subid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Food Item Added"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('sid');
                    $activity->value = md5($subid); 
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
                $res = [ 'result' => 'This food name already exist'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
    
    public function deleteitem()
    {
        $sid = (int) $this->request->data('val') ; 
        $fooditem_table = TableRegistry::get('food_item');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $foodid = $fooditem_table->find()->select(['id'])->where(['id'=> $sid ])->first();
		
        if($foodid)
        {   
            $del = $fooditem_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
            if($del)
            { 
                $activity = $activ_table->newEntity();
                $activity->action =  "food item Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($sid)    ;
                $activity->origin = $this->Cookie->read('sid')   ;
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
    
    public function geteditfood()
    {
        $id = $this->request->data('id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $fooditem_table = TableRegistry::get('food_item');
        $food_data = $fooditem_table->find()->where(['id' => $id])->first();
        return $this->json($food_data);
    }
    
    public function editfood()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $fooditem_table = TableRegistry::get('food_item');
            $activ_table = TableRegistry::get('activity');
            $id = $this->request->data('eid');
            $retrieve_food = $fooditem_table->find()->select(['id' ])->where(['food_name' => trim($this->request->data('efood_name')), 'id IS NOT' => $id ])->count() ;

            if($retrieve_food == 0)
            {
                $filename = $this->request->data('efimg');
                if(!empty($this->request->data['efood_img']))
                {   
                    
                    if($this->request->data['efood_img']['type'] == "image/jpeg" || $this->request->data['efood_img']['type'] == "image/jpg" || $this->request->data['efood_img']['type'] == "image/png" )
                    {
                        $picture =  $this->request->data['efood_img']['name'];
                        $filename = $this->request->data['efood_img']['name'];
                        $uploadpath = 'c_food/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['efood_img']['tmp_name'], $uploadfile))
                        {
                            $this->request->data['efood_img'] = $filename; 
                        }
                    }  
                }
                $update = $fooditem_table->query()->update()->set(['food_name' => trim($this->request->data('efood_name')), 'details' => trim($this->request->data('efood_detail')), 'food_img' => $filename])->where(['id' => $id])->execute();
                if($update)
                {   
                    $subid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Food Item Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('sid');
                    $activity->value = md5($subid); 
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
                $res = [ 'result' => 'This food name already exist'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
    
    public function assignfood()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
        
        $scl_table = TableRegistry::get('company');
        $vd_table = TableRegistry::get('vendor_to_food');
        $va_table = TableRegistry::get('vendor_assign_school');
        
        $retrieve_vendor = $va_table->find()->select(['canteen_vendor.vendor_company', 'id', 'vendor_id', 'school_ids' ])->join([ 
            'canteen_vendor' => 
            [
                'table' => 'canteen_vendor',
                'type' => 'LEFT',
                'conditions' => 'canteen_vendor.id = vendor_assign_school.vendor_id'
            ]
            
        ])->toArray() ;
        foreach($retrieve_vendor as $vendor)
        {
            $sclids = explode(",", $vendor['school_ids']);
            //print_r($sclids); 
            $retrieve_school = $scl_table->find()->select(['id', 'comp_name' ])->where([ 'id IN' => $sclids ])->toArray() ;
            $scls = [];
            foreach($retrieve_school as $scl)
            {
                $scls[] = $scl['comp_name'];
            }
            $vendor->schools = implode(",", $scls);
            
            $retrieve_vfood = $vd_table->find()->select(['food_item.food_name' ])->join([ 
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = vendor_to_food.food_id'
                ]
            ])->where([ 'assign_id' => $vendor['id'] ])->toArray() ;
            $vdfood = [];
            foreach($retrieve_vfood as $vf)
            {
                $vdfood[] = $vf['food_item']['food_name'];
            }
            $vendor->vendorfood = implode(",", $vdfood);
        }
        //print_r($retrieve_vendor);
        //die;
        $this->set('vendor_dtl', $retrieve_vendor);
    }
    
    public function addassignfood()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
        
        $cv_table = TableRegistry::get('canteen_vendor');
        $scl_table = TableRegistry::get('company');
        $fooditem_table = TableRegistry::get('food_item');
        $getcv_data = $cv_table->find()->where(['status' => 1])->toArray();
        $getscl_data = $scl_table->find()->where(['status' => 1])->toArray();
        $food_data = $fooditem_table->find()->toArray();
        $this->set('cv_details', $getcv_data);
        $this->set('scl_details', $getscl_data);
        $this->set('food_details', $food_data);
    }
    
    public function addfoodassigned()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $vd_table = TableRegistry::get('vendor_to_food');
            $va_table = TableRegistry::get(' vendor_assign_school');
            $activ_table = TableRegistry::get('activity');
            
            //print_r($_POST); die;
            
            $retrieve_vendor = $va_table->find()->select(['id' ])->where(['vendor_id' => trim($this->request->data('vendor')) ])->count() ;

            if($retrieve_vendor == 0 )
            {
                $va = $va_table->newEntity();
                $va->vendor_id = $this->request->data('vendor');
				$va->school_ids = implode(",", $this->request->data('school'));
                $va->created_date = time();
                                      
                if($saved = $va_table->save($va) )
                {   
                    $vas = $saved->id;
                    $foodlist = $this->request->data['foodlist'];
                    
                    foreach($foodlist as $key => $fl):
                        $foodprice = $this->request->data['foodprice'][$key];
                        if($foodprice != "" && $fl != "")
                        {
                            $vd = $vd_table->newEntity();
                            $vd->assign_id = $vas;
                            $vd->vendor_id = $this->request->data('vendor');
            				$vd->food_id = $fl;
            				$vd->price = $foodprice;
                            $vd->created_date = time();
                            $savedfood = $vd_table->save($vd);
                        }
                    endforeach;
                    
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Food Assigned"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('sid');
                    $activity->value = md5($vas); 
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
                $res = [ 'result' => 'Vendor already assigned. To add/remove school please edit that vendor'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
    
    public function deleteassign()
    {
        $sid = (int) $this->request->data('val') ; 
        $vtf_table = TableRegistry::get('vendor_to_food');
        $vas_table = TableRegistry::get('vendor_assign_school');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $foodid = $vas_table->find()->select(['id'])->where(['id'=> $sid ])->first();
		
        if($foodid)
        {   
            $del = $vas_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
            if($del)
            { 
                $deltd = $vtf_table->query()->delete()->where([ 'assign_id' => $sid ])->execute(); 
                $activity = $activ_table->newEntity();
                $activity->action =  "food/school assigned Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($sid)    ;
                $activity->origin = $this->Cookie->read('sid')   ;
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
    
    public function editassignfood($id)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $vd_table = TableRegistry::get('vendor_to_food');
        $va_table = TableRegistry::get('vendor_assign_school');
        $retrieve_vendor = $va_table->find()->where(['id' => $id])->first();
        $retrieve_vendorfood = $vd_table->find()->where(['assign_id' => $id])->toArray();
        
        $cv_table = TableRegistry::get('canteen_vendor');
        $scl_table = TableRegistry::get('company');
        $fooditem_table = TableRegistry::get('food_item');
        $getcv_data = $cv_table->find()->where(['status' => 1])->toArray();
        $getscl_data = $scl_table->find()->where(['status' => 1])->toArray();
        $food_data = $fooditem_table->find()->toArray();
        $this->set('cv_details', $getcv_data);
        $this->set('scl_details', $getscl_data);
        $this->set('food_details', $food_data);
        
        $this->set('vendorfood_dtl', $retrieve_vendorfood);
        $this->set('vendor_dtl', $retrieve_vendor);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function editfoodassigned()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $vd_table = TableRegistry::get('vendor_to_food');
            $va_table = TableRegistry::get(' vendor_assign_school');
            $activ_table = TableRegistry::get('activity');
            
            //print_r($_POST); die;
            
            $retrieve_vendor = $va_table->find()->select(['id' ])->where(['vendor_id' => trim($this->request->data('vendor')), 'id IS NOT' => $this->request->data('id') ])->count() ;

            if($retrieve_vendor == 0 )
            {
                $id = $this->request->data('id');
                $vendor_id = $this->request->data('vendor');
				$school_ids = implode(",", $this->request->data('school'));
                                      
                if($va_table->query()->update()->set(['vendor_id' => $vendor_id, 'school_ids' => $school_ids ])->where([ 'id' => $id ])->execute())
                {   
                    $vas = $id;
                    $deltd = $vd_table->query()->delete()->where([ 'assign_id' => $id ])->execute(); 
                    $foodlist = $this->request->data['foodlist'];
                    
                    foreach($foodlist as $key => $fl):
                        $foodprice = $this->request->data['foodprice'][$key];
                        if($foodprice != "" && $fl != "")
                        {
                            $vd = $vd_table->newEntity();
                            $vd->assign_id = $id;
                            $vd->vendor_id = $vendor_id;
            				$vd->food_id = $fl;
            				$vd->price = $foodprice;
                            $vd->created_date = time();
                            $savedfood = $vd_table->save($vd);
                        }
                    endforeach;
                    
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Food Assigned Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('sid');
                    $activity->value = md5($vas); 
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
                $res = [ 'result' => 'Already assigned food/school to vendor.'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
    
    public function assignfoodscl()
    {
        $fss_table = TableRegistry::get('food_serve_scl');
        $fi_table = TableRegistry::get('food_item');
        $getcv_data = $fss_table->find()->select(['school_id','vendor_id', 'id', 'food_ids', 'class_section', 'timings', 'weekday', 'company.comp_name', 'canteen_vendor.f_name', 'canteen_vendor.l_name', 'canteen_vendor.vendor_company'])->join([
            'canteen_vendor' => 
            [
                'table' => 'canteen_vendor',
                'type' => 'LEFT',
                'conditions' => 'canteen_vendor.id = food_serve_scl.vendor_id'
            ],
            'company' => 
            [
                'table' => 'company',
                'type' => 'LEFT',
                'conditions' => 'company.id = food_serve_scl.school_id'
            ]
        ])->toArray();
        foreach($getcv_data as $cvdata)
        {
            $foods = [];
            $foodids = explode(",", $cvdata['food_ids']);
            foreach($foodids as $fids)
            {
                $getfood = $fi_table->find()->where(['id' => $fids])->first();
                $foods[] = $getfood['food_name'];
            }
            $cvdata->foodnames = implode(",", $foods);
        }
        $this->set('food_details', $getcv_data);
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function addservefoodscl()
    {
        $scl_table = TableRegistry::get('company');
        $getscl_data = $scl_table->find()->where(['status' => 1])->toArray();
        
        $this->set('scl_details', $getscl_data);
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function getvendors()
    {
        $cv_table = TableRegistry::get('vendor_assign_school');
        $vendor_table = TableRegistry::get('canteen_vendor');
        $ids = $this->request->data('sclid');
        $multi = $this->request->data('multi');
        $getcv_data = $cv_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $ids .'\',vendor_assign_school.school_ids)')))->toArray();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        //print_r($getcv_data);
        $data = '<option value="">Choose value</option>';
        if($multi == 'multi')
        {
            /*foreach($getcv_data as $cv_data)
            {
                $get_vendor = $vendor_table->find()->where(['id' => $cv_data['vendor_id'] ])->first();
                $vndrs[] = $get_vendor['id'];
            }
            $vndrss = implode(",", $vndrs);*/
            $data .= '<option value="all">All</option>';
        }
        if(!empty($getcv_data))
        {
            foreach($getcv_data as $cv_data)
            {
                $get_vendor = $vendor_table->find()->where(['id' => $cv_data['vendor_id'] ])->first();
                $data .= '<option value="'.$get_vendor['id'].'">'.$get_vendor['l_name'].' '.$get_vendor['f_name'].' ('.$get_vendor['vendor_company'].')</option>';
            }
        }
        return $this->json($data);
    }
    
    public function getvendorfood()
    {
        $cv_table = TableRegistry::get('vendor_assign_school');
        $vf_table = TableRegistry::get('vendor_to_food');
        $get_fi = TableRegistry::get('food_item');
        $ids = $this->request->data('vndrid');
        $getcv_data = $cv_table->find()->where(['vendor_assign_school.vendor_id' => $ids])->first();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        //print_r($getcv_data);
        $data = '<option value="">Choose value</option>';
        if(!empty($getcv_data))
        {
            $get_foodids = $vf_table->find()->where(['assign_id' => $getcv_data['id'] ])->toArray();
            foreach($get_foodids as $food)
            {
                $get_food = $get_fi->find()->where(['id' => $food['food_id'] ])->first();
                $data .= '<option value="'.$get_food['id'].'">'.$get_food['food_name'].' ($'.$food['price'].')</option>';
            }
            
        }
        return $this->json($data);
    }
    
    public function getbreaktimings()
    {
        $sclid = $this->request->data('sclid');
        $sctn = $this->request->data('sctn');
        
        $stt_table = TableRegistry::get('school_timetable');
        $getstt_data = $stt_table->find()->where(['school_id' => $sclid, 'added_for' => $sctn, 'period_name LIKE' => '%RÉCRÉATION%'])->orwhere(['school_id' => $sclid, 'added_for' => $sctn, 'period_name LIKE' => '%PAUSE%'])->toArray();
        
        $data = '';
        if(!empty($getstt_data))
        {
            $data .= '<option value="">Choose Timings</option>';
            foreach($getstt_data as $stt)
            {
                $data .= '<option value="'.$stt['start_time'].'-'.$stt['end_time'].'">'.$stt['start_time'].'-'.$stt['end_time'].'</option>';
            }
            
        }
        return $this->json($data);
    }
    
    public function addservefood()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $fss_table = TableRegistry::get('food_serve_scl');
            $va_table = TableRegistry::get('vendor_assign_school');
            $activ_table = TableRegistry::get('activity');
            $cv_table = TableRegistry::get('canteen_vendor');
            $scl_table = TableRegistry::get('company');
            
            $schoolids = $this->request->data['school'];
            
            $vendors = $this->request->data['vendor'];
            $section = $this->request->data['section'];
            $timings = $this->request->data['break'];
            //$ctimings = $this->request->data['close_time'];
            $closedbooking = $this->request->data['closedbooking'];
            //print_r($_POST); die;
            $foodassigned = '';
            foreach($schoolids as $key => $sclid)
            {
                $f = $key+1;
                $foodlist = 'foodlist'.$f;
                $fooditem = $this->request->data($foodlist);
                
                $weekday = 'weekday'.$f;
                $weekdayss = $this->request->data[$weekday];
                
                $retrieve_scl = $scl_table->find()->select(['comp_name'])->where(['id' => $sclid ])->first() ;
                $sclname = $retrieve_scl['comp_name'];
                
                $retrieve_cv = $cv_table->find()->select(['vendor_company'])->where(['id' => $vendors[$key] ])->first() ;
                $vndrname = $retrieve_cv['vendor_company'];
                $weekdys = [];
                foreach($weekdayss as $weekdays):
                $retrieve_fss = $fss_table->find()->select(['id'])->where(['vendor_id' => $vendors[$key], 'timings' => $timings[$key], 'school_id' => $sclid, 'weekday' => $weekdays, 'class_section' => $section[$key] ])->count() ;
                
                if($retrieve_fss == 0 )
                {
                    $fss = $fss_table->newEntity();
                    $fss->weekday = $weekdays;
                    $fss->vendor_id = $vendors[$key];
                    $fss->class_section = $section[$key];
                    $fss->timings = $timings[$key];
                    $fss->order_booking_closed = $closedbooking[$key];
    				$fss->school_id = $sclid;
    				$fss->food_ids = implode(",", $fooditem);
                    $fss->created_at = time();
                                          
                    if($saved = $fss_table->save($fss) )
                    {   
                        $vas = $saved->id;
                        
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Food Assigned acc to weekdays"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('sid');
                        $activity->value = md5($vas); 
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
                    $weekdys[] = $weekdays;
                }   
                endforeach;
                
                if(!empty($weekdys))
                {
                    $weekdyss = implode(", ",$weekdys);
                    $foodassigned .= 'Already Assigned food of this <b>'.$vndrname.' on '.$weekdyss.'</b> in this school <b>'.$sclname.'</b>';
                    $foodassigned .= "<br>";
                    
                    $msg = $foodassigned.' Rest All are added.';
                }
                else
                {
                    $msg = "success";   
                }
            }
            
            if($foodassigned != '')
            {
                $res = [ 'result' => $msg  ];
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
    
    public function deleteserve()
    {
        $sid = (int) $this->request->data('val') ; 
        $fss_table = TableRegistry::get('food_serve_scl');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $foodid = $fss_table->find()->select(['id'])->where(['id'=> $sid ])->first();
        //print_r($foodid); die;
        if($foodid)
        {   
            $del = $fss_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
            if($del)
            { 
                $activity = $activ_table->newEntity();
                $activity->action =  "food served Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($sid)    ;
                $activity->origin = $this->Cookie->read('sid')   ;
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
    
    public function editservefoodscl($id)
    {
        $scl_table = TableRegistry::get('company');
        $cv_table = TableRegistry::get('canteen_vendor');
        $fss_table = TableRegistry::get('food_serve_scl');
        $vas_table = TableRegistry::get('vendor_assign_school');
        $vf_table = TableRegistry::get('vendor_to_food');
        $get_fi = TableRegistry::get('food_item');
        
        $getserve_data = $fss_table->find()->where(['id' => $id])->first();
        
        $getscl_data = $scl_table->find()->where(['status' => 1])->toArray();
        $this->set('scl_details', $getscl_data);
        $this->set('serve_details', $getserve_data);
        
        $time = explode("-",$getserve_data['timings']);
        $sclids = $getserve_data['school_id'];
        $sctn = $getserve_data['class_section'];
        
        $stt_table = TableRegistry::get('school_timetable');
        $getstt_data = $stt_table->find()->where(['school_id' => $sclids, 'added_for' => $sctn, 'period_name LIKE' => '%RÉCRÉATION%'])->orwhere(['school_id' => $sclids, 'added_for' => $sctn, 'period_name LIKE' => '%PAUSE%'])->toArray();
        $this->set('stt_details', $getstt_data);
        
        $data = '';
        if(!empty($getstt_data))
        {
            $data .= '<option value="">Choose Timings</option>';
            foreach($getstt_data as $stt)
            {
                $data .= '<option value="'.$stt['start_time'].'-'.$stt['end_time'].'">'.$stt['start_time'].'-'.$stt['end_time'].'</option>';
            }
            
        }
        
        $getstt_data = $stt_table->find()->where(['school_id' => $sclid, 'added_for' => $sctn, 'period_name LIKE' => '%RÉCRÉATION%'])->orwhere(['school_id' => $sclid, 'added_for' => $sctn, 'period_name LIKE' => '%PAUSE%'])->toArray();
        
        $data = '';
        if(!empty($getstt_data))
        {
            $data .= '<option value="">Choose Timings</option>';
            foreach($getstt_data as $stt)
            {
                $data .= '<option value="'.$stt['start_time'].'-'.$stt['end_time'].'">'.$stt['start_time'].'-'.$stt['end_time'].'</option>';
            }
            
        }
        
        $getcv_data = $vas_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $sclids .'\',vendor_assign_school.school_ids)')))->toArray();
        
        if(!empty($getcv_data))
        {
            foreach($getcv_data as $cv_data)
            {
                $get_vendor = $cv_table->find()->where(['id' => $cv_data['vendor_id'] ])->toArray();
            }
        }
        $this->set('vendr_details', $get_vendor);
        
        $ids = $getserve_data['vendor_id'];
        $getcv_data = $vas_table->find()->where(['vendor_assign_school.vendor_id' => $ids])->first();
        
        if(!empty($getcv_data))
        {
            $get_foodids = $vf_table->find()->where(['assign_id' => $getcv_data['id'] ])->toArray();
            foreach($get_foodids as $food)
            {
                $get_food = $get_fi->find()->where(['id' => $food['food_id'] ])->first();
                $food->foodname = $get_food['food_name'];
                $food->foodid = $get_food['id'];
                $food->foodprice = $food['price'];
            }
            
        }
        //$this->set('vendordata', $vendordata);
        $this->set('fooddata', $get_foodids);
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function editservefood()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $fss_table = TableRegistry::get('food_serve_scl');
            $va_table = TableRegistry::get(' vendor_assign_school');
            $activ_table = TableRegistry::get('activity');
            
            $schoolids = $this->request->data('school');
            $weekdays = $this->request->data('weekday');
            $vendors = $this->request->data('vendor');
            $section = $this->request->data('section');
            $closedbooking = $this->request->data('closedbooking');
            $timings = $this->request->data('break');
            //print_r($_POST); die;
            /*foreach($schoolids as $key => $sclid)
            {*/
                $fooditem = $this->request->data('foodlist');
                
                $retrieve_fss = $fss_table->find()->select(['id'])->where(['id IS NOT' => $this->request->data('id'), 'class_section' => $section, 'vendor_id' => $vendors, 'school_id' => $schoolids, 'weekday' => $weekdays ])->count() ;
                $id = $this->request->data('id');
                if($retrieve_fss == 0 )
                {
    				$food_ids = implode(",", $fooditem);
                       
                    if($fss_table->query()->update()->set(['vendor_id' => $vendors, 'timings' => $timings, 'school_id' => $schoolids, 'food_ids' => $food_ids, 'class_section' => $section, 'order_booking_closed' => $closedbooking])->where([ 'id' => $id ])->execute())    
                    {   
                        $vas = $this->request->data('id');
                        
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Food updated acc to weekdays"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('sid');
                        $activity->value = md5($vas); 
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
                    $res = [ 'result' => 'Already assigned food/school to vendor.'  ];
                }    
            //}
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
}

  




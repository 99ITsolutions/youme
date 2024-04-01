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
class ParentdashboardController  extends AppController
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
        $student_table = TableRegistry::get('student');	
        $parent_table = TableRegistry::get('parent_logindetails');	
        $parentid = $this->request->session()->read('parent_id');
        $session_id = $this->request->session()->read('session_id');
        if(!empty($parentid))
        {        
                $parent_details = $parent_table->find()->select(['student.id', 'student.adm_no', 'student.password' , 'student.session_id' ,'class.c_name', 'class.c_section', 'class.school_sections', 'student.class' ,'student.f_name' , 'student.s_f_name' ,  'student.s_m_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' ,  'company.city', 'student.school_id', 'company.primary_color', 'company.button_color' , 'company.comp_name', 'parent_logindetails.parent_email' , 'parent_logindetails.id', 'parent_logindetails.parent_password' ])->join([
                   'student' => 
                    [
                        'table' => 'student',
                        'type' => 'LEFT',
                        'conditions' => 'parent_logindetails.id = student.parent_id '
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ],
                    'class' => 
                    [
    						'table' => 'class',
    						'type' => 'LEFT',
    						'conditions' => 'class.id = student.class'
    				]
                ])->where(['parent_logindetails.id' => $parentid, 'student.session_id' => $session_id ])->toArray(); 
                
            $this->set("parent_details", $parent_details); 
            $this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function index2()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $pid = $this->Cookie->read('pid'); 
        if(!empty($pid))
        {
            $school_table = TableRegistry::get('company');
			$student_table = TableRegistry::get('student');
			
			$retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section'])->join(['class' => 
					[
					'table' => 'class',
					'type' => 'LEFT',
					'conditions' => 'class.id = student.class'
				]
			])->where(['md5(student.id)' => $pid ])->toArray() ;
			
		
			$this->set("students_details", $retrieve_students); 
			$this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
            
    public function canteenpin()
    {
        $student_table = TableRegistry::get('student');
        $sid =$this->request->session()->read('parent_id');
        $sessionid = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($sid))
        {
    		$retrieve_stud = $student_table->find()->where(['session_id' => $sessionid, 'parent_id' => $sid])->toArray() ;
            $this->set("studlist", $retrieve_stud);
            $this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
    
    public function getscl()
    {
        $studid = $this->request->data('studid');
        $student_table = TableRegistry::get('student');
        $comp_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_stud = $student_table->find()->where(['id' => $studid])->first() ;
        
        $retrieve_scl = $comp_table->find()->select(['comp_name'])->where(['id' => $retrieve_stud['school_id']])->first() ;
        $data['sclname'] = $retrieve_scl['comp_name'];
        $data['studid'] = $studid;
        $data['cpin'] = $retrieve_stud['canteen_pincode'];
        return $this->json($data);
    }
    
    public function canpingent()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {   
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
            $stdntid =  $this->request->data('selstud')  ;
            $canteenpin = $this->request->data('canteenpin') ;
         
            if( $student_table->query()->update()->set([  'canteen_pincode' => $canteenpin])->where([ 'id' => $stdntid  ])->execute())
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
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }         
            
    public function studentprofile()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $pid = $this->Cookie->read('pid'); 
        $student_table = TableRegistry::get('student');
        $sid =$this->request->session()->read('student_id');
		$session_id = $this->Cookie->read('sessionid');
        if(!empty($pid))
        {
            $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section'])->join(['class' => 
				[
					'table' => 'class',
					'type' => 'LEFT',
					'conditions' => 'class.id = student.class'
				]
			])->where(['md5(student.id)' => $pid ])->toArray() ;
			
            
            $this->set("studentprofile_details", $retrieve_student_table); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }

	public function changesession(){
        if ($this->request->is('ajax') && $this->request->is('post') ){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $activ_table = TableRegistry::get('activity');    
            $session_id = $this->Cookie->read('sessionid');

            if(!empty($this->request->data('currntsesssion')))
            {   
                $newsessionid = $this->request->data('currntsesssion');    
                $this->Cookie->write('sessionid',  $newsessionid);
	
                if($newsessionid)
                {
                      
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Cookie Updated"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                        $activity->value = $newsessionid   ;
                        $activity->origin = md5($this->Cookie->read('id')) ;
                        $activity->created = strtotime('now');
                        if($saved = $activ_table->save($activity) ){
                            $res = [ 'result' => 'success'  ];
                        }
                        else{
                            $res = [ 'result' => 'activity'  ];
                        }

                }
                else{
                    $res = [ 'result' => 'error'  ];
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
    
    public function getsidebar()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $pid = $this->Cookie->read('pid'); 
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $studsidebar_table = TableRegistry::get('student_sidebarmenu');
            if(!empty($pid)) 
            {
                $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $pid ])->first();
                
                $sid = $stuid_retrieve['id'];
        		$getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
    
                if($getcount == 0)
                {
                    $sidebarstu = $studsidebar_table->newEntity();
                    
                    $sidebarstu->status = 1;
                    $sidebarstu->student_id = $sid;
                    $sidebarstu->created_date = time();
                    if($saved = $studsidebar_table->save($sidebarstu) )
                    {     
                        $stusidebarid = $saved->id;
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'error occured.'  ];
                    }
                }
                else
                {
                    $update = $studsidebar_table->query()->update()->set([ 'status'=> '1' , 'created_date' => time() ])->where([ 'student_id' => $sid  ])->execute();
                    if($update)
                    {     
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'error occured.'  ];
                    }
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
    
    public function sidebarmenu()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $pid = $this->Cookie->read('pid'); 
        $student_table = TableRegistry::get('student');
        $studsidebar_table = TableRegistry::get('student_sidebarmenu');
        if(!empty($pid))
        {
            $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $pid ])->first();
            
            $sid = $stuid_retrieve['id'];
    		$getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
    
            if($getcount == 0)
            {
                $getside = $studsidebar_table->find()->where(['student_id'=> $sid ])->first();
                if($getside['status'] = 1)
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
               $res = [ 'result' => 'failed'  ];
            }
            return $this->json($res);
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function profile(){
        $parent_table = TableRegistry::get('parent_logindetails');	
        $parentid = $this->request->session()->read('parent_id');
        $session_id = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());     
        $parent_details = $parent_table->find()->where(['id' => $parentid ])->toArray(); 
                
        $this->set("parent_dtl", $parent_details); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function editparntprofile()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $student_table = TableRegistry::get('student');
            $parent_table = TableRegistry::get('parent_logindetails');
            $activ_table = TableRegistry::get('activity');
            $comp_table = TableRegistry::get('company');
            $teacher_table = TableRegistry::get('employee');
            $sclsubadmin_table = TableRegistry::get('school_subadmin');
            $supersubad_table = TableRegistry::get('users');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());   
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
                        $picture = $filename; 
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
                $parent_id =  $this->request->data('id') ;
                $opassword = $this->request->data('opassword') ;
                $password = $this->request->data('password') ;
                $cpassword = $this->request->data('cpassword') ;
                
                $modified = strtotime('now');
                
                $retrieve_parent = $parent_table->find()->select(['id' ])->where(['parent_email' => $this->request->data('email'), 'id IS NOT' => $parent_id])->count() ;
                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $this->request->data('email')])->count() ;
                $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $this->request->data('email')])->count() ;
                
                
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
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1628') { $passntup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                    } 
                
                if($retrieve_student == 0 && $retrieve_parent == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_schoolsub == 0 && $retrieve_supersub == 0)
                {
                    if(!empty($picture))
                    {
                        if($f_name != ""   || $email != ""  && $mobile_no != ""  )
                        {
                            if($update_parent = $parent_table->query()->update()->set(['f_name' => $f_name ,  'l_name' => $l_name , 'parent_email' => $email, 'mobile' => $mobile_no , 'image' => $picture  ])->where(['id' =>  $parent_id ])->execute())
                            {
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Parent Data Updated"  ;
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
                                $res = [ 'result' => $profilentup  ];
    
                            }
    
                            if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword )
                            {
                                if($update_student = $parent_table->query()->update()->set(['parent_password' => $password ])->where(['id' =>  $parent_id ])->execute())
                                {
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Parent Password Updated"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($parent_id)   ;
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
                                    $res = [ 'result' => $pass  ];
                                }
                                elseif($cpassword == ""){
                                    $res = [ 'result' => $cpass  ];
                                }
                                elseif($password != $cpassword){
                                    $res = [ 'result' => $passnt ];
                                }
                            }
    
                        }
                        else{
                            $res = [ 'result' => 'empty'  ];
    
                        }
                    }
                    else{ 
                        $res = [ 'result' => $imgjpg  ];
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
            

}

  


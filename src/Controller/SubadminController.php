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
class SubadminController  extends AppController
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
                
                $users_table = TableRegistry::get('users');
                $roles_table = TableRegistry::get('roles');
                $menus_table = TableRegistry::get('superadmin_menus');
                $retrieve_menus = $menus_table->find()->where(['status' => 1])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                $retrieve_user = $users_table->find()->select([ 'r.name', 'users.id' , 'users.fname' , 'users.role' , 'users.lname' , 'users.email', 'users.picture' , 'users.phone' , 'users.status' ])
                    ->join(['r' => [
                        'table' => 'roles',
                        'type' => 'LEFT',
                        'conditions' =>  'r.id =  users.role' 
                    ]
                ])->toArray();
                
                $scl_table = TableRegistry::get('company'); 
                $retrieve_scl = $scl_table->find()->select([ 'id', 'comp_name'])->where(['status' => 1])->toArray();

                $this->set("user_details", $retrieve_user);   
                $this->set("school_details", $retrieve_scl);   
                $this->set("menus_details", $retrieve_menus);   
                $this->viewBuilder()->setLayout('usersa');

            }
            
        public function profile(){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('usersa');
            }

        public function editprofile()
        {
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    
                    $teacher_table = TableRegistry::get('employee');
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $users_table = TableRegistry::get('users');
                    $activ_table = TableRegistry::get('activity');
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
                        if($langlbl['id'] == '1622') { $useremail = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                    } 
                    
                    if(!empty($this->request->data['picture']))
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
                    }else
                    {
                        $picture =  $this->request->data('apicture');
                    }


                    $fname =  $this->request->data('fname')  ;
                    $lname =  $this->request->data('lname')  ;
                    $email =  trim($this->request->data('email'))  ;
                    $phone = $this->request->data('phone') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->request->data('id') ;
                    $modified = strtotime('now'); 

                    $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => $email, 'id !=' =>  $userid  ])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['email' => $email ])->count() ;
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $email])->count() ;
                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $email])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $email ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $email ])->count() ;
                    
                    
                    if($retrieve_users == 0 && $retrieve_teacher == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_parent == 0)
                    {

                        if(!empty($picture))
                        {

                            if($fname != ""  || $lname != ""  || $email != ""  && $phone != ""  )
                            {
                                if($update_task = $users_table->query()->update()->set([  'fname' => $fname , 'lname' => $lname ,'email' => $email, 'phone' => $phone , 'picture' => $picture  ])->where(['id' =>  $userid ])->execute())
                                {
                                    if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword )
                                    {
                                        if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
                                            if($password == ""){
                                                $res = [ 'result' => $pass  ];
                                            }
                                            elseif($cpassword == ""){
                                                $res = [ 'result' => $cpass  ];
                                            }
                                            elseif($password != $cpassword){
                                                $res = [ 'result' => $passnt  ];
                                            }
                                        }
                                        if($update_task = $users_table->query()->update()->set([  'password' => $password , 'modified' => $modified ])->where(['id' =>  $userid ])->execute())
                                        {
                                    
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Admin Data & PAssword Updated"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($userid)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity) )
                                            {
                                                $res = [ 'result' => 'success'  ];
                                            }
                                        }
                                    }
                                    elseif($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
                                        if($password == ""){
                                            $res = [ 'result' => $pass  ];
                                        }
                                        elseif($cpassword == ""){
                                            $res = [ 'result' => $cpass ];
                                        }
                                        elseif($password != $cpassword){
                                            $res = [ 'result' => $passnt  ];
                                        }
                                    }
                                    else
                                    {
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Admin Data Updated"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($userid)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity) )
                                            {
                                                $res = [ 'result' => 'success'  ];
                                            }
                                    }
                                    
                                }
                                else
                                {
                                    $res = [ 'result' => $activityntadded  ];
                                }
                            } 
                            else
                            {
                                $res = [ 'result' => $profilentup ];
    
                            }
                        }
                        else
                        { 
                            $res = [ 'result' => $imgjpg  ];
                        }  
                    }
                    else
                    {
                        $res = [ 'result' => $useremail  ];
                    }      
            }
            else
            {
                $res = [ 'result' => 'Invalid Operation'  ];
            }


           return $this->json($res);

        }

        public function add()
        {
            $role_table = TableRegistry::get('roles');
            $scl_table = TableRegistry::get('company'); 
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_role = $role_table->find()->select(['id' , 'name'   ])->where([ 'status' => '1'  ])->toArray() ;
            
            $retrieve_scl = $scl_table->find()->select([ 'id', 'comp_name'])->where(['status' => 1])->toArray();
            
            $menus_table = TableRegistry::get('superadmin_menus');
            $retrieve_menus = $menus_table->find()->where(['status' => 1])->toArray();
               
   
            $this->set("menus_details", $retrieve_menus);   
            $this->set("school_details", $retrieve_scl);   
            $this->set("role_details", $retrieve_role); 
            $this->viewBuilder()->setLayout('usersa');
        }
        
        public function adduser()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $teacher_table = TableRegistry::get('employee');
                $comp_table = TableRegistry::get('company');
                $student_table = TableRegistry::get('student');
                $sclsubadmin_table = TableRegistry::get('school_subadmin');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                $users_table = TableRegistry::get('users');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email'))])->count() ;
                $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email'))])->count() ;
                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => trim($this->request->data('email'))])->count() ;
                $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                
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
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $useremail = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1625') { $userntsv = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1626') { $sclpri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1627') { $menupri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                        
                    } 
                    
                if($retrieve_users == 0 && $retrieve_teacher == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_parent == 0)
                {
                    $role_table = TableRegistry::get('roles');
                    //$retrieve_privilages = $role_table->find()->select(['privilage'])->where(['id' => $this->request->data('role')  ])->first() ;
                    

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

                    if(!empty($picture))
                    {
                       
                        $user = $users_table->newEntity();

                        $user->email =  trim($this->request->data('email'))  ;
                        $user->password = $this->request->data('password') ;
                        $user->phone =  $this->request->data('phone')  ;
                        $user->role =  $this->request->data('role') ;
                        
                        $user->picture =  $picture  ;
                        $user->created = strtotime('now');

                        $fname =  $this->request->data('fname')  ;
                        $lname =  $this->request->data('lname')  ;
                        
                        if (preg_match ("/^[0-9]*$/", $this->request->data('phone')) )
                        {
                            if(in_array("1", $this->request->data['menus_privilages']))
                            {
                                $sclpri = "1";
                            }
                            else
                            {
                                $sclpri = "0";
                            }
                            if((($sclpri == 1) && (!empty($this->request->data['subadmin_privilages']))) || (($sclpri == 0) && (!empty($this->request->data['subadmin_privilages']))) || (($sclpri == 0) && (empty($this->request->data['subadmin_privilages']))))
                            {
                                if(!empty($this->request->data['menus_privilages']))
                                {
                                    $privilages = implode(",", $this->request->data['subadmin_privilages']);
                                    $mprivilages = implode(",", $this->request->data['menus_privilages']);
                                    $user->privilages =  $privilages  ;
                                    $user->menus_privilages =  $mprivilages  ;
                                    if($saved = $users_table->save($user) )
                                    {   
                                        $usrid = $saved->id;
            
                                        if( $users_table->query()->update()->set([ 'fname' => $fname  ,'lname' => $lname ])->where([ 'id' => $usrid  ])->execute())
                                        {
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "User Created"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($saved->id)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity) )
                                            {
                                        
                                                $name = $fname.' '.$lname;
                                                $subject = 'New Registration as a subadmin in You-Me Global Education';
                                                $to =  trim($this->request->data('email'));
                                                $password =  trim($this->request->data('password'));
                                                $from = "support@you-me-globaleducation.org";
                                                $rname = "You-Me Global Education Team";
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
                                                        <p>Username: '.$to.' </p>
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
                                                $username = $fname.' '.$lname;
                                              
                                                $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                                                $res = [ 'result' => 'success'  ];
                                            }
                                            
                                            
                                            else{
                                                $res = [ 'result' => $activityntadded ];
                                            }
                                        }    
                                        else
                                        {
                                            $res = [ 'result' => $activityntadded  ];
                                        }    
                                    }
                                    else
                                    {
                                        $res = [ 'result' => $userntsv  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => $menupri  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $sclpri ];
                            }
                            
                        }
                        else
                        {
                            $res = [ 'result' => $insertdigit  ];
                        }
                    }
                    else
                    { 
                        $res = [ 'result' => $imgjpg  ];
                    }    
                } 
                else
                {
                    $res = [ 'result' => $useremail  ];
                }

            }
            else{
                $res = [ 'result' => 'invalid operation'  ];

            }


            return $this->json($res);

        } 

        public function edit($uid){

            $role_table = TableRegistry::get('roles');
            $users_table = TableRegistry::get('users');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_user = $users_table->find()->where(['md5(id)' => $uid   ])->toArray();

            $retrieve_role = $role_table->find()->select([ 'id' , 'name' ])->where([ 'status' => '1'  ])->toArray();

            $scl_table = TableRegistry::get('company');
            $retrieve_scl = $scl_table->find()->select([ 'id', 'comp_name'])->where(['status' => 1])->toArray();
            $this->set("school_details", $retrieve_scl);   
            
            $menus_table = TableRegistry::get('superadmin_menus');
            $retrieve_menus = $menus_table->find()->where(['status' => 1])->toArray();
            $this->set("menus_details", $retrieve_menus); 
            
            $this->set("role_details", $retrieve_role);
            $this->set("users_details", $retrieve_user); 
            $this->viewBuilder()->setLayout('usersa');
        }            

        public function edituser()
        {
            if ($this->request->is('ajax') && $this->request->is('post') ){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $teacher_table = TableRegistry::get('employee');
                $comp_table = TableRegistry::get('company');
                $student_table = TableRegistry::get('student');
                $sclsubadmin_table = TableRegistry::get('school_subadmin');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                $users_table = TableRegistry::get('users');
                $activ_table = TableRegistry::get('activity');
                
                //$retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => $this->request->data('email')])->count() ;
                $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['email' => $this->request->data('email') ])->count() ;
                $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $this->request->data('email')])->count() ;
                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => $this->request->data('email')])->count() ;
                $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $this->request->data('email') ])->count() ;
                    
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
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $useremail = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1625') { $userntsv = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1626') { $sclpri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1627') { $menupri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                        
                    } 
                $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => $this->request->data('email')  , 'id <> ' => $this->request->data('id') ])->count() ;
                
                if($retrieve_users == 0 && $retrieve_teacher == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_parent == 0)
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
                    }else
                    {
                        $picture =  $this->request->data('apicture');
                    }

                    $uid = $this->request->data('id');
                    $fname =  $this->request->data('fname')  ;
                    $lname =  $this->request->data('lname')  ;
                    $email =  $this->request->data('email')  ;
                    $password = $this->request->data('password') ;
                    $phone =  $this->request->data('phone')  ;
                    $role =  $this->request->data('role') ;
                    $modified = strtotime('now');

                    $role_table = TableRegistry::get('roles');
                    
                    

                    if(!empty($picture))
                    {
                        if (preg_match ("/^[0-9]*$/", $this->request->data('phone')) )
                        {
                            if(!empty($this->request->data['subadmin_privilages']))
                            {
                                if(!empty($this->request->data['menus_privilages']))
                                {
                                    $privilages = implode(",", $this->request->data['subadmin_privilages']);
                                    $mprivilages = implode(",", $this->request->data['menus_privilages']);
                                    if( $users_table->query()->update()->set([ 'fname' => $fname , 'menus_privilages' =>$mprivilages, 'lname' => $lname , 'email' => $email,  'phone' => $phone, 'password' => $password ,'picture' => $picture ,'role' => $role , 'privilages' => $privilages ,'modified' => $modified])->where([ 'id' => $uid  ])->execute())
                                    {   
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "User update"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                        $activity->origin = $this->Cookie->read('id');
                                        $activity->value = md5($uid); 
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
                                    $res = [ 'result' => $menupri  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $sclpri  ];
                            }
                    
                        }
                        else
                        {
                            $res = [ 'result' => $insertdigit  ];
                        }
                    }
                    else 
                    { 
                        $res = [ 'result' => $imgjpg  ];
                    }    
                } 
                else
                {
                    $res = [ 'result' => $useremail  ];
                }
            }
            else{
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);

        }  

        public function view($uid){

                $role_table = TableRegistry::get('roles');
                $users_table = TableRegistry::get('users');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_user = $users_table->find()->select([ 'role', 'id' , 'fname' , 'lname' , 'email', 'picture', 'password' , 'phone' ])->where(['md5(id)' => $uid   ])->toArray();

                $retrieve_role = $role_table->find()->select([ 'id' , 'name' ])->where([ 'status' => '1'  ])->toArray();

                $this->set("role_details", $retrieve_role);
                $this->set("users_details", $retrieve_user); 
                $this->viewBuilder()->setLayout('usersa');
            }            
            
        public function delete()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $users_table = TableRegistry::get('users');
                $activ_table = TableRegistry::get('activity');

                $userid = $users_table->find()->select(['id'])->where(['id'=> $uid ])->first();
				
                if($userid)
                {   
					$del = $users_table->query()->delete()->where([ 'id' => $uid ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "User Deleted"  ;
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
        
        public function status()
        {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ;
                $users_table = TableRegistry::get('users');
                $activ_table = TableRegistry::get('activity');

                $userid = $users_table->find()->select(['id'])->where(['id'=> $uid ])->first();
				$status = $sts == 1 ? 0 : 1;
                if($userid)
                {   
                    $stats = $users_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
					
                    if($stats)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "User status changed"  ;
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

        public function edituserprofile(){
            if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $company_table = TableRegistry::get('company');
                    $activ_table = TableRegistry::get('activity');
                    
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

                    $comp_name =  $this->request->data('fname')  ;
                    $email =  $this->request->data('email')  ;
                    $phone = $this->request->data('phone') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->Cookie->read('id');
                    $modified = strtotime('now');
				if(!empty($userid))	
                {
                    $teacher_table = TableRegistry::get('employee');
                    
                    $student_table = TableRegistry::get('student');
                    $sclsubadmin_table = TableRegistry::get('school_subadmin');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                    $users_table = TableRegistry::get('users');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['email' => $this->request->data('email') ])->count() ;
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => $this->request->data('email')])->count() ;
                    $retrieve_school = $users_table->find()->select(['id'])->where(['email' => $this->request->data('email')])->count() ;
                    $retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => $this->request->data('email') ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => $this->request->data('email') ])->count() ;
                    
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
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $useremail = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1625') { $userntsv = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1626') { $sclpri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1627') { $menupri = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1618') { $insertdigit = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1628') { $passntup = $langlbl['title'] ; } 
                        
                    } 
                    
                    $retrieve_users = $company_table->find()->select(['id'  ])->where(['email' => $this->request->data('email'), 'md5(id) !=' =>  $userid  ])->count() ;
                    
                    if($retrieve_users == 0 && $retrieve_teacher == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_schoolsub == 0 && $retrieve_parent == 0)
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
                                    $res = [ 'result' => $profilentup  ];
        
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
                                        $res = [ 'result' => $pass  ];
                                    }
                                    elseif($cpassword == ""){
                                        $res = [ 'result' => $cpass ];
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
                        $res = [ 'result' => $useremail  ];
                    }        
                }else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            else{
                $res = [ 'result' => 'Invalid Operation'  ];
            }

           return $this->json($res);

        }

}

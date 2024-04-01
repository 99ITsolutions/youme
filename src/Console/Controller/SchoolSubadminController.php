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
class SchoolSubadminController  extends AppController
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
                $compid = $this->request->session()->read('company_id');
                $users_table = TableRegistry::get('school_subadmin');
                $sclmenus_table = TableRegistry::get('school_menus');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))
                {
                    $retrieve_user = $users_table->find()->where(['school_id' => $compid])->toArray();
                    $lang = $this->Cookie->read('language');
                    if($lang != "")
                    {
                        $lang = $lang;
                    }
                    else
                    {
                        $lang = 2;
                    }
                    foreach($retrieve_user as $key =>$usercoll)
            		{
            		    $privileges = explode(",", $usercoll['privilages']);
            		    $mnus = [];
            		    foreach($privileges as $pri)
            		    {
            		        //echo $pri; 
            		        $sclmnus = $sclmenus_table->find()->where(['id' => $pri ])->first();
            		        if($lang == 2)
            		        { 
            		            $mnus[] = $sclmnus['french_name'];
            		        }
            		        else
            		        {
            		            $mnus[] = $sclmnus['name'];
            		        }
            		        
            		    }
            		    
            		    $usercoll->pri_name = implode(",", $mnus);
            		}
            		//print_r($retrieve_user); die;
                    $this->set("user_details", $retrieve_user);    
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            
            public function delete()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $uid = $this->request->data('val') ;
                $users_table = TableRegistry::get('school_subadmin');
                $activ_table = TableRegistry::get('activity');
    
                $userid = $users_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			
                if($userid)
                {   
    				$del = $users_table->query()->delete()->where([ 'id' => $uid ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Subadmin Deleted"  ;
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
                $users_table = TableRegistry::get('school_subadmin');
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
            
            public function profile(){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }

            public function editprofile(){
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $users_table = TableRegistry::get('school_subadmin');
                    $activ_table = TableRegistry::get('activity');
                    $teacher_table = TableRegistry::get('employee');
                    $comp_table = TableRegistry::get('company');
                    $student_table = TableRegistry::get('student');
                    $parentlogin_table = TableRegistry::get('parent_logindetails');
                   
                    $supersubad_table = TableRegistry::get('users');
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
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $userem = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1618') { $numonly = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1755') { $oldincor = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1756') { $profnt = $langlbl['title'] ; } 
                        
                    } 
                    
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


                    $fname =  $this->request->data('fname')  ;
                    $lname =  $this->request->data('lname')  ;
                    $email =  trim($this->request->data('email'))  ;
                    $contactyoume = $this->request->data('contactyoume') ;
                    $phone =  implode(",", $this->request->data['phone'])  ;
                    
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
                    
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    //$userid = $this->Cookie->read('id');
                    $userid = $this->request->data('id') ;
                    $modified = strtotime('now'); 
                    $jobtitle = $this->request->data('jobtitle');

                    $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email')), 'id !=' =>  $userid  , 'status' => '1' ])->count() ;
                    $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email'))])->count() ;
                    $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => trim($this->request->data('email'))])->count() ;
                    $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    //$retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                    $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                    
                    if($retrieve_users == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_supersub == 0  && $retrieve_parent == 0)
                    {
                        $retrieve_schoolsub = $users_table->find()->where(['id' =>  $userid])->first() ;
                        if(!empty($picture))
                        {

                            if($fname != ""  && $lname != ""  && $email != ""  && $phone != ""  )
                            {
                                if (!in_array("0", $numsonly) )
                                {
                                    if (preg_match ("/^[0-9]*$/", $contactyoume) )
                                    {
                                    if($update_task = $users_table->query()->update()->set(['contact_youme' => $contactyoume, 'fname' => $fname , 'jobtitle' => $jobtitle, 'lname' => $lname ,'email' => $email, 'phone' => $phone , 'picture' => $picture  ])->where(['id' =>  $userid ])->execute())
                                    {
                                        if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword )
                                        {
                                            if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
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
                                            if($opassword == $retrieve_schoolsub['password'])
                                            {
                                                $update = $update_task = $users_table->query()->update()->set([  'password' => $password , 'modified' => $modified ])->where(['id' =>  $userid ])->execute();
                                                if($update)
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
                                            else
                                            {
                                                $res = [ 'result' => $oldincor ];
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
                                        $res = [ 'result' => $activityntadded ];
                                    }
                                    }
                                    else
                                    {
                                        $res = ['result' => 'Please insert only numbers in You-Me phone number.'];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => $numonly ];
                                }
                            } 
                            else
                            {
                                $res = [ 'result' =>  $profnt ];
    
                            }
                        }
                        else
                        { 
                            $res = [ 'result' => $imgjpg  ];
                        }  
                    }
                    else
                    {
                        $res = [ 'result' => $userem ];
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sclmnus_table = TableRegistry::get('school_menus'); 
                $lang = $this->Cookie->read('language');
                if($lang != "")
                {
                    $lang = $lang;
                }
                else
                {
                    $lang = 2;
                }
                $retrieve_sclmnu = $sclmnus_table->find()->where(['status' => 1])->toArray();
                $this->set("schoolmnu_details", $retrieve_sclmnu); 
                $this->set("languagesel", $lang); 
                $this->viewBuilder()->setLayout('user');
            }
        
        public function addsubadmin()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $users_table = TableRegistry::get('school_subadmin');
                $activ_table = TableRegistry::get('activity');
                $comp_table = TableRegistry::get('company');
                $activ_table = TableRegistry::get('activity');
                $teacher_table = TableRegistry::get('employee');
                $student_table = TableRegistry::get('student');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
                $supersubad_table = TableRegistry::get('users');
                
                $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email'))  , 'status' => '1' ])->count() ;
                $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => trim($this->request->data('email'))])->count() ;
                $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                
                if($retrieve_users == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                {
                    $role_table = TableRegistry::get('roles');
                    

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
                        
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $userem = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1618') { $numonly = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1755') { $oldincor = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1756') { $profnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1757') { $sclprivi = $langlbl['title'] ; } 
                        
                        
                    } 
                    
                    $scl_id = $this->request->session()->read('company_id');
                    if(!empty($scl_id))
                    {
                        $retrieve_school = $comp_table->find()->where(['id'=> $scl_id ])->first() ;
                        $schoolname = $retrieve_school['comp_name'];
                        $link = "http://you-me-globaleducation.org/school/login";
                        if(!empty($picture))
                        {
                            $pass = $this->request->data('password') ;
                            /*echo $em = trim($this->request->data('email')) ;
                            die;*/
                            $cpass = $this->request->data('cpassword') ;
                            if($pass == $cpass)
                            {
                                $user = $users_table->newEntity();
        
                                $user->email =  trim($this->request->data('email'))  ;
                                $user->password = $this->request->data('password') ;
                                $user->phone =  implode(",", $this->request->data['phone'])  ;
                                $user->role =  "Subadmin" ; 
                                $user->jobtitle =  $this->request->data('jobtitle')  ;
                                $user->picture =  $picture  ;
                                $user->contact_youme =  $this->request->data('contactyoume')  ;
                                $user->school_id =  $scl_id  ;
                                $user->created = strtotime('now');
        
                                $fname =  $this->request->data('fname')  ;
                                $lname =  $this->request->data('lname')  ;
                                
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
                            if(!in_array("0", $numsonly))
                            {
                                if ($contctyoume == 1 )
                                {
                                    if(!empty( $this->request->data['subadmin_privilages']))
                                    {
                                        $privilages = implode(",", $this->request->data['subadmin_privilages']);
                                        $user->privilages =  $privilages  ; 
                                        $subprivilages = implode(",", $this->request->data['subroles_privilages']);
                                        $user->sub_privileges =  $subprivilages  ; 
                                        
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
                                                $subject = 'New Registration as a School Subadmin in You-Me Global Education';
                                                $to =  trim($this->request->data('email'));
                                                $password =  $this->request->data('password');
                                                $rname = "You-Me Global Education Team";
                                                $from = "support@you-me-globaleducation.org";
                                                
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
                                                    $res = [ 'result' => $activityntadded  ];
                                                }
                                            }    
                                            else
                                            {
                                                $res = [ 'result' => $activityntadded  ];
                                            }    
                                        }
                                        else
                                        {
                                            $res = [ 'result' => $profilentup  ];
                                        }
                                    }
                                    else
                                    {
                                         $res = [ 'result' =>  $sclprivi  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' =>  "Kindly insert only numbers in You-Me Contact number."  ];
                                }
                            }
                            else
                            {
                                $res = ['result' => $numonly];
                            }
                            }
                            else
                            {
                                 $res = [ 'result' => $passnt  ];
                            }
                        }
                        else
                        { 
                            $res = [ 'result' =>  $imgjpg  ];
                        }
                    }
                    else
                    {
                         return $this->redirect('/login/') ;
                    }
                } 
                else
                {
                    $res = [ 'result' => $userem  ];
                }

            }
            else{
                $res = [ 'result' => 'invalid operation'  ];

            }


            return $this->json($res);

        } 

        public function edit($uid){

            $role_table = TableRegistry::get('roles');
            $users_table = TableRegistry::get('school_subadmin');
            $retrieve_user = $users_table->find()->where(['md5(id)' => $uid   ])->toArray();
            $sclmnus_table = TableRegistry::get('school_menus'); 
            $retrieve_sclmnu = $sclmnus_table->find()->where(['status' => 1])->toArray();
            $this->set("schoolmnu_details", $retrieve_sclmnu);
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $submnurole_table = TableRegistry::get('school_menus_subroles'); 
            $retrieve_submnurole = $submnurole_table->find()->toArray();
            $this->set("submnu_details", $retrieve_submnurole);
            
            $lang = $this->Cookie->read('language');
            if($lang != "")
            {
                $lang = $lang;
            }
            else
            {
                $lang = 2;
            }
            $this->set("users_details", $retrieve_user); 
            $this->set("languagesel", $lang); 
            $this->viewBuilder()->setLayout('user');
        }            

        public function editsubadmin()
        {
            if ($this->request->is('ajax') && $this->request->is('post') ){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $users_table = TableRegistry::get('school_subadmin');
                $activ_table = TableRegistry::get('activity');
                $teacher_table = TableRegistry::get('employee');
                $comp_table = TableRegistry::get('company');
                $student_table = TableRegistry::get('student');
                $parentlogin_table = TableRegistry::get('parent_logindetails');
               
                $supersubad_table = TableRegistry::get('users');
                
                $retrieve_users = $users_table->find()->select(['id'  ])->where(['email' => trim($this->request->data('email'))  , 'id <> ' => $this->request->data('id') ])->count() ;
                $retrieve_student = $student_table->find()->select(['id' ])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_school = $comp_table->find()->select(['id'])->where(['email' => trim($this->request->data('email'))])->count() ;
                $retrieve_teacher = $teacher_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                //$retrieve_schoolsub = $sclsubadmin_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_supersub = $supersubad_table->find()->select(['id'])->where(['email' => trim($this->request->data('email')) ])->count() ;
                $retrieve_parent = $parentlogin_table->find()->select(['id'])->where(['parent_email' => trim($this->request->data('email')) ])->count() ;
                
                if($retrieve_users == 0 && $retrieve_student == 0 && $retrieve_school == 0 && $retrieve_teacher == 0 && $retrieve_supersub == 0 && $retrieve_parent == 0)
                {

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
                    }else
                    {
                        $picture =  $this->request->data('apicture');
                    }

                    $uid = $this->request->data('id');
                    $fname =  $this->request->data('fname')  ;
                    $lname =  $this->request->data('lname')  ;
                    $email =  trim($this->request->data('email'))  ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    
                    $modified = strtotime('now');
                    $jobtitle =  $this->request->data('jobtitle')  ;
                    $contactyoume = $this->request->data('contactyoume') ;
                    $phone =  implode(",", $this->request->data['phone'])  ;
                    
                    $role_table = TableRegistry::get('roles');
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
                    
                    $privilages = implode(",", $this->request->data['subadmin_privilages']);
                    $subprivilages = implode(",", $this->request->data['subroles_privilages']);
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
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $userem = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1618') { $numonly = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1755') { $oldincor = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1756') { $profnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1757') { $sclprivi = $langlbl['title'] ; } 
                    } 
                    
                    //print_r($numsonly); die;
                    if(!empty($picture))
                    {
                        if($password == $cpassword)
                        {
                        if(!in_array("0", $numsonly))
                        {
                            if (preg_match ("/^[0-9]*$/", $contactyoume) )
                            {
                                if( $users_table->query()->update()->set(['sub_privileges' => $subprivilages, 'contact_youme' => $contactyoume,'jobtitle' => $jobtitle, 'fname' => $fname , 'lname' => $lname , 'email' => $email,  'phone' => $phone, 'password' => $password ,'picture' => $picture , 'privilages' => $privilages ,'modified' => $modified])->where([ 'id' => $uid  ])->execute())
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
                                $res = [ 'result' => "Please insert number only in You-Me Phone Number." ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => $numonly ];
                        }
                        }
                        else
                        {
                            $res = [ 'result' => $passnt  ];
                        }
                    }
                    else
                    { 
                        $res = [ 'result' => $imgjpg  ];
                    }    
                } 
                else
                {
                    $res = [ 'result' => $userem ];
                }
            }
            else{
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);

        }  
           

        public function view($uid){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $role_table = TableRegistry::get('roles');
            $users_table = TableRegistry::get('users');
    
            $retrieve_user = $users_table->find()->select([ 'role', 'id' , 'fname' , 'lname' , 'email', 'picture', 'password' , 'phone' ])->where(['md5(id)' => $uid   ])->toArray();
    
            $retrieve_role = $role_table->find()->select([ 'id' , 'name' ])->where([ 'status' => '1'  ])->toArray();
    
            $this->set("role_details", $retrieve_role);
            $this->set("users_details", $retrieve_user); 
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
                        if($langlbl['id'] == '1619') { $pass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1620') { $cpass = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1621') { $passnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1615') { $imgjpg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1622') { $userem = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1623') { $profilentup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1624') { $activityntadded = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1628') { $ntpassup = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1618') { $numonly = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1755') { $oldincor = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1756') { $profnt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1757') { $sclprivi = $langlbl['title'] ; } 
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
                                $res = [ 'result' => $activityntadded ];
            
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
                                    $res = [ 'result' => $activityntadded   ];
                
                                        }
                                    } 
                                    else{
                                        $res = [ 'result' => $ntpassup  ];
            
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
                        $res = [ 'result' => $userem ];
                    }        
            }
            else{
                $res = [ 'result' => 'Invalid Operation'  ];
            }

           return $this->json($res);

        }

        public function getsubroles()
        {
            $sclmnurole_table = TableRegistry::get('school_menus_subroles');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $privid = $this->request->data('privid');
            $retrieve_role = $sclmnurole_table->find()->where([ 'sclmenu_id' => $privid  ])->toArray();
            $roles = '';
            foreach($retrieve_role as $role)
            {
                $roles .= '<div class="col-md-12 ml-1"><input type="checkbox" name="subroles_privilages[]" value="'.$role['id'].'" > '. $role['name'].'</div>';
            }
                
            //print_r($roles);                
            return $this->json($roles);
        }
}

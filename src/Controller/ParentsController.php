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
class ParentsController  extends AppController
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
         
    public function profile(){
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('user');
    }
            
    public function editstdntprofile()
    {
        if($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $student_table = TableRegistry::get('student');
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
            $school_id =  $this->request->data('school_id') ;
            $stdnt_id =  $this->request->data('id') ;
            $opassword = $this->request->data('opassword') ;
            $password = $this->request->data('password') ;
            $cpassword = $this->request->data('cpassword') ;
            $userid = $this->Cookie->read('pid');
            $modified = strtotime('now');
                    
            $retrieve_student = $student_table->find()->select(['id'])->where(['email' => $this->request->data('email'), 'id IS NOT' =>  $stdnt_id , 'school_id'=> $school_id ])->count() ;
                    
            if($retrieve_student == 0 )
            {
                if(!empty($picture))
                {
                    if($f_name != ""   || $email != ""  && $mobile_no != ""  )
                    {
                        if($update_student = $student_table->query()->update()->set([  'f_name' => $f_name ,  'l_name' => $l_name , 'email' => $email, 'parent_email' => $email, 'mobile_for_sms' => $mobile_no , 'pic' => $picture  ])->where(['md5(id)' =>  $userid ])->execute())
                        {
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
        
                        if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword )
                        {
                            $retrieve_pass = $student_table->find()->select(['parent_password' ])->where(['md5(id)' =>  $userid])->first();
                          
                            if($opassword == $retrieve_pass['parent_password'])
                            {
                                if($update_student = $student_table->query()->update()->set([  'password' => $password ])->where(['md5(id)' =>  $userid ])->execute())
                                {
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Parent Password Updated"  ;
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
                            else
                            {
                                 $res = [ 'result' => 'Parent current password doesn\'t match with your old password. ' ];
                            }
                        }
                        else if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) )
                        {
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
	    
            
	   
   
}

  


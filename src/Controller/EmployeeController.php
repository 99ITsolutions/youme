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
class EmployeeController  extends AppController
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
            
    public function editempprofile()
    {
        if ($this->request->is('ajax') && $this->request->is('post') ){
            $emp_table = TableRegistry::get('employee');
            $activ_table = TableRegistry::get('activity');
            $session_id = $this->Cookie->read('sessionid');	
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($this->request->data['picture']['name']))
            {   
                if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                {    
                    $picture =  time()."_".$this->request->data['picture']['name'];
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
            $quali =  $this->request->data('qualifcation')  ;
            $email =  $this->request->data('email')  ;
            $mobile_no = implode(",", $this->request->data['phone']);
            $school_id =  $this->request->data('school_id') ;
            $tchr_id =  $this->request->data('id') ;
            $opassword = $this->request->data('opassword') ;
            $password = $this->request->data('password') ;
            $cpassword = $this->request->data('cpassword') ;
            $userid = $this->Cookie->read('tid');
            $modified = strtotime('now');
            
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
            
            $retrieve_emp = $emp_table->find()->select(['id' ])->where(['email' => $this->request->data('email'), 'id IS NOT' =>  $tchr_id , 'school_id'=> $school_id ])->count() ;
            
            if($retrieve_emp == 0 )
            {
                if(!in_array("0", $numsonly)){
                if(!empty($picture))
                {
                    if($f_name != "" || $l_name != "" || $email != ""  && $mobile_no != "" && $quali != ""  )
                    {
                        if($update_emp = $emp_table->query()->update()->set(['f_name' => $f_name , 'quali' => $quali ,  'l_name' => $l_name , 'email' => $email, 'mobile_no' => $mobile_no , 'pict' => $picture  ])->where(['id' =>  $tchr_id ])->execute()){
                           
                           
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Employee Data Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
        
                            $activity->value = md5($tchr_id)   ;
                            $activity->origin = $this->Cookie->read('tid')   ;
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

                        if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword ){
                            if($update_emp = $emp_table->query()->update()->set([  'password' => $password ])->where(['id' =>  $tchr_id ])->execute()){
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Employee Password Updated"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($tchr_id)   ;
                                $activity->origin = $this->Cookie->read('tid')   ;
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
                else
                {
                    $res = [ 'result' => 'Only Numeric digit allowed in Mobile number.'  ];
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

  


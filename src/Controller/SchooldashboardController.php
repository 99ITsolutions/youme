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
class SchooldashboardController  extends AppController
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
                //echo "d"; die;
                //print_r($_SESSION); 
                $school_table = TableRegistry::get('company');
				$student_table = TableRegistry::get('student');
				$employee_table = TableRegistry::get('employee');
				$users_table = TableRegistry::get('users');
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $session_table = TableRegistry::get('session');
                $compid = $this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_session =  $session_table->find()->where(['id' => $session_id ])->first();
                $sessname = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                //die;
                if(!empty($compid))
                {
    				$retrieve_users = $users_table->find()->select(['id' ])->where(['status' => '1', 'role !=' => '2' ])->count();
    				$retrieve_school = $school_table->find()->select(['id' ])->where(['status' => '1' ])->count();
    				$retrieve_students = $student_table->find()->select(['id'])->where(['school_id '=> $compid , 'session_id' => $session_id, 'status'=> 1 ])->count();
    				$retrieve_employees = $employee_table->find()->select(['id' ])->where(['school_id '=> $compid ,  'status' => 1 ])->count();
    				$retrieve_subjects = $subjects_table->find()->select(['id' ])->where([ 'school_id '=> $compid , 'status' => 1 ])->count();
    				$retrieve_class = $class_table->find()->select(['id' ])->where([ 'school_id '=> $compid , 'active' => 1 ])->count();
    
    				$stud_dtls = $retrieve_students. " (".$sessname.")";
    				$this->set("students_details", $stud_dtls); 
    				$this->set("employees_details", $retrieve_employees); 
    				$this->set("users_details", $retrieve_users); 
    				$this->set("school_details", $retrieve_school); 
    		
    				
    				$this->set("subjects_details", $retrieve_subjects); 
    				$this->set("class_details", $retrieve_class);
    				$this->set('session_id', $this->Cookie->read('sessionid'));
    
    				$this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }
            
            
			public function changesession(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $activ_table = TableRegistry::get('activity');    
                    $session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
            

}

  


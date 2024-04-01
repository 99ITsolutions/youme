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
class StudentprofileController  extends AppController
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
                $stid = $this->Cookie->read('stid'); 
                $student_table = TableRegistry::get('student');
                $sid =$this->request->session()->read('student_id');
				$session_id = $this->Cookie->read('sessionid');
                if(!empty($stid))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section'])->join(['class' => 
    							[
    							'table' => 'class',
    							'type' => 'LEFT',
    							'conditions' => 'class.id = student.class'
    						]
    					])->where(['md5(student.id)' => $stid ])->toArray() ;
    					
    				
                    
                    $this->set("student_details", $retrieve_student_table); 
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
            

}

  


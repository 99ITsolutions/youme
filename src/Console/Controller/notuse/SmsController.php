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

class SmsController  extends AppController

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

                $sms_template_table = TableRegistry::get('sms_template');

                $student_table = TableRegistry::get('student');

                $class_table = TableRegistry::get('class');

                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid') ;


                $retrieve_sms_template_table = $sms_template_table->find()->select(['id','temp_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;



                $retrieve_student_table = $student_table->find()->select(['id','s_name' ])->where(['school_id' => $compid , 'session_id'=> $session_id ])->toArray() ;



                $retrieve_class_table = $class_table->find()->select(['id','c_name','c_section' ])->where(['school_id' => $compid , 'session_id'=>$session_id ])->toArray() ;

                

                $this->set("sms_template_details", $retrieve_sms_template_table);

                $this->set("student_table_details", $retrieve_student_table);

                $this->set("class_table_details", $retrieve_class_table); 

                $this->viewBuilder()->setLayout('user');

            }



            public function getstudentsid(){



                $this->viewBuilder()->layout(false); 

                          

                if($this->request->is('ajax') && $this->request->is('post') ){

                

                $student_table = TableRegistry::get('student');

                $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid') ;


                $studentid = $student_table->find()->select(['sid' => 'GROUP_CONCAT(id)'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray(); 



                $sid = explode(',', $studentid[0]['sid']);



                return $this->json($sid);



                }  

            }



            public function smssend()

            {



                if ($this->request->is('ajax') && $this->request->is('post') )

                {



                    $sms_template_table = TableRegistry::get('sms_template');

                    $student_table = TableRegistry::get('student');

                    $activ_table = TableRegistry::get('activity');

                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->request->session()->read('session_id');





                    $retrieve_template = $sms_template_table->find()->select(['temp_body' ])->where(['id' => $this->request->data('template') , 'school_id'=> $compid ])->toArray() ;

                    

                    $template_body = $retrieve_template[0]['temp_body'];



                    $numbers = array();



                    if(!empty($this->request->data('singlestudent'))){

                        $retrieve_students_detail = $student_table->find()->select(['mobile_for_sms' ])->where(['id' => $this->request->data('singlestudent') , 'school_id'=> $compid ])->toArray() ;

                       

                        array_push($numbers,$retrieve_students_detail[0]['mobile_for_sms']);

                    }



                    elseif(!empty($this->request->data('multistudent'))){



                        $retrieve_students_detail = $student_table->find()->select(['mob_for_sms' => 'GROUP_CONCAT(mobile_for_sms)' ])->where(['id IN' => $this->request->data('multistudent') , 'school_id'=> $compid ])->toArray() ;



                        array_push($numbers,$retrieve_students_detail[0]['mob_for_sms']);

                    }



                    elseif(!empty($this->request->data('allstudent'))){



                        $retrieve_students_detail = $student_table->find()->select(['mob_for_sms' => 'GROUP_CONCAT(mobile_for_sms)' ])->where(['id IN' => $this->request->data('allstudent') , 'school_id'=> $compid ])->toArray() ;



                        array_push($numbers,$retrieve_students_detail[0]['mob_for_sms']);

                    }



                    elseif(!empty($this->request->data('singleclass'))){



                        $retrieve_students_detail = $student_table->find()->select(['mob_for_sms' => 'GROUP_CONCAT(mobile_for_sms)'])->where(['class' => $this->request->data('singleclass') , 'session_id'=> $session_id , 'school_id'=> $compid ])->toArray() ;



                        array_push($numbers,$retrieve_students_detail[0]['mob_for_sms']);

                    }



                    elseif(!empty($this->request->data('multiclass'))){



                        $retrieve_students_detail = $student_table->find()->select(['mob_for_sms' => 'GROUP_CONCAT(mobile_for_sms)' ])->where(['class IN' => $this->request->data('multiclass'), 'session_id' => $session_id , 'school_id'=> $compid ])->toArray() ;



                        array_push($numbers,$retrieve_students_detail[0]['mob_for_sms']);

                    }



                    else

                    {

                        $res = [ 'result' => 'empty'  ];

                    } 



                    

                        if(!empty($numbers))

                        {    

                            

                            $apiKey = urlencode('aozo1ZdXbvA-mkQqjlMAf4aVL0EV9r8ZpZXAw0mfoE');

                                                        

                            $sender = urlencode('TXTLCL');

                            $message = rawurlencode($template_body);

                         

                            $number = implode(',', $numbers);

                         

                            $data = array('apikey' => $apiKey, 'numbers' => $number, "sender" => $sender, "message" => $message);

                         

                            $ch = curl_init('https://api.textlocal.in/send/');

                            curl_setopt($ch, CURLOPT_POST, true);

                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            $response = curl_exec($ch);

                            curl_close($ch);

                            
                            if($response)

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

                            $res = [ 'result' => 'number'  ];

                        }   

                        

                }

                else{

                    $res = [ 'result' => 'invalid operation'  ];


                }

               

                return $this->json($res);


            }





		




}
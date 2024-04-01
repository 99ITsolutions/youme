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

class TemplateController  extends AppController

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

                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid') ;

                $retrieve_sms_template_table = $sms_template_table->find()->select(['id','temp_name','temp_body','added_date' ])->where(['school_id' => $compid ,'session_id'=> $session_id ])->toArray() ;

                

                $this->set("sms_template_details", $retrieve_sms_template_table); 

                $this->viewBuilder()->setLayout('user');

            }



            public function add(){

                $this->viewBuilder()->setLayout('user');

            }





            public function addtmplt(){

                if ($this->request->is('ajax') && $this->request->is('post') )

                {



                    $sms_template_table = TableRegistry::get('sms_template');

                    $activ_table = TableRegistry::get('activity');

                    $compid = $this->request->session()->read('company_id');

		    $session_id = $this->Cookie->read('sessionid') ;


                    $retrieve_template = $sms_template_table->find()->select(['id'  ])->where(['temp_name' => $this->request->data('temp_name') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;

                    

                    if($retrieve_template == 0 ){



                        if(!empty($this->request->data('temp_body')))

                        {

                        

                            $template = $sms_template_table->newEntity();

                            $template->temp_name =  $this->request->data('temp_name');

                            $template->temp_body =  $this->request->data('temp_body');

                            $template->school_id =  $compid;
			    $template->session_id = $session_id;	

                            $template->added_date = strtotime('now');

                            if($saved = $sms_template_table->save($template) ){

                                $activity = $activ_table->newEntity();

                                $activity->action =  "Template Created"  ;

                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

            

                                $activity->value = md5($saved->id)   ;

                                $activity->origin = $this->Cookie->read('id')   ;

                                $activity->created = strtotime('now');

                                if($saved = $activ_table->save($activity) ){

                                    $res = [ 'result' => 'success'  ];

        

                                }

                                else{

                                $res = [ 'result' => 'activity'  ];

        

                                }

                            }

                            else{

                                $res = [ 'result' => 'activity'  ];

                            }

                        }

                        else{

                            $res = [ 'result' => 'empty'  ];

                        }

                    }    

                    else

                    {

                        $res = [ 'result' => 'exist'  ];

                    }    



                   

                }

                else{

                    $res = [ 'result' => 'invalid operation'  ];



                }





                return $this->json($res);



            }    





            public function edit($id)

            {   

                $template_table = TableRegistry::get('sms_template');

                $compid =$this->request->session()->read('company_id');



                $retrieve_template_table = $template_table->find()->select(['id' , 'temp_name', 'temp_body' ])->where([ 'md5(id)' => $id  ])->toArray();

              

                $this->set("template_details", $retrieve_template_table); 

                $this->viewBuilder()->setLayout('user');

            }





            public function edittmplt()

            {   

                if ($this->request->is('ajax') && $this->request->is('post') )

                {   

                    $sms_template_table = TableRegistry::get('sms_template');

                    $activ_table = TableRegistry::get('activity');
	
		    $session_id = $this->Cookie->read('sessionid') ;


                    $retrieve_template = $sms_template_table->find()->select(['id'  ])->where(['temp_name' => $this->request->data('temp_name') , 'id IS NOT' => $this->request->data('id') , 'school_id'=> $this->request->data('school_id') ,'session_id' => $session_id ])->count();

                    

                    if($retrieve_template == 0 )

                    {   



                        if(!empty($this->request->data('temp_body')))

                        {

                            $tmpltit =  $this->request->data('id');   

                            $temp_name =  $this->request->data('temp_name');

                            $temp_body =  $this->request->data('temp_body');                   

                            

                            if( $sms_template_table->query()->update()->set([ 'temp_name' => $temp_name, 'temp_body' => $temp_body  ])->where([ 'id' => $tmpltit  ])->execute())

                            {   

                                $activity = $activ_table->newEntity();

                                $activity->action =  "Template update"  ;

                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                                $activity->origin = $this->Cookie->read('id');

                                $activity->value = md5($tmpltit); 

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

                                $res = [ 'result' => 'error'  ];

                            }

                        }

                        else{

                            $res = [ 'result' => 'empty'  ];

                        }    

                                        

                    }

                    else

                    {

                        $res = [ 'result' => 'exist'  ];

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

                

                $sid = $this->request->data('val') ;

                $template_table = TableRegistry::get('sms_template');

                

                $tmpltid = $template_table->find()->select(['id'])->where(['id'=> $sid ])->first();

                if($tmpltid)

                {   

                    

                    $del = $template_table->query()->delete()->where([ 'id' => $sid ])->execute(); 

                    if($del)

                    {

                        $res = [ 'result' => 'success'  ];

                        

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







}
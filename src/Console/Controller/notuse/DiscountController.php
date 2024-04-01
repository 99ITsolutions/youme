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

class DiscountController  extends AppController

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

                $discount_table = TableRegistry::get('discount');

                $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');



                $retrieve_discount = $discount_table->find()->select(['id', 'code' ,'percentage','dscr'])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                

                 $this->set("discount_details", $retrieve_discount); 

                $this->viewBuilder()->setLayout('user');

            }



            public function add()

            {   

                $this->viewBuilder()->setLayout('user');

            }



            public function adddsc(){

                if ($this->request->is('ajax') && $this->request->is('post') ){



                    $discount_table = TableRegistry::get('discount');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');


                    $retrieve_discount = $discount_table->find()->select(['id'  ])->where(['code' => $this->request->data('code') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count();
                    

                    if($retrieve_discount == 0 )

                    {   

                        $discount = $discount_table->newEntity();



                        $discount->code =  $this->request->data('code')  ;

                        $discount->percentage =  $this->request->data('percentage')  ;

                        $discount->dscr = $this->request->data('dscr') ;

                        $discount->school_id = $compid ;
			$discount->session_id = $session_id;



                        if($saved = $discount_table->save($discount) ){

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Discount Created"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($saved->id) ;

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

                            $res = [ 'result' => 'discount not saved'  ];

                        }

                    } 

                    else{

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

                $discount_table = TableRegistry::get('discount');

                

                $retrieve_discount = $discount_table->find()->select(['id' ,'code' , 'percentage' , 'dscr' ])->where([ 'md5(id)' => $id  ])->toArray() ;



                $this->set("discount_details", $retrieve_discount);  

                $this->viewBuilder()->setLayout('user');

            } 





            public function editdsc()

            {   

                if ($this->request->is('ajax') && $this->request->is('post') )

                {

                    $discount_table = TableRegistry::get('discount');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');
		
		    $session_id = $this->Cookie->read('sessionid');	


                    $retrieve_discount = $discount_table->find()->select(['id'  ])->where(['code' => $this->request->data('code') , 'id <>' => $this->request->data('id') , 'school_id'=> $compid , 'session_id' => $session_id  ])->count() ;

                    

                    if($retrieve_discount == 0 )

                    {   

                        $id = $this->request->data('id');

                        $code =  $this->request->data('code')  ;

                        $percentage =  $this->request->data('percentage')  ;

                        $dscr = $this->request->data('dscr') ;

                        

                        if( $discount_table->query()->update()->set([ 'code' => $code , 'percentage' => $percentage, 'dscr' => $dscr ])->where([ 'id' => $id  ])->execute())

                        {   

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Discount updated"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                            $activity->origin = $this->Cookie->read('id');

                            $activity->value = md5($id); 

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

                        $res = [ 'result' => 'exist'  ];

                    }

                }

                else

                {

                    $res = [ 'result' => 'invalid operation'  ];



                }



                return $this->json($res);

            } 





            public function view($id)

            {   

                $discount_table = TableRegistry::get('discount');

                

                $retrieve_discount = $discount_table->find()->select(['id' ,'code' , 'percentage' , 'dscr' ])->where([ 'md5(id)' => $id  ])->toArray() ;



                $this->set("discount_details", $retrieve_discount);  

                $this->viewBuilder()->setLayout('user');

            } 

			

			public function delete()

            {

				

                $id = $this->request->data('val') ;

                $discount_table = TableRegistry::get('discount');

                $activ_table = TableRegistry::get('activity');

                

                $dsclid = $discount_table->find()->select(['id'])->where(['id'=> $id ])->first();

                if($dsclid)

                {   

                    

					$del = $discount_table->query()->delete()->where([ 'id' => $id ])->execute(); 

                    if($del)

                    {

                       $activity = $activ_table->newEntity();

                        $activity->action =  "Discount Deleted"  ;

                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                        $activity->value = md5($id)    ;

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





            



            



}



  




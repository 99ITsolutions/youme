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
 * This controller will render views from Template/Pages/*
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class FeesetupController  extends AppController
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
                $this->viewBuilder()->setLayout('user');
                $feesetup_table = TableRegistry::get('feesetup');
                $class_table = TableRegistry::get('class');
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_feesetup = $feesetup_table->find()->select(['feesetup.id' , 'class.id', 'class.c_name', 'class.c_section' , 'feesetup.added_date' ])->join(['class' => 

                        [

                        'table' => 'class',

                        'type' => 'LEFT',

                        'conditions' => 'class.id = feesetup.class'

                    ]

                ])->where(['feesetup.school_id' => $compid , 'feesetup.session_id' => $session_id ])->toArray();



                $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section'])->where(['school_id' => $compid  ])->toArray(); 

                $this->set("class_details", $retrieve_class); 
                $this->set("feesetup_details", $retrieve_feesetup);
            }





            public function addfeeset(){


                if ( $this->request->is('post') ){

                    $this->request->session()->write('LAST_ACTIVE_TIME', time());

                    $feesetup_table = TableRegistry::get('feesetup');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');

					$session_id = $this->Cookie->read('sessionid');                    

                    $retrieve_feesetup = $feesetup_table->find()->select(['id'  ])->where(['class' => $this->request->data('class'), 'session_id' => $session_id, 'school_id' => $compid ])->count() ;

                    

                    if($retrieve_feesetup == 0 )

                    {

                        $feeset = $feesetup_table->newEntity();



                        $feeset->class = $this->request->data('class');

                        $feeset->school_id = $compid;

                        $feeset->added_date = strtotime('now');

                        $feeset->session_id = $session_id;	

                        if($saved = $feesetup_table->save($feeset) ){

                            

                            $feesetupid = $saved->id;

                            //$feeset = md5($saved->id);

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Fee Setup Created"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($saved->id)   ;

                            $activity->origin = $this->Cookie->read('id')   ;

                            $activity->created = strtotime('now');

                            if($saved = $activ_table->save($activity) )

                            {

                                $this->request->session()->write('feesetupid', $feesetupid);

                               // $this->request->session()->write('session_name', $this->request->data('sess_name'));

                                $this->request->session()->write('feesetupcid', $this->request->data('class'));

                                

                               // return $this->redirect('/feedetail/');                        

								$res = [ 'result' => 'success'  ];

                            }

                            else{

                        $res = [ 'result' => 'activity not saved'  ];

    

                            }

    

                        }

                        else{

                            $res = [ 'result' => 'fee not saved'  ];

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
			
			
			 public function duplicatefeestructure(){
                if ($this->request->is('post') )
				{

                    $feesetup_table = TableRegistry::get('feesetup');
					$feedetail_table = TableRegistry::get('feedetail');
                    $activ_table = TableRegistry::get('activity');
					
                    $compid =$this->request->session()->read('company_id');
					$session_id = $this->Cookie->read('sessionid');   
					
					$structureclass = $this->request->data('structureclass');
					$r_feesetup_id = $feesetup_table->find()->select(['id'])->where(['class' => $structureclass, 'session_id' => $session_id, 'school_id' => $compid ])->toArray();
					
					$setupid = $r_feesetup_id[0]['id'];
					
					$r_feedetails = $feedetail_table->find()->select(['id', 'fee_h_id', 'amount'])->where(['fee_s_id' => $setupid, 'session_id' => $session_id, 'school_id' => $compid ])->toArray();
					
                    $retrieve_feesetup = $feesetup_table->find()->select(['id'  ])->where(['class' => $this->request->data('sessionclass'), 'session_id' => $session_id, 'school_id' => $compid ])->count() ;
                    if($retrieve_feesetup == 0 )
                    {
                        $feeset = $feesetup_table->newEntity();
                        $feeset->class = $this->request->data('sessionclass');
                        $feeset->school_id = $compid;
                        $feeset->added_date = strtotime('now');
                        $feeset->session_id = $session_id;	
                        if($saved = $feesetup_table->save($feeset) ){                            

                            $feesetupid = $saved->id;
							foreach($r_feedetails as $feedetails)
							{
								$feedtl = $feedetail_table->newEntity();
								$feedtl->fee_s_id = $feesetupid;
								$feedtl->fee_h_id = $feedetails['fee_h_id'];
								$feedtl->amount = $feedetails['amount'];
								$feedtl->school_id = $compid;
								$feedtl->session_id = $session_id;
								
								$saved_dtl = $feedetail_table->save($feedtl);
							}
							
							
							
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fee Setup Created"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->value = md5($saved->id)   ;
                            $activity->origin = $this->Cookie->read('id')   ;
                            $activity->created = strtotime('now');
                            if($saved = $activ_table->save($activity) )
                            {
                                $this->request->session()->write('feesetupid', $feesetupid);
                                $this->request->session()->write('feesetupcid', $this->request->data('class'));
								$res = [ 'result' => 'success'  ];
                            }
                            else{
								$res = [ 'result' => 'activity not saved'  ];
                            }
                        }
                        else{
                            $res = [ 'result' => 'fee not saved'  ];
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

            



            public function edit($fsid)

            {   

                

                $feedetail_table = TableRegistry::get('feedetail');

                $feehead_table = TableRegistry::get('feehead');

                $feesetup_table = TableRegistry::get('feesetup');

                $class_table = TableRegistry::get('class');

                $compid =$this->request->session()->read('company_id');
	
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_feedetail = $feedetail_table->find()->select(['feedetail.id'  , 'feedetail.amount'  ,'feehead.head_name' ])->join([

                    'feehead' => 

                        [

                        'table' => 'feehead',

                        'type' => 'LEFT',

                        'conditions' => 'feehead.id = feedetail.fee_h_id'

                    ]
                ])->where(['md5(feedetail.fee_s_id)' => $fsid , 'feedetail.session_id' => $session_id ])->toArray();

				

		 $retrieve_feeset = $feesetup_table->find()->select(['feesetup.id' , 'feesetup.class'])->join([

                    'class' => 

                        [

                        'table' => 'class',

                        'type' => 'LEFT',

                        'conditions' => 'class.id = feesetup.class'

                    ]

                ])->where(['md5(feesetup.id)' => $fsid , 'feesetup.session_id' => $session_id])->toArray();



                $retrieve_feehead = $feehead_table->find()->select(['id' ,'head_name'])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray();



                $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section'])->where(['school_id' => $compid , 'session_id' => $session_id  ])->toArray(); 



                $this->set("class_details", $retrieve_class); 

                $this->set("feedetail_details", $retrieve_feedetail);  

                $this->set("feehead_details", $retrieve_feehead); 

                $this->set("feeset_details", $retrieve_feeset);

				$this->set("feesetupid", $fsid);

				

				

				$this->viewBuilder()->setLayout('user');

            }

			

			public function addfeedet(){

                if ($this->request->is('ajax') && $this->request->is('post') ){

					$feestpid =$this->request->data('setupid');

                    $feedetail_table = TableRegistry::get('feedetail');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');

                    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_feehead = $feedetail_table->find()->select(['id'  ])->where(['fee_h_id' => $this->request->data('head_name'),  'fee_s_id' => $feestpid, 'school_id' => $compid , 'session_id' => $session_id ])->count() ;

                    

                    if($retrieve_feehead == 0 ){

                        $feedet = $feedetail_table->newEntity();



                        $feedet->fee_s_id =  $this->request->data('setupid');

                        $feedet->fee_h_id =  $this->request->data('head_name');

                        $feedet->amount =  $this->request->data('amount');

                        $feedet->school_id = $compid;

						$feedet->session_id = $session_id;

                        

                        if($saved = $feedetail_table->save($feedet) ){

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Fee Detail Created"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($saved->id)   ;

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

                            $res = [ 'result' => 'fee not saved'  ];

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

			public function editfeedet(){

                if ($this->request->is('ajax') && $this->request->is('post')){



                    $feedetail_table = TableRegistry::get('feedetail');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');

		    $session_id = $this->Cookie->read('sessionid');	
	
                    $retrieve_feehead = $feedetail_table->find()->select(['id'  ])->where(['fee_s_id' => $this->request->data('class'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid , 'session_id' => $session_id ])->count() ;

                    

                    if($retrieve_feehead == 0 ){



                        $id = $this->request->data('id');

                        $head_name =  $this->request->data('head_name') ;

                       // $class =  $this->request->data('class') ;

                        $amount =  $this->request->data('amount') ;

                        

                        if( $feedetail_table->query()->update()->set([ 'fee_h_id' => $head_name , 'amount'=>$amount ])->where([ 'id' => $id  ])->execute())

                        {

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Fee Detail Updated"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($id)   ;

                            $activity->origin = $this->Cookie->read('id')   ;

                            $activity->created = strtotime('now');

                            if($saved = $activ_table->save($activity) )

                            {

                                $res = [ 'result' => 'success'  ];

    

                            }

                            else

                            {

                                $res = [ 'result' => 'activity not saved'  ];

                            }

    

                        }

                        else

                        {

                            $res = [ 'result' => 'fee head not updated'  ];

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

            

			

			public function deleteDtl()

            {

                $rid = $this->request->data('val') ;

                $feedetail_table = TableRegistry::get('feedetail');

                $activ_table = TableRegistry::get('activity');

                

                $feeheadid = $feedetail_table->find()->select(['id'])->where(['id'=> $rid ])->first();

                if($feeheadid)

                {   

                    if($feedetail_table->delete($feedetail_table->get($rid)))

                    {

                        $activity = $activ_table->newEntity();

                        $activity->action =  "Fee Detail Deleted"  ;

                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                        $activity->value = md5($rid)    ;

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







            public function editfeeset(){

                if ($this->request->is('ajax') && $this->request->is('post')){



                    $feesetup_table = TableRegistry::get('feesetup');

                    $activ_table = TableRegistry::get('activity');

                    $compid =$this->request->session()->read('company_id');

		   $session_id = $this->Cookie->read('sessionid');	

                    $retrieve_feesetup = $feesetup_table->find()->select(['id'  ])->where(['class' => $this->request->data('class'), 'id IS NOT' => $this->request->data('setupid')  , 'session_id' => $session_id , 'school_id' => $compid ])->count() ;

                    

                    if($retrieve_feesetup == 0 ){



                         $id = $this->request->data('setupid');

                         $class =  $this->request->data('class')  ;



                        if( $feesetup_table->query()->update()->set([ 'class'=>$class ])->where([ 'id' => $id  ])->execute())

                        {

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Fee Setup Updated"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($id)   ;

                            $activity->origin = $this->Cookie->read('id')   ;

                            $activity->created = strtotime('now');

                            if($saved = $activ_table->save($activity) )

                            {

                                $res = [ 'result' => 'success'  ];

    

                            }

                            else

                            {

                                $res = [ 'result' => 'activity not saved'  ];

                            }

    

                        }

                        else

                        {

                            $res = [ 'result' => 'fee head not updated'  ];

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

            

            public function delete()

            {

                $rid = $this->request->data('val') ;

                $feesetup_table = TableRegistry::get('feesetup');

				$feedetail_table = TableRegistry::get('feedetail');

                $activ_table = TableRegistry::get('activity');

                

                $feeid = $feesetup_table->find()->select(['id'])->where(['id'=> $rid ])->first();

                if($feeid)

                {   

                    if($feesetup_table->delete($feesetup_table->get($rid)))

                    {

			$del_dtl =  $feedetail_table->query()->delete()->where([ 'fee_s_id' => $rid ])->execute(); 

                        $activity = $activ_table->newEntity();

                        $activity->action =  "Fee Setup Deleted"  ;

                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                        $activity->value = md5($rid)    ;

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

			

			public function updatesetupclass()

            {   

                if($this->request->is('post')){



                $classId = $this->request->data('classId');

                $Id = $this->request->data('Id');

                $feeset_table = TableRegistry::get('feesetup');



                $update_class = $feeset_table->query()->update()->set([ 'class' => $classId ])->where([ 'id' => $Id  ])->execute();

				$res = [ 'result' => 'updated'  ];

					return $this->json($res);



                }  

            }

			

	public function updatesetupsess()

            {   

                if($this->request->is('post')){



                $sess_name = $this->request->data('sessName');

                $Id = $this->request->data('Id');

                $feeset_table = TableRegistry::get('feesetup');



                $update_class = $feeset_table->query()->update()->set([ 'sess_name' => $sess_name ])->where([ 'id' => $Id  ])->execute();

				$res = [ 'result' => 'updated'  ];

					return $this->json($res);



                }  

            }

            

    }


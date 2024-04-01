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

class TransferController  extends AppController

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

            public function index(){



                $transfer_table = TableRegistry::get('tc');

                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');


                $retrieve_trasnfer = $transfer_table->find()->select(['id', 's_name' , 's_f_name' , 's_m_name'  ,'adm_dt' , 'tc_dt'])->where(['session_id' => $session_id , 'school_id'=> $compid  ])->toArray();


                $this->set("trasnfer_details", $retrieve_trasnfer); 

                $this->viewBuilder()->setLayout('user');



            }



            public function add(){

		
		$student_table = TableRegistry::get('student');
                $this->viewBuilder()->setLayout('user');
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

		$setting_table = TableRegistry::get('stdnt_h_setting');
		$class_table = TableRegistry::get('class');
		$transfer_table = TableRegistry::get('tc');
					
			
					$student = array();
					   
					$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();
					
					if(!empty($colname)){
						$col_type = explode(',', $colname[0]['col_type']);
						array_push($student,'student.s_name','student.id','student.acc_no');

						if(in_array("Student Name", $col_type)){
							array_push($student,'student.s_name');
						}
						if(in_array("Father Name", $col_type)){
							array_push($student,'student.s_f_name');  
						}
						if(in_array("Mother Name", $col_type)){
							array_push($student,'student.s_m_name');
						}
						if(in_array("Account Number", $col_type)){
							array_push($student,'student.acc_no');
						}
						if(in_array("Admission Number", $col_type)){
							array_push($student,'student.adm_no'); 
						}
						if(in_array("Address", $col_type)){
							array_push($student,'resi_add1'); 
						}
						if(in_array("Class", $col_type)){
							array_push($student,'class.c_name'); 
						}
						if(in_array("Section", $col_type)){
						   array_push($student,'class.c_section'); 
						}
						if(in_array("Session", $col_type)){
							array_push($student,'c_sess_name'); 
						} 
					}
					else
					{
						array_push($student,'student.s_name','student.id','student.acc_no');	
					}

					   

					$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.school_id'=> $compid , 'student.session_id'=> $session_id , 'stud_left IS NOT' => "Yes" ])->toArray(); 
		
		$class_details = $class_table->find()->select(['id' , 'c_name' , 'c_section'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();


		$file_no = '';

		$transfer_file_no = $transfer_table->find()->select(['file_no'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->last();
		
		if(!empty($transfer_file_no)){
			$file_no = $transfer_file_no['file_no'];
			$file_no++;
		}
		else{
			$file_no = 1;
			
		}

		   $todaydate = date('d-m-Y',strtotime('now'));
	
	
	            $file_number = ['file_no' => $file_no , 'today_date' => $todaydate  ];			
		
		$this->set("sibling_details", $retrieve_siblings);
		$this->set("file_number", $file_number);
		$this->set("class_detail", $class_details);
	

            }



	public function addslc(){

                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $compid = $this->request->session()->read('company_id');  

                    $transfer_table = TableRegistry::get('tc');

                    $activ_table = TableRegistry::get('activity');

		    $session_id = $this->Cookie->read('sessionid');
	
		    $student_table = TableRegistry::get('student');

		    $balance_table = TableRegistry::get('balance');		
                    

                    $retrieve_transfer = $transfer_table->find()->select(['id' ])->where(['adm_no' => $this->request->data('adm_no') , 'session_id' => $session_id , 'school_id'=> $compid  ])->count() ;

                    $retrieve_student = $student_table->find()->select(['acc_no' ])->where(['adm_no' => $this->request->data('adm_no') , 'school_id' => $compid , 'session_id' =>$session_id])->first() ;

                    $retrieve_balance = $balance_table->find()->select(['bal_amt' ])->where(['acc_no' => $retrieve_student['acc_no'] , 'school_id' => $compid , 'session_id' =>$session_id ])->first() ;

                    
		    $subjects = implode(",",$this->request->data('subjects'));
	
		   if ($retrieve_balance['bal_amt'] == 0) 
                    {

	                    if($retrieve_transfer == 0 )

	                    {   

	                        $transfer = $transfer_table->newEntity();


	                        $transfer->file_no =  $this->request->data('file_no');

				$transfer->sr_no =  $this->request->data('sr_no');

				$transfer->scl_no =  $this->request->data('scl_no');

				$transfer->national =  $this->request->data('nationality');

	                        $transfer->adm_no =  $this->request->data('adm_no');                        

	                        $transfer->s_name =  $this->request->data('s_name');

	                        $transfer->s_f_name = $this->request->data('s_f_name');

	                        $transfer->s_m_name = $this->request->data('s_m_name');

	                        $transfer->withdrl_adm_no =  $this->request->data('withdrl_adm_no');

	                        $transfer->fld_class =  $this->request->data('fld_class');

	                        $transfer->prsnt_class =  $this->request->data('prsnt_class');

	                        $transfer->prmt_class =  $this->request->data('prmt_class');

	                        $transfer->g_con =  $this->request->data('g_con');

	                        $transfer->fee_pd_till_mon =  $this->request->data('fee_pd_till_mon');

	                        $transfer->ncc =  $this->request->data('ncc');

	                        $transfer->consession =  $this->request->data('consession');

	                        $transfer->subjects =  $subjects;

	                        $transfer->f_class =  $this->request->data('f_class');

	                        $transfer->tc_no =  $this->request->data('tc_no');

	                        $transfer->tc_app_dt =  date('Y-m-d', strtotime($this->request->data('tc_app_dt')));

	                        $transfer->tc_dt =  date('Y-m-d', strtotime($this->request->data('tc_dt')));

	                        $transfer->adm_dt =  date('Y-m-d', strtotime($this->request->data('adm_dt')));

	                        $transfer->t_no_days =  $this->request->data('t_no_days');

	                        $transfer->t_no_p_day =  $this->request->data('t_no_p_day');

	                        $transfer->game =  $this->request->data('game');

	                        $transfer->slc_reason =  $this->request->data('slc_reason');

	                        $transfer->remarks =  $this->request->data('remarks');

	                        $transfer->dob =  date('Y-m-d', strtotime($this->request->data('dob')));

	                        $transfer->s_caste =  $this->request->data('s_caste');

	                        $transfer->l_exm_resl =  $this->request->data('l_exm_resl');

	                        $transfer->cat =  $this->request->data('cat');                        

	                        $transfer->school_id = $compid ;

				$transfer->session_id = $session_id ;

				$studentid =  $this->request->data('id');

	                        if($saved = $transfer_table->save($transfer) )

	                        {   
					$todaydate = date('Y-m-d',strtotime('now'));

					if($student_table->query()->update()->set([ 'stud_left' => "Yes", 'left_dt'=> $todaydate ])->where([ 'id' => $studentid  ])->execute())
					{

	                                $activity = $activ_table->newEntity();

	                                $activity->action =  "Trasnfer Certificate Created"  ;

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

	                                $res = [ 'result' => 'Certificate not saved'  ];

	                            }

	                         }

	                         else{

	                            $res = [ 'result' => 'Certificate not saved'  ];

	                         }
	                    } 

	                    else{

	                        $res = [ 'result' => 'exist'];

	                    }
	                }
                    else{
                        $res = [ 'result' => 'payment'];
                    }    

                }

                else{

                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);

            }
	



            public function edit($id){


                $transfer_table = TableRegistry::get('tc');

		$student_table = TableRegistry::get('student');

                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

		$setting_table = TableRegistry::get('stdnt_h_setting');

		$class_table = TableRegistry::get('class');

                $retrieve_transfer = $transfer_table->find()->select(['id','file_no' , 'scl_no' ,'sr_no','adm_no', 's_name' , 's_f_name' , 's_m_name', 'withdrl_adm_no', 'fld_class', 'prsnt_class', 'prmt_class', 'g_con', 'fee_pd_till_mon', 'ncc' , 'national' , 'consession', 'subjects', 'f_class', 'tc_no', 'tc_app_dt', 'tc_dt', 'adm_dt', 't_no_days', 't_no_p_day', 'game', 'slc_reason', 'remarks', 'dob', 's_caste', 'l_exm_resl', 'cat', 'school_id'])->where(['md5(id)' => $id , 'school_id'=> $compid  ])->toArray();

		$student = array();
             
          	$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();
          
              if(!empty($colname))
	      {
   	         $col_type = explode(',', $colname[0]['col_type']);
        	    array_push($student,'student.s_name','student.id','student.acc_no');

            	if(in_array("Student Name", $col_type)){
              		array_push($student,'student.s_name');
            	}

            	if(in_array("Father Name", $col_type)){
              		array_push($student,'student.s_f_name');  
            	}
            	if(in_array("Mother Name", $col_type)){
              		array_push($student,'student.s_m_name');
            	}
    	        if(in_array("Account Number", $col_type)){
             		array_push($student,'student.acc_no');
        	}
            	if(in_array("Admission Number", $col_type)){
              		array_push($student,'student.adm_no'); 
            	}
 	        if(in_array("Address", $col_type)){
              		array_push($student,'resi_add1'); 
            	}
            	if(in_array("Class", $col_type)){
              		array_push($student,'class.c_name'); 
            	}
            	if(in_array("Section", $col_type)){
               		array_push($student,'class.c_section'); 
            	}
            	if(in_array("Session", $col_type)){
              		array_push($student,'c_sess_name'); 
            	} 
            }
            else
            {
            	array_push($student,'student.s_name','student.id','student.acc_no');  
            }

             

          	$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
              		[
 	             	 'table' => 'class',
        	         'type' => 'LEFT',
              		 'conditions' => 'class.id = student.class'
            		]
          	 ])->where(['student.school_id'=> $compid , 'student.session_id'=> $session_id  ])->toArray(); 
    
   		
		$class_details = $class_table->find()->select(['id' , 'c_name' , 'c_section'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();

                $this->set("transfer_details", $retrieve_transfer); 
		$this->set("sibling_details", $retrieve_siblings);
		$this->set("class_detail", $class_details);
                $this->viewBuilder()->setLayout('user');

            }



            public function editslc()

            {   

                if ($this->request->is('ajax') && $this->request->is('post') )

                {

                    $transfer_table = TableRegistry::get('tc');

                    $activ_table = TableRegistry::get('activity');

		     $session_id = $this->Cookie->read('sessionid');
                    

                    $retrieve_transfer = $transfer_table->find()->select(['id'  ])->where(['adm_no' => $this->request->data('adm_no') , 'id IS NOT' => $this->request->data('id') ,'school_id'=> $this->request->data('school_id') , 'session_id' => $session_id   ])->count() ;

                    

                    if($retrieve_transfer == 0 )

                    {   
                        

                        $id =  $this->request->data('id');

                        $file_no =  $this->request->data('file_no');

			$sr_no =  $this->request->data('sr_no');

			$scl_no =  $this->request->data('scl_no');

                        $adm_no =  $this->request->data('adm_no');                        

                     //   $s_name =  $this->request->data('s_name');

                        $s_f_name = $this->request->data('s_f_name');

                        $s_m_name = $this->request->data('s_m_name');

                        $withdrl_adm_no =  $this->request->data('withdrl_adm_no');

                        $fld_class =  $this->request->data('fld_class');

                        $prsnt_class =  $this->request->data('prsnt_class');

                        $prmt_class =  $this->request->data('prmt_class');

                        $g_con =  $this->request->data('g_con');

                        $fee_pd_till_mon =  $this->request->data('fee_pd_till_mon');

                        $ncc =  $this->request->data('ncc');

			$national =  $this->request->data('nationality');

                        $consession =  $this->request->data('consession');

                        $f_class =  $this->request->data('f_class');

                        $tc_no =  $this->request->data('tc_no');

                        $tc_app_dt =  date('Y-m-d', strtotime($this->request->data('tc_app_dt')));

                        $tc_dt =  date('Y-m-d', strtotime($this->request->data('tc_dt')));

                        $adm_dt =  date('Y-m-d', strtotime($this->request->data('adm_dt')));

                        $t_no_days =  $this->request->data('t_no_days');

                        $t_no_p_day =  $this->request->data('t_no_p_day');

                        $game =  $this->request->data('game');

                        $slc_reason =  $this->request->data('slc_reason');

                        $remarks =  $this->request->data('remarks');

                        $dob =  date('Y-m-d', strtotime($this->request->data('dob')));

                        //$s_caste =  $this->request->data('s_caste');

                        $l_exm_resl =  $this->request->data('l_exm_resl');

                        $cat =  $this->request->data('cat');    

			$subjects = implode(",",$this->request->data('subjects'));                    

                        

                            if( $transfer_table->query()->update()->set(['sr_no' => $sr_no , 'scl_no'=> $scl_no, 'file_no'=>$file_no, 'adm_no' => $adm_no ,  's_f_name' => $s_f_name , 's_m_name' => $s_m_name , 'withdrl_adm_no'=>$withdrl_adm_no, 'fld_class'=>$fld_class, 'prsnt_class'=>$prsnt_class, 'prmt_class'=>$prmt_class, 'g_con'=>$g_con , 'fee_pd_till_mon'=>$fee_pd_till_mon, 'ncc'=>$ncc , 'national'=> $national , 'consession'=>$consession, 'subjects'=>$subjects, 'f_class'=>$f_class, 'tc_no'=>$tc_no, 'tc_app_dt'=>$tc_app_dt, 'tc_dt'=> $tc_dt, 'adm_dt'=>$adm_dt , 't_no_days'=>$t_no_days, 't_no_p_day'=>$t_no_p_day, 'game'=>$game, 'slc_reason'=>$slc_reason, 'remarks'=>$remarks, 'dob' => $dob , 's_caste'=>$s_caste, 'l_exm_resl'=>$l_exm_resl ,  'cat' => $cat ])->where([ 'id' => $id  ])->execute())

                            {   

                                $activity = $activ_table->newEntity();

                                $activity->action =  "Trasnfer certificate updated"  ;

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



            public function view($id){


                $transfer_table = TableRegistry::get('tc');

		$student_table = TableRegistry::get('student');

                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

		$setting_table = TableRegistry::get('stdnt_h_setting');

		$class_table = TableRegistry::get('class');

                $retrieve_transfer = $transfer_table->find()->select(['id','file_no' , 'scl_no' ,'sr_no','adm_no', 's_name' , 's_f_name' , 's_m_name', 'withdrl_adm_no', 'fld_class', 'prsnt_class', 'prmt_class', 'g_con', 'fee_pd_till_mon', 'ncc' , 'national' , 'consession', 'subjects', 'f_class', 'tc_no', 'tc_app_dt', 'tc_dt', 'adm_dt', 't_no_days', 't_no_p_day', 'game', 'slc_reason', 'remarks', 'dob', 's_caste', 'l_exm_resl', 'cat', 'school_id'])->where(['md5(id)' => $id , 'school_id'=> $compid  ])->toArray();

		$student = array();
             
          	$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();
          
              if(!empty($colname))
	      {
   	         $col_type = explode(',', $colname[0]['col_type']);
        	    array_push($student,'student.s_name','student.id','student.acc_no');

            	if(in_array("Student Name", $col_type)){
              		array_push($student,'student.s_name');
            	}

            	if(in_array("Father Name", $col_type)){
              		array_push($student,'student.s_f_name');  
            	}
            	if(in_array("Mother Name", $col_type)){
              		array_push($student,'student.s_m_name');
            	}
    	        if(in_array("Account Number", $col_type)){
             		array_push($student,'student.acc_no');
        	}
            	if(in_array("Admission Number", $col_type)){
              		array_push($student,'student.adm_no'); 
            	}
 	        if(in_array("Address", $col_type)){
              		array_push($student,'resi_add1'); 
            	}
            	if(in_array("Class", $col_type)){
              		array_push($student,'class.c_name'); 
            	}
            	if(in_array("Section", $col_type)){
               		array_push($student,'class.c_section'); 
            	}
            	if(in_array("Session", $col_type)){
              		array_push($student,'c_sess_name'); 
            	} 
            }
            else
            {
            	array_push($student,'student.s_name','student.id','student.acc_no');  
            }

             

          	$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
              		[
 	             	 'table' => 'class',
        	         'type' => 'LEFT',
              		 'conditions' => 'class.id = student.class'
            		]
          	 ])->where(['student.school_id'=> $compid , 'student.session_id'=> $session_id  ])->toArray(); 
    
   		
		$class_details = $class_table->find()->select(['id' , 'c_name' , 'c_section'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->toArray();

                $this->set("transfer_details", $retrieve_transfer); 
		$this->set("sibling_details", $retrieve_siblings);
		$this->set("class_detail", $class_details);
                $this->viewBuilder()->setLayout('user');

            }




            public function delete()

            {   



                $tcid = $this->request->data('val') ;

                $trasnfer_table = TableRegistry::get('tc');

                $activ_table = TableRegistry::get('activity');



                $tcsid = $trasnfer_table->find()->select(['id'])->where(['id'=> $tcid ])->first();

                

                if($tcsid)

                {   

                    $del = $trasnfer_table->query()->delete()->where([ 'id' => $tcid ])->execute(); 

                    if($del)

                    {

                        $activity = $activ_table->newEntity();

                        $activity->action =  "Trasnfer Certificate Deleted"  ;

                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                        $activity->value = md5($tcid)    ;

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



	    public function studentdetail()
            {
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $studentname = $this->request->data['studentname'];

                    $student_table = TableRegistry::get('student');
                    $class_table = TableRegistry::get('class');

                    $compid =$this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');

                    $student_detail = $student_table->find()->select(['id','s_name','s_f_name','s_m_name','adm_no','adm_dt','cat','dob','class', 'org_adm_no','national'])->where(['school_id'=> $compid , 'session_id'=>$session_id, 'stud_left IS NOT' => 'Yes' ,'s_name'=> $studentname ])->first();


                    $student_olddetail = $student_table->find()->select(['s_name' , 'class' , 'adm_dt' ])->where(['school_id'=> $compid , 'stud_left IS NOT' => 'Yes'  ,'adm_no'=> $student_detail['adm_no'] ])->first(); 
                        
                    $adm_dt = date('d-m-Y',strtotime($student_olddetail['adm_dt']));

                    $dob = date('d-m-Y',strtotime($student_detail['dob']));   

                    $data = ['id' => $student_detail['id'], 's_name'=> $student_detail['s_name'], 's_f_name'=> $student_detail['s_f_name'] ,'s_m_name'=> $student_detail['s_m_name'] , 'adm_no'=> $student_detail['adm_no'] , 'adm_dt'=> $adm_dt , 'cat'=> $student_detail['cat'], 'dob'=> $dob , 'currentclass'=> $student_detail['class'] , 'oldclass'=> $student_olddetail['class'], 'org_adm_no' => $student_detail['org_adm_no'] , 'national'=> $student_detail['national'] ];        

                    return $this->json($data);

                }
            }

			
			/*public function pdf($tid = "")
            {
				
				
				$pdf=new FPDF();

				//set document properties
				$pdf->SetAuthor('Lana Kovacevic');
				$pdf->SetTitle('FPDF tutorial');

				//set font for the entire document
				$pdf->SetFont('Helvetica','B',20);
				$pdf->SetTextColor(50,60,100);

				//set up a page
				$pdf->AddPage('P');
				$pdf->SetDisplayMode(real,'default');

				//insert an image and make it a link
				$pdf->Image('logo.png',10,20,33,0,' ','http://www.fpdf.org/');

				//display the title with a border around it
				$pdf->SetXY(50,20);
				$pdf->SetDrawColor(50,60,100);
				$pdf->Cell(100,10,'FPDF Tutorial',1,0,'C',0);

				//Set x and y position for the main text, reduce font size and write content
				$pdf->SetXY (10,50);
				$pdf->SetFontSize(10);
				$pdf->Write(5,'Congratulations! You have generated a PDF.');

				//Output the document
				$pdf->Output('example1.pdf','I');
				
				$transfer_table = TableRegistry::get('tc');
                    
                $retrieve_transfer = $transfer_table->find()->select(['id', 'file_no', 'sr_no', 'prefix', 'adm_no', 'h_prefix', 'h_adm_no', 's_name', 's_f_name', 's_m_name', 'study_med', 'c_name', 'withdrl_adm_no', 'fld_class', 'prsnt_class', 'prmt_class', 'g_con', 'fee_pd_till_mon', 'ncc', 'consession', 'subjects', 'cc_name', 'f_class', 'national', 'resi_add1', 'resi_add2', 'resi_city', 'tc_no', 'tc_app_dt', 'tc_dt', 'adm_dt', 'sess_name', 't_no_days', 't_no_p_day', 'game', 'slc_reason', 'remarks', 'l_pain_mon', 'overall', 'dob', 's_caste', 'l_exm_resl', 'cat', 'add1', 'school_id', 'session_id' ])->where(['id' => $tid   ])->toArray() ;
				
				$this->set("trasnfer_details", $retrieve_trasnfer);  

                $this->viewBuilder()->setLayout('user');
			} */
			
		
            /*public function pdf($tid)
            {   

                Configure::write('CakePdf.download', true);
                Configure::write('CakePdf.filename', "Slc.pdf");
				
				
                $school_id = $this->request->session()->read('school_id');                
				$transfer_table = TableRegistry::get('tc');
				$class_table = TableRegistry::get('class');
                    
                $retrieve_transfer = $transfer_table->find()->select(['id', 'file_no', 'sr_no', 'prefix', 'adm_no', 'h_prefix', 'h_adm_no', 's_name', 's_f_name', 's_m_name', 'study_med', 'c_name', 'withdrl_adm_no', 'fld_class', 'prsnt_class', 'prmt_class', 'g_con', 'fee_pd_till_mon', 'ncc', 'consession', 'subjects', 'cc_name', 'f_class', 'national', 'resi_add1', 'resi_add2', 'resi_city', 'tc_no', 'tc_app_dt', 'tc_dt', 'adm_dt', 'sess_name', 't_no_days', 't_no_p_day', 'game', 'slc_reason', 'remarks', 'l_pain_mon', 'overall', 'dob', 's_caste', 'l_exm_resl', 'cat', 'add1', 'school_id', 'session_id' ])->where(['md5(id)' => $tid   ])->toArray() ;
				
				$retrieve_class1 = $class_table->find()->select(['c_name', 'c_section' ])->where(['id' => $retrieve_transfer[0]['f_class']   ])->toArray() ;
				
				$retrieve_class2 = $class_table->find()->select(['c_name', 'c_section' ])->where(['id' => $retrieve_transfer[0]['prsnt_class']   ])->toArray() ;
				
				$this->set("f_class", $retrieve_class1); 
				$this->set("l_class", $retrieve_class2); 
				$this->set("transfer_certificate", $retrieve_transfer); 
				
				

            } */



            public function cakePdf()
            {
                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $CakePdf->template('cake_pdf', 'default');
                $CakePdf->viewVars($this->viewVars);
                $pdf = $CakePdf->write(APP . 'Files' . DS . 'Output.pdf');
                echo $pdf;
                die();
				
				

            }
			
	public function pdf($tid = null)
            {
				
				$school_id = $this->request->session()->read('school_id');                
				$transfer_table = TableRegistry::get('tc');
				$class_table = TableRegistry::get('class');
				$school_table = TableRegistry::get('company');

                    
                $transfer_certificate = $transfer_table->find()->select(['id', 'file_no', 'scl_no' , 'sr_no', 'prefix', 'adm_no', 'h_prefix', 'h_adm_no', 's_name', 's_f_name', 's_m_name', 'study_med', 'c_name', 'withdrl_adm_no', 'fld_class', 'prsnt_class', 'prmt_class', 'g_con', 'fee_pd_till_mon', 'ncc', 'consession', 'subjects', 'cc_name', 'f_class', 'national', 'resi_add1', 'resi_add2', 'resi_city', 'tc_no', 'tc_app_dt', 'tc_dt', 'adm_dt', 'sess_name', 't_no_days', 't_no_p_day', 'game', 'slc_reason', 'remarks', 'l_pain_mon', 'overall', 'dob', 's_caste', 'l_exm_resl', 'cat', 'add1', 'school_id', 'session_id' ])->where(['md5(id)' => $tid   ])->toArray() ;
				
		$f_class = $class_table->find()->select(['c_name', 'c_section' ])->where(['id' => $transfer_certificate[0]['f_class']   ])->toArray() ;
				
		$l_class = $class_table->find()->select(['c_name', 'c_section' ])->where(['id' => $transfer_certificate[0]['prsnt_class']   ])->toArray() ;

		$school_detail = $school_table->find()->select(['comp_name', 'add_1' , 'ph_no' , 'email' ])->where(['id' => $transfer_certificate[0]['school_id'] ])->toArray() ;
                
                $dompdf = new Dompdf();
                $dompdf->loadHtml('<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				
                <div class="header mt-5" style="height:100px">
                	<div class="row">
				
                	</div>
                </div>
                <hr style="border: 1px solid red; width:90%;"><hr style="border: 1px solid red; width:85%;">
                <div class="row container mb-3">
	                <div class="col-sm-12">
	                	<h4 class="text-center mt-3 mb-3 font-weight-bold" style="text-align:center"><span style="border:1px solid #000">SCHOOL LEAVING CERTIFICATE/TRANSFER CERTIFICATE </span></h4>
	                </div>
					<div class="col-sm-12" style="border:1px solid #000">
					</div>
	                
	             </div>
					<div class="col-sm-12" style="margin-top:20px !important; margin-bottom:40px !important;">
	                <span style="margin-right:100px !important; text-align:left">Book No. '. $transfer_certificate[0]['file_no'].'</span>
						<!--<p>Date: <?php //echo date("d-m-Y", time())?></p>-->                   
	                <span style="margin-right:100px !important; margin-left:100px !important; text-align:center">Sl No.: '. $transfer_certificate[0]['sr_no'] .'</span>
					<span style="margin-right:100px !important; margin-left:100px !important; text-align:center;">
						School No.: '. $transfer_certificate[0]['scl_no'] .'
					</span>
					<span style=" margin-left:100px; !important; text-align:right;">
						Admission No.: '. $transfer_certificate[0]['withdrl_adm_no'] .'
					</span>
					</div>
					
					
					
	                <div class="col-sm-12" style="margin-top:15px !important;"><span>1. </span><span>Name of Pupil: '. $transfer_certificate[0]['s_name'] .'</span></div>
	                <div class="col-sm-12"><span>2. </span><span>Mothers Name: </span> '. $transfer_certificate[0]['s_m_name'].'</div>
					<div class="col-sm-12"><span>3. </span><span>Fathers Name: </span> '. $transfer_certificate[0]['s_f_name'] .'</div>
					<div class="col-sm-12"><span>4. </span><span>Date of Birth (in Christan Era) according to Admission & Withdrawal Register (in figures): </span>'.date("d-m-Y", strtotime($transfer_certificate[0]['dob'])).'
					</div> <!-- <p>in words </p> -->
					<div class="col-sm-12"><span>5. </span><span>Nationality: </span>'. $transfer_certificate[0]['national'] .'</div>
					<div class="col-sm-12"><span>6. </span><span>Whether the candidate belongs to Schedule Caste or Schedule Tribe or OBC: </span> '. $transfer_certificate[0]['cat'] .'</div>
					<div class="col-sm-12"><span>7. </span><span>Date of first admission in the school with class: '.date("d-m-Y", strtotime($transfer_certificate[0]['adm_dt'])).'  '. $f_class[0]['c_name']. ' '.$f_class[0]['c_section'].'</span></div>
					<div class="col-sm-12"><span>8. </span><span>Class in which the pupil last studied (in figures): '. $l_class[0]['c_name']. ' '.$l_class[0]['c_section'] .'</span></div>
					<div class="col-sm-12"><span>9. </span><span>School/Board Annual Examination last taken with result: </span> '. $transfer_certificate[0]['l_exm_resl'] .'</div>
					<div class="col-sm-12"><span>10. </span><span>Whether failed, if so once/twice in the same class: </span> '. $transfer_certificate[0]['fld_class'].'</div>
					<div class="col-sm-12"><span>11. </span><span>Subject Studied: </span> '. $transfer_certificate[0]['subjects'] .'</div>
					<div class="col-sm-12"><p><span>12. </span>Whether qualified for promotion to the higher class: </p>
					<p><span>If so, to which class (in figures) </span> '. $transfer_certificate[0]['prmt_class'] .'</p></div>
					
					<div class="col-sm-12"><span>13. </span><span>Month upto which the pupil has paid school dues: </span> '. $transfer_certificate[0]['fee_pd_till_mon'] .'</div>
					<div class="col-sm-12"><span>14. </span><span>Any fee concession availed of. If so, the nature of such concession: </span> '. $transfer_certificate[0]['consession'] .'</div>
					<div class="col-sm-12"><span>15. </span><span>Total No. of working days in the academic session: </span> '. $transfer_certificate[0]['t_no_days'] .'</div>
					<div class="col-sm-12"><span>16. </span><span>Total No. of working days pupil present in the school: </span> '. $transfer_certificate[0]['t_no_p_day'] .'</div>
					<div class="col-sm-12"><span>17. </span><span>Whether NCC Cadet/Boy Scout/Girl Guide (details may be given): </span> '. $transfer_certificate[0]['ncc'] .'</div>
					<div class="col-sm-12"><span>18. </span><span>Games played or extra curricular activities in which pupil usually took part(mention achievement level therein): </span> '. $transfer_certificate[0]['game'] .'</div>
					<div class="col-sm-12"><span>19. </span><span>General Conduct: </span> '. $transfer_certificate[0]['g_con'] .'</div>
					<div class="col-sm-12"><span>20. </span><span>Date of application of certificate: </span> '. date("d-m-Y",strtotime($transfer_certificate[0]['tc_app_dt'])) .'</div>
					<div class="col-sm-12"><span>21. </span><span>Date of issue of certificate: '. date("d-m-Y", strtotime($transfer_certificate[0]['tc_dt'])).'</span></div>
					<div class="col-sm-12"><span>22. </span><span>Reason for leaving the school: </span> '.$transfer_certificate[0]['slc_reason'] .'</div>
					<div class="col-sm-12"><span>23. </span><span>Any other remarks: </span> '. $transfer_certificate[0]['remarks'] .'</div>
									
					<div class="row">
	                			<div class="col-sm-4 text-center mt-5 ht" >
	                			  <p><b>Signature of Class Teacher</b></p>
	                			</div>
	                			<div class="col-sm-4 text-center mt-5 ht"><p><b>Checked By</b></p>
						  <p><b>(With full name and Designation)</b></p></div>
						<div class="col-sm-4 text-center mt-5 ht" >
						  <p><b>Signature of Principal with</b></p>
						  <p><b>date & School Seal</b></p>
						</div>
					</div>
					
					
					
	                
	            </div>    
	          
                        	
            </div>
		</div>
	</div>
</div>
				');
                
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
                // Render the HTML as PDF
                $dompdf->render();
                
                // Output the generated PDF to Browser
                $dompdf->stream();
            
            }



	    public function getfileno()
            {
               if ($this->request->is('ajax') && $this->request->is('post'))
		{

		    $transfer_table = TableRegistry::get('tc');
                    $class_table = TableRegistry::get('class');

		    $compid =$this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

		$file_no = '';

		$transfer_file_no = $transfer_table->find()->select(['file_no'])->where(['school_id'=>$compid , 'session_id' => $session_id ])->last();
		
		if(!empty($transfer_file_no)){
			$file_no = $transfer_file_no['file_no'];
			$file_no++;
		}
		else{
			$file_no = 1;
			
		}

		$data = ['file_no' => $file_no ];
					
		return $this->json($data);

                }
            }


	public function test($id = null)
            {
                
                
                $dompdf = new Dompdf();
                $dompdf->loadHtml('hello world');

                
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
                // Render the HTML as PDF
                $dompdf->render();
                
                // Output the generated PDF to Browser
                $dompdf->stream();
            
            }




}
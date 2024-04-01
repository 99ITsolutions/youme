<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
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
 * Static content controller *
 * This controller will render views from Template/Pages/ *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */


class FeeGenerationController  extends AppController
{
	public function index()
	{   
		$feereceipt_table = TableRegistry::get('feereceipt');
		$school_id = $this->request->session()->read('company_id');
		$class_table = TableRegistry::get('class');		
		$session_id = $this->Cookie->read('sessionid');
		$feecoll_table = TableRegistry::get('fee_collection_detail');
		
        $retrieve_receipt = $feereceipt_table->find()->select(['feereceipt.id' , 'feereceipt.acc_no' , 'feereceipt.adm_no',  'class.c_name', 'class.c_section', 'student.s_name', 'student.s_f_name', 'student.acc_no'])->join(['class' => 
		[
			'table' => 'class',
			'type' => 'LEFT',
			'conditions' => 'class.id = feereceipt.class'
		]])->join(['student' => 
		[
			'table' => 'student',
			'type' => 'LEFT',
			'conditions' => 'student.adm_no = feereceipt.adm_no'
		] ])->where([ 'feereceipt.school_id '=> $school_id ,'feereceipt.session_id '=> $session_id ])->GROUP(['feereceipt.id'])->toArray() ;

		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $school_id ])->toArray();	

		foreach($retrieve_receipt as $key =>$feecoll)
		{
			$acc_no = $feecoll['acc_no'];
			
			$retrieve_coll = $feecoll_table->find()->select(['id' ])->where(['session_id'=>$session_id ,'school_id' => $school_id, 'acc_no' => $acc_no])->toArray();	
			foreach($retrieve_coll as $coll)
			{
				$feecoll->collected_fee = $coll['id'];
			}
			

			
			
			
		}
		

		$this->set("class_details", $retrieve_class);
		$this->set("receipt_details", $retrieve_receipt); 
		$this->viewBuilder()->setLayout('user');
	}

	public function genfeereceipt()
	{   
		if ($this->request->is('post'))
		{
			//print_r($_POST); 
			$compid = $this->request->session()->read('company_id');
			$feereceipt_table = TableRegistry::get('feereceipt');
			$student_table = TableRegistry::get('student');
			$activ_table = TableRegistry::get('activity');
			$session_id = $this->Cookie->read('sessionid');	
			$feegen_table = TableRegistry::get('fees_generated');
			$feehead_table = TableRegistry::get('feehead');
					

			$adm_no = $this->request->data('adm_no');
			$class = $this->request->data('class');
			$acc_no = $this->request->data('acc_no');
			$s_name = $this->request->data('s_name');
			
			$fee_amt = implode(",",$this->request->data['structure_amt']);
			$dis_amt = implode(",",$this->request->data['discount_amt']);
			$amount = $this->request->data('amount');
			$feehead = $this->request->data('fee_h_id');
			$student_id = $this->request->data('sibl_in_scl');
			

			if(($this->request->data('dis_code')) != "")
			{
				$dis_code = $this->request->data('dis_code');
			}
			else
			{
				$dis_code = "";
			}					

			if(($this->request->data('transport')) != "")
			{
				$transport = $this->request->data('transport');
			}
			else
			{
				$transport = "";
			}					

			if(($this->request->data('stoppage')) != 0)
			{
				$feehead .= ",Transport Charges";
			}
			else
			{
				$stoppage = "";
			}

			if(($this->request->data('t_charges')) != "")
			{
				$t_charges = $this->request->data('t_charges');
			}

			else
			{
				$t_charges = "";
			}
			
			
			
			$retrieve_recipt = $feereceipt_table->find()->select(['id'  ])->where(['adm_no' => $adm_no, 'school_id' => $compid , 'session_id' => $session_id  ])->count() ;                    

			if($retrieve_recipt == 0 )
			{

				$feereceipt = $feereceipt_table->newEntity();
				$feereceipt->adm_no =  $adm_no;
				$feereceipt->class =  $class;
				$feereceipt->school_id = $compid;
				$feereceipt->acc_no = $acc_no;
				$feereceipt->s_name = $s_name;
				$feereceipt->fee_h_id = $feehead;
				$feereceipt->fee_s_amt = $fee_amt;
				$feereceipt->discount_id = $dis_code;
				$feereceipt->transport = $transport;
				//$feereceipt->stoppage = $stoppage;
				$feereceipt->t_charges = $t_charges;							
				$feereceipt->session_id =  $session_id;
				$feereceipt->student_id = $student_id;
				

				if($saved = $feereceipt_table->save($feereceipt) ){

					$Rid = $saved->id;
					$feereceipt_table->query()->update()->set([ 't_charges' => $t_charges, 'transport' => $transport ,'discount_id' => $dis_code ,'fee_s_amt'=>$fee_amt, 'fee_h_id' => $feehead , 's_name'=> $s_name , 'acc_no'=>$acc_no , 'total_amount' => $amount, 'discount_amount' => $dis_amt, 'student_id' => $student_id])->where([ 'id' => $Rid  ])->execute();
					
					
					$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";		
					$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['id' => $Rid])->toArray();
					$feerece_id = $retrieve_receipt[0]['id'];		
					$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
					$feehead = explode(",", $fee_h_id);		
					if($feehead != "Transport Charges")
					{
						$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
					}
					
					$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
					$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
					$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
					$count = count($feehead);
					
					$mnth = explode(",", $SelMonths);
					foreach($mnth as $mn)
					{
						
						$totalamt = "";
						$disamt = "";
						$headid = "";
						for($i = 0; $i < $count; $i++)
						{ 
							if($feehead[$i] != "Transport Charges") 
							{
								foreach($retrieve_feehead as $val)
								{
									
									if($val['id'] == $feehead[$i])
									{
										
										$head_months = explode(",",$val['months']);
										if(in_array($mn, $head_months))
										{
											$heads = $val['id'].",";		
										}
										else
										{
											$heads = "";
										}							
									}
								}														
							}
							else
							{
								$trans = '%transport%';
								$retrieve_feehead1 = $feehead_table->find()->select(['months'])->where(['session_id'=>$session_id ,'school_id' => $compid, 'head_name LIKE ' => $trans ])->toArray();
								
								
								foreach($retrieve_feehead1 as $val1)
								{
									$head_months1= explode(",",$val1['months']);
									if(in_array($mn, $head_months1))
									{
										$heads = "Transport Charges";		
									}
									else
									{
										$heads = "";
									}	
								}
								
							}		
					
							$mns = $mn;
							if($heads != "")
							{
								$headid .= $heads;
								$totalamt .= $feesamt[$i].",";
								$disamt .= $discount[$i].",";
							}
							else
							{
								$totalamt .= "0,";
								$disamt .= "0,";
							}
							
							
								
							
							
						}	
						$tamt = explode(",", $totalamt);
						$damt = explode(",", $disamt);
						$discountsum = array_sum($damt);
						$feesum = array_sum($tamt);
						
						$totlsum = $feesum - $discountsum ;
						
						$feegen = $feegen_table->newEntity();
						
						//$feegen->adm_no =  $adm_no;
						
						//$feegen->acc_no = $acc_no;
						$feegen->month = $mn;
						$feegen->fee_h_id = $headid;
						$feegen->fee_s_amt = $totalamt;
						$feegen->discount_amt = $disamt;
						$feegen->total_amt = $totlsum;
						$feegen->final_amt = $feesum;
						$feegen->total_dis_amt = $discountsum;
						$feegen->feereceipt_id = $feerece_id;							
						$feegen->session =  $session_id;
						$feegen->student_id = $student_id;
						$feegen->class =  $class;
						$feegen->school_id = $compid;
						$feegen->status = 0;
						$feegen->created_date = time();
						
						
						$savedd = $feegen_table->save($feegen);
						/*if($savedd)
						{
							$id = $savedd->id;
							$update = $feegen_table->query()->update()->set([ 'month' => $mn ])->where([ 'id' => $id  ])->execute();
						}*/
					}	
					
					

					$activity = $activ_table->newEntity();
					$activity->action =  "Fee receipt Created"  ;
					$activity->ip =  $_SERVER['REMOTE_ADDR'] ;       

					$activity->value = md5($saved->id)   ;
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
				else{
					$res = [ 'result' => 'Receipt not generated'  ];
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

			

	public function editgenfeereceipt()
	{   
		if ($this->request->is('post'))
		{
			
			$compid = $this->request->session()->read('company_id');
			$feereceipt_table = TableRegistry::get('feereceipt');
			$student_table = TableRegistry::get('student');
			$activ_table = TableRegistry::get('activity');
			$session_id = $this->Cookie->read('sessionid');	
			$feehead_table = TableRegistry::get('feehead');		
			$feegen_table = TableRegistry::get('fees_generated');
			$adm_no = $this->request->data('adm_no');
			$class = $this->request->data('class'); 
			$acc_no = $this->request->data('acc_no');
			$s_name = $this->request->data('s_name');
			
			$fee_amt = implode(",",$this->request->data['structure_amt']);
			$dis_amt = implode(",",$this->request->data['discount_amt']);
			$amount = $this->request->data('amount');
			$feehead = $this->request->data('fee_h_id');
			
			$student_id = $this->request->data('sibl_in_scl');
			
			$receiptID = $this->request->data('feerec_id');
			

			if(($this->request->data('dis_code')) != "")
			{
				$dis_code = $this->request->data('dis_code');
			}
			else
			{
				$dis_code = "";
			}							

			if(($this->request->data('stoppage')) != 0)
			{
				$feehead .= ",Transport Charges";
			}

			
			$update = $feereceipt_table->query()->update()->set([ 'discount_id' => $dis_code ,'fee_s_amt'=>$fee_amt, 'fee_h_id' => $feehead , 's_name'=> $s_name , 'acc_no'=>$acc_no , 'adm_no'=>$adm_no , 'class'=>$class , 'total_amount' => $amount, 'discount_amount' => $dis_amt, 'student_id' => $student_id])->where([ 'id' => $receiptID  ])->execute();
			if($update)
			{		
		
				$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";		
					$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['id' => $receiptID])->toArray();
					$feerece_id = $retrieve_receipt[0]['id'];		
					$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
					$feehead = explode(",", $fee_h_id);		
					if($feehead != "Transport Charges")
					{
						$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
					}
					
					$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
					$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
					$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
					$count = count($feehead);
					
					$mnth = explode(",", $SelMonths);
					foreach($mnth as $mn)
					{
						
						$totalamt = "";
						$disamt = "";
						$headid = "";
						for($i = 0; $i < $count; $i++)
						{ 
							if($feehead[$i] != "Transport Charges") 
							{
								foreach($retrieve_feehead as $val)
								{
									
									if($val['id'] == $feehead[$i])
									{
										
										$head_months = explode(",",$val['months']);
										if(in_array($mn, $head_months))
										{
											$heads = $val['id'].",";		
										}
										else
										{
											$heads = "";
										}							
									}
								}														
							}
							else
							{
								$trans = '%transport%';
								$retrieve_feehead1 = $feehead_table->find()->select(['months'])->where(['session_id'=>$session_id ,'school_id' => $compid, 'head_name LIKE ' => $trans ])->toArray();
								
								
								foreach($retrieve_feehead1 as $val1)
								{
									$head_months1 = explode(",",$val1['months']);
									if(in_array($mn, $head_months1))
									{
										$heads = "Transport Charges";		
									}
									else
									{
										$heads = "";
									}	
								}
							}		
					
							$mns = $mn;
							if($heads != "")
							{
								$headid .= $heads;
								$totalamt .= $feesamt[$i].",";
								$disamt .= $discount[$i].",";
							}
							else
							{
								$totalamt .= "0,";
								$disamt .= "0,";
							}
							
							
								
							
							
						}	
						$tamt = explode(",", $totalamt);
						$damt = explode(",", $disamt);
						$discountsum = array_sum($damt);
						$feesum = array_sum($tamt);
						
						$totlsum = $feesum - $discountsum ;
						
						
						$update = $feegen_table->query()->update()->set([ 'class' => $class, 'fee_h_id' => $headid, 'fee_s_amt' => $totalamt, 'discount_amt' => $disamt, 'total_amt' => $totlsum, 'final_amt' => $feesum, 'total_dis_amt' => $discountsum])->where(['school_id' => $compid, 'student_id' => $student_id, 'month' => $mn , 'feereceipt_id' => $feerece_id])->execute();
						
													
						
						
					}	
		
		
				$activity = $activ_table->newEntity();
				$activity->action =  "Fee receipt Updated"  ;
				$activity->ip =  $_SERVER['REMOTE_ADDR'] ;       

				$activity->value = md5($receiptID)   ;
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
			else{
				$res = [ 'result' => 'Receipt not generated'  ];
			}
				
			
		}
		else{
			$res = [ 'result' => 'invalid operation'  ];
		}
		
		//print_r($res);
		return $this->json($res);

    }

	public function add()
	{   

		$compid =$this->request->session()->read('company_id');

		$feereceipt_table = TableRegistry::get('feereceipt');

		$class_table = TableRegistry::get('class');

		$discount_table = TableRegistry::get('discount');

		$stoppage_table = TableRegistry::get('routechg');

		$feehead_table = TableRegistry::get('feehead');
		
		$student_table = TableRegistry::get('student');

		$adm_no = $this->request->data('admNo');

		$class = $this->request->data('class');
		

		$session = $this->request->data('sess_name');
		$session_id = $this->Cookie->read('sessionid');



		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['session_id'=>$session_id , 'school_id' => $compid ])->toArray();

		$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();

		$retrieve_discount = $discount_table->find()->select(['id' , 'dscr', 'percentage', 'code'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();

		$retrieve_stoppage = $stoppage_table->find()->select(['id' , 'village'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		

		$this->set("stoppage_details", $retrieve_stoppage);

		$this->set("class_details", $retrieve_class);

		$this->set("discount_details", $retrieve_discount);

		$this->set("feehead_details", $retrieve_feehead);
		
		
		$setting_table = TableRegistry::get('stdnt_h_setting');
					
		$student = array();
					   
		$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid , 'session_id'=>$session_id])->toArray();
					
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
		])->where(['student.school_id'=> $compid , 'student.session_id'=>$session_id, 'student.stud_left !='=> 'Yes' ])->toArray(); 
		
		$this->set("sibling_details", $retrieve_siblings);

		$this->viewBuilder()->setLayout('user');

	}

	

	public function edit($id)
	{   

		$compid =$this->request->session()->read('company_id');
		$feereceipt_table = TableRegistry::get('feereceipt');
		$class_table = TableRegistry::get('class');
		$discount_table = TableRegistry::get('discount');
		$stoppage_table = TableRegistry::get('routechg');
		$feehead_table = TableRegistry::get('feehead');
		$session_table = TableRegistry::get('session');
		$feereceipt_table = TableRegistry::get('feereceipt');
		$session_id = $this->Cookie->read('sessionid');



		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $compid ])->toArray();

		$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name'])->where(['school_id' => $compid ])->toArray();

		$retrieve_discount = $discount_table->find()->select(['id' , 'dscr', 'percentage', 'code'])->where(['school_id' => $compid , 'session_id' => $session_id])->toArray();

		$retrieve_stoppage = $stoppage_table->find()->select(['id' , 'village'])->where(['school_id' => $compid ])->toArray();

		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no',  'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount', 'student_id'])->where(['school_id' => $compid, 'md5(id)' => $id ])->toArray();

		

		$this->set("receipt_details", $retrieve_receipt);
		$this->set("stoppage_details", $retrieve_stoppage);
		$this->set("class_details", $retrieve_class);
		$this->set("discount_details", $retrieve_discount);
		$this->set("feehead_details", $retrieve_feehead);
		
		
		$setting_table = TableRegistry::get('stdnt_h_setting');
		$student_table = TableRegistry::get('student');
					
		$student = array();
					   
		$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid , 'session_id'=>$session_id])->toArray();
					
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
		])->where(['student.school_id'=> $compid , 'student.session_id'=>$session_id, 'student.stud_left !='=> 'Yes' ])->toArray(); 
		
		$this->set("sibling_details", $retrieve_siblings);
		
		
		$this->viewBuilder()->setLayout('user');

	}			

	public function getstoppagecharges()
	{   
		$compid =$this->request->session()->read('company_id');
		$stoppage_table = TableRegistry::get('routechg');	
		$stopId = $this->request->data('stopgeId');          
		$stoppage_charges = $stoppage_table->find()->select(['amount'])->where(['school_id' => $compid, 'id' => $stopId])->toArray();
		return $this->json($stoppage_charges);
	}

			

	public function getfeestructure()
	{   
		$this->viewBuilder()->layout(false);			

		$classId = $this->request->data('classId');
		$discountCode = $this->request->data('discountCode');
		$sessName = $this->Cookie->read('sessionid');	
		//$sessName = $this->request->data('sessName');
		$compid =$this->request->session()->read('company_id');	
		$feehead_table = TableRegistry::get('feehead');
		$feesetup_table = TableRegistry::get('feesetup');
		$feedetail_table = TableRegistry::get('feedetail');		
		$discount_table = TableRegistry::get('discount');				

		$get_fees = $feesetup_table->find()->select(['feesetup.id', 'feedetail.amount', 'feedetail.id', 'feehead.head_name', 'feehead.student_prefix', 'feehead.id'])->join([
		'feedetail' => 
			[
				'table' => 'feedetail',
				'type' => 'LEFT',
				'conditions' => 'feedetail.fee_s_id = feesetup.id'
			],
			'feehead' => 
			[
				'table' => 'feehead',
				'type' => 'LEFT',
				'conditions' => 'feehead.id = feedetail.fee_h_id'
			]
		])->where(['feesetup.class ' => $classId , 'feesetup.session_id'=> $sessName, 'feesetup.school_id'=> $compid ])->toArray();
		
		$discounted = $discount_table->find()->select(['percentage'])->where(['discount.code ' => $discountCode , 'discount.session_id'=> $sessName, 'discount.school_id'=> $compid ])->toArray();

			

		//$data = ['feedetail_id' => $get_fees['feedetail']['id'], 'feehead_id' => $get_fees['feehead']['id'], 'feeset_id' => $get_fees['id'], 'amount' => $get_fees['feedetail']['amount'], 'head_name' => $get_fees['feehead']['head_name']];

		$data = ['feedetails' => $get_fees, 'discount_per' => $discounted];

		return $this->json($data);

	}

	public function getstudentdetails()
	{   

		$this->viewBuilder()->layout(false);
		$stdID = $this->request->data('stdID');
		$compid =$this->request->session()->read('company_id');
		$student_table = TableRegistry::get('student');	
		$session_id = $this->Cookie->read('sessionid');	

		$get_name = $student_table->find()->select(['student.id', 'student.s_name', 'student.s_f_name', 'student.acc_no', 'student.adm_no', 'student.class', 'student.dis_code', 'student.stopage', 'student.april', 'student.may', 'student.june', 'student.july', 'student.aug','student.sep', 'student.oct','student.nov', 'student.december','student.jan','student.feb','student.mar', 'student.prefix'])->where(['student.id ' => $stdID , 'student.school_id'=> $compid ])->toArray(); 
		
		
		return $this->json($get_name);

	}
	
	public function getdiscount()
	{   

		$this->viewBuilder()->layout(false);
		$classId = $this->request->data('classId');
		$discountCode = $this->request->data('discountCode');
		$compid =$this->request->session()->read('company_id');
		$discount_table = TableRegistry::get('discount');	
		$session_id = $this->Cookie->read('sessionid');

		$feehead_table = TableRegistry::get('feehead');
		$feesetup_table = TableRegistry::get('feesetup');
		$feedetail_table = TableRegistry::get('feedetail');				

		$get_fees = $feesetup_table->find()->select(['feesetup.id', 'feedetail.amount', 'feedetail.id', 'feehead.head_name', 'feehead.id'])->join([
		'feedetail' => 
			[
				'table' => 'feedetail',
				'type' => 'LEFT',
				'conditions' => 'feedetail.fee_s_id = feesetup.id'
			],
			'feehead' => 
			[
				'table' => 'feehead',
				'type' => 'LEFT',
				'conditions' => 'feehead.id = feedetail.fee_h_id'
			]
		])->where(['feesetup.class ' => $classId , 'feesetup.session_id'=> $session_id, 'feesetup.school_id'=> $compid ])->toArray();
		
		$discounted = $discount_table->find()->select(['percentage'])->where(['discount.code ' => $discountCode , 'discount.session_id'=> $session_id, 'discount.school_id'=> $compid ])->toArray();
		foreach($get_fees as $fees)
		{		
			//print_r($fees['feehead']['head_name']);
			if($fees['feehead']['head_name'] == "Tuition Fee")
			{
				$headid = $fees['feehead']['id'];
				$headamt = $fees['feedetail']['amount'];
				
				$discount_per = $discounted[0]['percentage'];
				$discount_price = ($discount_per*$headamt)/100;
				
			}
			
		}
		
		$data = ['headid' => $headid, 'discount_price' => $discount_price];
		
		
		
		
		return $this->json($data);

	}
	
	public function allstudentreceipt()
	{
		 $school_id = $this->request->session()->read('company_id');
		 $session_id = $this->Cookie->read('sessionid');
		
		$student_table = TableRegistry::get('student');
		

		$getstudentdetails = $student_table->find()->select(['student.id', 'student.s_name', 'student.acc_no', 'student.class', 'student.dis_code', 'student.stopage', 'student.adm_no', 'student.prefix'])->where(['student.session_id ' => $session_id , 'student.school_id'=> $school_id ])->toArray(); 
		
		
		foreach($getstudentdetails as $studentdetail):
			$adm_no = $studentdetail['adm_no'];
			$stdid = $studentdetail['id'];
			$acc_no = $studentdetail['acc_no'];
			$s_name = $studentdetail['s_name'];
			$classId = $studentdetail['class'];
			$dis_code = $studentdetail['dis_code'];
			$stopage = $studentdetail['stopage'];
			$prefix = $studentdetail['prefix'];
			
			if($stopage != null || $stopage != 0) 
			{
				$stoppage_table = TableRegistry::get('routechg');					
				$stoppage_charges = $stoppage_table->find()->select(['amount'])->where(['school_id' => $school_id, 'id' => $stopage])->toArray();
				
				$transport_amt = $stoppage_charges[0]['amount'];
			}
			

			
			$feehead_table = TableRegistry::get('feehead');
			$feesetup_table = TableRegistry::get('feesetup');
			$feedetail_table = TableRegistry::get('feedetail');
			$feegen_table = TableRegistry::get('fees_generated');	

			$getfeesdetails = $feesetup_table->find()->select(['feesetup.id', 'feedetail.amount', 'feedetail.id', 'feehead.head_name', 'feehead.student_prefix', 'feehead.id'])->join([
			'feedetail' => 
				[
					'table' => 'feedetail',
					'type' => 'LEFT',
					'conditions' => 'feedetail.fee_s_id = feesetup.id'
				],
				'feehead' => 
				[
					'table' => 'feehead',
					'type' => 'LEFT',
					'conditions' => 'feehead.id = feedetail.fee_h_id'
				]
			])->where(['feesetup.class ' => $classId , 'feesetup.session_id'=> $session_id, 'feesetup.school_id'=> $school_id ])->toArray();
			$fee_amt = [];			
			$fee_head =[];
			
			foreach($getfeesdetails as $feedetail):
			
				$student_prefix = explode(",",$feedetail['feehead']['student_prefix']);
				
				if(in_array($prefix, $student_prefix))
				{			
					$fee_amt[] = $feedetail['feedetail']['amount'];
					$fee_head[] = $feedetail['feehead']['id'];
				}
			
			endforeach;		
			
			 $fee_s_amt  = implode(",", $fee_amt);
			 $fee_h_id  = implode(",", $fee_head);
			 
			if($stopage != null || $stopage != 0) 
			{
				$fee_s_amt  .= ",".$transport_amt;
				$fee_h_id  .= ",Transport Charges";						
			}
			
			$strture_amt = explode(",", $fee_s_amt);
			$count = count($strture_amt);
			for($i = 0; $i < $count; $i++ )
			{
				$disc[] = 0;
			}
			
			
			$dis_amt  = implode(",", $disc);
			
			 $sum_discount = array_sum($disc);
			 $sum_feeamt = array_sum($strture_amt);
			 
			
			 $amount = $sum_feeamt - $sum_discount;
			
			
			
			
			$feereceipt_table = TableRegistry::get('feereceipt');	
			
			$retrieve_recipt = $feereceipt_table->find()->select(['id'  ])->where(['adm_no' => $adm_no, 'school_id' => $school_id , 'session_id' => $session_id  ])->count() ; 

			$retrieve_reciptid = $feereceipt_table->find()->select(['id'  ])->where(['adm_no' => $adm_no, 'school_id' => $school_id , 'session_id' => $session_id  ])->toArray() ; 

			foreach($retrieve_reciptid as $reciptid)
			{
				$receiptID = $reciptid['id'];
			}
			if($retrieve_recipt == 0 )
				{
					$feereceipt = $feereceipt_table->newEntity();
					$feereceipt->adm_no =  $adm_no;
					$feereceipt->class =  $classId;
					$feereceipt->school_id = $school_id;
					$feereceipt->acc_no = $acc_no;
					$feereceipt->s_name = $s_name;
					$feereceipt->fee_h_id = $fee_h_id;
					$feereceipt->fee_s_amt = $fee_s_amt;
					$feereceipt->discount_id = $dis_code;						
					$feereceipt->session_id =  $session_id;
					$feereceipt->student_id = $stdid;
			

				if($saved = $feereceipt_table->save($feereceipt) ){

					$Rid = $saved->id;
					$update = $feereceipt_table->query()->update()->set([ 'discount_id' => $dis_code ,'fee_s_amt'=>$fee_s_amt, 'fee_h_id' => $fee_h_id , 's_name'=> $s_name , 'acc_no'=>$acc_no , 'total_amount' => $amount, 'discount_amount' => $dis_amt])->where([ 'id' => $Rid  ])->execute();
				
				
				
				
					$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";		
					$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['id' => $Rid])->toArray();
					$feerece_id = $retrieve_receipt[0]['id'];		
					$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
					$feehead = explode(",", $fee_h_id);		
					if($feehead != "Transport Charges")
					{
						$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $school_id ])->toArray();
					}
					
					$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
					$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
					$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
					$count = count($feehead);
					
					$mnth = explode(",", $SelMonths);
					foreach($mnth as $mn)
					{
						
						$totalamt = "";
						$disamt = "";
						$headid = "";
						for($i = 0; $i < $count; $i++)
						{ 
							if($feehead[$i] != "Transport Charges") 
							{
								foreach($retrieve_feehead as $val)
								{
									
									if($val['id'] == $feehead[$i])
									{
										
										$head_months = explode(",",$val['months']);
										if(in_array($mn, $head_months))
										{
											$heads = $val['id'].",";		
										}
										else
										{
											$heads = "";
										}							
									}
								}														
							}
							else
							{
								$trans = '%transport%';
								$retrieve_feehead1 = $feehead_table->find()->select(['months'])->where(['session_id'=>$session_id ,'school_id' => $compid, 'head_name LIKE ' => $trans ])->toArray();
								
								
								foreach($retrieve_feehead1 as $val1)
								{
									$head_months1 = explode(",",$val1['months']);
									if(in_array($mn, $head_months1))
									{
										$heads = "Transport Charges";		
									}
									else
									{
										$heads = "";
									}	
								}
							}		
					
							$mns = $mn;
							if($heads != "")
							{
								$headid .= $heads;
								$totalamt .= $feesamt[$i].",";
								$disamt .= $discount[$i].",";
							}
							else
							{
								$totalamt .= "0,";
								$disamt .= "0,";
							}
							
							
								
							
							
						}	
						$tamt = explode(",", $totalamt);
						$damt = explode(",", $disamt);
						$discountsum = array_sum($damt);
						$feesum = array_sum($tamt);
						
						$totlsum = $feesum - $discountsum ;
						
						$feegen = $feegen_table->newEntity();
						
						//$feegen->adm_no =  $adm_no;
						
						//$feegen->acc_no = $acc_no;
						$feegen->month = $mn;
						$feegen->fee_h_id = $headid;
						$feegen->fee_s_amt = $totalamt;
						$feegen->discount_amt = $disamt;
						$feegen->total_amt = $totlsum;
						$feegen->final_amt = $feesum;
						$feegen->total_dis_amt = $discountsum;
						$feegen->feereceipt_id = $feerece_id;							
						$feegen->session =  $session_id;
						$feegen->student_id = $stdid;
						$feegen->class =  $classId;
						$feegen->school_id = $school_id;
						$feegen->status = 0;
						$feegen->created_date = time();
						
						
						$savedd = $feegen_table->save($feegen);
						/*if($savedd)
						{
							$id = $savedd->id;
							$update = $feegen_table->query()->update()->set([ 'month' => $mn ])->where([ 'id' => $id  ])->execute();
						}*/
					}	
				
					$res = [ 'result' => 'success'  ];    
				
				}
				else{
					$res = [ 'result' => 'Receipt not generated'  ];
				}
			}
			else
			{
				$update = $feereceipt_table->query()->update()->set([ 'discount_id' => $dis_code ,'fee_s_amt'=>$fee_s_amt, 'fee_h_id' => $fee_h_id , 's_name'=> $s_name , 'acc_no'=>$acc_no , 'adm_no'=>$adm_no , 'class'=>$classId , 'total_amount' => $amount, 'discount_amount' => $dis_amt])->where([ 'adm_no' => $adm_no  ])->execute();
				
				if($update){
					
					$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";		
					$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'student_id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['id' => $receiptID])->toArray();
					$feerece_id = $retrieve_receipt[0]['id'];		
					$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
					$feehead = explode(",", $fee_h_id);		
					if($feehead != "Transport Charges")
					{
						$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $school_id ])->toArray();
					}
					
					$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
					$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
					$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
					$count = count($feehead);
					$class = $retrieve_receipt[0]['class'];
					$student_id = $retrieve_receipt[0]['student_id'];
					
					$mnth = explode(",", $SelMonths);
					foreach($mnth as $mn)
					{
						
						$totalamt = "";
						$disamt = "";
						$headid = "";
						for($i = 0; $i < $count; $i++)
						{ 
							if($feehead[$i] != "Transport Charges") 
							{
								foreach($retrieve_feehead as $val)
								{
									
									if($val['id'] == $feehead[$i])
									{
										
										$head_months = explode(",",$val['months']);
										if(in_array($mn, $head_months))
										{
											$heads = $val['id'].",";		
										}
										else
										{
											$heads = "";
										}							
									}
								}														
							}
							else
							{
								$trans = '%transport%';
								$retrieve_feehead1 = $feehead_table->find()->select(['months'])->where(['session_id'=>$session_id ,'school_id' => $compid, 'head_name LIKE ' => $trans ])->toArray();
								
								
								foreach($retrieve_feehead1 as $val1)
								{
									$head_months1 = explode(",",$val1['months']);
									if(in_array($mn, $head_months1))
									{
										$heads = "Transport Charges";		
									}
									else
									{
										$heads = "";
									}	
								}
							}		
					
							$mns = $mn;
							if($heads != "")
							{
								$headid .= $heads;
								$totalamt .= $feesamt[$i].",";
								$disamt .= $discount[$i].",";
							}
							else
							{
								$totalamt .= "0,";
								$disamt .= "0,";
							}
							
							
								
							
							
						}	
						$tamt = explode(",", $totalamt);
						$damt = explode(",", $disamt);
						$discountsum = array_sum($damt);
						$feesum = array_sum($tamt);
						
						$totlsum = $feesum - $discountsum ;
						
						
						$update = $feegen_table->query()->update()->set([ 'class' => $class, 'fee_h_id' => $headid, 'fee_s_amt' => $totalamt, 'discount_amt' => $disamt, 'total_amt' => $totlsum, 'final_amt' => $feesum, 'total_dis_amt' => $discountsum])->where(['school_id' => $school_id, 'student_id' => $student_id, 'month' => $mn , 'feereceipt_id' => $feerece_id])->execute();
						
													
						
						
					}	
				
					$res = [ 'result' => 'success'  ];    
				
				}
				else{
					$res = [ 'result' => 'Receipt not generated'  ];
				}
			}
			
		endforeach;		
		return $this->json($res); 		
	}
	
	public function cakePdf()
	{
		$CakePdf = new \CakePdf\Pdf\CakePdf();
		$CakePdf->template('cake_pdf', 'default');
		$CakePdf->viewVars($this->viewVars);
		$pdf = $CakePdf->write(APP . 'Files' . DS . 'Output.pdf');
		echo $pdf;
		die();	

	}

	/*public function pdf($fid = null)
	{
		
		$compid =$this->request->session()->read('company_id');
		$feereceipt_table = TableRegistry::get('feereceipt');
		$class_table = TableRegistry::get('class');
		$discount_table = TableRegistry::get('discount');
		$stoppage_table = TableRegistry::get('routechg');
		$feehead_table = TableRegistry::get('feehead');
		$session_table = TableRegistry::get('session');
		$session_id = $this->Cookie->read('sessionid');
		$school_table = TableRegistry::get('company');
		
		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no',  'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['school_id' => $compid, 'md5(id)' => $fid ])->toArray();
		
		$fee_h_id = $retrieve_receipt[0]['fee_h_id'];
		$feehead = explode(",", $fee_h_id);		
		if($feehead != "Transport Charges")
		{
			$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		}
		
		$classId = $retrieve_receipt[0]['class'];
		
		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['id' => $classId ])->toArray();
		$school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
			'states' => 
				[
				'table' => 'states',
				'type' => 'LEFT',
				'conditions' => 'states.id = company.state'
			],
			'cities' => 
				[
				'table' => 'cities',
				'type' => 'LEFT',
				'conditions' => 'cities.id = company.city'
			]

		])->where(['company.id '=> $compid])->first() ;	
		$session_details = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
		
		 $disId =  $retrieve_receipt[0]['discount_id'];
		$retrieve_discount = $discount_table->find()->select(['id' , 'dscr', 'percentage', 'code'])->where(['code' => $disId ,'school_id' => $compid ])->toArray();		
		 
		$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
		$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
		$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
		$count = count($feehead);
		$rec_format = "";
		for($mnth = 1; $mnth <= 12; $mnth++)
		{ 
			
			if($mnth == 1){ $month = "January";}
			if($mnth == 2){ $month = "Feburary";}
			if($mnth == 3){ $month = "March";}
			if($mnth == 4){ $month = "April";}
			if($mnth == 5){ $month = "May";}
			if($mnth == 6){ $month = "June";}
			if($mnth == 7){ $month = "July";}
			if($mnth == 8){ $month = "August";}
			if($mnth == 9){ $month = "September";}
			if($mnth == 10){ $month = "October";}
			if($mnth == 11){ $month = "November";}
			if($mnth == 12){ $month = "December";}
			
			$tbl = "";
			$totalamt = "";
			$disamt = "";
			for($i = 0; $i < $count; $i++)
			{ 
				if($feehead[$i] != "Transport Charges") 
				{
					foreach($retrieve_feehead as $val)
					{
						
						
							if($val['id'] == $feehead[$i])
							{
								$head_months = explode(",",$val['months']);
								if(in_array($mnth, $head_months))
								{
									$head = $val['head_name'];		
								}
								else
								{
									$head = "";
								}								
							}
						
						
					}														
				}
				else
				{
					 $head = "Transport Charges";
				}		
				
		
				if(!empty($head))
				{
					$tbl .= '<tr>
						
						<td style="width: 50%;text-align: center;border: 1px solid #000;">'.ucfirst($head) .'</td>	
						<td style="width: 25%;text-align: center;border: 1px solid #000;">'. $feesamt[$i] .'</td>
						<td style="width: 25%;text-align: center;border: 1px solid #000;">'. $discount[$i] .'</td>
						
						</tr> ';
						
					$totalamt .= $feesamt[$i].",";
					$disamt .= $discount[$i].",";
				}	

				if(strtolower($head) == "tution fee")
				{
					$discount_apply = $feesamt[$i];
				}
			}	
			
		//echo $discount_apply; die;
		
		if($retrieve_receipt[0]['discount_id'] != 0 && $retrieve_receipt[0]['discount_amount']!= null)
		{
			$per = (int) $retrieve_discount[0]['percentage'];
			$discounted = (($per*$discount_apply)/100);
			
			$tbl .= '<tr>
						
						<td style="width: 50%;text-align: center;border: 1px solid #000;"> Discount (if available any) - '.$retrieve_discount[0]['dscr'].' ('.(int) $retrieve_discount[0]['percentage'].'%)</td>	
						<td style="width: 25%;text-align: center;border: 1px solid #000;"> 0 </td>
						<td style="width: 25%;text-align: center;border: 1px solid #000;">'. $discounted.'</td>
						
						</tr> ';
		}
		else
		{
			$discounted = 0;
			$tbl .= '<tr>
						
						<td style="width: 50%;text-align: center;border: 1px solid #000;"> Discount (if available any) - </td>	
						<td style="width: 25%;text-align: center;border: 1px solid #000;"> 0 </td>
						<td style="width: 25%;text-align: center;border: 1px solid #000;"> '. $discounted.' </td>
						
						</tr> ';
		}
		
		$tamt = explode(",", $totalamt);
		$damt = explode(",", $disamt);
		$discountsum = array_sum($damt);
		$feesum = array_sum($tamt);
		
		$totlsum = $feesum - $discountsum - $discounted;
		
		$rec_format .= '<div class="row" style="display:flex; width:100%; ">
			<div style="width: 25%;float: left;">
				<img src="img/'.$school_details["comp_logo"].'" height="80" width="80" alt="school logo">
			</div>
			<div style="text-align:center; width: 50%;float: left;">
				<span style="font-size:22px">'.$school_details['comp_name'].'</span><br>
				<span>'.$school_details['add_1']." ".$school_details['cities']['name']
					." ".$school_details['states']['name']."-".$school_details['zipcode'].'</span><br>
				<span>Email Address : '.$school_details['email'].'</span> 
			</div>
			<div style="text-align:center; width: 25%;float: left;">
				<span style="color:#ccc">'.$month.'</span>
			</div>	
							
		</div>

		<div class="row" style="width:100%;  text-align:center;  margin:20px 0 !important; clear:both">
			<h5 style="margin:20px 0 !important;">Fee Receipt ('.$session_details['startyear']."-". $session_details['endyear'].')</h5>
		</div>
		
		<div class="row" style="width:100% !important; display:flex; clear:both">							
			<div style="float:left; width:38% !important;">
				<div><span>Date: </span> '. date("d-m-Y", time()) .'</div>
				<div><span>Student: </span> '. ucfirst($retrieve_receipt[0]['s_name']) .'</div>
				<div><span>Payment Mode: </span> Cash</div>
				
			</div>
			<div style="float:left;  width:30% !important; ">
				<div><span>Admission No.: </span> '. $retrieve_receipt[0]['adm_no'] .'</div>
			</div>
			<div style="float:right; text-align:right; width:31% !important;">
				<div><span>Receipt No.: </span> '. $retrieve_receipt[0]['id'] .'</div>
				<div><span>Class: </span> '. $retrieve_class[0]['c_name']. ' '. $retrieve_class[0]['c_section'] .'</div>
				<div><span>Account No.: '. $retrieve_receipt[0]['acc_no'].'</div>
			</div>
		   
		</div> 
		
		<div class="row" style="width:100% !important; clear: both; ">
				<table style="width: 100%;">
					<thead style="  background: #212529;">
						<tr>
							
							<th width="50%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px ">Fee Name</th>
							<th width="25%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px">Amount</th>
							<th width="25%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px">Discount</th>
							
						</tr>
					</thead>
					<tbody>
						'. $tbl	.'
					</tbody>
				</table>
		</div>	
		<div class="row" style="width:100% !important; clear: both; ">
			<div style="float:left; text-align:center; width:50% !important; border: 1px solid #000; ">
				Net Payable Amount  (in Rupees):
			</div>
			<div style=" float:left; text-align:center; width:49% !important; border: 1px solid #000;">
				'. $totlsum .'
			</div>
		</div>
		
		<div class="row" style="width:100% !important; display:flex; clear: both; ">
			<div style= "text-align:right; ">
				<p style="margin-top: 60px;">........................................................................</p>
				<p>(Signature of the person issuing the receipt)</p>
			</div>
		</div>
		
		
		<div class="row" style="width:100% !important; height:50px !important; clear: both; margin:45px 0;">
			
		</div>
		
		'; 
		
		$rec_format .= '<div class="row" style="display:flex; width:100%; ">
			<div style="width: 25%;float: left;">
				<img src="img/'.$school_details["comp_logo"].'" height="80" width="80" alt="school logo">
			</div>
			<div style="text-align:center; width: 50%;float: left;">
				<span style="font-size:22px">'.$school_details['comp_name'].'</span><br>
				<span>'.$school_details['add_1']." ".$school_details['cities']['name']
					." ".$school_details['states']['name']."-".$school_details['zipcode'].'</span><br>
				<span>Email Address : '.$school_details['email'].'</span> 
			</div>
			<div style="text-align:center; width: 25%;float: left;">
				<span style="color:#ccc">'.$month.' Copy</span>
			</div>	
							
		</div>

		<div class="row" style="width:100%;  text-align:center;  margin:20px 0 !important; clear:both">
			<h5 style="margin:20px 0 !important;">Fee Receipt ('.$session_details['startyear']."-". $session_details['endyear'].')</h5>
		</div>
		
		<div class="row" style="width:100% !important; display:flex; clear:both">							
			<div style="float:left; width:38% !important;">
				<div><span>Date: </span> '. date("d-m-Y", time()) .'</div>
				<div><span>Student: </span> '. ucfirst($retrieve_receipt[0]['s_name']) .'</div>
				<div><span>Payment Mode: </span> Cash</div>
				
			</div>
			<div style="float:left;  width:30% !important; ">
				<div><span>Admission No.: </span> '. $retrieve_receipt[0]['adm_no'] .'</div>
			</div>
			<div style="float:right; text-align:right; width:31% !important;">
				<div><span>Receipt No.: </span> '. $retrieve_receipt[0]['id'] .'</div>
				<div><span>Class: </span> '. $retrieve_class[0]['c_name']. ' '. $retrieve_class[0]['c_section'] .'</div>
				<div><span>Account No.: '. $retrieve_receipt[0]['acc_no'].'</div>
			</div>
		   
		</div> 
		
		<div class="row" style="width:100% !important; clear: both; ">
				<table style="width: 100%;">
					<thead style="  background: #212529;">
						<tr>
							
							<th width="50%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px ">Fee Name</th>
							<th width="25%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px">Amount</th>
							<th width="25%" style=" width:50% !important; color: #fff; text-align:center; padding:10px 0; margin-top: 30px">Discount</th>
							
						</tr>
					</thead>
					<tbody>
						'. $tbl	.'
					</tbody>
				</table>
		</div>	
		<div class="row" style="width:100% !important; clear: both; ">
			<div style="float:left; text-align:center; width:50% !important; border: 1px solid #000; ">
				Net Payable Amount  (in Rupees):
			</div>
			<div style=" float:left; text-align:center; width:49% !important; border: 1px solid #000;">
				'. $totlsum .'
			</div>
		</div>
		
		<div class="row" style="width:100% !important; display:flex; clear: both; ">
			<div style= "text-align:right; ">
				<p style="margin-top: 60px;">........................................................................</p>
				<p>(Signature of the person issuing the receipt)</p>
			</div>
		</div>
		
		
		<div class="row" style="width:100% !important; height:50px !important; clear: both; margin:40px 0;">
			
		</div>
		
		'; 
			
		}
		
		
		
		
			
					
		
		$dompdf = new Dompdf();
		$dompdf->loadHtml($rec_format);
		
		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'portrait');
		$dompdf->setPaper('A4', 'landscape');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream(); 
	
	} */
	
	
	public function viewdetails()
	{
		
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		
		$feereceipt_table = TableRegistry::get('feereceipt');
		$class_table = TableRegistry::get('class');
		$feehead_table = TableRegistry::get('feehead');
		$session_table = TableRegistry::get('session');		
		$school_table = TableRegistry::get('company');
		$feegen_table = TableRegistry::get('fees_generated');
		$stdId = $this->request->data('stdId');
		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['school_id' => $compid, 'student_id' => $stdId, 'session_id' => $session_id ])->toArray();
		
		$classId = $retrieve_receipt[0]['class'];
		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['id' => $classId ])->toArray();
		$school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
			'states' => 
				[
				'table' => 'states',
				'type' => 'LEFT',
				'conditions' => 'states.id = company.state'
			],
			'cities' => 
				[
				'table' => 'cities',
				'type' => 'LEFT',
				'conditions' => 'cities.id = company.city'
			]

		])->where(['company.id '=> $compid])->first() ;	
		$session_details = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
		
		
		$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
		$feehead = explode(",", $fee_h_id);		
		if($feehead != "Transport Charges")
		{
			$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		}
		
		 
		
		
		$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
		$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
		$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
		$count = count($feehead);
		
		$head = "";
		for($i = 0; $i < $count; $i++)
		{ 
			if($feehead[$i] != "Transport Charges") 
			{
				foreach($retrieve_feehead as $val)
				{
					
					if($val['id'] == $feehead[$i])
					{
						$head .= $val['head_name'].",";
					}
				}														
			}
			else
			{
				$head .= "Transport Charges";
			}
			
		}
		/*$head .= "Total Amount,";
		$head .= "Grand Total ,";
		$head .= "Cumm Total ";
		//echo $head;	*/	
		
		$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";
		$mnth = explode(",", $SelMonths);
		foreach($mnth as $mn)
		{
			$gen_struct_amt = "";
			$gen_disc_amt = "";
			$receiptId = $retrieve_receipt[0]['id'];
			$retrieve_feegen = $feegen_table->find()->select([ 'fee_h_id', 'fee_s_amt', 'discount_amt', 'final_amt', 'total_amt', 'total_dis_amt'])->where(['school_id' => $compid, 'student_id' => $stdId, 'month' => $mn , 'feereceipt_id' => $receiptId])->toArray();
			
			foreach($retrieve_feegen as $feegenrtn)
			{				
				$gen_struct_amt = $feegenrtn['fee_s_amt'];
				$gen_disc_amt = $feegenrtn['discount_amt'];
				$finalamt = $feegenrtn['total_amt'];
				$totalamt = $feegenrtn['final_amt'];
				$totaldiscount = $feegenrtn['total_dis_amt'];
			}
			
			
			$mnt[] = $mn;
			$struct_amt[] = $gen_struct_amt;
			$disc_amt[] = $gen_disc_amt;
			$final_amtt[] = $finalamt;
			$total_amtt[] = $totalamt;
			$total_disc[] = $totaldiscount;
			
		}
		
		
		$data = ['headname' => $head, 'school_details' => $school_details, 'classes' => $retrieve_class, 'session_name' => $session_details, 'month' => $mnt, 'structure_amount' => $struct_amt, 'discount_amount' => $disc_amt, 'final_amount' => $final_amtt, 'total_amount' => $total_amtt, 'total_discount' => $total_disc];

		return $this->json($data); 
	} 
	
	public function pdf()
	{
		 
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		
		$feereceipt_table = TableRegistry::get('feereceipt');
		$class_table = TableRegistry::get('class');
		$feehead_table = TableRegistry::get('feehead');
		$session_table = TableRegistry::get('session');		
		$school_table = TableRegistry::get('company');
		$feegen_table = TableRegistry::get('fees_generated');
		$stdId = $this->request->data('stdId');
		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['school_id' => $compid, 'student_id' => $stdId, 'session_id' => $session_id ])->toArray();
		
		$classId = $retrieve_receipt[0]['class'];
		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['id' => $classId ])->toArray();
		$school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
			'states' => 
				[
				'table' => 'states',
				'type' => 'LEFT',
				'conditions' => 'states.id = company.state'
			],
			'cities' => 
				[
				'table' => 'cities',
				'type' => 'LEFT',
				'conditions' => 'cities.id = company.city'
			]

		])->where(['company.id '=> $compid])->first() ;	
		$session_details = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
		
		
		$fee_h_id = $retrieve_receipt[0]['fee_h_id'];		
		$feehead = explode(",", $fee_h_id);		
		if($feehead != "Transport Charges")
		{
			$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		}
		
		 
		
		
		$feehead = explode(",",$retrieve_receipt[0]['fee_h_id']);
		$feesamt = explode(",",$retrieve_receipt[0]['fee_s_amt']);
		$discount = explode(",",$retrieve_receipt[0]['discount_amount']);
		$count = count($feehead);
		
		$head = "";
		for($i = 0; $i < $count; $i++)
		{ 
			if($feehead[$i] != "Transport Charges") 
			{
				foreach($retrieve_feehead as $val)
				{
					
					if($val['id'] == $feehead[$i])
					{
						$head .= $val['head_name'].",";
					}
				}														
			}
			else
			{
				$head .= "Transport Charges";
			}
			
		}
		/*$head .= "Total Amount,";
		$head .= "Grand Total ,";
		$head .= "Cumm Total ";
		//echo $head;	*/	
		
		$SelMonths = "4,5,6,7,8,9,10,11,12,1,2,3";
		$mnth = explode(",", $SelMonths);
		foreach($mnth as $mn)
		{
			$gen_struct_amt = "";
			$gen_disc_amt = "";
			$receiptId = $retrieve_receipt[0]['id'];
			$retrieve_feegen = $feegen_table->find()->select([ 'fee_h_id', 'fee_s_amt', 'discount_amt', 'final_amt', 'total_amt', 'total_dis_amt'])->where(['school_id' => $compid, 'student_id' => $stdId, 'month' => $mn , 'feereceipt_id' => $receiptId])->toArray();
			
			foreach($retrieve_feegen as $feegenrtn)
			{				
				$gen_struct_amt = $feegenrtn['fee_s_amt'];
				$gen_disc_amt = $feegenrtn['discount_amt'];
				$finalamt = $feegenrtn['total_amt'];
				$totalamt = $feegenrtn['final_amt'];
				$discamt = $feegenrtn['total_dis_amt'];
			}
			
			
			$mnt[] = $mn;
			$struct_amt[] = $gen_struct_amt;
			$disc_amt[] = $gen_disc_amt;
			$final_amtt[] = $finalamt;
			$total_amtt[] = $totalamt;
			$discou_amtt[] = $discamt;
			
		}
		//$data = ['headname' => $head, 'school_details' => $school_details, 'classes' => $retrieve_class, 'session_name' => $session_details, 'month' => $mnt, 'structure_amount' => $struct_amt, 'discount_amount' => $disc_amt, 'final_amount' => $final_amtt, 'total_amount' => $total_amtt];

		//return $this->json($data); 
		
		$heads = explode(",", $head);
		$table = "";
		foreach($heads as $hd=> $val)
		{
			if($val != "")
			{
				$table .= '<tr style=" border:1px solid #000 !important;" >';
				$table .= '<td>'.$val.'</td>';
				foreach($mnt as $m=> $months)
				{
					$structure = explode(',',$struct_amt[$m]);
					$table .= '<td>'.$structure[$hd].'</td>';
				}
				$table .= '</tr>';
				$table .= '<tr style=" border:1px solid #000 !important;" >';
				$table .= '<td>Exemption</td>';
				foreach($mnt as $d=> $discs)
				{
					$exemption = explode(',',$disc_amt[$d]);
					$table .= '<td>'.$exemption[$hd].'</td>';
				}
				$table .= '</tr>';
			}
		}
		$tables = '';
		$tables .= '<tr style=" border:1px solid #000 !important;" >';
		$tables .= '<td>Total Amount</td>';
		foreach($total_amtt as $ta=> $tamt)
		{
			$tables .= '<td>'.$tamt.'</td>';		
		}		
		$tables .= '</tr>';

		$tables .= '<tr style=" border:1px solid #000 !important;" >';
		$tables .= '<td>Total Discount</td>';
		foreach($discou_amtt as $da=> $damt)
		{
			$tables .= '<td>'.$damt.'</td>';		
		}
		$tables .= '</tr>';

		$tables .= '<tr style=" border:1px solid #000 !important;" >';
		$tables .= '<td>Grand Total</td>';
		foreach($final_amtt as $fa=> $famt)
		{
			$tables .= '<td>'.$famt.'</td>';		
		}		
		$tables .= '</tr>';
		
		$payable = array_sum($total_amtt);
		$exem = array_sum($discou_amtt);
		$net = array_sum($final_amtt);
		
		
		
		
		
		
			
			
			
				
			
			
		
		
		$viewpdf = ' <div style=" width:100%; font-family: arial;font-size: 14px;">
	<table style=" width: 100%;">
		<tbody>
			<tr>
				<td style="width: 100%;">
					<table style="width: 100%;  ">
						<tr>
						<th style="font-size:22px;color: #ff0000;text-transform: uppercase;font-weight: bold; width: 100%;">'.$school_details['comp_name'].'</th>
						</tr>
						<tr>
						<th style="font-size:20px; font-weight: bold; width: 100%;">'.$school_details['add_1']." ".$school_details['cities']['name']." ".$school_details['states']['name']."-".$school_details['zipcode'].'</th></tr><tr>
						<th style="font-size:20px; font-weight: bold; width: 100%;">Email Address : '.$school_details['email'].'</th> 
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width: 100%;  ">
						<tr>
							<td style="width: 33%;  "> Admission No.:  '.$retrieve_receipt[0]['adm_no'].'</td>
							<td style="width: 33%; text-align:center "> Student Name:  '.$retrieve_receipt[0]['s_name'].'</td>
							<td style="width: 33%; text-align:right "> Class:  '.$retrieve_class[0]['c_name'].' '.$retrieve_class[0]['c_section'].'</td>
							
						</tr> 
					</table>
				</td>
			</tr>
			
			<tr>
				<td>
					<table style="width: 100%; border: 1px solid #000; text-align: center ">
						<thead class="thead-light">
							<tr>
								<th> Description </th>
								<th> April </th>	
								<th> May </th>
								<th> June </th>
								<th> July </th>
								<th> Aug </th>
								<th> Sep </th>
								<th> Oct </th>
								<th> Nov </th>
								<th> Dec </th>
								<th> Jan </th>
								<th> Feb </th>
								<th> Mar </th>
							</tr>									
							</thead>
							<tbody id="viewed" style=" border-top: 1px solid #000; ">
								'.$table.'
								'.$tables.'
							</tbody>
					</table>
				</td>
			</tr>
			
			<tr>
				<td>
					<table style="width: 100%;  ">
						<tr>
							<td style="width: 100%;">Annual Fees</td>
						</tr>
						<tr>						
							<td style="width: 100%;">
								<table style="width: 50%;  ">
									<tr>
										<td style="text-align:left;"> Total Payable:  '.$payable.'</td>
										<td style="text-align:left;"> Total Exemption:  '.$exem.'</td>
										<td style="text-align:left;"> Net Payable Fees:  '.$net.' </td>
									</tr> 
								</table>							
							</td>
						</tr> 
					</table>
				</td>
			</tr>
			
			
			
			
			
		</tbody>
	</table>
</div>';
		$dompdf = new Dompdf();
		$dompdf->loadHtml($viewpdf);
		
		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'portrait');
		$dompdf->setPaper('A4', 'landscape');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream(); 
		
		
	} 
	public function printfee()
	{
		
	}
	
	public function delete()
	{
		$rid = $this->request->data('val') ;
		$feereceipt_table = TableRegistry::get('feereceipt');
		$activ_table = TableRegistry::get('activity');
		
		$frid = $feereceipt_table->find()->select(['id'])->where(['id'=> $rid ])->first();
		if($frid)
		{   
			
			$del = $feereceipt_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
			if($del)
			{
				$activity = $activ_table->newEntity();
				$activity->action =  "Fee Receipt Deleted"  ;
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
	
	
	
	
		
}
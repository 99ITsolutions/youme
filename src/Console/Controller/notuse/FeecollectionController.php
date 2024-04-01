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


class FeecollectionController  extends AppController
{
	public function index()
	{   

		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
	
		$student_table = TableRegistry::get('student');		
		$feecollctn_table = TableRegistry::get('fee_collection_detail');		
		
		$retrieve_collection = $feecollctn_table->find()->select(['id' , 'student_id', 'receipt_no', 'months', 'deposit_amount', 'payment_mode', 'status', 'deposit_date', 'total_fee', 'student.s_name', 'student.adm_no', 'student.acc_no', 'class.c_name', 'class.c_section' ])->join([
		'student' => 
			[
				'table' => 'student',
				'type' => 'LEFT',
				'conditions' => 'student.id  = fee_collection_detail.student_id  '
			],
		'class' => 
			[
				'table' => 'class',
				'type' => 'LEFT',
				'conditions' => 'class.id = student.class'
			]
		])->where(['fee_collection_detail.session_id'=>$session_id ,'fee_collection_detail.school_id' => $compid ])->toArray();		
		
		
		
		foreach($retrieve_collection as $key =>$studentcoll)
		{
			$stdid = explode(",",$studentcoll['student_id']);
			$i = 0;
			$studentsname = [];
			foreach($stdid as $sid)
			{
				$retrieve_students = $student_table->find()->select(['s_name' ])->where(['session_id'=>$session_id ,'school_id' => $compid, 'id' => $sid])->toArray();	
					
				foreach($retrieve_students as $rstd)
				{
					$studentsname[$i] = $rstd['s_name'];				
				}
				$i++;
				$snames = implode(",", $studentsname);
				
			}
			 $studentcoll->student_name = $snames;
			
			
		}
		
		
		$this->set("collection_dtl", $retrieve_collection);
		$this->viewBuilder()->setLayout('user');

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
		
		$feecollctn_table = TableRegistry::get('fee_collection_detail');

		$adm_no = $this->request->data('admNo');

		$class = $this->request->data('class');
		$session = $this->request->data('sess_name');
		$session_id = $this->Cookie->read('sessionid');

		$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['session_id'=>$session_id , 'school_id' => $compid ])->toArray();

		$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();

		$retrieve_discount = $discount_table->find()->select(['id' , 'dscr', 'percentage', 'code'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();

		$retrieve_stoppage = $stoppage_table->find()->select(['id' , 'village'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();		
		
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
		
		
		$retrieve_receiptID = $feecollctn_table->find()->select(['receipt_no'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->last();
		$rNo = $retrieve_receiptID['receipt_no'];
		$this->set("sibling_details", $retrieve_siblings);
		$this->set("stoppage_details", $retrieve_stoppage);
		$this->set("class_details", $retrieve_class);
		$this->set("discount_details", $retrieve_discount);
		$this->set("feehead_details", $retrieve_feehead);
		$this->set("school_id", $compid);
		$this->set("session_id", $session_id);
		$this->set("receiptNO", $rNo );
		

		$this->viewBuilder()->setLayout('user');

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

	public function getfeesmonth()
	{
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		
		$feereceipt_table = TableRegistry::get('feereceipt');
		$feegen_table = TableRegistry::get('fees_generated');
		$feehead_table = TableRegistry::get('feehead');
		$balance_table = TableRegistry::get('balance');
		
		$accNo = $this->request->data('accNo');
		$stdId = $this->request->data('stdId');
		$SelMonths = $this->request->data('SelMonths');
		
		$retrieve_balance = $balance_table->find()->select(['id' , 'bal_amt'])->where(['school_id' => $compid, 'acc_no' => $accNo, 'session_id' => $session_id ])->last();		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['school_id' => $compid, 'acc_no' => $accNo, 'session_id' => $session_id ])->toArray();
		
		$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		foreach($retrieve_receipt as $rreceipt)
		{
			$fee_h_id = $rreceipt['fee_h_id'];		
			$feehead = explode(",", $fee_h_id);		
			$fh[] = $feehead;
			$rid[] = $rreceipt['id'];		
			$feesamt = explode(",",$rreceipt['fee_s_amt']);
			$discount = explode(",",$rreceipt['discount_amount']);
			$count = count($feehead);
			$rec_format = "";
			$fee =[];
			if(!empty($SelMonths)) {
			$mnth = explode(",", $SelMonths);
			foreach($mnth as $mn)
			{				
				$tbl = "";
				$totalamt = "";
				$disamt = "";
				$fee = [];
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
					}	//echo $head;	
					
			
					if(!empty($head))
					{
						$totalamt .= $feesamt[$i].",";
						$disamt .= $discount[$i].",";
						$fee[$head] = $feesamt[$i].",".$discount[$i];
					}
					
					
				}	
									
				
				
				$tamt = explode(",", $totalamt);
				$damt = explode(",", $disamt);
				$discountsum = array_sum($damt);
				$feesum = array_sum($tamt);
				$feest[] = $fee;
				$totlsum[] = $feesum - $discountsum ;		
				
			}
			}
			else
			{
				$feest[] = "";
				$feest[] = "";
			}
			
		}
		
		foreach ($feest as $k => $v)
		{
			 foreach ($v as $key => $value)
			 $my_arr[$key][] = $value;
		}

		

		foreach ($my_arr as $key => $values)
		{
		   $first_charge  = 0;
		   $second_charge = 0;
		   foreach ($values as $k => $v)
			  {
				list($first, $second) = explode(',', $v);
				$first_charge  = $first_charge + $first;
				$second_charge = $second_charge + $second;
			  }
		  $feedtl[$key] = $first_charge .','.$second_charge;			
		}

		/*print_r($feedtl);
		die;
		
		$new_arr = [];
		foreach($feest as $f)
		{
			foreach($f as $v => $k)
			{
				array_push($new_arr, $v);
			}
		}
		$arr = array_unique($new_arr);
		
		foreach($arr as $val)
		{
			//print_r($val);
			$strc_arr = [];
			$dis_arr = [];
			foreach($feest as $f)
			{
				foreach($f as $v => $k)
				{
					$amt = explode(",", $k);
					if($val == $v)
					{
						array_push($strc_arr, $amt[0]);
						array_push($dis_arr, $amt[1]);
						
					}
				}
			}
			
			$structureAmt = array_sum($strc_arr);
			$DiscountAmt = array_sum($dis_arr);
			$feedtl[$val] = $structureAmt.",". $DiscountAmt;
		}
		print_r($feedtl);
		die; */
		
		
		$finlsum = array_sum($totlsum);		
		$bal_amt = $retrieve_balance['bal_amt'] == null ? 0 : $retrieve_balance['bal_amt'];
		$bal_id = $retrieve_balance['id'] == null ? "" : $retrieve_balance['id'];		
		$data = ['total_fee' => $finlsum, 'bal_amt' => $bal_amt, 'bal_id' => $bal_id, 'feedtl' => $feedtl];

		return $this->json($data);		
	
	}
	
	public function getbalance()
	{
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		
		$balance_table = TableRegistry::get('balance');
		
		$accNo = $this->request->data('accNo');
		
		$retrieve_balance = $balance_table->find()->select(['id' , 'bal_amt'])->where(['school_id' => $compid, 'acc_no' => $accNo, 'session_id' => $session_id ])->last();		
		
			
		$bal_amt = $retrieve_balance['bal_amt'] == null ? 0 : $retrieve_balance['bal_amt'];
		$bal_id = $retrieve_balance['id'] == null ? "" : $retrieve_balance['id'];		
		$data = [ 'bal_amt' => $bal_amt, 'bal_id' => $bal_id];

		return $this->json($data);		
	
	}
	
	public function addcollectionfee()
	{
		
		if($this->request->is('post'))
		{
			
			$session_id = $this->request->data('session_id');
			$compid =$this->request->session()->read('company_id');
			
			$student_table = TableRegistry::get('student');
			$balance_table = TableRegistry::get('balance');
			$feecollectn_table = TableRegistry::get('fee_collection_detail');
			
			$feecoll = $feecollectn_table->newEntity();			
			
			$feecoll->receipt_no =  $this->request->data('receipt_no');
			if(!empty($this->request->data('sibls')))
			{
				$feecoll->student_id =  $this->request->data('sibls');
			}
			else
			{
				$feecoll->student_id =  $this->request->data('sibls');
			}
			$feecoll->school_id = $this->request->data('school_id');			
			$feecoll->months = implode(",", $this->request->data('months'));		
			$feecoll->deposit_date = $this->request->data('date');
			$feecoll->total_fee = $this->request->data('total_fee');
			$feecoll->fine = $this->request->data('fine');
			$feecoll->deposit_amount = $this->request->data('deposit_amt');
			$feecoll->balance = $this->request->data('balance');
			$feecoll->payment_mode = $this->request->data('payment_type');
			$feecoll->remarks_offical = $this->request->data('remarks_offical');
			$feecoll->remarks_feerelated = $this->request->data('remarks_feerelated');			
			$feecoll->cheque_no = $this->request->data('cheque_no');
			$feecoll->cheque_date = $this->request->data('cheque_date');
			$feecoll->transaction_no = $this->request->data('transaction_no');
			$feecoll->transaction_date = $this->request->data('transaction_date');
			$feecoll->bank_listing = $this->request->data('bank_listing');			
			$feecoll->session_id = $this->request->data('session_id');						
			$feecoll->status =  1;
			$feecoll->created_date = time();
			$feecoll->acc_no = $this->request->data('acc_no');
			
			
			
			$bal = $this->request->data('balance');
			$acc_no = $this->request->data('acc_no');
			$father_name = $this->request->data('father_name');
			$s_name = $this->request->data('s_name');
			$skul_id = $this->request->data('school_id');	
			$month = $this->request->data['months'];
			$student_id =  $this->request->data('sibl_in_scl');
			$sibling_ids =  explode(",", $this->request->data('sibls'));
			if (preg_match ("/^[0-9]*$/", $this->request->data('deposit_amt')) )
			{
			if($saved = $feecollectn_table->save($feecoll) ){
				$update_balance = $balance_table->query()->update()->set([ 'bal_amt' => $bal])->where([ 'acc_no' => $acc_no, 'school_id' => $skul_id, 'session_id' => $session_id])->execute();					
				foreach($month as $mnth)
				{					
					if($mnth == 4)	
					{						
						$mn = 'april';
					}
					if($mnth == 5)
					{
						$mn = 'may';
					}
					if($mnth == 6)
					{
						$mn = 'june';
					}
					if($mnth == 7)
					{
						$mn = 'july';
					}
					if($mnth == 8)
					{
						$mn = 'aug';
					}
					if($mnth == 9)
					{
						$mn = 'sep';
					}
					if($mnth == 10)
					{
						$mn = 'oct';
					}
					if($mnth == 11)
					{
						$mn = 'nov';
					}
					if($mnth == 12)
					{
						$mn = 'december';
					}
					if($mnth == 1)
					{
						$mn = 'jan';
					}
					if($mnth == 2)
					{
						$mn = 'feb';
					}
					if($mnth == 3)
					{
						$mn = 'mar';
					}
					$update_mnths = $student_table->query()->update()->set([$mn => 1])->where(['id' => $student_id, 'school_id' => $skul_id, 'session_id' => $session_id])->execute();	
					foreach($sibling_ids as $sids)
					{
						$update_mnths = $student_table->query()->update()->set([$mn => 1])->where(['id' => $sids, 'school_id' => $skul_id, 'session_id' => $session_id])->execute();
					}
										
				}
				
				$activ_table = TableRegistry::get('activity');
				
				$activity = $activ_table->newEntity();
				$activity->action =  "Fee Collected "  ;
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
			else
			{
				$res = [ 'result' => 'Collection not saved'  ]; 
			}
			}
			else
			{
				$res = [ 'result' => 'Deposit Amount contains only Positive Numbers.'  ]; 
			}
			
		}
		else{
			$res = [ 'result' => 'invalid operation'  ];
		}
		return $this->json($res);
	}
	

	
	public function showdetails()
	{
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$feereceipt_table = TableRegistry::get('feereceipt');
		$feegen_table = TableRegistry::get('fees_generated');
		$feehead_table = TableRegistry::get('feehead');
		
		$stdId = $this->request->data('stdId');
		$SelMonths = $this->request->data('SelMonths');
			
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount'])->where(['school_id' => $compid, 'student_id' => $stdId, 'session_id' => $session_id ])->toArray();
		
		$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
		foreach($retrieve_receipt as $rreceipt)
		{
			$fee_h_id = $rreceipt['fee_h_id'];		
			$feehead = explode(",", $fee_h_id);		
			$fh[] = $feehead;
			$rid[] = $rreceipt['id'];		
			$feesamt = explode(",",$rreceipt['fee_s_amt']);
			$discount = explode(",",$rreceipt['discount_amount']);
			$count = count($feehead);
			$rec_format = "";
			$fee =[];
			if(!empty($SelMonths))
			{
			$mnth = explode(",", $SelMonths);
			foreach($mnth as $mn)
			{				
				$tbl = "";
				$totalamt = "";
				$disamt = "";
				$fee = [];
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
						$fee[$head] = $feesamt[$i].",".$discount[$i];
					}
				}	
					
				$feest[] = $fee;
				
			}
			}
			else
			{
				$feest[] = "";
			}
			
		}
		
		foreach ($feest as $k => $v)
		{
			 foreach ($v as $key => $value)
			 $my_arr[$key][] = $value;
		}

		

		foreach ($my_arr as $key => $values)
		{
		   $first_charge  = 0;
		   $second_charge = 0;
		   foreach ($values as $k => $v)
			  {
				list($first, $second) = explode(',', $v);
				$first_charge  = $first_charge + $first;
				$second_charge = $second_charge + $second;
			  }
		  $feedtl[$key] = $first_charge .','.$second_charge;			
		}

		$data = ['feedtl' => $feedtl];
		//print_r($data);
		return $this->json($data);		
	}
	
	public function pdf($fid = null)
	{
		
		$compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		
		$feereceipt_table = TableRegistry::get('feereceipt');
		$class_table = TableRegistry::get('class');
		$discount_table = TableRegistry::get('discount');
		$stoppage_table = TableRegistry::get('routechg');
		$feehead_table = TableRegistry::get('feehead');
		$session_table = TableRegistry::get('session');		
		$school_table = TableRegistry::get('company');
		$balance_table = TableRegistry::get('balance');
		$feecollection_table = TableRegistry::get('fee_collection_detail');
		$student_table = TableRegistry::get('student');
		
		$school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode', 'company.acc_no', 'company.ifsc_code' ])->join([
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
		
		
		$retrieve_collection = $feecollection_table->find()->select(['id', 'receipt_no', 'acc_no', 'student_id', 'months', 'deposit_date', 'total_fee', 'fine', 'deposit_amount', 'balance', 'payment_mode', 'remarks_offical', 'session_id', 'school_id', 'status', 'created_date', 'remarks_feerelated', 'transaction_no', 'transaction_date', 'bank_listing', 'cheque_no', 'cheque_date'])->where(['md5(id)' => $fid ])->toArray();	
		
		//print_r($retrieve_collection[0]['cheque_date']);
		
		$acc_no = $retrieve_collection[0]['acc_no'];
		$session_id = $retrieve_collection[0]['session_id'];
		$school_id = $retrieve_collection[0]['school_id'];
		
		
		$retrieve_receipt = $feereceipt_table->find()->select(['id' , 'adm_no', 'acc_no', 'class', 's_name', 'fee_h_id', 'fee_s_amt', 'discount_id', 'transport', 'stoppage', 't_charges', 'total_amount', 'discount_amount', 'student_id'])->where(['school_id' => $school_id, 'acc_no' => $acc_no, 'session_id' => $session_id ])->toArray();
		$std_name = [];
		$cls_name = [];
		foreach($retrieve_receipt as $rreceipt)
		{
			
			$retrieve_discount = $discount_table->find()->select(['id' , 'dscr', 'percentage'])->where(['school_id' => $school_id, 'code' => $rreceipt['discount_id'], 'session_id' => $session_id ])->toArray();
			$student_id = $rreceipt['student_id'];
			$retrieve_student = $student_table->find()->select(['id' , 's_f_name', 's_name', 'resi_add1', 'org_adm_no', 'acc_no'])->where(['school_id' => $school_id, 'id' => $student_id, 'session_id' => $session_id ])->toArray();	
			
			foreach($retrieve_student as $studen)
			{
				$std_name[] = $studen['s_name'];
			}
			
		
		
			$classId = $rreceipt['class'];		
			$retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['id' => $classId ])->toArray();
			foreach($retrieve_class as $clas)
			{
				$cls_name[] = $clas['c_name']."-".$clas['c_section'];
			}
		
			$fee_h_id = $rreceipt['fee_h_id'];		
			$feehead = explode(",", $fee_h_id);		
			if($feehead != "Transport Charges")
			{
				$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name', 'head_frequency', 'months'])->where(['session_id'=>$session_id ,'school_id' => $compid ])->toArray();
			}
			
				
			 
			$feehead = explode(",",$rreceipt['fee_h_id']);
			$feesamt = explode(",",$rreceipt['fee_s_amt']);
			$discount = explode(",",$rreceipt['discount_amount']);
			$count = count($feehead);
			$rec_format = "";
			
			$fee =[];
			
			$SelMonths = $retrieve_collection[0]['months'];
			$mnth = explode(",", $SelMonths);
			foreach($mnth as $mn)
			{
				if($mn == 4){	$mnt = 'april';}
				if($mn == 5){ $mnt = 'may'; }
				if($mn == 6){	$mnt = 'june';		}
				if($mn == 7){	$mnt = 'july';			}
				if($mn == 8){	$mnt = 'aug';			}
				if($mn == 9){	$mnt = 'sep';			}
				if($mn == 10){ $mnt = 'oct';	}
				if($mn == 11){ $mnt = 'nov'; 			}
				if($mn == 12){ $mnt = 'dec'; }
				if($mn == 1){  $mnt= 'jan';			}
				if($mn == 2){	$mnt = 'feb';			}
				if($mn == 3){	$mnt = 'mar';			}
				$tbl = "";
				$totalamt = "";
				$disamt = "";
				$fee = [];
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
						$feehd[] = ucfirst($head. "(". $mnt. ")");
						if(!empty($feesamt[$i]))
						{
							$feeamt[] = $feesamt[$i];
							$disAmnt[] = $discount[$i];
						}
					}
					
					
			
					if(!empty($head))
					{
						$totalamt .= $feesamt[$i].",";
						$disamt .= $discount[$i].",";
					}	

					if(!empty($head))
					{
						$fee[$head] = $feesamt[$i].",".$discount[$i];
					}
				}	
									
				
				
				$tamt = explode(",", $totalamt);
				$damt = explode(",", $disamt);
				$discountsum = array_sum($damt);
				$feesum = array_sum($tamt);
				
				$totlsum[] = $feesum - $discountsum;		
				
					
				$feest[] = $fee;
				
			}
			
		}
		
		$snames = implode(",", $std_name);
		$sclass = implode(",", $cls_name);
		foreach ($feest as $k => $v)
		{
			 foreach ($v as $key => $value)
			 $my_arr[$key][] = $value;
		}

		

		foreach ($my_arr as $key => $values)
		{
		   $first_charge  = 0;
		   $second_charge = 0;
		   foreach ($values as $k => $v)
			  {
				list($first, $second) = explode(',', $v);
				$first_charge  = $first_charge + $first;
				$second_charge = $second_charge + $second;
			  }
		  $feedtl[$key] = $first_charge .','.$second_charge;			
		}

		
		
		$finlsum = array_sum($totlsum);
		
		$disAt = $discountsum;
		$fh = '';
		$fa = '';
		$fd = '';
		foreach($feedtl as $key => $feeh)
		{
			
			$fh .= "<tr><th style='text-align: left; border: 1px solid #000; '>".$key."</th></tr>";
			$fm = explode(",", $feeh);
			$fa .= "<tr><th style='text-align: right; border: 1px solid #000; '>".$fm[0]."</th></tr>";
			$fd .= "<tr><th style='text-align: right; border: 1px solid #000; '>".$fm[1]."</th></tr>";
			
			
		}
		
			
		
		
		
		$number = $retrieve_collection[0]['deposit_amount'];
		$no = floor($number);
		$point = round($number - $no, 2) * 100;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'one', '2' => 'Two',
		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
		'13' => 'Thirteen', '14' => 'Fourteen',
		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
		'60' => 'Sixty', '70' => 'Seventy',
		'80' => 'Eighty', '90' => 'Ninety');
		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
		}
		$str = array_reverse($str);
		$amt_words = implode('', $str);

		$monthss = explode(',', $retrieve_collection[0]['months']);
		foreach($monthss as $mnthss) 
		{
			$mnt = '';
			if($mnthss == 4){	$mnt .= 'April';}
			if($mnthss == 5){ $mnt .= 'May'; }
			if($mnthss == 6){	$mnt .= 'June';}
			if($mnthss == 7){	$mnt .= 'July';}
			if($mnthss == 8){	$mnt .= 'Aug';}
			if($mnthss == 9){	$mnt .= 'Sep';}
			if($mnthss == 10){ $mnt .= 'Oct';}
			if($mnthss == 11){ $mnt .= 'Nov'; }
			if($mnthss == 12){ $mnt .= 'Dec'; }
			if($mnthss == 1){  $mnt .= 'Jan';	}
			if($mnthss == 2){	$mnt .= 'Feb';}
			if($mnthss == 3){	$mnt .= 'March'; }
		
			
			$month[] = $mnt;
		}
		$fee_month = implode(', ', $month);
		
		if(strtolower($retrieve_collection[0]['payment_mode']) == "cheque")
		{
			$colldetl = ", Cheque No. - ".$retrieve_collection[0]['cheque_no'].", Cheque Date -".date("d-m-Y", strtotime($retrieve_collection[0]['cheque_date']))." ".$retrieve_collection[0]['bank_listing'];
		}
		elseif(strtolower($retrieve_collection[0]['payment_mode']) == "card")
		{
			$colldetl = ", Transaction No. - ".$retrieve_collection[0]['transaction_no'].", Transaction Date -".date("d-m-Y", strtotime($retrieve_collection[0]['transaction_date']))." ".$retrieve_collection[0]['bank_listing'];
		}
		else
		{
			$colldetl = "";
		}
		
		//print_r($retrieve_discount); die;
		if(!empty($retrieve_discount[0]['dscr']))
		{
			$dis_ty = $retrieve_discount[0]['dscr']. "(". $retrieve_discount[0]['percentage']. "%)";
			$discount_ty = '<tr><td style="float: left;width: 100%;text-align: left;">Discount Type: '.$dis_ty.'</td></tr>';
			
		}
		else
		{
			$discount_ty = '';
		}
		
		$rec_format .= '<div style=" width:100%; border: 1px solid #000; border-radius: 10px;font-family: arial;font-size: 10px;">
	<table style=" width: 100%;">
		<tbody>
			<tr>
				<td style="width: 100%;">
					<table style="width: 100%;  ">
						<tr>
							<td style="width: 25%;  ">
								<img src="img/'.$school_details["comp_logo"].'" height="80" width="80" alt="school logo">
							</td>
							<td style="width: 75%; text-align:center  ">
								<table style="width: 100%;  ">
									<tr>
										<th style="font-size:16px;color: #ff0000;text-transform: uppercase;font-weight: bold; width: 100%;">'.$school_details['comp_name'].'</th>
									</tr>
									<tr>
										<th style="font-size:12px; font-weight: bold; width: 100%;">'.$school_details['add_1']." ".$school_details['cities']['name']." ".$school_details['states']['name']."-".$school_details['zipcode'].'</th></tr><tr>
										<th style="font-size:12px; font-weight: bold; width: 100%;">Email Address : '.$school_details['email'].'</th> 
									</tr>
								</table>
							</td>
							
						</tr>
						
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 38% text-align: left; "></td>
							<td style="width: 32% text-align: center; font-size:11px; font-weight:bold">Fee Receipt</td>
							<td style="text-align: right;padding-right: 20px; width: 30%;">Office Copy</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 62%;">
								<table>
									<tr>
										<th style="float: left;text-align: left;">Receipt No. : '.$retrieve_collection[0]['receipt_no'].'</th>
									</tr>
								</table>
							</td>
							<td style="width: 38%;  text-align: right !important;">
								<table>
									<tr>
										<th style=" text-align: right !important;">Deposit Date : '.$retrieve_collection[0]['deposit_date'].'</th>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td>
								<table style="width: 100%;">>
									<tr>
										<th style="text-align: left;">Name : '.$snames.'</th>
									</tr>
									<tr>
										<th style="text-align: left; ">Father Name : '.$retrieve_student[0]['s_f_name'].'</th>
									</tr>
									<tr>
										<th>
											<table style="width: 100%; margin-top:-4px; margin-bottom:-4px; margin-left:-3px;">
												<tr>
													<th style="text-align: left; width: 60%;">Class : '.$sclass.'</th>
													<th style="text-align: right; width: 40%;">Account No. : '.$retrieve_student[0]['acc_no'].' </th>
												</tr>
											</table>										
										</th>
									</tr>
									<tr>
										<th>
											<table style="width: 100%; margin-top:-4px; margin-bottom:-4px; margin-left:-3px;">
												<tr>
													<th style="text-align: left; width: 60%;">Address : '.$retrieve_student[0]['resi_add1'].'</th>
													<th style="float: right;text-align: right; width: 40%;">O.Adm No. : '.$retrieve_student[0]['org_adm_no'].'</th>
												</tr>
											</table>										
										</th>
									</tr>
									<tr><th style="text-align: left; ">Fee for the month of : '.$fee_month.'</th></tr>
									
								</table>
							</td>
							
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 60%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align:center; border: 1px solid #000; font-size:11px; ">Description</th></tr>
									'.$fh.'
									
									
								</table>
							</td>
							<td style="width: 20%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align:center;  border: 1px solid #000; font-size:11px; ">Amount</th></tr>
									'.$fa.'
									
								</table>
							</td>
							<td style="width: 20%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align:center;  border: 1px solid #000; font-size:11px; ">Discount</th></tr>
									'.$fd.'
									
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 33%;border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Total Amt</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['total_fee'].'</th></tr>
									
								</table>
							</td>
							<td style="width: 33%; border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Amt Deposited</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['deposit_amount'].'</th></tr>
								</table>
							</td>
							<td style="width: 33%;border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Balance Amt</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['balance'].'</th></tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr><td style="float: left;width: 100%;text-align: left;">Amount in Words: Rs. <b>'. $amt_words.' only</b></td></tr>
						<tr><td style="float: left;width: 100%;text-align: left;">Payment Mode: '.ucfirst($retrieve_collection[0]['payment_mode']).' '. $colldetl.'</td></tr>
						'.$discount_ty.'
						<tr><td style="float: left;width: 100%;text-align: left;">Remarks:  N/A</td></tr>
						<tr><td style="float: left;width: 100%;text-align: left;">Notes: Please Submit your ward fee by 15th of the respective month.Late fee shall be charged Rs. 50/- from 16th to 20th therafter, Rs. 200/day from 21st to end of the month. Fees once paid will not refund.</td></tr>
						<tr><td style="float: left;width: 100%;text-align: right; padding-top:15px">Authorised Signature</td></tr>
					</table>
				</td>
			</tr>
			
		</tbody>
	</table>
</div>
'; 
$rec_format_p = '';
$rec_format_p .= '<div style=" width:100%; border: 1px solid #000; border-radius: 10px;font-family: arial;font-size: 10px;">
	<table style=" width: 100%;">
		<tbody>
			<tr>
				<td style="width: 100%;">
					<table style="width: 100%;  ">
						<tr>
							<td style="width: 25%;  ">
								<img src="img/'.$school_details["comp_logo"].'" height="80" width="80" alt="school logo">
							</td>
							<td style="width: 75%; text-align:center  ">
								<table style="width: 100%;  ">
									<tr>
										<th style="font-size:16px;color: #ff0000;text-transform: uppercase;font-weight: bold; width: 100%;">'.$school_details['comp_name'].'</th>
									</tr>
									<tr>
										<th style="font-size:12px; font-weight: bold; width: 100%;">'.$school_details['add_1']." ".$school_details['cities']['name']." ".$school_details['states']['name']."-".$school_details['zipcode'].'</th></tr><tr>
										<th style="font-size:12px; font-weight: bold; width: 100%;">Email Address : '.$school_details['email'].'</th> 
									</tr>
								</table>
							</td>
							
						</tr>
						
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 38% text-align: left; "></td>
							<td style="width: 32% text-align: center; font-size:11px; font-weight:bold">Fee Receipt</td>
							<td style="text-align: right;padding-right: 20px; width: 30%;">Parents Copy</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 62%;">
								<table>
									<tr>
										<th style="float: left;text-align: left;">Receipt No. : '.$retrieve_collection[0]['receipt_no'].'</th>
									</tr>
								</table>
							</td>
							<td style="width: 38%;  text-align: right !important;">
								<table>
									<tr>
										<th style=" text-align: right !important;">Deposit Date : '.$retrieve_collection[0]['deposit_date'].'</th>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td>
								<table style="width: 100%;">>
									<tr>
										<th style="text-align: left;">Name : '.$snames.'</th>
									</tr>
									<tr>
										<th style="text-align: left; ">Father Name : '.$retrieve_student[0]['s_f_name'].'</th>
									</tr>
									<tr>
										<th>
											<table style="width: 100%; margin-top:-4px; margin-bottom:-4px; margin-left:-3px;">
												<tr>
													<th style="text-align: left; width: 60%;">Class : '.$sclass.'</th>
													<th style="text-align: right; width: 40%;">Account No. : '.$retrieve_student[0]['acc_no'].' </th>
												</tr>
											</table>										
										</th>
									</tr>
									<tr>
										<th>
											<table style="width: 100%; margin-top:-4px; margin-bottom:-4px; margin-left:-3px;">
												<tr>
													<th style="text-align: left; width: 60%;">Address : '.$retrieve_student[0]['resi_add1'].'</th>
													<th style="float: right;text-align: right; width: 40%;">O.Adm No. : '.$retrieve_student[0]['org_adm_no'].'</th>
												</tr>
											</table>										
										</th>
									</tr>
									<tr><th style="text-align: left; ">Fee for the month of : '.$fee_month.'</th></tr>
									
								</table>
							</td>
							
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 60%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Description</th></tr>
									'.$fh.'
									
									
								</table>
							</td>
							<td style="width: 20%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Amount</th></tr>
									'.$fa.'
									
								</table>
							</td>
							<td style="width: 20%;  border: 1px solid #000;">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Discount</th></tr>
									'.$fd.'
									
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr>
							<td style="width: 33%;border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Total Amt</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['total_fee'].'</th></tr>
									
								</table>
							</td>
							<td style="width: 33%; border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Amt Deposited</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['deposit_amount'].'</th></tr>
								</table>
							</td>
							<td style="width: 33%;border: 1px solid #000; ">
								<table style="width: 100%;">
									<tr><th style="text-align: center; border: 1px solid #000; font-size:11px; ">Balance Amt</th></tr>
									<tr><th style="text-align: center; border: 1px solid #000; ">'.$retrieve_collection[0]['balance'].'</th></tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000;border-radius: 10px;">
					<table style="width: 100%;">
						<tr><td style="float: left;width: 100%;text-align: left;">Amount in Words: Rs. <b>'. $amt_words.' only </b></td></tr>
						<tr><td style="float: left;width: 100%;text-align: left;">Payment Mode: '.ucfirst($retrieve_collection[0]['payment_mode']).' '. $colldetl.'</td></tr>
						'.$discount_ty.'
						<tr><td style="float: left;width: 100%;text-align: left;">Remarks: '.ucfirst($retrieve_collection[0]['remarks_offical']).'</td></tr>
						<tr><td style="float: left;width: 100%;text-align: left;">Notes: Please Submit your ward fee by 15th of the respective month.Late fee shall be charged Rs. 50/- from 16th to 20th therafter, Rs. 200/day from 21st to end of the month. Fees once paid will not refund.</td></tr>
						<tr><td style="float: left;width: 100%;text-align: right; padding-top:15px">Authorised Signature</td></tr>
					</table>
				</td>
			</tr>
			
		</tbody>
	</table>
</div>
'; 

$print_receipt = '<div style=" width:100%; font-family: arial;">
<div style=" width:49%; float:left; margin-right:8px;">'.$rec_format.'</div>

<div style=" width:49%; float:left;">'.$rec_format_p.'</div>
</div>';
		
		
		$dompdf = new Dompdf();
		$dompdf->loadHtml($print_receipt);	
		
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$dompdf->stream(); 
		
		/*print_r($data);
		print_r($retrieve_collection); echo "<br><br>";
		print_r($retrieve_receipt); echo "<br><br>";
		print_r($session_details); echo "<br><br>";
		print_r($school_details); echo "<br><br>";
		print_r($retrieve_class); echo "<br><br>";
		print_r($retrieve_feehead); echo "<br><br>";
		die;	
		return $this->json($data);	 */	
	
	}
	public function getsiblingsdetails()
	{   

		$this->viewBuilder()->layout(false);
		$stdID = $this->request->data('stdID');
		$compid =$this->request->session()->read('company_id');
		$student_table = TableRegistry::get('student');	
		$session_id = $this->Cookie->read('sessionid');	

		$get_acc_no = $student_table->find()->select([ 'student.acc_no'])->where(['student.id ' => $stdID , 'student.school_id'=> $compid ])->toArray(); 
		//print_r($get_acc_no);
		$accNo = $get_acc_no[0]['acc_no'];
		
		/*$get_siblings = $student_table->find()->select([ 'student.id', 'student.s_name', 'student.class', 'class.c_name', 'class.c_section'])->join([		
		'class' => 
			[
				'table' => 'class',
				'type' => 'LEFT',
				'conditions' => 'class.id = student.class'
			]
		])->where(['student.session_id ' => $session_id , 'student.school_id'=> $compid, 'student.acc_no'=> $accNo, 'student.id !='=> $stdID  ])->toArray(); */
		
		$get_siblings = $student_table->find()->select([ 'student.id', 'student.s_name', 'student.adm_no', 'student.class', 'class.c_name', 'class.c_section'])->join([		
		'class' => 
			[
				'table' => 'class',
				'type' => 'LEFT',
				'conditions' => 'class.id = student.class'
			]
		])->where(['student.session_id ' => $session_id , 'student.school_id'=> $compid, 'student.acc_no'=> $accNo])->toArray(); 
		
		return $this->json($get_siblings);

	}
	
	public function getfeecollectdetail()
	{   

		$this->viewBuilder()->layout(false);
		$accNo = $this->request->data('accNo');
		$compid =$this->request->session()->read('company_id');
		$feecollect_table = TableRegistry::get('fee_collection_detail');	
		$session_id = $this->Cookie->read('sessionid');	

		$get_collected_receipts = $feecollect_table->find()->select([ 'deposit_amount', 'deposit_date', 'receipt_no'])->where(['session_id ' => $session_id , 'school_id'=> $compid , 'acc_no' => $accNo ])->order(['id' => 'DESC'])->toArray() ;
		 
		//print_r($get_collected_receipts); die;
		return $this->json($get_collected_receipts);

	}
	
	public function printfee()
	{
		
	}
	
	public function print()
	{
		
	}

		
}
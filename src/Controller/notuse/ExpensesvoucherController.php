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

class ExpensesvoucherController extends AppController

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
	
		$expenses_voucher_table = TableRegistry::get('expenses_voucher');
                $compid =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');    
               
                $retrieve_expenses_voucher = $expenses_voucher_table->find()->order('id')->select(['id', 'voucher_no' ,'pay_to','pay_mode' ,'amount' ,'date' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
                
	        $this->set("expenses_voucher_details", $retrieve_expenses_voucher); 

                $this->viewBuilder()->setLayout('user');
            }


	public function add()
            {   

		$expenses_voucher_table = TableRegistry::get('expenses_voucher');
		$pay_to_table = TableRegistry::get('pay_to');
		$vendor_table = TableRegistry::get('vendor');
		$vehicle_table = TableRegistry::get('vehicles');
		$ex_head_table = TableRegistry::get('ex_head');
		$ex_subhead_table = TableRegistry::get('ex_subhead');
		$vehicle_table = TableRegistry::get('vehicles');
		$employee_table = TableRegistry::get('employee');
                $compid =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');    
               
                $retrieve_expenses_voucher = $expenses_voucher_table->find()->order('id')->select(['id', 'voucher_no' ,'pay_to','pay_mode' ,'amount' ,'date' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->last() ;
		
		$retrieve_pay_to = $pay_to_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_vendor = $vendor_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
		                
                $retrieve_vehicle = $vehicle_table->find()->select(['id', 'vehicle_no' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

           //   $retrieve_ex_head = $ex_head_table->find()->select(['id', 'ex_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id', 'ex_s_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_employee = $employee_table->find()->select(['id', 'e_name' ])->where(['lefts IS NOT' => 1,  'school_id' => $compid ])->toArray() ;

		$todaydate = date('d-m-Y',strtotime('now'));

		$voucher = '';

		if(!empty($retrieve_expenses_voucher)){
		 	$voucher = $retrieve_expenses_voucher['voucher_no'];
		}
		else{
			$voucher = 0;
		}	
		
		$voucher++;
	
	        $number = ['date' => $todaydate , 'voucher_no' => $voucher ];

		$this->set("todaydate", $number); 
                $this->set("expenses_voucher_details", $retrieve_expenses_voucher); 
		$this->set("pay_to_details", $retrieve_pay_to);
		$this->set("vendor_details", $retrieve_vendor);
		$this->set("vehicle_details", $retrieve_vehicle);
		//$this->set("ex_head_details", $retrieve_ex_head);
		$this->set("ex_subhead_details", $retrieve_ex_subhead);
		$this->set("employee_details", $retrieve_employee);

                $this->viewBuilder()->setLayout('user');
            }		


	    public function getheadname()
            {   
                if($this->request->is('post'))
                {

                  $id = $this->request->data['id'];
		  $ex_head_table = TableRegistry::get('ex_head');	
                  $ex_subhead_table = TableRegistry::get('ex_subhead');
                  $compid =$this->request->session()->read('company_id');
                  $session_id = $this->Cookie->read('sessionid'); 
		  

    		  $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id', 'ex_name' ])->where([ 'id' => $id , 'school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
		
    		  $retrieve_ex_head = $ex_head_table->find()->select(['id' , 'ex_name'])->where([ 'ex_name' => $retrieve_ex_subhead[0]['ex_name'] , 'school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
		
                  $data = ['id' => $retrieve_ex_head[0]['id'] , 'name' => $retrieve_ex_head[0]['ex_name'] ];
 
                  return $this->json($data);

                }  
            }


	    public function addexvouchr(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $activ_table = TableRegistry::get('activity');
                    $expenses_voucher_table = TableRegistry::get('expenses_voucher');
                    $vendor_table = TableRegistry::get('vendor');
                    $pay_to_table = TableRegistry::get('pay_to');
                    $compid =$this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');
		
		    $retrieve_vendor = $vendor_table->find()->select(['id' ])->where([ 'name' => $this->request->data('vendor'), 'school_id' => $compid , 'session_id' => $session_id ])->count();

                    $retrieve_pay = $pay_to_table->find()->select(['id' ])->where([ 'name' => $this->request->data('pay_to'), 'school_id' => $compid , 'session_id' => $session_id ])->count();	
			
                    $expenses_voucher = $expenses_voucher_table->newEntity();
                    $expenses_voucher->voucher_no =  $this->request->data('voucher_no');
                    $expenses_voucher->date = date('Y-m-d', strtotime($this->request->data('date'))) ;
                    $expenses_voucher->pay_mode =  $this->request->data('pay_mode');
                    $expenses_voucher->pay_to =  $this->request->data('pay_to');
                    $expenses_voucher->vendor =  $this->request->data('vendor');
                    $expenses_voucher->vehicle_no =  $this->request->data('vehicle_no');
                    $expenses_voucher->prv_reading =  $this->request->data('prv_reading');
                    $expenses_voucher->crrnt_reading =  $this->request->data('crrnt_reading');
                    $expenses_voucher->amount =  $this->request->data('amount');
                    $expenses_voucher->fuel_qty =  $this->request->data('fuel_qty');
                    $expenses_voucher->subhead_id =  $this->request->data('subhead');
                    $expenses_voucher->head_id =  $this->request->data('head');
                    $expenses_voucher->emp_name =  $this->request->data('emp_name');
                    $expenses_voucher->balance =  $this->request->data('balance');
                    $expenses_voucher->remarks =  $this->request->data('remarks');
                    $expenses_voucher->school_id = $compid;
                    $expenses_voucher->session_id = $session_id;
                    
                    if($saved = $expenses_voucher_table->save($expenses_voucher) )
                    {   
                        $expvouchrid = $saved->id;
                        
                            if($retrieve_vendor == 0){     

                            $vendor = $vendor_table->newEntity();

                            $vendor->name =  $this->request->data('vendor') ;
                            $vendor->session_id =  $session_id;
                            $vendor->school_id = $compid;
                            $savedvendor = $vendor_table->save($vendor);    

                            }

                            if($retrieve_pay == 0){

                            $pay = $pay_to_table->newEntity();

                            $pay->name =  $this->request->data('pay_to') ;
                            $pay->session_id =  $session_id;
                            $pay->school_id = $compid;
                            $savedpay = $pay_to_table->save($pay);

                            }

                            if($expvouchrid)
                            {
                            
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Expenses voucher created"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($expvouchrid)   ;
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
                                $res = [ 'result' => 'vendor or paid name not update'  ];
                            }
                    }
                    else{
                        $res = [ 'result' => 'Expenses voucher not saved'  ];
                    }
                                    
                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);

            }	



	    public function edit($id)
            {   
                $expenses_voucher_table = TableRegistry::get('expenses_voucher');
                $pay_to_table = TableRegistry::get('pay_to');
                $vendor_table = TableRegistry::get('vendor');
                $vehicle_table = TableRegistry::get('vehicles');
                $ex_head_table = TableRegistry::get('ex_head');
                $ex_subhead_table = TableRegistry::get('ex_subhead');
                $vehicle_table = TableRegistry::get('vehicles');
                $employee_table = TableRegistry::get('employee');
                $compid =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');

                $retrieve_expenses_voucher = $expenses_voucher_table->find()->select(['id', 'voucher_no' ,'pay_to','pay_mode' , 'date' , 'vehicle_no' , 'prv_reading' , 'crrnt_reading' ,'amount', 'fuel_qty' , 'subhead_id' , 'head_id' , 'emp_name' , 'vendor' , 'balance' , 'remarks' ])->where([ 'md5(id)' => $id, 'school_id' => $compid , 'session_id' => $session_id ])->first() ;
        
                $retrieve_pay_to = $pay_to_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_vendor = $vendor_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
                        
                $retrieve_vehicle = $vehicle_table->find()->select(['id', 'vehicle_no' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_ex_head = $ex_head_table->find()->select(['id', 'ex_name' ])->where(['id' => $retrieve_expenses_voucher['head_id'] ,'school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id', 'ex_s_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_employee = $employee_table->find()->select(['id', 'e_name' ])->where(['lefts IS NOT' => 1,  'school_id' => $compid ])->toArray() ;

                $this->set("voucher_details", $retrieve_expenses_voucher); 
                $this->set("pay_to_details", $retrieve_pay_to);
                $this->set("vendor_details", $retrieve_vendor);
                $this->set("vehicle_details", $retrieve_vehicle);
                $this->set("ex_head_details", $retrieve_ex_head);
                $this->set("ex_subhead_details", $retrieve_ex_subhead);
                $this->set("employee_details", $retrieve_employee); 
                $this->viewBuilder()->setLayout('user');
            }	
	

	   public function getvouchernumber()
            {   
                if($this->request->is('ajax') && $this->request->is('post') )
                {

		  $compid = $this->request->session()->read('company_id');
                  $session_id = $this->Cookie->read('sessionid');  
                  $expenses_voucher_table = TableRegistry::get('expenses_voucher');

		  $retrieve_expenses_voucher = $expenses_voucher_table->find()->order('id')->select(['id', 'voucher_no' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->last() ; 		  
		  $voucher = $retrieve_expenses_voucher['voucher_no'];
		  $voucher++;

	 	  $data = ['voucher_no' => $voucher ];

                  return $this->json($data);

                }  
            }



	    public function editexvouchr(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $activ_table = TableRegistry::get('activity');
                    $expenses_voucher_table = TableRegistry::get('expenses_voucher');
                    $vendor_table = TableRegistry::get('vendor');
                    $pay_to_table = TableRegistry::get('pay_to');
                    $compid =$this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_vendor = $vendor_table->find()->select(['id' ])->where([ 'name' => $this->request->data('vendor'), 'school_id' => $compid , 'session_id' => $session_id ])->count();

                    $retrieve_pay = $pay_to_table->find()->select(['id' ])->where([ 'name' => $this->request->data('pay_to'), 'school_id' => $compid , 'session_id' => $session_id ])->count();
		
                    $id =  $this->request->data('id');
                    $voucher_no =  $this->request->data('voucher_no');
                    $date = date('Y-m-d', strtotime($this->request->data('date'))) ;
                    $pay_mode =  $this->request->data('pay_mode');
                    $pay_to =  $this->request->data('pay_to');
                    $vendor =  $this->request->data('vendor');
                    $vehicle_no =  $this->request->data('vehicle_no');
                    $prv_reading =  $this->request->data('prv_reading');
                    $crrnt_reading =  $this->request->data('crrnt_reading');
                    $amount =  $this->request->data('amount');
                    $fuel_qty =  $this->request->data('fuel_qty');
                    $subhead_id =  $this->request->data('subhead');
                    $head_id =  $this->request->data('head');
                    $emp_name =  $this->request->data('emp_name');
                    $balance =  $this->request->data('balance');
                    $remarks =  $this->request->data('remarks');

                    if( $expenses_voucher_table->query()->update()->set([ 'voucher_no' => $voucher_no, 'date' => $date , 'pay_mode' => $pay_mode , 'pay_to' => $pay_to , 'vendor' => $vendor , 'vehicle_no' => $vehicle_no , 'prv_reading' => $prv_reading, 'crrnt_reading' => $crrnt_reading, 'amount' => $amount, 'fuel_qty' => $fuel_qty, 'subhead_id' => $subhead_id , 'head_id' => $head_id, 'emp_name' => $emp_name, 'balance' => $balance , 'remarks' => $remarks ])->where([ 'id' => $id  ])->execute())
                    {       
                            if($retrieve_vendor == 0){     

                            $vendor = $vendor_table->newEntity();

                            $vendor->name =  $this->request->data('vendor') ;
                            $vendor->session_id =  $session_id;
                            $vendor->school_id = $compid;
                            $savedvendor = $vendor_table->save($vendor);    

                            }

                            if($retrieve_pay == 0){

                            $pay = $pay_to_table->newEntity();

                            $pay->name =  $this->request->data('pay_to') ;
                            $pay->session_id =  $session_id;
                            $pay->school_id = $compid;
                            $savedpay = $pay_to_table->save($pay);

                            }

                            
                            
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Expenses voucher created"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($id)   ;
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
                        $res = [ 'result' => 'Expenses voucher not saved'  ];
                    }
                                    
                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);

            }


	     public function view($id)
            {   
                $expenses_voucher_table = TableRegistry::get('expenses_voucher');
                $pay_to_table = TableRegistry::get('pay_to');
                $vendor_table = TableRegistry::get('vendor');
                $vehicle_table = TableRegistry::get('vehicles');
                $ex_head_table = TableRegistry::get('ex_head');
                $ex_subhead_table = TableRegistry::get('ex_subhead');
                $vehicle_table = TableRegistry::get('vehicles');
                $employee_table = TableRegistry::get('employee');
                $compid =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');

                $retrieve_expenses_voucher = $expenses_voucher_table->find()->select(['id', 'voucher_no' ,'pay_to','pay_mode' , 'date' , 'vehicle_no' , 'prv_reading' , 'crrnt_reading' ,'amount', 'fuel_qty' , 'subhead_id' , 'head_id' , 'emp_name' , 'vendor' , 'balance' , 'remarks' ])->where([ 'md5(id)' => $id, 'school_id' => $compid , 'session_id' => $session_id ])->first() ;
        
                $retrieve_pay_to = $pay_to_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_vendor = $vendor_table->find()->select(['id', 'name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;
                        
                $retrieve_vehicle = $vehicle_table->find()->select(['id', 'vehicle_no' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_ex_head = $ex_head_table->find()->select(['id', 'ex_name' ])->where(['id' => $retrieve_expenses_voucher['head_id'] ,'school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id', 'ex_s_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray() ;

                $retrieve_employee = $employee_table->find()->select(['id', 'e_name' ])->where(['lefts IS NOT' => 1,  'school_id' => $compid ])->toArray() ;

                $this->set("voucher_details", $retrieve_expenses_voucher); 
                $this->set("pay_to_details", $retrieve_pay_to);
                $this->set("vendor_details", $retrieve_vendor);
                $this->set("vehicle_details", $retrieve_vehicle);
                $this->set("ex_head_details", $retrieve_ex_head);
                $this->set("ex_subhead_details", $retrieve_ex_subhead);
                $this->set("employee_details", $retrieve_employee); 
                $this->viewBuilder()->setLayout('user');
            }	


	   public function getvendorname()
            {   
                if($this->request->is('ajax') && $this->request->is('post') )
                {

                    $vendor_table = TableRegistry::get('vendor');
		    $compid =$this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');
	
                    $get_vendor = $vendor_table->find()->select([ 'id' , 'name']) ->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray(); 
 
                    return $this->json($get_vendor);

                }  
            }	


	public function getpaidname()
            {   
                if($this->request->is('ajax') && $this->request->is('post') )
                {

                    $pay_table = TableRegistry::get('pay_to');
		    $compid =$this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');
	
                    $get_pay = $pay_table->find()->select([ 'id' , 'name']) ->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray(); 
 
                    return $this->json($get_pay);

                }  
            }	



	public function voucher($id = null)
            {
                
                $expenses_voucher_table = TableRegistry::get('expenses_voucher');
                $vehicles_table = TableRegistry::get('vehicles');
		$school_table = TableRegistry::get('company');
                $ex_head_table = TableRegistry::get('ex_head');
                $ex_subhead_table = TableRegistry::get('ex_subhead');
                $school_id =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');
                
                $expenses_voucher_details = $expenses_voucher_table->find()->select(['id', 'school_id', 'voucher_no', 'pay_to', 'pay_mode', 'date', 'vehicle_no', 'prv_reading', 'crrnt_reading', 'amount', 'fuel_qty', 'subhead_id', 'head_id', 'emp_name', 'vendor', 'balance', 'remarks'])->where(['md5(id)' => $id ])->first();
    
		$school_details = $school_table->find()->select(['comp_name' , 'add_1'])->where(['id '=> $school_id ])->first() ;

                $vehicle_details = $vehicles_table->find()->select(['vehicle_no'])->where(['id '=> $expenses_voucher_details['vehicle_no'] , 'school_id' => $school_id , 'session_id' => $session_id ])->first() ;

                $ex_head_details = $ex_head_table->find()->select(['ex_name'])->where(['id '=> $expenses_voucher_details['head_id'] , 'school_id' => $school_id , 'session_id' => $session_id ])->first() ;

                $ex_subhead_details = $ex_subhead_table->find()->select(['ex_s_name'])->where(['id '=> $expenses_voucher_details['subhead_id'] , 'school_id' => $school_id , 'session_id' => $session_id ])->first() ;
                
                   $number = (int) $expenses_voucher_details['amount'];
                   $no = floor($number);
                   $point = round(($number - $no), 2) * 100;
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
               
            $dompdf = new Dompdf();
                       $dompdf->loadHtml('<div style="width: 100%;" >
    <table style="padding:0px 40px 0px 40px; width: 100%;">
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%; text-align: center;" >
                        <tr>
                            <td style="font-weight: bold; font-size: x-large;">'. $school_details['comp_name'] .'</td>
                        </tr>
                        <tr>    
                            <td style="font-weight: bold;">'. $school_details['add_1'] .'</td>
                        </tr>   
                                                
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%; text-align: center; margin-top: 40px;">
                        <tr>
                            <td></td>
                            <td style="font-weight: bold;font-size: x-large; border: 1px solid black; border-radius: 10px;width: 40%;">Expense Voucher</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>    
                <td>
                    <table style="border: 1px solid black; width: 100%; padding: 10px 50px 10px 10px ;">
                        <tr>
                            <th style="text-align: left;">Voucher No: '. $expenses_voucher_details['voucher_no'] .'</th>
                            <th style="text-align: right;">Date: '. date('d/m/Y',strtotime($expenses_voucher_details['date'])) .' </th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%; ">
                        <tr>
                            <td style="padding: 10px 0px; width: 70%;"><strong style="display: inline-block; text-align: right;width: 150px;">  Pay To : </strong> <span style="font-family:monospace; ">'. $expenses_voucher_details['pay_to'] .'</span></td>
                            <td style="padding-left: 40px ;"> <strong style="display: inline-block;"> Amount : </strong> <span  > '. $expenses_voucher_details['amount'] .'</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 10px 0px;"><strong style=" display: inline-block;text-align: right;width: 150px;">Expense Head : </strong> <span style="font-family:monospace;">'. $ex_head_details['ex_name'] .'</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 10px 0px;"><strong style=" display: inline-block;text-align: right;width: 150px;">Amount in Words : </strong> <span>'. $amt_words .'</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 10px 0px;"><strong style=" display: inline-block;text-align: right;width: 150px;">Narration : </strong> <span >'. $expenses_voucher_details['remarks'] .'</span> </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%; margin-top: 100px; ">
                        <tr>
				<td style="text-align: center; padding: 0px 30px;">
					<hr style="border: 1px solid;padding: 1px;"><br><p>Prepared By</p>
				</td>
				<td style="text-align: center; padding: 0px 30px;">
					<hr style=" border: 1px solid;padding: 1px; "><br><p>Prepared By</p>
				</td>
				<td style="text-align: center; padding: 0px 30px;">
					<hr style=" border: 1px solid;padding: 1px; "><br><p>Prepared By</p>
				</td>
			</tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="position: relative;">
		    	<p style="border:1px dashed #000; margin: 50px 0px;position: relative; "></p>
                    	<img style="position: absolute; right: 80px; top: 35px;" src="img/cut.jpg" height="20" width="50" alt="cut">
                </td>
            </tr>
        </tbody>
    </table>

</div>');
                
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
                // Render the HTML as PDF
                $dompdf->render();
                
                // Output the generated PDF to Browser
                $dompdf->stream();
            
            }



	
}
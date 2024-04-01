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
class IncomeController  extends AppController
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

                $this->viewBuilder()->setLayout('user');
                
                $income_table = TableRegistry::get('income');
		$incomeh_table = TableRegistry::get('income_head');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_income = $income_table->find()->select(['id' , 'in_h_id' ,'amount', 'added_date'  ])->where([ 'school_id' => $compid , 'session_id' => $session_id ])->toArray();
				
		$retrieve_incomeh = $incomeh_table->find()->select(['id' ,'in_name' ])->where([ 'school_id' => $compid , 'session_id' => $session_id ])->toArray();
                
		$retrieve_voucher = $income_table->find()->select(['voucher_no'])->where([ 'school_id' => $compid , 'session_id' => $session_id ])->last();

		$todaydate = date('d-m-Y',strtotime('now'));
		
		$voucher = '';

		if(!empty($retrieve_voucher)){
		 	$voucher = $retrieve_voucher['voucher_no'];
		}
		else{
			$voucher = 0;
		}	
		
		$voucher++;
	
	        $number = ['date' => $todaydate , 'voucher_no' => $voucher ];

                $this->set("income_details", $retrieve_income);  
		$this->set("incomeh_details", $retrieve_incomeh);
		$this->set("todaydate", $number);  

            }

            

            public function addinc(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $income_table = TableRegistry::get('income');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_income = $income_table->find()->select(['id' ])->where(['in_h_id' => $this->request->data('name') , 'school_id'=> $compid , 'session_id' => $session_id  ])->count() ;
                    
                        $income = $income_table->newEntity();
			$income->voucher_no = $this->request->data('voucher_no');
                        $income->in_h_id =  $this->request->data('name');
                        $income->amount = $this->request->data('amount') ;
			$income->date = date('Y-m-d', strtotime($this->request->data('vouchrdate'))) ;
			$income->remarks = $this->request->data('remarks') ;
                        $income->school_id = $compid  ;
			$income->session_id = $session_id;	
                        $income->added_date = strtotime('now');
                        if($saved = $income_table->save($income) ){

                            $activity = $activ_table->newEntity();
                            $activity->action =  "Income Voucher Created"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
        
                            $activity->value = md5($saved->id)   ;
                            $activity->origin = $this->Cookie->read('id')   ;
                            $activity->created = strtotime('now');
                            if($saved = $activ_table->save($activity) ){
                                $res = [ 'result' => 'success'  ];
    
                            }
                            else{
                        $res = [ 'result' => 'activity not saved'];
    
                            }
    
                        }
                        else{
                            $res = [ 'result' => 'income not saved'  ];
                        }
    
                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }
            

            public function update()
            {   
                if($this->request->is('post')){

                $id = $this->request->data['id'];
                
                $income_table = TableRegistry::get('income');

                $update_income = $income_table->find()->select(['in_h_id' , 'voucher_no' , 'amount' , 'date' , 'remarks' ])->where(['id' => $id])->toArray(); 
                    
                $data = ['name' => $update_income[0]['in_h_id'] , 'voucher_no' => $update_income[0]['voucher_no'] , 'amount'=> $update_income[0]['amount'] , 'date'=> date('d-m-Y',strtotime($update_income[0]['date'])) , 'remarks'=> $update_income[0]['remarks'] ];
                
                return $this->json($data);

                }  
            }


            public function editinc(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $income_table = TableRegistry::get('income');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');
                    $retrieve_income = $income_table->find()->select(['id' ])->where(['in_h_id' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid ])->count() ;
                    
                   

                        $id = $this->request->data('id');
			$voucher_no = $this->request->data('voucher_no');
                        $name =  $this->request->data('name');
                        $amount = $this->request->data('amount');
			$date =  date('Y-m-d', strtotime($this->request->data('vouchrdate')));
			$remarks =  $this->request->data('remarks');
                        
                        if( $income_table->query()->update()->set(['voucher_no' => $voucher_no , 'in_h_id' => $name , 'amount'=> $amount , 'date' => $date , 'remarks' => $remarks  ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Income Voucher Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
        
                            $activity->value = md5($id);
                            $activity->origin = $this->Cookie->read('id');
                            $activity->created = strtotime('now');
                            if($saved = $activ_table->save($activity))
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
                            $res = [ 'result' => 'income not updated'  ];
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
                $income_table = TableRegistry::get('income');
                $activ_table = TableRegistry::get('activity');
                
                $incomeid = $income_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($incomeid)
                {                       
                    $del = $income_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Income Deleted"  ;
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

            public function getdata(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                $income_table = TableRegistry::get('income');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

		$employee_table = TableRegistry::get('employee');

                $retrieve_income = $income_table->find()->select(['income.id' , 'income.voucher_no' , 'income.in_h_id' ,'income.amount', 'income.date' , 'income_head.in_code', 'income_head.in_name' ])->join(['income_head' => 
							[
							'table' => 'income_head',
							'type' => 'LEFT',
							'conditions' => 'income_head.id = income.in_h_id'
						]
					])->where([ 'income.school_id' => $compid , 'income.session_id'=> $session_id ])->toArray();
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
        	    $employee_id = $this->Cookie->read('id');
            	    $retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
              	    $emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}
	
		
	
                $data = "";
                $datavalue = array();
                foreach ($retrieve_income as $value) {
                   
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editincs" data-toggle="modal"  data-target="#editinc" title="Edit"><i class="fa fa-edit"></i></button>';

		$delete = '<button type="button" data-url="income/delete" data-id='.$value['id'].' data-str="Income Voucher" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
		
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
	            $e = in_array(93, $emp_privilage) ? $edit : "";
        	    $d = in_array(94, $emp_privilage) ? $delete : "";
        	}
     	  	else
        	{
            		$e = $edit;
            		$d = $delete;
        	}
 
                $data .=  '<tr>
                            <td class="width45">
                            <label class="fancy-checkbox">
                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$value['income_head']['in_name'].' </span>
                            </td>
			    <td>
                                <span>'.$value['voucher_no'].'</span>
                            </td>	
                            <td>
                                <span>'.$value['amount'].'</span>
                            </td>
                            <td>
                                <span>'.date('d-m-Y',strtotime($value['date'])).'</span>
                            </td>
                            <td>
                            	'.$e.$d.'    
                            </td>
                        </tr>';

                    
                }

                $datavalue['html']= $data;
                
                return $this->json($datavalue);

                }
            }


	public function getvoucherno()
            {  
		
                if($this->request->is('ajax') && $this->request->is('post') )
                {

                $income_table = TableRegistry::get('income');
		$compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $get_voucher_no = $income_table->find()->select([ 'voucher_no'])->where(['school_id' => $compid , 'session_id'=> $session_id ])->last(); 
		
		if(!empty($get_voucher_no)){
		  $voucher_no = $get_voucher_no['voucher_no'];
		}
		else{
		   $voucher_no = 0;	
		}
		$voucher_no++;

		$data = ['voucher_no' => $voucher_no ];

		return $this->json($data);

                }  
            } 
	




            
    }

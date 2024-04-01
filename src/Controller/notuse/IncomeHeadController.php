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
class IncomeHeadController  extends AppController
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

                $in_head_table = TableRegistry::get('income_head');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
				
                $retrieve_ex_head = $in_head_table->find()->select(['id' , 'in_code'  ,'in_name'])->where([ 'school_id'=> $compid  ])->toArray();

                $this->set("ex_head_details", $retrieve_ex_head); 
                $this->viewBuilder()->setLayout('user');

            }

            public function addinhead(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $in_head_table = TableRegistry::get('income_head');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_in_head = $in_head_table->find()->select(['id'  ])->where(['in_name' => $this->request->data('name') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_in_head == 0 ){

                        $head = $in_head_table->newEntity();
                        $head->in_name =  $this->request->data('name');
                        $head->school_id =  $compid;
			$head->session_id = $session_id;
                        if($saved = $in_head_table->save($head) ){

                            $activity = $activ_table->newEntity();
                            $activity->action =  "Head Income Created"  ;
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
                            $res = [ 'result' => 'expenses not saved'  ];
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

            public function update()
            {   
                if($this->request->is('post')){

                $id = $this->request->data['id'];
                
                $ex_head_table = TableRegistry::get('income_head');

                $update_ex_head = $ex_head_table->find()->select(['in_name' ])->where(['id' => $id])->toArray(); 
                    
                $data = ['name'=>$update_ex_head[0]['in_name']];
                
                return $this->json($data);

                }  
            }


            public function editinhead(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $ex_head_table = TableRegistry::get('income_head');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_inc_head = $ex_head_table->find()->select(['id'])->where(['in_name' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_inc_head == 0 ){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name');
                        $now = strtotime('now');
                        
                        if( $ex_head_table->query()->update()->set([ 'in_name' => $name  ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Income Head Updated"  ;
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
                            $res = [ 'result' => 'Income head not updated'  ];
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
                $in_head_table = TableRegistry::get('income_head');
				$income_table = TableRegistry::get('income');
                $activ_table = TableRegistry::get('activity');
                
                $headid = $in_head_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($headid)
                {                       
                    if($in_head_table->delete($in_head_table->get($rid)))
                    {
			$del = $income_table->query()->delete()->where([ 'in_h_id' => $rid ])->execute(); 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Head Income Deleted"  ;
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

                $in_head_table = TableRegistry::get('income_head');
                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');
		
		$employee_table = TableRegistry::get('employee');

                $retrieve_in_head = $in_head_table->find()->select(['id' ,'in_name'])->where([ 'school_id'=> $compid , 'session_id' => $session_id ])->toArray();
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
        	    $employee_id = $this->Cookie->read('id');
            	    $retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
              	    $emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}
		
                $data = "";
                $datavalue = array();
                foreach ($retrieve_in_head as $value) {
                    
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editinhead" data-toggle="modal"  data-target="#editinhead" title="Edit"><i class="fa fa-edit"></i></button>';

		$delete = '<button type="button" data-url="incomeHead/delete" data-id='.$value['id'].' data-str="Income Head" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
	            $e = in_array(51, $emp_privilage) ? $edit : "";
        	    $d = in_array(52, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['in_name'].'</span>
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

}
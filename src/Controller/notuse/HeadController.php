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
class HeadController  extends AppController
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

                $ex_head_table = TableRegistry::get('ex_head');
                $compid = $this->request->session()->read('company_id');

                $retrieve_ex_head = $ex_head_table->find()->select(['id' , 'ex_code'  ,'ex_name'])->where([ 'school_id'=> $compid  ])->toArray();

                $this->set("ex_head_details", $retrieve_ex_head); 
                $this->viewBuilder()->setLayout('user');

            }

            public function addexhead(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $ex_head_table = TableRegistry::get('ex_head');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_ex_head = $ex_head_table->find()->select(['id'  ])->where(['ex_code' => $this->request->data('code') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_ex_head == 0 ){

                        $head = $ex_head_table->newEntity();
                        $head->ex_name =  $this->request->data('name');
                        $head->ex_code = $this->request->data('code');
                        $head->school_id =  $compid;
			$head->session_id = $session_id;
                        if($saved = $ex_head_table->save($head) ){

                            $activity = $activ_table->newEntity();
                            $activity->action =  "Head expenses Created"  ;
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
                
                $ex_head_table = TableRegistry::get('ex_head');

                $update_ex_head = $ex_head_table->find()->select(['ex_name' , 'ex_code'])->where(['id' => $id])->toArray(); 
                    
                $data = ['code' => $update_ex_head[0]['ex_code'] , 'name'=>$update_ex_head[0]['ex_name']];
                
                return $this->json($data);

                }  
            }


            public function editexhead(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $ex_head_table = TableRegistry::get('ex_head');
		    $ex_subhead_table = TableRegistry::get('ex_subhead');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_ex_head_count = $ex_head_table->find()->select(['id'])->where([ 'ex_name' => $this->request->data('name') , 'id IS NOT' => $this->request->data('id') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;

                    $retrieve_ex_head = $ex_head_table->find()->select(['ex_name'])->where(['id' => $this->request->data('id') , 'school_id'=> $compid , 'session_id' => $session_id ])->first() ;
                    
                    $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id'])->where(['ex_name' => $retrieve_ex_head['ex_name'] , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;

                    if($retrieve_ex_head_count == 0 ){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name');
                        $code = $this->request->data('code');
                        $now = strtotime('now');
                        
                        if( $ex_head_table->query()->update()->set([ 'ex_name' => $name , 'ex_code'=> $code ])->where([ 'id' => $id  ])->execute())
                        {
			    if($retrieve_ex_subhead > 0)
			    {
				$ex_s_name = $ex_subhead_table->query()->update()->set([ 'ex_name' => $this->request->data('name') ])->where([ 'ex_name' => $retrieve_ex_head['ex_name']  ])->execute();
			    }	

                            $activity = $activ_table->newEntity();
                            $activity->action =  "Head expenses Updated"  ;
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
                            $res = [ 'result' => 'Head expenses not updated'  ];
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
                $ex_head_table = TableRegistry::get('ex_head');
		$ex_subhead_table = TableRegistry::get('ex_subhead');
                $activ_table = TableRegistry::get('activity');
                $compid =$this->request->session()->read('company_id');
                $session_id = $this->Cookie->read('sessionid');

                $headid = $ex_head_table->find()->select(['id' , 'ex_name'])->where(['id'=> $rid ])->first();
                                    
                $retrieve_ex_subhead = $ex_subhead_table->find()->select(['id'])->where(['ex_name' => $headid['ex_name'] , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;

                if($headid)
                {                       
                    if($ex_head_table->delete($ex_head_table->get($rid)))
                    {
			$del = $ex_subhead_table->query()->delete()->where([ 'ex_name' => $headid['ex_name'] , 'school_id'=> $compid , 'session_id' => $session_id ])->execute();
 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Head Expenses Deleted"  ;
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

                $ex_head_table = TableRegistry::get('ex_head');
                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

		$employee_table = TableRegistry::get('employee');

                $retrieve_ex_head = $ex_head_table->find()->select(['id' , 'ex_code'  ,'ex_name'])->where([ 'school_id'=> $compid , 'session_id' => $session_id ])->toArray();
                

		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$employee_id = $this->Cookie->read('id');
            		$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid ])->toArray() ; 
                
              		$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}


                $data = "";
                $datavalue = array();
                foreach ($retrieve_ex_head as $value) {

		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editexhead" data-toggle="modal"  data-target="#editexhead" title="Edit"><i class="fa fa-edit"></i></button>';                    

		$delete = '<button type="button" data-url="head/delete" data-id='.$value['id'].' data-str="Head Expenses" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$e = in_array(31, $emp_privilage) ? $edit : "";
            		$d = in_array(32, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['ex_code'].'</span>
                            </td>
                            <td>
                                <span>'.$value['ex_name'].'</span>
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
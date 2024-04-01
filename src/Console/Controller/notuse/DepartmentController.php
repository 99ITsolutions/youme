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
class DepartmentController  extends AppController
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

                $dprtmnt_table = TableRegistry::get('department');
                $compid = $this->request->session()->read('company_id');

                $retrieve_dprtmnt = $dprtmnt_table->find()->select(['id' ,'dprt_name' , 'added_date'])->where([ 'school_id'=> $compid  ])->toArray();

                $this->set("dprtmnt_details", $retrieve_dprtmnt); 
                $this->viewBuilder()->setLayout('user');

            }

            public function adddprt(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $dprtmnt_table = TableRegistry::get('department');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');
	
                    $retrieve_dprtmnt = $dprtmnt_table->find()->select(['id'  ])->where(['dprt_name' => $this->request->data('dprt_name') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_dprtmnt == 0 ){

                        $dprtmnt = $dprtmnt_table->newEntity();
                        $dprtmnt->dprt_name =  $this->request->data('dprt_name');
                        $dprtmnt->school_id =  $compid ;
			$dprtmnt->session_id = $session_id;
                        $dprtmnt->added_date =  strtotime('now') ;
                        if($saved = $dprtmnt_table->save($dprtmnt) ){

                            $activity = $activ_table->newEntity();
                            $activity->action =  "Department Created"  ;
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
                
                $dprtmnt_table = TableRegistry::get('department');

                $update_dprtmnt = $dprtmnt_table->find()->select(['dprt_name'])->where(['id' => $id])->toArray(); 
                    
                $data = ['name' => $update_dprtmnt[0]['dprt_name'] ];
                
                return $this->json($data);

                }  
            }


            public function editdprt(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $dprtmnt_table = TableRegistry::get('department');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_dprtmnt = $dprtmnt_table->find()->select(['id'  ])->where(['dprt_name' => $this->request->data('dprt_name'), 'id IS NOT' => $this->request->data('id') , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_dprtmnt == 0 ){

                        $id = $this->request->data('id');
                        $dprt_name =  $this->request->data('dprt_name')  ;
                        
                        if( $dprtmnt_table->query()->update()->set([ 'dprt_name' => $dprt_name ])->where([ 'id' => $id  ])->execute())
                        {
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
                            $res = [ 'result' => 'Department not updated'  ];
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
                $dprtmnt_table = TableRegistry::get('department');
                $activ_table = TableRegistry::get('activity');
                
                $dprtmnt_id = $dprtmnt_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($dprtmnt_id)
                {                       
                    if($dprtmnt_table->delete($dprtmnt_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Department Deleted"  ;
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

                $dprtmnt_table = TableRegistry::get('department');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$employee_table = TableRegistry::get('employee');

                $retrieve_dprtmnt = $dprtmnt_table->find()->select(['id' ,'dprt_name' , 'added_date'])->where(['session_id' => $session_id, 'school_id'=> $compid  ])->toArray();
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$employee_id = $this->Cookie->read('id');
            		$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
              		$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}

                $data = "";
                $datavalue = array();
                foreach ($retrieve_dprtmnt as $value) {
        
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editdprts" data-toggle="modal"  data-target="#editdprt" title="Edit"><i class="fa fa-edit"></i></button>';

		$delete = '<button type="button" data-url="department/delete" data-id='.$value['id'].' data-str="Department" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

		if($this->Cookie->read('logtype') == md5('Employee'))
       	 	{
            		$e = in_array(59, $emp_privilage) ? $edit : "";
            		$d = in_array(60, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['dprt_name'].'</span>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.date('d-m-Y',$value['added_date']).'</span>
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
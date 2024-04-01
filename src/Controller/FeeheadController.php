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
class FeeheadController  extends AppController
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
                $feehead_table = TableRegistry::get('feehead');
                $compid =$this->request->session()->read('company_id');
	            $this->request->session()->write('LAST_ACTIVE_TIME', time());
	            if(!empty($compid))
	            {
                    $retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name' , 'added_date' ])->where(['school_id' => $compid])->toArray();
                    $this->set("feehead_details", $retrieve_feehead);  
	            }
	            else
	            {
	                return $this->redirect('/login/') ;   
	            }
            }


            public function addfeehead(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $feehead_table = TableRegistry::get('feehead');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($compid))
                    {
                    $retrieve_feehead = $feehead_table->find()->select(['id'  ])->where(['head_name' => $this->request->data('head_name'), 'school_id' => $compid  ])->count() ;
                    
                    if($retrieve_feehead == 0 ){
                        $feehead = $feehead_table->newEntity();
		
						$headName = strtolower($this->request->data('head_name'));
						if ($headName == "tuition fee")
						{
							$headN = "Tuition Fee";
							$feehead->head_name =  $headN;
						}
						else
						{
							$feehead->head_name =  $this->request->data('head_name');
						}
                        
						/*$feehead->head_frequency =  $this->request->data('head_frequency');
						$feehead->months = implode(',' , $this->request->data('months'));*/
                        $feehead->school_id = $compid;
                        $feehead->added_date = strtotime('now');
                        if($saved = $feehead_table->save($feehead) ){
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fee Head Created"  ;
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
                    else
                    {
                        return $this->redirect('/login/') ;   
                    }
                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);

            }
            

            public function update()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['id'];
                    $feehead_table = TableRegistry::get('feehead');
                    $update_feehead = $feehead_table->find()->select(['head_name'])->where(['id' => $id])->toArray(); 
    				//$months = explode(',' , $update_feehead[0]['months']);  
                    $data = ['name' => $update_feehead[0]['head_name']];
                    return $this->json($data);
                }  
            }



            public function editfeehead()
            {
                if ($this->request->is('ajax') && $this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $feehead_table = TableRegistry::get('feehead');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');
                    if(!empty($compid))
                    {
                    $retrieve_feehead = $feehead_table->find()->select(['id'  ])->where(['head_name' => $this->request->data('head_name'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid ])->count() ;
                    
                    if($retrieve_feehead == 0 ){

                        $id = $this->request->data('id');
						$headName =  strtolower($this->request->data('head_name'))  ;
						if ($headName == "tuition fee")
						{
							$headN = "Tuition Fee";
							$head_name =  $headN;
						}
						else
						{
							$head_name =  $this->request->data('head_name');
						}
                        
                        /*$head_frequency =  $this->request->data('head_frequency');
						$months = implode(',' , $this->request->data('months'));*/

                        if( $feehead_table->query()->update()->set([ 'head_name' => $head_name])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fee Head Updated"  ;
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
                    else
                    {
                        return $this->redirect('/login/') ;   
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
                $feehead_table = TableRegistry::get('feehead');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $feeheadid = $feehead_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($feeheadid)
                {   
                    if($feehead_table->delete($feehead_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Fee Head Deleted"  ;
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
            

            public function getdata()
			{
                if ($this->request->is('ajax') && $this->request->is('post'))
				{
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
					$feehead_table = TableRegistry::get('feehead');
					$compid =$this->request->session()->read('company_id');
					//$session_id = $this->Cookie->read('sessionid') ;
					$employee_table = TableRegistry::get('employee');
					$feedetail_table = TableRegistry::get('feedetail');
                    if(!empty($compid))
                    {
					$retrieve_feehead = $feehead_table->find()->select(['id' , 'head_name' , 'added_date' ])->where(['school_id' => $compid ])->toArray();
                
					$data = "";
					$datavalue = array();
					$sclsub_table = TableRegistry::get('school_subadmin');
    				if($this->Cookie->read('logtype') == md5('School Subadmin'))
    				{
    				    
    					$sclsub_id = $this->Cookie->read('subid');
    					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
    					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
    					//print_r($scl_privilage); 
    				}
    				
					foreach ($retrieve_feehead as $value) 
					{
						$studentsname = [];
						$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editfeeheads" data-toggle="modal"  data-target="#editfeehead" title="Edit"><i class="fa fa-edit"></i></button>';   
						$delete = '<button type="button" data-url="feehead/delete" data-id='.$value['id'].' data-str="Fee head" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

                        $d = '';
						if($this->Cookie->read('logtype') == md5('School Subadmin'))
						{
							$e = in_array(35, $scl_privilage) ? $edit : "";
							$d = in_array(36, $scl_privilage) ? $delete : "";
						}
						else
						{
							$e = $edit;
							$d = $delete;
						}
						
						if($value['feedtlid'] == "")
						{
							$de = $d;
						}
						else
						{
							$de = "";
						}
						

						$data .=  '<tr>
									<td class="width45">
									<label class="fancy-checkbox">
											<input class="checkbox-tick" type="checkbox" name="checkbox">
											<span></span>
										</label>
									</td>
									<td>
										<span class="mb-0 font-weight-bold">'.ucwords($value['head_name']).'</span>
									</td>
						
									<td>
										<span>'.date('d-m-Y',$value['added_date']).'</span>
									</td>
									<td>
										'.$e.$de.'     
									</td>
								</tr>';
							
					}
                
					$datavalue['html']= $data;  
					return $this->json($datavalue);
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
                    }
                }
            }



            
    }

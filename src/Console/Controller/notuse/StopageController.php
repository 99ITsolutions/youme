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
class StopageController  extends AppController
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
                
                $stopage_table = TableRegistry::get('routechg');
				
                $compid = $this->request->session()->read('company_id');
         	$route_table = TableRegistry::get('routes');

		$session_id = $this->Cookie->read('sessionid');

                $retrieve_stopage = $stopage_table->find()->select(['routechg.id' , 'routechg.village' ,'routechg.amount', 'routes.route_name', 'routes.number' , 'routechg.route_id' ])->join([
                        'routes' => [
                            'table' => 'routes',
                            'type' => 'LEFT',
                            'conditions' =>  'routes.id =  routechg.route_id' 
                        ]
                    ])->where([ 'routechg.school_id' => $compid , 'routechg.session_id' => $session_id  ])->toArray();
		
		$retrieve_route = $route_table->find()->select(['id' , 'number' ,'route_name'  ])->where([ 'school_id' => $compid , 'session_id' => $session_id    ])->toArray();
                

                $this->set("stopage_details", $retrieve_stopage); 
		$this->set("route_details", $retrieve_route);  				

            }

            

            public function addstop(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $stopage_table = TableRegistry::get('routechg');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');

		    $session_id = $this->Cookie->read('sessionid');	
                    
                    $retrieve_stopage = $stopage_table->find()->select(['id' ])->where(['village' => $this->request->data('name') , 'school_id'=> $compid, 'session_id' => $session_id ])->count() ;

                    if($retrieve_stopage == 0 ){

                        $stopage = $stopage_table->newEntity();
                        $stopage->village =  $this->request->data('name')  ;
                        $stopage->amount = $this->request->data('amount') ;
			$stopage->route_id = $this->request->data('stopage_route') ;
                        $stopage->school_id = $compid  ;
			$stopage->session_id = $session_id  ;
			$stop_route = $this->request->data('stopage_route') ;
						
                        if($saved = $stopage_table->save($stopage) ){
							$rid = $saved->id;
							$update = $stopage_table->query()->update()->set([ 'route_id' => $stop_route ])->where([ 'id' => $rid  ])->execute();
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Stopage Created"  ;
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
                            $res = [ 'result' => 'stopage not saved'  ];
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
                
                $stopage_table = TableRegistry::get('routechg');
				$route_table = TableRegistry::get('routes');

                $update_stopage = $stopage_table->find()->select(['village' , 'amount', 'route_id' ])->where(['id' => $id])->toArray(); 
                    
                
				
				$get_route_stopage = $stopage_table->find()->select(['amount' , 'village'])->where([ 'route_id' => $update_stopage[0]['route_id']  ])->toArray(); 
				
				$data = ['name' => $update_stopage[0]['village'] , 'amount'=> $update_stopage[0]['amount'], 'routeId'=> $update_stopage[0]['route_id'], 'routelist' => $get_route_stopage ];
                
                return $this->json($data);

                }  
            }


            public function editstop(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $stopage_table = TableRegistry::get('routechg');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');

                    $retrieve_stopage = $stopage_table->find()->select(['id' ])->where(['village' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_stopage == 0 ){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name');
                        $amount = $this->request->data('amount');
						$routeid = $this->request->data('estopage_route');
                        
                        if( $stopage_table->query()->update()->set([ 'village' => $name , 'amount'=> $amount, 'route_id' => $routeid ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Stopage Updated"  ;
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
                            $res = [ 'result' => 'stopage not updated'  ];
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
                $stopage_table = TableRegistry::get('routechg');
                $activ_table = TableRegistry::get('activity');
                
                $stopageid = $stopage_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($stopageid)
                {                       
                    $del = $stopage_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Stopage Deleted"  ;
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

                $stopage_table = TableRegistry::get('routechg');
                $compid = $this->request->session()->read('company_id');

		$employee_table = TableRegistry::get('employee');

		$session_id = $this->Cookie->read('sessionid');

                $route_table = TableRegistry::get('routes');
                $retrieve_stopage = $stopage_table->find()->select(['routechg.id' , 'routechg.village' ,'routechg.amount', 'routes.route_name', 'routes.number'])->join([
                        'routes' => [
                            'table' => 'routes',
                            'type' => 'LEFT',
                            'conditions' =>  'routes.id =  routechg.route_id' 
                        ]
                    ])->where([ 'routechg.school_id' => $compid , 'routechg.session_id'=>$session_id  ])->toArray();


		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$employee_id = $this->Cookie->read('id');
            		$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
              		$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}
		                

                $data = "";
                $datavalue = array();
                foreach ($retrieve_stopage as $value) {
                    
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editstops" data-toggle="modal"  data-target="#editstop" title="Edit"><i class="fa fa-edit"></i></button>';

		$delete = '<button type="button" data-url="stopage/delete" data-id='.$value['id'].' data-str="Stopage" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$e = in_array(55, $emp_privilage) ? $edit : "";
            		$d = in_array(56, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['routes']['number'].'</span>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$value['village'].'</span>
                            </td>
                            <td>
                                <span>'.$value['amount'].'</span>
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
			
			public function importstoppage()
            {   
			
                if ( $this->request->is('post'))
                {   
			
					$schoolid = $this->request->session()->read('company_id');

					$session_id = $this->Cookie->read('sessionid');

					$route_table = TableRegistry::get('routes');
                    $routechg_table = TableRegistry::get('routechg');
                    $activ_table = TableRegistry::get('activity');
                    
					
                    if(!empty($this->request->data['file']['name']))
                    {
                        $fileexe = explode('.', $this->request->data['file']['name']);
                    
                        if($fileexe[1] =='csv')
                        {

                            $filename = $this->request->data['file']['tmp_name'];
                            
                            $handle = fopen($filename, "r");
                            
                            $header = fgetcsv($handle);
                            $i = 0;
                            
							while (($row = fgetcsv($handle)) !== FALSE) 
                            {		
						
                                $i++;
                                //$adm_no++;
                                $data = array();
								//$routeNum = $row[0];
                                $route = str_replace("#", ",", $row[0]);								
								$amt = $row[1];
								
								//$routeId = $route_table->find()->select(['id'])->where(['number' => $routeNum])->toArray(); 
								
								$stopage_amt = $routechg_table->newEntity();
								
								$stopage_amt->village =  $route;
								$stopage_amt->amount =  $amt;
								//$stop_route =  $routeId[0]['id'];
								$stopage_amt->school_id =  $schoolid;
								$stopage_amt->session_id = $session_id  ;
							
							
									
									$saved = $routechg_table->save($stopage_amt);
									
									if($saved)
									{  
										$rid = $saved->id;
										//$update = $routechg_table->query()->update()->set([ 'route_id' => $stop_route ])->where([ 'id' => $rid  ])->execute();
										$res = [ 'result' => 'success'  ];
										//return $this->redirect('/promotion');
									}
									else{
										$res = [ 'result' => 'Error! File not uploaded.'  ];
									}
								/*} 
								else
								{
									$res = [ 'result' => 'Error! File not uploaded. (Tabs should not blank)'  ];
								} */ 

                            }
							
                        fclose($handle);
                        
                        }
                        else
                        {
                            $res = [ 'result' => 'Only csv format file are allowed'];

                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Empty file'  ];

                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);    

            }
			
			public function routestoppage()
            {   
                if($this->request->is('post')){

                $routeId = $this->request->data('routeId');
                
                $stopage_table = TableRegistry::get('routechg');

                $update_stopage = $stopage_table->find()->select(['village' , 'amount' ])->where(['route_id' => $routeId])->toArray();  
              
				return $this->json($update_stopage);

                }  
            }


            
    }

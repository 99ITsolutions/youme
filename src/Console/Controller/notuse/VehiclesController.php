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
class VehiclesController  extends AppController
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

                $vehicles_table = TableRegistry::get('vehicles');
		$school_id =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_vehicles = $vehicles_table->find()->select(['id','driver_name','driver_mobile_number','vehicle_type', 'purchase_date' ,'route_no', 'vehicle_no'])->where(['school_id' => $school_id , 'session_id'=> $session_id ])->toArray() ;
                
                $this->set("vehicles_details", $retrieve_vehicles);
                $this->viewBuilder()->setLayout('user');
            }

            public function add(){

                $routes_table = TableRegistry::get('routes');
				$school_id =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');

                $retrieve_routes = $routes_table->find()->select(['id','number','route_name'])->where(['school_id' => $school_id , 'session_id'=> $session_id ])->toArray() ;
                
                $this->set("routes_details", $retrieve_routes);
                $this->viewBuilder()->setLayout('user');
            }

            public function addvhcl(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $vehicle_table = TableRegistry::get('vehicles');
                    $activ_table = TableRegistry::get('activity');
                    
                    $school_id =$this->request->session()->read('company_id');
					$session_id = $this->Cookie->read('sessionid');	

                    $retrieve_vehicle = $vehicle_table->find()->select(['id'  ])->where(['driver_mobile_number' => $this->request->data('driver_mobile_number') ,'school_id' => $school_id , 'session_id'=> $session_id ])->count() ;

                    //if($retrieve_vehicle == 0 )
                    //{   
						if(!empty($this->request->data('route_no')))
						{
							$route_no = implode(',', $this->request->data('route_no'));
						}
						else
						{
							$route_no = "";
						}
                        
                        
                        $vehicle = $vehicle_table->newEntity();

                        $vehicle->vehicle_no =  $this->request->data('vehicle_no')  ;
                        $vehicle->purchase_date =  date('Y-m-d', strtotime($this->request->data('purchase_date')))  ;
                        $vehicle->vehicle_type = $this->request->data('vehicle_type') ;
                        $vehicle->vehicle_fitness_date = date('Y-m-d', strtotime($this->request->data('vehicle_fitness_date'))) ;
                        $vehicle->vehicle_insurance = date('Y-m-d', strtotime($this->request->data('vehicle_insurance'))) ;
                        $vehicle->passing_date =  date('Y-m-d', strtotime($this->request->data('passing_date')));
                        $vehicle->permit_date = date('Y-m-d', strtotime($this->request->data('permit_date'))) ;
                        $vehicle->driver_name = $this->request->data('driver_name') ;
                        $vehicle->driver_mobile_number = $this->request->data('driver_mobile_number') ;
                        $vehicle->route_no =  $route_no  ;
                        $vehicle->school_id = $school_id;
			$vehicle->session_id = $session_id;

                        $pollution_date = date('Y-m-d', strtotime($this->request->data('pollution_date')));

                        if($saved = $vehicle_table->save($vehicle) ){

                            $vid = $saved->id;

                            if($vehicle_table->query()->update()->set(['pollution_date'=> $pollution_date ])->where([ 'id' => $vid  ])->execute())
                            {  
                                $activity = $activ_table->newEntity();
                                $activity->action =  "vehicles Created"  ;
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
                                $res = [ 'result' => 'pollution date not saved'  ];
                            }    
    
                        }
                        else{
                            $res = [ 'result' => 'driver not saved'  ];
                        }
                    /*} 
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }*/

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }

            public function edit($id)
            {   
                $vehicle_table = TableRegistry::get('vehicles');
                $route_table = TableRegistry::get('routes');

		$school_id = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_vehicle = $vehicle_table->find()->select(['id','vehicle_no','purchase_date', 'vehicle_type', 'vehicle_fitness_date', 'vehicle_insurance', 'passing_date','permit_date','pollution_date','driver_name', 'driver_mobile_number' , 'route_no'])->where([ 'md5(id)' => $id  ])->toArray() ;

                $retrieve_route = $route_table->find()->select(['id','number','route_name'])->where(['school_id' => $school_id , 'session_id'=> $session_id])->toArray() ;
                
                $this->set("vehicles_details", $retrieve_vehicle);
                $this->set("routes_details", $retrieve_route);

                $this->viewBuilder()->setLayout('user');
            } 

            public function editvhcl()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $vehicle_table = TableRegistry::get('vehicles');
                    $activ_table = TableRegistry::get('activity');
                    
	   	    $school_id = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');
		
                    /*$retrieve_vehicle = $vehicle_table->find()->select(['id'  ])->where(['driver_mobile_number' => $this->request->data('driver_mobile_number') , 'id <>' => $this->request->data('vid') , 'school_id' => $school_id , 'session_id'=> $session_id])->count() ;
                    
                    if($retrieve_vehicle == 0 )
                    {  */ 
                        $vehicle_no =  $this->request->data('vehicle_no')  ;
                        $purchase_date =  date('Y-m-d', strtotime($this->request->data('purchase_date')))  ;
                        $vehicle_type = $this->request->data('vehicle_type') ;
                        $vehicle_fitness_date = date('Y-m-d', strtotime($this->request->data('vehicle_fitness_date'))) ;
                        $vehicle_insurance = date('Y-m-d', strtotime($this->request->data('vehicle_insurance'))) ;
                        $passing_date =  date('Y-m-d', strtotime($this->request->data('passing_date')));
                        $permit_date = date('Y-m-d', strtotime($this->request->data('permit_date'))) ;
                        $pollution_date = date('Y-m-d', strtotime($this->request->data('pollution_date')));
                        $driver_name = $this->request->data('driver_name') ;
                        $driver_mobile_number = $this->request->data('driver_mobile_number') ;
                        //$route_no = implode(',', $this->request->data('route_no'));
                        $vid = $this->request->data('vid');
						
						if(!empty($this->request->data('route_no')))
						{
							$route_no = implode(',', $this->request->data('route_no'));
						}
						else
						{
							$route_no = "";
						}
                                                 
                        if($vehicle_table->query()->update()->set(['vehicle_no'=> $vehicle_no ,'purchase_date'=>$purchase_date , 'vehicle_type'=> $vehicle_type , 'vehicle_fitness_date'=> $vehicle_fitness_date , 'vehicle_insurance'=> $vehicle_insurance, 'passing_date'=> $passing_date, 'permit_date'=> $permit_date ,'pollution_date'=> $pollution_date , 'driver_name'=> $driver_name , 'driver_mobile_number'=> $driver_mobile_number, 'route_no'=> $route_no ])->where([ 'id' => $vid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Vehicle update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($vid); 
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
                    /*}
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    } */
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }

            
            public function delete()
            {
                $vid = $this->request->data('val') ;
                $vehicle_table = TableRegistry::get('vehicles');
                $activ_table = TableRegistry::get('activity');
                
                $vhclid = $vehicle_table->find()->select(['id'])->where(['id'=> $vid ])->first();
                if($vhclid)
                {   
                    $del = $vehicle_table->query()->delete()->where([ 'id' => $vid ])->execute();    
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Vehicle Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($vid)    ;
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


            public function view($id)
            {   
                $vehicle_table = TableRegistry::get('vehicles');
                $route_table = TableRegistry::get('routes');
		
	   	    $school_id = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');

                $retrieve_vehicle = $vehicle_table->find()->select(['id','vehicle_no','purchase_date', 'vehicle_type', 'vehicle_fitness_date', 'vehicle_insurance', 'passing_date','permit_date','pollution_date','driver_name', 'driver_mobile_number' , 'route_no'])->where([ 'md5(id)' => $id  ])->toArray() ;

                $retrieve_route = $route_table->find()->select(['id','number','route_name'])->where(['school_id' => $school_id , 'session_id'=> $session_id])->toArray() ;
                
                $this->set("vehicles_details", $retrieve_vehicle);
                $this->set("routes_details", $retrieve_route);

                $this->viewBuilder()->setLayout('user');
            } 





}
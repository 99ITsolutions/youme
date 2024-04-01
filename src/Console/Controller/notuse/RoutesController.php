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
class RoutesController  extends AppController
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

                $route_table = TableRegistry::get('routes');
                $compid = $this->request->session()->read('company_id');

                $retrieve_routes = $route_table->find()->select(['id' ,'number', 'route_name' ])->where(['school_id' => $compid])->toArray();

                $this->set("routes_details", $retrieve_routes);
                $this->viewBuilder()->setLayout('user');  
            }


            public function addroute(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $route_table = TableRegistry::get('routes');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');	

                    $retrieve_route = $route_table->find()->select(['id'  ])->where(['number' => $this->request->data('number'), 'school_id'=>$compid , 'session_id'=> $session_id ])->count() ;
                    
                    if($retrieve_route == 0 )
                    {

                        $routes = $route_table->newEntity();
                        $routes->route_name =  $this->request->data('name')  ;
                        $routes->number = $this->request->data('number')  ;
                        $routes->school_id =  $compid;
			$routes->session_id = $session_id;
                        
                        if($saved = $route_table->save($routes) ){
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Route Created"  ;
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
                            $res = [ 'result' => 'route not saved'  ];
                        }
                    }
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }
            

            public function update()
            {   
                if($this->request->is('post')){

                $id = $this->request->data['id'];
                
                $route_table = TableRegistry::get('routes');

                $update_route = $route_table->find()->select(['route_name' , 'id' , 'number'])->where(['id' => $id])->toArray(); 
                    
                $data = $update_route;

                return $this->json($data);

                }  
            }


            public function editroute(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $route_table = TableRegistry::get('routes');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');
                    
                    $retrieve_route = $route_table->find()->select(['id'  ])->where(['number' => $this->request->data('number'), 'id IS NOT' => $this->request->data('id'), 'school_id'=>$compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_route == 0 ){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name')  ;
                        $number =  $this->request->data('number')  ;
                        
                        if( $route_table->query()->update()->set([ 'route_name' => $name , 'number'=> $number ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Route Updated"  ;
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
                            $res = [ 'result' => 'route not updated'  ];
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
                $route_table = TableRegistry::get('routes');
                $activ_table = TableRegistry::get('activity');
                
                $routeid = $route_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($routeid)
                {                       
                    if($route_table->delete($route_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Route Deleted"  ;
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

                $route_table = TableRegistry::get('routes');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$employee_table = TableRegistry::get('employee');

                $retrieve_routes = $route_table->find()->select(['id' ,'number', 'route_name' ])->where(['school_id' => $compid , 'session_id' => $session_id ])->toArray();
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$employee_id = $this->Cookie->read('id');
            		$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
              		$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
        	}
		
                $data = "";
                $datavalue = array();
                foreach ($retrieve_routes as $value) {
                    
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editroute" data-toggle="modal"  data-target="#editroute" title="Edit"><i class="fa fa-edit"></i></button>';
		
		$delete = '<button type="button" data-url="routes/delete" data-id='.$value['id'].' data-str="Route" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$e = in_array(17, $emp_privilage) ? $edit : "";
            		$d = in_array(18, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['route_name'].'</span>
                            </td>
                            <td>
                                <span>'.$value['number'].'</span>
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

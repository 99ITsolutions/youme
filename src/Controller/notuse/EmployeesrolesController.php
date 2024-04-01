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
class EmployeesrolesController  extends AppController
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
				$school_id =$this->request->session()->read('company_id');
                $this->viewBuilder()->setLayout('user');
                
                $employeeroles_table = TableRegistry::get('employeeroles');
                //$schoolprivilage_table = TableRegistry::get('schoolprivilages');

                $retrieve_schoolroles = $employeeroles_table->find()->select(['id' , 'name' , 'added_date'  ])->where([  'school_id' => $school_id ])->toArray();


                //$retrieve_schoolTroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Teacher Management' ])->toArray() ;

                //$retrieve_schoolSroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Student Management' ])->toArray() ;

                //$retrieve_schoolFroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Fees Management' ])->toArray() ;
                

                $this->set("employeerole_details", $retrieve_schoolroles);  

                //$this->set("schoolTroles_details", $retrieve_schoolTroles);  
                //$this->set("schoolSroles_details", $retrieve_schoolSroles); 
                //$this->set("schoolFroles_details", $retrieve_schoolFroles); 

            }

            public function add(){
                $this->viewBuilder()->setLayout('user');
            }
            public function detail(){
                $this->viewBuilder()->setLayout('user');
            }

            public function addrole(){
				
                if ( $this->request->is('post') && $this->request->is('ajax') ){
					$school_id =$this->request->session()->read('company_id'); 
                    $role_table = TableRegistry::get('employeeroles');
                    $activ_table = TableRegistry::get('activity');
                    
                        $role = $role_table->newEntity();
                        $role->name =  $this->request->data('name')  ;
						$role->school_id =  $school_id  ;
                        $role->status =  1 ;
                        $role->added_date = strtotime('now');
                        if($saved = $role_table->save($role) ){
							
							$role_table->query()->update()->set(['school_id' => $school_id  ])->where([ 'id' => $saved->id  ])->execute();
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Employees Role Created"  ;
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
                            $res = [ 'result' => 'Employee role not saved'  ];
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
                
                $role_table = TableRegistry::get('employeeroles');

                $update_roles = $role_table->find()->select(['name' , 'id' ])->where(['id' => $id])->toArray(); 
                    
                //$prv = explode(",",$update_roles[0]['privilage']);    

                $data = ['name' => $update_roles[0]['name'] , 'id'=>$update_roles[0]['id']];
                
                return $this->json($data);

                }  
            }

            public function view()
            {   
                if($this->request->is('post')){

                $id = $this->request->data['id'];

                $role_table = TableRegistry::get('schoolroles');
                $privilage_table = TableRegistry::get('schoolprivilages');

                $update_roles = $role_table->find()->select([ 'schoolroles.name', 'prname' => 'GROUP_CONCAT(privilages.name)']) ->join(['privilages' => 
                        [
                        'table' => 'schoolprivilages',
                        'type' => 'LEFT',
                        'conditions' => 'FIND_IN_SET(privilages.id,schoolroles.privilage)'
                    ]
                ])->where(['schoolroles.id' => $id])->toArray(); 
 
                $data =  explode(',',$update_roles[0]['prname']);
                   
                return $this->json($data);

                }  
            }


            public function editrole(){
                if ($this->request->is('ajax') && $this->request->is('post')){
					$school_id =$this->request->session()->read('company_id');
                    $role_table = TableRegistry::get('employeeroles');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_roles = $role_table->find()->select(['id'  ])->where(['name' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'status' => '1' ])->count() ;
                    
                    if($retrieve_roles == 0 ){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name')  ;
                        //$privilage = implode(',' , $this->request->data('privilage'));
                        $now = strtotime('now');
                        
                        if( $role_table->query()->update()->set([ 'name' => $name, 'school_id' => $school_id  ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Employee Roles Updated"  ;
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
                            $res = [ 'result' => 'Employee role not updated'  ];
                        }
                    } 
                    else
                    {
                        $res = [ 'result' => 'name'  ];
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
                $role_table = TableRegistry::get('employeeroles');
                $activ_table = TableRegistry::get('activity');
                
                $roleid = $role_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($roleid)
                {   $del = $role_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                    if($del)
                    //if($role_table->delete($role_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "School Role Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = $rid    ;
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
            
    }

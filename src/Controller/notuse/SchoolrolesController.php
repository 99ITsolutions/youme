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
class SchoolrolesController  extends AppController
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
                
                $schoolroles_table = TableRegistry::get('schoolroles');
                $schoolprivilage_table = TableRegistry::get('schoolprivilages');

                $retrieve_schoolroles = $schoolroles_table->find()->select(['id' , 'name' , 'added_date' ,'privilage' ])->toArray();


                $retrieve_schoolTroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Employee Management' ])->toArray() ;

                $retrieve_schoolSroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Student Management' ])->toArray() ;

                $retrieve_schoolFroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Fees Management' ])->toArray() ;
                
                $retrieve_schoolCroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Class Management' ])->toArray() ; 

                $retrieve_schoolRTroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Route Management' ])->toArray() ;

                $retrieve_schoolVroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Vehicle Management' ])->toArray() ; 

                $retrieve_schoolRLroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Role Management' ])->toArray() ; 

                $retrieve_schoolBroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Balance Management' ])->toArray() ; 

                $retrieve_schoolEHroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Expenses Management' ])->toArray() ;

                $retrieve_schoolTsroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Transfer Management' ])->toArray() ;

                $retrieve_schoolDscroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Discount Management' ])->toArray() ;

                $retrieve_schoolHDroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Holiday Management' ])->toArray() ;

                $retrieve_schoolICroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Income Management' ])->toArray() ;

                $retrieve_schoolSTProles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Stopage Management' ])->toArray() ; 

                $retrieve_schoolDPTroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Department Management' ])->toArray() ;

		$retrieve_schoolENQroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Enquiry Management' ])->toArray() ;              
		
		$retrieve_schoolSRMroles = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category' => 'Session Records Management' ])->toArray() ;


                $this->set("schoolrole_details", $retrieve_schoolroles);  

                $this->set("schoolTroles_details", $retrieve_schoolTroles);  
                $this->set("schoolSroles_details", $retrieve_schoolSroles); 
                $this->set("schoolFroles_details", $retrieve_schoolFroles);
                $this->set("schoolCroles_details", $retrieve_schoolCroles);
                $this->set("schoolRTroles_details", $retrieve_schoolRTroles);
                $this->set("schoolVroles_details", $retrieve_schoolVroles);
                $this->set("schoolRLroles_details", $retrieve_schoolRLroles);
                $this->set("schoolBroles_details", $retrieve_schoolBroles);
                $this->set("schoolEHroles_details", $retrieve_schoolEHroles); 
                $this->set("schoolTsroles_details", $retrieve_schoolTsroles);
                $this->set("schoolDscroles_details", $retrieve_schoolDscroles);
                $this->set("schoolHDroles_details", $retrieve_schoolHDroles);
                $this->set("schoolICroles_details", $retrieve_schoolICroles);
                $this->set("schoolSTProles_details", $retrieve_schoolSTProles);
                $this->set("schoolDPTroles_details", $retrieve_schoolDPTroles);
		$this->set("schoolENQroles_details", $retrieve_schoolENQroles);
		$this->set("schoolSRMroles_details", $retrieve_schoolSRMroles);

            }


            public function addrole(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $role_table = TableRegistry::get('schoolroles');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_roles = $role_table->find()->select(['id'  ])->where(['name' => $this->request->data('name'), 'status' => '1' ])->count() ;
                    
                    if($retrieve_roles == 0 ){

		      if(!empty($this->request->data('privilage'))){

                        $role = $role_table->newEntity();
                        $role->name =  $this->request->data('name')  ;
                        $role->privilage = implode(',' , $this->request->data('privilage'))  ;
                        $role->status =  1 ;
                        $role->deleted =  0 ;
                        $role->added_date = strtotime('now');
                        if($saved = $role_table->save($role) ){
                            $activity = $activ_table->newEntity();
                            $activity->action =  "School Role Created"  ;
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
                            $res = [ 'result' => 'role not saved'  ];
                        }
		      }    
                      else
                      {
                         $res = [ 'result' => 'empty'  ];
                      }	

                    }    
                    else{
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
                
                $role_table = TableRegistry::get('schoolroles');

                $update_roles = $role_table->find()->select(['name' , 'id' , 'privilage'])->where(['id' => $id])->toArray(); 
                    
                $prv = explode(",",$update_roles[0]['privilage']);    

                $data = ['name' => $update_roles[0]['name'] , 'id'=>$update_roles[0]['id'] , 'privilage'=> $prv ];
                
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

                    $role_table = TableRegistry::get('schoolroles');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_roles = $role_table->find()->select(['id'  ])->where(['name' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'status' => '1' ])->count() ;
                    
                    if($retrieve_roles == 0 ){
		
		      if(!empty($this->request->data('privilage'))){

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name')  ;
                        $privilage = implode(',' , $this->request->data('privilage'));
                        $now = strtotime('now');
                        
                        if( $role_table->query()->update()->set([ 'name' => $name , 'privilage'=> $privilage ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "School Roles Updated"  ;
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
                            $res = [ 'result' => 'role not updated'  ];
                        }
                      }    
                      else
                      {
                         $res = [ 'result' => 'empty'  ];
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
                $role_table = TableRegistry::get('schoolroles');
                $activ_table = TableRegistry::get('activity');
                
                $roleid = $role_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($roleid)
                {   
                    if($role_table->delete($role_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "School Role Deleted"  ;
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

                $usersid = $this->request->session()->read('users_id');    
                $users_table = TableRegistry::get('users');
                $schoolroles_table = TableRegistry::get('schoolroles');
                
                $retrieve_schoolroles = $schoolroles_table->find()->select(['id' , 'name' , 'added_date' ,'privilage' ])->toArray();

                $retrieve_users = $users_table->find()->select(['privilages' ])->where(['id' => $usersid ])->toArray() ; 
                
                $user_privilage = explode(',',$retrieve_users[0]['privilages']) ;
                
                $data  = "" ;
                $datavalue = array();
                foreach ($retrieve_schoolroles as $value) {
                    
                    $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editschoolroles" data-toggle="modal"  data-target="#editrole" title="Edit"><i class="fa fa-edit"></i></button>';
                    $delete = '<button type="button" data-url="schoolroles/delete" data-id='. $value['id'].' data-str="Role" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

                $e = in_array(28, $user_privilage) ? $edit : "";
                $d = in_array(29, $user_privilage) ? $delete : "";
                
                $data .=  '<tr>
                            <td class="width45">
                            <label class="fancy-checkbox">
                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$value['name'].'</span>
                            </td>
                            <td>
                                <a data-toggle="modal" style="color:black;" class="viewschoolroles" data-id='.$value['id'].' href="#viewrole">'.count(explode(',',$value['privilage'])).' Privilages</a>
                            </td>
                            <td>
                                <span>'.date('d-m-Y',$value['added_date']).'</span>
                            </td>

                            <td>'.$e.$d.'</td>
                            
                        </tr>';

                    
                }
                
                $datavalue['html']= $data;
                return $this->json($datavalue);

                }
            }


            
    }

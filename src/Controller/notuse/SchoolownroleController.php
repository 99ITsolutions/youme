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
class SchoolownroleController  extends AppController
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
                
                $school_table = TableRegistry::get('company');
                //$roles_table = TableRegistry::get('schoolownrole');
                $schoolprivilage_table = TableRegistry::get('schoolprivilages');
                $compid = $this->request->session()->read('company_id');

                //$retrieve_roles = $roles_table->find()->select(['id' , 'name'  ,'privilage', 'added_date' ])->where([ 'status' => 1 , 'school_id'=> $compid  ])->toArray();

                $retrieve_prv = $school_table->find()->select(['prv_cat' ])->where([ 'id'=> $compid  ])->toArray();

                $category = explode(",",$retrieve_prv[0]['prv_cat']);

                //$retrieve_tchrprv = $schoolprivilage_table->find()->select(['id' , 'name'  ])->where([  'category IN' => $category ])->toArray() ;

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

                //$this->set("role_details", $retrieve_roles);
                //$this->set("retrieve_tchrprv", $retrieve_tchrprv);
                $this->set("category",$category);
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

            public function addsrole(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $role_table = TableRegistry::get('schoolownrole');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');
	
                    $retrieve_roles = $role_table->find()->select(['id'  ])->where(['name' => $this->request->data('name')  , 'status' => '1' , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;
                    
                    if($retrieve_roles == 0 ){

		      if(!empty($this->request->data('privilage'))){

                        $role = $role_table->newEntity();
                        $role->name =  $this->request->data('name')  ;
                        $role->privilage = implode(',' , $this->request->data('privilage'))  ;
                        $role->status =  1 ;
			$role->session_id = $session_id;
                        $role->school_id =  $compid ;
                        $role->added_date = strtotime('now');
                        if($saved = $role_table->save($role) ){
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Role Created"  ;
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
                
                $role_table = TableRegistry::get('schoolownrole');

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

                $role_table = TableRegistry::get('schoolownrole');
                $privilage_table = TableRegistry::get('schoolprivilages');

                $update_roles = $role_table->find()->select([ 'schoolownrole.name', 'prname' => 'GROUP_CONCAT(schoolprivilages.name)'])->join(['schoolprivilages' => 
                        [
                        'table' => 'schoolprivilages',
                        'type' => 'LEFT',
                        'conditions' => 'FIND_IN_SET(schoolprivilages.id,schoolownrole.privilage)'
                    ]
                ])->where(['schoolownrole.id' => $id])->toArray(); 
 
                $data =  explode(',',$update_roles[0]['prname']);
                
                return $this->json($data);

                }  
            }


            public function editsrole(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $role_table = TableRegistry::get('schoolownrole');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
		    $session_id = $this->Cookie->read('sessionid');	
                    
                    $retrieve_roles = $role_table->find()->select(['id'  ])->where(['name' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'status' => '1' , 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_roles == 0 ){
		
		       if(!empty($this->request->data('privilage'))){
			

                        $id = $this->request->data('id');
                        $name =  $this->request->data('name')  ;
                        $privilage = implode(',' , $this->request->data('privilage'));
                        $now = strtotime('now');
                        
                        if( $role_table->query()->update()->set([ 'name' => $name , 'privilage'=> $privilage ])->where([ 'id' => $id  ])->execute())
                        {
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Roles Updated"  ;
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
                $role_table = TableRegistry::get('schoolownrole');
                $activ_table = TableRegistry::get('activity');
                
                $roleid = $role_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                
                if($roleid)
                {                       
                    if($role_table->delete($role_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Role Deleted"  ;
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

            public function getdata(){

                if ($this->request->is('ajax') && $this->request->is('post')){

                $role_table = TableRegistry::get('schoolownrole');
		$employee_table = TableRegistry::get('employee');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $role_details = $role_table->find()->select(['id', 'name' , 'privilage','added_date'])->where([ 'school_id'=>$compid , 'session_id' => $session_id ])->toArray(); 
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
		  $employee_id = $this->Cookie->read('id');	
		  $retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
		  $emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
		}

                $data  = "" ;
                $datavalue = array();
                foreach ($role_details as $value) {
	
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editroles" data-toggle="modal"  data-target="#editrole" title="Edit"><i class="fa fa-edit"></i></button>';
                            
		$delete = '<button type="button" data-url="schoolownrole/delete" data-id='.$value['id'].' data-str="Role" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
		
                if($this->Cookie->read('logtype') == md5('Employee'))
		{
        		$e = in_array(24, $emp_privilage) ? $edit : "";
        		$d = in_array(25, $emp_privilage) ? $delete : "";
		}
		else{
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
                                <span class="mb-0 font-weight-bold">'.$value['name'].'</span>
                            </td>
                            <td>
                                <a data-toggle="modal" style="color:black;" class="viewsprv" data-id='.$value['id'].' href="#viewsprv">'.count(explode(',',$value['privilage'])).' Privilages</a>
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

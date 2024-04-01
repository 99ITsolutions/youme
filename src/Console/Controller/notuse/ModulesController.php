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
class ModulesController  extends AppController
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
                $modules_table = TableRegistry::get('modules');
                $privilage_table = TableRegistry::get('schoolprivilages');

                $retrieve_modules = $modules_table->find()->select(['id' , 'name' , 'added_date' ,'scl_prv_cat' ])->where([ 'status' => '1'  ])->toArray();

                $retrieve_schoolprv = $privilage_table->find()->distinct('category')->select(['id' , 'category'])->toArray();    

                $this->set("modules_details", $retrieve_modules); 
                $this->set("schoolprv_details", $retrieve_schoolprv);
                
            }

            public function addmodule(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $modules_table = TableRegistry::get('modules');
                    $activ_table = TableRegistry::get('activity');
                    

                        $retrieve_modules = $modules_table->find()->select(['id' ])->where(['name' => $this->request->data('name') ,'status' => '1' ])->count() ;
                        
                        if($retrieve_modules == 0 )
                        {                    
                            $scl_prv_cat = implode(',' , $this->request->data('category'))  ;

                            $modules = $modules_table->newEntity();
                            $modules->name =  $this->request->data('name');
                            $modules->added_date = strtotime('now');
                            $modules->status = 1;
                            if($saved = $modules_table->save($modules) )
                            {   
                                $mid = $saved->id;
                                if( $modules_table->query()->update()->set([ 'scl_prv_cat' => $scl_prv_cat ])->where([ 'id' => $mid  ])->execute())
                                {    
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Module Created"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                    $activity->value = md5($saved->id)  ;
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
                                else{
                                $res = [ 'result' => 'privilage not saved'  ];
                            }
        
                            }
                            else{
                                $res = [ 'result' => 'module not saved'  ];
                            }
                        }
                        else{
                            $res = [ 'result' => 'exist' ];
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
                
                $modules_table = TableRegistry::get('modules');
                $schoolprivilages_table = TableRegistry::get('schoolprivilages');

                $update_modules = $modules_table->find()->select(['name' , 'id' , 'scl_prv_cat'])->where(['id' => $id])->toArray(); 
                    
                $prv = explode(",",$update_modules[0]['scl_prv_cat']);    

                $data = ['name' => $update_modules[0]['name'] , 'id'=>$update_modules[0]['id'] , 'category'=> $prv ];

                return $this->json($data);

                }  
            }

            public function view()
            {   
                if($this->request->is('post')){

                $id = $this->request->data['id'];

                $module_table = TableRegistry::get('modules');
                $sclprv_table = TableRegistry::get('schoolprivilages');

                $update_module = $module_table->find()->select(['scl_prv_cat'])->where(['id ' => $id])->toArray(); 

                $data = explode(',',$update_module[0]['scl_prv_cat']);
                
                return $this->json($data);

                }  
            }


            public function editmodule(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $modules_table = TableRegistry::get('modules');
					$company_tbl = TableRegistry::get('company');
                    $schoolprivilages_table = TableRegistry::get('schoolprivilages');
                    $activ_table = TableRegistry::get('activity');
                    
                        $retrieve_modules = $modules_table->find()->select(['id'  ])->where(['name' => $this->request->data('name'), 'id IS NOT' => $this->request->data('id')  , 'status' => '1' ])->count() ;
                        
                        if($retrieve_modules == 0 )
                        {
                            $id = $this->request->data('id');
                            $name =  $this->request->data('name')  ;
                            $scl_prv_cat = implode(',' , $this->request->data('category'));
                            
                            if( $modules_table->query()->update()->set([ 'name' => $name , 'scl_prv_cat'=> $scl_prv_cat ])->where([ 'id' => $id  ])->execute())
                            {
								$update_compny = $company_tbl->query()->update()->set([ 'prv_cat'=> $scl_prv_cat ])->where([ 'module' => $id  ])->execute();
								
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Module Updated"  ;
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
                                $res = [ 'result' => 'Module not updated'  ];
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
                $mid = $this->request->data('val') ;
                $module_table = TableRegistry::get('modules');
                $activ_table = TableRegistry::get('activity');
                
                $moduleid = $module_table->find()->select(['id'])->where(['id'=> $mid ])->first();
                
                if($moduleid)
                {                       
                    $del = $module_table->query()->delete()->where([ 'id' => $mid ])->execute(); 
                    if($del)
					{
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Module Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($mid)    ;
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
                $modules_table = TableRegistry::get('modules');

                $retrieve_modules = $modules_table->find()->select(['id' , 'name' , 'added_date' ,'scl_prv_cat' ])->where([ 'status' => '1'  ])->toArray();

                $retrieve_users = $users_table->find()->select(['privilages' ])->where(['id' => $usersid ])->toArray() ; 
                
                $user_privilage = explode(',',$retrieve_users[0]['privilages']) ;
                
                $data = "";
                $datavalue = array();
                foreach ($retrieve_modules as $value) {
                
                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editmodule" data-toggle="modal"  data-target="#editmodule" title="Edit"><i class="fa fa-edit"></i></button>';
                
                $delete = '<button type="button" data-url="modules/delete" data-id='. $value['id'].' data-str="Module" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

                $e = in_array(25, $user_privilage) ? $edit : "";
                $d = in_array(26, $user_privilage) ? $delete : "";

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
                                <a data-toggle="modal" style="color:black;" class="viewsclprv" data-id='.$value['id'].' href="#viewsclprv">'.count(explode(',',$value['scl_prv_cat'])).' Sub-Module</a>
                            </td>
                            <td>
                                <span>'.date('d-m-Y',$value['added_date']).'</span>
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

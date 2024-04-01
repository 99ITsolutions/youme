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
class SettingController  extends AppController
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

                $setting_table = TableRegistry::get('stdnt_h_setting');
                $compid = $this->request->session()->read('company_id');
		
		$session_id = $this->Cookie->read('sessionid') ;

                $retrieve_setting = $setting_table->find()->select(['id' , 'col_type' , 'added_date' ])->where(['session_id'=> $session_id , 'school_id'=> $compid  ])->toArray();

                $this->set("setting_details", $retrieve_setting);
                $this->viewBuilder()->setLayout('user');

            }

            public function addstdntset(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $compid = $this->request->session()->read('company_id');  
                    $setting_table = TableRegistry::get('stdnt_h_setting');
                    $activ_table = TableRegistry::get('activity');
                    
		    $session_id = $this->Cookie->read('sessionid') ;
	
                    $retrieve_setting = $setting_table->find()->select(['id' ])->where(['school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_setting == 0 )
                    {   

                        $setting = $setting_table->newEntity();

                        $setting->col_type = implode(',', $this->request->data('col_type'));
                        $setting->school_id = $compid ;
			$setting->session_id = $session_id;
                        $setting->added_date = strtotime('now');
            
                        if($saved = $setting_table->save($setting) )
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Setting Created"  ;
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
                                $res = [ 'result' => 'Setting not saved'  ];
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
                
                $setting_table = TableRegistry::get('stdnt_h_setting');

                $update_setting = $setting_table->find()->select(['col_type'])->where(['id' => $id])->toArray(); 
                    
                $col_type = explode(",",$update_setting[0]['col_type']);    

                $data = ['column' => $col_type ];

                return $this->json($data);

                }  
            }

            public function editstdntset()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $setting_table = TableRegistry::get('stdnt_h_setting');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');  

		    $session_id = $this->Cookie->read('sessionid') ;

                    /*$retrieve_setting = $setting_table->find()->select(['id' ])->where(['id <>' => $this->request->data('id') , 'school_id'=> $compid ])->count() ;
                    
                    if($retrieve_setting == 0 )
                    {*/ 
                        $setid = $this->request->data('id');
                        $col_type = implode(',', $this->request->data('col_type'));
                       
                        if( $setting_table->query()->update()->set([ 'col_type' => $col_type ])->where([ 'id' => $setid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Setting update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($setid); 
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
                    }*/
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }

            public function delete()
            {
                $sid = (int) $this->request->data('val') ; 
                $setting_table = TableRegistry::get('stdnt_h_setting');
                $activ_table = TableRegistry::get('activity');
                
                $settingid = $setting_table->find()->select(['id'])->where(['id'=> $sid ])->first();
                
                if($settingid)
                {   
                    $del = $setting_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                    if($del)
                    { 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Setting Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($sid)    ;
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

                $setting_table = TableRegistry::get('stdnt_h_setting');
                $compid = $this->request->session()->read('company_id');
		$employee_table = TableRegistry::get('employee');
		$session_id = $this->Cookie->read('sessionid') ;

                $retrieve_setting = $setting_table->find()->select(['id' , 'col_type' , 'added_date' ])->where(['session_id' => $session_id , 'school_id'=> $compid  ])->toArray();
                
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            	    $employee_id = $this->Cookie->read('id');
            	    $retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid  ])->toArray() ; 
                
        	    $emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
	        }

                $data = "";
                $datavalue = array();
                foreach ($retrieve_setting as $value) {
                  
		$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editstdntset" data-toggle="modal"  data-target="#editstdntset" title="Edit"><i class="fa fa-edit"></i></button>';

		$delete = '<button type="button" data-url="setting/delete" data-id='.$value['id'].' data-str="Setting" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
 
		if($this->Cookie->read('logtype') == md5('Employee'))
        	{
            		$e = in_array(72, $emp_privilage) ? $edit : "";
            		$d = in_array(73, $emp_privilage) ? $delete : "";
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
                                <span class="mb-0 font-weight-bold">'.$value['col_type'].'</span>
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
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
class ApprovesubjectsController  extends AppController
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
                $subjects_table = TableRegistry::get('subjects');
                $compid = $this->request->session()->read('company_id');
                $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'status' => 0])->toArray() ;
                $this->set("subject_details", $retrieve_subjects); 
                $this->viewBuilder()->setLayout('user');
            }
            public function editsub()
            {  
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $subjects_table = TableRegistry::get('subjects');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $retrieve_sub = $subjects_table->find()->select(['id' ])->where(['subject_name' => $this->request->data('esubject_name') , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $compid  ])->count() ;

                    if($retrieve_sub == 0 )
                    {   
                        $sid = $this->request->data('eid');
                        $sub_name = $this->request->data('esubject_name');
						$status = 0; 
                        if( $subjects_table->query()->update()->set(['subject_name' => $sub_name , 'status' => $status ])->where([ 'id' => $sid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Subject update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($sid); 
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
                    } 
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    }           
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }

        public function delete()
        {
            $sid = $this->request->data('val') ;
            $subjects_table = TableRegistry::get('subjects');
            $activ_table = TableRegistry::get('activity');
            
            $subid = $subjects_table->find()->select(['id'])->where(['id'=> $sid ])->first();
            if($subid)
            {   
                
                if($subjects_table->delete($subjects_table->get($sid)))
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Subject Deleted"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = $sid    ;
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
		
	    public function update()
        {   
            if($this->request->is('post'))
            {
                $id = $this->request->data['id'];
                $subjects_table = TableRegistry::get('subjects');
                $update_sub = $subjects_table->find()->select(['subject_name' , 'id' , 'status'])->where(['id' => $id])->toArray(); 
                return $this->json($update_sub);
            }  
        }
        
        
        public function status()
        {   

            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $subjects_table = TableRegistry::get('subjects');
            $activ_table = TableRegistry::get('activity');

            $sid = $subjects_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $subjects_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Subject status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($uid)    ;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }

            
               
	   public function getdata(){
			if ($this->request->is('ajax') && $this->request->is('post'))
			{		
				$subjects_table = TableRegistry::get('subjects');
                $compid = $this->request->session()->read('company_id');
				//$employee_table = TableRegistry::get('employee');
		
                $retrieve_subjects = $subjects_table->find()->select(['id' , 'subject_name' , 'status'  ])->where([ 'school_id' => $compid])->order(['id' => 'DESC'])->toArray() ;
                
				/*if($this->Cookie->read('logtype') == md5('Employee'))
				{
					$employee_id = $this->Cookie->read('id');
					$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid ])->toArray() ; 
                
					$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
				}*/

                $data = "";
                $datavalue = array();
                foreach ($retrieve_subjects as $value) 
				{
					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
					$delete = '<button type="button" data-url="subjects/delete" data-id='.$value['id'].' data-str="Subject" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
		
					/*if($this->Cookie->read('logtype') == md5('Employee'))
					{
						$e = in_array(14, $emp_privilage) ? $edit : "";
						$d = in_array(15, $emp_privilage) ? $delete : "";
					}
					else
					{
						$e = $edit;
						$d = $delete;
					}*/
					$e = $edit;
					$d = $delete;
					
					if(!empty($this->Cookie->read('sid')))
                    {
                        if( $value['status'] == 0)
                        {
                            $sts = '<a href="javascript:void()" data-url="subjects/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                        }
                        else 
                        { 
                            $sts = '<a href="javascript:void()" data-url="subjects/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                        }
                    }
                    else
                    {
                        if( $value['status'] == 0)
                        {
                            $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                        
                        }
                        else 
                        { 
                            $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                        }
                    }
                    
					$data .=    '<tr>
                                    <td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['subject_name'].'</span>
                                    </td>
                                    <td>'.$sts.'</td>
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

  




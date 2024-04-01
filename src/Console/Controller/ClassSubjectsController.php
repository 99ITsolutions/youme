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
class ClassSubjectsController  extends AppController
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
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                $subjects_table = TableRegistry::get('subjects');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid)) 
                {
                    $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                    
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'c_name' ])->where(['school_id' => $compid])->group(['c_name'])->toArray() ;
                    
                    $this->set("grade_details", $retrieve_classgrade); 
                    $this->set("subject_details", $retrieve_subjects); 
                    $this->set("class_details", $retrieve_class); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }

            public function addclssub()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $classsub_table = TableRegistry::get('class_subjects');
                    $activ_table = TableRegistry::get('activity');
		            $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $compid = $this->request->session()->read('company_id');
                    if(!empty($compid)) {
                        $retrieve_classsub = $classsub_table->find()->select(['id'])->where(['class_id IN' => $this->request->data['class'], 'school_id' => $compid  ])->count() ;
    
                        if($retrieve_classsub == 0 )
                        {
                            $classids = $this->request->data['class'];
                            foreach($classids as $cids)
                            {
                                $classsub = $classsub_table->newEntity();
                                $classsub->class_id = $cids;
                                $classsub->subject_id = implode(",", $this->request->data('subjects'));
        						$classsub->status = 0;
                                $classsub->school_id = $compid;
                                $classsub->created_date = time();
                                                  
                                if($saved = $classsub_table->save($classsub) )
                                {   
                                    $clsid = $saved->id;
        
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Class Subjects Added"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                    $activity->origin = $this->Cookie->read('id');
                                    $activity->value = md5($clsid); 
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
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }

            public function editclssub()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                { //print_r($this->request->data('eclass')); die;
                    $classsub_table = TableRegistry::get('class_subjects');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    //$session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($compid)) 
                    {
                        $clasids = $this->request->data['eclass'];
                        $retrieve_classsub = 0;
                        foreach($clasids as $clid)
                        {
                          //echo $clid;
                            /*$retrieve_classsub = $classsub_table->find()->select(['id'])->where(['class_id' => $clid , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $compid  ])->count() ;
                              //echo $retrieve_classsub; 
                            if($retrieve_classsub == 0 )
                            {   */
                                $scid = $this->request->data('eid');
                                $class = $clid; 
                                $subjects = implode(",", $this->request->data('esubjects'));
        						$status = 0;
                                if( $classsub_table->query()->update()->set([ 'subject_id' => $subjects  ])->where(['class_id' => $class ,  'school_id' => $compid ])->execute())
                                {   
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Class Subjects update"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                    $activity->origin = $this->Cookie->read('id');
                                    $activity->value = md5($scid); 
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
                    }
                    else
                    {
                        return $this->redirect('/login/') ;    
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
                $cid = $this->request->data('val') ;
                $classsub_table = TableRegistry::get('class_subjects');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $clid = $classsub_table->find()->select(['id'])->where(['id'=> $cid ])->first();
                if($clid)
                {   
                    
                    if($classsub_table->delete($classsub_table->get($cid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Class SUbjects Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = $cid    ;
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
                $classsub_table = TableRegistry::get('class_subjects');
                $update_classsub = $classsub_table->find()->where(['id' => $id])->first(); 
                $data = [];
                $class_table = TableRegistry::get('class');
                $get_class = $class_table->find()->where(['id' => $update_classsub['class_id']])->first(); 
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $get_classids = $class_table->find()->where(['school_id' => $compid, 'school_sections' => $get_class['school_sections'], 'c_name' => $get_class['c_name'] ])->toArray(); 
                
                $classids = [];
                foreach($get_classids as $cids)
                {
                    $classids[] = $cids['id'];
                }
                $clsids = implode(",", $classids);
                $data['class_id'] = $clsids;
                $data['subject_id'] = $update_classsub['subject_id'];
                $data['status'] = $update_classsub['status'];
                return $this->json($data);
            }  
        }
            
        public function status()
        {   
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $class_subjects_table = TableRegistry::get('class_subjects');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $class_subjects_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $class_subjects_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Class Subject status changed"  ;
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
               
	    public function getdata()
	    {
			if ($this->request->is('ajax') && $this->request->is('post'))
			{		
				$classsub_table = TableRegistry::get('class_subjects');
                $compid = $this->request->session()->read('company_id');
                $subjects_table = TableRegistry::get('subjects');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
				//$session_id = $this->Cookie->read('sessionid');
		        if(!empty($compid)) {
                    $retrieve_classsub = $classsub_table->find()->select(['class_subjects.id' ,'class.c_name','class.c_section', 'class.school_sections' ,'class_subjects.subject_id', 'class_subjects.status' ])->join([
                        'class' => 
    						[
    							'table' => 'class',
    							'type' => 'LEFT',
    							'conditions' => 'class.id = class_subjects.class_id'
    						]
    					])->where([ 'class_subjects.school_id' => $compid])->order(['class_subjects.id' => 'ASC'])->toArray() ;
    					
    				foreach($retrieve_classsub as $key =>$subcoll)
            		{
            			$subid = explode(",",$subcoll['subject_id']);
            			$i = 0;
            			$subjectsname = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $sid])->toArray() ;	
            				foreach($retrieve_subjects as $rstd)
            				{
            					$subjectsname[$i] = $rstd['subject_name'];				
            				}
            				$i++;
            				$snames = implode(",", $subjectsname);
            				
            			}
            			 $subcoll->subject_name = $snames;
            			
            			
            		}
                    /*if($this->Cookie->read('logtype') == md5('Employee'))
    				{
    					$employee_id = $this->Cookie->read('id');
    					$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid ])->toArray() ; 
                    
    					$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
    				}*/
    
                    $data = "";
                    $datavalue = array();
                    
                    foreach ($retrieve_classsub as $value) 
    				{
    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubjectclass" data-toggle="modal"  data-target="#editsubjectclass" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classSubjects/delete" data-id='.$value['id'].' data-str="Class Subjects" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    				    if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(8, $scl_privilage) ? $edit : "";
    						$d = in_array(9, $scl_privilage) ? $delete : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    					}
    					
    				
    					
    					/*if(!empty($this->Cookie->read('sid')))
                        {
                            if( $value['status'] == 0)
                            {
                                $sts = '<a href="javascript:void()" data-url="ClassSubjects/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Class Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                            }
                            else 
                            { 
                                $sts = '<a href="javascript:void()" data-url="ClassSubjects/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Class Subject Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                            }
                        }
                        else
                        {*/
                            if( $value['status'] == 0)
                            {
                                $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                            
                            }
                            else 
                            { 
                                $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                            }
                        //}
                        
                        $privi = explode(",", $value['subject_name']);
                        if(!empty($privi[1])) 
                        { 
                            $priname = $privi[0].",".$privi[1];
                        }
                        else
                        {
                            $priname = $privi[0];
                        }
                        
                        $classname = $value['class']['c_name']. '-'. $value['class']['c_section'].' ('. $value['class']['school_sections'].')';
    					$data .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['class']['c_name']. "-". $value['class']['c_section'].' ('. $value['class']['school_sections'].')</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="subjectsdetl" data-id="'.$value['subject_name'].'" data-cls="'.$classname.'" ><span>'. $priname .'</span> <span>...</span></a>
                                </td>
                                <td> '.$sts.' </td>
                                <td>
    							'.$e.$d.'
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
            
            public function getsclsctns()
            {
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                $c_name = $this->request->data('cname');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
                if(!empty($compid)) 
                {
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'school_sections' ])->where(['school_id' => $compid, 'c_name' => $c_name])->group(['school_sections'])->toArray() ;
                    
                    $data = "";
                    $data .= '<option value="">Choose Sections</option>';
                    foreach($retrieve_classgrade as $cls)
                    {
                        $data .= '<option value="'.$cls['school_sections'].'">'.$cls['school_sections'].'</option>';
                        $classids[] = $cls['id'];
                    }
            
    			    $classsub_table = TableRegistry::get('class_subjects');
                    $subjects_table = TableRegistry::get('subjects');
    		
                    $retrieve_classsub = $classsub_table->find()->select(['class_subjects.id' ,'class.c_name','class.c_section', 'class.school_sections' ,'class_subjects.subject_id', 'class_subjects.status' ])->join([
                        'class' => 
    						[
    							'table' => 'class',
    							'type' => 'LEFT',
    							'conditions' => 'class.id = class_subjects.class_id'
    						]
    					])->where([ 'class_subjects.school_id' => $compid, 'class_subjects.class_id IN' => $classids])->order(['class_subjects.id' => 'ASC'])->toArray() ;
    					
    				foreach($retrieve_classsub as $key =>$subcoll)
            		{
            			$subid = explode(",",$subcoll['subject_id']);
            			$i = 0;
            			$subjectsname = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $sid])->toArray() ;	
            				foreach($retrieve_subjects as $rstd)
            				{
            					$subjectsname[$i] = $rstd['subject_name'];				
            				}
            				$i++;
            				$snames = implode(",", $subjectsname);
            				
            			}
            			 $subcoll->subject_name = $snames;
            		}
               

                    $tabledata = "";
                    $datavalue = array();
                    
                    foreach ($retrieve_classsub as $value) 
    				{
    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubjectclass" data-toggle="modal"  data-target="#editsubjectclass" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classSubjects/delete" data-id='.$value['id'].' data-str="Class Subjects" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(8, $scl_privilage) ? $edit : "";
    						$d = in_array(9, $scl_privilage) ? $delete : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    					}
    				
                        if( $value['status'] == 0)
                        {
                            $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                        }
                        else 
                        { 
                            $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                        }
                       
                        $privi = explode(",", $value['subject_name']);
                        if(!empty($privi[1])) 
                        { 
                            $priname = $privi[0].",".$privi[1];
                        }
                        else
                        {
                            $priname = $privi[0];
                        }
                        $classname = $value['class']['c_name']. '-'. $value['class']['c_section'].' ('. $value['class']['school_sections'].')';
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['class']['c_name']. "-". $value['class']['c_section'].' ('. $value['class']['school_sections'].')</span>
                                </td>
                                 <td>
                                    <a href="javascript:void(0)" class="subjectsdetl" data-id="'.$value['subject_name'].'" data-cls="'.$classname.'" ><span>'. $priname .'</span> <span>...</span></a>
                                </td>
                                <td> '.$sts.' </td>
                                <td>
    							'.$e.$d.'
                                </td>
                            </tr>';
                        
                    }
                
                    $datavalue['html'] = $data;
                    $datavalue['tabledata'] = $tabledata; 
    
                    return $this->json($datavalue);
                }
                else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            
            public function getclssctns()
            {
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                $c_name = $this->request->data('cname');
                $sclsectn = $this->request->data('sclsectn');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
                
                if(!empty($compid)) {
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'c_section' ])->where(['school_id' => $compid, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;
                    
                    $data = "";
                    $data .= '<option value="">Choose Classes</option>';
                    foreach($retrieve_classgrade as $cls)
                    {
                        $data .= '<option value="'.$cls['c_section'].'">'.$cls['c_section'].'</option>';
                        $classids[] = $cls['id'];
                    }
            
    			    $classsub_table = TableRegistry::get('class_subjects');
                    $subjects_table = TableRegistry::get('subjects');
    		
                    $retrieve_classsub = $classsub_table->find()->select(['class_subjects.id' ,'class.c_name','class.c_section', 'class.school_sections' ,'class_subjects.subject_id', 'class_subjects.status' ])->join([
                        'class' => 
    						[
    							'table' => 'class',
    							'type' => 'LEFT',
    							'conditions' => 'class.id = class_subjects.class_id'
    						]
    					])->where([ 'class_subjects.school_id' => $compid, 'class_subjects.class_id IN' => $classids])->order(['class_subjects.id' => 'ASC'])->toArray() ;
    					
    				foreach($retrieve_classsub as $key =>$subcoll)
            		{
            			$subid = explode(",",$subcoll['subject_id']);
            			$i = 0;
            			$subjectsname = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $sid])->toArray() ;	
            				foreach($retrieve_subjects as $rstd)
            				{
            					$subjectsname[$i] = $rstd['subject_name'];				
            				}
            				$i++;
            				$snames = implode(",", $subjectsname);
            				
            			}
            			 $subcoll->subject_name = $snames;
            		}
               

                    $tabledata = "";
                    $datavalue = array();
                    
                    foreach ($retrieve_classsub as $value) 
    				{
    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubjectclass" data-toggle="modal"  data-target="#editsubjectclass" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classSubjects/delete" data-id='.$value['id'].' data-str="Class Subjects" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(8, $scl_privilage) ? $edit : "";
    						$d = in_array(9, $scl_privilage) ? $delete : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    					}
    				
                        if( $value['status'] == 0)
                        {
                            $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                        }
                        else 
                        { 
                            $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                        }
                        
                        $privi = explode(",", $value['subject_name']);
                        if(!empty($privi[1])) 
                        { 
                            $priname = $privi[0].",".$privi[1];
                        }
                        else
                        {
                            $priname = $privi[0];
                        }
                       
                        $classname = $value['class']['c_name']. '-'. $value['class']['c_section'].' ('. $value['class']['school_sections'].')';
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['class']['c_name']. "-". $value['class']['c_section'].' ('. $value['class']['school_sections'].')</span>
                                </td>
                                 <td>
                                    <a href="javascript:void(0)" class="subjectsdetl" data-id="'.$value['subject_name'].'" data-cls="'.$classname.'" ><span>'. $priname .'</span> <span>...</span></a>
                                </td>
                                <td> '.$sts.' </td>
                                <td>
    							'.$e.$d.'
                                </td>
                            </tr>';
                        
                    }
                    
                    $datavalue['html'] = $data;
                    $datavalue['tabledata'] = $tabledata; 
    
                    return $this->json($datavalue);
                }
                else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            
            public function getsctns()
            {
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                $c_name = $this->request->data('cname');
                $sclsectn = $this->request->data('sclsectn');
                $sec = $this->request->data('classes');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
                
                if(!empty($compid)) {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_section' => $sec, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;
                    foreach($retrieve_class as $cls)
                    {
                       
                        $classids[] = $cls['id'];
                    }
                
    			    $classsub_table = TableRegistry::get('class_subjects');
                    $subjects_table = TableRegistry::get('subjects');
    		
                    $retrieve_classsub = $classsub_table->find()->select(['class_subjects.id' ,'class.c_name','class.c_section', 'class.school_sections' ,'class_subjects.subject_id', 'class_subjects.status' ])->join([
                        'class' => 
    						[
    							'table' => 'class',
    							'type' => 'LEFT',
    							'conditions' => 'class.id = class_subjects.class_id'
    						]
    					])->where([ 'class_subjects.school_id' => $compid, 'class_subjects.class_id IN' => $classids])->order(['class_subjects.id' => 'ASC'])->toArray() ;
    					
    				foreach($retrieve_classsub as $key =>$subcoll)
            		{
            			$subid = explode(",",$subcoll['subject_id']);
            			$i = 0;
            			$subjectsname = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $sid])->toArray() ;	
            				foreach($retrieve_subjects as $rstd)
            				{
            					$subjectsname[$i] = $rstd['subject_name'];				
            				}
            				$i++;
            				$snames = implode(",", $subjectsname);
            				
            			}
            			 $subcoll->subject_name = $snames;
            		}
    
                    $tabledata = "";
                    $datavalue = array();
                    
                    foreach ($retrieve_classsub as $value) 
    				{
    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubjectclass" data-toggle="modal"  data-target="#editsubjectclass" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classSubjects/delete" data-id='.$value['id'].' data-str="Class Subjects" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(8, $scl_privilage) ? $edit : "";
    						$d = in_array(9, $scl_privilage) ? $delete : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    					}
    				
                        if( $value['status'] == 0)
                        {
                            $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                        }
                        else 
                        { 
                            $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                        }
                       
                        $privi = explode(",", $value['subject_name']);
                        if(!empty($privi[1])) 
                        { 
                            $priname = $privi[0].",".$privi[1];
                        }
                        else
                        {
                            $priname = $privi[0];
                        }
                        $classname = $value['class']['c_name']. '-'. $value['class']['c_section'].' ('. $value['class']['school_sections'].')';
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['class']['c_name']. "-". $value['class']['c_section'].' ('. $value['class']['school_sections'].')</span>
                                </td>
                                 <td>
                                    <a href="javascript:void(0)" class="subjectsdetl" data-id="'.$value['subject_name'].'" data-cls="'.$classname.'" ><span>'. $priname .'</span> <span>...</span></a>
                                </td>
                                <td> '.$sts.' </td>
                                <td>
    							'.$e.$d.'
                                </td>
                            </tr>';
                        
                    }
                    $datavalue['tabledata'] = $tabledata; 
                    return $this->json($datavalue);
                }
                else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            
            
}

  




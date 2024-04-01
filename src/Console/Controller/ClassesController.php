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
class ClassesController  extends AppController
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name','c_section' ,'active'  ])->where(['school_id' => $compid])->toArray() ;
                    
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'c_name' ])->where(['school_id' => $compid])->group(['c_name'])->toArray() ;
                    $error = "";
                    $filters = '';
                    if(!empty($_POST))
                    {
                        $filters = "filters";
                        $cname = $this->request->data('grades') ;
                        $sclsectn = $this->request->data('sections') ;
                        $classes = $this->request->data('classes') ;
                        
                        if( ($cname != "") && ($sclsectn != "") && ($classes != "") )
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'c_section' => $classes, 'school_sections' => $sclsectn])->toArray() ;
                        }
                        elseif( ($cname != "") && ($sclsectn != ""))
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'school_sections' => $sclsectn])->toArray() ;
                        }
                        elseif($cname != "")
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname])->toArray() ;
                        }
                        else
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid ])->toArray() ;
                        }
                    }
                    
                    $this->set("filters", $filters); 
                    $this->set("error", $error); 
                    $this->set("grade_details", $retrieve_classgrade); 
                    $this->set("class_details", $retrieve_class); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;    
                }
            }
            
            public function getsclsctns()
            {
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                $c_name = $this->request->data('cname');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid)) {
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'school_sections' ])->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->group(['school_sections'])->toArray() ;
                    
                    $data = "";
                    $data .= '<option value="">Choose Sections</option>';
                    foreach($retrieve_classgrade as $cls)
                    {
                        $data .= '<option value="'.$cls['school_sections'].'">'.$cls['school_sections'].'</option>';
                    }
                
    			    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->toArray() ;
    
                    $sclsub_table = TableRegistry::get('school_subadmin');
    				if($this->Cookie->read('logtype') == md5('School Subadmin'))
    				{
    					$sclsub_id = $this->Cookie->read('subid');
    					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
    					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
    				}

    
                    $tabledata = "";
                    $datavalue = array();
                    foreach ($retrieve_class as $value) 
    				{
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';

    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(2, $scl_privilage) ? $edit : "";
    						$d = in_array(3, $scl_privilage) ? $delete : "";
    						$f = in_array(95, $scl_privilage) ? $report : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
                            $f= $report;
    					}
    					
    				
                        if( $value['active'] == 0)
                        {
                            $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        
                        }
                        else 
                        { 
                            $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        }
                        
                        
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['c_section'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['school_sections'].'</span>
                                </td>
                                
                                <td>
                                    '.$status.'
                                </td>
                                <td>
    							'.$f.$e.$d.'
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                if(!empty($compid)) {
                    $c_name = $this->request->data('cname');
                    $sclsectn = $this->request->data('sclsectn');
                    
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'c_section' ])->where(['school_id' => $compid, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;
                    
                    $data = "";
                    $data .= '<option value="">Choose Classes</option>';
                    foreach($retrieve_classgrade as $cls)
                    {
                        $data .= '<option value="'.$cls['c_section'].'">'.$cls['c_section'].'</option>';
                        
                    }
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;
    
                    $sclsub_table = TableRegistry::get('school_subadmin');
    				if($this->Cookie->read('logtype') == md5('School Subadmin'))
    				{
    					$sclsub_id = $this->Cookie->read('subid');
    					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
    					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
    				}
    				
                    $tabledata = "";
                    $datavalue = array();
                    foreach ($retrieve_class as $value) 
    				{
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';

    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    					
    						$e = $edit;
    						$d = $delete;
    						
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(2, $scl_privilage) ? $edit : "";
    						$d = in_array(3, $scl_privilage) ? $delete : "";
                            $r = $report;
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
                            $r = $report;
    					}
    					
    				
                        if( $value['active'] == 0)
                        {
                            $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        
                        }
                        else 
                        { 
                            $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        }
                        
                        
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['c_section'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['school_sections'].'</span>
                                </td>
                                
                                <td>
                                    '.$status.'
                                </td>
                                <td>
    							'.$r.$e.$d.'
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sclsub_table = TableRegistry::get('school_subadmin');
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
                
                if(!empty($compid)) {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_section' => $sec, 'c_name' => $c_name, 'school_sections' => $sclsectn])->order(['school_sections' => 'ASC'])->toArray() ;
                    
                    
                    $tabledata = "";
                    $datavalue = array();
                    foreach ($retrieve_class as $value) 
    				{
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';

    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    					
    						$e = $edit;
    						$d = $delete;
                            
                            
                        if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(2, $scl_privilage) ? $edit : "";
    						$d = in_array(3, $scl_privilage) ? $delete : "";
                            $r = in_array(95, $scl_privilage) ? $report : "";
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    						$r = $report;
                            
    					}
    					
    				
                        if( $value['active'] == 0)
                        {
                            $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        
                        }
                        else 
                        { 
                            $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        }
                        
                        
    					$tabledata .=  '<tr>
                                <td class="width45">
                                <label class="fancy-checkbox">
                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['c_section'].'</span>
                                </td>
                                <td>
                                    <span>'.$value['school_sections'].'</span>
                                </td>
                                
                                <td>
                                    '.$status.'
                                </td>
                                <td>
    							'.$r.$e.$d.'
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

            public function add()
            {   
                $this->viewBuilder()->setLayout('user');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
            }

            public function addcls()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $class_table = TableRegistry::get('class');
                    $activ_table = TableRegistry::get('activity');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $lang = $this->Cookie->read('language');	
        			if($lang != "") { $lang = $lang; }
                    else { $lang = 2; }
                    
                    //echo $lang;
                    $language_table = TableRegistry::get('language_translation');
                    if($lang == 1)
                    {
                        $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                        
                        foreach($retrieve_langlabel as $langlabel)
                        {
                            $langlabel->title = $langlabel['english_label'];
                        }
                    }
                    else
                    {
                        $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                        foreach($retrieve_langlabel as $langlabel)
                        {
                            $langlabel->title = $langlabel['french_label'];
                        }
                    }
                    
                    foreach($retrieve_langlabel as $langlbl) 
                    { 
                        if($langlbl['id'] == '1942') { $grdclsexist = $langlbl['title'] ; } 
                    } 
		            
                    $compid = $this->request->session()->read('company_id');
                    if(!empty($compid)) {
                    if(empty($this->request->data['addsectns']))
                    {
                        $retrieve_classsec = $class_table->find()->select(['id' ])->where(['c_name' => $this->request->data('c_name'), 'school_sections' => $this->request->data('c_section'), 'school_id' => $compid  ])->count() ;
                            if($retrieve_classsec == 0) {
                                $class = $class_table->newEntity();
                                $class->c_name = trim($this->request->data('c_name'));
                                //$class->c_section = trim($sec);
                                $class->school_sections = trim($this->request->data('c_section'));
        						$class->active = 0;
                                $class->school_id = $compid;
                                                      
                                if($saved = $class_table->save($class) )
                                {   
                                    $clsid = $saved->id;
        
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Class Added"  ;
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
                            else
                            {
                                $res = [ 'result' => $grdclsexist  ];
                            }
                    } else {    
                       
                     $sectns = $this->request->data['addsectns'];
                    
                        foreach($sectns as $sec) 
                        {
                            
                            $retrieve_classsec = $class_table->find()->select(['id' ])->where(['c_name' => $this->request->data('c_name'), 'c_section' => trim($sec),'school_sections' => $this->request->data('c_section'), 'school_id' => $compid  ])->count() ;
                            if($retrieve_classsec == 0) {
                                $class = $class_table->newEntity();
                                $class->c_name = trim($this->request->data('c_name'));
                                $class->c_section = trim($sec);
                                $class->school_sections = trim($this->request->data('c_section'));
        						$class->active = 0;
                                $class->school_id = $compid;
                                                      
                                if($saved = $class_table->save($class) )
                                {   
                                    $clsid = $saved->id;
        
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Class Added"  ;
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
                            else
                            {
                                $res = [ 'result' => $grdclsexist  ];
                            }
                        }
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

            public function edit($cid)
            {   
                $class_table = TableRegistry::get('class');
                $module_table = TableRegistry::get('modules');
		        $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_class = $class_table->find()->select([ 'id', 'c_name' , 'c_section' , 'active'])->where([ 'md5(id)' => $cid ])->toArray() ;

                $this->set("class_details", $retrieve_class);
                $this->viewBuilder()->setLayout('user');
            }


            public function editcls()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $class_table = TableRegistry::get('class');
                    $class_sub_table = TableRegistry::get('class_subjects');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))     {
                    //$retrieve_class = $class_table->find()->select(['id' ])->where(['c_name' => $this->request->data('c_name') , 'c_section' => $this->request->data('c_section') , 'id IS NOT' => $this->request->data('id') , 'school_id' => $compid  ])->count() ;
                    
                    $retrieve_classsub = $class_sub_table->find()->select(['subject_id' ])->where(['class_id' => $this->request->data('id'), 'school_id' => $compid  ])->first() ;
                    $subjectids = $retrieve_classsub['subject_id'];
                    $sectns ='';
                    if(!empty($this->request->data['addsectns']))
                    {
                        $sectns = $this->request->data['addsectns'];
                        $count_sec = count($sectns);
                    }
                    else
                    {
                        $count_sec = 0;
                    }
                    
                    $cid = $this->request->data('id');
                    $c_name = trim($this->request->data('c_name'));
                    $scl_section = trim($this->request->data('c_section'));
                    $status = 1;
                    	
                    if($count_sec == 0 )
                    {
                    	if( $class_table->query()->update()->set(['c_name' => $c_name , 'school_sections' => $scl_section , 'active' => $status ])->where([ 'id' => $cid ])->execute())
                    	{   
                    		$activity = $activ_table->newEntity();
                    		$activity->action =  "Class update"  ;
                    		$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    		$activity->origin = $this->Cookie->read('id');
                    		$activity->value = md5($cid); 
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
                    elseif($count_sec == 1 )
                    {
                        $sectns = trim($sectns[0]); 
                    	$retrieve_class = $class_table->find()->select(['id' ])->where([ 'c_name' => $c_name , 'c_section' => $sectns, 'school_sections' => $scl_section,  'id IS NOT' => $this->request->data('id') , 'school_id' => $compid  ])->count() ;
                    	
                    	if($retrieve_class == 0) {
                    		if( $class_table->query()->update()->set(['c_name' => $c_name , 'c_section' => $sectns, 'school_sections' => $scl_section , 'active' => $status ])->where([ 'id' => $cid ])->execute())
                    		{   
                    			$activity = $activ_table->newEntity();
                    			$activity->action =  "Class update"  ;
                    			$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    			$activity->origin = $this->Cookie->read('id');
                    			$activity->value = md5($cid); 
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
                    	foreach($sectns as $key => $sec)
                    	{
                    		$sec = trim($sec);
                    		if($key == 0)
                    		{
                    		    $retrieve_class = $class_table->find()->select(['id' ])->where([ 'c_name' => $c_name , 'c_section' => $sec, 'school_sections' => $scl_section,  'id IS NOT' => $cid , 'school_id' => $compid  ])->count() ;
                    		}
                    		else
                    		{
                    		    $retrieve_class = $class_table->find()->select(['id' ])->where([ 'c_name' => $c_name , 'c_section' => $sec, 'school_sections' => $scl_section,  'school_id' => $compid  ])->count() ;
                    		}
                    		
                    		if($retrieve_class == 0)
                    		{
                    		
                        		if($key == 0)
                        		{
                        			$clssaved = $class_table->query()->update()->set(['c_name' => $c_name , 'c_section' => $sec, 'school_sections' => $scl_section , 'active' => $status ])->where([ 'id' => $cid ])->execute(); 
                        		}
                        		else
                        		{
                        			$class = $class_table->newEntity();
                        			$class->c_name = trim($c_name);
                        			$class->c_section = trim($sec);
                        			$class->school_sections = trim($scl_section);
                        			$class->active = 1;
                        			$class->school_id = $compid;
                        								  
                        			$clssaved = $class_table->save($class);
                        			$clsid = $clssaved->id;
                        			
                        			$classsub = $class_sub_table->newEntity();
                        			$classsub->class_id = $clsid;
                        			$classsub->subject_id = $subjectids;			            
                        			$classsub->status = 1;
                        			$classsub->school_id = $compid;
                        			$classsub->created_date = time();
                        								  
                        			$saved = $class_sub_table->save($classsub);
                        		}
                    		
                        		if($clssaved)
                        		{   
                        			$activity = $activ_table->newEntity();
                        			$activity->action =  "Class Added"  ;
                        			$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        			$activity->origin = $this->Cookie->read('id');
                        			$activity->value = md5($cid); 
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
            
            
            public function view($cid)
            {   
                $class_table = TableRegistry::get('class');
                $module_table = TableRegistry::get('modules');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_class = $class_table->find()->select([ 'id', 'c_name' , 'c_section' , 'active'])->where([ 'md5(id)' => $cid ])->toArray() ;

                $this->set("class_details", $retrieve_class);
                $this->viewBuilder()->setLayout('user');
            }


            public function delete()
            {
                $cid = $this->request->data('val') ;
                $class_table = TableRegistry::get('class');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $cls_sub_table = TableRegistry::get('class_subjects'); 
                $emp_cls_table = TableRegistry::get('employee_class_subjects');
                $exams_table = TableRegistry::get('exams_assessments');
                $exams_ass_table = TableRegistry::get('exam_ass_question');
                $submit_exams_table = TableRegistry::get('submit_exams');
                $fee_structure_table = TableRegistry::get('fee_structure'); 
                $meeting_link_table = TableRegistry::get('meeting_link'); 
                $school_library_table  = TableRegistry::get('school_library'); 
                $tutorial_fee_table  = TableRegistry::get('tutorial_fee'); 
                $tutorial_content_table  = TableRegistry::get('tutorial_content');
                $timetable_table  = TableRegistry::get('time_table'); 
                $student_table  = TableRegistry::get('student');
                
                $clid = $class_table->find()->select(['id'])->where(['id'=> $cid ])->first();
                if($clid)
                {   
                    
                    if($class_table->query()->delete()->where([ 'id' => $cid])->execute())
                    {
                        $clsid = $cid;
                        $del_cls1 = $cls_sub_table->query()->delete()->where([ 'class_id' => $clsid])->execute(); 
                    	$del_cls2 = $emp_cls_table->query()->delete()->where([ 'class_id' => $clsid])->execute(); 
                    	
                    	$get_exm = $exams_table->find()->select(['id'])->where([ 'class_id' => $clsid])->toArray(); 
                    	foreach($get_exm as $exm) {
                    		$del_cls3 = $exams_table->query()->delete()->where([ 'id' => $exm['id'] ])->execute(); 
                    		$del_cls4 = $exams_ass_table->query()->delete()->where([ 'exam_id' => $exm['id'] ])->execute(); 
                    		$del_cls5 = $submit_exams_table->query()->delete()->where([ 'exam_id' => $exm['id'] ])->execute();
                    	}
                    	
                    	$del_cls6 = $fee_structure_table->query()->delete()->where([ 'class_id' => $clsid ])->execute();
                    	$del_cls7 = $meeting_link_table->query()->delete()->where([ 'class_id' => $clsid ])->execute(); 	
                    	$del_cls8= $school_library_table->query()->delete()->where([ 'class_id' => $clsid ])->execute(); 	
                    	$del_cls9= $tutorial_fee_table->query()->delete()->where([ 'class_id' => $clsid ])->execute(); 	
                    	$del_cls10= $tutorial_content_table->query()->delete()->where([ 'class_id' => $clsid ])->execute(); 	
                    	
                    	$del_cls11= $timetable_table->query()->delete()->where([ 'class_id' => $clsid ])->execute(); 	
                    	$del_cls12= $student_table->query()->delete()->where([ 'class' => $clsid ])->execute(); 
	
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Class Deleted"  ;
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data['id'];
                $class_table = TableRegistry::get('class');
                $update_class = $class_table->find()->select(['c_name' , 'id' , 'school_sections', 'c_section','active'])->where(['id' => $id])->toArray(); 
                return $this->json($update_class);
            }  
        }
        
        public function status()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $class_table = TableRegistry::get('class');
            $activ_table = TableRegistry::get('activity');

            $sid = $class_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $class_table->query()->update()->set([ 'active' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Class status changed"  ;
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
				$class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				
				$retrieve_class = $class_table->find()->select(['id' ,'c_name','c_section' ,'active' , 'school_sections' ])->where([ 'school_id' => $compid])->order(['school_sections' => 'ASC'])->toArray() ;
			
                //print_r($_COOKIE);
                $sclsub_table = TableRegistry::get('school_subadmin');
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}

                $data = "";
                $datavalue = array();
                foreach ($retrieve_class as $value) 
				{   
                    $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';

					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
		
					if($this->Cookie->read('logtype') == md5('School Subadmin'))
					{
						$e = in_array(2, $scl_privilage) ? $edit : "";
						$d = in_array(3, $scl_privilage) ? $delete : "";
						$r = in_array(95, $scl_privilage) ? $report : "";
					}
					else
					{
						$e = $edit;
						$d = $delete;
						$r = $report;
					}
					
				
                    if( $value['active'] == 0)
                    {
                        $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                    
                    }
                    else 
                    { 
                        $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                    }
                    
                    
					$data .=  '<tr>
                            <td class="width45">
                            <label class="fancy-checkbox">
                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                            </td>
                            <td>
                                <span>'.$value['c_section'].'</span>
                            </td>
                            <td>
                                <span>'.$value['school_sections'].'</span>
                            </td>
                            
                            <td>
                                '.$status.'
                            </td>
                            <td>
							'.$r.$e.$d.'
                            </td>
                        </tr>';
                    
                }
                
                $datavalue['html']= $data;

                return $this->json($datavalue);

                }
            }


          public function classall(){
             $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid');
                    $sessionid = $this->Cookie->read('sessionid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_stdnt = $student_list->find(all,  ['order' => ['l_name'=>'asc']])->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['class' => $classsub, 'session_id' => $sessionid, 'status' => 1 ])->toArray();
                    $this->set("classes_students", $retrieve_stdnt);   
                    $this->set("classes_name", $retrieve_class);     
                    $this->viewBuilder()->setLayout('user'); 
          }   

          public function excutepublist(){
                $report_table = TableRegistry::get('reportcard');
                $reportcard = $report_table->newEntity();
                $teacher->classid = $this->request->data('classids');
                $report_table->query()->update()->set(['publish' => '1' ])->where([ 'classids' => $this->request->data('classids')])->execute();
                $res = ['result' => 'success'];
                return $this->json($res);

          } 

          public function excuteunpublist(){
              $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $report_table = TableRegistry::get('reportcard');
                $reportcard = $report_table->newEntity();
                $teacher->classid = $this->request->data('classids');
                $report_table->query()->update()->set(['status' => '0' ])->where([ 'classids' => $this->request->data('classids')])->execute();
                $res = ['result' => 'success'];
                return $this->json($res);

          } 

          public function editreport(){
              $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid');                     
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);   
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');   
          } 

          public function subreport(){
              $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $nid = implode(",", $_POST['nid']);
                    $province = $_POST['province'];
                    $code = implode(",", $_POST['code']);
                    $neea = $_POST['neea'];
                    $lethe = implode(",", $_POST['lethe']);
                    $eleve = $_POST['eleve'];
                    $nperm = implode(",", $_POST['nperm']);
                    $stuid = $_POST['studentid'];
                    $classid = $_POST['classid'];
                    $falt = $_POST['falt'];
                    $faltdat = implode(",", $_POST['faltdat']);
                    $bull = implode(",", $_POST['bull']);
                    $max2 = implode(",", $_POST['max2']);
                    $max3 = implode(",", $_POST['max3']);
                    $nmax2 = implode(",", $_POST['nmax2']);
                    $nmax3 = implode(",", $_POST['nmax3']);
                    $max22 = implode(",", $_POST['max22']);
                    $nmax22 = implode(",", $_POST['nmax22']);
                    $signprof = implode(",", $_POST['signprof']);
                    $report_list = TableRegistry::get('reportcard');
                    $reportadd = $report_list->newEntity();
                    $reportadd->nid = $nid;
                    $reportadd->province = $province;
                    $reportadd->code = $code;
                    $reportadd->neea = $neea;
                    $reportadd->lethe = $lethe;
                    $reportadd->eleve = $eleve;
                    $reportadd->nperm = $nperm;
                    $reportadd->stuid = $stuid;
                    $reportadd->stuid = $stuid;
                    $reportadd->classids = $_POST['classid'];
                    $retrieve_student = $report_list->find()->where(['stuid' => $stuid])->count();
                    if($retrieve_student == 0){
                       $saved = $report_list->save($reportadd);
                    }else{
                       $saved = $report_list->query()->update()->set(['nid' => $nid, 'province' => $province, 'code' => $code, 'neea' => $neea, 'lethe' => $lethe,  'eleve' => $eleve, 'nperm' => $nperm, 'bull' => $bull, 'falt' => $falt, 'faltdat' => $faltdat, 'max2' => $max2, 'max3' => $max3, 'max22' => $max22, 'nmax2' => $nmax2, 'nmax3' => $nmax3, 'nmax22' => $nmax22, 'signprof' => $signprof, 'status' => $_POST['status'] , 'classids' => $_POST['classid']])->where([ 'stuid' => $stuid])->execute();
                    }    
                    $myred = "/classes/editreport?classid=".$classid."&studentid=".$stuid;
                    return $this->redirect($myred);
          }
          
            
            
}

  




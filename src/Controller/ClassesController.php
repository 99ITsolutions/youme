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
                    $cnames = [];
                    $retrieve_classgradess = $class_table->find()->where(['school_id' => $compid])->toArray() ;
                    foreach($retrieve_classgradess as $cg)
                    {
                        $cnames[$cg['id']] = $cg['c_name'];
                    }
                    $cname_data = array_unique($cnames);
                    $classkeys = array_keys($cname_data);
                    $retrieve_classgrade = $class_table->find()->where(['id IN' => $classkeys])->toArray() ;
                    
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
                    $sclsub_table = TableRegistry::get('school_subadmin');
    				if($this->Cookie->read('logtype') == md5('School Subadmin'))
    				{
    					$sclsub_id = $this->Cookie->read('subid');
    					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
    					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
    					$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
    				}
                    $csections = [];
                    $retrieve_classgradess = $class_table->find()->select(['id' ,'school_sections' ])->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->toArray() ;
                    foreach($retrieve_classgradess as $cg)
                    {
                        $csections[$cg['id']] = $cg['school_sections'];
                    }
                    $csctn_data = array_unique($csections);
                    $classkeys = array_keys($csctn_data);
                    
                    $retrieve_classgrade = $class_table->find()->where(['id IN' => $classkeys])->toArray() ;
                    
                    $data = "";
                    $data .= '<option value="">Choose Sections</option>';
                    foreach($retrieve_classgrade as $cls)
                    {
                        if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
                            if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                                $clsmsg = "kindergarten";
                            }
                            elseif(strtolower($cls['school_sections']) == "primaire") {
                                $clsmsg = "primaire";
                            }
                            else
                            {
                                $clsmsg = "secondaire";
                            }
                            
                            if(in_array($clsmsg, $subpriv)) { 
                                $show = 1;
                            }
                            else
                            {
                                $show = 0;
                            }
    					}
    					else
    					{
    					     $show = 1;
    					}
					    if($show == 1) {
                            $data .= '<option value="'.$cls['school_sections'].'">'.$cls['school_sections'].'</option>'; 
					    }
                    }
                
    			    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->toArray() ;
    
                    

    
                    $tabledata = "";
                    $datavalue = array();
                    foreach ($retrieve_class as $value) 
    				{
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>
                        <a href="https://you-me-globaleducation.org/school/SchoolReportcard/getclassreport?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-edit"></i> Report Card</button></a>';

    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(2, $scl_privilage) ? $edit : "";
    						$d = in_array(3, $scl_privilage) ? $delete : "";
    						$f = in_array(95, $scl_privilage) ? $report : "";
    						
    						if(strtolower($value['school_sections']) == "creche" || strtolower($value['school_sections']) == "maternelle") {
                                $clsmsg = "kindergarten";
                            }
                            elseif(strtolower($value['school_sections']) == "primaire") {
                                $clsmsg = "primaire";
                            }
                            else
                            {
                                $clsmsg = "secondaire";
                            }
                            
                            if(in_array($clsmsg, $subpriv)) { 
                                $show = 1;
                            }
                            else
                            {
                                $show = 0;
                            }
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
                            $f= $report;
                            $show = 1;
    					}
    					
    				
                        if( $value['active'] == 0)
                        {
                            $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        
                        }
                        else 
                        { 
                            $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        }
                        
                        if($show == 1)
                        {
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
                        			$class->active = 0;
                        			$class->school_id = $compid;
                        								  
                        			$clssaved = $class_table->save($class);
                        			$clsid = $clssaved->id;
                        			
                        			$classsub = $class_sub_table->newEntity();
                        			$classsub->class_id = $clsid;
                        			$classsub->subject_id = $subjectids;			            
                        			$classsub->status = 0;
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
    					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
    					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
    					$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
    				}
    
                    $data = "";
                    $datavalue = array();
                    foreach ($retrieve_class as $value) 
    				{   
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';
                        $reprtcard = ' <a href="https://you-me-globaleducation.org/school/SchoolReportcard/getclassreport?classid='.$value['id'].'"> <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-edit"></i> Report Card</button></a>';
    					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editclass" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
    					$delete = '<button type="button" data-url="classes/delete" data-id='.$value['id'].' data-str="Class" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		
    					if($this->Cookie->read('logtype') == md5('School Subadmin'))
    					{
    						$e = in_array(2, $scl_privilage) ? $edit : "";
    						$d = in_array(3, $scl_privilage) ? $delete : "";
    						$r = in_array(95, $scl_privilage) ? $report : "";
    						
    						if(strtolower($value['school_sections']) == "creche" || strtolower($value['school_sections']) == "maternelle") {
                                $clsmsg = "kindergarten";
                            }
                            elseif(strtolower($value['school_sections']) == "primaire") {
                                $clsmsg = "primaire";
                            }
                            else
                            {
                                $clsmsg = "secondaire";
                            }
                            
                            if(in_array($clsmsg, $subpriv)) { 
                                $show = 1;
                            }
                            else
                            {
                                $show = 0;
                            }
    					}
    					else
    					{
    						$e = $edit;
    						$d = $delete;
    						$r = $report;
    						$show = 1;
    					}
    					
    				
                        if( $value['active'] == 0)
                        {
                            $status = '<label class="switch"><input type="checkbox" disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        
                        }
                        else 
                        { 
                            $status = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round" data-toggle="tooltip" data-placement="top" title="Tooltip on top"></span></label>';
                        }
                        
                        if($show == 1) {
                        
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
    							'.$r.$e.$reprtcard.$d.'
                                </td>
                            </tr>';
                        
                        }
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
                
                $stuids = $this->request->data('stuids');
                //print_r($_POST); die;
                foreach($stuids as $stuid)
                {
                    $report_table->query()->update()->set(['publish' => '1', 'student_status' => 0, 'parent_status' => 0, 'publish_date' => time() ])->where(['stuid' => $stuid, 'classids' => $this->request->data('classids')])->execute();
                    
                    if(!empty($stuid)):
                    $psr_table = TableRegistry::get('parentsignature_report');
                    $psr = $psr_table->newEntity();     
                   
                    $psr->school_publish_date = time();
                    $psr->created_date = time();
                    $psr->session_id =  $this->Cookie->read('sessionid'); 
                    $psr->school_id = $_SESSION['company_id'];         
                    $psr->class_id = $this->request->data('classids');   
                    $psr->student_id = $stuid;   
                    
                    $savedpsr = $psr_table->save($psr) ;
                    
                    
                    $notification_table = TableRegistry::get('notification');
                    $notification = $notification_table->newEntity();     
                    $notification->title = "Your". $classnames. " Report card has published.";
                    $notification->notify_to = "students";
                    $notification->description = "Your". $classnames. " Report card has published. Please check it.";
                    $notification->status = 1;
                    $notification->sent_notify = 0;
                    $notification->added_by = "school";
                    $notification->created_date = time();
                    $notification->schedule_date = date("d-m-Y h:i A", time());
                    $notification->sc_date_time = time();
                    $notification->school_id = $_SESSION['company_id'];         
                    $notification->class_ids = $this->request->data('classids');   
                    $notification->class_opt = 'multiple';   
                    
                    $saved = $notification_table->save($notification) ;
                    
                    endif;
                    $res = ['result' => 'success'];
                }
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
            
            public function getclassreport()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid');                
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                   
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();  
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['l_name'];
                    
                    
                    $this->set("stydent_name", $stydent_name);  
                    $this->set("city", $city);  
                    
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                        return $this->redirect('/classes/editreport?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                       return $this->redirect('/classes/fifthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                       return $this->redirect('/classes/seventhclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème-Primaire")
                    {
                         return $this->redirect('/classes/fourthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                         return $this->redirect('/classes/secondclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                     elseif($clss == "3ème-Maternelle")
                    {
                         return $this->redirect('/classes/threeema?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                         return $this->redirect('/classes/firsterec?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/classes/firstereannee?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/classes/twoerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Primaire")
                    {
                         return $this->redirect('/classes/firstclasspremire?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème-Maternelle")
                    {
                         return $this->redirect('/classes/twoemematernel?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème-Primaire")
                    {
                         return $this->redirect('/classes/thiredclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "6ème-Primaire")
                    {
                         return $this->redirect('/classes/sixthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/classes/threeemezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/classes/twomezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/classes/fouremezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Creche")
                    {
                         return $this->redirect('/classes/oneèrecreche?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/classes/oneerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/classes/fouremeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/classes/threeemeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                         return $this->redirect('/classes/firsterehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/classes/twoemeanneehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/classes/firstpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/classes/secondpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/classes/thiredpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/classes/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/classes/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                        return $this->redirect('/classes/chime?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/classes/threehumanitiemath?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/classes/fourememath?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/classes/fouremebilogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                     elseif($clss == "3ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/classes/threeanneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/classes/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/classes/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                
            }

            public function editreport()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_list = TableRegistry::get('student');
                $class_list = TableRegistry::get('class');
                $subject_list = TableRegistry::get('class_subjects');
                $subject_name = TableRegistry::get('subjects');
                $school_name = TableRegistry::get('company');
                $reportcard = TableRegistry::get('reportcard');
                $classsub = $this->request->query('classid'); 
                $studentsub = $this->request->query('studentid'); 
                 $session_id = $this->Cookie->read('sessionid');
                $report_list = TableRegistry::get('reportcard');   
                
                $retrieve_data = $report_list->find()->where(['stuid' => $stuid])->first();
                    
                $class_list = TableRegistry::get('class');
                $classsub = $this->request->query('classid'); 
                $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                $school_id = $retrieve_class['school_id'];
                $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                $city = $retrieve_schoolinfo['city'];
                $student_list = TableRegistry::get('student');
                $studentsub = $this->request->query('studentid'); 
                $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                $gender = $retrieve_studentinfo['gender'];
                $class = $retrieve_studentinfo['class']; 
                // dd($retrieve_studentinfo);
                // temp
                $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                $retrieve_1st = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE' ])->toArray();
                $retrieve_2nd = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE' ])->toArray();
                // dd($retrieve_1st);
                
                $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                $retrieve_subjectid['subject_id'];
                $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                $this->set("subject_names", $retrieve_subjectname);
                $this->set("gender", $gender);
                $this->set("class", $class);
                $this->set("retrieve_data", $retrieve_data);
                $this->set("city", $city);
                $this->set("classes_name", $retrieve_class); 
                
                // prem_1
                $first_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $this->set("first_gemotry", $first_gemotry); 
                $this->set("second_gemotry", $second_gemotry); 
                
                
                $first_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $this->set("first_algebra", $first_algebra); 
                $this->set("second_algebra", $second_algebra); 
                
                
                $first_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $this->set("first_ARITHMÉTIQUE", $first_ARITHMÉTIQUE); 
                $this->set("second_ARITHMÉTIQUE", $second_ARITHMÉTIQUE); 
                
                
                
                $first_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $this->set("first_STATISTIQUE", $first_STATISTIQUE); 
                $this->set("second_STATISTIQUE", $second_STATISTIQUE); 
                
                
                $first_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $this->set("first_Anatomie", $first_Anatomie); 
                $this->set("second_Anatomie", $second_Anatomie); 
                
                
                $first_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Botanique", $first_Botanique); 
                $this->set("second_Botanique", $second_Botanique);
                
                $first_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Zoologie", $first_Zoologie); 
                $this->set("second_Zoologie", $second_Zoologie);
                
                $first_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_sp", $first_sp); 
                $this->set("second_sp", $second_sp);
                
                
                
                $first_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Technologie", $first_Technologie); 
                $this->set("second_Technologie", $second_Technologie);
                
                
                $first_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_TECHN", $first_TECHN); 
                $this->set("second_TECHN", $second_TECHN);
                
                
                $first_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Anglais", $first_Anglais); 
                $this->set("second_Anglais", $second_Anglais);
                
                
                $first_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Français", $first_Français); 
                $this->set("second_Français", $second_Français);
                
                $first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Religion", $first_Religion); 
                $this->set("second_Religion", $second_Religion);
                
                
                
                $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_edu", $first_edu); 
                $this->set("second_edu", $second_edu);
                
                
                $first_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_moral", $first_moral); 
                $this->set("second_moral", $second_moral);
                
                $first_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Géographie", $first_Géographie); 
                $this->set("second_Géographie", $second_Géographie);
                
                
                $first_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
                
                $this->set("first_Histoire", $first_Histoire); 
                $this->set("second_Histoire", $second_Histoire);
                
                
                
                $first_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                $second_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                //  dd($first_ep);
                $this->set("first_ep", $first_ep); 
                $this->set("second_ep", $second_ep);
                
                    
                $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();
                
                $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();
                $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
                $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Statistique'])->toArray();
                
                $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
                $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
                $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();
                
                $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
                $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();
                
                $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                
                $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
                $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                
                $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                
              
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $second_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $third_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();
                    
                    $fourth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();
                    
                    $fifth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
                    $sixth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Statistique'])->toArray();
                    
                    $sevent_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
                    $eight_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
                    $nine_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();
                    
                    $ten_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
                    $eleven_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $twelve_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();
                    
                    $threteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    
                    $fifteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $sixteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $seventeen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
                    $eightteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    
                    $nineteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                    $twentyone_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                    $twentysix_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_periode)){
                    	$period_first = $reportmax_marks->find()->where(['school_id' => $first_periode[0]->school_id,'session_id' => $session_id,'class_id' =>$classsub, 'request_status' => 1, 'subject_id'=> $first_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_periode)){
                    	$period_second = $reportmax_marks->find()->where(['school_id' => $second_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $second_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_periode)){
                    	$period_third = $reportmax_marks->find()->where(['school_id' => $third_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $third_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_periode)){
                    	$period_fourth = $reportmax_marks->find()->where(['school_id' => $fourth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fourth_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_periode)){
                    	$period_fifth = $reportmax_marks->find()->where(['school_id' => $fifth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifth_periode[0]->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_periode)){
                    	$period_sixth = $reportmax_marks->find()->where(['school_id' => $sixth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixth_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_periode)){
                    	$period_sevent = $reportmax_marks->find()->where(['school_id' => $sevent_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sevent_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_periode)){
                    	$period_eight = $reportmax_marks->find()->where(['school_id' => $eight_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eight_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_periode)){
                    	$period_nine = $reportmax_marks->find()->where(['school_id' => $nine_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nine_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_periode)){
                    	$period_ten = $reportmax_marks->find()->where(['school_id' => $ten_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $ten_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_periode)){
                    	$period_eleven = $reportmax_marks->find()->where(['school_id' => $eleven_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eleven_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_periode)){
                    	$period_twelve= $reportmax_marks->find()->where(['school_id' => $twelve_periode[0]->school_id,'session_id' => $session_id,'class_id' =>  $classsub, 'request_status' => 1, 'subject_id'=> $twelve_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_periode)){
                    	$period_threteen= $reportmax_marks->find()->where(['school_id' => $threteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $threteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_periode)){
                    	$period_forteen= $reportmax_marks->find()->where(['school_id' => $forteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $forteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_periode)){
                    	$period_fifteen= $reportmax_marks->find()->where(['school_id' => $fifteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_periode)){
                    	$period_sixteen= $reportmax_marks->find()->where(['school_id' => $sixteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_periode)){
                    	$period_seventeen= $reportmax_marks->find()->where(['school_id' => $seventeen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $seventeen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_periode)){
                    	$period_eightteen= $reportmax_marks->find()->where(['school_id' => $eightteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eightteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    //print_r($period_eightteen); die;
                    if(!empty($nineteen_periode)){
                    	$period_nineteen= $reportmax_marks->find()->where(['school_id' => $nineteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nineteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_periode)){
                    	$period_twenty= $reportmax_marks->find()->where(['school_id' => $twenty_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twenty_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_periode)){
                    	$period_twentyone = $reportmax_marks->find()->where(['school_id' => $twentyone_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyone_periode[0]->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_periode)){
                    	$period_twentytwo= $reportmax_marks->find()->where(['school_id' => $twentytwo_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentytwo_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_periode)){
                    	$period_twentythree= $reportmax_marks->find()->where(['school_id' => $twentythree_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentythree_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_periode)){
                    	$period_twentyfour= $reportmax_marks->find()->where(['school_id' => $twentyfour_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfour_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_periode)){
                    	$period_twentyfive= $reportmax_marks->find()->where(['school_id' => $twentyfive_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfive_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_periode)){
                    	$period_twentysix= $reportmax_marks->find()->where(['school_id' => $twentysix_periode[0]->school_id,'session_id' => $twentysix_periode[0]->session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentysix_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                

                $this->set("period_first", $period_first); 
                $this->set("period_second", $period_second);
                $this->set("period_third", $period_third);
                $this->set("period_fourth", $period_fourth);
                $this->set("period_fifth", $period_fifth);
                $this->set("period_sixth", $period_sixth); 
                $this->set("period_sevent", $period_sevent);
                $this->set("period_eight", $period_eight);
                $this->set("period_nine", $period_nine);
                $this->set("period_ten", $period_ten);
                $this->set("period_eleven", $period_eleven); 
                $this->set("period_twelve", $period_twelve);
                $this->set("period_threteen", $period_threteen);
                $this->set("period_forteen", $period_forteen);
                $this->set("period_fifteen", $period_fifteen);
                $this->set("period_sixteen", $period_sixteen); 
                $this->set("period_seventeen", $period_seventeen);
                $this->set("period_eightteen", $period_eightteen);
                $this->set("period_nineteen", $period_nineteen);
                $this->set("period_twenty", $period_twenty);
                $this->set("period_twentyone", $period_twentyone); 
                $this->set("period_twentytwo", $period_twentytwo);
                $this->set("period_twentythree", $period_twentythree);
                $this->set("period_twentyfour", $period_twentyfour);
                $this->set("period_twentyfive", $period_twentyfive);
                $this->set("period_twentysix", $period_twentysix);

                $this->set("first_period", $first_period); 
                $this->set("second_period", $second_period);
                $this->set("third_period", $third_period);
                $this->set("fourth_period", $fourth_period);
                $this->set("fifth_period", $fifth_period);
                $this->set("sixth_period", $sixth_period);
                $this->set("sevent_period", $sevent_period);
                $this->set("eight_period", $eight_period);
                $this->set("nine_period", $nine_period);
                $this->set("ten_period", $ten_period);
                $this->set("eleven_period", $eleven_period);
                $this->set("twelve_period", $twelve_period);
                $this->set("threteen_period", $threteen_period);
                $this->set("forteen_period", $forteen_period);
                $this->set("fifteen_period", $fifteen_period);
                $this->set("sixteen_period", $sixteen_period);
                $this->set("seventeen_period", $seventeen_period);
                $this->set("eightteen_period", $eightteen_period);
                $this->set("nineteen_period", $nineteen_period);
                $this->set("twenty_period", $twenty_period);
                $this->set("twentyone_period", $twentyone_period);
                $this->set("twentytwo_period", $twentytwo_period);
                $this->set("twentythree_period", $twentythree_period);
                $this->set("twentyfour_period", $twentyfour_period);
                $this->set("twentyfive_period", $twentyfive_period);
                $this->set("twentysix_period", $twentysix_period);

                $this->set("retrieve_1st", $retrieve_1st); 
                $this->set("retrieve_2nd", $retrieve_2nd); 
                $this->set("retrieve_record", $retrieve_record); 
                $this->set("student_name", $retrieve_studentinfo);   
                $this->set("school_name", $retrieve_schoolinfo);
                $this->set("report_marks", $retrieve_reportcard);
                $this->viewBuilder()->setLayout('user');    
                
            }
            
            public function seventhclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $report_list = TableRegistry::get('reportcard');   
                    $session_id = $this->Cookie->read('sessionid');
                     $retrieve_data = $report_list->find()->where(['stuid' => $stuid])->first();
                   
                    // temp
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');
					$studentsub = $this->request->query('studentid'); 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class']; 
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $retrieve_1st = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE' ])->toArray();
                    $retrieve_2nd = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE' ])->toArray();
                    // dd($retrieve_1st);
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("retrieve_data", $retrieve_data);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class); 
                    
                    // prem_1
                    $first_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_gemotry", $first_gemotry); 
                    $this->set("second_gemotry", $second_gemotry); 
                    
                    
                    $first_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_algebra", $first_algebra); 
                    $this->set("second_algebra", $second_algebra); 
                    
                    
                    $first_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_ARITHMÉTIQUE", $first_ARITHMÉTIQUE); 
                    $this->set("second_ARITHMÉTIQUE", $second_ARITHMÉTIQUE); 
                    
                    
                    
                    $first_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_STATISTIQUE", $first_STATISTIQUE); 
                    $this->set("second_STATISTIQUE", $second_STATISTIQUE); 
                    
                    
                    $first_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_Anatomie", $first_Anatomie); 
                    $this->set("second_Anatomie", $second_Anatomie); 
                                        
                                           
                    $first_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Botanique", $first_Botanique); 
                    $this->set("second_Botanique", $second_Botanique);
                    
                    $first_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Zoologie", $first_Zoologie); 
                    $this->set("second_Zoologie", $second_Zoologie);
                    
                    $first_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_sp", $first_sp); 
                    $this->set("second_sp", $second_sp);
                    
                    
                    
                    $first_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Technologie", $first_Technologie); 
                    $this->set("second_Technologie", $second_Technologie);
                    
                    
                    $first_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_TECHN", $first_TECHN); 
                    $this->set("second_TECHN", $second_TECHN);
                    
                    
                    $first_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Anglais", $first_Anglais); 
                    $this->set("second_Anglais", $second_Anglais);
                    
                    
                    $first_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Français", $first_Français); 
                    $this->set("second_Français", $second_Français);
                    
                    $first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Religion", $first_Religion); 
                    $this->set("second_Religion", $second_Religion);
                    
                    
                    
                    $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_edu", $first_edu); 
                    $this->set("second_edu", $second_edu);
                    
                    
                    $first_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_moral", $first_moral); 
                    $this->set("second_moral", $second_moral);
                    
                    $first_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Géographie", $first_Géographie); 
                    $this->set("second_Géographie", $second_Géographie);
                    
                    
                    $first_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_Histoire", $first_Histoire); 
                    $this->set("second_Histoire", $second_Histoire);
                    
                    
                    
                    $first_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    
                    $this->set("first_ep", $first_ep); 
                    $this->set("second_ep", $second_ep);
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Statistique'])->toArray();
                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();
                    
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();
                    
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $second_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $third_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();
                    
                    $fourth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();
                    
                    $fifth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
                    $sixth_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Statistique'])->toArray();
                    
                    $sevent_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
                    $eight_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
                    $nine_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();
                    
                    $ten_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
                    $eleven_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $twelve_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();
                    
                    $threteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    
                    $fifteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $sixteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $seventeen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
                    $eightteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    
                    $nineteen_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                    $twentyone_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                    $twentysix_periode = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_periode)){
                    	$period_first = $reportmax_marks->find()->where(['school_id' => $first_periode[0]->school_id,'session_id' => $session_id,'class_id' =>$classsub, 'request_status' => 1, 'subject_id'=> $first_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_periode)){
                    	$period_second = $reportmax_marks->find()->where(['school_id' => $second_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $second_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_periode)){
                    	$period_third = $reportmax_marks->find()->where(['school_id' => $third_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $third_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_periode)){
                    	$period_fourth = $reportmax_marks->find()->where(['school_id' => $fourth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fourth_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_periode)){
                    	$period_fifth = $reportmax_marks->find()->where(['school_id' => $fifth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifth_periode[0]->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_periode)){
                    	$period_sixth = $reportmax_marks->find()->where(['school_id' => $sixth_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixth_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_periode)){
                    	$period_sevent = $reportmax_marks->find()->where(['school_id' => $sevent_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sevent_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_periode)){
                    	$period_eight = $reportmax_marks->find()->where(['school_id' => $eight_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eight_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_periode)){
                    	$period_nine = $reportmax_marks->find()->where(['school_id' => $nine_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nine_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_periode)){
                    	$period_ten = $reportmax_marks->find()->where(['school_id' => $ten_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $ten_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_periode)){
                    	$period_eleven = $reportmax_marks->find()->where(['school_id' => $eleven_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eleven_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_periode)){
                    	$period_twelve= $reportmax_marks->find()->where(['school_id' => $twelve_periode[0]->school_id,'session_id' => $session_id,'class_id' =>  $classsub, 'request_status' => 1, 'subject_id'=> $twelve_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_periode)){
                    	$period_threteen= $reportmax_marks->find()->where(['school_id' => $threteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $threteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_periode)){
                    	$period_forteen= $reportmax_marks->find()->where(['school_id' => $forteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $forteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_periode)){
                    	$period_fifteen= $reportmax_marks->find()->where(['school_id' => $fifteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_periode)){
                    	$period_sixteen= $reportmax_marks->find()->where(['school_id' => $sixteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_periode)){
                    	$period_seventeen= $reportmax_marks->find()->where(['school_id' => $seventeen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $seventeen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_periode)){
                    	$period_eightteen= $reportmax_marks->find()->where(['school_id' => $eightteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eightteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_periode)){
                    	$period_nineteen= $reportmax_marks->find()->where(['school_id' => $nineteen_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nineteen_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_periode)){
                    	$period_twenty= $reportmax_marks->find()->where(['school_id' => $twenty_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twenty_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_periode)){
                    	$period_twentyone = $reportmax_marks->find()->where(['school_id' => $twentyone_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyone_periode[0]->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_periode)){
                    	$period_twentytwo= $reportmax_marks->find()->where(['school_id' => $twentytwo_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentytwo_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_periode)){
                    	$period_twentythree= $reportmax_marks->find()->where(['school_id' => $twentythree_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentythree_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_periode)){
                    	$period_twentyfour= $reportmax_marks->find()->where(['school_id' => $twentyfour_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfour_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_periode)){
                    	$period_twentyfive= $reportmax_marks->find()->where(['school_id' => $twentyfive_periode[0]->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfive_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_periode)){
                    	$period_twentysix= $reportmax_marks->find()->where(['school_id' => $twentysix_periode[0]->school_id,'session_id' => $twentysix_periode[0]->session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentysix_periode[0]->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    

                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone); 
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                    $this->set("period_twentyfour", $period_twentyfour);
                    $this->set("period_twentyfive", $period_twentyfive);
                    $this->set("period_twentysix", $period_twentysix);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    $this->set("twentythree_period", $twentythree_period);
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);

                    $this->set("retrieve_1st", $retrieve_1st); 
                    $this->set("retrieve_2nd", $retrieve_2nd); 
                    $this->set("retrieve_record", $retrieve_record); 
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function sixthclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
                    $studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Langues Congolaises)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sportive'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Ecrit. /Calligr.'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédaction'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Français)'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Récitation'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phras. Ecrite & Réda.'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orthographe'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comp. Orale & Vocab.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique / Zoologie'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $twentyseven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twentyeight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentynine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Lecture (Langues Congolaises)'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sportive'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();                    
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Ecrit. /Calligr.'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Rédaction'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Lecture (Français)'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Récitation'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phras. Ecrite & Réda.'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orthographe'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comp. Orale & Vocab.'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique / Zoologie'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $twentyfour_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $twentyfive_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();
                    $twentysix_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $twentyseven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twentyeight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentynine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    //print_r($period_first);exit;
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_priod)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentysix_priod)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyseven_priod)){
                        $period_twentyseven= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyseven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyeight_priod)){
                        $period_twentyeight= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyeight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentynine_priod)){
                        $period_twentynine= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentynine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone); 
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                    $this->set("period_twentyfour", $period_twentyfour);
                    $this->set("period_twentyfive", $period_twentyfive);
                    $this->set("period_twentysix", $period_twentysix);
                    $this->set("period_twentyseven", $period_twentyseven);
                    $this->set("period_twentyeight", $period_twentyeight);
                    $this->set("period_twentynine", $period_twentynine);
                   
                    // dd($sixth_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    $this->set("twentythree_period", $twentythree_period);
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    $this->set("twentyseven_period", $twentyseven_period);
                    $this->set("twentyeight_period", $twentyeight_period);
                    $this->set("twentynine_period", $twentynine_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fifthclass()
            {
                
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');

                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédac. Orthographe'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocabulaire'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orthographe'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédaction'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langue Congolaises'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langue Francaise'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->first();
                    //print_r($school_id);exit;
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocabulaire'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orthographe'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Rédaction'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langue Congolaises'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langue Francaise'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->first();
                    $twentysix_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    } 

                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_priod)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }                    
                    
                    if(!empty($twentysix_priod)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                                       
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone); 
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                    $this->set("period_twentyfour", $period_twentyfour);
                    $this->set("period_twentyfive", $period_twentyfive);
                    $this->set("period_twentysix", $period_twentysix);
                   
                    // dd($sixth_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    
                    $this->set("twelve_period", $twelve_period);
                    
                    $this->set("threteen_period", $threteen_period);
                    
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    
                    
                    
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                    }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function fourthclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    // dd($third_period);
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth);
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven);
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen);
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone);
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function thiredclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    // dd($third_period);
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth);
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven);
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen);
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone);
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function secondclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    $first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_Religion", $first_Religion); 
                    $this->set("second_Religion", $second_Religion); 
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Ecrite'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Vocabulaire'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed civ & morale'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Ecrite'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Vocabulaire'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed civ & morale'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentysix_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_priod)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    // dd($second_period);
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone); 
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                    $this->set("period_twentyfour", $period_twentyfour);
                    $this->set("period_twentyfive", $period_twentyfive);
                    $this->set("period_twentysix", $period_twentysix);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function firstclasspremire ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    // temp
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');
					$studentsub = $this->request->query('studentid'); 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Ecrite'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Vocabulaire'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed civ & morale'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Ecrite'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Vocabulaire'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed civ & morale'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentysix_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_priod)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_priod)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_priod)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_priod)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    // dd($second_period);
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);
                    $this->set("period_nineteen", $period_nineteen);
                    $this->set("period_twenty", $period_twenty);
                    $this->set("period_twentyone", $period_twentyone); 
                    $this->set("period_twentytwo", $period_twentytwo);
                    $this->set("period_twentythree", $period_twentythree);
                    $this->set("period_twentyfour", $period_twentyfour);
                    $this->set("period_twentyfive", $period_twentyfive);
                    $this->set("period_twentysix", $period_twentysix);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
            public function firstereannee ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    // temp
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Droit'])->toArray();                    
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();                   
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();                    
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->toArray();                    
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();                    
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();                    
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();                    
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->toArray();                    
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->toArray();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();                    
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();                    
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();                    
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();                    
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();                
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first(); 
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();     
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->first();                     
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();     
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();                    
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                   
                    $reportmax_marks = TableRegistry::get('reportmax_marks'); 
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);


                    $this->set("twentyone_period", $twentyone_period);
					$this->set("first_period", $first_period); 
					$this->set("second_period", $second_period);
					$this->set("third_period", $third_period);
					$this->set("fourth_period", $fourth_period);
					$this->set("fifth_period", $fifth_period);
					$this->set("sixth_period", $sixth_period);
					$this->set("sevent_period", $sevent_period);
					$this->set("eight_period", $eight_period);
					$this->set("nine_period", $nine_period);
					$this->set("ten_period", $ten_period);
					$this->set("eleven_period", $eleven_period);
					$this->set("twelve_period", $twelve_period);
					$this->set("threteen_period", $threteen_period);
					
					$this->set("forteen_period", $forteen_period);
					$this->set("fifteen_period", $fifteen_period);
					$this->set("sixteen_period", $sixteen_period);
					
					$this->set("seventeen_period", $seventeen_period);
					$this->set("eightteen_period", $eightteen_period);
					$this->set("nineteen_period", $nineteen_period);
					$this->set("twenty_period", $twenty_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
         
            public function twomezestion ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Droit'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);


                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
            public function threeemezestion ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations des banques et des crédits'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'DROIT'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'CORRESPONDANCE COM. FRANÇAISE'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Entreprenariat'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                   $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                    
                    if(!empty($first_priod)){
                    	$period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                    	$period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                    	$period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                    	$period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                    	$period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                    	$period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                    	$period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
            public function fouremezestion ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $session_id = $this->Cookie->read('sessionid');
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Française'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Déontologie Professionnelle'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Finances Publiques'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'DROIT'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie de développement'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Organisations des entreprises'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Analytique'])->toArray();
                   $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                     $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                   $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                   
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'DROIT'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Analytique'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find() ->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    if(!empty($nine_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $twenty_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent); 

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }       
            }
            
            public function firsterehumanitieliter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');    
                                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();                    
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();                    
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();                    
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();                    
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->toArray();                    
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();                    
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->toArray();                    
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais (1)'])->toArray();                    
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();                    
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grec (1)'])->toArray();                    
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();                    
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();                   
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();                     
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais (1)'])->first();               
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first(); 
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){  
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    

    $first_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Latin", $first_Latin); 
    $this->set("second_Latin", $second_Latin); 
    
    $first_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_grc", $first_grc); 
    $this->set("second_grc", $second_grc); 

    $first_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Microbiologie", $first_Microbiologie); 
    $this->set("second_Microbiologie", $second_Microbiologie); 

    $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_edu", $first_edu); 
    $this->set("second_edu", $second_edu);

    $first_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_edus", $first_edus); 
    $this->set("second_edus", $second_edus);
                 
                 
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }
            
            public function twoemeanneehumanitieliter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais (1)'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grec (1)'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais (1)'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();

                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){  
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $first_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_Latin", $first_Latin); 
                    $this->set("second_Latin", $second_Latin); 
                    
                    $first_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_grc", $first_grc); 
                    $this->set("second_grc", $second_grc); 
    
                    $first_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_Microbiologie", $first_Microbiologie); 
                    $this->set("second_Microbiologie", $second_Microbiologie);
    
                    $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_edu", $first_edu); 
                    $this->set("second_edu", $second_edu);
                    
                    $first_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_edus", $first_edus); 
                    $this->set("second_edus", $second_edus);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }       
            }
            
            public function threeemeanneHumanitelitere()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();

                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){  
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
            public function fouremeanneHumanitelitere()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen = array();
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                   
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();

                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){  
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    
                    }
                    
                    if(!empty($forteen_priod)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }
            
            public function firstpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();                    
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();                    
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();                    
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();                    
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();                    
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->toArray();                    
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Travaux Man./Ecriture'])->toArray();                    
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->toArray();                    
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();                    
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();                    
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();                    
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();                    
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();                    
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();                    
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();                    
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();                     
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();              
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();                
                    $nineteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first(); 

                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }          
            }
            
            public function secondpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie/Micro'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie/Micro'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique générale'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
                
            }
            
            public function thiredpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale /théâtrale'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->toArray();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->first();
                    
                    if(!empty($first_priod)){
                    	$period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                    	$period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                    	$period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                    	$period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                    	$period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }          
            }
            
            public function fourthpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                   
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->toArray();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $twentytwo_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
            public function oneerehumanitiescientifices ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre. Stat. et Analy.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géom. et Trigo'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie Générale'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géologie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Techn. d\'Info. & Com. (TIC)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. et Morale'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre. Stat. et Analy.'])->first();
                    $second_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géom. et Trigo'])->first();
                    $third_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie Générale'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();
                    $sixth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géologie'])->first();
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $nine_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Techn. d\'Info. & Com. (TIC)'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $eleven_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. et Morale'])->first();
                    $threteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $fifteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $sixteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->first();
                    $seventeen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $eightteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_priod)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_priod)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_priod)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_priod)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_priod)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_priod)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_priod)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_priod)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_priod)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    $this->set("period_fifth", $period_fifth);
                    $this->set("period_sixth", $period_sixth); 
                    $this->set("period_sevent", $period_sevent);
                    $this->set("period_eight", $period_eight);
                    $this->set("period_nine", $period_nine);
                    $this->set("period_ten", $period_ten);
                    $this->set("period_eleven", $period_eleven); 
                    $this->set("period_twelve", $period_twelve);
                    $this->set("period_threteen", $period_threteen);
                    $this->set("period_forteen", $period_forteen);
                    $this->set("period_fifteen", $period_fifteen);
                    $this->set("period_sixteen", $period_sixteen); 
                    $this->set("period_seventeen", $period_seventeen);
                    $this->set("period_eightteen", $period_eightteen);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function twoerehumanitiescientifices ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Soc. Afri. / Ecopol (1)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find() ->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function threeanneehumanitiescientifices()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find() ->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fouranneehumanitiescientifices()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $session_id = $this->Cookie->read('sessionid');
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find() ->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                   
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function chime()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                    $class_subjects_tab = TableRegistry::get('subjects');
                    
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                        
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fouremebilogie()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie/Micro'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $twentyseven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                   
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'FRANÇAIS'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                        
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);

                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    $this->set("twentyseven_period", $twentyseven_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }  

            public function threehumanitiemath()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                   
                   $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);


                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fourememath()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                     $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $fifth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_priod)){
                    	$period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    	
                    }
                    
                    
                    if(!empty($fifth_priod)){
                    	$period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                    	$period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_priod)){
                    	$period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
            public function oneèrecreche ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de langage'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("ten_period", $ten_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function firsterec ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');
					$studentsub = $this->request->query('studentid'); 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("city", $city);
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de langage'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $first_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->first();
                    $eight_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    if(!empty($first_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                   
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    
                    $this->set("gender", $gender);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                } 
            }
            
            public function twoemematernel ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
                    /*$sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();*/
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    if(!empty($sevent_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                   
                   
                   $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    
                    
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }

            public function threeema ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->request->query('studentid'); 
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
                    /*$sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();*/
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $class_subjects_tab = TableRegistry::get('subjects');
                    $sevent_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->first();
                    $ten_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $fourth_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $twelve_priod = $class_subjects_tab->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    if(!empty($sevent_priod)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_priod)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_priod)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_priod)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_priod->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                   
                   
                   $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                   
                    
                    
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            

            public function fifthRecord()
            {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $tid = $this->request->data('studentid'); 
                    
                    if(!empty($tid))
                    {
                        $classid = $this->request->data('classid'); ;
                        $class_list = TableRegistry::get('class');
                        $retrieve_class = $class_list->find()->where(['id' => $classid ])->first();
                        $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                        
                        $this->request->session()->write('LAST_ACTIVE_TIME', time());
                        $nid = implode(",", $_POST['nid']);
                        
                        $province = $_POST['province'];
                        $nom = $_POST['nom'];
                        $nom_post = $_POST['post_nom'];
                        $matricular = $_POST['matriculer'];
                        $le_date = implode(",", $_POST['le']);
    
                        $code = implode(",", $_POST['code']);
                        $neea = $_POST['neea'];
                        $lethe = $_POST['lethe'];
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
                        $reportadd->falt = $falt;
                        $reportadd->lethe = $lethe;
                        $reportadd->eleve = $eleve;
                        $reportadd->nperm = $nperm;
                        $reportadd->stuid = $stuid;
                        $reportadd->max2 = $matricular;
                        $reportadd->max3 = $le_date;
                        $reportadd->faltdat = $nom;
                        $reportadd->bull = $nom_post;
                        $reportadd->classids = $_POST['classid'];
                        
                        $reportadd->place = implode(",", $_POST['place']);
                        $reportadd->conduite = implode(",", $_POST['condute']);
                        $reportadd->application = implode(",", $_POST['applictn']);
                        
                        $retrieve_student = $report_list->find()->where(['stuid' => $stuid])->count();
                        if($retrieve_student == 0){
                            $saved = $report_list->save($reportadd);
                        } else{
                            $place = implode(",", $_POST['place']);
                            $conduite = implode(",", $_POST['condute']);
                            $application = implode(",", $_POST['applictn']);
                        
                            $saved = $report_list->query()->update()->set([
                                'nid' => $nid, 
                                'application' => $application,
                                'place' => $place,
                                'conduite' => $conduite,
                                'province' => $province,
                                'falt' => $falt,
                                'bull' => $nom_post,
                                'faltdat' => $nom,
                                'code' => $code,
                                'neea' => $neea, 'lethe' => $lethe,  'eleve' => $eleve, 'nperm' => $nperm,  'falt' => $falt, 'max2' => $matricular, 'max3' => $le_date, 'max22' => $max22, 'nmax2' => $nmax2, 'nmax3' => $nmax3, 'nmax22' => $nmax22, 'signprof' => $signprof, 'status' => $_POST['status'] , 'classids' => $_POST['classid']])->where([ 'stuid' => $stuid])->execute();
                        }
                    
                    
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/classes/editreport?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                    return $this->redirect('/classes/fifthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/classes/seventhclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "4ème-Primaire")
                    {
                    return $this->redirect('/classes/fourthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                    return $this->redirect('/classes/secondclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "3ème-Maternelle")
                    {
                    return $this->redirect('/classes/threeema?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                    return $this->redirect('/classes/firsterec?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/classes/firstereannee?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/classes/twoerehumanitiescientifices?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "1ère-Primaire")
                    {
                    return $this->redirect('/classes/firstclasspremire?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "2ème-Maternelle")
                    {
                    return $this->redirect('/classes/twoemematernel?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "3ème-Primaire")
                    {
                    return $this->redirect('/classes/thiredclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "6ème-Primaire")
                    {
                    return $this->redirect('/classes/sixthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/classes/threeemezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/classes/twomezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/classes/fouremezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "1ère-Creche")
                    {
                    
                    return $this->redirect('/classes/oneèrecreche?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/classes/oneerehumanitiescientifices?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/classes/fouremeanneHumanitelitere?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/classes/threeemeanneHumanitelitere?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                    return $this->redirect('/classes/firsterehumanitieliter?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/classes/twoemeanneehumanitieliter?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    
                    
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/firstpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/secondpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/thiredpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/fourthpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/fourthpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/classes/chime?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/classes/fourthpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/classes/threehumanitiemath?classid='.$classid.'&studentid='.$stuid);
                    
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/classes/fourememath?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/classes/fouremebilogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

            public function subreport()
            {
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

  




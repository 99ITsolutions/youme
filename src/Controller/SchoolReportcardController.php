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
class SchoolReportcardController  extends AppController
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
                        $report = '<a href="https://you-me-globaleducation.org/school/classes/classall?classid='.$value['id'].'"><button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" title="Report"><i class="fa fa-book"></i></button></a>';

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

            public function excuteunpublist()
            {
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
                    //$studentsub = $this->request->query('studentid');                
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                   
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    /*$retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();  
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['l_name'];
                    */
                    
                    $this->set("stydent_name", $stydent_name);  
                    $this->set("city", $city);  
                    
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                        return $this->redirect('/SchoolReportcard/editreport?classid='.$classsub);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                       return $this->redirect('/SchoolReportcard/fifthclass?classid='.$classsub);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                       return $this->redirect('/SchoolReportcard/seventhclass?classid='.$classsub);
                    }
                    elseif($clss == "4ème-Primaire")
                    {
                         return $this->redirect('/SchoolReportcard/fourthclass?classid='.$classsub);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                         return $this->redirect('/SchoolReportcard/secondclass?classid='.$classsub);
                    }
                     elseif($clss == "3ème-Maternelle")
                    {
                         return $this->redirect('/SchoolReportcard/threeema?classid='.$classsub);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                         return $this->redirect('/SchoolReportcard/firsterec?classid='.$classsub);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/SchoolReportcard/firstereannee?classid='.$classsub);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/SchoolReportcard/twoerehumanitiescientifices?classid='.$classsub);
                    }
                    elseif($clss == "1ère-Primaire")
                    {
                         return $this->redirect('/SchoolReportcard/firstclasspremire?classid='.$classsub);
                    }
                    elseif($clss == "2ème-Maternelle")
                    {
                         return $this->redirect('/SchoolReportcard/twoemematernel?classid='.$classsub);
                    }
                    elseif($clss == "3ème-Primaire")
                    {
                         return $this->redirect('/SchoolReportcard/thiredclass?classid='.$classsub);
                    }
                    elseif($clss == "6ème-Primaire")
                    {
                         return $this->redirect('/SchoolReportcard/sixthclass?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/SchoolReportcard/threeemezestion?classid='.$classsub);
                    }
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/SchoolReportcard/twomezestion?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/SchoolReportcard/fouremezestion?classid='.$classsub);
                    }
                    elseif($clss == "1ère-Creche")
                    {
                         return $this->redirect('/SchoolReportcard/oneèrecreche?classid='.$classsub);
                    }
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/SchoolReportcard/oneerehumanitiescientifices?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/SchoolReportcard/fouremeanneHumanitelitere?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/SchoolReportcard/threeemeanneHumanitelitere?classid='.$classsub);
                    }
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                         return $this->redirect('/SchoolReportcard/firsterehumanitieliter?classid='.$classsub);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/SchoolReportcard/twoemeanneehumanitieliter?classid='.$classsub);
                    }
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/SchoolReportcard/firstpedagogie?classid='.$classsub);
                    }
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/SchoolReportcard/secondpedagogie?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/SchoolReportcard/thiredpedagogie?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                        return $this->redirect('/SchoolReportcard/chime?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/SchoolReportcard/threehumanitiemath?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/SchoolReportcard/fourememath?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/SchoolReportcard/fouremebilogie?classid='.$classsub);
                    }
                     elseif($clss == "3ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/SchoolReportcard/threeanneehumanitiescientifices?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/SchoolReportcard/fouranneehumanitiescientifices?classid='.$classsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/SchoolReportcard/fouranneehumanitiescientifices?classid='.$classsub);
                    }
                    elseif($clss == "3ème Année-Humanité Electricité Générale")
                    {
                         return $this->redirect('/SchoolReportcard/fouranneehumanitiescientifices?classid='.$classsub);
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
               
                $classsub = $this->request->query('classid');   
                    
                $class_list = TableRegistry::get('class'); 
                $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                $school_id = $retrieve_class['school_id'];
                $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                $city = $retrieve_schoolinfo['city'];               
                $session_id = $this->Cookie->read('sessionid');
                $subject_report_recorder = TableRegistry::get('subjects');
                $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre'])->first();
                
                $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arithmétique'])->first();
                $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géométrie'])->first();
                $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Statistique'])->first();
                
                $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie'])->first();
                $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Botanique'])->first();
                $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie'])->first();
                
                $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences Physiques'])->first();
                $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->first();
                
                $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                
                $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->first();
                $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                
                //   done                   
                $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->first();
                $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                
                $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();

                $reportmax_marks = TableRegistry::get('reportmax_marks');
                if(!empty($first_period)){
                    $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($second_period)){
                    $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($third_period)){
                    $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($fourth_period)){
                    $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($fifth_period)){
                    $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($sixth_period)){
                    $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($sevent_period)){
                    $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($eight_period)){
                    $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($nine_period)){
                    $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($ten_period)){
                    $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($eleven_period)){
                    $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($twelve_period)){
                    $period_twelve = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }
                if(!empty($threteen_period)){
                    $period_threteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($forteen_period)){
                    $period_forteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($fifteen_period)){
                    $period_fifteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($sixteen_period)){
                    $period_sixteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($seventeen_period)){
                    $period_seventeen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }
                if(!empty($eightteen_period)){
                    $period_eightteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($nineteen_period)){
                    $period_nineteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($twenty_period)){
                    $period_twenty = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }
                if(!empty($twentyone_period)){
                    $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }   
                if(!empty($twentytwo_period)){
                    $period_twentytwo = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($twentythree_period)){
                    $period_twentythree = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }
                if(!empty($twentyfour_period)){
                    $period_twentyfour = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($twentyfive_period)){
                    $period_twentyfive = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                }                
                if(!empty($twentysix_period)){
                    $period_twentysix = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid');     
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid');                     
                    $session_id = $this->Cookie->read('sessionid');
                    // temp
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_list = TableRegistry::get('class');
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);                    
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    $this->set("retrieve_data", $retrieve_data);
                    $this->set("classes_name", $retrieve_class); 
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre'])->first();
                    
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arithmétique'])->first();                    
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géométrie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Statistique'])->first();
                    
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Botanique'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie'])->first();
                    
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences Physiques'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->first();
                    
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->first();
                    
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks'); 
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $first_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $second_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $third_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $fourth_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $fifth_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $sixth_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $sevent_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $eight_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $nine_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $ten_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $eleven_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $twelve_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $threteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }  
                    //print_r($period_threteen); die;
                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $forteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $fifteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $sixteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $seventeen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $eightteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $nineteen_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $twenty_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $twentyone_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $twentytwo_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $twentythree_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_period)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $twentyfour_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $twentyfive_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentysix_period)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $twentysix_period->school_id,'session_id' => $session_id,'class_id' => $classsub, 'request_status' => 1, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');

                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Lecture (Langues Congolaises)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sportive'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();                    
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Ecrit. /Calligr.'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Rédaction'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Lecture (Français)'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Récitation'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phras. Ecrite & Réda.'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orthographe'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comp. Orale & Vocab.'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique / Zoologie'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $twentyseven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twentyeight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentynine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_period)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentysix_period)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyseven_period)){
                        $period_twentyseven= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyseven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyeight_period)){
                        $period_twentyeight= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyeight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentynine_period)){
                        $period_twentynine= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentynine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->first();
                    //print_r($school_id);exit;
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Rédac. Orthographe'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocabulaire'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orthographe'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Rédaction'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langue Congolaises'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langue Francaise'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_period)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentysix_period)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();

                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    } 

                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    } 

                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();

                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    } 

                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }

                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    } 

                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Ecrite'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Vocabulaire'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed civ & morale'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_period)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                   
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Ecrite'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Vocabulaire'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues Congolaises'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grandeurs'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Formes géométriques'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Numération'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed civ & morale'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Problèmes'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->first();
                    
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Technologie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Calligraphie'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts plastiques'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_twelve= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_threteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_forteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_sixteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_seventeen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eightteen_period)){
                        $period_eightteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_nineteen= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_twenty= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyone_period)){
                        $period_twentyone = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyone_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_twentytwo= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentythree_period)){
                        $period_twentythree= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentythree_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfour_period)){
                        $period_twentyfour= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfour_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentyfive= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentyfive_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentyfive_period)){
                        $period_twentysix= $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentysix_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Droit'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie économique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Fiscalité'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->first();                    
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();                    
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->first();                    
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();                    
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->first();                    
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->first();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();                    
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();                    
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();                    
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();                    
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eleven_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Droit'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie économique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Fiscalité'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($threteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                //print_r($tid);exit;
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                      
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie économique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Fiscalité'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Opérations des banques et des crédits'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'DROIT'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'CORRESPONDANCE COM. FRANÇAISE'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Entreprenariat'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Economie Politique'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nine_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twenty_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                //print_r($tid);exit;
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    /*$first_period1 = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $first_period = $class_subjects->find('all',array('conditions' => array('school_id' => $school_id, 'class_id' => $classsub, 'status' => 1,'FIND_IN_SET(\''. $first_period1->id .'\',subject_id)')))->order(['id' => 'desc']);
                    print_r($first_period);exit;*/
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Correspondance Com. Française'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Déontologie Professionnelle'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Finances Publiques'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'DROIT'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Economie de développement'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Organisations des entreprises'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Fiscalité'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Analytique'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->first();
                   
                    //print_r($first_period);exit;
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                   
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find() ->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($third_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sevent_period)){
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub,  'subject_id'=> $twenty_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                    /*$this->set("student_name", $retrieve_studentinfo);*/   
                    /*$this->set("school_name", $retrieve_schoolinfo);*/
                    /*$this->set("report_marks", $retrieve_reportcard);*/
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                   $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks'); 
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();                    
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();                    
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();                    
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();                    
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();                    
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();                    
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();                    
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->first();                    
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();                    
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->first();                    
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais (1)'])->first();                    
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();                    
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grec (1)'])->first();                    
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();                    
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Latin'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($twelve_period)){    
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($sixteen_period)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais (1)'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Grec (1)'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Latin'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($twelve_period)){    
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($sixteen_period)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    
                    $this->set("period_first", $period_first); 
                    $this->set("period_second", $period_second);
                    $this->set("period_third", $period_third);
                    $this->set("period_fourth", $period_fourth);
                    
                    
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Esthétique'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Latin'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($twelve_period)){    
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($forteen_period)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Philosophie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Latin'])->first();
                    
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($twelve_period)){    
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($forteen_period)){ 
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();                    
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();                    
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->first();                    
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();                    
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();                    
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();                    
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();                    
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();                    
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->first();                    
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Travaux Man./Ecriture'])->first();                    
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->first();                    
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues nationales'])->first();                    
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();                    
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();                    
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Psychologie'])->first();                    
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();                    
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();                    
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();                    
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();                    
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'FRANÇAIS'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($nineteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nineteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie/Micro'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Economie Politique'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'FRANÇAIS'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique générale'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Psychologie'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($seventeen_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Esthétique'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education musicale /théâtrale'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues nationales'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Psychologie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique générale'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Philosophie'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Langues nationales'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Psychologie'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique générale'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twentytwo_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twentytwo_period->id, 'tchr_updatemarks_sts' => '1'])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Algèbre. Stat. et Analy.'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géom. et Trigo'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie Générale'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Microbiologie'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géologie'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Techn. d\'Info. & Com. (TIC)'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. et Morale'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $second_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($third_period)){ 
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $third_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_fifth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($sixth_period)){
                        $period_sixth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($sevent_period)){ 
                        $period_sevent = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sevent_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($eight_period)){
                        $period_eight = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($nine_period)){
                        $period_nine = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $nine_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($ten_period)){
                        $period_ten = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($eleven_period)){ 
                        $period_eleven = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eleven_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($twelve_period)){
                        $period_twelve = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($threteen_period)){
                        $period_threteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $threteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($forteen_period)){
                        $period_forteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifteen_period)){ 
                        $period_fifteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($sixteen_period)){
                        $period_sixteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($seventeen_period)){
                        $period_seventeen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $seventeen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($eightteen_period)){
                        $period_eightteen = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eightteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Soc. Afri. / Ecopol (1)'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();

                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                   if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($ten_period)){ 
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($forteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                      
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Esthétique'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                 
                   
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($ten_period)){ 
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($forteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                //print_r($tid);exit;
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Philosophie'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($second_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($third_period)){ 
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    if(!empty($fourth_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Esthétique'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                //print_r($tid);exit;
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                     $class_list = TableRegistry::get('class');
                      $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                   
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie/Micro'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Economie Politique'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $sixteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'FRANÇAIS'])->first();
                    $seventeen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique générale'])->first();
                    $eightteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->first();
                    $nineteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Psychologie'])->first();
                    $twenty_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    $twentyone_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->first();
                    $twentytwo_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Pédagogie'])->first();
                    $twentythree_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Arts dramatiques'])->first();
                    $twentyfour_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->first();
                    $twentyfive_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->first();
                    $twentysix_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $twentyseven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Philosophie'])->first();
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifteen_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($sixteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $sixteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                      
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion (1)'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Esthétique'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                //print_r($tid);exit;
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Religion'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education à la vie'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Informatique (1)'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Dessin'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Education Physique'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Géographie'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Histoire'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Philosophie'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Anglais'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Chimie'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Biologie'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Physique'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Français'])->first();
                    $fifteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Mathématiques'])->first();
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fifth_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fifth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($forteen_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de comportement'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités musicales'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Physiques'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->first();                    
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités mathematiques'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->first();
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de comportement'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités musicales'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Physiques'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de langage'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->first();                    
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités mathematiques'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    
                    
                    if(!empty($first_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $first_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($eight_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $eight_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités mathematiques'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de language'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de comportement'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités musicales'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Physiques'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de latéralité'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->first();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($forteen_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                $tid = $this->request->query('classid'); 
                $session_id = $this->Cookie->read('sessionid');
                if(!empty($tid))
                {
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    
                    $subject_report_recorder = TableRegistry::get('subjects');
                    $class_subjects = TableRegistry::get('class_subjects');
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités mathematiques'])->first();
                    $third_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de language'])->first();
                    $fourth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités exploratrices'])->first();
                    $fifth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de comportement'])->first();
                    $sixth_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->first();
                    $sevent_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->first();
                    $eight_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités musicales'])->first();
                    $nine_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités Physiques'])->first();
                    $ten_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités sensorielles'])->first();
                    $eleven_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->first();
                    $twelve_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités libres'])->first();
                    $threteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de latéralité'])->first();
                    $forteen_period = $subject_report_recorder->find()->where(['school_id' => $school_id, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->first();
                 
                    $period_first =  $period_second = $period_third = $period_fourth = $period_fifth = $period_sixth =  $period_sevent = $period_eight = $period_nine = $period_ten = $period_eleven =  $period_twelve = $period_threteen = $period_forteen = $period_fifteen =  $period_sixteen =  $period_seventeen = $period_eightteen = $period_nineteen = $period_twenty = $period_twentyone =  $period_twentytwo = $period_twentythree = $period_twentyfour = $period_twentyfive = $period_twentysix = array();
                    $reportmax_marks = TableRegistry::get('reportmax_marks');
                    if(!empty($forteen_period)){
                        $period_first = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $forteen_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($ten_period)){
                        $period_second = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $ten_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($fourth_period)){
                        $period_third = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $fourth_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
                    }
                    
                    if(!empty($twelve_period)){
                        $period_fourth = $reportmax_marks->find()->where(['school_id' => $school_id,'session_id' => $session_id,'class_id' => $classsub, 'subject_id'=> $twelve_period->id, 'tchr_updatemarks_sts' => 1])->order(['id' => 'DESC'])->first();
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
                    $classid = $this->request->data('classid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    if(!empty($classid))
                    {
                        $data = $this->request->data('subject');
                        $reportmax_marks = TableRegistry::get('reportmax_marks');
                        
                        $class_list = TableRegistry::get('class');
                        $retrieve_class = $class_list->find()->where(['id' => $classid ])->first();
                        $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                        
                        $school_id = $retrieve_class['school_id'];
                    //print_r($data);exit;
                        $reportcrd =$this->request->data('reportcrd');
                        foreach($data as $key=> $marks){
                            foreach($marks['subject_id'] as $subjects){
                                $ret_mark = $reportmax_marks->find()->where(['class_id' => $classid, 'school_id'=> $school_id,'report_card' =>$reportcrd, 'session_id' => $session_id, 'section' => $key, 'subject_id' => $subjects])->first();
                                
                                if(empty($ret_mark)){
                                    $reportadd = $reportmax_marks->newEntity();
                                    $reportadd->class_id = $classid;
                                    $reportadd->school_id = $school_id;
                                    $reportadd->session_id = $session_id;
                                    $reportadd->report_card = $reportcrd;
                                    $reportadd->section = $key;
                                    $reportadd->subject_id = $subjects;
                                    $reportadd->tchr_updatemarks_sts = 1;
                                    $reportadd->max_marks = $marks['marks'][0];
                                    $reportadd->created_date = time();
                                    $saved = $reportmax_marks->save($reportadd);
                                }else{
                                    $saved = $reportmax_marks->query()->update()->set(['max_marks' => $marks['marks'][0], 'tchr_updatemarks_sts' => 1, 'updated_at' => time()])->where([ 'id' => $ret_mark->id])->execute();
                                }
                            }
                            
                        }
                        
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/SchoolReportcard/editreport?classid='.$classid);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/fifthclass?classid='.$classid);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/SchoolReportcard/seventhclass?classid='.$classid);
                    }
                    
                    elseif($clss == "4ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/fourthclass?classid='.$classid);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/secondclass?classid='.$classid);
                    }
                    elseif($clss == "3ème-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/threeema?classid='.$classid);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/firsterec?classid='.$classid);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/firstereannee?classid='.$classid);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/twoerehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/threeanneehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "1ère-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/firstclasspremire?classid='.$classid);
                    }
                    
                    elseif($clss == "2ème-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/twoemematernel?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/thiredclass?classid='.$classid);
                    }
                    
                    elseif($clss == "6ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/sixthclass?classid='.$classid);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/threeemezestion?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/twomezestion?classid='.$classid);
                    }
                    
                    
                    
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/fouremezestion?classid='.$classid);
                    }
                    
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/fouranneehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "1ère-Creche")
                    {
                    
                    return $this->redirect('/SchoolReportcard/oneèrecreche?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/oneerehumanitiescientifices?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/fouremeanneHumanitelitere?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/threeemeanneHumanitelitere?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/firsterehumanitieliter?classid='.$classid);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/twoemeanneehumanitieliter?classid='.$classid);
                    }
                    
                    
                    
                    
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/firstpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/secondpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/thiredpedagogie?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/SchoolReportcard/chime?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    
                    
                    
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/SchoolReportcard/threehumanitiemath?classid='.$classid);
                    
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/SchoolReportcard/fourememath?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/SchoolReportcard/fouremebilogie?classid='.$classid);
                    }
                    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fifthRecordSecondFormat()
            {
                
                   $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $classid = $this->request->data('classid'); 
                    $session_id = $this->Cookie->read('sessionid');
                    if(!empty($classid))
                    {
                        $data = $this->request->data('subject');
                        $reportmax_marks = TableRegistry::get('reportmax_marks');
                        //print_r($data); die;
                        $class_list = TableRegistry::get('class');
                        $retrieve_class = $class_list->find()->where(['id' => $classid ])->first();
                        $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                        
                        $school_id = $retrieve_class['school_id'];
                        $reportcrd =$this->request->data('reportcrd');
                        foreach($data as $subjects=> $marks){
                            $ret_mark = $reportmax_marks->find()->where(['class_id' => $classid, 'school_id'=> $school_id,'report_card' =>$reportcrd, 'session_id' => $session_id, 'subject_id' => $subjects])->first();
                            
                            if(empty($ret_mark)){
                                $reportadd = $reportmax_marks->newEntity();
                                $reportadd->class_id = $classid;
                                $reportadd->school_id = $school_id;
                                $reportadd->session_id = $session_id;
                                $reportadd->report_card = $reportcrd;
                                $reportadd->subject_id = $subjects;
                                $reportadd->tchr_updatemarks_sts = 1;
                                $reportadd->max_marks = $marks;
                                $reportadd->created_date = time();
                                $saved = $reportmax_marks->save($reportadd);
                            }else{
                                $saved = $reportmax_marks->query()->update()->set(['max_marks' => $marks, 'tchr_updatemarks_sts' => 1, 'updated_at' => time()])->where([ 'id' => $ret_mark->id])->execute();
                            }
                            
                        }
                        
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/SchoolReportcard/editreport?classid='.$classid);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/fifthclass?classid='.$classid);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                    return $this->redirect('/SchoolReportcard/seventhclass?classid='.$classid);
                    }
                    
                    elseif($clss == "4ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/fourthclass?classid='.$classid);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/secondclass?classid='.$classid);
                    }
                    elseif($clss == "3ème-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/threeema?classid='.$classid);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/firsterec?classid='.$classid);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/firstereannee?classid='.$classid);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/twoerehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/threeanneehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "1ère-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/firstclasspremire?classid='.$classid);
                    }
                    
                    elseif($clss == "2ème-Maternelle")
                    {
                    return $this->redirect('/SchoolReportcard/twoemematernel?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/thiredclass?classid='.$classid);
                    }
                    
                    elseif($clss == "6ème-Primaire")
                    {
                    return $this->redirect('/SchoolReportcard/sixthclass?classid='.$classid);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/threeemezestion?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/twomezestion?classid='.$classid);
                    }
                    
                    
                    
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                    return $this->redirect('/SchoolReportcard/fouremezestion?classid='.$classid);
                    }
                    
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/fouranneehumanitiescientifices?classid='.$classid);
                    }
                    
                    elseif($clss == "1ère-Creche")
                    {
                    
                    return $this->redirect('/SchoolReportcard/oneèrecreche?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                    return $this->redirect('/SchoolReportcard/oneerehumanitiescientifices?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/fouremeanneHumanitelitere?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/threeemeanneHumanitelitere?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/firsterehumanitieliter?classid='.$classid);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                    return $this->redirect('/SchoolReportcard/twoemeanneehumanitieliter?classid='.$classid);
                    }
                    
                    
                    
                    
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/firstpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/secondpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/thiredpedagogie?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    
                    
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/SchoolReportcard/chime?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                    return $this->redirect('/SchoolReportcard/fourthpedagogie?classid='.$classid);
                    }
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/SchoolReportcard/threehumanitiemath?classid='.$classid);
                    
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                    return $this->redirect('/SchoolReportcard/fourememath?classid='.$classid);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                    return $this->redirect('/SchoolReportcard/fouremebilogie?classid='.$classid);
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

  




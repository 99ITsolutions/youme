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
class TimetableController  extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        $class_table = TableRegistry::get('class');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $timetable_table = TableRegistry::get('time_table');
        $scl_table = TableRegistry::get('company');
        $sclid = $this->request->session()->read('company_id');
        $sclsub_table = TableRegistry::get('school_subadmin');
		if($this->Cookie->read('logtype') == md5('School Subadmin'))
		{
			$sclsub_id = $this->Cookie->read('subid');
			$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $sclid ])->first() ; 
			$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
			$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
		}
		
		$session_id = $this->Cookie->read('sessionid');
	    
		//print_r($_SESSION); die;
		$retrieve_school = $scl_table->find()->where(['id' => $sclid ])->first() ;
		$retrieve_classes = $class_table->find()->where(['school_id' => $sclid ])->toArray() ;
		$retrieve_session = $session_table->find()->toArray() ;
		
		$retrieve_timetable = $timetable_table->find()->where(['school_id' => $sclid, 'session_id' => $session_id ])->toArray() ;
		//print_r($retrieve_timetable); die;
		if(!empty($retrieve_timetable)) {
    		foreach($retrieve_timetable as $timetbl)
    		{
    		    $subid = $timetbl['subject_id'];
    		    $retrieve_subject = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		    $timetbl->subjectName = $retrieve_subject['subject_name'];
    		    
    		    $clsid = $timetbl['class_id'];
    		    $retrieve_class = $class_table->find()->where(['id' => $clsid ])->first() ;
    		    if(!empty($retrieve_class['c_section']))
    		    {
    		        $sec = " - ".$retrieve_class['c_section'];
    		    }
    		    else
    		    {
    		        $sec = "";
    		    }
    		    $timetbl->className = $retrieve_class['c_name'].$sec."(".$retrieve_class['school_sections'].")";
    		    
    		    if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
                    if(strtolower($retrieve_class['school_sections']) == "creche" || strtolower($retrieve_class['school_sections']) == "maternelle") {
                        $clsmsg = "kindergarten";
                    }
                    elseif(strtolower($retrieve_class['school_sections']) == "primaire") {
                        $clsmsg = "primaire";
                    }
                    else
                    {
                        $clsmsg = "secondaire";
                    }
                    
                    //print_r($subpriv);
                    if(in_array($clsmsg, $subpriv)) { 
                        $timetbl->show = 1;
                    }
                    else
                    {
                        $timetbl->show = 0;
                    }
				}
				else
				{
				     $timetbl->show = 1;
				}
    		}
		}
		
		$this->set("scl_details", $retrieve_school);
		$this->set("timetbl_details", $retrieve_timetable);
        $this->set("session_details", $retrieve_session);
        $this->set("class_details", $retrieve_classes);
        $this->viewBuilder()->setLayout('user');
    }
    
    public function view()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $timetable_table = TableRegistry::get('time_table');
        $scl_table = TableRegistry::get('company');
        $scltimeslot_table = TableRegistry::get('school_timetable');
		$retrieve_school = $scl_table->find()->where(['id' => $sclid ])->first() ;
        
		$session_id = $this->Cookie->read('sessionid');
		$retrieve_class = $class_table->find()->where(['school_id' => $sclid ])->toArray() ;
		$retrieve_session = $session_table->find()->toArray() ;
		if(!empty($_POST))
		{
		    $classid = $this->request->data('class_fil');
		    $session_id = $this->request->data('session');
    		$retrieve_timetable = $timetable_table->find()->where(['school_id' => $sclid, 'session_id' => $session_id, 'class_id' => $classid ])->order(['start_time' => 'ASC'])->toArray() ;
    		foreach($retrieve_timetable as $timetbl)
    		{
    		    $subid = $timetbl['subject_id'];
    		    $retrieve_subject = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		    $timetbl->subjectName = $retrieve_subject['subject_name'];
    		}
    		
    		$class_table = TableRegistry::get('class');
            $get_class = $class_table->find()->where(['id' => $classid])->first();
            $class_sectnname = strtolower($get_class['school_sections']);
    		$sclinfo = [];
            if($class_sectnname == "creche" || $class_sectnname == "maternelle")
            {
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'KinderGarten'])->order(['id' => 'ASC'])->toArray();
                $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['kinderscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['kinderscl_endtimings']));
                
            }
            elseif($class_sectnname == "primaire")
            {
                $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['primaryscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['primaryscl_endtimings']));
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'Primary'])->order(['id' => 'ASC'])->toArray();
            }
            else
            {
                $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['seniorscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['seniorscl_endtimings']));
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'Senior'])->order(['id' => 'ASC'])->toArray();
            }
    		if(empty($get_slot))
    		{
    		    $error = "No data Exist";
    		}
    		else
    		{
    		     $error = "";
    		}
    		$this->set("classid", $classid);
		    $this->set("sessionid", $session_id);
        }
		else
		{
		    $sclinfo = '';
		    $classid = '';
		    $session_id = '';
		    $retrieve_timetable = '';
		    $error = '';
		    $get_slot ='';
		    $this->set("classid", $classid);
		    $this->set("sessionid", $session_id);
		} 
		$this->set("get_slot", $get_slot);
		$this->set("sclinfo", $sclinfo);
		$this->set("scl_details", $retrieve_school);
		$this->set("timetbl_details", $retrieve_timetable);
        $this->set("session_details", $retrieve_session);
        $this->set("class_details", $retrieve_class);
        $this->set("error", $error);
        $this->viewBuilder()->setLayout('user');
    }
    
    public function getsubjectsss()
    {   
        if($this->request->is('post'))
        {
            $lang = $this->Cookie->read('language');
            if($lang != "")
            {
                $lang = $lang;
            }
            else
            {
                $lang = 2;
            }
            
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
            
            foreach($retrieve_langlabel as $langlbl) { 
                if($langlbl['id'] == '2103') { $chozslt = $langlbl['title'] ; } 
            }
            
            $id = $this->request->data('classId');
            $prdslot = $this->request->data('prdslot');
            $subid = $this->request->data('subId');
            $schoolid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $separator = "subjects";
            $classsub_table = TableRegistry::get('class_subjects');
            $class_table = TableRegistry::get('class');
            $scltimeslot_table = TableRegistry::get('school_timetable');
            $get_class = $class_table->find()->where(['id' => $id])->first();
            $class_sectnname = strtolower($get_class['school_sections']);
            
            $company_table = TableRegistry::get('company');
            $get_schoolinfo = $company_table->find()->where(['id' => $schoolid])->first();
            $sclinfo = [];
            if($class_sectnname == "creche" || $class_sectnname == "maternelle")
            {
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['kinderscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['kinderscl_endtimings']));
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $schoolid, 'added_for' => 'KinderGarten', 'period_name' => ""])->order(['id' => 'ASC'])->toArray();
            }
            elseif($class_sectnname == "primaire")
            {
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['primaryscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['primaryscl_endtimings']));
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $schoolid, 'added_for' => 'Primary', 'period_name' => ""])->order(['id' => 'ASC'])->toArray();
            }
            else
            {
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['seniorscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['seniorscl_endtimings']));
                $get_slot = $scltimeslot_table->find()->where(['school_id' => $schoolid, 'added_for' => 'Senior', 'period_name' => ""])->order(['id' => 'ASC'])->toArray();
            }
            
            $getslot = "";
            $getslot .= '<option value="">'. $chozslt.'</option>';
            foreach($get_slot as $slot)
            {
                $sel = "";
                if($prdslot == $slot['start_time']){ $sel = "selected"; }
                $getslot .= '<option value="'.$slot['start_time'].'" '.$sel.'>'.$slot['start_time'].'-'.$slot['end_time'].'</option>';
            }
            
            $subject_table = TableRegistry::get('subjects');
            $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $schoolid])->first();
            $html_content='';
            if($get_subjects->subject_id !=''){
                $subjects = explode(',',$get_subjects->subject_id);
                
                $html_content .= '<option value="">Choose Subjects</option>';
                if(empty($subid))
                {
                    foreach($subjects as $subject){
                        $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                        $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'">'.$subjects_data->subject_name.'</option>';
                    }
                }
                else
                {
                    foreach($subjects as $subject){
                        $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first();
                        $sel = '';
                        if($subid == $subjects_data->id)
                        {
                            $sel = "selected";
                        }
                        $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'" '.$sel.'>'.$subjects_data->subject_name.'</option>';
                    }
                }
            }
            
            $data['abc'] = $html_content;
            $data['sep'] = $separator;
            $data['sclinfo'] = $sclinfo;
            $data['getslot'] = $getslot;
            return $this->json($data);

        }  
    }
    
    public function gettchrinfo()
    {   
        if($this->request->is('post'))
        {
            $cid = $this->request->data('classId');
            $sid = $this->request->data('subid');
            $schoolid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $empcs_table = TableRegistry::get('employee_class_subjects');
            $emp_table = TableRegistry::get('employee');
            $get_empid = $empcs_table->find()->where(['class_id' => $cid, 'subject_id' => $sid])->first();
            
            $empid = $get_empid['emp_id'];
            $get_empname = $emp_table->find()->where(['id' => $empid, 'school_id' => $schoolid, 'status' => 1])->first();
            $empname = $get_empname['l_name']." ".$get_empname['f_name'];
            $data['tchrname'] = $empname;
            $data['tchrid'] = $get_empname['id'];
            
            return $this->json($data);

        }  
    }
    
    
    public function addtimetbl()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $timetable_table = TableRegistry::get('time_table');
            $activ_table = TableRegistry::get('activity');
            $session_id = $this->Cookie->read('sessionid');
            $compid = $this->request->session()->read('company_id');
            $subject_id = $this->request->data('subjects');
			$class_id = $this->request->data('class');
			$week = $this->request->data('weekday');
			$st = $this->request->data('event_start_time');
            $bt = $this->request->data('breaktime');
            //$retrieve_tt = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'subject_id' => $subject_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid  ])->count() ;
            
            $retrieve_tt = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'start_time' => $st, 'week_day' => $week, 'class_id' => $class_id, 'school_id' => $compid  ])->count() ;
            
            if($retrieve_tt == 0 )
            {
                
                $tt = $timetable_table->newEntity();
                $tt->subject_id = $this->request->data('subjects');
				$tt->class_id = $this->request->data('class');
				$tt->teacher_id = $this->request->data('teacher_id');
                $tt->school_id = $compid;
                $tt->tchr_name = $this->request->data('teacher_name');
                $tt->start_time = $this->request->data('event_start_time');
                $tt->week_day = $this->request->data('weekday');
                $tt->created_date = time();
                $tt->session_id = $this->Cookie->read('sessionid');
                $tid = $this->request->data('teacher_id');
                
                $retrieve_tym = $timetable_table->find()->where(['session_id' => $session_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid ])->toArray() ;
                $retrieve_tymtbl = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid ])->count() ;
                if($retrieve_tymtbl != 0)
                {
                    $retrieve_tym = $timetable_table->find()->where(['session_id' => $session_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid, 'start_time' => $this->request->data('event_start_time') ])->toArray() ;
                    if(!empty($retrieve_tym))
                    {
                        $res = [ 'result' => 'Time Already selected.'  ];
                    }
                    else
                    {
                        if(!empty($tid))                 
                        {
                            if($saved = $timetable_table->save($tt) )
                            {   
                                $subid = $saved->id;
                                $activity = $activ_table->newEntity();
                                $activity->action =  "timetable Added"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($subid); 
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
                            $res = [ 'result' => 'Teacher for this subject is not allocated. Please add teacher first'  ];
                        }
                    }
                }
                else
                {
                    /*$strt = explode(":", $st);
                    $end = explode(":", $et);*/
                    if(!empty($tid))                 
                    {
                        /*if(($strt[0] < $end[0]) || (($strt[0] == $end[0]) && ($strt[1] < $end[1])))
                        {*/
                            if($saved = $timetable_table->save($tt) )
                            {   
                                $subid = $saved->id;
                                $activity = $activ_table->newEntity();
                                $activity->action =  "timetable Added"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($subid); 
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
                             $res = [ 'result' => 'Start time is not less than End time'  ];
                        }*/
                    }
                    else
                    {
                        $res = [ 'result' => 'Teacher for this subject is not allocated. Please add teacher first'  ];
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
            $res = [ 'result' => 'invalid operation'  ];

        }

        return $this->json($res);
    }
    
    public function delete()
    {
        $sid = (int) $this->request->data('val') ; 
        $time_table = TableRegistry::get('time_table');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $eaid = $time_table->find()->select(['id'])->where(['id'=> $sid ])->first();
		
        if($eaid)
        {   
            $del = $time_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
            if($del)
            { 
                $activity = $activ_table->newEntity();
                $activity->action =  "Time Table Data Deleted"  ;
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
    
    public function edit()
    {
        $id = $this->request->data('id');
        $time_table = TableRegistry::get('time_table');
        $retrieve_timetable = $time_table->find()->where(['id' => $id])->first() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $clsid = $retrieve_timetable['class_id'];
		$session_id = $this->Cookie->read('sessionid');
		$schoolid = $this->request->session()->read('company_id');
        $subject_table = TableRegistry::get('subjects');
		$classsub_table = TableRegistry::get('class_subjects');
        $get_subjects = $classsub_table->find()->where(['class_id' => $clsid, 'school_id' => $schoolid])->first();
        
        $class_table = TableRegistry::get('class');
        $get_class = $class_table->find()->where(['id' => $clsid])->first();
        $class_sectnname = strtolower($get_class['school_sections']);
            
        $company_table = TableRegistry::get('company');
        $get_schoolinfo = $company_table->find()->where(['id' => $schoolid])->first();
        $sclinfo = [];
            if($class_sectnname == "creche" || $class_sectnname == "maternelle")
            {
                $bs = explode(",",$get_schoolinfo['kinder_breakstrt']);
                $bstrt = [];
                foreach($bs as $val)
                {
                    $bstrt[] = $val.":00";
                }
                
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['kinderscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['kinderscl_endtimings']));
                $sclinfo['school_slot'] = $get_schoolinfo['kinder_periodslot'];
                $sclinfo['school_breakname'] = $get_schoolinfo['kinder_breakname'];
                $sclinfo['school_breakstrt'] = implode(",", $bstrt);
                $sclinfo['school_breakend'] = $get_schoolinfo['kinder_breakend'];
                $sclinfo['strt_timings'] = date("H:i:s", strtotime($get_schoolinfo['kinderscl_strttimings']));
                $sclinfo['end_timings'] =  date("H:i:s", strtotime($get_schoolinfo['kinderscl_endtimings']));
                $slot = explode(" minutes", $get_schoolinfo['kinder_periodslot']);
                $sclinfo['slot'] = $slot[0];
            }
            elseif($class_sectnname == "primaire")
            {
                $bs = explode(",",$get_schoolinfo['primary_breakstrt']);
                $bstrt = [];
                foreach($bs as $val)
                {
                    $bstrt[] = $val.":00";
                }
                
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['primaryscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['primaryscl_endtimings']));
                $sclinfo['school_slot'] = $get_schoolinfo['primary_periodslot'];
                $sclinfo['school_breakname'] = $get_schoolinfo['primary_breakname'];
                $sclinfo['school_breakstrt'] = implode(",", $bstrt);
                $sclinfo['school_breakend'] = $get_schoolinfo['primary_breakend'];
                $sclinfo['strt_timings'] = date("H:i:s", strtotime($get_schoolinfo['primaryscl_strttimings']));
                $sclinfo['end_timings'] =  date("H:i:s", strtotime($get_schoolinfo['primaryscl_endtimings']));
                $slot = explode(" minutes", $get_schoolinfo['primary_periodslot']);
                $sclinfo['slot'] = $slot[0];
            }
            else
            {
                $bs = explode(",",$get_schoolinfo['senior_breakstrt']);
                $bstrt = [];
                foreach($bs as $val)
                {
                    $bstrt[] = $val.":00";
                } 
                $sclinfo['school_stym'] =  date("H:i", strtotime($get_schoolinfo['seniorscl_strttimings']));
                $sclinfo['school_etym'] =  date("H:i", strtotime($get_schoolinfo['seniorscl_endtimings']));
                $sclinfo['school_slot'] = $get_schoolinfo['senior_periodslot'];
                $sclinfo['school_breakname'] = $get_schoolinfo['senior_breakname'];
                $sclinfo['school_breakstrt'] = implode(",", $bstrt);
                $sclinfo['school_breakend'] = $get_schoolinfo['senior_breakend'];
                $sclinfo['strt_timings'] = date("H:i:s", strtotime($get_schoolinfo['seniorscl_strttimings']));
                $sclinfo['end_timings'] =  date("H:i:s", strtotime($get_schoolinfo['seniorscl_endtimings']));
                $slot = explode(" minutes", $get_schoolinfo['senior_periodslot']);
                $sclinfo['slot'] = $slot[0];
            }
        
        $html_content='';
        if($get_subjects->subject_id !=''){
            $subjects = explode(',',$get_subjects->subject_id);
            
            $html_content .= '<option value="">Choose Subjects</option>';
            foreach($subjects as $subject){
                $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                if($subjects_data->id == $retrieve_timetable['subject_id'])
                {
                    $sel = "selected";
                }
                else
                {
                    $sel = "";
                }
                $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'" >'.$subjects_data->subject_name.'</option>';
            }
        }
		$data['getdata'] = $retrieve_timetable;
    	$data['getsubjcts'] = $html_content;
    	$data['sclinfo'] = $sclinfo;
		return $this->json($data);
    }
    
    public function edittimetbl()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $timetable_table = TableRegistry::get('time_table');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $session_id = $this->Cookie->read('sessionid');
            $ttid = $this->request->data('edittid');
            $subject_id = $this->request->data('subjects');
			$class_id = $this->request->data('class');
			$week = $this->request->data('weekday');
			$st = $this->request->data('event_start_time');
            $retrieve_tt = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'start_time' => $st, 'week_day' => $week, 'school_id' => $compid, 'id !=' => $ttid  ])->count() ;
            
            if($retrieve_tt == 0 )
            {
                $subject_id = $this->request->data('subjects');
				$class_id = $this->request->data('class');
				$teacher_id = $this->request->data('teacher_id');
                $school_id = $compid;
                $tchr_name = $this->request->data('teacher_name');
                $start_time = $this->request->data('event_start_time');
                $week_day = $this->request->data('weekday');
                $created_date = time();
                $session_id = $this->Cookie->read('sessionid');
                $tid = $this->request->data('teacher_id');
                
                $retrieve_tym = $timetable_table->find()->where(['session_id' => $session_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid, 'id != ' =>$ttid ])->toArray() ;
                $retrieve_tymtbl = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid, 'id != ' =>$ttid ])->count() ;
                if($retrieve_tymtbl != 0)
                {
                    $retrieve_tym = $timetable_table->find()->where(['session_id' => $session_id,  'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid, 'id != ' =>$ttid, 'start_time' => $start_time])->toArray() ;
                
                    if(!empty($retrieve_tym))
                    {
                        $res = [ 'result' => 'Time Already selected.'  ];
                    }
                    else
                    {
                        if(!empty($tid))                 
                        {
                            if($update = $timetable_table->query()->update()->set(['class_id' => $class_id, 'school_id' => $school_id,  'week_day' => $week_day, 'tchr_name' => $tchr_name, 'start_time' => $start_time, 'subject_id' => $subject_id, 'teacher_id' => $teacher_id])->where([ 'id' => $ttid ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "timetable Added"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($ttid); 
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
                            $res = [ 'result' => 'Teacher for this subject is not allocated. Please add teacher first'  ];
                        }
                    }
                }
                else
                {
                    if(!empty($tid))                 
                    {
                        if($update = $timetable_table->query()->update()->set(['class_id' => $class_id, 'school_id' => $school_id, 'week_day' => $week_day, 'tchr_name' => $tchr_name, 'start_time' => $start_time, 'subject_id' => $subject_id, 'teacher_id' => $teacher_id])->where([ 'id' => $ttid ])->execute())
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "timetable Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($ttid); 
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
                        $res = [ 'result' => 'Teacher for this subject is not allocated. Please add teacher first'  ];
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
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }
}

  


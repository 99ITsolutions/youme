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
        
		$session_id = $this->Cookie->read('sessionid');
	    $sclid = $this->request->session()->read('company_id');
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
    		if(empty($retrieve_timetable))
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
		    $classid = '';
		    $session_id = '';
		    $retrieve_timetable = '';
		    $error = '';
		    $this->set("classid", $classid);
		    $this->set("sessionid", $session_id);
		}
		
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
            $id = $this->request->data('classId');
            $subid = $this->request->data('subId');
            $schoolid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $separator = "subjects";
            $classsub_table = TableRegistry::get('class_subjects');
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
            $retrieve_tt = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'subject_id' => $subject_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid  ])->count() ;
            if($st == $bt)
            {
                 $res = [ 'result' => 'Selected Time alloted for break.'  ];
            }
            else
            {
                if($retrieve_tt == 0 )
                {
                    
                    $tt = $timetable_table->newEntity();
                    $tt->subject_id = $this->request->data('subjects');
    				$tt->class_id = $this->request->data('class');
    				$tt->teacher_id = $this->request->data('teacher_id');
                    $tt->school_id = $compid;
                    $tt->tchr_name = $this->request->data('teacher_name');
                    $tt->start_time = $this->request->data('event_start_time');
                    //$tt->end_time = $this->request->data('event_end_time');
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
            $bt = $this->request->data('breaktime');
            $retrieve_tt = $timetable_table->find()->select(['id' ])->where(['session_id' => $session_id, 'subject_id' => $subject_id, 'class_id' => $class_id, 'week_day' => $week, 'school_id' => $compid, 'id !=' => $ttid  ])->count() ;
            if($st == $bt)
            {
                 $res = [ 'result' => 'Selected Time alloted for break.'  ];
            }
            else
            {
                if($retrieve_tt == 0 )
                {
                    
                    //$tt = $timetable_table->newEntity();
                    $subject_id = $this->request->data('subjects');
    				$class_id = $this->request->data('class');
    				$teacher_id = $this->request->data('teacher_id');
                    $school_id = $compid;
                    $tchr_name = $this->request->data('teacher_name');
                    $start_time = $this->request->data('event_start_time');
                    //$end_time = $this->request->data('event_end_time');
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
                            /*$strt = explode(":", $st);
                            $end = explode(":", $et);*/
                            if(!empty($tid))                 
                            {
                                /*if(($strt[0] < $end[0]) || (($strt[0] == $end[0]) && ($strt[1] < $end[1])))
                                {*/
                                    if($update = $timetable_table->query()->update()->set(['class_id' => $class_id, 'school_id' => $school_id,  'week_day' => $week_day, 'tchr_name' => $tchr_name, 'start_time' => $start_time, 'subject_id' => $subject_id, 'teacher_id' => $teacher_id])->where([ 'id' => $ttid ])->execute())
                                    {   
                                        //$subid = $saved->id;
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
                        /*$strt = explode(":", $st);
                        $end = explode(":", $et);*/
                        if(!empty($tid))                 
                        {
                            /*if(($strt[0] < $end[0]) || (($strt[0] == $end[0]) && ($strt[1] < $end[1])))
                            {*/
                                if($update = $timetable_table->query()->update()->set(['class_id' => $class_id, 'school_id' => $school_id, 'week_day' => $week_day, 'tchr_name' => $tchr_name, 'start_time' => $start_time, 'subject_id' => $subject_id, 'teacher_id' => $teacher_id])->where([ 'id' => $ttid ])->execute())
                                {   
                                    //$subid = $saved->id;
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
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }

        return $this->json($res);
    }
}

  


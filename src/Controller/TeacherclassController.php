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
 * @link      https://cakephp.org CakePHP(tm) Projectr
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
class TeacherclassController  extends AppController
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
                $classteacher_table =  TableRegistry::get('classteacher');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid');

                if(!empty($tid))
                {
    				$retrieve_class = $classteacher_table->find()->select(['class.c_name'  , 'class.id'  , 'class.c_section', 'class.school_sections'])->join([
                    'class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = classteacher.classid
'
                    ]
                ])->where(['md5(classteacher.teacherid)' => $tid  ])->toArray();

                    $this->set("classes_details", $retrieve_class);     
                    $this->viewBuilder()->setLayout('user'); 
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
		
            public function classall()
            {
               	$tid = $this->Cookie->read('tid');
               	if(!empty($tid))
               	{
               	    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
    				$classsub = $this->request->query('classid');
    				$sessionid = $this->Cookie->read('sessionid');
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['class' => $classsub, 'session_id' => $sessionid, 'status' => 1 ])->toArray();
    				$this->set("classes_students", $retrieve_stdnt);   
                    $this->set("classes_name", $retrieve_class);     
                    $this->viewBuilder()->setLayout('user');    
               	}
               	else
               	{
               	    return $this->redirect('/login/') ;   
               	}
            }
            
            public function savemaxmarks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $reportmm_list = TableRegistry::get('reportmax_marks');
                $activ_table = TableRegistry::get('activity');
                 
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$mrk = $this->request->data('mrk');
    			$reqid = $this->request->data('reqid');
    			//print_r($reqid);exit;
    			$updatests = $reportmm_list->query()->update()->set(['max_marks' => $mrk, 'tchr_updatemarks_sts' => 1 ])->where(['id' => $reqid ])->execute();
                
                if($updatests)
                {   
                    $res = [ 'result' => 'updated'  ];
                }
                else
                { 
                    $res = [ 'result' => 'activity'  ];
                }
    			return $this->json($res);
            }
            
            public function savemaxmarksmul()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $reportmm_list = TableRegistry::get('reportmax_marks');
                $activ_table = TableRegistry::get('activity');
                 
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$mrk = $this->request->data('mrk');
    			$reqids = json_decode($this->request->data('reqid'));
    			//print_r(json_decode($reqid));exit;
    			foreach($reqids as $reqid){
        			$updatests = $reportmm_list->query()->update()->set(['max_marks' => $mrk, 'tchr_updatemarks_sts' => 1 ])->where(['id' => $reqid ])->execute();
                    
                    if($updatests)
                    {   
                        $res = [ 'result' => 'updated'  ];
                    }
                    else
                    { 
                        $res = [ 'result' => 'activity'  ];
                    }
    			}
    			return $this->json($res);
            }
            
            public function reportmarks()
            {
                
                //print_r($_POST);
                $report_list = TableRegistry::get('subject_report_recorder');
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$stid = 'maxmarks';
    			$marks = $this->request->data('marks');
    			$subid = $this->request->data('subid');
    			$clsid = $this->request->data('clsid');
    			$subname = $this->request->data('subname');
    			$semname = $this->request->data('semname');
    			$perdname = '';
    			if(!empty($this->request->data('perdname')))
    			{
    			    $perdname = $this->request->data('perdname');
    			}
    			if($perdname != "")
    			{
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'period_name' => $perdname ])->first();
    			}
    			else
    			{
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'semester_name' => $semname])->first();
    			}
    			
    			//print_r($retrieve_rprtmrks);
    			if(empty($retrieve_rprtmrks))
    			{
    			    $rpmrks = $report_list->newEntity();
                    $rpmrks->class_id = $clsid;
                    $rpmrks->student_id = $stid;
                    $rpmrks->max_marks = $marks;
                    $rpmrks->subject_id = $subid;
                    $rpmrks->subject_name = $subname;
                    $rpmrks->period_name = $perdname;
                    $rpmrks->semester_name = $semname;
                    $rpmrks->created_date = time();
    				$rpmrks->status = 0;
    				$rpmrks->session_id = $sessionid;
                    $rpmrks->school_id = $compid;
                    
                    //print_r($rpmrks); 
                    
                    if($saved = $report_list->save($rpmrks) )
                    {   
                        $res = [ 'result' => 'success'  ];
                    }
    			}
    			else
    			{
    			    $id = $retrieve_rprtmrks['id'];
    			    $report_list->query()->update()->set(['max_marks' => $marks ])->where(['id' => $id])->execute();
    			    $res = [ 'result' => 'success'  ];
            
    			}
    			
    			return $this->json($res);
            }
            
            public function reportmarks1()
            {
                //print_r($_POST);
                $report_list = TableRegistry::get('subject_report_recorder');
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$stid = $this->request->data('stid');
    			$marks = $this->request->data('marks');
    			$subid = $this->request->data('subid');
    			$clsid = $this->request->data('clsid');
    			$subname = $this->request->data('subname');
    			$semname = $this->request->data('semname');
    			$perdname = '';
    			if(!empty($this->request->data('perdname')))
    			{
    			    $perdname = $this->request->data('perdname');
    			}
    			if($perdname != "")
    			{
    			    //echo "hi";
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'semester_name' => $semname, 'period_name' => $perdname ])->first();
    			}
    			else
    			{
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'semester_name' => $semname])->first();
    			}
    			
    			//print_r($_POST); die;
    			
    			if(empty($retrieve_rprtmrks))
    			{
    			    $rpmrks = $report_list->newEntity();
                    $rpmrks->class_id = $clsid;
                    $rpmrks->student_id = $stid;
                    $rpmrks->subject_id = $subid;
                    $rpmrks->max_marks = $marks;
                    $rpmrks->subject_name = $subname;
                    $rpmrks->period_name = $perdname;
                    $rpmrks->semester_name = $semname;
                    $rpmrks->created_date = time();
    				$rpmrks->status = 0;
    				$rpmrks->session_id = $sessionid;
                    $rpmrks->school_id = $compid;
                    
                    //print_r($rpmrks); 
                    
                    if($saved = $report_list->save($rpmrks) )
                    {   
                        $res = [ 'result' => 'success'  ];
                    }
    			}
    			else
    			{
    			    $id = $retrieve_rprtmrks['id'];
    			    $report_list->query()->update()->set(['max_marks' => $marks ])->where(['id' => $id])->execute();
    			    $res = [ 'result' => 'success'  ];
    			}
    			
    			return $this->json($res);
            }
            
            public function reportmaxrequest()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $reportmm_list = TableRegistry::get('reportmax_marks');
                    $activ_table = TableRegistry::get('activity');
                    $employee_table = TableRegistry::get('employee');
                    $notify_table = TableRegistry::get('notification'); 
                    $class_table = TableRegistry::get('class');  
                    $subject_table = TableRegistry::get('subjects');
                    
        			$sessionid = $this->Cookie->read('sessionid');
        			$compid = $this->request->session()->read('company_id');
        			$tchrid = $this->request->session()->read('tchr_id');
        			$subid = $this->request->data('subid');
        			$clsid = $this->request->data('clsid');
        			$trimestre_arr = $this->request->data('trimestre');
        			$periode_arr = $this->request->data('periode');
        			
        			$retrieve_emp = $employee_table->find()->where(['id' => $tchrid ])->first() ;
                    $tchrnam = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
                    
                    $retrieve_cls = $class_table->find()->where(['id' => $clsid ])->first() ;
                    $clsnam = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']. " (".$retrieve_cls['school_sections'].")";
                    
                    $retrieve_sub = $subject_table->find()->where(['id' => $subid ])->first() ;
                    $subjnam = $retrieve_sub['subject_name'];
                    
        			//
        			 foreach($periode_arr as $trim => $periodear){
        			    $trimestre = $trimestre_arr[$trim];
            		$i=0;	foreach($periodear as $periode){
                			$retrieve_reportmm = $reportmm_list->find()->select(['id', 'request_status' ])->where(['subject_id' => $subid, 'class_id' => $clsid, 'teacher_id' => $tchrid, 'school_id' => $compid, 'semester' => $trimestre, 'periode' => $periode, 'session_id' => $sessionid  ])->first() ;
            
                            if(empty($retrieve_reportmm))
                            {
                                $reportmm = $reportmm_list->newEntity();
                                $reportmm->school_id = $compid;
                                $reportmm->session_id = $sessionid;
                                $reportmm->teacher_id = $tchrid;
                                $reportmm->subject_id = $subid;
                                $reportmm->class_id = $clsid;
        						$reportmm->request_status = 0;
        						$reportmm->tchr_updatemarks_sts = 0;
                                $reportmm->semester = $trimestre;
                                $reportmm->periode = $periode;
                                $reportmm->created_date = time();
                                                      
                                $saved = $reportmm_list->save($reportmm);   
                                $saveid = $saved->id;
                                
                                $title = "Edit Request for Subrecorder max marks of class ".$clsnam." (Subject: ".$subjnam.")";
                            } 
                            else
                            {
                                if($retrieve_reportmm['request_status'] == 1)
                                {
                                    $saved = $reportmm_list->query()->update()->set(['request_status' => 0, 'tchr_updatemarks_sts' => 0 ])->where(['id' => $retrieve_reportmm['id'] ])->execute();
                                
                                    $title = "Again Edit Request for Subrecorder max marks of class ".$clsnam." (Subject: ".$subjnam.")";
                                }
                                else
                                {
                                    $title = "Reminder Edit Request for Subrecorder max marks of class ".$clsnam." (Subject: ".$subjnam.")";
                                }
                            }   
                            $desc = "<p>Hello Management,</p>
                            <p>This is to inform you that <b>Teacher ".$tchrnam."</b> of <b>Class ".$clsnam." and subject ".$subjnam."</b> has requested to edit max marks in subject recorder. </p>
                            <p>Trimestre: ".$trimestre."</p>
                            <p>Periode: ".$periode."</p>
                            </p>Thanks.</p>";
                            date_default_timezone_set('Africa/Kinshasa');
                            $notify = $notify_table->newEntity();
                            $notify->title = $title;
                            $notify->description = $desc;
                            $notify->notify_to = 'schools';
                            $notify->created_date =  time();
                            $notify->added_by = 'teachers';
                            $notify->teacher_id = $tchrid;
                            $notify->status = '1';
                            $notify->school_ids = $compid;
                            $scdate = date("d-m-Y H:i",time());
                            $notify->schedule_date = $scdate;
                            $notify->sent_notify = '1';
                            $notify->sc_date_time = time();
                            
                            $saved = $notify_table->save($notify);
                            
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Report max marks edit request Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('tid');
                            $activity->value = md5($saveid); 
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
                            $i++;
        			    }
        			}
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
    			return $this->json($res);
            }
            
            public function subrecoder()
            {
                //print_r($_POST);
                $report_list = TableRegistry::get('subject_report_recorder');
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$subid = $this->request->data('subjectid');
    			$clsid = $this->request->data('classid');
    		
    			$updatests = $report_list->query()->update()->set(['status' => 1 ])->where(['class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid])->execute();
    			
    			
    			if($updatests)
    			{
    			    $res =  [ 'result' => 'success'  ];
    			}
    			return $this->json($res);
            }
            
            public function classallSubjects()
            {
                //print_r($_SESSION); die;
               	$tid = $this->Cookie->read('tid');
               	$sclid = $this->request->session()->read('company_id');
               	if(!empty($tid))
               	{
               	    $subject_name = TableRegistry::get('subjects');
                    $student_table = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $submit_exams_table = TableRegistry::get('submit_exams');
                    $report_list = TableRegistry::get('subject_report_recorder');
                    $reportmm_list = TableRegistry::get('reportmax_marks');
                    
               	    $sid = $this->request->query('subid');
                    $gid = $this->request->query('classid');
               	    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_class = $class_list->find()->where(['id' => $gid ])->first();
                    $session_id = $this->Cookie->read('sessionid');
                    
                    $retrieve_reportmm = $reportmm_list->find()->where(['subject_id' => $sid, 'class_id' => $gid, 'school_id' => $sclid, 'session_id' => $session_id ])->toArray() ;
                    //print_r($retrieve_reportmm); die;
                    $this->set("reportmm", $retrieve_reportmm);    
                    
                    $subject_names = $subject_name->find()->where(['id' => $sid ])->first();
                    
                    $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
                    $sclsctn = strtolower($retrieve_class['school_sections']);
                    $studentdetails = $student_table->find()->select(['id' ,'f_name' , 'l_name' ])->where(['class' => $gid, 'session_id' => $session_id])->toArray() ;
                    
                    
                    if($sclsctn == "maternelle" || $sclsctn == "creche")
                    {
                        $assesment = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_type' => "Premier Trimestre" ])->toArray() ;
                        $assesment2 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_type' => "Deuxieme Trimestre" ])->toArray() ;
                        $assesment3 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_type' => "Troisieme Trimestre" ])->toArray() ;
                        $exams1 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Premier Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        $exams2 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Deuxieme Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        $exams3 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Troisieme Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        
                        $this->set(compact('studentdetails','assesment','assesment2','assesment3','retrieve_class','subject_names', 'exams1', 'exams2', 'exams3')); 
                    }
                    elseif($sclsctn == "primaire")
                    {
                        $assesment = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "1ère Periode" ])->toArray() ;
                        $assesment2 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "2ème Periode" ])->toArray() ;
                        $assesment3 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "3ème Periode" ])->toArray() ;
                        $assesment4 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "4ème Periode" ])->toArray() ;
                        $assesment5 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "5ème Periode" ])->toArray() ;
                        $assesment6 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "6ème Periode" ])->toArray() ;
                        $exams1 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Premier Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        //print_r($assesment);exit;
                        $exams2 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Deuxieme Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        $exams3 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Troisieme Trimestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        
                        $this->set(compact('studentdetails','assesment','assesment2','assesment3','assesment4', 'assesment5', 'assesment6', 'retrieve_class', 'subject_names', 'exams1', 'exams2', 'exams3'));
                    }
                    else
                    {
                        $assesment = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "1ère Periode" ])->toArray() ;
                        $assesment2 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "2ème Periode" ])->toArray() ;
                        $assesment3 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "3ème Periode" ])->toArray() ;
                        $assesment4 = $exams_assessment_table->find()->where(['type !=' => 'Exams', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id,'exam_period' => "4ème Periode" ])->toArray() ;
                        $exams1 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Premier Semestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        $exams2 = $exams_assessment_table->find()->where(['type' => 'Exams', 'exam_type' => 'Second Semestre', 'class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id, 'exam_period IS' => NULL ])->toArray() ;
                        
                        //print_r($exams1); die;
                        $this->set(compact('studentdetails','assesment','assesment2','assesment3','assesment4','retrieve_class','subject_names', 'exams1', 'exams2'));
                    }
                    
                    $marksrecord = $report_list->find()->where(['class_id' => $gid, 'subject_id' => $sid, 'school_id' => $sclid, 'session_id' => $session_id])->toArray();
                    
                    $this->set("marksrecord", $marksrecord);    
                    $this->set("classes_name", $retrieve_class);     
                    $this->viewBuilder()->setLayout('user');   
               	    
               	}
               	else
               	{
               	    return $this->redirect('/login/') ;   
               	}
            }
            
            public function excutepublist()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $report_table = TableRegistry::get('reportcard');
                $reportcard = $report_table->newEntity();
                $class_list = TableRegistry::get('class');
                $notification_table = TableRegistry::get('notification');
                $notification = $notification_table->newEntity();     
                $teacher->classid = $this->request->data('classids');
                $report_table->query()->update()->set(['status' => '2' ])->where([ 'classids' => $this->request->data('classids')])->execute();
                $retrieve_classname = $class_list->find()->where(['id' => $this->request->data('classids')])->first();
                $classnames = $retrieve_classname['c_name']." ".$retrieve_classname['c_section']." (".$retrieve_classname['school_sections'].")";
                $notification->title = $classnames. " Report cards are ready to publish.";
                $notification->notify_to = "schools";
                $notification->description =  $classnames. " Report cards are ready to publish.";
                $notification->status = 1;
                $notification->sent_notify = 0;
                $notification->added_by = "teachers";
                $notification->created_date = time();
                $notification->schedule_date = date("d-m-Y h:i A", time());
                $notification->sc_date_time = time();
                $notification->school_ids = $_SESSION['company_id'];         
                $notification->teacher_id = $_SESSION['tchr_id'];             
                $saved = $notification_table->save($notification) ;
                $res = ['result' => 'success'];
                return $this->json($res);
            }

            public function excutenotfisubteacher()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $teacher_table = TableRegistry::get('employee_class_subjects');
                $class_list = TableRegistry::get('class');
                $teacherids = $teacher_table->newEntity();
                $notification_table = TableRegistry::get('notification');
                $notification = $notification_table->newEntity();
                $teacher->classid = $this->request->data('classids');
                $retrieve_classname = $class_list->find()->where(['id' => $this->request->data('classids')])->first();
                $classnames = $retrieve_classname['c_name']." ".$retrieve_classname['c_section'];
                $retrieve_teachersids = $teacher_table->find()->select(['emp_id'])->where(['class_id' => $this->request->data('classids')])->group(['emp_id'])->toArray();
                $subjectteacherids = ""; 
                foreach ($retrieve_teachersids as $value) {
                    $subjectteacherids .= $value['emp_id'].",";
                }
                $lang = $this->Cookie->read('language');	
    			if($lang != "") { $lang = $lang; }
                else { $lang = 2; }
            
            
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
                    if($langlbl['id'] == '1943') { $repcrdinit = $langlbl['title'] ; } 
                } 
                $notification->title = $classnames. " ".$repcrdinit;
                $notification->notify_to = "teachers";
                $notification->description = $classnames. " ".$repcrdinit;
                $notification->status = 1;
                $notification->sent_notify = 0;
                $notification->added_by = "teachers";
                $notification->created_date = time();
                $notification->schedule_date = date("d-m-Y h:i A", time());
                $notification->sc_date_time = time();
                $notification->teacher_id = $_SESSION['tchr_id'];
                $notification->teacher_ids = $subjectteacherids;                      
                $saved = $notification_table->save($notification) ;
                $res = ['result' => 'success'];
                return $this->json($res);
            }

            public function excutenotficlsteacher()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $teacher_table = TableRegistry::get('employee');
                $class_list = TableRegistry::get('class');
                $classtec_id = TableRegistry::get('classteacher');
                $teacherids = $teacher_table->newEntity();
                $notification_table = TableRegistry::get('notification');
                $notification = $notification_table->newEntity();
                $teacher->classid = $this->request->data('classids');
                $retrieve_classname = $class_list->find()->where(['id' => $this->request->data('classids')])->first();
                $classnames = $retrieve_classname['c_name']." ".$retrieve_classname['c_section'];
                $retrieve_teachersids = $teacher_table->find()->where(['id' => $_SESSION['tchr_id']])->first();
                $retrieve_classtecid = $classtec_id->find()->where(['classid' => $this->request->data('classids')])->first();
                $subjecttec = $retrieve_teachersids['f_name']." ".$retrieve_teachersids['l_name'];
                $notification->title = $subjecttec. " updated their Suject marks. Please check on student report card";
                $notification->notify_to = "teachers";
                $notification->description = $subjecttec. " updated their Suject marks. Please check on student report card";
                $notification->status = 1;
                $notification->sent_notify = 0;
                $notification->added_by = "teachers";
                $notification->created_date = time();
                $notification->schedule_date = date("d-m-Y h:i A", time());
                $notification->sc_date_time = time();
                $notification->teacher_id = $_SESSION['tchr_id'];
                $notification->teacher_ids = $retrieve_classtecid['teacherid'];                      
                $saved = $notification_table->save($notification) ;
                $res = ['result' => 'success'];
                return $this->json($res);
            }
            public function getclassreport()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                        return $this->redirect('/teacherclass/editreport?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                       return $this->redirect('/teacherclass/fifthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                       return $this->redirect('/teacherclass/seventhclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/fourthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/secondclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                     elseif($clss == "3ème-Maternelle")
                    {
                         return $this->redirect('/teacherclass/threeema?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                         return $this->redirect('/teacherclass/firsterec?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/firstereannee?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/twoerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Primaire")
                    {
                         return $this->redirect('/teacherclass/firstclasspremire?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème-Maternelle")
                    {
                         return $this->redirect('/teacherclass/twoemematernel?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/thiredclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "6ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/sixthclass?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/threeemezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/twomezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/fouremezestion?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère-Creche")
                    {
                         return $this->redirect('/teacherclass/oneèrecreche?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/oneerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/fouremeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/threeemeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/firsterehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/twoemeanneehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/firstpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/secondpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/thiredpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/teacherclass/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/teacherclass/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                        return $this->redirect('/teacherclass/chime?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/teacherclass/threehumanitiemath?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/teacherclass/fourememath?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/teacherclass/fouremebilogie?classid='.$classsub.'&studentid='.$studentsub);
                    }
                     elseif($clss == "3ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/threeanneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                    elseif($clss == "4ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
                    }
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
 
            public function editreport()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                  
                //   done   
              
              
               
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
                   $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                   $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                   $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                   $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                   
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

            public function fifthclass()
            {
                
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                  
                    
                    // dd($studentsub);
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
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
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
$twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
$twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
$twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
$twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
$twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
$twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
$twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                   
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
             public function firstclass()
            {
                 $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    // dd($stydent_name);
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
              public function secondaryChild()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    // dd($stydent_name);
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
            public function kindergardan()
            {
                $this->viewBuilder()->setLayout('user');    
            }
            public function primary()
            {
                $this->viewBuilder()->setLayout('user');    
            }
           
            public function seventhclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
            
            public function fourthclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'GRAM. - CONJ. - ANALYSE'])->toArray();
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
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sport'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
//$twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
//$twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
//$twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                   
                    // dd($third_period);
                 
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
            
            public function secondclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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

                    // dd($second_period);
                 
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
            
            public function firsterec ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
            
              public function threeema ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    
                      $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
                    
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();

                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
                   
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                   
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                   
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                   
                   
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                   
                   
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                  
                  
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
                    
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
                 
                    // dd($second_period);
                 
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
            
            public function firstereannee ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Droit'])->toArray();
            $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->toArray();
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->toArray();
            $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
            $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
            $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();

               
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
         
            public function firstclasspremire ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                   
                    // dd($second_period);
                 
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
            
            
                public function twoemematernel ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    
                      $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
                    
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();

                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
                   
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                   
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                   
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                   
                   
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                   
                   
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                  
                  
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
                    
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
                 
                    // dd($second_period);
                 
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
            
            public function thiredclass()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    $rmm_table = TableRegistry::get('reportmax_marks');
                    $mxmrks1 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $mxmrks2 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Grammaire & Conj.'])->toArray();
                    $mxmrks3 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Orth. & Rédaction'])->toArray();
                    $mxmrks4 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
                    $mxmrks5 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
                    $mxmrks6 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $mxmrks7 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Langues Congolaises'])->toArray();
                    $mxmrks8 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Numération'])->toArray();
                    $mxmrks9 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Opérations'])->toArray();
                    $mxmrks10 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Grandeurs'])->toArray();
                    $mxmrks11 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Formes géométriques'])->toArray();

                    $mxmrks12 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Zoologie - botanique & Info.'])->toArray();
                    $mxmrks13 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Technologie'])->toArray();
                    $mxmrks14 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Problèmes'])->toArray();
                    $mxmrks15 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Educ. civ & morale'])->toArray();
                    $mxmrks16 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Ed. Santé Env.'])->toArray();
                    $mxmrks17 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Géographie'])->toArray();
                    $mxmrks18 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Histoire'])->toArray();
                    $mxmrks19 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Arts plastiques'])->toArray();
                    $mxmrks20 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Arts dramatiques'])->toArray();
                    $mxmrks21 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Ed. phys. & sports'])->toArray();
                    $mxmrks22 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $mxmrks23 = $rmm_table->find()->select(['periode', 'semester', 'max_marks'])->join(['subjects' => [ 'table' => 'subjects', 'type' => 'LEFT', 'conditions' => 'subjects.id = reportmax_marks.subject_id' ] ])->where(['reportmax_marks.class_id' => $classsub, 'subjects.subject_name'=>'Religion'])->toArray();
                    
                    $this->set("mxmrks1", $mxmrks1); 
                    $this->set("mxmrks2", $mxmrks2); 
                    $this->set("mxmrks3", $mxmrks3); 
                    $this->set("mxmrks4", $mxmrks4); 
                    $this->set("mxmrks5", $mxmrks5); 
                    $this->set("mxmrks6", $mxmrks6); 
                    $this->set("mxmrks7", $mxmrks7); 
                    $this->set("mxmrks8", $mxmrks8); 
                    $this->set("mxmrks9", $mxmrks9); 
                    $this->set("mxmrks10", $mxmrks10); 
                    $this->set("mxmrks11", $mxmrks11); 
                    $this->set("mxmrks12", $mxmrks12); 
                    $this->set("mxmrks13", $mxmrks13); 
                    $this->set("mxmrks14", $mxmrks14); 
                    $this->set("mxmrks15", $mxmrks15); 
                    $this->set("mxmrks16", $mxmrks16); 
                    $this->set("mxmrks17", $mxmrks17); 
                    $this->set("mxmrks18", $mxmrks18); 
                    $this->set("mxmrks19", $mxmrks19); 
                    $this->set("mxmrks20", $mxmrks20); 
                    $this->set("mxmrks21", $mxmrks21); 
                    $this->set("mxmrks22", $mxmrks22); 
                    $this->set("mxmrks23", $mxmrks23); 
                  
                  
                    
                    
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
                  
                    // dd($third_period);
                 
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
            
            public function sixthclass ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Langues Congolaises)'])->toArray();      
                    $this->set("first_period", $first_period); 

                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $this->set("second_period", $second_period);
                    
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $this->set("third_period", $third_period);
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $this->set("fourth_period", $fourth_period);
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sportive'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Ecrit. /Calligr.'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédaction'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Français)'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Analyse'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Récitation'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phras. Ecrite & Réda.'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orthographe'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comp. Orale & Vocab.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique / Zoologie'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mesures de grandeurs'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie / Botanique'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Techno. / Informatique (1)'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $twentyseven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé & Env.'])->toArray();
                    $twentyeight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentynine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $this->set("twentynine_period", $twentynine_period);                  
                   
                    // dd($ten_period);
                    
                    
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
                $tid = $this->Cookie->read('tid');
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
            
                 public function twomezestion ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                   
                   
                    // dd($twelve_period);
                 
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
            
             public function fouremezestion ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
            
            public function oneèrecreche ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de langage'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
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
            
             public function oneerehumanitiescientifices ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
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
            
            public function threeemeanneHumanitelitere()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
            
            public function firsterehumanitieliter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
            
            public function firstpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
            
            public function chime()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
            
            public function threehumanitiemath()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function threeanneehumanitiescientifices()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function threehumanitiebilogie()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fouranneehumanitiescientifices()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function fouremebilogie()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid');
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
                $tid = $this->Cookie->read('tid');
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
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }

            public function editreportSubject()
            {
                $tid = $this->Cookie->read('tid');
                if(!empty($tid))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $teachersubject = TableRegistry::get('employee_class_subjects');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid');       
                    $tchrid = $this->request->session()->read('tchr_id');
                    $retrieve_teachersub = $teachersubject->find()->where(['emp_id' => $tchrid, 'class_id' => $classsub])->toArray(); 
                    $subsids = [];          
                    foreach ($retrieve_teachersub as $subids) {
                        $subsids[] = $subids['subject_id'];
                     } 
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
                    $this->set("subjectsids", $subsids);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

            public function subreport()
            {
                $tid = $this->Cookie->read('tid');
                if(!empty($tid))
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
                    $myred = "/teacherclass/editreport?classid=".$classid."&studentid=".$stuid;
                    return $this->redirect($myred);
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

            public function fifthRecord()
            {
                $tid = $this->Cookie->read('tid');
                if(!empty($tid))
                {
                    $classid = $_POST['classid'];
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
                    
                    $sts = $_POST['status'] == "on" ? "0" : $_POST['status'];
                    
                    $reportadd->status = $sts;
                    
                   //print_r($reportadd); die;
                    $retrieve_student = $report_list->find()->where(['stuid' => $stuid])->count();
                    if($retrieve_student == 0){
                       $saved = $report_list->save($reportadd);
                    }else{
                        // dd($nom);
                        $sts = $_POST['status'] == "on" ? "0" : $_POST['status'];
                        
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
                           'neea' => $neea, 'lethe' => $lethe,  'eleve' => $eleve, 'nperm' => $nperm,  'falt' => $falt, 'max2' => $matricular, 'max3' => $le_date, 'max22' => $max22, 'nmax2' => $nmax2, 'nmax3' => $nmax3, 'nmax22' => $nmax22, 'signprof' => $signprof, 'status' => $sts , 'classids' => $_POST['classid']])->where([ 'stuid' => $stuid])->execute();
                    }
                    
                    
                     
                    if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                        return $this->redirect('/teacherclass/editreport?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "5ème-Primaire")
                    {
                       return $this->redirect('/teacherclass/fifthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
                    {
                       return $this->redirect('/teacherclass/seventhclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "4ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/fourthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "2ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/secondclass?classid='.$classid.'&studentid='.$stuid);
                    }
                     elseif($clss == "3ème-Maternelle")
                    {
                         return $this->redirect('/teacherclass/threeema?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "1ère-Maternelle")
                    {
                         return $this->redirect('/teacherclass/firsterec?classid='.$classid.'&studentid='.$stuid);
                    }
                     elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/firstereannee?classid='.$classid.'&studentid='.$stuid);
                    }
                     elseif($clss == "2ème Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/twoerehumanitiescientifices?classid='.$classid.'&studentid='.$stuid);
                    }
                     elseif($clss == "1ère-Primaire")
                    {
                         return $this->redirect('/teacherclass/firstclasspremire?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                      elseif($clss == "2ème-Maternelle")
                    {
                         return $this->redirect('/teacherclass/twoemematernel?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                      elseif($clss == "3ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/thiredclass?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                        elseif($clss == "6ème-Primaire")
                    {
                         return $this->redirect('/teacherclass/sixthclass?classid='.$classid.'&studentid='.$stuid);
                    }
                         elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/threeemezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                         elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/twomezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                    
                         elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
                    {
                         return $this->redirect('/teacherclass/fouremezestion?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                           elseif($clss == "1ère-Creche")
                    {
                         return $this->redirect('/teacherclass/oneèrecreche?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                           elseif($clss == "1ère Année-Humanités Scientifiques")
                    {
                         return $this->redirect('/teacherclass/oneerehumanitiescientifices?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                             elseif($clss == "4ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/fouremeanneHumanitelitere?classid='.$classid.'&studentid='.$stuid);
                    }
                     
                             elseif($clss == "3ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/threeemeanneHumanitelitere?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    
                            elseif($clss == "1ère Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/firsterehumanitieliter?classid='.$classid.'&studentid='.$stuid);
                    }
                              elseif($clss == "2ème Année-Humanité Littéraire")
                    {
                         return $this->redirect('/teacherclass/twoemeanneehumanitieliter?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "1ère Année-Humanité Pedagogie générale")
                    {
                        return $this->redirect('/teacherclass/firstpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                      
                        elseif($clss == "2ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/secondpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "3ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/thiredpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/fourthpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "4ème Année-Humanité Pedagogie générale")
                    {
                         return $this->redirect('/teacherclass/fourthpedagogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/teacherclass/chime?classid='.$classid.'&studentid='.$stuid);
                    }
                    elseif($clss == "3ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/teacherclass/threehumanitiemath?classid='.$classid.'&studentid='.$stuid);
                         
                    }
                       elseif($clss == "4ème Année-Humanité Math - Physique")
                    {
                         return $this->redirect('/teacherclass/fourememath?classid='.$classid.'&studentid='.$stuid);
                    }
                       elseif($clss == "4ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/teacherclass/fouremebilogie?classid='.$classid.'&studentid='.$stuid);
                    }
                    
                    elseif($clss == "3ème Année-Humanité Chimie - Biologie")
                    {
                         return $this->redirect('/teacherclass/threehumanitiebilogie?classid='.$classid.'&studentid='.$stuid);
                    }
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

            public function subreportsubject()
            {
                $tid = $this->Cookie->read('tid');
                if(!empty($tid))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $nid = implode(",", $_POST['nid']);
                    $province = $_POST['province'];
                    $code = implode(",", $_POST['code']);
                    $neea = $_POST['neea'];
                    $subiding = $_POST['subiding'];
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
                    $myred = "/teacherclass/editreport_subject?classid=".$classid."&studentid=".$stuid."&subid=".$subiding;
                    return $this->redirect($myred);
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
           public function reportmarksmul1()
            {
                //print_r($_POST);
                $report_list = TableRegistry::get('subject_report_recorder');
    			$sessionid = $this->Cookie->read('sessionid');
    			$compid = $this->request->session()->read('company_id');
    			$stid = $this->request->data('stid');
    			$marks = $this->request->data('marks');
    			$subid = $this->request->data('subid');
    			$clsid = $this->request->data('clsid');
    			$subname = $this->request->data('subname');
    			$semname = $this->request->data('semname');
    			$perdname = '';
    			if(!empty($this->request->data('perdname')))
    			{
    			    $perdname = $this->request->data('perdname');
    			}
    			if($perdname != "")
    			{
    			    //echo "hi";
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'semester_name' => $semname, 'period_name' => $perdname ])->first();
    			}
    			else
    			{
    			    $retrieve_rprtmrks = $report_list->find()->where(['student_id' => $stid, 'class_id' => $clsid, 'subject_id' => $subid , 'session_id' => $sessionid, 'semester_name' => $semname])->first();
    			}
    			
    			//print_r($_POST); die;
    			
    			if(empty($retrieve_rprtmrks))
    			{
    			    $rpmrks = $report_list->newEntity();
                    $rpmrks->class_id = $clsid;
                    $rpmrks->student_id = $stid;
                    $rpmrks->subject_id = $subid;
                    $rpmrks->max_marks = $marks;
                    $rpmrks->subject_name = $subname;
                    $rpmrks->period_name = $perdname;
                    $rpmrks->semester_name = $semname;
                    $rpmrks->created_date = time();
    				$rpmrks->status = 0;
    				$rpmrks->session_id = $sessionid;
                    $rpmrks->school_id = $compid;
                    
                    //print_r($rpmrks); 
                    
                    if($saved = $report_list->save($rpmrks) )
                    {   
                        $res = [ 'result' => 'success'  ];
                    }
    			}
    			else
    			{
    			    $id = $retrieve_rprtmrks['id'];
    			    $report_list->query()->update()->set(['max_marks' => $marks ])->where(['id' => $id])->execute();
    			    $res = [ 'result' => 'success'  ];
    			}
    			
    			return $this->json($res);
            }
     

}

  


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
            
            public function classallSubjects()
            {
               	$tid = $this->Cookie->read('tid');
               	if(!empty($tid))
               	{
               	    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
    				$classsub = $this->request->query('classid');
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['class' => $classsub ])->toArray();
    				$this->set("classes_students", $retrieve_stdnt);   
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
                $classnames = $retrieve_classname['c_name']." ".$retrieve_classname['c_section'];
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
            
           
     

}

  


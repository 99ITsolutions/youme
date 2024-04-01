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
class ParentreportController  extends AppController
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
        $student_table = TableRegistry::get('student');	
        $parent_table = TableRegistry::get('parent_logindetails');
        $reportcard_table = TableRegistry::get('reportcard');
        $parentid = $this->request->session()->read('parent_id');
        $session_id = $this->request->session()->read('session_id');
        if(!empty($parentid))
        {        
            
            $parent_details = $parent_table->find()->select(['student.id', 'student.adm_no', 'student.password' , 'student.session_id' ,'class.c_name', 'class.c_section', 'class.school_sections', 'student.class' ,'student.f_name' , 'student.s_f_name' ,  'student.s_m_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' ,  'company.city', 'student.school_id', 'company.primary_color', 'company.button_color' , 'company.comp_name', 'parent_logindetails.parent_email' , 'parent_logindetails.id', 'parent_logindetails.parent_password' ])->join([
               'student' => 
                [
                    'table' => 'student',
                    'type' => 'LEFT',
                    'conditions' => 'parent_logindetails.id = student.parent_id '
                ],
                'company' => 
                [
                    'table' => 'company',
                    'type' => 'LEFT',
                    'conditions' => 'company.id = student.school_id '
                ],
                'class' => 
                [
						'table' => 'class',
						'type' => 'LEFT',
						'conditions' => 'class.id = student.class'
				]
            ])->where(['parent_logindetails.id' => $parentid, 'student.session_id' => $session_id ])->toArray(); 
            
           
		    foreach($parent_details as $prnt)
		    {
		        $stuid = $prnt['student']['id'];
		        $clsid = $prnt['student']['class'];
		        
		        $report_data = $reportcard_table->find()->where([ 'stuid'=> $stuid, 'classids' => $clsid ])->first();
		        if($report_data['status'] == "2" && $report_data['publish'] == "1")
    		    {
    		        $publish_date =date('Y-m-d', $report_data['publish_date']);
                    $NewDate = strtotime($publish_date . " +7 days");
                    $now = time();
                    
                    if($NewDate >= $now )
                    {
        		        $prnt->publish = 1;
        		        if($report_data['parent_status'] == "1") {
        		            $prnt->stusts = 1;
        		        }
        		        else {
        		            $prnt->stusts = 0;
        		        }
                    }
                    else
        		    {
        		        $prnt->publish = 0;
        		        $prnt->stusts = 0;
        		    }
    		    }
    		    else
    		    {
    		        $prnt->publish = 0;
    		        $prnt->stusts = 0;
    		    }
		    }
                
            $this->set("parent_details", $parent_details); 
            $this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function submitsign()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $parntsign_table = TableRegistry::get('parentsignature_report');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
            
            $pid =$this->request->session()->read('parent_id');
            $sessionid =$this->request->session()->read('session_id');
            
            $signid = $this->request->data('signid');
            $now = time();
            if(!empty($this->request->data['signature_image']))
            {   
                $folderPath = "dairy_signature/";
                $image_parts = explode(";base64,", $_POST['signature_image']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = uniqid() . '.'.$image_type; 
                $file = $folderPath . $filename;
                
                
                file_put_contents($file, $image_base64);
            }
            
            $update = $parntsign_table->query()->update()->set([ 'signature' => $filename,  'parent_update_signature' => $now])->where([ 'id' => $signid ])->execute();
            if($update)
            {     
                $strucid = $saved->id;
                $activity = $activ_table->newEntity();
                $activity->action =  "parent e-signature uploaded for report card"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('id');
                $activity->value = md5($strucid); 
                $activity->created = strtotime('now');
                $save = $activ_table->save($activity) ;

                if($save)
                {   
                    $res = [ 'result' => 'success' ];
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
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
    
    public function returnreport()
    {
        $clsid = $this->request->query('classid'); 
        $studid = $this->request->query('studentid'); 
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        if(!empty($studid))
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
            
            $update = $reportcard->query()->update()->set([ 'parent_status'=> '1'])->where([ 'stuid' => $studid, 'classids' => $clsid ])->execute();
            
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();  
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['l_name'];
            $this->set("stydent_name", $stydent_name);  
            $this->set("city", $city);  
                   
            if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
            {
                return $this->redirect('/Parentreport/editreport?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "5ème-Primaire")
            {
                return $this->redirect('/Parentreport/fifthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
            {
                return $this->redirect('/Parentreport/seventhclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème-Primaire")
            {
                return $this->redirect('/Parentreport/fourthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème-Primaire")
            {
                return $this->redirect('/Parentreport/secondclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème-Maternelle")
            {
                return $this->redirect('/Parentreport/threeema?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Maternelle")
            {
                return $this->redirect('/Parentreport/firsterec?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Parentreport/firstereannee?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanités Scientifiques")
            {
                return $this->redirect('/Parentreport/twoerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Primaire")
            {
                return $this->redirect('/Parentreport/firstclasspremire?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème-Maternelle")
            {
                return $this->redirect('/Parentreport/twoemematernel?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème-Primaire")
            {
                return $this->redirect('/Parentreport/thiredclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "6ème-Primaire")
            {
                return $this->redirect('/Parentreport/sixthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Parentreport/threeemezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Parentreport/twomezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Parentreport/fouremezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Creche")
            {
                return $this->redirect('/Parentreport/oneèrecreche?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanités Scientifiques")
            {
                return $this->redirect('/Parentreport/oneerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Littéraire")
            {
                //echo "DScsd"; die;
                return $this->redirect('/Parentreport/fouremeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Littéraire")
            {
                return $this->redirect('/Parentreport/threeemeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Littéraire")
            {
                return $this->redirect('/Parentreport/firsterehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Littéraire")
            {
                return $this->redirect('/Parentreport/twoemeanneehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Parentreport/firstpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Parentreport/secondpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Parentreport/thiredpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Parentreport/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Chimie - Biologie")
            {
                 return $this->redirect('/Parentreport/chime?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Math - Physique")
            {
                return $this->redirect('/Parentreport/threehumanitiemath?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Math - Physique")
            {
                return $this->redirect('/Parentreport/fourememath?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Chimie - Biologie")
            {
                 return $this->redirect('/Parentreport/fouremebilogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanités Scientifiques")
            {
                 return $this->redirect('/Parentreport/threeanneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanités Scientifiques")
            {
                 return $this->redirect('/Parentreport/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function fouremeanneHumanitelitere()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }     
    }
    
    public function editreport()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }

    public function fifthclass()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function firstclass()
    {
         $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function secondaryChild()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function seventhclass()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
    
    public function fourthclass()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
    
    public function secondclass()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }

    public function firsterec()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        } 
    }
    
    public function threeema()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function firstereannee ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
 
    public function firstclasspremire ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }  
    }
    
    public function twoemematernel ()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function thiredclass ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function sixthclass ()
    {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('pid');
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
                    $this->viewBuilder()->setLayout('usersa'); 
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
    public function threeemezestion ()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }  
    }
            
    public function twomezestion ()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }    
    }
    
    public function fouremezestion ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }       
    }
    
    public function oneèrecreche ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function oneerehumanitiescientifices ()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
    
    public function twoerehumanitiescientifices ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
            public function threeemeanneHumanitelitere()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('pid');
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
                    $this->viewBuilder()->setLayout('usersa'); 
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
            public function firsterehumanitieliter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('pid');
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
                    $this->viewBuilder()->setLayout('usersa'); 
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }
            
            public function twoemeanneehumanitieliter()
            {
                 $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('pid');
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
                    $this->viewBuilder()->setLayout('usersa'); 
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }       
            }
            
    public function firstpedagogie()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }          
    }
    
    public function secondpedagogie()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
        
    }
    
    public function thiredpedagogie()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }          
    }
    
    public function fourthpedagogie()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }    
    }
    
    public function chime()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
            
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function threehumanitiemath()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function threeanneehumanitiescientifices()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function threehumanitiebilogie()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function fouranneehumanitiescientifices()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function fouremebilogie()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }     
    }  
    
    public function fourememath()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('pid');
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
            $this->viewBuilder()->setLayout('usersa'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }    
    }
            

}

  


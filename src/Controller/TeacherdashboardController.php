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
use \Imagick;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TeacherdashboardController  extends AppController
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
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid'); 
                $employee_table = TableRegistry::get('employee');
                $empclssub_table = TableRegistry::get('employee_class_subjects');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				
				$sclid = $retrieve_employees[0]['school_id'];
				//print_r($retrieve_employees); die;
				if(!empty($retrieve_employees))
				{
    				/*$retrieve_empclsses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id'])->join(
                        [
        		            'employee_class_subjects' => 
                            [
                                'table' => 'employee_class_subjects',
                                'type' => 'LEFT',
                                'conditions' => 'employee.id = employee_class_subjects.emp_id'
                            ],
                            'class' => 
                            [
                                'table' => 'class',
                                'type' => 'LEFT',
                                'conditions' => 'class.id = employee_class_subjects.class_id'
                            ]
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['class.school_sections', 'class.c_name'])->toArray();*/
                        
                    $retrieve_empclsses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id'])->join(
                        [
        		            'employee_class_subjects' => 
                            [
                                'table' => 'employee_class_subjects',
                                'type' => 'LEFT',
                                'conditions' => 'employee.id = employee_class_subjects.emp_id'
                            ],
                            'class' => 
                            [
                                'table' => 'class',
                                'type' => 'LEFT',
                                'conditions' => 'class.id = employee_class_subjects.class_id'
                            ]
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->toArray();
                    $clsses = [];    
                    $clsname = [];    
                    foreach($retrieve_empclsses as $empcls)
                    {
                        $clsname[] = $empcls['class']['c_name']."_".$empcls['class']['school_sections'];
                        $clsses[] = $empcls['class']['id'];
                    }
                    $clsnames = array_unique($clsname);
                    //print_r($clsnames); die;    
                    
                    $clsids = array_unique($clsses);
                    //print_r($clsids); die;    
                    $classid = [];
                    $classnames = [];
                    $cnames = [];
                    foreach($clsnames as $cnm)
                    {
                        //print_r($cnm); die;
                        $cls = explode("_", $cnm);
                        $retrieve_class = $class_table->find()->where([ 'c_name'=> $cls[0], 'school_sections' => $cls[1], 'school_id' => $sclid ])->first();
                        $classid[] = $retrieve_class['id'];
                        //$classnames[] = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")" ;
                        $classnames[] = $retrieve_class['c_name']." (". $retrieve_class['school_sections'].")" ;
                        $cnames[] = $retrieve_class['c_name']."_". $retrieve_class['school_sections'];
                    }
                    
                    $getcls['id'] = $classid;
                    $getcls['c_name'] = $classnames;
                    $getcls['sectns'] = $cnames;
                    
    				$retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
                        [
        		            'employee_class_subjects' => 
                            [
                                'table' => 'employee_class_subjects',
                                'type' => 'LEFT',
                                'conditions' => 'employee.id = employee_class_subjects.emp_id'
                            ],
                            'class' => 
                            [
                                'table' => 'class',
                                'type' => 'LEFT',
                                'conditions' => 'class.id = employee_class_subjects.class_id'
                            ],
                            'subjects' => 
                            [
                                'table' => 'subjects',
                                'type' => 'LEFT',
                                'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                            ],
                            
        
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->toArray();
    				
    				$notification_table = TableRegistry::get('notification');
                    $schoolid  = $retrieve_employees[0]['school_id'];
                    $tchrid  = $retrieve_employees[0]['id'];
                
                    $rcve_nottzCount = $notification_table->find()->where(['sent_notify' => 1, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'teachers']] ])->toArray() ;
                    //print_r($rcve_nottzCount) ;
                    if(!empty($rcve_nottzCount))
                    {
                        $countNotifctn = [];
                        foreach($rcve_nottzCount as $notzcount)
                        {
                            //echo $notzcount['notify_to'] ;
                            if(($notzcount['notify_to'] == "all") && ($notzcount['added_by'] == "superadmin"))
                            {
                                //echo $notzcount['id'];
                                $countNotifctn[] = 1;
                            }
                            elseif(($notzcount['notify_to'] == "all") && ($notzcount['added_by'] == "school")  && ($notzcount['school_id'] == $emp_details[0]['school_id']))
                            {
                              
                                $countNotifctn[] = 1;
                            }
                            else
                            {
                                $tchrids = explode(",", $notzcount['teacher_ids']);
                              
                                if(in_array($tchrid, $tchrids))
                                {
                                    $countNotifctn[] = 1;
                                }
                                else
                                {
                                    $countNotifctn[] = 0;
                                }
                            }
                        }
                        $countNoti = array_sum($countNotifctn);
                        
                    }
                    else
                    {
                        $countNoti = 0;
                    }
                    $this->set("total_count", $countNoti); 
    				//echo $countNoti; die;
        			foreach($retrieve_employees as $key =>$emp_coll)
            		{
            			$gradeid = explode(",",$emp_coll['grades']);
            			$subid = explode(",",$emp_coll['subjects']);
            			$i = 0;
            			$empgrades = [];
            			foreach($gradeid as $gid)
            			{
            			    $retrieve_class = $class_table->find()->where([ 'id '=> $gid ])->toArray();
            				foreach($retrieve_class as $grad)
            				{
            					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section']. "(".$grad['school_sections'].")";				
            				}
            				$i++;
            				$gradenames = implode(",", $empgrades);
            				
            			}
            			$j = 0;
            			$empsub = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subject = $subjects_table->find()->where([ 'id '=> $sid ])->toArray();
            				foreach($retrieve_subject as $subj)
            				{
            					$empsub[$j] = $subj['subject_name'];				
            				}
            				$j++;
            				$subjectnames = implode(",", $empsub);
            				
            			}
            			
            			$emp_coll->subjectName = $subjectnames;
            			
            			$emp_coll->gradesName = $gradenames;
            		}	
                    //print_r($retrieve_empclses); die;
                    $this->set("empcls_details", $retrieve_empclses); 
    				$this->set("employees_details", $retrieve_employees); 
    				$this->set("empclses_details", $retrieve_empclsses); 
    				$this->set("getcls", $getcls);
    
    				$this->viewBuilder()->setLayout('user');
				}
				else
				{
				    return $this->redirect('/login/') ;
				}
            }
	
			/*public function submitrequest()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $all_data = $this->request->data('data');
                    $examid_al_id = array();
                    foreach($all_data as $key_a=>$n_data){
                        
                        $this->request->session()->write('LAST_ACTIVE_TIME', time());
                        $exams_assessment_table = TableRegistry::get('exams_assessments');
                        $activ_table = TableRegistry::get('activity');
                        $teacherid = $this->Cookie->read('tid');
                        $employee_table = TableRegistry::get('employee');
                        $submitexams_table = TableRegistry::get('submit_exams');
                        $sessionid = $this->Cookie->read('sessionid');
                        $student_table = TableRegistry::get('student');  
                        $notify_table = TableRegistry::get('notification'); 
                        $class_table = TableRegistry::get('class');  
                        $subject_table = TableRegistry::get('subjects'); 
                        $studata = $n_data['student'];
                        //print_r($studata);exit;
                        $uniqueid = uniqid();
                        
                        $lang = $this->Cookie->read('language');	
            			if($lang != "") { $lang = $lang; }
                        else { $lang = 2; }
                    
                        $nopages = 0;
                        $dirname = "";
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
                            if($langlbl['id'] == '1611') { $labelstrtdtenddt = $langlbl['title'] ; } 
                            if($langlbl['id'] == '1944') { $maxmrksntzero = $langlbl['title'] ; } 
                            if($langlbl['id'] == '1945') { $opa = $langlbl['title'] ; } 
                            if($langlbl['id'] == '1946') { $eans = $langlbl['title'] ; } 
                        } 
                        $retrieve_langlabel1 = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                        
                        foreach($retrieve_langlabel1 as $langlbl1) { 
                            if($langlbl1['id'] == '635') { $scllbl = $langlbl1['french_label'] ; } 
                            if($langlbl1['id'] == '1798') { $quizlbl = $langlbl1['french_label'] ; } 
                            if($langlbl1['id'] == '2404') { $asslbl = $langlbl1['french_label'] ; } 
                            if($langlbl1['id'] == '1724') { $exmlbl = $langlbl1['french_label'] ; } 
                            if($langlbl1['id'] == '1799') { $studgulbl = $langlbl1['french_label'] ; } 
                            
        				}
        				 
                        if(!empty($teacherid)) 
                        {
                        $retrieve_schoolid = $employee_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $teacherid])->toArray() ;
                        
                        $school_id = $retrieve_schoolid[0]['school_id'];
                        $tid =  $retrieve_schoolid[0]['id'];
                        
                        $rqst = $this->request->data('request_for');
                        $type = $this->request->data('request_for');
                        $type1 = $type == "Assessment" ? "Assignment" : $type;
                        if( $type1 == "Exams" ) { 
                            $typelang = $exmlbl; 
                        }
                        elseif( $type1 == "Quiz" ) { 
                            $typelang = $quizlbl; 
                        }
                        elseif( $type1 == "Assignment" ) { 
                            $typelang = $asslbl; 
                        }
                        else { 
                            $typelang =  $studgulbl ;
                        } 
                        //echo $typelang; die;
                        if($rqst == "Study Guide")
                        {
                            
                                if(!empty($this->request->data['fileupload']))
                                {   
                                    if($this->request->data['fileupload']['type'] == "application/pdf"  )
                                    {
                                        $filename = time()."_".$this->request->data['fileupload']['name'];
                                        $uploadpath = 'img/';
                                        $uploadfile = $uploadpath.$filename;
                                        if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                        {
                                            $filename = $filename; 
                                        }
                                    } 
                                    else
                                    {
                                        $filename = "";
                                    }
                                }
                                if($filename != "" && $key_a == 1)
                                {
                                    $im = new Imagick();
                                    $im->pingImage('img/'.$filename);
                                    $nopages = $im->getNumberImages();
                                    $dirname = "Ebook".time();
                                }
                            
                                if(!empty($filename))
                                {
                                    $clsidss = $n_data['class'];
                                    foreach ($clsidss as $cidss)
                                    {
                                        
                                        $exams_ass = $exams_assessment_table->newEntity();
                                        
                                        $exams_ass->title =  $this->request->data('title')  ;
                                        $exams_ass->subject_id =  $n_data['subjects'];
                                        $exams_ass->start_date =  $this->request->data('start_date');
                                        $exams_ass->end_date =  $this->request->data('end_date');
                                        $exams_ass->type =  $this->request->data('request_for');
                                        $exams_ass->created_date =  time()  ;
                                        $exams_ass->file_name =  $filename ;
                                        $exams_ass->teacher_id =  $tid ;
                                        $exams_ass->school_id =  $school_id ;
                                        $exams_ass->exam_format= "study_guide";
                                        $exams_ass->session_id =  $sessionid ;
                                        $exams_ass->status =  1  ;
                                        $exams_ass->numpages = $nopages;
                                        $exams_ass->dirname = $dirname;
                                        $exams_ass->uniqid = $uniqueid;
                                        $exams_ass->class_id = $cidss;
                                        
                                        if(!empty($studata)){
                                            $studata = implode(',',$studata);
                                        }else{
                                            $studata = '';
                                        }
                                        $exams_ass->student_id =  $studata;
                                        //print_r($exams_ass);
                                        $start_date =  strtotime($this->request->data('start_date'));
                                        $end_date =  strtotime($this->request->data('end_date'));
                                        
                                        if($start_date < $end_date)
                                        {
                                            if($saved = $exams_assessment_table->save($exams_ass) )
                                            {   
                                                $examid = $saved->id;
                                                array_push($examid_al_id, $examid);
                                                if(!empty($filename) && $key_a == 1)
                                                {
                                                    mkdir($dirname);
                                                    $numpages = $nopages-1;
                        
                                                    for ($x = 0; $x <= $numpages; $x++) {
                                                        $save_to    = $dirname."/".$filename.$x.'.jpg';  
                                                        $im = new Imagick();
                                                        $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                                                        $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                                                        $im->setImageFormat('jpg');
                                                        $im = $im->flattenImages();
                                                        $im ->writeImages($save_to, true);
                                                        header('Content-Type: image/jpeg');
                                                        sleep(2);
                                                    }
                                                }
                                                
                                                $ret_sub = $subject_table->find()->where(['id'=> $n_data['subjects']])->first();
                                                $subname = $ret_sub['subject_name'];
                                                
                                                $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                $desc = "<p>Cher élève,</p>
                                                <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                <p>Merci pour votre diligence. </p>
                                                </p>Cordialement.</p>";
                                                date_default_timezone_set('Africa/Kinshasa');
                                                $notify = $notify_table->newEntity();
                                                $notify->title = $title;
                                                $notify->description = $desc;
                                                $notify->notify_to = 'students';
                                                $notify->created_date =  time();
                                                $notify->added_by = 'teachers';
                                                $notify->teacher_id = $tid;
                                                $notify->status = '1';
                                                
                                                $notify->class_ids = $cidss;
                                                $notify->class_opt = 'multiple';
                                                $scdate = date("d-m-Y H:i",time());
                                                $notify->schedule_date = $scdate;
                                                $notify->sent_notify = '1';
                                                $notify->sc_date_time = time();
                                                
                                                $saved = $notify_table->save($notify);
                                                
                                                $activity = $activ_table->newEntity();
                                                $activity->action =  "Study Guide Created"  ;
                                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            
                                                $activity->value = md5($saved->id)   ;
                                                $activity->origin = $this->Cookie->read('tid')   ;
                                                $activity->created = strtotime('now');
                                                if($saved = $activ_table->save($activity) ){
                                                    $res = [ 'result' => 'success' , 'type' => 'pdf'  ];
                        
                                                }
                                                else{
                                                    $res = [ 'result' => 'activity not saved'  ];
                                                }
                                            }
                                            else{
                                                $res = [ 'result' => $eans  ];
                                            }
                                        } 
                                        else
                                        {
                                            $res = [ 'result' => $labelstrtdtenddt  ];
                                        }
                                    }
                                }
                                else
                                { 
                                    $res = [ 'result' => $opa  ];
                                }  
                        }
                        else
                        {
                            if($this->request->data('contentupload') == "pdf")
                            {
                                if(!empty($this->request->data['fileupload']))
                                {   
                                    if($this->request->data['fileupload']['type'] == "application/pdf"  )
                                    {
                                        $filename = time()."_".$this->request->data['fileupload']['name'];
                                        $uploadpath = 'img/';
                                        $uploadfile = $uploadpath.$filename;
                                        if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                        {
                                            $filename = $filename; 
                                        }
                                    } 
                                    else
                                    {
                                        $filename = "";
                                    }
                                }
                            
                                if(!empty($filename))
                                {
                                    $clsidss = $n_data['class'];
                                    foreach ($clsidss as $cidss)
                                    {
                                        $exams_ass = $exams_assessment_table->newEntity();
                                        
                                        $exams_ass->uniqid = $uniqueid;
                                        $exams_ass->class_id = $cidss;
                                        $exams_ass->subject_id =  $n_data['subjects'] ;
                                        $exams_ass->title =  $this->request->data('title');
                                        $exams_ass->special_instruction =  $this->request->data('instruction');
                                        $exams_ass->start_date =  $this->request->data('start_date');
                                        $exams_ass->end_date =  $this->request->data('end_date');
                                        $exams_ass->type =  $this->request->data('request_for');
                                        $exams_ass->exam_type =  $this->request->data('exam_type');
                                        $exams_ass->exam_period =  $this->request->data('exam_period');
                                        $exams_ass->max_marks =  $this->request->data('max_marks');
                                        if(!empty($studata)){
                                            $studata = implode(',',$studata);
                                        }else{
                                            $studata = '';
                                        }
                                        $exams_ass->student_id =  $studata;
                                        
                                        $exams_ass->created_date =  time()  ;
                                        $exams_ass->file_name =  $filename ;
                                        $exams_ass->teacher_id =  $tid ;
                                        $exams_ass->school_id =  $school_id ;
                                        $exams_ass->exam_format= $this->request->data('contentupload');
                                        $exams_ass->session_id =  $sessionid ;
                                        $exams_ass->show_exmfrmt = "pdf";
                                        $type =  $this->request->data('request_for');
                                        if($type == "Exams")
                                        {
                                            $exams_ass->status =  0  ;
                                            $status =0;
                                        }
                                        else
                                        {
                                            $exams_ass->status =  1  ;
                                            $status = 1;
                                        }
                                        $maxmarks =  $this->request->data('max_marks');
                                        $start_date =  strtotime($this->request->data('start_date'));
                                        $end_date =  strtotime($this->request->data('end_date'));
                                        
                                        if($type == "Exams")
                                        {
                                            $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $n_data['subjects'], 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                        }
                                        else
                                        {
                                            $getexamsexist = [];
                                        }
                                        //print_r($exams_ass); die;
                                        if(empty($getexamsexist))
                                        {
                                            if($maxmarks > 0)
                                            {
                                                if($start_date < $end_date)
                                                { 
                                                    if($saved = $exams_assessment_table->save($exams_ass) )
                                                    {   
                                                        $examid = $saved->id;
                                                        array_push($examid_al_id, $examid);
                                                        if(!empty($studata) != ''){
                                                            $studata = explode(",",  $studata);
                                                            if($studata[0] != 'all'){
                                                                $retrieve_students = $student_table->find()->select(['id'])->where(['id IN' => $studata,'class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                            }else{
                                                                $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                            }
                                                            
                                                            foreach($retrieve_students as $students)
                                                            {
                                                                $stud_id = $students['id'];
                                                    		    $submitexams = $submitexams_table->newEntity();
                                                                $submitexams->exam_id = $examid;
                                                                $submitexams->student_id = $stud_id;
                                                        		$submitexams->status = 0;
                                                                $submitexams->school_id = $school_id;
                                                                $saved = $submitexams_table->save($submitexams);
                                                                
                                                            }
                                                        }
                                                        
                                                
                                                        if($status == 1)
                                                        {
                                                            $ret_sub = $subject_table->find()->where(['id'=>$n_data['subjects']])->first();
                                                            
                                                            $subname = $ret_sub['subject_name'];
                                                            $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                            $desc = "<p>Cher élève,</p>
                                                            <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                            <p>Merci pour votre diligence. </p>
                                                            </p>Cordialement.</p>";
                                                            date_default_timezone_set('Africa/Kinshasa');
                                                            $notify = $notify_table->newEntity();
                                                            $notify->title = $title;
                                                            $notify->description = $desc;
                                                            $notify->notify_to = 'students';
                                                            $notify->created_date =  time();
                                                            $notify->added_by = 'teachers';
                                                            $notify->teacher_id = $tid;
                                                            $notify->status = '1';
                                                            
                                                            $notify->class_ids = $cidss;
                                                            $notify->class_opt = 'multiple';
                                                            $scdate = date("d-m-Y H:i",time());
                                                            $notify->schedule_date = $scdate;
                                                            $notify->sent_notify = '1';
                                                            $notify->sc_date_time = time();
                                                            
                                                            $saved = $notify_table->save($notify);
                                                            
                                                            
                                                        }
                                                        
                                                        $activity = $activ_table->newEntity();
                                                        $activity->action =  "Exam Assessment Created"  ;
                                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                    
                                                        $activity->value = md5($saved->id)   ;
                                                        $activity->origin = $this->Cookie->read('tid')   ;
                                                        $activity->created = strtotime('now');
                                                        if($saved = $activ_table->save($activity) ){
                                                            $res = [ 'result' => 'success' , 'type' => 'pdf'  ];
                                
                                                        }
                                                        else{
                                                            $res = [ 'result' => 'activity not saved'  ];
                                                        }
                                                    }
                                                    else{
                                                        $res = [ 'result' => $eans  ];
                                                    }
                                                }
                                                else
                                                {
                                                    $res = [ 'result' => $labelstrtdtenddt  ];
                                                }
                                            }
                                            else
                                            {
                                                $res = ['result' => $maxmrksntzero ];
                                            }
                                        }
                                        else
                                        {
                                            $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                        }
                                    }
                                }
                                else
                                { 
                                    $res = [ 'result' => $opa  ];
                                }  
                                
                            }
                            else
                            {
                                $clsidss = $n_data['class'];
                                foreach ($clsidss as $cidss)
                                {
                                    $exams_ass = $exams_assessment_table->newEntity();
                                    $exams_ass->uniqid = $uniqueid;
                                    $exams_ass->class_id = $cidss;
                                        
                                    $exams_ass->subject_id =   $n_data['subjects'];
                                    $exams_ass->title =  $this->request->data('title');
                                    $exams_ass->special_instruction =  $this->request->data('instruction');
                                    $exams_ass->start_date =  $this->request->data('start_date');
                                    $exams_ass->end_date =  $this->request->data('end_date');
                                    $exams_ass->type =  $this->request->data('request_for');
                                    $exams_ass->exam_type =  $this->request->data('exam_type');
                                    $exams_ass->max_marks =  $this->request->data('max_marks');
                                    $exams_ass->created_date =  time()  ;
                                    $exams_ass->exam_period =  $this->request->data('exam_period');
                                    $exams_ass->teacher_id =  $tid ;
                                    $exams_ass->school_id =  $school_id ;
                                    $exams_ass->exam_format= $this->request->data('contentupload');
                                    $exams_ass->session_id =  $sessionid ;
                                    
                                    if(!empty($studata)){
                                        $studata = implode(',',$studata);
                                    }else{
                                        $studata = '';
                                    }
                                    $exams_ass->student_id =  $studata;
                                        
                                        
                                    if($this->request->data('request_for') == "Exams")
                                    {
                                        $exams_ass->show_exmfrmt = $this->request->data['exam_format'];
                                    }
                                    else
                                    {
                                        $exams_ass->show_exmfrmt = "pdf";
                                    }
                                    $type =  $this->request->data('request_for');
                                    if($type == "Exams")
                                    {
                                        $exams_ass->status =  0  ;
                                        $status = 0;
                                    }
                                    else
                                    {
                                        $exams_ass->status =  1  ;
                                        $status = 1;
                                    }
                                    $max_marks =  $this->request->data('max_marks');
                                    $start_date =  strtotime($this->request->data('start_date'));
                                    $end_date =  strtotime($this->request->data('end_date'));
                                    
                                    if($type == "Exams")
                                    {
                                        $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $n_data['subjects'], 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                    }
                                    else
                                    {
                                        $getexamsexist = [];
                                    }
                                    
                                    if(empty($getexamsexist))
                                    {
                                        if($max_marks > 0)
                                        {
                                            if($start_date < $end_date)
                                            {
                                                if($saved = $exams_assessment_table->save($exams_ass) )
                                                {   
                                                    $examid = $saved->id;
                                                    array_push($examid_al_id, $examid);
                                                    if(!empty($studata) != ''){
                                                        $studata = explode(",",  $studata);
                                                        if($studata[0] != 'all'){
                                                            $retrieve_students = $student_table->find()->select(['id'])->where(['id IN' => $studata,'class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                        }else{
                                                            $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                        }
                                                        
                                                        foreach($retrieve_students as $students)
                                                        {
                                                            $stud_id = $students['id'];
                                                		    $submitexams = $submitexams_table->newEntity();
                                                            $submitexams->exam_id = $examid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                    
                                                    if($status == 1)
                                                    {
                                                        $ret_sub = $subject_table->find()->where(['id'=> $n_data['subjects'] ])->first();
                                                        
                                                        $subname = $ret_sub['subject_name'];
                                                        
                                                        $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                        $desc = "<p>Cher élève,</p>
                                                        <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                        <p>Merci pour votre diligence. </p>
                                                        </p>Cordialement.</p>";
                                                        date_default_timezone_set('Africa/Kinshasa');
                                                        $notify = $notify_table->newEntity();
                                                        $notify->title = $title;
                                                        $notify->description = $desc;
                                                        $notify->notify_to = 'students';
                                                        $notify->created_date =  time();
                                                        $notify->added_by = 'teachers';
                                                        $notify->teacher_id = $tid;
                                                        $notify->status = '1';
                                                        
                                                        $notify->class_ids = $cidss;
                                                        $notify->class_opt = 'multiple';
                                                        $scdate = date("d-m-Y H:i",time());
                                                        $notify->schedule_date = $scdate;
                                                        $notify->sent_notify = '1';
                                                        $notify->sc_date_time = time();
                                                        
                                                        $saved = $notify_table->save($notify);
                                                        
                                                        
                                                    }
                                                    
                                                    $activity = $activ_table->newEntity();
                                                    $activity->action =  "Exam Assessment Created"  ;
                                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                
                                                    $activity->value = md5($saved->id)   ;
                                                    $activity->origin = $this->Cookie->read('tid')   ;
                                                    $activity->created = strtotime('now');
                                                    if($saved = $activ_table->save($activity) ){
                                                        
                                                    }
                                                    else{
                                                        $res = [ 'result' => 'activity not saved'  ];
                                                    }
                                                }
                                                else{
                                                    $res = [ 'result' => $eans  ];
                                                }
                                            }
                                            else
                                            {
                                                 $res = [ 'result' => $labelstrtdtenddt ];
                                            }
                                        }
                                        else
                                        {
                                            $res = ['result' => $maxmrksntzero ];
                                        }
                                    }
                                    else
                                    {
                                        $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                    }
                                }
                            }
                        }
                        }
                        else
                        {
                            return $this->redirect('/login/') ;   
                        }
                    }
                    
                    if(!empty($examid_al_id)){
                        if($rqst == "Study Guide")
                        {
                            $ntype ="pdf";
                        }else{
                            $ntype ="customize";
                        }
                        $examid_al_id = implode(',', $examid_al_id);
                        $res = [ 'result' => 'success' , 'submitId' => $examid_al_id, 'type' => $ntype, 'uniqueid' => $uniqueid ];
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                
                return $this->json($res);
			}*/
			
			public function submitrequest()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $teacherid = $this->Cookie->read('tid');
                    $employee_table = TableRegistry::get('employee');
                    $submitexams_table = TableRegistry::get('submit_exams');
                    $sessionid = $this->Cookie->read('sessionid');
                    $student_table = TableRegistry::get('student');  
                    $notify_table = TableRegistry::get('notification'); 
                    $class_table = TableRegistry::get('class');  
                    $subject_table = TableRegistry::get('subjects'); 
                    
                    $uniqueid = uniqid();
                    
                    $lang = $this->Cookie->read('language');	
        			if($lang != "") { $lang = $lang; }
                    else { $lang = 2; }
                
                    $nopages = 0;
                    $dirname = "";
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
                        if($langlbl['id'] == '1611') { $labelstrtdtenddt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1944') { $maxmrksntzero = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1945') { $opa = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1946') { $eans = $langlbl['title'] ; } 
                    } 
                    $retrieve_langlabel1 = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                    
                    foreach($retrieve_langlabel1 as $langlbl1) { 
                        if($langlbl1['id'] == '635') { $scllbl = $langlbl1['french_label'] ; } 
                        if($langlbl1['id'] == '1798') { $quizlbl = $langlbl1['french_label'] ; } 
                        if($langlbl1['id'] == '2404') { $asslbl = $langlbl1['french_label'] ; } 
                        if($langlbl1['id'] == '1724') { $exmlbl = $langlbl1['french_label'] ; } 
                        if($langlbl1['id'] == '1799') { $studgulbl = $langlbl1['french_label'] ; } 
                        
    				}
    				 
                    if(!empty($teacherid)) 
                    {
                    $retrieve_schoolid = $employee_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $teacherid])->toArray() ;
                    
                    $school_id = $retrieve_schoolid[0]['school_id'];
                    $tid =  $retrieve_schoolid[0]['id'];
                    
                    $rqst = $this->request->data('request_for');
                    $type = $this->request->data('request_for');
                    $type1 = $type == "Assessment" ? "Assignment" : $type;
                    if( $type1 == "Exams" ) { 
                        $typelang = $exmlbl; 
                    }
                    elseif( $type1 == "Quiz" ) { 
                        $typelang = $quizlbl; 
                    }
                    elseif( $type1 == "Assignment" ) { 
                        $typelang = $asslbl; 
                    }
                    else { 
                        $typelang =  $studgulbl ;
                    } 
                    //echo $typelang; die;
                    if($rqst == "Study Guide")
                    {
                        
                            if(!empty($this->request->data['fileupload']))
                            {   
                                if($this->request->data['fileupload']['type'] == "application/pdf"  )
                                {
                                    //$picture =  $this->request->data['fileupload']['name'];
                                    $filename = time()."_".$this->request->data['fileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                } 
                                else
                                {
                                    $filename = "";
                                }
                            }
                            if($filename != "")
                            {
                                $im = new Imagick();
                                $im->pingImage('img/'.$filename);
                                $nopages = $im->getNumberImages();
                                $dirname = "Ebook".time();
                            }
                        
                            if(!empty($filename))
                            {
                                $all_data = $this->request->data('data');
                                $examid_al_id = array();
                                foreach($all_data as $key_a=>$n_data){
                                    $clsidss = $n_data['class'];
                                    $studata = $n_data['student'];
                                    foreach ($clsidss as $cidss)
                                    {                                    
                                        $exams_ass = $exams_assessment_table->newEntity();
                                        
                                        $exams_ass->title =  $this->request->data('title')  ;
                                        $exams_ass->subject_id =  $n_data['subjects'];
                                        //$exams_ass->special_instruction =  $this->request->data('instruction');
                                        $exams_ass->start_date =  $this->request->data('start_date');
                                        $exams_ass->end_date =  $this->request->data('end_date');
                                        $exams_ass->type =  $this->request->data('request_for');
                                        /*$exams_ass->exam_type =  $this->request->data('exam_type');
                                        $exams_ass->max_marks =  $this->request->data('max_marks');*/
                                        $exams_ass->created_date =  time()  ;
                                        $exams_ass->file_name =  $filename ;
                                        $exams_ass->teacher_id =  $tid ;
                                        $exams_ass->school_id =  $school_id ;
                                        $exams_ass->exam_format= "study_guide";
                                        $exams_ass->session_id =  $sessionid ;
                                        $exams_ass->status =  1  ;
                                        $exams_ass->numpages = $nopages;
                                        $exams_ass->dirname = $dirname;
                                        $exams_ass->uniqid = $uniqueid;
                                        $exams_ass->class_id = $cidss;

                                        if(!empty($studata)){
                                            $studata = implode(',',$studata);
                                        }else{
                                            $studata = '';
                                        }
                                        $exams_ass->student_id =  $studata;
                                        //print_r($exams_ass);
                                        $start_date =  strtotime($this->request->data('start_date'));
                                        $end_date =  strtotime($this->request->data('end_date'));
                                        
                                        if($start_date < $end_date)
                                        {
                                            if($saved = $exams_assessment_table->save($exams_ass) )
                                            {   
                                                $examid = $saved->id;
                                                array_push($examid_al_id, $examid);
                                                if(!empty($filename))
                                                {
                                                    mkdir($dirname);
                                                    $numpages = $nopages-1;
                        
                                                    for ($x = 0; $x <= $numpages; $x++) {
                                                        $save_to    = $dirname."/".$filename.$x.'.jpg';  
                                                        $im = new Imagick();
                                                        $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                                                        $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                                                        $im->setImageFormat('jpg');
                                                        $im = $im->flattenImages();
                                                        $im ->writeImages($save_to, true);
                                                        header('Content-Type: image/jpeg');
                                                        sleep(2);
                                                    }
                                                }
                                                
                                                $ret_sub = $subject_table->find()->where(['id'=> $n_data['subjects']])->first();
                                                $subname = $ret_sub['subject_name'];
                                                
                                                $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                $desc = "<p>Cher élève,</p>
                                                <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                <p>Merci pour votre diligence. </p>
                                                </p>Cordialement.</p>";
                                                date_default_timezone_set('Africa/Kinshasa');
                                                $notify = $notify_table->newEntity();
                                                $notify->title = $title;
                                                $notify->description = $desc;
                                                $notify->notify_to = 'students';
                                                $notify->created_date =  time();
                                                $notify->added_by = 'teachers';
                                                $notify->teacher_id = $tid;
                                                $notify->status = '1';
                                                
                                                $notify->class_ids = $cidss;
                                                $notify->class_opt = 'multiple';
                                                $scdate = date("d-m-Y H:i",time());
                                                $notify->schedule_date = $scdate;
                                                $notify->sent_notify = '1';
                                                $notify->sc_date_time = time();
                                                
                                                $saved = $notify_table->save($notify);
                                                
                                                $activity = $activ_table->newEntity();
                                                $activity->action =  "Study Guide Created"  ;
                                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            
                                                $activity->value = md5($saved->id)   ;
                                                $activity->origin = $this->Cookie->read('tid')   ;
                                                $activity->created = strtotime('now');
                                                $saved = $activ_table->save($activity);
                                                
                                            }
                                            else{
                                                $res = [ 'result' => $eans  ];
                                            }
                                        } 
                                        else
                                        {
                                            $res = [ 'result' => $labelstrtdtenddt  ];
                                        }
                                    }
                                }
                                if(!empty($examid_al_id)){                                    
                                    $examid_al_id = implode(',', $examid_al_id);
                                    $res = [ 'result' => 'success' , 'type' => 'pdf'  ];
                                }                                
                            }
                            else
                            { 
                                $res = [ 'result' => $opa  ];
                            }  
                    }
                    else
                    {
                        if($this->request->data('contentupload') == "pdf")
                        {
                            if(!empty($this->request->data['fileupload']))
                            {   
                                if($this->request->data['fileupload']['type'] == "application/pdf"  )
                                {
                                    //$picture =  $this->request->data['fileupload']['name'];
                                    $filename = time()."_".$this->request->data['fileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                } 
                                else
                                {
                                    $filename = "";
                                }
                            }
                        
                            if(!empty($filename))
                            {
                                $all_data = $this->request->data('data');
                                $examid_al_id = array();
                                foreach($all_data as $key_a=>$n_data){
                                    $clsidss = $n_data['class'];
                                    $studata = $n_data['student'];
                                    foreach ($clsidss as $cidss)
                                    {
                                        $exams_ass = $exams_assessment_table->newEntity();
                                        
                                        $exams_ass->uniqid = $uniqueid;
                                        $exams_ass->class_id = $cidss;
                                        $exams_ass->subject_id =  $n_data['subjects'];
                                        $exams_ass->title =  $this->request->data('title');
                                        $exams_ass->special_instruction =  $this->request->data('instruction');
                                        $exams_ass->start_date =  $this->request->data('start_date');
                                        $exams_ass->end_date =  $this->request->data('end_date');
                                        $exams_ass->type =  $this->request->data('request_for');
                                        $exams_ass->exam_type =  $this->request->data('exam_type');
                                        $exams_ass->exam_period =  $this->request->data('exam_period');
                                        $exams_ass->max_marks =  $this->request->data('max_marks');

                                        if(!empty($studata)){
                                            $studata = implode(',',$studata);
                                        }else{
                                            $studata = '';
                                        }
                                        $exams_ass->student_id =  $studata;

                                        $exams_ass->created_date =  time()  ;
                                        $exams_ass->file_name =  $filename ;
                                        $exams_ass->teacher_id =  $tid ;
                                        $exams_ass->school_id =  $school_id ;
                                        $exams_ass->exam_format= $this->request->data('contentupload');
                                        $exams_ass->session_id =  $sessionid ;
                                        $exams_ass->show_exmfrmt = "pdf";
                                        $type =  $this->request->data('request_for');
                                        if($type == "Exams")
                                        {
                                            $exams_ass->status =  0  ;
                                            $status =0;
                                        }
                                        else
                                        {
                                            $exams_ass->status =  1  ;
                                            $status = 1;
                                        }
                                        $maxmarks =  $this->request->data('max_marks');
                                        $start_date =  strtotime($this->request->data('start_date'));
                                        $end_date =  strtotime($this->request->data('end_date'));
                                        
                                        if($type == "Exams")
                                        {
                                            $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $n_data['subjects'], 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                        }
                                        else
                                        {
                                            $getexamsexist = [];
                                        }
                                        //print_r($exams_ass); die;
                                        if(empty($getexamsexist))
                                        {
                                            if($maxmarks > 0)
                                            {
                                                if($start_date < $end_date)
                                                { 
                                                    if($saved = $exams_assessment_table->save($exams_ass) )
                                                    {   
                                                        $examid = $saved->id;

                                                        array_push($examid_al_id, $examid);
                                                        if(!empty($studata) != ''){
                                                            $studata = explode(",",  $studata);
                                                            if($studata[0] != 'all'){
                                                                $retrieve_students = $student_table->find()->select(['id'])->where(['id IN' => $studata,'class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                            }else{
                                                                $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                            }
                                                            
                                                            foreach($retrieve_students as $students)
                                                            {
                                                                $stud_id = $students['id'];
                                                                $submitexams = $submitexams_table->newEntity();
                                                                $submitexams->exam_id = $examid;
                                                                $submitexams->student_id = $stud_id;
                                                                $submitexams->status = 0;
                                                                $submitexams->school_id = $school_id;
                                                                $saved = $submitexams_table->save($submitexams);
                                                                
                                                            }
                                                        }
                                                        /*if($type != "Exams")
                                                        {
                                                            $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                            foreach($retrieve_students as $students)
                                                            {
                                                                $stud_id = $students['id'];
                                                    		    $submitexams = $submitexams_table->newEntity();
                                                                $submitexams->exam_id = $examid;
                                                                $submitexams->student_id = $stud_id;
                                                        		$submitexams->status = 0;
                                                                $submitexams->school_id = $school_id;
                                                                $saved = $submitexams_table->save($submitexams);
                                                            }
                                                        }*/
                                                
                                                        if($status == 1)
                                                        {
                                                            $ret_sub = $subject_table->find()->where(['id'=> $n_data['subjects']])->first();
                                                            
                                                            $subname = $ret_sub['subject_name'];
                                                            $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                            $desc = "<p>Cher élève,</p>
                                                            <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                            <p>Merci pour votre diligence. </p>
                                                            </p>Cordialement.</p>";
                                                            date_default_timezone_set('Africa/Kinshasa');
                                                            $notify = $notify_table->newEntity();
                                                            $notify->title = $title;
                                                            $notify->description = $desc;
                                                            $notify->notify_to = 'students';
                                                            $notify->created_date =  time();
                                                            $notify->added_by = 'teachers';
                                                            $notify->teacher_id = $tid;
                                                            $notify->status = '1';
                                                            
                                                            $notify->class_ids = $cidss;
                                                            $notify->class_opt = 'multiple';
                                                            $scdate = date("d-m-Y H:i",time());
                                                            $notify->schedule_date = $scdate;
                                                            $notify->sent_notify = '1';
                                                            $notify->sc_date_time = time();
                                                            
                                                            $saved = $notify_table->save($notify);
                                                            
                                                            
                                                        }
                                                        
                                                        $activity = $activ_table->newEntity();
                                                        $activity->action =  "Exam Assessment Created"  ;
                                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                    
                                                        $activity->value = md5($saved->id)   ;
                                                        $activity->origin = $this->Cookie->read('tid')   ;
                                                        $activity->created = strtotime('now');
                                                        
                                                    }
                                                    else{
                                                        $res = [ 'result' => $eans  ];
                                                    }
                                                }
                                                else
                                                {
                                                    $res = [ 'result' => $labelstrtdtenddt  ];
                                                }
                                            }
                                            else
                                            {
                                                $res = ['result' => $maxmrksntzero ];
                                            }
                                        }
                                        else
                                        {
                                            $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                        }
                                    }
                                }
                                if(!empty($examid_al_id)){                                    
                                    $examid_al_id = implode(',', $examid_al_id);
                                    $res = [ 'result' => 'success' , 'type' => 'pdf'  ];
                                }                                  
                            }
                            else
                            { 
                                $res = [ 'result' => $opa  ];
                            }  
                            
                        }
                        else
                        {
                            $all_data = $this->request->data('data');
                            $examid_al_id = array();
                            foreach($all_data as $key_a=>$n_data){
                                $clsidss = $n_data['class'];
                                $studata = $n_data['student'];
                                foreach ($clsidss as $cidss)
                                {
                                    $exams_ass = $exams_assessment_table->newEntity();
                                    $exams_ass->uniqid = $uniqueid;
                                    $exams_ass->class_id = $cidss;
                                        
                                    $exams_ass->subject_id =  $n_data['subjects']  ;
                                    $exams_ass->title =  $this->request->data('title');
                                    $exams_ass->special_instruction =  $this->request->data('instruction');
                                    $exams_ass->start_date =  $this->request->data('start_date');
                                    $exams_ass->end_date =  $this->request->data('end_date');
                                    $exams_ass->type =  $this->request->data('request_for');
                                    $exams_ass->exam_type =  $this->request->data('exam_type');
                                    $exams_ass->max_marks =  $this->request->data('max_marks');
                                    $exams_ass->created_date =  time()  ;
                                    //$exams_ass->file_name =  $filename ;
                                    $exams_ass->exam_period =  $this->request->data('exam_period');
                                    $exams_ass->teacher_id =  $tid ;
                                    $exams_ass->school_id =  $school_id ;
                                    $exams_ass->exam_format= $this->request->data('contentupload');
                                    $exams_ass->session_id =  $sessionid ;

                                    if(!empty($studata)){
                                        $studata = implode(',',$studata);
                                    }else{
                                        $studata = '';
                                    }
                                    $exams_ass->student_id =  $studata;
                                    
                                    if($this->request->data('request_for') == "Exams")
                                    {
                                        $exams_ass->show_exmfrmt = $this->request->data['exam_format'];
                                    }
                                    else
                                    {
                                        $exams_ass->show_exmfrmt = "pdf";
                                    }
                                    $type =  $this->request->data('request_for');
                                    if($type == "Exams")
                                    {
                                        $exams_ass->status =  0  ;
                                        $status = 0;
                                    }
                                    else
                                    {
                                        $exams_ass->status =  1  ;
                                        $status = 1;
                                    }
                                    $max_marks =  $this->request->data('max_marks');
                                    $start_date =  strtotime($this->request->data('start_date'));
                                    $end_date =  strtotime($this->request->data('end_date'));
                                    
                                    if($type == "Exams")
                                    {
                                        $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $n_data['subjects'], 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                    }
                                    else
                                    {
                                        $getexamsexist = [];
                                    }
                                    
                                    if(empty($getexamsexist))
                                    {
                                        if($max_marks > 0)
                                        {
                                            if($start_date < $end_date)
                                            {
                                                if($saved = $exams_assessment_table->save($exams_ass) )
                                                {   
                                                    $examid = $saved->id;
                                                    array_push($examid_al_id, $examid);
                                                    if(!empty($studata) != ''){
                                                        $studata = explode(",",  $studata);
                                                        if($studata[0] != 'all'){
                                                            $retrieve_students = $student_table->find()->select(['id'])->where(['id IN' => $studata,'class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                        }else{
                                                            $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                        }
                                                        
                                                        foreach($retrieve_students as $students)
                                                        {
                                                            $stud_id = $students['id'];
                                                            $submitexams = $submitexams_table->newEntity();
                                                            $submitexams->exam_id = $examid;
                                                            $submitexams->student_id = $stud_id;
                                                            $submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                    /*if($type != "Exams")
                                                    {
                                                        $retrieve_students = $student_table->find()->select(['id'])->where(['class'=> $cidss, 'session_id' => $sessionid])->toArray();
                                                        foreach($retrieve_students as $students)
                                                        {
                                                            $stud_id = $students['id'];
                                                		    $submitexams = $submitexams_table->newEntity();
                                                            $submitexams->exam_id = $examid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                        }
                                                    }*/
                                                    
                                                    if($status == 1)
                                                    {
                                                        $ret_sub = $subject_table->find()->where(['id'=> $n_data['subjects']])->first();
                                                        
                                                        $subname = $ret_sub['subject_name'];
                                                        
                                                        $title = "(".$typelang.") Votre instructeur en ". $subname." a généré une nouvelle tâche qui nécessite votre attention.";
                                                        $desc = "<p>Cher élève,</p>
                                                        <p>(".$typelang.") Nous vous informons que votre enseignant(e) en ".$subname. " a créé une nouvelle tâche. Vous êtes prié(e) de soumettre votre travail avant la date et l'heure limite qui est le ". $this->request->data('start_date'). ". Veuillez noter que le système ne vous permettra pas de soumettre votre travail après l’heure et la date indiquée ci-dessus par votre enseignant(e) et cette tâche se fermera automatiquement.</p>
                                                        <p>Merci pour votre diligence. </p>
                                                        </p>Cordialement.</p>";
                                                        date_default_timezone_set('Africa/Kinshasa');
                                                        $notify = $notify_table->newEntity();
                                                        $notify->title = $title;
                                                        $notify->description = $desc;
                                                        $notify->notify_to = 'students';
                                                        $notify->created_date =  time();
                                                        $notify->added_by = 'teachers';
                                                        $notify->teacher_id = $tid;
                                                        $notify->status = '1';
                                                        
                                                        $notify->class_ids = $cidss;
                                                        $notify->class_opt = 'multiple';
                                                        $scdate = date("d-m-Y H:i",time());
                                                        $notify->schedule_date = $scdate;
                                                        $notify->sent_notify = '1';
                                                        $notify->sc_date_time = time();
                                                        
                                                        $saved = $notify_table->save($notify);
                                                        
                                                        
                                                    }
                                                    
                                                    $activity = $activ_table->newEntity();
                                                    $activity->action =  "Exam Assessment Created"  ;
                                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                
                                                    $activity->value = md5($saved->id)   ;
                                                    $activity->origin = $this->Cookie->read('tid')   ;
                                                    $activity->created = strtotime('now');
                                                    $saved = $activ_table->save($activity);                                                    
                                                }
                                                else{
                                                    $res = [ 'result' => $eans  ];
                                                }
                                            }
                                            else
                                            {
                                                 $res = [ 'result' => $labelstrtdtenddt ];
                                            }
                                        }
                                        else
                                        {
                                            $res = ['result' => $maxmrksntzero ];
                                        }
                                    }
                                    else
                                    {
                                        $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                    }
                                }
                            }

                            if(!empty($examid_al_id)){                        
                                $examid_al_id = implode(',', $examid_al_id);
                                $res = [ 'result' => 'success' , 'submitId' => $examid_al_id, 'type' => 'customize', 'uniqueid' => $uniqueid ];
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
                    $res = ['result' => 'invalid operation'];
                }
                
               // print_r($res); die;
                return $this->json($res);
			}
			public function addQuestion($id)
            {  
                
                $ids = explode(',', $id);
                //print_r($ids);exit;
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $ids[0]])->toArray(); 
                $retrieve_cls = $cls_table->find()->where([ 'id' => $get_examass_data[0]['class_id'] ])->toArray();
                $retrieve_sub = $sub_table->find()->where([ 'id' => $get_examass_data [0]['subject_id'] ])->toArray();
                
                $get_data = $ques_table->find()->where(['exam_id' => $ids[0]])->toArray(); 
                $ddata = array();
                foreach($get_data as $key =>$gdata){
                    $gnid = ''; $ddata[$key] = $gdata;
                    foreach($ids as $nids){
                        $get_id = $ques_table->find()->select(['id'])->where(['exam_id' => $nids, 'question' => $gdata['question']])->first(); 
                        if($gnid == ''){
                            $gnid .= $get_id['id'];
                        }else{
                            $gnid .= ','.$get_id['id'];
                        }
                    }
                    
                    if($gnid != ''){
                        $ddata[$key]['id'] = $gnid;
                    }
                }
                
                //return $this->json($get_data);
                $this->set("questiondetails", $ddata);
                $this->set("datadetails", $get_examass_data);
                $this->set("subjectdtl", $retrieve_sub);
                $this->set("classdtl", $retrieve_cls);
                $this->set("ids", $ids);
                $this->set("examid", $id);
                $this->viewBuilder()->setLayout('user');
                
            }
			public function getquestion()
            {  
                $id = $this->request->data('examId');
                $ques_table = TableRegistry::get('exam_ass_question');
                $get_data = $ques_table->find()->where(['exam_id' => $id])->toArray(); 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                
                
                
                $data = "";
                $datavalue = array();
                $total = [];
                foreach ($get_data as $value) 
				{
				    $total[] =  $value['marks'];
				    
					$data .=  "
					    <tr> 
                            <td>
                                <span >". substr($value['question'], 0, 40) ."</span>
                            </td>
                            <td>
                                <span >". $value['ques_type'] ."</span>
                            </td>
                            <td>
                                <span >". $value['marks'] ."</span>
                            </td>
                            <td>
                                <span>". date('d-m-Y', $value['created_date'])."</span>
                            </td>
                            <td>
                                <button type='button' data-id='". $value['id'] ."' class='btn btn-sm btn-outline-secondary ecutsomize_exam' data-toggle='modal'  data-target='#ecutsomize_exam' title='Edit'><i class='fa fa-edit'></i></button>
                                
                                <button type='button' data-id='".$value['id']."' data-url='../../examAssessment/delete_question' class='btn btn-sm btn-outline-danger js-sweetalert ' title='Delete' data-str='Question' data-type='confirm'><i class='fa fa-trash-o'></i></button>
                            </td>
                        </tr>";
                }
                if(empty($total))
                {
                    $allo = 0;
                }
                else
                {
                    $allo = array_sum($total);
                }
                
                
                $datavalue['html']= $data;
                $datavalue['allocated']= $allo;
                return $this->json($datavalue);
            }
            public function addcutsomizeexm()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $company_table = TableRegistry::get('company');
                    $exam_ids =  explode(',',$this->request->data('submitId'))  ;
                    foreach($exam_ids as $exam_id){
                        $get_maxmarks = $exams_assessment_table->find()->select(['max_marks', 'uniqid'])->where(['id' => $exam_id])->toArray(); 
                        $maxMarks = $get_maxmarks[0]['max_marks'];
                        
                        $get_examids = $exams_assessment_table->find()->select(['id'])->where(['uniqid' => $get_maxmarks[0]['uniqid']])->toArray(); 
                        
                        $get_marks = $custmze_que_table->find()->select(['marks'])->where(['exam_id' => $exam_id])->toArray(); 
                        $totl_que_mrks = [];
                        foreach($get_marks as $tlmarks)
                        {
                            $totl_que_mrks[] = $tlmarks['marks'];
                        }
                        
                        if(!empty($totl_que_mrks))
                        {
                            $total = array_sum($totl_que_mrks);
                            $totl = $total+$this->request->data('marks');
                            
                        }
                        else
                        {
                            $totl = $this->request->data('marks');
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
                            if($langlbl['id'] == '1950') { $qeanu = $langlbl['title'] ; } 
                            if($langlbl['id'] == '1949') { $wcntneg = $langlbl['title'] ; }
                            if($langlbl['id'] == '1948') { $mgtz = $langlbl['title'] ; }
                            if($langlbl['id'] == '1947') { $qmemm = $langlbl['title'] ; }
                        } 
                        $uniq = uniqid();
                        if($totl <= $maxMarks )
                        {
                            foreach($get_examids as $exmids)
                            {
                                
                                
                                $cus_que = $custmze_que_table->newEntity();
                                
                                $cus_que->exam_id =  $exmids['id']  ;
                                $cus_que->marks =  $this->request->data('marks')  ;
                                $cus_que->question =  $this->request->data('question')  ;
                                $cus_que->ques_type =  $this->request->data('optionques')  ;
                                $cus_que->uniqid =  $uniq;
                                if(!empty($this->request->data['valueques']))
                                {
                                    $cus_que->options = implode("~^", $this->request->data['valueques']);
                                }
                                $cus_que->created_date =  time();
                                $maxwords = '';
                                if(!empty($this->request->data['maxwords']))
                                {
                                    $maxwords = $this->request->data('maxwords')  ;
                                    $cus_que->max_words =  $this->request->data('maxwords')  ;
                                }
                                $marks =  $this->request->data('marks')  ;
                                if($marks > 0)
                                {
                                    if(empty($maxwords) || $maxwords > 0)
                                    {
                                        $getcount = $custmze_que_table->find()->where(['exam_id' => $exmids['id'],'marks' => $this->request->data('marks'),'question' =>$this->request->data('question'), 'ques_type' => $this->request->data('optionques')])->count(); 
                                            if($getcount == 0 )
                                            {   
                                                $saved = $custmze_que_table->save($cus_que);
                                                $saved = 1;
                                                if($totl == 0)
                                                {
                                                    $totl = $this->request->data('marks');
                                                }
                                                
                                            }
                                            else{
                                                $res = [ 'result' => $qeanu ];
                                            }
                                    }
                                    else
                                    {
                                        $res = [ 'result' => $wcntneg ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' =>  $mgtz ];
                                }
                            }
                        }
                        else
                        {
                             $res = [ 'result' => $qmemm  , 'max_marks' =>  $maxMarks, 'allocate' => $totl ];
                        }
                       
                    }
                    if($saved){
                        $res = [ 'result' => 'success', 'max_marks' =>  $maxMarks, 'allocate' => $totl];
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
			
			public function editquestion()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $queid = $this->request->data('queid');
                $ques_table = TableRegistry::get('exam_ass_question');
                $get_data = $ques_table->find()->where(['id' => $queid])->toArray(); 
                return $this->json($get_data);
            }
            
            public function edcustmque()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $schoolid = $this->Cookie->read('id');
                    $company_table = TableRegistry::get('company');
                    $exam_ids = explode(',', $this->request->data('examid'))  ;
                    $que_ids =  explode(',', $this->request->data('queid'))  ;
                    $exam_id = $exam_ids[0];
                    $que_id = $que_ids[0];
                    
                    /*$exam_ids =  explode(',',$this->request->data('examid'));
                    $que_ids =  explode(',',$this->request->data('queid'));*/
                    
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $get_maxmarks = $exams_assessment_table->find()->select(['max_marks'])->where(['id' => $exam_id])->toArray(); 
                    $maxMarks = $get_maxmarks[0]['max_marks'];
                    
                    $get_marks = $custmze_que_table->find()->select(['marks'])->where(['exam_id' => $exam_id, 'id !=' => $que_id])->toArray(); 
                    $totl_que_mrks = [];
                    foreach($get_marks as $tlmarks)
                    {
                        $totl_que_mrks[] = $tlmarks['marks'];
                    }
                    
                    if(!empty($totl_que_mrks))
                    {
                        $total = array_sum($totl_que_mrks);
                        $totl = $total+$this->request->data('emarks');
                        
                    }
                    else
                    {
                        $totl = $this->request->data('emarks');
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
                        if($langlbl['id'] == '1950') { $qeanu = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1949') { $wcntneg = $langlbl['title'] ; }
                        if($langlbl['id'] == '1948') { $mgtz = $langlbl['title'] ; }
                        if($langlbl['id'] == '1947') { $qmemm = $langlbl['title'] ; }
                    } 
                    
                    $getuniqid = $custmze_que_table->find()->select(['uniqid'])->where(['id' => $que_id])->first(); 
                    $uniqueid = $getuniqid['uniqid'];
                    
                    if($totl <= $maxMarks )
                    {
                        /*$que_id =  $this->request->data('queid')  ;
                        $exam_id =  $this->request->data('examid')  ;*/
                        $exam_ids = explode(',', $this->request->data('examid'))  ;
                        $que_ids =  explode(',', $this->request->data('queid'))  ;
                        $exam_id = $exam_ids[0];
                        $que_id = $que_ids[0];
                    
                        $marks =  $this->request->data('emarks')  ;
                        $question =  $this->request->data('equestion')  ;
                        $ques_type =  $this->request->data('eoptionques')  ;
                        $max_words =  $this->request->data('emaxwords')  ;
                        $created_date =  time();
                        if($marks > 0)
                        {
                            if(empty($max_words) || $max_words > 0)
                            {
                                
                                if(!empty($this->request->data['evalueques']))
                                {
                                    $options = implode("~^", $this->request->data['evalueques']);
                                    foreach($que_ids as $nkey => $nqid){
                                        $ngetuniqid = $custmze_que_table->find()->select(['uniqid'])->where(['id' => $nqid])->first(); 
                                        $nuniqueid = $ngetuniqid['uniqid'];
                                        $update = $custmze_que_table->query()->update()->set(['question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'options' => $options, 'created_date' => $created_date])->where([ 'id' => $nqid  ])->execute();
                                    }
                                }
                                else
                                {
                                    
                                    foreach($que_ids as $nkey => $nqid){
                                        $ngetuniqid = $custmze_que_table->find()->select(['uniqid'])->where(['id' => $nqid])->first(); 
                                        $nuniqueid = $ngetuniqid['uniqid'];
                                        $update = $custmze_que_table->query()->update()->set(['max_words' => $max_words, 'question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'created_date' => $created_date])->where([ 'id' => $nqid  ])->execute();
                                       
                                    }
                                    
                                }
                                        
                                if($update)
                                {   
                                        //print_r($exam_id);exit;    
                                    $retrieve_ques = $custmze_que_table->find()->where(['exam_id' => $exam_id, 'id >' => $que_id])->order(['id' => 'ASC'])->limit(1)->toArray() ;
                                    $res = [ 'result' => 'success', 'question' =>  $retrieve_ques ];
                                }
                                else{
                                     print_r("rgerger");exit;
                                    $res = [ 'result' => $qeanu ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $wcntneg];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => $mgtz  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => $qmemm  ];
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
			
			public function deleteQuestion()
            {
                $sid = $this->request->data('val') ; 
                $ques_table = TableRegistry::get('exam_ass_question');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $eaid = $ques_table->find()->select(['id', 'uniqid'])->where(['id'=> $sid ])->first();
				//echo $eaid['uniqid'];
                if($eaid)
                {   
                    $del = $ques_table->query()->delete()->where([ 'uniqid' => $eaid['uniqid'] ])->execute(); 
                    if($del)
                    { 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Question Deleted"  ;
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
            
            public function getsubjecttchr()
            {
                $clsid = $this->request->data['clsid'];
                $countlcs = count($clsid);
                $subid = $this->request->data('subid');
                $subject_table = TableRegistry::get('subjects');
                $empcls_table = TableRegistry::get('employee_class_subjects');
                $subcls_table = TableRegistry::get('class_subjects');
                $teacherid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid'); 
                
                if(!empty($tid))
                {
                    if($countlcs == 1)
                    {
                        $retrieve_sub = $empcls_table->find()->select(['subjects.subject_name', 'subjects.id'])->join(
    	                [
    			            'subjects' => 
                            [
                                'table' => 'subjects',
                                'type' => 'LEFT',
                                'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                            ]
    
                        ])->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['subjects']['id'].'">'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                    }
                    else
                    {
                        $retrieve_subids = $empcls_table->find()->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        foreach($retrieve_subids as $subids)
                        {
                            $subjcid[] = $subids['subject_id'];
                        }
                        $subjidss = [];
                        foreach($retrieve_subids as $subids)
                        {
                            
                            $counts = array_count_values($subjcid);
                            $subidss = $counts[$subids['subject_id']];
                            if($subidss > 1)
                            {
                                $subjidss[] = $subids['subject_id'];
                            }
                        }
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjidss ])->group(['id'])->toArray();
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                    }
                }
                else
                {
                    if($countlcs == 1)
                    {
                        
                        $retrieve_clssub = $subcls_table->find()->where(['class_id IN' => $clsid ])->toArray();
                        $subjectsids = explode(",",  $retrieve_clssub[0]['subject_id']);
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjectsids ])->toArray();
                        
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['id'].'" '.$sel.'>'.$subj['subject_name'].'</option>';
                            }
                        }
                    }
                    else
                    {
                        $retrieve_subids = $empcls_table->find()->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        foreach($retrieve_subids as $subids)
                        {
                            $subjcid[] = $subids['subject_id'];
                        }
                        $subjidss = [];
                        foreach($retrieve_subids as $subids)
                        {
                            
                            $counts = array_count_values($subjcid);
                            $subidss = $counts[$subids['subject_id']];
                            if($subidss > 1)
                            {
                                $subjidss[] = $subids['subject_id'];
                            }
                        }
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjidss ])->group(['id'])->toArray();
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                    }
                }
                $subjects['subjectname'] = $data;
                return $this->json($subjects);
				
            }

            public function getsections()
            {
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid'); 
                $employee_table = TableRegistry::get('employee');
                $empclssub_table = TableRegistry::get('employee_class_subjects');
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				$compid = $this->request->session()->read('company_id');
				$sessionid = $this->Cookie->read('sessionid');
			//	print_r($sessionid);exit;
				$student_table = TableRegistry::get('student');
				if(!empty($tid))
				{
    				$retrieve_empclass = $empclssub_table->find()->where([ 'md5(emp_id)' => $tid ])->toArray();
    				$empcls = [];
    				foreach($retrieve_empclass as $ecls)
    				{
    				    $empcls[] = $ecls['class_id'];
    				}
    				$empcls = array_unique($empcls);
    			    $clsname = $this->request->data('filter'); 
    			    if(is_array($clsname))
    			    {
    			        
        				$retrieve_clssectns = $class_table->find()->where([ 'id IN' => $clsname ])->toArray();
        				$sctnid = [];
        				foreach($retrieve_clssectns as $csctn)
        				{
        				    $sctnid[] = $csctn['id'];
        				}
        				$common = array_intersect($sctnid, $empcls);
        				
        				$retrieve_sectns = $class_table->find()->where([ 'id IN' => $common ])->toArray();
        				$data = '<option value="">Choose Sectionss</option>';
        				foreach($retrieve_sectns as $csctns)
        				{
        				    $sel = '';
        				    if(!empty($csctns['c_section']))
        				    {
        				        if(in_array($csctns['id'], $clsname))
        				        {
        				            $sel = "selected";
        				        }
        				        $data .= '<option value="'.$csctns['id'].'" '.$sel.'>'.$csctns['c_section'].'</option>';
        				    }
        				    else
        				    {
        				        $data .= '<option value="'.$csctns['id'].'">Click here to get subjects</option>';
        				        //return $this->redirect('getsubjecttchr');
        				    }
        				}
        				
        				$retrieve_stdnt = $student_table->find()->where([ 'class IN' => $clsname, 'session_id' => $sessionid ])->toArray();
    				
        				$data1 = '<option value="">Choose Student</option><option value="all" selected>All</option>';
        				//if(count($retrieve_stdnt)){
        				    $data1 = '';
        				    $data1 .= '<option value="all" selected>All</option>';    
        				//}
        				foreach($retrieve_stdnt as $stdnt)
        				{
        				    //$sel = ''; '.$sel.'
        				    
        				        /*if(in_array($stdnt['id'], $clsname))
        				        {
        				            $sel = "selected";
        				        }*/
        				        $data1 .= '<option value="'.$stdnt['id'].'" >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
        				    
        				}
    			    }
    			    else
    			    {
        				$cname = explode("_", $clsname);
        				
        				$retrieve_clssectns = $class_table->find()->where([ 'c_name' => $cname[0], 'school_sections' => $cname[1], 'school_id' => $compid ])->toArray();
        				$sctnid = [];
        				foreach($retrieve_clssectns as $csctn)
        				{
        				    $sctnid[] = $csctn['id'];
        				}
        				$common = array_intersect($sctnid, $empcls);
        				
        				$retrieve_sectns = $class_table->find()->where([ 'id IN' => $common ])->toArray();
        				$data = '<option value="">Choose Section</option>';
        				foreach($retrieve_sectns as $csctns)
        				{
        				    if(!empty($csctns['c_section']))
        				    {
        				        $data .= '<option value="'.$csctns['id'].'">'.$csctns['c_section'].'</option>';
        				    }
        				    else
        				    {
        				        $data .= '<option value="'.$csctns['id'].'">Click here to get subjects</option>';
        				        //return $this->redirect('getsubjecttchr');
        				    }
        				}
        				
        				$retrieve_stdnt = $student_table->find()->where([ 'class IN' => $common, 'session_id' => $sessionid ])->toArray();
    				
        				$data1 = '<option value="">Choose Student</option><option value="all" selected>All</option>';
        				//if(count($retrieve_stdnt)){
        				    $data1 = '';
        				    $data1 .= '<option value="all" selected>All</option>';    
        				//}
        				foreach($retrieve_stdnt as $stdnt)
        				{
        				    //$sel = ''; '.$sel.'
        				    
        				        /*if(in_array($stdnt['id'], $clsname))
        				        {
        				            $sel = "selected";
        				        }*/
        				        $data1 .= '<option value="'.$stdnt['id'].'" >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
        				    
        				}
    			    }
				}
				else
				{
    			    $clsname = $this->request->data('filter'); 
    			    $cname = explode("_", $clsname);
    			    $retrieve_clssectns = $class_table->find()->where([ 'c_name' => $cname[0], 'school_sections' => $cname[1], 'school_id' => $compid ])->toArray();
    			    //print_r($retrieve_clssectns);exit;
    				$sctnid = [];
    				//print_r($retrieve_clssectns);
    				foreach($retrieve_clssectns as $csctn)
    				{
    				    //print_r($csctn);
    				    $sctnid[] = $csctn['id'];
    				}
    				$common = $sctnid;
        		    //print_r($common); die;
    				$retrieve_sectns = $class_table->find()->where([ 'id IN' => $common ])->toArray();
    				$data = '<option value="">Choose Section</option>';
    				foreach($retrieve_sectns as $csctns)
    				{
    				    $sel = '';
    				    if(!empty($csctns['c_section']))
    				    {
    				        if(in_array($csctns['id'], $clsname))
    				        {
    				            $sel = "selected";
    				        }
    				        $data .= '<option value="'.$csctns['id'].'" '.$sel.'>'.$csctns['c_section'].'</option>';
    				    }
    				    else
    				    {
    				        $data .= '<option value="'.$csctns['id'].'">Click here to get subjects</option>';
    				    }
    				}
    				
    				$retrieve_stdnt = $student_table->find()->where([ 'class IN' => $common, 'session_id' => $sessionid, 'school_id' => $compid])->toArray();
    				
    				$data1 = '<option value="">Choose Student</option>';
    				//if(count($retrieve_stdnt)){
    				    $data1 = '';
    				    $data1 .= '<option value="all" selected>All</option>';    
    				//}
    				foreach($retrieve_stdnt as $stdnt)
    				{
    				    //$sel = ''; '.$sel.'
    				    
    				        /*if(in_array($stdnt['id'], $clsname))
    				        {
    				            $sel = "selected";
    				        }*/
    				        $data1 .= '<option value="'.$stdnt['id'].'" >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
    				    
    				}
    				
				}
				$result['section'] = $data;
				$result['student'] = $data1;
				return $this->json($result);
            }
            
            public function getstdnt(){
                $student_table = TableRegistry::get('student');
                $compid = $this->request->session()->read('company_id');
				$sessionid = $this->Cookie->read('sessionid');
				
				$clsname = $this->request->data('filter'); 
                $retrieve_stdnt = $student_table->find()->where([ 'class IN' => $clsname, 'session_id' => $sessionid, 'school_id' => $compid ])->toArray();
    				
				$data1 = '<option value="">Choose Student</option>';
				//if(count($retrieve_stdnt)){
				    $data1 = '';
				    $data1 .= '<option value="all">All</option>';    
				//}
				foreach($retrieve_stdnt as $stdnt)
				{
				    //$sel = ''; '.$sel.'
				    
				        /*if(in_array($stdnt['id'], $clsname))
				        {
				            $sel = "selected";
				        }*/
				        $data1 .= '<option value="'.$stdnt['id'].'" >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
				}
				$result['student'] = $data1;
				return $this->json($result);
            }
            
            
             public function getsubjecttchrnew()
            {
                $clsid = $this->request->data['clsid'];
                $countlcs = count($clsid);
                $subid = $this->request->data('subid');
                $subject_table = TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $empcls_table = TableRegistry::get('employee_class_subjects');
                $subcls_table = TableRegistry::get('class_subjects');
                $teacherid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('tid'); 
                $display = 1;
                if(!empty($tid))
                {
                    if($countlcs == 1)
                    {
                        $retrieve_sub = $empcls_table->find()->select(['subjects.subject_name', 'subjects.id'])->join(
    	                [
    			            'subjects' => 
                            [
                                'table' => 'subjects',
                                'type' => 'LEFT',
                                'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                            ]
    
                        ])->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['subjects']['id'].'">'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                        
                        $retrieve_class = $class_table->find()->where([ 'id' => $clsid ])->first();
                        $class_kin = array('Maternelle', 'Creche');
                        if(in_array($retrieve_class['school_sections'], $class_kin)){
                            $display = 0;
                        }
                    }
                    else
                    {
                        $retrieve_subids = $empcls_table->find()->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        foreach($retrieve_subids as $subids)
                        {
                            $subjcid[] = $subids['subject_id'];
                        }
                        $subjidss = [];
                        foreach($retrieve_subids as $subids)
                        {
                            
                            $counts = array_count_values($subjcid);
                            $subidss = $counts[$subids['subject_id']];
                            if($subidss > 1)
                            {
                                $subjidss[] = $subids['subject_id'];
                            }
                        }
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjidss ])->group(['id'])->toArray();
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                    }
                }
                else
                {
                    if($countlcs == 1)
                    {
                        
                        $retrieve_clssub = $subcls_table->find()->where(['class_id IN' => $clsid ])->toArray();
                        $subjectsids = explode(",",  $retrieve_clssub[0]['subject_id']);
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjectsids ])->toArray();
                        
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['id'].'" '.$sel.'>'.$subj['subject_name'].'</option>';
                            }
                        }
                    }
                    else
                    {
                        $retrieve_subids = $empcls_table->find()->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id IN' => $clsid ])->toArray();
                        foreach($retrieve_subids as $subids)
                        {
                            $subjcid[] = $subids['subject_id'];
                        }
                        $subjidss = [];
                        foreach($retrieve_subids as $subids)
                        {
                            
                            $counts = array_count_values($subjcid);
                            $subidss = $counts[$subids['subject_id']];
                            if($subidss > 1)
                            {
                                $subjidss[] = $subids['subject_id'];
                            }
                        }
                        
                        $retrieve_sub = $subject_table->find()->where(['id IN' => $subjidss ])->group(['id'])->toArray();
                        $data = "";
                        $data .= '<option value="">Choose Subjects</option>';
                        
                        if($subid == "")
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $data .= '<option value="'.$subj['id'].'">'.$subj['subject_name'].'</option>';
                            }
                        }
                        else
                        {
                            foreach($retrieve_sub as $subj)
                            {
                                $sel ='';
                                if($subj['subjects']['id'] == $subid)
                                {
                                    $sel = "selected";
                                }
                                $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                            }
                        }
                    }
                }
                
                
                $subjects['subjectname'] = $data;
                $subjects['subjectdisplay'] = $display;
                return $this->json($subjects);
				
            }
}

  


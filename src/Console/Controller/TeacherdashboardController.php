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
				if(!empty($retrieve_employees))
				{
    				
    				
    				$retrieve_empclsses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
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
                            
        
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['class.id'])->toArray();
                        
                        
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
    				
    
    				$this->viewBuilder()->setLayout('user');
				}
				else
				{
				    return $this->redirect('/login/') ;
				}
            }
	
			public function submitrequest()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $teacherid = $this->Cookie->read('tid');
                    $employee_table = TableRegistry::get('employee');
                    $session_id = $this->Cookie->read('sessionid');
                    
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
                        if($langlbl['id'] == '1611') { $labelstrtdtenddt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1944') { $maxmrksntzero = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1945') { $opa = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1946') { $eans = $langlbl['title'] ; } 
                    } 
                    
                    if(!empty($teacherid)) 
                    {
                    $retrieve_schoolid = $employee_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $teacherid])->toArray() ;
                    
                    $school_id = $retrieve_schoolid[0]['school_id'];
                    $tid =  $retrieve_schoolid[0]['id'];
                    
                    $rqst = $this->request->data('request_for');
                    if($rqst == "Study Guide")
                    {
                        
                            if(!empty($this->request->data['fileupload']))
                            {   
                                if($this->request->data['fileupload']['type'] == "application/pdf"  )
                                {
                                    //$picture =  $this->request->data['fileupload']['name'];
                                    $filename = $this->request->data['fileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['fileupload'] = $filename; 
                                    }
                                } 
                                else
                                {
                                    $filename = "";
                                }
                            }
                        
                            if(!empty($filename))
                            {
                                $exams_ass = $exams_assessment_table->newEntity();
                                
                                $exams_ass->title =  $this->request->data('title')  ;
                                $exams_ass->class_id =  $this->request->data('class')  ;
                                $exams_ass->subject_id =  $this->request->data('subjects')  ;
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
                                $exams_ass->session_id =  $session_id ;
                                $exams_ass->status =  1  ;
                             
                                $start_date =  strtotime($this->request->data('start_date'));
                                $end_date =  strtotime($this->request->data('end_date'));
                                
                                if($start_date < $end_date)
                                {
                                    //print_r($exams_ass); die;
                                    if($saved = $exams_assessment_table->save($exams_ass) )
                                    {   
                                        $examid = $saved->id;
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
                                    $filename = $this->request->data['fileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['fileupload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['fileupload'] = $filename; 
                                    }
                                } 
                                else
                                {
                                    $filename = "";
                                }
                            }
                        
                            if(!empty($filename))
                            {
                                $exams_ass = $exams_assessment_table->newEntity();
                                
                                $exams_ass->class_id =  $this->request->data('class')  ;
                                $exams_ass->subject_id =  $this->request->data('subjects')  ;
                                $exams_ass->title =  $this->request->data('title');
                                $exams_ass->special_instruction =  $this->request->data('instruction');
                                $exams_ass->start_date =  $this->request->data('start_date');
                                $exams_ass->end_date =  $this->request->data('end_date');
                                $exams_ass->type =  $this->request->data('request_for');
                                $exams_ass->exam_type =  $this->request->data('exam_type');
                                $exams_ass->max_marks =  $this->request->data('max_marks');
                                $exams_ass->created_date =  time()  ;
                                $exams_ass->file_name =  $filename ;
                                $exams_ass->teacher_id =  $tid ;
                                $exams_ass->school_id =  $school_id ;
                                $exams_ass->exam_format= $this->request->data('contentupload');
                                $exams_ass->session_id =  $session_id ;
                                $exams_ass->show_exmfrmt = "pdf";
                                $type =  $this->request->data('request_for');
                                if($type == "Exams")
                                {
                                    $exams_ass->status =  0  ;
                                }
                                else
                                {
                                    $exams_ass->status =  1  ;
                                }
                                $maxmarks =  $this->request->data('max_marks');
                                $start_date =  strtotime($this->request->data('start_date'));
                                $end_date =  strtotime($this->request->data('end_date'));
                                if($maxmarks > 0)
                                {
                                    if($start_date < $end_date)
                                    {
                                        //print_r($exams_ass); die;
                                        if($saved = $exams_assessment_table->save($exams_ass) )
                                        {   
                                            $examid = $saved->id;
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
                                $res = [ 'result' => $opa  ];
                            }  
                            
                        }
                        else
                        {
                            $exams_ass = $exams_assessment_table->newEntity();
                            $exams_ass->class_id =  $this->request->data('class')  ;
                            $exams_ass->subject_id =  $this->request->data('subjects')  ;
                            $exams_ass->title =  $this->request->data('title');
                            $exams_ass->special_instruction =  $this->request->data('instruction');
                            $exams_ass->start_date =  $this->request->data('start_date');
                            $exams_ass->end_date =  $this->request->data('end_date');
                            $exams_ass->type =  $this->request->data('request_for');
                            $exams_ass->exam_type =  $this->request->data('exam_type');
                            $exams_ass->max_marks =  $this->request->data('max_marks');
                            $exams_ass->created_date =  time()  ;
                            //$exams_ass->file_name =  $filename ;
                            $exams_ass->teacher_id =  $tid ;
                            $exams_ass->school_id =  $school_id ;
                            $exams_ass->exam_format= $this->request->data('contentupload');
                            $exams_ass->session_id =  $session_id ;
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
                            }
                            else
                            {
                                $exams_ass->status =  1  ;
                            }
                            $max_marks =  $this->request->data('max_marks');
                            $start_date =  strtotime($this->request->data('start_date'));
                            $end_date =  strtotime($this->request->data('end_date'));
                            if($max_marks > 0)
                            {
                                if($start_date < $end_date)
                                {
                                    if($saved = $exams_assessment_table->save($exams_ass) )
                                    {   
                                        $examid = $saved->id;
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Exam Assessment Created"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($saved->id)   ;
                                        $activity->origin = $this->Cookie->read('tid')   ;
                                        $activity->created = strtotime('now');
                                        if($saved = $activ_table->save($activity) ){
                                            $res = [ 'result' => 'success' , 'submitId' => $examid, 'type' => 'customize' ];
                
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                $retrieve_cls = $cls_table->find()->where([ 'id' => $get_examass_data[0]['class_id'] ])->toArray();
                $retrieve_sub = $sub_table->find()->where([ 'id' => $get_examass_data [0]['subject_id'] ])->toArray();
                
                $get_data = $ques_table->find()->where(['exam_id' => $id])->toArray(); 
                //return $this->json($get_data);
                $this->set("questiondetails", $get_data);
                $this->set("datadetails", $get_examass_data);
                $this->set("subjectdtl", $retrieve_sub);
                $this->set("classdtl", $retrieve_cls);
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
                    $exam_id =  $this->request->data('submitId')  ;
                    
                    $get_maxmarks = $exams_assessment_table->find()->select(['max_marks'])->where(['id' => $exam_id])->toArray(); 
                    $maxMarks = $get_maxmarks[0]['max_marks'];
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
                        $totl = 0;
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
                    
                    if($totl <= $maxMarks )
                    {
                        $cus_que = $custmze_que_table->newEntity();
                        
                        $cus_que->exam_id =  $this->request->data('submitId')  ;
                        $cus_que->marks =  $this->request->data('marks')  ;
                        $cus_que->question =  $this->request->data('question')  ;
                        $cus_que->ques_type =  $this->request->data('optionques')  ;
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
                                if($saved = $custmze_que_table->save($cus_que) )
                                {   
                                    if($totl == 0)
                                    {
                                        $totl = $this->request->data('marks');
                                    }
                                    $res = [ 'result' => 'success', 'max_marks' =>  $maxMarks, 'allocate' => $totl];
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
                    else
                    {
                         $res = [ 'result' => $qmemm  , 'max_marks' =>  $maxMarks, 'allocate' => $totl ];
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
                    $exam_id =  $this->request->data('examid')  ;
                    $que_id =  $this->request->data('queid')  ;
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
                        $totl = 0;
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
                    
                    if($totl <= $maxMarks )
                    {
                        $que_id =  $this->request->data('queid')  ;
                        $exam_id =  $this->request->data('examid')  ;
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
                                    $update = $custmze_que_table->query()->update()->set(['question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'options' => $options, 'created_date' => $created_date])->where([ 'id' => $que_id  ])->execute();
                                }
                                else
                                {
                                    $update = $custmze_que_table->query()->update()->set(['max_words' => $max_words, 'question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'created_date' => $created_date])->where([ 'id' => $que_id  ])->execute();
                                }
                                        
                                if($update)
                                {   
                                    $retrieve_ques = $custmze_que_table->find()->where(['exam_id' => $exam_id, 'id >' => $que_id])->order(['id' => 'ASC'])->limit(1)->toArray() ;
                                    $res = [ 'result' => 'success', 'question' =>  $retrieve_ques ];
                                }
                                else{
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
                $eaid = $ques_table->find()->select(['id'])->where(['id'=> $sid ])->first();
				
                if($eaid)
                {   
                    $del = $ques_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
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
                $clsid = $this->request->data('clsid');
                $subid = $this->request->data('subid');
                $subject_table = TableRegistry::get('subjects');
                $empcls_table = TableRegistry::get('employee_class_subjects');
                $teacherid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_sub = $empcls_table->find()->select(['subjects.subject_name', 'subjects.id'])->join(
	                [
			            'subjects' => 
                        [
                            'table' => 'subjects',
                            'type' => 'LEFT',
                            'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                        ]

                    ])->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id' => $clsid ])->toArray();
                    
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
                    
                $subjects['subjectname'] = $data;
                return $this->json($subjects);
				
            }

            

}

  


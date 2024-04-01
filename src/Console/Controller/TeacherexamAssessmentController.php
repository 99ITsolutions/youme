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
use Dompdf\Dompdf;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TeacherexamAssessmentController  extends AppController
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
                $tid = $this->Cookie->read('tid');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $subjects_table = TableRegistry::get('subjects');
                $employee_table = TableRegistry::get('employee');
                $class_table = TableRegistry::get('class');
                $que_table = TableRegistry::get('exam_ass_question');
                if(!empty($tid)) {
    				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
                    $retrieve_examsassessment = $exams_assessment_table->find()->where(['teacher_id'=> $retrieve_employees['id'] ])->toArray() ; 
                   	
    				foreach($retrieve_examsassessment as $key =>$approvecoll)
            		{
            			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examsassessment[$key]['subject_id'] ])->toArray();
            			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
            			
            			$retrieve_class = $class_table->find()->select(['c_name', 'c_section', 'school_sections'])->where(['id' => $retrieve_examsassessment[$key]['class_id']  ])->toArray();
            			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section']." (".$retrieve_class[0]['school_sections'].")";
            			
            			if(!empty($retrieve_examsassessment[$key]['teacher_id']))
            			{
                			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examsassessment[$key]['teacher_id']  ])->toArray();
                			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']." ".$retrieve_employee[0]['l_name'];
            			}
            			else
            			{
            			    $approvecoll->teacher_name = "School";
            			}
            			
            			if($retrieve_examsassessment[$key]['exam_format'] == "custom")
            			{
            			    $retrieve_quecount = $que_table->find()->where(['exam_id' => $retrieve_examsassessment[$key]['id']  ])->count();
            			    $approvecoll->addquestion = $retrieve_quecount;
            			}
            			else
            			{
            			    $approvecoll->addquestion = 1;
            			}
            		}
            		
            		$empclssub_table = TableRegistry::get('employee_class_subjects');
    				$retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section', 'class.school_sections', 'class.id'])->join(
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
                            
        
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['class.id'])->toArray();
            	
    
    				$this->set("employees_details", $retrieve_employees); 
    				$this->set("empcls_details", $retrieve_empclses);
    	            $this->set("approvedetails", $retrieve_examsassessment);
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            
            public function exmassadd()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $schoolid = $this->Cookie->read('id');
                    $company_table = TableRegistry::get('company');
                    
                    $retrieve_schoolid = $company_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                    
                    $school_id = $retrieve_schoolid[0]['id'];
                    /*$tid =  $retrieve_schoolid[0]['id'];*/
                    $tid = "";
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
                        $exams_ass->title =  $this->request->data('title')  ;
                        $exams_ass->special_instruction =  $this->request->data('instruction');
                        $exams_ass->start_date =  $this->request->data('start_date');
                        $exams_ass->end_date =  $this->request->data('end_date');
                        $exams_ass->type =  $this->request->data('request_for');
                        $exams_ass->exam_type =  $this->request->data('exam_type');
                        $exams_ass->status =  0  ;
                        $exams_ass->created_date =  time()  ;
                        $exams_ass->file_name =  $filename ;
                        $exams_ass->teacher_id =  $tid ;
                        $exams_ass->school_id =  $school_id ;
                        
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
                                $res = [ 'result' => 'success'  ];
    
                            }
                            else{
                                $res = [ 'result' => 'activity not saved'  ];
                            }
                        }
                        else{
                            $res = [ 'result' => 'Exam/Assessment not saved'  ];
                        }
                        
                    }
                    else
                    { 
                        $res = [ 'result' => 'Only Pdf allowed'  ];
                    }  
                    
                    
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
			
			public function edit()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['id'];
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $get_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                    return $this->json($get_data);
                }  
            }

            public function exmassedit()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $teacherid = $this->Cookie->read('tid');
                    $custm_que_tbl = TableRegistry::get('exam_ass_question');
                    $employee_table = TableRegistry::get('employee');
                    
                    if(!empty($teacherid))
                    {
                    $retrieve_schoolid = $employee_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $teacherid])->toArray() ;
                    
                    $school_id = $retrieve_schoolid[0]['school_id'];
                    $tid =  $retrieve_schoolid[0]['id'];
                    $rqst = $this->request->data('erequest_for');
                    if($rqst == "Study Guide")
                    {
                        if(!empty($this->request->data['efileupload']))
                        {   
                            if($this->request->data['efileupload']['type'] == "application/pdf"  )
                            {
                                //$picture =  $this->request->data['fileupload']['name'];
                                $filename = $this->request->data['efileupload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efileupload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efileupload'] = $filename; 
                                }
                            } 
                            else
                            {
                                $filename = "";
                            }
                        }
                        else
                        {
                           $filename = $this->request->data('pre_file');
                        }
                        
                        if(!empty($filename))
                        {
                            //$exams_ass = $exams_assessment_table->newEntity();
                            
                            $class_id =  $this->request->data('eclass')  ;
                            $subject_id =  $this->request->data('esubjects')  ;
                            $title =  $this->request->data('etitle');
                            //$special_instruction =  $this->request->data('einstruction');
                            $start_date =  $this->request->data('estart_date');
                            $end_date =  $this->request->data('eend_date');
                            $type =  $this->request->data('erequest_for');
                           
                            $created_date =  time()  ;
                            $file_name =  $filename ;
 
                            $exam_assid =  $this->request->data('exam_assid');
                            $contentupload ="study_guide";
                            $status = 1  ;
                                
                                    
                            $st = strtotime($this->request->data('estart_date'));
                            $et =  strtotime($this->request->data('eend_date'));
                            if($st < $et)
                            {
                                $update = $exams_assessment_table->query()->update()->set(['title' => $title, 'exam_format' => $contentupload,'school_id' => $school_id, 'created_date' => $created_date , 'file_name' => $file_name , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                //print_r($exams_ass); die;
                                if($update)
                                {   
                                    $examid = $exam_assid;
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Exam Assessment Updated"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($examid)   ;
                                    $activity->origin = $this->Cookie->read('id')   ;
                                    $activity->created = strtotime('now');
                                    if($saved = $activ_table->save($activity) ){
                                        $res = [ 'result' => 'success' , 'type' => 'pdf' ];
            
                                    }
                                    else{
                                        $res = [ 'result' => 'activity not saved'  ];
                                    }
                                }
                                else{
                                    $res = [ 'result' => 'Exam/Assessment not saved'  ];
                                }
                            }
                            else
                            {
                                 $res = [ 'result' => 'Start date/time Should be less than End date/time'  ];
                            }
                                
                        }
                        else
                        {
                            $res = [ 'result' => 'Only Pdf allowed'  ];
                        }
                    }
                    else
                    {
                        if($this->request->data('econtentupload') == "pdf")
                        {
                        
                            if(!empty($this->request->data['efileupload']))
                            {   
                                if($this->request->data['efileupload']['type'] == "application/pdf"  )
                                {
                                    //$picture =  $this->request->data['fileupload']['name'];
                                    $filename = $this->request->data['efileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['efileupload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['efileupload'] = $filename; 
                                    }
                                } 
                                else
                                {
                                    $filename = "";
                                }
                            }
                            else
                            {
                               $filename = $this->request->data('pre_file');
                            }
                        
                            if(!empty($filename))
                            {
                                //$exams_ass = $exams_assessment_table->newEntity();
                                
                                $class_id =  $this->request->data('eclass')  ;
                                $subject_id =  $this->request->data('esubjects')  ;
                                $title =  $this->request->data('etitle');
                                
                                $special_instruction =  $this->request->data('einstruction');
                                $start_date =  $this->request->data('estart_date');
                                $end_date =  $this->request->data('eend_date');
                                $type =  $this->request->data('erequest_for');
                                $exam_type =  $this->request->data('eexam_type');
                                $emax_marks =  $this->request->data('emax_marks');
                                
                                $created_date =  time()  ;
                                $file_name =  $filename ;
                                //$exams_ass->teacher_id =  $tid ;
                                $school_id =  $school_id ;
                                $exam_assid =  $this->request->data('exam_assid');
                                $contentupload =$this->request->data('econtentupload');
                                $show_exmfrmt = "pdf";
                                if($type == "Exams")
                                {
                                    $status =  0  ;
                                }
                                else
                                {
                                    $status = 1  ;
                                }
                                if($emax_marks > 0)
                                {
                                    
                                    $st = strtotime($this->request->data('estart_date'));
                                    $et =  strtotime($this->request->data('eend_date'));
                                    if($st < $et)
                                    {
                                        $update = $exams_assessment_table->query()->update()->set(['title' => $title, 'show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload,'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date , 'file_name' => $file_name , 'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction,  'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                        //print_r($exams_ass); die;
                                        if($update)
                                        {   
                                            $examid = $exam_assid;
                                            $activity = $activ_table->newEntity();
                                            $activity->action =  "Exam Assessment Updated"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($examid)   ;
                                            $activity->origin = $this->Cookie->read('id')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity) ){
                                                $res = [ 'result' => 'success' , 'type' => 'pdf' ];
                    
                                            }
                                            else{
                                                $res = [ 'result' => 'activity not saved'  ];
                                            }
                                        }
                                        else{
                                            $res = [ 'result' => 'Exam/Assessment not saved'  ];
                                        }
                                    }
                                    else
                                    {
                                         $res = [ 'result' => 'Start date/time Should be less than End date/time'  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Maximum Marks should not be negative.'  ];
                                }
                            }
                            else
                            {
                                
                                $res = [ 'result' => 'Only Pdf allowed'  ];
                            }
                            
                            
                        }
                        else
                        {   
                            $class_id =  $this->request->data('eclass')  ;
                            $subject_id =  $this->request->data('esubjects')  ;
                            $title =  $this->request->data('etitle');
                            $special_instruction =  $this->request->data('einstruction');
                            $start_date =  $this->request->data('estart_date');
                            $end_date =  $this->request->data('eend_date');
                            $type =  $this->request->data('erequest_for');
                            $exam_type =  $this->request->data('eexam_type');
                            $emax_marks =  $this->request->data('emax_marks');
                            
                            $created_date =  time()  ;
                            $school_id =  $school_id ;
                            $exam_assid =  $this->request->data('exam_assid');
                            $contentupload =$this->request->data('econtentupload');
                            
                            if($type == "Exams")
                            {
                                $status =  0  ;
                            }
                            else
                            {
                                $status = 1  ;
                            }
                            if($this->request->data('erequest_for') == "Exams")
                            {
                                $show_exmfrmt = $this->request->data['exam_format'];
                            }
                            else
                            {
                                $show_exmfrmt = "pdf";
                            }
                            if($emax_marks > 0)
                            {
                                $strt = strtotime($this->request->data('estart_date'));
                                $et =  strtotime($this->request->data('eend_date'));
                                if($strt < $et)
                                {
                                    $update = $exams_assessment_table->query()->update()->set(['title' => $title, 'show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload, 'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date ,  'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction, 'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                    if($update)
                                    {   
                                        $examid = $exam_assid;
                                        
                                        $retrieve_ques = $custm_que_tbl->find()->where(['exam_id' => $examid])->order(['id' => 'ASC'])->limit(1)->toArray() ;
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Exam Assessment Updated"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($examid)   ;
                                        $activity->origin = $this->Cookie->read('id')   ;
                                        $activity->created = strtotime('now');
                                        if($saved = $activ_table->save($activity) ){
                                            $res = [ 'result' => 'success' , 'type' => 'customize', 'submtID' => $examid , 'question' => $retrieve_ques];
                
                                        }
                                        else{
                                            $res = [ 'result' => 'activity not saved'  ];
                                        }
                                    }
                                    else{
                                        $res = [ 'result' => 'Exam/Assessment not saved'  ];
                                    }
                                }
                                else
                                {
                                     $res = [ 'result' => 'Start date/time Should be less than End date/time'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'Maximum Marks should not be negative.'  ];
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
                return $this->json($res);
			}
			
			public function pdf($exmid =null)
            {
                
                $que_table = TableRegistry::get('exam_ass_question');
                $exam_table = TableRegistry::get('exams_assessments');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $school_table = TableRegistry::get('company');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_exam = $exam_table->find()->where([ 'id' => $exmid ])->toArray();
                $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam[0]['class_id'] ])->toArray();
                $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam[0]['subject_id'] ])->toArray();
                $retrieve_questions = $que_table->find()->where([ 'exam_id' => $exmid ])->toArray();
                $schoolid = $retrieve_exam[0]['school_id'];
                $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
                $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
			    $n =1;
			    $questns = "";
			    foreach($retrieve_questions as $ques)
			    {
			        $questns .= '<tr><td style="width: 100%;"><table style="width: 100%; "><tr>';
			        
                	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px; font-style:normal; font-weight:16px !important; margin-bottom:30px !important;">';
                	
                	$questns .= "<div  style='margin-left:20px; ' >
                	<span id='ques' style=' width:95%;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
                	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
                	
			        if($ques['ques_type'] == "objective")
			        {
			            $questns .= "<div style='margin:10px 0;'>";
			            $options = explode("~^", $ques['options']);
			            $m = 1;
			            foreach($options as $opt)
			            {
			                $questns .= "<span style='margin-left:25px;'>".$m.") <span id='options'>".$opt."</span></span>";
			                $m++;
			            }
			            $questns .= "</div>";
			            
			        };
                	$questns .= "</div>";
			            
                	$questns .= '</th>';
                	
                	$questns .= "</tr></table</td></tr>";
                	
			        
			        
			        $n++;
			    }
			    $header = '<table style=" width: 100%;">
                		    <tbody>
                			    <tr>
                			        <td  style="width: 100%;">
                			            <table style="width: 100%;  ">
                    			        <tr>
                            			    <td  style="width: 30%; float:left; ">
                            			        <table style="width: 100%;  ">
                            			            <tr>
                            						    <th style="width: 100%; text-align:center;"><span> '.$school_logo.' </span></th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($retrieve_school[0]['comp_name']).'</th>
                            						</tr>
                            						
                            						<tr>
                        						        <th></th>
                        						    </tr>
                            					</table>
                            			    </td>
                            				<td style="width: 33%; float:left; text-align:center;">
                            					<table style="width: 100%;  ">
                            						<tr>
                            						    <th style="width: 100%; text-align:center;  font-size: 14px;"></th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>Subject: </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                            						</tr>
                            						<tr>
                        						        <th style=" font-size: 14px;"> <span><b>Class: </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'].' ( '.$retrieve_cls[0]['school_sections'].')</span></th>
                        						    </tr>
                            					</table>
                            				</td>
                            				<td style="width: 37%;  float:left; text-align:right;">
                            					<table style="width: 100%;  ">
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>Maximum Marks: </span>'.$retrieve_exam[0]['max_marks'].'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>Start Date/Time: </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>End Date/Time: </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
                            						</tr>
                            					</table>
                            				</td>
                			            </tr>
                			            </table>
                			        </td>
                			</tr>
                			<tr>
                    			<td style="width: 100%;">
                    			    <table style="width: 100%;">
                						<tr>
                						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;">

                                             <span> Title: </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['title']).'</span><br><br>

                                            <span> Special Instructions: </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span>

                                            </th>
                						</tr>
                					</table>
                    			</td>
                			</tr>
                			'.$questns.'
                		</tbody>
                		</table>';
			    
			    $title = $retrieve_exam[0]['title'];
			    
			    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
			    //print_r($viewpdf); die;
			    $dompdf = new Dompdf();
        		$dompdf->loadHtml($viewpdf);	
        		
        		$dompdf->setPaper('A4', 'Portrait');
        		$dompdf->render();
        		$dompdf->stream($title.".pdf", array("Attachment" => false));

                exit(0);
            }

            public function delete()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sid = (int) $this->request->data('val') ; 
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $activ_table = TableRegistry::get('activity');
                $custmze_que_table = TableRegistry::get('exam_ass_question');
                $eaid = $exams_assessment_table->find()->select(['id'])->where(['id'=> $sid ])->first();
				
                if($eaid)
                {   
                    $del = $exams_assessment_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                    if($del)
                    { 
                        $del_que = $custmze_que_table->query()->delete()->where([ 'exam_id' => $sid ])->execute(); 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Exam assignment Deleted"  ;
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
            
            public function addQuestion($id)
            {  
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                $retrieve_cls = $cls_table->find()->where([ 'id' => $get_examass_data[0]['class_id'] ])->toArray();
                $retrieve_sub = $sub_table->find()->where([ 'id' => $get_examass_data [0]['subject_id'] ])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
            
            
            public function editquestion()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $queid = $this->request->data('queid');
                $ques_table = TableRegistry::get('exam_ass_question');
                $get_data = $ques_table->find()->where(['id' => $queid])->toArray(); 
                $get_marks = $ques_table->find()->where(['exam_id' => $get_data[0]['exam_id']])->toArray(); 
                foreach($get_marks as $datas)
                {
                    $marks[] = $datas['marks'];
                }
                $totalAllo = array_sum($marks);
                $data['getdata'] = $get_data;
                $data['alloc'] = $totalAllo;
                return $this->json($data);
            }
			
			public function addcutsomizeexm()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $schoolid = $this->Cookie->read('id');
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
                        
                        $marks =  $this->request->data('marks')  ;
                        $maxwords = '';
                        if(!empty($this->request->data['maxwords']))
                        {
                            $maxwords = $this->request->data('maxwords')  ;
                            $cus_que->max_words =  $this->request->data('maxwords')  ;
                        }
                        
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
                                    $res = [ 'result' => 'Question Exam/Assessment not saved'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'Words count should not be in negative.'  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Marks should not be negative.'  ];
                        }
                
                    }
                    else
                    {
                         $res = [ 'result' => 'Question Marks Exceeded Maximum Marks '  ];
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
			
			public function edcustmque()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $schoolid = $this->Cookie->read('id');
                    $company_table = TableRegistry::get('company');
                    $exam_id =  $this->request->data('examid')  ;
                    $que_id =  $this->request->data('queid')  ;
                    
                    
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
                            if($max_words > 0 || empty($max_words)) 
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
                                    $res = [ 'result' => 'Question Exam/Assessment not updated'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'Word count should not be negative'];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Marks should not be negative.'  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Question Marks Exceeded Maximum Marks '  ];
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
            

            

            
            
}

  




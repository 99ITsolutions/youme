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
class ExamAssessmentController  extends AppController
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
                $schoolid = md5($this->request->session()->read('company_id'));
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $que_table = TableRegistry::get('exam_ass_question');
                $subjects_table = TableRegistry::get('subjects');
                $employee_table = TableRegistry::get('employee');
                $class_table = TableRegistry::get('class');
                $retrieve_examsassessment = $exams_assessment_table->find()->where(['md5(school_id)'=> $schoolid ])->order(['id' => 'DESC'])->toArray() ; 
                $retrieve_classes = $class_table->find()->where(['md5(school_id)' => $schoolid , 'active' => 1 ])->toArray();
                $retrieve_subjects = $subjects_table->find()->where(['md5(school_id)' => $schoolid , 'status' => 1 ])->toArray();
				$this->request->session()->write('LAST_ACTIVE_TIME', time());	
				foreach($retrieve_examsassessment as $key =>$approvecoll)
        		{
        			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examsassessment[$key]['subject_id'] ])->toArray();
        			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
        			
        			$retrieve_class = $class_table->find()->select(['c_name', 'c_section', 'school_sections'])->where(['id' => $retrieve_examsassessment[$key]['class_id']  ])->first();
        			$approvecoll->class_name = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (".$retrieve_class['school_sections'].")";
        			
        			if(!empty($retrieve_examsassessment[$key]['teacher_id']))
        			{
            			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examsassessment[$key]['teacher_id']  ])->toArray();
            			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']." ".$retrieve_employee[0]['l_name'];
        			}
        			else
        			{
        			    $approvecoll->teacher_name = "School";
        			}
        			
        			if(empty($retrieve_examsassessment[$key]['file_name']))
        			{
        			    $exmid = $retrieve_examsassessment[$key]['id'];
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
        		
					
				$this->set("classDetails", $retrieve_classes);
				$this->set("subjectDetails", $retrieve_subjects);
	            $this->set("approvedetails", $retrieve_examsassessment);
                $this->viewBuilder()->setLayout('user');
            }
            
            public function pdf($exmid =null)
            {
                $lang = $this->Cookie->read('language');
                if($lang != "") { 
                    $lang = $lang; 
                } else { 
                    $lang = 2; 
                    
                }
                //echo $lang; die;
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
			            $questns .= "<div style='margin:7px 0;'>";
			            $options = explode("~^", $ques['options']);
			            $m = 1;
			            $chckbox = "<input type='radio' style='margin-left:25px; padding-top:7px;'>";
			            foreach($options as $opt)
			            {
			                $questns .= "<div>".$chckbox." <span id='options' style='margin-left:5px;'>".$opt."</span></div>";
			                $m++;
			            }
			            $questns .= "</div>";
			            
			        };
                	$questns .= "</div>";
			            
                	$questns .= '</th>';
                	
                	$questns .= "</tr></table</td></tr>";
                	
			        
			        
			        $n++;
			    }
			    foreach($retrieve_langlabel as $langlbl) { 
                    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
                    
                    if($langlbl['id'] == '1018') { $yerlylbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1800') { $weklylbl = $langlbl['title'] ; } 
                    
                    if($langlbl['id'] == '2024') { $spclinstlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '388') { $titlelbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '365') { $sublbl = $langlbl['title'] ; }
                    if($langlbl['id'] == '371') { $maxmrklbl = $langlbl['title'] ; } 
                    
                    if($langlbl['id'] == '2025') { $stdttymlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2026') { $endtymlbl = $langlbl['title'] ; }
                } 
                
                
                if( $retrieve_exam[0]['exam_type'] == "Quarterly" ) { 
                    $typexm = $quatrlbl; 
                }
                elseif( $retrieve_exam[0]['exam_type'] == "Monthly" ) { 
                    $typexm = $mnthlbl; 
                }
                elseif( $retrieve_exam[0]['exam_type'] == "Weekly" ) { 
                    $typexm = $weklylbl; 
                } 
                elseif( $retrieve_exam[0]['exam_type'] == "Yearly" ) { 
                    $typexm = $yerlylbl; 
                }
                else { 
                    $typexm =  $hlfyrlbl ;
                } 

                if( $retrieve_exam[0]['type'] == "Exams" ) { 
                    $type = $exmlbl; 
                }
                elseif( $retrieve_exam[0]['type'] == "Quiz" ) { 
                    $type = $quizlbl; 
                }
                elseif( $retrieve_exam[0]['type'] == "Assessment" ) { 
                    $type = $asslbl; 
                }
                else { 
                    $type =  $studgulbl ;
                } 
			    
			    /*if($retrieve_exam[0]['type'] == "Assessment")
			    {
			        $type = "Assignment";
			    }
			    else
			    {
			        $type = $retrieve_exam[0]['type'];
			    }*/
			    
			    if($retrieve_exam[0]['type'] == "Exams")
			    {
			        $extype = "(".$typexm.")";
			    }
			    else
			    {
			        $extype = "";
			    }
			    $type_request = $type." ".$extype;
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
                            						    <th style="width: 100%; text-align:center;  font-size: 16px;">'.$type_request .'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>'.$sublbl.': </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                            						</tr>
                            						<tr>
                        						        <th style=" font-size: 14px;">
                        						            <span><b>'.$clslbl.': </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'</span>
                        						            
                        						        </th>
                        						    </tr>
                            					</table>
                            				</td>
                            				<td style="width: 37%;  float:left; text-align:right;">
                            					<table style="width: 100%;  ">
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$maxmrklbl.': </span>'.$retrieve_exam[0]['max_marks'].'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$stdttymlbl.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                            						</tr>
                            						<tr>
                            						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$endtymlbl.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
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

                                                <span> '.$titlelbl.': </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['title']).'</span><br><br>

                                                <span> '.$spclinstlbl.': </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span>

                                                </th>
                						</tr>
                					</table>
                    			</td>
                			</tr>
                			'.$questns.'
                		</tbody>
                		</table>';
			    
			    $title = $retrieve_sub[0]['subject_name'];
			    
			    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
			    //print_r($viewpdf); die;
			    $dompdf = new Dompdf();
        		$dompdf->loadHtml($viewpdf);	
        		
        		$dompdf->setPaper('A4', 'Portrait');
        		$dompdf->render();
        		$dompdf->stream($title.".pdf", array("Attachment" => false));

                exit(0);
            }
            
            public function exmassadd()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $schoolid =  md5($this->request->session()->read('company_id'));
                    $company_table = TableRegistry::get('company');
                    $retrieve_schoolid = $company_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                    $session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                   
                    
                    $school_id = $retrieve_schoolid[0]['id'];
                    $tid = "";
                    
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
                                        $res = [ 'result' => 'Exam/Assessment not saved'  ];
                                    }
                                }
                                else
                                {
                                    $res = [ 'result' => 'Start date/time should not be greater than end date/time'  ];
                                }
                            }
                            else
                            { 
                                $res = [ 'result' => 'Only Pdf allowed'  ];
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
                            $exams_ass->title =  $this->request->data('title')  ;
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
                            $type = $this->request->data('request_for');
                            if($type == "Exams")
                            {
                                $exams_ass->status =  0  ;
                            }
                            else
                            {
                                $exams_ass->status =  1  ;
                            }
                            
                            //print_r($exams_ass); die;
                            
                            $maxmarks = $this->request->data('max_marks');
                            $start_date =  strtotime($this->request->data('start_date'));
                            $end_date =  strtotime($this->request->data('end_date'));
                            if($maxmarks > 0)
                            {
                                if($end_date > $start_date)
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
                                            $res = [ 'result' => 'success' , 'type' => 'pdf'  ];
                
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
                                     $res = [ 'result' => 'End Date should be greater than Start Date. '  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'Maximum Marks should be greater than Zero. '  ];
                            }
                            
                        }
                        else
                        { 
                            $res = [ 'result' => 'Only Pdf allowed'  ];
                        }  
                        
                    }
                    else
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
                        $type = $this->request->data('request_for');
                        if($type == "Exams")
                        {
                            $exams_ass->status =  0  ;
                        }
                        else
                        {
                            $exams_ass->status =  1  ;
                        }
                        
                        
                        $maxmarks = $this->request->data('max_marks');
                        $start_date =  strtotime($this->request->data('start_date'));
                        $end_date =  strtotime($this->request->data('end_date'));
                        if($maxmarks > 0)
                        {
                            if($end_date > $start_date)
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
                                    $res = [ 'result' => 'Exam/Assessment not saved'  ];
                                }
                            }
                            else
                            {
                                 $res = [ 'result' => 'End Date should be greater than Start Date. '  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Maximum Marks should be greater than Zero. '  ];
                        }
                    }
                    }
                }
                else
                {
                    $res = ['result' => 'invalid operation'];
                }
                return $this->json($res);
			}
			
			public function addcutsomizeexm()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $schoolid =  md5($this->request->session()->read('company_id'));
                    $company_table = TableRegistry::get('company');
                    $exam_id =  $this->request->data('submitId')  ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        $maxwords = '';
                        if(!empty($this->request->data['maxwords']))
                        {
                            $maxwords = $this->request->data('maxwords')  ;
                            $cus_que->max_words =  $this->request->data('maxwords')  ;
                        }
                        
                        $cus_que->created_date =  time();
                        $marks =  $this->request->data('marks');
                        
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
                            $res = [ 'result' => 'Marks should not be in negative.'  ];
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
                
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                
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
			
			public function edit()
            {   
                if($this->request->is('post'))
                {
                    $id = $this->request->data['id'];
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $subject_table = TableRegistry::get('subjects');
                    $get_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    foreach($get_data as $data)
                    {
                         $get_subjectnam = $subject_table->find()->where(['id' => $data['subject_id'] ])->first(); 
                         
                         $data->subjname = $get_subjectnam['subject_name'];
                    }
                    
                    return $this->json($get_data);
                }  
            }
            
            public function tedit()
            {   
                if($this->request->is('post'))
                {
                    $id = $this->request->data['id'];
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $subject_table = TableRegistry::get('subjects');
                    $get_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $empcls_table = TableRegistry::get('employee_class_subjects');
                    $teacherid = $this->Cookie->read('tid');
                    $clsid = $get_data[0]['class_id'];
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
                    foreach($retrieve_sub as $subj)
                    {
                        $sel = '';
                        if($subj['subjects']['id'] == $get_data[0]['subject_id'])
                        {
                            $sel = "selected";
                        }
                        $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                    }
                        
                    $subjects['subjectname'] = $data;
                    $subjects['resultss'] = $data;
                    return $this->json($subjects);
                }  
            }
            
            public function exmassedit()
			{
			    if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $custm_que_tbl = TableRegistry::get('exam_ass_question');
                    $schoolid =  md5($this->request->session()->read('company_id'));
                    $company_table = TableRegistry::get('company');
                    $retrieve_schoolid = $company_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                    $school_id = $retrieve_schoolid[0]['id'];
                $this->request->session()->write('LAST_ACTIVE_TIME', time());    
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
                            $title =  $this->request->data('etitle')  ;
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
                                    $activity->origin =  md5($this->request->session()->read('company_id'))   ;
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
                            //$title =  $this->request->data('etitle')  ;
                            
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
                                 $status =  1  ;
                            }
                            
                            $sdate = strtotime($start_date);
                            $edate =  strtotime($end_date);
                            
                            if($emax_marks > 0)
                            {
                                if($edate > $sdate)
                                {
                                    $update = $exams_assessment_table->query()->update()->set(['show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload,'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date , 'file_name' => $file_name , 'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction,  'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                    //print_r($exams_ass); die;
                                    if($update)
                                    {   
                                        $examid = $exam_assid;
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Exam Assessment Updated"  ;
                                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    
                                        $activity->value = md5($examid)   ;
                                        $activity->origin =  md5($this->request->session()->read('company_id'))   ;
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
                                    $res = [ 'result' => 'End Date should be greater than Start Date. '  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'Maximum Marks should be greater than Zero.'  ];
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
                        //$title =  $this->request->data('etitle')  ;
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
                             $status =  1  ;
                        }
                        if($this->request->data('erequest_for') == "Exams")
                        {
                            $show_exmfrmt = $this->request->data['exam_format'];
                        }
                        else
                        {
                            $show_exmfrmt = "pdf";
                        }
                        $sdate = strtotime($start_date);
                        $edate =  strtotime($end_date);
                        
                        if($emax_marks > 0)
                        {
                            if($edate > $sdate)
                            {
                                $update = $exams_assessment_table->query()->update()->set(['$show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload, 'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date ,  'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction, 'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                if($update)
                                {   
                                    $examid = $exam_assid;
                                    
                                    $retrieve_ques = $custm_que_tbl->find()->where(['exam_id' => $examid])->order(['id' => 'ASC'])->limit(1)->toArray() ;
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Exam Assessment Updated"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($examid)   ;
                                    $activity->origin =  md5($this->request->session()->read('company_id'))   ;
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
                                $res = [ 'result' => 'End Date should be greater than Start Date. '  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Maximum Marks should be greater than Zero.'  ];
                        }
                    }  
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
                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $schoolid =  md5($this->request->session()->read('company_id'));
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
                    
                    if($totl <= $maxMarks )
                    {
                        $que_id =  $this->request->data('queid')  ;
                        $exam_id =  $this->request->data('examid')  ;
                        $marks =  $this->request->data('emarks')  ;
                        $question =  $this->request->data('equestion')  ;
                        $ques_type =  $this->request->data('eoptionques')  ;
                        $max_words =  $this->request->data('emaxwords')  ;
                        $created_date =  time();
                        if($max_words > 0 || empty($max_words)) {
                        if($marks > 0)
                        {
                            if(!empty($this->request->data['evalueques']))
                            {
                                $options = implode("~^", $this->request->data['evalueques']);
                                $update = $custmze_que_table->query()->update()->set(['max_words' => $max_words, 'question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'options' => $options, 'created_date' => $created_date])->where([ 'id' => $que_id  ])->execute();
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
                            $res = [ 'result' => 'Marks should not be negative'];
                        }
                        }
                        else
                        {
                            $res = [ 'result' => 'Word count should not be negative'];
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
            

            public function delete()
            {
                $sid = (int) $this->request->data('val') ; 
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $custmze_que_table = TableRegistry::get('exam_ass_question');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        $activity->origin =  md5($this->request->session()->read('company_id'))   ;
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
                        $activity->origin =  md5($this->request->session()->read('company_id'))   ;
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

            

            
            
}

  




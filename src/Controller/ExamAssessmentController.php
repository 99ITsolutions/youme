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
use \Imagick;
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
                $sessionid = $this->Cookie->read('sessionid');
                $retrieve_examsassessment = $exams_assessment_table->find()->where(['md5(school_id)'=> $schoolid , 'session_id' => $sessionid])->order(['id' => 'DESC'])->toArray() ; 
                $retrieve_classes = $class_table->find()->where(['md5(school_id)' => $schoolid , 'active' => 1 ])->toArray();
                $retrieve_subjects = $subjects_table->find()->where(['md5(school_id)' => $schoolid , 'status' => 1 ])->toArray();
				$this->request->session()->write('LAST_ACTIVE_TIME', time());	
				
				$sclsub_table = TableRegistry::get('school_subadmin');
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
				    $compid = $this->request->session()->read('company_id');
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
					$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
					
				}
				
				
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
        			
        			if($this->Cookie->read('logtype') == md5('School Subadmin'))
					{
					    //print_r($subpriv);
					    //echo $retrieve_class['school_sections']; die;
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
                        
                        if(in_array($clsmsg, $subpriv)) { 
                            $approvecoll->show = 1;
                        }
                        else
                        {
                            $approvecoll->show = 0;
                        }
					}
					else
					{
						$approvecoll->show = 1;
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
                $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
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
                    if($langlbl['id'] == '81') { $nomlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2025') { $stdttymlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2026') { $endtymlbl = $langlbl['title'] ; }
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
			        $extype = "(".$retrieve_exam[0]['exam_type'].")";
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
                        						    <tr>
                        						        <th style=" font-size: 14px;">
                        						            <span><b>'.$nomlbl.': </b></span><span>_________________</span>
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
			    
			    /*$dompdf = new Dompdf();
			    $options = $dompdf->getOptions();
        		$options->setIsHtml5ParserEnabled(true);
        		$options->set('isRemoteEnabled', true);
            	$dompdf->setOptions($options);
            	
        		$dompdf->loadHtml($viewpdf);	
        		
        		$dompdf->setPaper('A4', 'Portrait');
        		$dompdf->render();
        		$dompdf->stream($title.".pdf", array("Attachment" => false));*/
        		
        		$dompdf = new Dompdf();
        		$options = $dompdf->getOptions();
        		$options->set('isRemoteEnabled', true);
        		$dompdf->setOptions($options);
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
                    /*$lang = $this->Cookie->read('language');
                    if($lang != "") { 
                        $lang = $lang; 
                    } else { 
                        $lang = 2; 
                    }*/
                    $lang = 2;
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
                        if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
                        
    				}
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $schoolid =  md5($this->request->session()->read('company_id'));
                    $company_table = TableRegistry::get('company');
                    $retrieve_schoolid = $company_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                    $session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $submitexams_table = TableRegistry::get('submit_exams');
                    $sessionid = $this->Cookie->read('sessionid');
                    $student_table = TableRegistry::get('student');  
                    $notify_table = TableRegistry::get('notification'); 
                    $class_table = TableRegistry::get('class');  
                    $subject_table = TableRegistry::get('subjects');
                    $uniqueid = uniqid();
                    $school_id = $retrieve_schoolid[0]['id'];
                    $type = $this->request->data('request_for');
                    $tid = "";
                    $nopages = 0;
                    $dirname = "";
                    $rqst = $this->request->data('request_for');
                    
                    $studata =$this->request->data('student');
                    //print_r($studata);exit;
                    $type1 = $type == "Assessment" ? "Assignment" : $type;
                    /*$title = "New ". $type1 . " for ". $subname." has been generated";
                    $desc = "<p>Hi Students,</p>
                    <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('start_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                    <p>Thanks</p>";*/
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
                                $im = new Imagick();
                                $im->pingImage('img/'.$filename);
                                $nopages = $im->getNumberImages();
                                $dirname = "Ebook".time();
                            }
                            
                            if(!empty($filename))
                            {
                                $clsidss = $this->request->data['class'];
                                foreach ($clsidss as $cidss)
                                {
                                    $exams_ass = $exams_assessment_table->newEntity();
                                    
                                    $exams_ass->title =  $this->request->data('title')  ;
                                    $exams_ass->class_id = $cidss ;
                                    $exams_ass->uniqid = $uniqueid;
                                    $exams_ass->subject_id =  $this->request->data('subjects')  ;
                                    //$exams_ass->special_instruction =  $this->request->data('instruction');
                                    $exams_ass->start_date =  $this->request->data('start_date');
                                    $exams_ass->end_date =  $this->request->data('end_date');
                                    $exams_ass->type =  $this->request->data('request_for');
                                    $type = $this->request->data('request_for');
                                    /*$exams_ass->exam_type =  $this->request->data('exam_type');
                                    $exams_ass->max_marks =  $this->request->data('max_marks');*/
                                    $exams_ass->created_date =  time()  ;
                                    $exams_ass->file_name =  $filename ;
                                    $exams_ass->teacher_id =  $tid ;
                                    $exams_ass->school_id =  $school_id ;
                                    $exams_ass->exam_format= "study_guide";
                                    $exams_ass->session_id =  $session_id ;
                                    $exams_ass->status =  1  ;
                                    $exams_ass->numpages = $nopages;
                                    $exams_ass->dirname = $dirname;
                                    $exams_ass->session_id = $session_id;
                                    $status = 1;
                                    $start_date =  strtotime($this->request->data('start_date'));
                                    $end_date =  strtotime($this->request->data('end_date'));
                                
                                    if($start_date < $end_date)
                                    {
                                        //print_r($exams_ass); die;
                                        if($saved = $exams_assessment_table->save($exams_ass) )
                                        {   
                                            $examid = $saved->id;
                                            
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
                                            
                                            if($status == 1)
                                            {
                                                $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('subjects')])->first();
                                                
                                                $subname = $ret_sub['subject_name'];
                                                /************* Study guide ****************/
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
                                                $title = "New ". $typelang . " for ". $subname." has been generated";
                                                $desc = "<p>Hi Students,</p>
                                                <p>This is you to inform that ".$typelang." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('start_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                                                <p>Thanks</p>";
                                                date_default_timezone_set('Africa/Kinshasa');
                                                $notify = $notify_table->newEntity();
                                                $notify->title = $title;
                                                $notify->description = $desc;
                                                $notify->notify_to = 'students';
                                                $notify->created_date =  time();
                                                $notify->added_by = 'school';
                                                $notify->school_id = $school_id;
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
                                            $activity->action =  "Study Guide Created"  ;
                                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        
                                            $activity->value = md5($saved->id)   ;
                                            $activity->origin = $this->Cookie->read('tid')   ;
                                            $activity->created = strtotime('now');
                                            if($saved = $activ_table->save($activity))
                                            {
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
                                $clsidss = $this->request->data['class'];
                                foreach ($clsidss as $cidss)
                                {
                                    $exams_ass = $exams_assessment_table->newEntity();
                                    
                                    $exams_ass->uniqid = $uniqueid;
                                    $exams_ass->class_id = $cidss;
                                    
                                    $exams_ass->subject_id =  $this->request->data('subjects')  ;
                                    $exams_ass->title =  $this->request->data('title')  ;
                                    $exams_ass->special_instruction =  $this->request->data('instruction');
                                    $exams_ass->start_date =  $this->request->data('start_date');
                                    $exams_ass->end_date =  $this->request->data('end_date');
                                    $exams_ass->type =  $this->request->data('request_for');
                                    $exams_ass->exam_type =  $this->request->data('exam_type');
                                    $exams_ass->max_marks =  $this->request->data('max_marks');
                                    $exams_ass->exam_period =  $this->request->data('exam_period');
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
                                    $exams_ass->session_id =  $session_id ;
                                    $exams_ass->show_exmfrmt = "pdf";
                                    $type = $this->request->data('request_for');
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
                                    
                                    if($type == "Exams")
                                    {
                                        $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $this->request->data('subjects'), 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                    }
                                    else
                                    {
                                        $getexamsexist = [];
                                    }
                                
                                    if(empty($getexamsexist))
                                    {
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
                                                        $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('subjects')])->first();
                                                        
                                                        $subname = $ret_sub['subject_name'];
                                                        
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
                                                        /*$title = "New ". $type1 . " for ". $subname." has been generated";
                                                        $desc = "<p>Hi Students,</p>
                                                        <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('start_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                                                        <p>Thanks</p>";*/
                                                        
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
                                                        $notify->added_by = 'school';
                                                        $notify->school_id = $school_id;
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
                                        $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                    }
                                }
                            }
                            else
                            { 
                                $res = [ 'result' => 'Only Pdf allowed'  ];
                            }  
                            
                        }
                        else
                        {
                            $clsidss = $this->request->data['class'];
                            
                            
                            foreach ($clsidss as $cidss)
                            {
                                $exams_ass = $exams_assessment_table->newEntity();
                                $exams_ass->uniqid = $uniqueid;
                                $exams_ass->class_id = $cidss;
                                $exams_ass->subject_id =  $this->request->data('subjects')  ;
                                $exams_ass->title =  $this->request->data('title')  ;
                                $exams_ass->special_instruction =  $this->request->data('instruction');
                                $exams_ass->start_date =  $this->request->data('start_date');
                                $exams_ass->end_date =  $this->request->data('end_date');
                                $exams_ass->type =  $this->request->data('request_for');
                                $exams_ass->exam_type =  $this->request->data('exam_type');
                                $exams_ass->max_marks =  $this->request->data('max_marks');
                                $exams_ass->created_date =  time()  ;
                                $exams_ass->exam_period =  $this->request->data('exam_period');
                                //$exams_ass->file_name =  $filename ;
                                $exams_ass->session_id = $sessionid;
                                
                                if(!empty($studata)){
                                    $studata = implode(',',$studata);
                                }else{
                                    $studata = '';
                                }
                                    
                                $exams_ass->student_id =  $studata;   
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
                                    $status = 0;
                                }
                                else
                                {
                                    $exams_ass->status =  1  ;
                                    $status = 1;
                                }
                            
                                if($type == "Exams")
                                {
                                    $getexamsexist = $exams_assessment_table->find()->where(['class_id' => $cidss, 'subject_id' => $this->request->data('subjects'), 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('exam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                }
                                else
                                {
                                    $getexamsexist = [];
                                }

                                if(empty($getexamsexist))
                                {
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
//print_r($studata);exit;
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
                                                    $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('subjects')])->first();
                                                    
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
                                                    $notify->added_by = 'school';
                                                    $notify->school_id = $school_id;
                                                    $notify->status = '1';
                                                    
                                                    $notify->class_ids = $cidss;
                                                    $notify->class_opt = 'multiple';
                                                    $scdate = date("d-m-Y H:i",time());                             $notify->schedule_date = $scdate;
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
                $ids = array();
                if($this->request->data('examId') != ''){
                    $ids = explode(',',$this->request->data('examId'));
                    //print_r();exit;
                    $id =$ids[0];
                }else{
                   $id = $this->request->data('examId'); 
                }
                
                $ques_table = TableRegistry::get('exam_ass_question');
                $get_data = $ques_table->find()->where(['exam_id' => $id])->toArray(); 
                
                $ques_table = TableRegistry::get('exam_ass_question');
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $exams_assessment_table = TableRegistry::get('exams_assessments');
                $get_examass_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                //print_r($id);exit;
                $data = "";
                $datavalue = array();
                $total = [];
                foreach ($get_data as $value) 
				{
				    $alln_ids = $value['id'];
				    
				    if(!empty($ids) && count($ids) > 1){
				        $all_ids = array();
				        foreach($ids as $n_id){
				            $get_ids = $ques_table->find()->select(['id'])->where(['exam_id' => $n_id, 'question' => $value['question']])->first(); 
				            $all_ids[] = $get_ids['id'];
				        }
				        $alln_ids = implode(',',$all_ids);
				    }
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
                                <button type='button' data-id='". $alln_ids ."' class='btn btn-sm btn-outline-secondary ecutsomize_exam' data-toggle='modal'  data-target='#ecutsomize_exam' title='Edit'><i class='fa fa-edit'></i></button>
                                
                                <button type='button' data-id='".$alln_ids."' data-url='../../examAssessment/delete_question' class='btn btn-sm btn-outline-danger js-sweetalert ' title='Delete' data-str='Question' data-type='confirm'><i class='fa fa-trash-o'></i></button>
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
                $queids = explode(',',$this->request->data('queid'));
                $queid = $queids[0];
                $ques_table = TableRegistry::get('exam_ass_question');
                $get_data = $ques_table->find()->where(['id' => $queid])->toArray(); 
                $qdata [0] = array(
                    'id' => $get_data[0]['id'],
                    'question' => $get_data[0]['question'],
                    'exam_id' => $get_data[0]['exam_id'],
                    'ques_type' => $get_data[0]['ques_type'],
                    'options' => $get_data[0]['options'],
                    'max_words' => $get_data[0]['max_words'],
                    'marks' => $get_data[0]['marks'],
                    'created_date' => $get_data[0]['created_date'],
                    'uniqid' => $get_data[0]['uniqid'],
                    ); 
                if(count($queids > 1)){
                    $eid= array(); $qid = array();
                    foreach($queids as $a_qid){
                        $get_data2 = $ques_table->find()->select(['id', 'exam_id'])->where(['id' => $a_qid])->first(); 
                        $eid[] = $get_data2['exam_id'];
                        $qid[] = $get_data2['id'];
                    }
                    $qdata[0]['exam_id'] = implode(',', $eid);
                    $qdata[0]['id'] = implode(',', $qid);
                }
                
                $get_marks = $ques_table->find()->where(['exam_id' => $get_data[0]['exam_id']])->toArray(); 
                foreach($get_marks as $datas)
                {
                    $marks[] = $datas['marks'];
                }
                $totalAllo = array_sum($marks);
                $data['getdata'] = $qdata;
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
                    $student_table = TableRegistry::get('student');
                    $get_data = $exams_assessment_table->find()->where(['id' => $id])->toArray(); 
                    
                    $uniqid = $get_data[0]['uniqid'];
                    
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $data1 = '';
                    foreach($get_data as $data)
                    {
                         $get_subjectnam = $subject_table->find()->where(['id' => $data['subject_id'] ])->first(); 
                         $data->subjname = $get_subjectnam['subject_name'];
                       
                        if($data['student_id'] != ''){
                            $studata = explode(',',$data['student_id']);
                             
                            $retrieve_students = $student_table->find()->select(['id', 'l_name','f_name'])->where(['class'=> $data['class_id'] , 'session_id' => $data['session_id'] ])->toArray();
//print_r($studata[0]);exit; 
                            if($studata[0] != 'all'){
                				foreach($retrieve_students as $stdnt)
                				{
                				    $sel = ''; 
                				    
            				        if(in_array($stdnt['id'], $studata))
            				        {
            				            $sel = "selected";
            				        }
            				        $data1 .= '<option value="'.$stdnt['id'].'" '.$sel.' >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
                				}
                            }else{
                                $data1 .= '<option value="all" selected>All</option>';    
                                
                                foreach($retrieve_students as $stdnt)
                				{
                				    $sel = ''; 
                				    
            				        if(in_array($stdnt['id'], $studata))
            				        {
            				            $sel = "selected";
            				        }
            				        $data1 .= '<option value="'.$stdnt['id'].'" '.$sel.' >'.$stdnt['f_name'].' '.$stdnt['l_name'].'</option>';
                				}
                            }
                        }    
                    }
                    
                    $get_clsids = $exams_assessment_table->find()->where(['uniqid' => $uniqid])->toArray();
                    $clsids = [];
                    foreach($get_clsids as $clids)
                    {
                        $clsids[] = $clids['class_id'];
                    }
                    
                    $clsid = $get_data[0]['class_id'];
                    $cls_table = TableRegistry::get('class');
                    $get_periods = $cls_table->find()->where(['id' => $clsid])->first(); 
                    $clsname = $get_periods['c_name']."_".$get_periods['school_sections'];
                    
                    $tid = $this->Cookie->read('tid'); 
                    if(!empty($tid))
                    {
                        $compid = $this->request->session()->read('company_id');
                        $empclssub_table = TableRegistry::get('employee_class_subjects');
        				$retrieve_empclass = $empclssub_table->find()->where([ 'md5(emp_id)' => $tid ])->group(['class_id'])->toArray();
        				
        				$empcls = [];
        				foreach($retrieve_empclass as $ecls)
        				{
        				    $empcls[] = $ecls['class_id'];
        				}
    				    $cname = explode("_", $clsname);
    				
        				$retrieve_clssectns = $cls_table->find()->where([ 'c_name' => $cname[0], 'school_sections' => $cname[1], 'school_id' => $compid ])->toArray();
        				$sctnid = [];
        				foreach($retrieve_clssectns as $csctn)
        				{
        				    $sctnid[] = $csctn['id'];
        				}
        				
        				$common = array_intersect($sctnid, $empcls);
                    }
                    else
                    {
                        $compid = $this->request->session()->read('company_id');
                        $cname = explode("_", $clsname);
    				
        				$retrieve_clssectns = $cls_table->find()->where([ 'c_name' => $cname[0], 'school_sections' => $cname[1], 'school_id' => $compid ])->toArray();
        				$sctnid = [];
        				foreach($retrieve_clssectns as $csctn)
        				{
        				    $sctnid[] = $csctn['id'];
        				}
        				
        				$common = $sctnid;
                    }
        				
    				//print_r($common); die;
    				$retrieve_sectns = $cls_table->find()->where([ 'id IN' => $common ])->toArray();
    				$datasec = '<option value="">Choose Section</option>';
    				foreach($retrieve_sectns as $csctns)
    				{
    				    
    				    if(!empty($csctns['c_section']))
    				    {
    				        $datasec .= '<option value="'.$csctns['id'].'">'.$csctns['c_section'].'</option>';
    				    }
    				    else
    				    {
    				        $datasec .= '<option value="'.$csctns['id'].'">No section for this class</option>';
    				    }
    				}                                   
                    //print_r($datasec); die;
                    
                    $sectn_name = strtolower($get_periods['school_sections']);
                    $mestr = "";
                    if($sectn_name == "maternelle" || $sectn_name == "creche" || $sectn_name == "primaire")
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Trimestre'>Premier Trimestre</option>";
                        $mestr .= "<option value='Deuxieme Trimestre'>Deuxieme Trimestre</option>";
                        $mestr .= "<option value='Troisieme Trimestre'>Troisieme Trimestre</option>";
                    }
                    else
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Semestre'>Premier Semestre</option>";
                        $mestr .= "<option value='Second Semestre'>Second Semestre</option>";
                    }
                    
                    $exmprd = "";
                    if($get_data[0]['exam_type']  != "") {
                        if($get_data[0]['exam_type'] == "Premier Trimestre")
                        {
                            $exmprd .= "<option value='1ère Periode'>1ère Periode</option>";
                            $exmprd .= "<option value='2ème Periode'>2ème Periode</option>";
                        }
                        if($get_data[0]['exam_type'] == "Deuxieme Trimestre")
                        {
                            $exmprd .= "<option value='3ème Periode'>3ème Periode</option>";
                            $exmprd .= "<option value='4ème Periode'>4ème Periode</option>";
                        }
                        if($get_data[0]['exam_type'] == "Premier Semestre")
                        {
                            $exmprd .= "<option value='1ère Periode'>1ère Periode</option>";
                            $exmprd .= "<option value='2ème Periode'>2ème Periode</option>";
                        }
                        if($get_data[0]['exam_type'] == "Second Semestre")
                        {
                            $exmprd .= "<option value='3ème Periode'>3ème Periode</option>";
                            $exmprd .= "<option value='4ème Periode'>4ème Periode</option>";
                        }
                        if($get_data[0]['exam_type'] == "Troisieme Trimestre")
                        {
                            $exmprd .= "<option value='5ème Periode'>5ème Periode</option>";
                            $exmprd .= "<option value='6ème Periode'>6ème Periode</option>";
                        }
                    }
                    
                    $res['exams'] = $get_data;
                    $res['studnt'] = $data1;
                    $res['exmprd'] = $exmprd;
                    $res['mestr'] = $mestr;
                    $res['clsname'] = $clsname;
                    $res['classess'] = $datasec;
                    $res['selsctns'] = implode(",", $clsids);
                    //print_r($res); die;
                    return $this->json($res);
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
                    /*$lang = $this->Cookie->read('language');
                    if($lang != "") { 
                        $lang = $lang; 
                    } else { 
                        $lang = 2; 
                        
                    }*/
                    $lang = 2;
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
                        if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
                        
    				}
                    $exams_assessment_table = TableRegistry::get('exams_assessments');
                    $activ_table = TableRegistry::get('activity');
                    $custm_que_tbl = TableRegistry::get('exam_ass_question');
                    $schoolid =  md5($this->request->session()->read('company_id'));
                    $company_table = TableRegistry::get('company');
                    $retrieve_schoolid = $company_table->find()->select(['id'])->where(['md5(id)' => $schoolid])->toArray() ;
                    $school_id = $retrieve_schoolid[0]['id'];
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());    
                    $rqst = $this->request->data('erequest_for');
                    $studata =$this->request->data('estdnt');
                    $submitexams_table = TableRegistry::get('submit_exams');
                    $student_table = TableRegistry::get('student');
                    $nopages = 0;
                    $dirname = "";
                    
                    if($rqst == "Study Guide")
                    {
                        if(!empty($this->request->data['efileupload']))
                        {   
                            if($this->request->data['efileupload']['type'] == "application/pdf"  )
                            {
                                //$picture =  $this->request->data['fileupload']['name'];
                                $filename = time()."_".$this->request->data['efileupload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efileupload']['tmp_name'], $uploadfile))
                                {
                                    $filename = $filename; 
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
                        
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        $nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                        
                        if(!empty($filename))
                        {
                            $clsidss = $this->request->data['eclass'];
                            foreach ($clsidss as $cidss)
                            {
                                $class_id =  $cidss;
                                $subject_id =  $this->request->data('esubjects')  ;
                                $title =  $this->request->data('etitle')  ;
                                $start_date =  $this->request->data('estart_date');
                                $end_date =  $this->request->data('eend_date');
                                $type =  $this->request->data('erequest_for');
                               
                                $created_date =  time()  ;
                                $file_name =  $filename ;
                                
                                $exam_assid =  $this->request->data('exam_assid');
                                $get_uniqid = $exams_assessment_table->find()->select(['uniqid'])->where(['id' => $exam_assid])->first(); 
                                $uniqueid = $get_uniqid['uniqid'];
                                
                                $get_eaqsid = $exams_assessment_table->find()->select(['id'])->where(['uniqid' => $uniqueid, 'class_id' => $class_id])->first(); 
                                $exam_assid = $get_eaqsid['id'];
     
                                $contentupload ="study_guide";
                                $status = 1  ;
                                    
                                        
                                $st = strtotime($this->request->data('estart_date'));
                                $et =  strtotime($this->request->data('eend_date'));
                                if($st < $et)
                                {
                                    if(!empty($get_eaqsid))
                                    {
                                        $update = $exams_assessment_table->query()->update()->set(['dirname' => $dirname, 'numpages' => $nopages, 'title' => $title, 'exam_format' => $contentupload,'school_id' => $school_id, 'created_date' => $created_date , 'file_name' => $file_name , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                    }
                                    else
                                    {
                                        $exams_ass = $exams_assessment_table->newEntity();
                                    
                                        $exams_ass->title =  $title;
                                        $exams_ass->class_id = $class_id ;
                                        $exams_ass->uniqid = $uniqueid;
                                        $exams_ass->subject_id = $subject_id;
                                        $exams_ass->start_date =  $this->request->data('estart_date');
                                        $exams_ass->end_date =  $this->request->data('eend_date');
                                        $exams_ass->type =  $this->request->data('erequest_for');
                                        $exams_ass->created_date =  time()  ;
                                        $exams_ass->file_name =  $file_name ;
                                        $exams_ass->school_id =  $school_id ;
                                        $exams_ass->exam_format= "study_guide";
                                        $exams_ass->session_id = $this->Cookie->read('sessionid'); ;
                                        $exams_ass->status =  1  ;
                                        $exams_ass->numpages = $nopages;
                                        $exams_ass->dirname = $dirname;
                                        
                                        $saved = $exams_assessment_table->save($exams_ass);
                                        $exam_assid = $saved->id;
                                    }
                                    
                                    if($update)
                                    {   
                                        $examid = $exam_assid;
                                        
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
                                        
                                        $notify_table = TableRegistry::get('notification'); 
                                        $class_table = TableRegistry::get('class');  
                                        $subject_table = TableRegistry::get('subjects');
                                        
                                        if($status == 1)
                                        {
                                            $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('esubjects')])->first();
                                            
                                            $subname = $ret_sub['subject_name'];
                                            
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
                                            /*$title = "Update Request ". $type1 . " for ". $subname." has been generated";
                                            $desc = "<p>Hi Students,</p>
                                            <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('estart_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                                            <p>Thanks</p>";*/
                                            
                                            $title = "(".$typelang.") Demande de mise à jour ".$subname." a généré une nouvelle tâche qui nécessite votre attention.";
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
                                            $notify->added_by = 'school';
                                            $notify->school_id = $school_id;
                                            $notify->status = '1';
                                        
                                            $notify->class_ids = $class_id;
                                            $notify->class_opt = 'multiple';
                                            $scdate = date("d-m-Y H:i",time());                             
                                            $notify->schedule_date = $scdate;
                                            $notify->sent_notify = '1';
                                            $notify->sc_date_time = time();
                                            
                                            $saved = $notify_table->save($notify);
                                            
                                        }
                                    
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
                                    $filename = time()."_".$this->request->data['efileupload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['efileupload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
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
                                $clsidss = $this->request->data['eclass'];
                                foreach ($clsidss as $cidss)
                                {
                                    $class_id =  $cidss;
                                    $subject_id =  $this->request->data('esubjects')  ;
                                    $title =  $this->request->data('etitle')  ;
                                    
                                    $special_instruction =  $this->request->data('einstruction');
                                    $start_date =  $this->request->data('estart_date');
                                    $end_date =  $this->request->data('eend_date');
                                    $type =  $this->request->data('erequest_for');
                                    $exam_type =  $this->request->data('eexam_type');
                                    $emax_marks =  $this->request->data('emax_marks');
                                    $exam_period =  $this->request->data('eexam_period');
                                    $created_date =  time()  ;
                                    
                                    if(!empty($studata)){
                                        $studata = implode(',',$studata);
                                    }else{
                                        $studata = '';
                                    }
                                    
                                    $file_name =  $filename ;
                                    //$exams_ass->teacher_id =  $tid ;
                                    $school_id =  $school_id ;
                                    $exam_assid =  $this->request->data('exam_assid');
                                    $get_uniqid = $exams_assessment_table->find()->select(['uniqid'])->where(['id' => $exam_assid])->first(); 
                                    $uniqueid = $get_uniqid['uniqid'];
                                    
                                    $get_eaqsid = $exams_assessment_table->find()->select(['id'])->where(['uniqid' => $uniqueid, 'class_id' => $class_id])->first(); 
                                    $exam_assid = $get_eaqsid['id'];
                                    
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
                                    if(!empty($exam_type)) {
                                    $sdate = strtotime($start_date);
                                    $edate =  strtotime($end_date);
                                    $sessionid = $this->Cookie->read('sessionid');
                                    if($type == "Exams")
                                    {
                                        $getexamsexist = $exams_assessment_table->find()->where([ 'id' => $exam_assid ,'student_id' => $studata , 'title' => $title, 'class_id' => $class_id, 'subject_id' => $this->request->data('esubjects'), 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('eexam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                    }
                                    else
                                    {
                                        $getexamsexist = [];
                                    }
                                    
                                    if(empty($getexamsexist))
                                    {
                                        if($emax_marks > 0)
                                        {
                                            if($edate > $sdate)
                                            {
                                                if(!empty($get_eaqsid))
                                                {
                                                    $update = $exams_assessment_table->query()->update()->set(['exam_period' => $exam_period ,'student_id' => $studata , 'show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload,'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date , 'file_name' => $file_name , 'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction,  'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                                
                                                    $del = $submitexams_table->query()->delete()->where([ 'exam_id' => $exam_assid ])->execute(); 
                                                    
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
                                                            $submitexams->exam_id = $exam_assid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    //$del = $submitexams_table->query()->delete()->where([ 'exam_id' => $exam_assid ])->execute(); 
                                                    
                                                    $exams_ass = $exams_assessment_table->newEntity();
                                    
                                                    $exams_ass->title =  $title;
                                                    $exams_ass->class_id = $class_id ;
                                                    $exams_ass->uniqid = $uniqueid;
                                                    $exams_ass->subject_id = $subject_id;
                                                    $exams_ass->start_date =  $this->request->data('estart_date');
                                                    $exams_ass->end_date =  $this->request->data('eend_date');
                                                    $exams_ass->type =  $this->request->data('erequest_for');
                                                    $exams_ass->created_date =  time()  ;
                                                    $exams_ass->file_name =  $file_name ;
                                                    $exams_ass->school_id =  $school_id ;
                                                    $exams_ass->exam_format= "study_guide";
                                                    $exams_ass->session_id = $this->Cookie->read('sessionid'); ;
                                                    $exams_ass->status =  1  ;
                                                    $exams_ass->numpages = $nopages;
                                                    $exams_ass->dirname = $dirname;
                                    
                                                    $exams_ass->special_instruction =  $special_instruction;
                                                    $exams_ass->exam_type =  $exam_type;
                                                    $exams_ass->max_marks =  $emax_marks;
                                                    $exams_ass->exam_period = $exam_period;
                                    
                                                    $exams_ass->exam_format= $contentupload;
                                                    $exams_ass->show_exmfrmt = $show_exmfrmt;
                                                    if($type == "Exams")
                                                    {
                                                        $exams_ass->status =  0  ;
                                                    }
                                                    else
                                                    {
                                                        $exams_ass->status =  1  ;
                                                    }
                                                    $saved = $exams_assessment_table->save($exams_ass);
                                                    $exam_assid = $saved->id;
                                                    
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
                                                            $submitexams->exam_id = $exam_assid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                }   
                                                
                                                if($update)
                                                {   
                                                    $examid = $exam_assid;
                                                    
                                                    $notify_table = TableRegistry::get('notification'); 
                                                    $class_table = TableRegistry::get('class');  
                                                    $subject_table = TableRegistry::get('subjects');
                                                    
                                                    if($status == 1)
                                                    {
                                                        $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('esubjects')])->first();
                                                        
                                                        $subname = $ret_sub['subject_name'];
                                                        
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
                                                        /*$title = "Update Request ". $type1 . " for ". $subname." has been generated";
                                                        $desc = "<p>Hi Students,</p>
                                                        <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('estart_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                                                        <p>Thanks</p>";*/
                                                        $title = "(".$typelang.") Demande de mise à jour ".$subname." a généré une nouvelle tâche qui nécessite votre attention.";
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
                                                        $notify->added_by = 'school';
                                                        $notify->school_id = $school_id;
                                                        $notify->status = '1';
                                                        
                                                        $notify->class_ids = $class_id;
                                                        $notify->class_opt = 'multiple';
                                                        $scdate = date("d-m-Y H:i",time());                             
                                                        $notify->schedule_date = $scdate;
                                                        $notify->sent_notify = '1';
                                                        $notify->sc_date_time = time();
                                                        
                                                        $saved = $notify_table->save($notify);
                                                        
                                                    }
                                                    
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
                                        $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                    }
                                    }
                                    else
                                    {
                                        $res = ['result' => 'Select Semestre/Trimestre'];
                                    }
                                }
                            }
                            else
                            {
                                
                                $res = [ 'result' => 'Only Pdf allowed'  ];
                            }
                    }
                        else
                        {   
                            $clsidss = $this->request->data['eclass'];
                            foreach ($clsidss as $cidss)
                            {
                                $class_id =  $cidss;
                                $subject_id =  $this->request->data('esubjects')  ;
                                $title =  $this->request->data('etitle')  ;
                                $special_instruction =  $this->request->data('einstruction');
                                $start_date =  $this->request->data('estart_date');
                                $end_date =  $this->request->data('eend_date');
                                $type =  $this->request->data('erequest_for');
                                $exam_type =  $this->request->data('eexam_type');
                                $emax_marks =  $this->request->data('emax_marks');
                                $exam_period =  $this->request->data('eexam_period');
                                $created_date =  time()  ;
                                $school_id =  $school_id ;
                                
                                if(!empty($studata)){
                                    $studata = implode(',',$studata);
                                }else{
                                    $studata = '';
                                }
                                $exam_assidforque =  $this->request->data('exam_assid');
                                $exam_assid =  $this->request->data('exam_assid');
                                $get_uniqid = $exams_assessment_table->find()->select(['uniqid'])->where(['id' => $exam_assid])->first(); 
                                $uniqueid = $get_uniqid['uniqid'];
                                
                                $get_eaqsid = $exams_assessment_table->find()->select(['id'])->where(['uniqid' => $uniqueid, 'class_id' => $class_id])->first(); 
                                $exam_assid = $get_eaqsid['id'];
                                $contentupload =$this->request->data('econtentupload');
                                
                                if(!empty($exam_type))
                                {
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
                                    
                                    $sessionid = $this->Cookie->read('sessionid');
                                    if($type == "Exams")
                                    {
                                        $getexamsexist = $exams_assessment_table->find()->where([ 'id !=' => $exam_assid, 'student_id' => $studata , 'class_id' => $class_id, 'subject_id' => $this->request->data('esubjects'), 'type' => "Exams", 'exam_period IS' => NULL, 'exam_type' => $this->request->data('eexam_type'), 'school_id' => $school_id, 'session_id' => $sessionid ])->toArray() ;
                                    }
                                    else
                                    {
                                        $getexamsexist = [];
                                    }
                                    if(empty($getexamsexist))
                                    {
                                        if($emax_marks > 0)
                                        {
                                            if($edate > $sdate)
                                            {
                                                if(!empty($get_eaqsid))
                                                {
                                                    $update = $exams_assessment_table->query()->update()->set(['exam_period' => $exam_period, 'student_id' => $studata ,'title' => $title, 'show_exmfrmt' => $show_exmfrmt, 'exam_format' => $contentupload, 'school_id' => $school_id, 'max_marks' => $emax_marks, 'created_date' => $created_date ,  'exam_type' => $exam_type , 'type' => $type , 'end_date' => $end_date, 'start_date' => $start_date, 'special_instruction' => $special_instruction, 'subject_id' => $subject_id , 'class_id' => $class_id , 'status' => $status ])->where([ 'id' => $exam_assid  ])->execute();
                                                    $del = $submitexams_table->query()->delete()->where([ 'exam_id' => $exam_assid ])->execute(); 
                                                    
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
                                                            $submitexams->exam_id = $exam_assid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $exams_ass = $exams_assessment_table->newEntity();
                                        
                                                    $exams_ass->title =  $title;
                                                    $exams_ass->class_id = $class_id ;
                                                    $exams_ass->uniqid = $uniqueid;
                                                    $exams_ass->subject_id = $subject_id;
                                                    $exams_ass->start_date =  $this->request->data('estart_date');
                                                    $exams_ass->end_date =  $this->request->data('eend_date');
                                                    $exams_ass->type =  $this->request->data('erequest_for');
                                                    $exams_ass->created_date =  time()  ;
                                                    //$exams_ass->file_name =  $file_name ;
                                                    $exams_ass->school_id =  $school_id ;
                                                    $exams_ass->session_id = $this->Cookie->read('sessionid'); ;
                                                    $exams_ass->status =  1  ;
                                                    $exams_ass->special_instruction =  $special_instruction;
                                                    $exams_ass->exam_type =  $exam_type;
                                                    $exams_ass->max_marks =  $emax_marks;
                                                    $exams_ass->exam_period = $exam_period;
                                                    $exams_ass->student_id = $studata;
                                                    $exams_ass->exam_format= $contentupload;
                                                    $exams_ass->show_exmfrmt = $show_exmfrmt;
                                                    if($type == "Exams")
                                                    {
                                                        $exams_ass->status =  0  ;
                                                    }
                                                    else
                                                    {
                                                        $exams_ass->status =  1  ;
                                                    }
                                                    $saved = $exams_assessment_table->save($exams_ass);
                                                    $exam_assid = $saved->id;
                                                    
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
                                                            $submitexams->exam_id = $exam_assid;
                                                            $submitexams->student_id = $stud_id;
                                                    		$submitexams->status = 0;
                                                            $submitexams->school_id = $school_id;
                                                            $saved = $submitexams_table->save($submitexams);
                                                            
                                                        }
                                                    }
                                                    
                                                    $custmze_que_table = TableRegistry::get('exam_ass_question');
                                                    $get_allques = $custmze_que_table->find()->where(['exam_id' => $exam_assidforque])->toArray(); 
                                                    foreach($get_allques as $allques)
                                                    {
                                                        $cus_que = $custmze_que_table->newEntity();
                                
                                                        $cus_que->exam_id = $exam_assid;
                                                        $cus_que->marks = $allques['marks'];
                                                        $cus_que->question = $allques['question'];
                                                        $cus_que->ques_type = $allques['ques_type'];
                                                        $cus_que->uniqid = $allques['uniqid'];
                                                        if($allques['uniqid'] == "objective")
                                                        {
                                                            $cus_que->options = $allques['options'];
                                                        }
                                                        if(!empty($this->request->data['maxwords']))
                                                        {
                                                            $cus_que->max_words =  $this->request->data('maxwords')  ;
                                                        }
                                                        
                                                        $cus_que->created_date =  time();
                                                        $saved = $custmze_que_table->save($cus_que);
                                                    }
                                                }
                                                
                                                if($update)
                                                {   
                                                    $examid = $exam_assid;
                                                    
                                                    $notify_table = TableRegistry::get('notification'); 
                                                    $class_table = TableRegistry::get('class');  
                                                    $subject_table = TableRegistry::get('subjects');
                                                    
                                                    if($status == 1)
                                                    {
                                                        $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('esubjects')])->first();
                                                        
                                                        $subname = $ret_sub['subject_name'];
                                                        
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
                                                        /*$title = "Update Request ". $type1 . " for ". $subname." has been generated";
                                                        $desc = "<p>Hi Students,</p>
                                                        <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('estart_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                                                        <p>Thanks</p>";*/
                                                        $title = "(".$typelang.") Demande de mise à jour ".$subname." a généré une nouvelle tâche qui nécessite votre attention.";
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
                                                        $notify->added_by = 'school';
                                                        $notify->school_id = $school_id;
                                                        $notify->status = '1';
                                                        
                                                        $notify->class_ids = $class_id;
                                                        $notify->class_opt = 'multiple';
                                                        $scdate = date("d-m-Y H:i",time());                             
                                                        $notify->schedule_date = $scdate;
                                                        $notify->sent_notify = '1';
                                                        $notify->sc_date_time = time();
                                                        
                                                        $saved = $notify_table->save($notify);
                                                        
                                                    }
                                                    
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
                                    else
                                    {
                                        $res = ['result' => 'Semestre/Trimestre Exams of this class subject is already exist'];
                                    }
                                }
                                else
                                {
                                    $res = ['result' => 'Select Semestre/Trimestre'];
                                }
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
                        $totl = $this->request->data('emarks');
                    }
                    $getuniqid = $custmze_que_table->find()->select(['uniqid'])->where(['id' => $que_id])->first(); 
                    $uniqueid = $getuniqid['uniqid'];
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
                                $update = $custmze_que_table->query()->update()->set(['question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'options' => $options, 'created_date' => $created_date])->where([ 'uniqid' => $uniqueid  ])->execute();
                            }
                            else
                            {
                                $update = $custmze_que_table->query()->update()->set(['max_words' => $max_words, 'question' => $question, 'marks' => $marks, 'ques_type' => $ques_type, 'created_date' => $created_date])->where([ 'uniqid' => $uniqueid  ])->execute();
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
            
            public function status()
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
				foreach($retrieve_langlabel as $langlbl) { 
                    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
                    
				}
                $uid = $this->request->data('val') ;
                $sts = $this->request->data('sts') ;
                $exmass_table = TableRegistry::get('exams_assessments');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sid = $exmass_table->find()->select(['id'])->where(['id'=> $uid ])->first();
    			$status = $sts == 1 ? 0 : 1;
                if($sid)
                {   
                    $stats = $exmass_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
    				
                    if($stats)
                    {
                        $retrieve_examsassessment = $exmass_table->find()->where(['id'=> $uid])->toArray();
                        $type = $retrieve_examsassessment[0]['type'];
                        
                        if($type == "Study Guide" && $status == 1 && $retrieve_examsassessment[0]['teacher_id'] != "") {
                            $notify_table = TableRegistry::get('notification');
                            $subject_table = TableRegistry::get('subjects');
                            $ret_sub = $subject_table->find()->where(['id'=> $retrieve_examsassessment[0]['subject_id']])->first();
                            
                            $subname = $ret_sub['subject_name'];
                            /************* Study guide ****************/
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
                            $title = "New ".$type1." for ". $subname." has been generated";
                            $desc = "<p>Hi Students,</p>
                            <p>This is you to inform that ".$type." of ".$subname. " has been generated. This request is schedule on ". $this->request->data('start_date'). ". Please check the request on start time and submit it before end time as it will not submit afterwords.</p>
                            <p>Thanks</p>";
                            
                            date_default_timezone_set('Africa/Kinshasa');
                            $notify = $notify_table->newEntity();
                            $notify->title = $title;
                            $notify->description = $desc;
                            $notify->notify_to = 'students';
                            $notify->created_date =  time();
                            $notify->added_by = 'teacher';
                            $notify->teacher_id = $retrieve_examsassessment[0]['teacher_id'];
                            $notify->status = '1';
                            
                            $notify->class_ids = $retrieve_examsassessment[0]['class_id'];
                            $notify->class_opt = 'multiple';
                            $scdate = date("d-m-Y H:i",time());
                            $notify->schedule_date = $scdate;
                            $notify->sent_notify = '1';
                            $notify->sc_date_time = time();
                            
                            //print_r($notify); die;
                            
                            $saved = $notify_table->save($notify);
                            
                        }
                        
                        
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Guide status changed"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($uid)    ;
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
                        $res = [ 'result' => 'Status Not changed'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'error'  ];
                }
    
                return $this->json($res);
            }
            
            public function getperiodlst()
            {
                //print_r($_POST); 
                $clsid = explode("_", $this->request->data('cls'));  
                $exmtyp = $this->request->data('exmtyp'); 
                $periodid = $this->request->data('periodid'); 
                $req = $this->request->data('req'); 
                
                $cls_table = TableRegistry::get('class');
                //$get_periods = $cls_table->find()->where(['c_name' => $clsid[0], 'school_sections' => $clsid[1], 'school_id' => $compid])->first(); 
                
                //$sectn_name = strtolower($get_periods['school_sections']);
                $sectn_name = strtolower($clsid[1]);
                $exmprd = "";
                if($req != "Exams")
                {
                    if($sectn_name == "maternelle" || $sectn_name == "creche")
                    {
                        $exmprd = "";
                    }
                    else
                    {
                        $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; 
                        if($periodid == '1ère Periode') { $p1 = 'selected'; }
                        if($periodid == '2ème Periode') { $p2 =  'selected'; }
                        if($periodid == '3ème Periode') { $p3 =  'selected'; }
                        if($periodid == '4ème Periode') { $p4 =  'selected'; }
                        if($periodid == '5ème Periode') { $p5 =  'selected'; }
                        if($periodid == '6ème Periode') { $p6 =  'selected'; }
                        if($exmtyp == "Premier Trimestre")
                        {
                            $exmprd .= "<option value='1ère Periode' ". $p1.">1ère Periode</option>";
                            $exmprd .= "<option value='2ème Periode' ". $p2.">2ème Periode</option>";
                        }
                        if($exmtyp == "Deuxieme Trimestre")
                        {
                            $exmprd .= "<option value='3ème Periode' ". $p3.">3ème Periode</option>";
                            $exmprd .= "<option value='4ème Periode' ". $p4.">4ème Periode</option>";
                        }
                        if($exmtyp == "Premier Semestre")
                        {
                            $exmprd .= "<option value='1ère Periode' ". $p1.">1ère Periode</option>";
                            $exmprd .= "<option value='2ème Periode' ". $p2.">2ème Periode</option>";
                        }
                        if($exmtyp == "Second Semestre")
                        {
                            $exmprd .= "<option value='3ème Periode' ". $p3.">3ème Periode</option>";
                            $exmprd .= "<option value='4ème Periode' ". $p4.">4ème Periode</option>";
                        }
                        if($exmtyp == "Troisieme Trimestre")
                        {
                            $exmprd .= "<option value='5ème Periode' ". $p5.">5ème Periode</option>";
                            $exmprd .= "<option value='6ème Periode' ". $p6.">6ème Periode</option>";
                        }
                    }
                }
                
                $res = ['result' => $exmprd, 'sections' => $sectn_name ];
                
                return $this->json($res);
            }
            public function getmestr()
            {
                $filter = $this->request->data('filter'); 
                $abc = $this->request->data('abc'); 
                $clsid = $this->request->data('clsid'); 
                $cid = explode("_", $clsid);
                //print_r($cid); die;
                //$cls_table = TableRegistry::get('class');
                //$get_periods = $cls_table->find()->where(['id' => $clsid])->first(); 
                
                //$sectn_name = strtolower($get_periods['school_sections']);
                $sectn_name = strtolower($cid[1]);
                $mestr = "";
                if($filter == "add")
                {
                    if($sectn_name == "maternelle" || $sectn_name == "creche" || $sectn_name == "primaire")
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Trimestre'>Premier Trimestre</option>";
                        $mestr .= "<option value='Deuxieme Trimestre'>Deuxieme Trimestre</option>";
                        $mestr .= "<option value='Troisieme Trimestre'>Troisieme Trimestre</option>";
                    }
                    else
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Semestre'>Premier Semestre</option>";
                        $mestr .= "<option value='Second Semestre'>Second Semestre</option>";
                    }
                }
                else
                {
                    $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = '';
                    if($abc == 'Premier Trimestre') { $p1 = 'selected'; }
                    if($abc == 'Deuxieme Trimestre') { $p2 =  'selected'; }
                    if($abc == 'Troisieme Trimestre') { $p3 =  'selected'; }
                    if($abc == 'Premier Semestre') { $p4 =  'selected'; }
                    if($abc == 'Second Semestre') { $p5 =  'selected'; }
                    
                    if($sectn_name == "maternelle" || $sectn_name == "creche" || $sectn_name == "primaire")
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Trimestre' ".$p1." >Premier Trimestre</option>";
                        $mestr .= "<option value='Deuxieme Trimestre' ".$p2." >Deuxieme Trimestre</option>";
                        $mestr .= "<option value='Troisieme Trimestre' ".$p3." >Troisieme Trimestre</option>";
                    }
                    else
                    {
                        $mestr .= "<option value=''>Choose Option</option>";
                        $mestr .= "<option value='Premier Semestre' ".$p4." >Premier Semestre</option>";
                        $mestr .= "<option value='Second Semestre' ".$p5." >Second Semestre</option>";
                    }
                }
                
                $res = ['data' => $mestr ];
                
                return $this->json($res);
            }
        
            
            
}

  




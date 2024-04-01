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
class ExamListingController  extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     **/
     
     
    public function index()
    {   
        
        if(empty($_GET))
        {
            $classid = $this->request->data('classId');
    		$subjectid = $this->request->data('subId');
        }
        else
        {
            $classid = $this->request->query('classId');
    		$subjectid = $this->request->query('subId');

        }
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
        $subjects_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $employee_table = TableRegistry::get('employee');
        $submit_exams_table = TableRegistry::get('submit_exams');
        $exams_assessments_table = TableRegistry::get('exams_assessments');
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->toArray() ;
        $retrieve_examass = $exams_assessments_table->find()->where(['class_id' => $classid , 'subject_id' => $subjectid, 'status' => 1, 'type' => 'Exams'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        foreach($retrieve_examass as $key =>$approvecoll)
		{
			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examass[$key]['subject_id'] ])->toArray();
			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
			
			$retrieve_class = $class_table->find()->select(['c_name', 'c_section', 'school_sections'])->where(['id' => $retrieve_examass[$key]['class_id']  ])->toArray();
			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section']."(".$retrieve_class[0]['school_sections'].")";
			
			
			if(!empty($retrieve_examass[$key]['teacher_id']))
			{
    			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examass[$key]['teacher_id']  ])->toArray();
    			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']."-".$retrieve_employee[0]['l_name'];
			}
			else
			{
			    $approvecoll->teacher_name = 'School';
			}
			
			$retrieve_subexams = $submit_exams_table->find()->select(['examformt', 'student_id', 'status', 'passcode', 'id', 'upload_exams', 'file_type'])->where(['md5(student_id)' => $stid , 'exam_id' => $retrieve_examass[$key]['id'] ])->toArray() ;
		    $approvecoll->exam_sts = $retrieve_subexams[0]['status'];
		    $approvecoll->passcode = $retrieve_subexams[0]['passcode'];
		    $approvecoll->submitexam_id = $retrieve_subexams[0]['id'];
		    $approvecoll->uploadfile = $retrieve_subexams[0]['upload_exams'];
		    $approvecoll->studentid = $retrieve_subexams[0]['student_id'];
		    $approvecoll->examformt = $retrieve_subexams[0]['examformt'];
		    $approvecoll->file_type = $retrieve_subexams[0]['file_type']; 
		}
		
		
        $this->set("studentid", $stid);
        $this->set("classid", $classid); 
        $this->set("subjectid", $subjectid); 
        $this->set("subject_details", $retrieve_subjectname); 
        $this->set("examass_detail", $retrieve_examass); 
        //$this->set("examsts_detail", $retrieve_exams); 
        $this->viewBuilder()->setLayout('user');
    }
    
    public function view()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $employee_table = TableRegistry::get('employee');
		$subjectid = $this->request->data('classid');
		$classid = $this->request->data('subjectid');
        $retrieve_employee = $employee_table->find()->where(['subjects LIKE' => "%".$subjectid."%", 'grades LIKE' => "%".$classid."%" ])->toArray() ;
		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid];
        return $this->json($data);
    }
    
    public function viewexamformat()
    {
        $subexmid = $this->request->data('SubexamID'); 
        $submitexams_table = TableRegistry::get('submit_exams');
        $retrieve_exam = $submitexams_table->find()->select(['exam_id', 'examformt'])->where([ 'id' => $subexmid ])->first();
        $format = $retrieve_exam['examformt'];
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        return $this->json($format);
    }
    
    public function customexamsubmitform()
    {
        $customexam_table = TableRegistry::get('custom_exam_submit');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$studentid = $this->request->data('studentid');
		$examid = $this->request->data('examid');
		$totalque = $this->request->data('totalque');
		$countpost = count($_POST);
		$answerpost = $countpost-5;
		$putans = [];
		for($i = 1; $i<= $totalque; $i++)
        {
            $ansids = "answers_".$i;
            $answer = $_POST[$ansids];
            if(is_array($answer))
            {
                $putans[] = trim(implode(",", $answer));
            }
            else
            {
                $putans[] = trim($answer);
            }
        }		
        $anssubmit = implode("~^|*~", $putans);
        
        $retrieve_custmexam = $customexam_table->find()->select(['id'])->where([ 'student_id' => $studentid, 'exam_id' => $examid ])->count();
        if($retrieve_custmexam == 0)
        {
            $custmexm = $customexam_table->newEntity();
            $custmexm->student_id = $studentid;
            $custmexm->exam_id = $examid;
    		$custmexm->answer_submit = $anssubmit;
            $custmexm->created_date = time();
                                  
            if($saved = $customexam_table->save($custmexm) )
            {   
                $custmexmid = $saved->id;
    
                $activity = $activ_table->newEntity();
                $activity->action =  "customm exam Added"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('stid');
                $activity->value = md5($custmexmid); 
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
            $retrieve_custmexam = $customexam_table->find()->select(['id'])->where([ 'student_id' => $studentid, 'exam_id' => $examid ])->first();
            $custmexmid = $retrieve_custmexam['id'];
            $update = $customexam_table->query()->update()->set(['student_id' => $studentid , 'exam_id' => $examid, 'created_date' => time(), 'answer_submit' => $anssubmit])->where([ 'id' => $custmexmid  ])->execute() ;     
        
            if($update)
            {   
                $activity = $activ_table->newEntity();
                $activity->action =  "customm exam Added"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('stid');
                $activity->value = md5($custmexmid); 
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
        return $this->json($res);
    }
    
    public function customexamsubmitauto()
    {
        $customexam_table = TableRegistry::get('custom_exam_submit');
        $submitexams_table = TableRegistry::get('submit_exams');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$studentid = $this->request->data('studentid');
		$examid = $this->request->data('examid');
		$totalque = $this->request->data('totalque');
		$countpost = count($_POST);
		$answerpost = $countpost-5;
		$putans = [];
		for($i = 1; $i<= $totalque; $i++)
        {
            $ansids = "answers_".$i;
            $answer = $_POST[$ansids];
            if(is_array($answer))
            {
                $putans[] = trim(implode(",", $answer));
            }
            else
            {
                $putans[] = trim($answer);
            }
        }		
        $anssubmit = implode("~^|*~", $putans);
        $filetype ="custom";
        $update = $submitexams_table->query()->update()->set(['upload_exams' => $anssubmit , 'file_type' => $filetype, 'created_date' => time() , 'status' => 1 ])->where([ 'exam_id' => $examid, 'student_id' => $studentid])->execute() ;     
        if($update)
        {   
            $custmexmid = $saved->id;

            $activity = $activ_table->newEntity();
            $activity->action =  "customm exam Added"  ;
            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            $activity->origin = $this->Cookie->read('stid');
            $activity->value = md5($custmexmid); 
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
        
        return $this->json($res);
    }
    
    public function viewcustomexam()
    {
        $exmid = $this->request->data('id'); 
        $subexmid = $this->request->data('SubexamID'); 
        $examformt = $this->request->data('examformt'); 
        $submitexams_table = TableRegistry::get('submit_exams');
        if(!empty($examformt))
        {
            $update = $submitexams_table->query()->update()->set(['examformt' => $examformt ])->where([ 'id' => $subexmid  ])->execute() ;                  
        }       
        $this->request->session()->write('LAST_ACTIVE_TIME', time());       
        $que_table = TableRegistry::get('exam_ass_question');
        $exam_table = TableRegistry::get('exams_assessments');
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $school_table = TableRegistry::get('company');
        
        $retrieve_exam = $exam_table->find()->where([ 'id' => $exmid ])->toArray();
        $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam[0]['class_id'] ])->toArray();
        $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam[0]['subject_id'] ])->toArray();
        $retrieve_questions = $que_table->find()->where([ 'exam_id' => $exmid ])->toArray();
        $schoolid = $retrieve_exam[0]['school_id'];
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    $questns = "";
	    
	    
	    $lang = $this->Cookie->read('language');	
		if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
            $lang = 2;
        }
        
        //echo $lang;
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
            if($langlbl['id'] == '1742') { $labelassign = $langlbl['title'] ; } 
            if($langlbl['id'] == '365') { $labelsubjct = $langlbl['title'] ; } 
            if($langlbl['id'] == '136') { $labelclass = $langlbl['title'] ; } 
            
            if($langlbl['id'] == '371') { $labelmaxmarks = $langlbl['title'] ; } 
            if($langlbl['id'] == '368') { $labelstartdt = $langlbl['title'] ; } 
            if($langlbl['id'] == '369') { $labelenddt = $langlbl['title'] ; } 
            if($langlbl['id'] == '373') { $labelspinst = $langlbl['title'] ; }
        } 
	    foreach($retrieve_questions as $ques)
	    {
	        $questns .= '<tr><td style="width: 100%;"><table style="width: 100%; "><tr>';
	        
        	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px;  font-weight:16px !important; margin-bottom:30px !important;">';
        	
        	$questns .= "<div  style='margin-left:20px; font-style:normal !important;' >
        	<span id='ques' style=' width:95%; padding-top:10px;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
        	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
        	
	        if($ques['ques_type'] == "objective")
	        {
	            $questns .= "<div style='margin:7px 0;'>";
	            $options = explode("~^", $ques['options']);
	            $m = 1;
	            $chckbox = "<input type='radio' style='margin-left:25px; padding-top:5px;'>";
	            foreach($options as $opt)
	            {
	                $questns .= "<div>".$chckbox." <span id='options' style='margin-left:5px;'>".$opt."</span></div>";
	                $m++;
	            }
	            $questns .= "</div>";
	            
	        }
	        elseif($ques['ques_type'] == "subjective")
	        {
	            $questns .= "<div style='margin:70px 0;'></div>";
	            
	        }
        	$questns .= "</div>";
	            
        	$questns .= '</th>';
        	
        	$questns .= "</tr></table</td></tr>";
        	
	        
	        
	        $n++;
	    }
	    
	    if($retrieve_exam[0]['type'] == "Assessment")
	    {
	        $type = $labelassign;
	    }
	    else
	    {
	        $type = $retrieve_exam[0]['type'];
	    }
	    
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
                			<td style="width: 100%;">
                			    <table style="width: 100%;">
            						<tr>
            						    <th style="width: 100%; text-align:right; margin-right:20px !important;"><a href="'.$this->base_url.'examListing/downloadpdf/'.$exmid.'" class="btn btn-info" download onclick="popup()"> <i class="fa fa-download"></i>
            						    </th>
            						</tr>
            					</table>
                			</td>
            			</tr>
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
                    						    <th style="width: 100%; text-align:center;  font-size: 16px;">'.$type_request.'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>'.$labelsubjct.': </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                    						</tr>
                    						<tr>
                						        <th style=" font-size: 14px;"> <span><b>'.$labelclass.': </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'].'('.$retrieve_cls[0]['school_sections'].')</span></th>
                						    </tr>
                    					</table>
                    				</td>
                    				<td style="width: 37%;  float:left; text-align:right;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelmaxmarks.': </span>'.$retrieve_exam[0]['max_marks'].'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelstartdt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelenddt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
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
        						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;"><span> '.$labelspinst.': </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span></th>
        						</tr>
        					</table>
            			</td>
        			</tr>
        			'.$questns.'
        		</tbody>
        		</table>';
	    
	    $title = $retrieve_exam[0]['title'];
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
	    
	    
	    return $this->json($viewpdf);
    }
            
    public function viewexam($subexamID)
    {
        $que_table = TableRegistry::get('exam_ass_question');
        $exam_table = TableRegistry::get('exams_assessments');
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $school_table = TableRegistry::get('company');
        $submitexams_table = TableRegistry::get('submit_exams');
        $customexam_table = TableRegistry::get('custom_exam_submit');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_subexam = $submitexams_table->find()->where([ 'id' => $subexamID ])->first();
        $examid = $retrieve_subexam['exam_id'];
        $studentid = $retrieve_subexam['student_id'];
        $status = $retrieve_subexam['status'];
        
        $retrieve_exam = $exam_table->find()->where([ 'id' => $examid ])->first();
        $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam['class_id'] ])->first();
        $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam['subject_id'] ])->first();
        $retrieve_questions = $que_table->find()->where([ 'exam_id' => $examid ])->toArray();
        $retrieve_answers = $customexam_table->find()->where([ 'exam_id' => $examid, 'student_id' => $studentid ])->first();
        
        $this->set("status", $status); 
        $this->set("exam_details", $retrieve_exam); 
        $this->set("cls_details", $retrieve_cls); 
        $this->set("sub_details", $retrieve_sub); 
        $this->set("studentid", $studentid); 
        $this->set("ques_details", $retrieve_questions); 
        $this->set("ans_details", $retrieve_answers); 
        $this->viewBuilder()->setLayout('user');
    }
	
	
	public function examupload()
	{
	    if($this->request->is('ajax') && $this->request->is('post') )
        { 
    	    $examid = $this->request->data('exam_id');
    		$studentid = $this->request->data('student_id');
    		
    		$activ_table = TableRegistry::get('activity');
    		$student_table = TableRegistry::get('student');
    		$submitexams_table = TableRegistry::get('submit_exams');
    		$this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$retrieve_student = $student_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $studentid ])->toArray() ;
    		
    		$stdid = $retrieve_student[0]['id'];
    		
    		if(is_array($_FILES['exam_upload']['name']))
    		{
    		    $data = '';
    		    $uploads = array();
    		    foreach($this->request->data['exam_upload'] as $new_up){
    		        array_push($uploads,$new_up['name']);
    		        
                    $files = $new_up['name'];
                    $uploadpath = 'uploadExams/';
                    $uploadfile = $uploadpath.$files;
                    if(move_uploaded_file($new_up['tmp_name'], $uploadfile))
                    {
                        $this->request->data['exam_upload'] = $files; 
                    }
    		    }
    		    $filename = implode(",", $uploads);
    		    $filetype = "images";
    		   
    		}
			elseif(!empty($this->request->data['exam_upload']['name']))
            {   
                if($this->request->data['exam_upload']['type'] == "application/pdf" || $this->request->data['exam_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"  )
                {
                    $filename = $this->request->data['exam_upload']['name'];
                    $uploadpath = 'uploadExams/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['exam_upload']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['exam_upload'] = $filename; 
                    }
                }  
                $filetype = "pdf";
            }
            else
            {
                $filename = "";
            }
            
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
            
            //echo $lang;
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
                if($langlbl['id'] == '1938') { $filentuplod = $langlbl['title'] ; } 
            } 
    		
    		if(!empty($filename))
    		{
                $update = $submitexams_table->query()->update()->set(['upload_exams' => $filename , 'file_type' => $filetype, 'created_date' => time() , 'status' => 1 ])->where([ 'id' => $examid  ])->execute() ;                  
                
                if($update)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else{
                    $res = [ 'result' => 'exam not uploaded'  ];
                }
    		}
    		else
    		{
    		    $res = [ 'result' => $filentuplod ];
    		}
        }
        else
        {
            $res = [ 'result' => 'Invalid Operation'  ];
        }
        return $this->json($res);
	}
	
	public function submitcustomizeexam()
	{
	    if($this->request->is('ajax') && $this->request->is('post') )
        { 
	        $examid = $this->request->data('examid');
    		$studentid = $this->request->data('studentid');
    		
    		$activ_table = TableRegistry::get('activity');
    		$submitexams_table = TableRegistry::get('submit_exams');
    		$filetype = 'custom';
    		$this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$countpost = count($_POST);
    		$answerpost = $countpost-4;
    		$putans = [];
    		for($i = 1; $i<= $answerpost; $i++)
            {
                $ansids = "answers_".$i;
                $answer = $_POST[$ansids];
                if(is_array($answer))
                {
                    $putans[] = trim(implode(",", $answer));
                }
                else
                {
                    $putans[] = trim($answer);
                }
            }		
            $anssubmit = implode("~^|*~", $putans);
    		//print_r($_POST); 
    		
            $update = $submitexams_table->query()->update()->set(['upload_exams' => $anssubmit , 'file_type' => $filetype, 'created_date' => time() , 'status' => 1 ])->where([ 'exam_id' => $examid, 'student_id' => $studentid])->execute() ;     
            if($update)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else{
                $res = [ 'result' => 'exam not uploaded'  ];
            }
    		
        }
        else
        {
            $res = [ 'result' => 'Invalid Operation'  ];
        }
        
        return $this->json($res);
	}
	
	public function pdf($subexmid =null)
    {
        
        $submitexm_table = TableRegistry::get('submit_exams');
        $exam_table = TableRegistry::get('exams_assessments');
        $examques_table = TableRegistry::get('exam_ass_question');
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $school_table = TableRegistry::get('company');
        $retrieve_submit = $submitexm_table->find()->where([ 'id' => $subexmid])->toArray();
        $exmid = $retrieve_submit[0]['exam_id'];
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_exam = $exam_table->find()->where([ 'id' => $exmid ])->toArray();
        $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam[0]['class_id'] ])->toArray();
        $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam[0]['subject_id'] ])->toArray();
        
       /* $retrieve_questions = $que_table->find()->where([ 'exam_id' => $exmid ])->toArray();*/
        $schoolid = $retrieve_exam[0]['school_id'];
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    $questns = "";
	    
	    $lang = $this->Cookie->read('language');	
		if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
            $lang = 2;
        }
        
        //echo $lang;
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
            if($langlbl['id'] == '1742') { $labelassign = $langlbl['title'] ; } 
            if($langlbl['id'] == '365') { $labelsubjct = $langlbl['title'] ; } 
            if($langlbl['id'] == '136') { $labelclass = $langlbl['title'] ; } 
            
            if($langlbl['id'] == '371') { $labelmaxmarks = $langlbl['title'] ; } 
            if($langlbl['id'] == '368') { $labelstartdt = $langlbl['title'] ; } 
            if($langlbl['id'] == '369') { $labelenddt = $langlbl['title'] ; } 
            if($langlbl['id'] == '373') { $labelspinst = $langlbl['title'] ; }
        } 
        
        
	    foreach($retrieve_submit as $subimgs)
	    {
	        if($subimgs['file_type'] == "images")
	        {
    	        $imgs = explode(",", $subimgs['upload_exams']);
            	
            	foreach($imgs as $imagename) 
            	{
            	$questns .= '<tr><td style="width: 100%;"><table style="width: 100%; "><tr>';
    			        
            	$questns .= '<th style="width: 100%; text-align:left; margin-top:15px; margin-left:15px; font-style:normal; font-weight:16px !important; margin-bottom:30px !important;">';
            	
            	$questns .= "<div style='text-align:center !important; margin-top:10px; border:1px solid #ccc;'>
            	<img src='uploadExams/". $imagename."' style='width:600px !important; height:390px;'>";
            	$questns .= "</div>";
    	            
            	$questns .= '</th>';
            	
            	$questns .= "</tr></table</td></tr>";
            	
            	}
    	        
    	        $n++;
	        }
	        elseif($subimgs['file_type'] == "custom")
	        {
	            $examid = $subimgs['exam_id'];
	            $explodeans = explode('~^|*~', $subimgs['upload_exams']);
	            $retrieve_questions = $examques_table->find()->where([ 'exam_id' => $examid ])->toArray();
	            $disable = "";
                    	   
                    	        foreach($retrieve_questions as $ques)
                        	    {
                        	        $questns .= '<tr>';
                        	        
                                	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px;  font-weight:16px !important; margin-bottom:30px !important;">';
                                	
                                	$questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;'>
                                	<span id='ques' style=' width:95%; padding-top:10px;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
                                	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
                                	
                        	        if($ques['ques_type'] == "objective")
                        	        {
                        	            $questns .= "<div style='margin:7px 0;'>";
                        	            $options = explode("~^", $ques['options']);
                        	            $m = 1;
                        	            
                        	            foreach($options as $opt)
                        	            {
                        	                $questns .= "<div>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    
                        	                    if($n == $ans)
                        	                    {
                        	                        
                        	                        $checked= '';
                        	                        $checkboxval = explode(",", $val);
                        	                        if(in_array($opt, $checkboxval)) { $checked = "checked"; }
                        	                        $questns .= "<input type='radio' ".$checked." ".$disable." class='submitanswer' name='answers_".$n."[]' style='margin-left:25px; padding-top:5px;' value='".$opt."'>";
                        	                    }
                        	                }
                        	                $questns .= "<span id='options' style='margin-left:5px;'>".$opt."</span></div>";
                        	                $m++;
                        	                
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                        	        elseif($ques['ques_type'] == "subjective")
                        	        {
                        	            
                        	            $questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;' >";
                        	            if($ques['max_words'] != "" )
                        	            {
                        	                //$questns .= "<input type='hidden' id='wordlimitmax' value='".$ques['max_words']."'>";
                        	                //$questns .= "<textarea rows='3' ".$disable." class='form-control mb-2 answercount submitanswer' id='countanswer_".$ques['max_words']."' name='answers_".$n."'>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    if($n == $ans)
                        	                    {
                                	                $questns .= $val ;
                                	            }
                        	                }
                        	                //$questns .= "</textarea>";
                        	                //$questns .= "</textarea><small id='fileHelp' class='form-text text-muted mb-3' id='wordlimit'>Total word count: <span id='displaycount_".$ques['max_words']."'>0</span> words. Words left: <span id='wordleft_".$ques['max_words']."'>".$ques['max_words']."</span></small>";
                        	            
                        	            }
                        	            else
                        	            {
                        	                //$questns .= "<textarea rows='3' ".$disable." class='form-control mb-2 submitanswer' name='answers_".$n."'>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    if($n == $ans)
                        	                    {
                        	                        $questns .= $val;
                        	                    }
                        	                }
                        	                //$questns .= "</textarea>";
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                                	$questns .= "</div></th></tr>";
                        	        $n++;
                        	    }
	        }
	    }
	   
	    
	    if($retrieve_exam[0]['type'] == "Assessment")
	    {
	        $type = $labelassign;
	    }
	    else
	    {
	        $type = $retrieve_exam[0]['type'];
	    }
	    
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
                    						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>'.$labelsubjct.': </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                    						</tr>
                    						<tr>
                						        <th style=" font-size: 14px;">
                						            <span><b>'.$labelclass.': </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'('.$retrieve_cls[0]['school_sections'].')</span>
                						            
                						        </th>
                						    </tr>
                    					</table>
                    				</td>
                    				<td style="width: 37%;  float:left; text-align:right;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelmaxmarks.': </span>'.$retrieve_exam[0]['max_marks'].'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelstartdt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelenddt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
                    						</tr>
                    					</table>
                    				</td>
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
    
    public function downloadpdf($exmid =null)
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
	    
	    $lang = $this->Cookie->read('language');	
		if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
            $lang = 2;
        }
        
        //echo $lang;
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
            if($langlbl['id'] == '1742') { $labelassign = $langlbl['title'] ; } 
            if($langlbl['id'] == '365') { $labelsubjct = $langlbl['title'] ; } 
            if($langlbl['id'] == '136') { $labelclass = $langlbl['title'] ; } 
            
            if($langlbl['id'] == '371') { $labelmaxmarks = $langlbl['title'] ; } 
            if($langlbl['id'] == '368') { $labelstartdt = $langlbl['title'] ; } 
            if($langlbl['id'] == '369') { $labelenddt = $langlbl['title'] ; } 
            if($langlbl['id'] == '373') { $labelspinst = $langlbl['title'] ; }
        } 
	    
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
	    
	    if($retrieve_exam[0]['type'] == "Assessment")
	    {
	        $type = $labelassign;
	    }
	    else
	    {
	        $type = $retrieve_exam[0]['type'];
	    }
	    
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
                    						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>'.$labelsubjct.': </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                    						</tr>
                    						<tr>
                						        <th style=" font-size: 14px;">
                						            <span><b>'.$labelclass.': </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'('.$retrieve_cls[0]['school_sections'].')</span>
                						            
                						        </th>
                						    </tr>
                    					</table>
                    				</td>
                    				<td style="width: 37%;  float:left; text-align:right;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelmaxmarks.': </span>'.$retrieve_exam[0]['max_marks'].'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelstartdt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['start_date'])).'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$labelenddt.': </span>'.date("d M, Y h:i A", strtotime($retrieve_exam[0]['end_date'])).'</th>
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
        						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;"><span> '.$labelspinst.': </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span></th>
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
}

  


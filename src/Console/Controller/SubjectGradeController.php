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
class SubjectGradeController extends AppController
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
        if(!empty($_GET))
        {
            $classid = $this->request->query('classId');
		    $subjectid = $this->request->query('subId');
        }
        else
        {
            $classid = $this->request->data('classId');
		    $subjectid = $this->request->data('subId');
        }
        $subjects_table = TableRegistry::get('subjects');
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->toArray() ;
        $class_table = TableRegistry::get('class');
        $retrieve_class = $class_table->find()->where(['id' => $classid ])->toArray() ;
        if(!empty($this->Cookie->read('stid')))
        {
            $stdid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stdid = $this->Cookie->read('pid'); 
        }
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $exams_assessments_table = TableRegistry::get('exams_assessments');
        $submit_exams_table = TableRegistry::get('submit_exams');
        if(!empty($stdid))
        {
            $retrive_examids = $exams_assessments_table->find()->select([ 'id' ])->where(['subject_id' => $subjectid, 'class_id' => $classid ])->toArray() ;
            $exams_students = [];
            foreach($retrive_examids as $examids)
            {
                //echo $examids['id'];
                $retrieve_exams_students = $submit_exams_table->find()->where([ 'submit_exams.exam_id' => $examids['id'], 'md5(student_id)' => $stdid])->order(['submit_exams.exam_id' => 'DESC'])->toArray() ;
                
                foreach($retrieve_exams_students as $key =>$exm_dtl)
        		{
        		    
    				/*$retrieve_student = $student_table->find()->select(['student.adm_no' ,'student.f_name' , 'student.l_name'])->where(['id'=> $exm_dtl['student_id'] ])->toArray();
    				
        			$exm_dtl->student_adm_no = $retrieve_student[0]['adm_no'];
        			$exm_dtl->student_f_name = $retrieve_student[0]['f_name'];
        			$exm_dtl->student_l_name = $retrieve_student[0]['l_name'];*/
        			
        			$retrieve_examass = $exams_assessments_table->find()->select(['exams_assessments.id', 'exams_assessments.exam_type', 'exams_assessments.max_marks',  'exams_assessments.type', 'exams_assessments.end_date', 'exams_assessments.title', 'exams_assessments.file_name' ])->where(['id'=> $exm_dtl['exam_id'] ])->toArray();
        			$exm_dtl->examass_id = $retrieve_examass[0]['id'];
        			$exm_dtl->exam_type = $retrieve_examass[0]['exam_type'];
        			$exm_dtl->title = $retrieve_examass[0]['title'];
        			$exm_dtl->file_name = $retrieve_examass[0]['file_name'];
        			$exm_dtl->end_date = $retrieve_examass[0]['end_date'];
        			$exm_dtl->type = $retrieve_examass[0]['type'];
        			$exm_dtl->max_marks = $retrieve_examass[0]['max_marks'];
        			
        		}
        		
        		$exams_students[] = $retrieve_exams_students;
            } 
            $this->set("exams_sts_dtl", $exams_students);
            $this->set("classid", $classid); 
            $this->set("subjectid", $subjectid); 
            $this->set("subject_details", $retrieve_subjectname);
            $this->set("class_details", $retrieve_class);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function view()
    {   
        $employee_table = TableRegistry::get('employee');
		$subjectid = $this->request->data('classid');
		$classid = $this->request->data('subjectid');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_employee = $employee_table->find()->where(['subjects LIKE' => "%".$subjectid."%", 'grades LIKE' => "%".$classid."%" ])->toArray() ;
		
		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid];
		
        return $this->json($data);
    }
    
    public function updateclaim()
    {   
        if($this->request->is('ajax') && $this->request->is('post') )
        { 
            $submitexams_table = TableRegistry::get('submit_exams');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$subjectid = $this->request->data('subjectid');
    		$classid = $this->request->data('classid');
        	$subexmid = $this->request->data('sub_exm_id');
        	$claim = $this->request->data('claim');
            $update = $submitexams_table->query()->update()->set(['raise_claim' => $claim , 'claim_status' => 0 ])->where([ 'id' => $subexmid  ])->execute() ;                  
                    
    		if($update)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else{
                $res = [ 'result' => 'Claim not Updated'  ];
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $submitexm_table = TableRegistry::get('submit_exams');
        $exam_table = TableRegistry::get('exams_assessments');
        $examques_table = TableRegistry::get('exam_ass_question');
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $school_table = TableRegistry::get('company');
        $retrieve_submit = $submitexm_table->find()->where([ 'id' => $subexmid])->toArray();
        $exmid = $retrieve_submit[0]['exam_id'];
        
        
        $retrieve_exam = $exam_table->find()->where([ 'id' => $exmid ])->toArray();
        $retrieve_cls = $cls_table->find()->where([ 'id' => $retrieve_exam[0]['class_id'] ])->toArray();
        $retrieve_sub = $sub_table->find()->where([ 'id' => $retrieve_exam[0]['subject_id'] ])->toArray();
        
       /* $retrieve_questions = $que_table->find()->where([ 'exam_id' => $exmid ])->toArray();*/
        $schoolid = $retrieve_exam[0]['school_id'];
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    $questns = "";
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
	        $type = "Assignment";
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
                    						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>Subject: </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                    						</tr>
                    						<tr>
                						        <th style=" font-size: 14px;">
                						            <span><b>Class: </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'</span>
                						            
                						        </th>
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

  


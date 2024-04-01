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
class StudyguideController  extends AppController
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
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
        $guidecontent_table = TableRegistry::get('guide_content');
        $submit_exams_table = TableRegistry::get('submit_exams');
        $exams_assessments_table = TableRegistry::get('exams_assessments');
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->first() ;
        $retrieve_examass = $exams_assessments_table->find()->where(['class_id' => $classid , 'subject_id' => $subjectid, 'status' => 1, 'type' => 'Study Guide'])->toArray() ;
        
        foreach($retrieve_examass as $key =>$approvecoll)
		{
			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examass[$key]['subject_id'] ])->toArray();
			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
			
			$retrieve_class = $class_table->find()->select(['c_name', 'c_section'])->where(['id' => $retrieve_examass[$key]['class_id']  ])->toArray();
			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
			
			
			if(!empty($retrieve_examass[$key]['teacher_id']))
			{
    			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examass[$key]['teacher_id']  ])->toArray();
    			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']."-".$retrieve_employee[0]['l_name'];
			}
			else
			{
			    $approvecoll->teacher_name = 'School';
			}
			
			$approvecoll->from_tab = 'exams'; 
	
		}
		date_default_timezone_set('Africa/Kinshasa');
		$now = time();
		$retrieve_guide = $guidecontent_table->find()->where(['class_id' => $classid , 'subject_id' => $subjectid, 'status' => 1])->toArray() ;
        foreach($retrieve_guide as $key =>$guidecoll)
		{
		    $st = strtotime($guidecoll['start_date']);
		    $et = strtotime($guidecoll['end_date']);
		    if(($now >= $st) && ($now <= $et))
		    {
    			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examass[$key]['subject_id'] ])->toArray();
    			$guidecoll->subject_name = $retrieve_subject[0]['subject_name'];
    			
    			$retrieve_class = $class_table->find()->select(['c_name', 'c_section'])->where(['id' => $retrieve_examass[$key]['class_id']  ])->toArray();
    			$guidecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
    			
    			
    			if(!empty($retrieve_examass[$key]['teacher_id']))
    			{
        			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examass[$key]['teacher_id']  ])->toArray();
        			$guidecoll->teacher_name = $retrieve_employee[0]['f_name']."-".$retrieve_employee[0]['l_name'];
    			}
    			else
    			{
    			    $guidecoll->teacher_name = 'School';
    			}
    			
    			$guidecoll->from_tab = 'guide';
		    }
		}
		
		$results_array = array_merge($retrieve_examass,$retrieve_guide);
		
		$this->set("guidedtl", $retrieve_guide);
        $this->set("studentid", $stid);
        $this->set("classid", $classid); 
        $this->set("subjectid", $subjectid); 
        $this->set("subject_details", $retrieve_subjectname); 
        $this->set("content_details", $results_array); 
        $this->set("material", $results_array); 
        $this->viewBuilder()->setLayout('user');
    }
    
    public function view($id)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $xamsass_table = TableRegistry::get('exams_assessments');
        $guidecontent_table = TableRegistry::get('guide_content');
        $retrieve_exams = $xamsass_table->find()->where(['id' => $id ])->toArray() ;
        if(empty($retrieve_exams)){
            $retrieve_exams = $guidecontent_table->find()->where(['id' => $id])->toArray() ;
            $retrieve_exams[0]['from_tab'] = "guide";
        }else{
            $retrieve_exams[0]['from_tab'] = "exams";
        }
		$this->set("knowledge_details", $retrieve_exams); 
        $this->viewBuilder()->setLayout('userwj');
    }
    
    public function viewcustomexam()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $exmid = $this->request->data('id'); 
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
	            $chckbox = "<input type='checkbox' style='margin-left:25px; padding-top:5px;'>";
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
                    						    <th style="width: 100%; text-align:center ;  font-size: 14px;"><span><b>Subject: </b></span><span> '. $retrieve_sub[0]['subject_name'].' </span></th>
                    						</tr>
                    						<tr>
                						        <th style=" font-size: 14px;"> <span><b>Class: </b></span><span>'.$retrieve_cls[0]['c_name'].' - '.$retrieve_cls[0]['c_section'] .'</span></th>
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
        						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;"><span> Special Instructions: </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span></th>
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
            
	
	
	public function examupload()
	{
	    if($this->request->is('ajax') && $this->request->is('post') )
        { 
    	    $examid = $this->request->data('exam_id');
    		$studentid = $this->request->data('student_id');
    		$this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$activ_table = TableRegistry::get('activity');
    		$student_table = TableRegistry::get('student');
    		$submitexams_table = TableRegistry::get('submit_exams');
    		
    		$retrieve_student = $student_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $studentid ])->toArray() ;
    		
    		$stdid = $retrieve_student[0]['id'];
    		
    		if(is_array($_FILES['exam_upload']['name']))
    		{
    		    $data = '';
    		    $uploads = array();
    		    foreach($this->request->data['exam_upload'] as $new_up){
    		        //print_r($new_up);
    		        
    		        
                    $files = time().$new_up['name'];
                    array_push($uploads,$files);
                    $uploadpath = 'uploadExams/';
                    $uploadfile = $uploadpath.$files;
                    if(move_uploaded_file($new_up['tmp_name'], $uploadfile))
                    {
                        $this->request->data['exam_upload'] = $files; 
                    }
    		    }
    		    //print_r($uploads);
    		    $filename = implode(",", $uploads);
    		    $filetype = "images";
    		    /*$viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$data.'</p></div>';
                //print_r($viewpdf); die;
                $dompdf = new Dompdf();
                $dompdf->loadHtml($viewpdf);    
                
                $dompdf->setPaper('A4', 'Portrait');
                $dompdf->render();
                $dompdf->stream('new_pddf.pdf');*/
    		}
			elseif(!empty($this->request->data['exam_upload']['name']))
            {   
                if($this->request->data['exam_upload']['type'] == "application/pdf" || $this->request->data['exam_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"  )
                {
                    $filename = time().$this->request->data['exam_upload']['name'];
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
    		
    		if(!empty($filename))
    		{
    		    //$submitexams = $submitexams_table->newEntity();
                /*$submitexams->exam_id = $examid;
                $submitexams->student_id = $stdid;
                $submitexams->upload_exams = $filename;
                $submitexams->created_date = time();
        		$submitexams->status = 1;
                $submitexams->school_id = $retrieve_student[0]['school_id'];*/
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
    		    $res = [ 'result' => 'File Not Uploaded! Only pdf and doc files type is allowed.'  ];
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
    
    public function downloadpdf($exmid =null)
    {
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
	            $chckbox = "<input type='checkbox' style='margin-left:25px; padding-top:7px;'>";
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
        			<tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%;">
        						<tr>
        						    <th style="width: 100%; text-align:justify; margin-left:20px !important;   margin-bottom:20px !important; margin-top:20px !important; border-top:2px solid; border-bottom:2px solid; padding:30px 0;"><span> Special Instructions: </span><span style="font-weight:normal;">'.ucfirst($retrieve_exam[0]['special_instruction']).'</span></th>
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

  


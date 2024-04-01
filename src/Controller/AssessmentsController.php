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
class AssessmentsController  extends AppController
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
        
        if(!empty($this->Cookie->read('stid')))
        {
            $studentid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $studentid = $this->Cookie->read('pid'); 
        }
        //$studentid = $this->Cookie->read('stid');
        if(!empty($studentid))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $subjects_table = TableRegistry::get('subjects');
            $student_table = TableRegistry::get('student');
            $cls_table = TableRegistry::get('class');
            $submit_exams_table = TableRegistry::get('submit_exams');
            $exams_assessment_table = TableRegistry::get('exams_assessments');
            $sessid = $this->Cookie->read('sessionid');
            $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->toArray() ;
            $retrieve_cls = $cls_table->find()->where(['id' => $classid ])->toArray() ;
            $retrieve_assessment = $exams_assessment_table->find()->where(['subject_id' => $subjectid, 'class_id' => $classid, 'type' => 'Assessment', 'session_id' => $sessid ])->toArray() ;
            
            foreach($retrieve_assessment as $key =>$ass)
    		{
    			$retrieve_submitass = $submit_exams_table->find()->select(['id', 'upload_exams', 'file_type'])->where(['md5(student_id)' => $studentid, 'exam_id' => $ass['id']])->toArray() ;
    			//print_r($retrieve_submitass);exit;
    			if(!empty($retrieve_submitass)){
        			if(!empty($retrieve_submitass[0]['upload_exams']))
        			{
            			$ass->upload_exams = $retrieve_submitass[0]['upload_exams'];
            			$ass->file_type = $retrieve_submitass[0]['file_type'];
            			$ass->submit_id = $retrieve_submitass[0]['id'];
        			}
        			else
        			{$ass->upload_exams = "";
        			}
        		}else
    			{
    			    unset($retrieve_assessment[$key]); 
    			}
    		}

            $retrieve_submits = $submit_exams_table->find()->select(['exam_id', 'upload_exams'])->where(['md5(student_id)' => $studentid])->toArray() ;
            /*$retrieve_studentid = $student_table->find()->select(['id'])->where(['md5(id)' => $studentid])->toArray() ;
            $studentid = $retrieve_studentid[0]['id'];*/
            
            $this->set("classid", $classid); 
            $this->set("subjectid", $subjectid); 
            $this->set("subject_details", $retrieve_subjectname); 
            $this->set("cls_details", $retrieve_cls); 
            $this->set("assessment_details", $retrieve_assessment); 
            $this->set("submitex_details", $retrieve_submits); 
            $this->set("studId", $studentid); 
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function pdf($subexmid =null)
    {
        
        $submitexm_table = TableRegistry::get('submit_exams');
        $exam_table = TableRegistry::get('exams_assessments');
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
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => false));

        exit(0);
    }
    
    
    
    public function assupload()
	{
	    if($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
    	    $examid = $this->request->data('exam_id');
    		$studentid = $this->request->data('student_id');
    		$filetype = $this->request->data('type');
    		$activ_table = TableRegistry::get('activity');
    		$student_table = TableRegistry::get('student');
    		$submitexams_table = TableRegistry::get('submit_exams');
    		
    		$retrieve_student = $student_table->find()->select(['id', 'school_id'])->where(['md5(id)' => $studentid ])->toArray() ;
    		$stdid = $retrieve_student[0]['id'];
    		
    		
    		$retrieve_subass = $submitexams_table->find()->select(['id', 'school_id'])->where(['student_id' => $stdid , 'exam_id' => $examid])->count() ;
    		
    		if(!empty($this->request->data['assessment_upload']))
            {   
                $allfiles = [];
                foreach($this->request->data['assessment_upload'] as $ansshet) {
                   // echo $ansshet['type'];
                    if($ansshet['type'] == "application/pdf" || $ansshet['type'] == "image/jpeg" || $ansshet['type'] == "image/jpg" || $ansshet['type'] == "image/png" || $ansshet['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
                    {
                        $filenm = time()."_".$ansshet['name'];
                        $allfiles[] = $filenm;
                        $uploadpath = 'uploadExams/';
                        $uploadfile = $uploadpath.$filenm;
                        if(move_uploaded_file($ansshet['tmp_name'], $uploadfile))
                        {
                            $ansshet = $filenm; 
                        }
                    } 
                                    }
                $filename = implode(",", $allfiles);
                
                
            }
            else
            {
                $filename = "";
            }
            $lang = $this->Cookie->read('language');
            if($lang != "")
            {
                $lang = $lang;
            }
            else
            {
                $lang = 2;
            }
            
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
                if($langlbl['id'] == '1939') { $asntupld = $langlbl['title'] ; } 
                if($langlbl['id'] == '1938') { $fnuopda = $langlbl['title'] ; } 
            } 
    		
    		if(!empty($filename))
    		{
    		    if($retrieve_subass  == 0)
    		    {
        		    $submitexams = $submitexams_table->newEntity();
                    $submitexams->exam_id = $examid;
                    $submitexams->student_id = $stdid;
                    $submitexams->upload_exams = $filename;
                    $submitexams->created_date = time();
            		$submitexams->status = 1;
            		$submitexams->file_type = $filetype;
                    $submitexams->school_id = $retrieve_student[0]['school_id'];
                    //$update = $submitexams_table->query()->update()->set(['upload_exams' => $filename , 'created_date' => time() , 'status' => 1 ])->where([ 'id' => $examid  ])->execute() ;                  
                    $saved = $submitexams_table->save($submitexams);
    		    }
    		    else
    		    {
    		        $saved = $submitexams_table->query()->update()->set(['file_type' => $filetype, 'upload_exams' => $filename , 'created_date' => time() , 'status' => 1 ])->where([ 'exam_id' => $examid , 'student_id' => $stdid ])->execute() ;                  
    		    }
                if($saved)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else{
                    $res = [ 'result' => $asntupld  ];
                }
    		}
    		else
    		{
    		    $res = [ 'result' => $fnuopda  ];
    		}
        }
        else
        {
            $res = [ 'result' => 'Invalid Operation'  ];
        }
        return $this->json($res);
	}
			
}

  


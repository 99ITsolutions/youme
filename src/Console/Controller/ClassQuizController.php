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
class ClassQuizController  extends AppController
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
        $classid = $this->request->query('gradeid');
		$subjectid = $this->request->query('subid');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        $subjects_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $exams_assessments_table = TableRegistry::get('exams_assessments');
        $submit_exams_table = TableRegistry::get('submit_exams');
        $student_table  = TableRegistry::get('student');
        
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->toArray() ;
        $retrieve_class = $class_table->find()->where(['id' => $classid ])->toArray() ;
        $retrive_asstitles = $exams_assessments_table->find()->select([ 'id' , 'title'])->where(['subject_id' => $subjectid, 'class_id' => $classid , 'type' => 'Quiz'])->toArray() ;
        if(!empty($_POST))
        {
            $examid = $this->request->data('all_assessment');
            $retrive_examids = $exams_assessments_table->find()->select([ 'id'])->where(['id' => $examid])->toArray() ;
        }
        else
        {
            $retrive_examids = $exams_assessments_table->find()->select([ 'id'])->where(['subject_id' => $subjectid, 'class_id' => $classid , 'type' => 'Quiz'])->toArray() ;
        }
        $exams_students = [];
        foreach($retrive_examids as $examids)
        {
            //echo $examids['id'];
            $retrieve_exams_students = $submit_exams_table->find()->where([ 'submit_exams.exam_id' => $examids['id'] ])->toArray() ;
            
            foreach($retrieve_exams_students as $key =>$exm_dtl)
    		{
    		    
				$retrieve_student = $student_table->find()->select(['student.adm_no' ,'student.f_name' , 'student.l_name'])->where(['id'=> $exm_dtl['student_id'] ])->toArray();
				
    			$exm_dtl->student_adm_no = $retrieve_student[0]['adm_no'];
    			$exm_dtl->student_f_name = $retrieve_student[0]['f_name'];
    			$exm_dtl->student_l_name = $retrieve_student[0]['l_name'];
    			
    			$retrieve_examass = $exams_assessments_table->find()->select(['exams_assessments.id', 'exams_assessments.exam_type', 'exams_assessments.max_marks', 'exams_assessments.end_date', 'exams_assessments.title', 'exams_assessments.file_name' ])->where(['id'=> $exm_dtl['exam_id'] ])->toArray();
    			$exm_dtl->examass_id = $retrieve_examass[0]['id'];
    			$exm_dtl->exam_type = $retrieve_examass[0]['exam_type'];
    			$exm_dtl->title = $retrieve_examass[0]['title'];
    			$exm_dtl->file_name = $retrieve_examass[0]['file_name'];
    			$exm_dtl->end_date = $retrieve_examass[0]['end_date'];
    			$exm_dtl->max_marks = $retrieve_examass[0]['max_marks'];
    			
    		}
    		
    		$exams_students[] = $retrieve_exams_students;
        } 
        
        
        $this->set("classid", $classid); 
        $this->set("subjectid", $subjectid); 
        $this->set("ass_titles", $retrive_asstitles); 
        $this->set("subject_details", $retrieve_subjectname); 
        $this->set("class_details", $retrieve_class); 
        $this->set("ass_stdents", $exams_students); 
        $this->viewBuilder()->setLayout('user');
    }
    public function updateassreviews()
    {   
        if($this->request->is('ajax') && $this->request->is('post') )
        { 
            $submitexams_table = TableRegistry::get('submit_exams');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$subjectid = $this->request->data('subjectid');
    		$classid = $this->request->data('classid');
        	$subexmid = $this->request->data('sub_exm_id');
        	$grade = $this->request->data('grade');
        	$marks = $this->request->data('marks');
        	$comments = $this->request->data('comments');
            $claim_sts = $this->request->data('claim_sts');
            
            $retrive_exam_id = $submitexams_table->find()->select([ 'exam_id'])->where(['id' => $subexmid])->first() ;
            $exmid = $retrive_exam_id['exam_id'];
            
            $exams_assessments_table = TableRegistry::get('exams_assessments');
            $retrive_exam_marks = $exams_assessments_table->find()->select([ 'max_marks'])->where(['id' => $exmid])->first() ;
            $max_marks = $retrive_exam_marks['max_marks'];
            
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
                if($langlbl['id'] == '1935') { $mnetmm = $langlbl['title'] ; } 
                if($langlbl['id'] == '1937') { $rnu = $langlbl['title'] ; } 
                if($langlbl['id'] == '1936') { $uffpo = $langlbl['title'] ; } 
            } 
        	
        	
        	if(!empty($this->request->data['answersheet']))
            {   
                if($this->request->data['answersheet']['type'] == "application/pdf"  )
                {
                    //$picture =  $this->request->data['answersheet']['name'];
                    $filename = $this->request->data['answersheet']['name'];
                    $uploadpath = 'uploadevaluatedanswersheet/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['answersheet']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['answersheet'] = $filename; 
                    }
                } 
                else
                {
                    $filename = "";
                }
            }
        	if(!empty($filename))
        	{
        	    if($marks <= $max_marks && $marks > 0)
        	    {
                    if(empty($claim_sts))
                    {
                        $update = $submitexams_table->query()->update()->set(['marks' => $marks , 'submit_answersheet' => $filename, 'comments' => $comments, 'grade' => $grade , 'teacher_checked_status' => 1 ])->where([ 'id' => $subexmid  ])->execute() ;                  
                    }
                    else
                    {
                        $update = $submitexams_table->query()->update()->set(['marks' => $marks , 'claim_status' => 1, 'submit_answersheet' => $filename, 'comments' => $comments, 'grade' => $grade , 'teacher_checked_status' => 1 ])->where([ 'id' => $subexmid  ])->execute() ;                  
                    }
            		if($update)
                    {   
                        $res = [ 'result' => 'success'  ];
                    }
                    else{
                        $res = [ 'result' => $rnu  ];
                    }
        	    }
        	    else
        	    {
        	        $res = [ 'result' => $mnetmm  ];
        	    }
        	}
        	else
        	{
        	    $res = [ 'result' => $uffpo  ];
        	}
            
        }
        else
        {
            $res = [ 'result' => 'Invalid Operation'  ];
        }
        return $this->json($res);
    }
			
}

  


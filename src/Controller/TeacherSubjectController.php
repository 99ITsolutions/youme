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
class TeacherSubjectController  extends AppController
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
        $sid = $this->request->query('subid');
        $gid = $this->request->query('gradeid');
        $subjects_table = TableRegistry::get('subjects');
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $session_id = $this->Cookie->read('sessionid');
     
        if(!empty($session_id))
        {
            $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;
            $retrieve_class = $class_table->find()->select(['id' ,'c_name' , 'c_section', 'school_sections' ])->where(['id' => $gid])->toArray() ;
            $retrieve_students = $student_table->find()->select(['id' ,'f_name' , 'l_name' ])->where(['class' => $gid, 'session_id' => $session_id])->toArray() ;
            // dd($retrieve_students);
            /*$data = ['subjects' => $retrieve_subjects, 'class' => $retrieve_class, 'students' => $retrieve_students];
            return $this->json($data);*/
            $this->set("subject_details", $retrieve_subjects); 
            $this->set("class_details", $retrieve_class); 
            $this->set("studentdetails", $retrieve_students); 
            $this->set("gid", $gid); 
            $this->set("sid", $sid); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function getexamass()
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
            if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
        }
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $studid = $this->request->data('id');
        $sid = $this->request->data('sub');
        $gid = $this->request->data('cls');
        $subjects_table = TableRegistry::get('subjects');
        $examass_table = TableRegistry::get('exams_assessments');
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
        if(!empty($compid))
        {
            $retrieve_examass = $examass_table->find()->where(['school_id' => $compid, 'type !=' => 'Study Guide', 'session_id' => $session_id, 'subject_id' => $sid, 'class_id' => $gid])->order(['id' => 'DESC'])->toArray() ;
            $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->first() ;
            $retrieve_class = $class_table->find()->select(['id' ,'c_name' , 'c_section', 'school_sections' ])->where(['id' => $gid])->first() ;
            
            $subname = $retrieve_subjects['subject_name'];
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']."(".$retrieve_class['school_sections'].")";
            
            $data = "";
            $datavalue = array();
            foreach($retrieve_examass as $val)
            {
                $data .= "<tr>";
                $type = $val['type'];
                $startdt = date("d-m-Y", strtotime($val['start_date']));
                
                if( $type == "Exams" ) { 
                    $etype = $exmlbl; 
                }
                elseif( $type == "Quiz" ) { 
                    $etype = $quizlbl; 
                }
                elseif( $type == "Assessment" ) { 
                    $etype = $asslbl; 
                }
                
                
                if($etype != "")
                {
                    $exmtype = $etype;
                    $data .= "<td><a href='".$this->base_url."teacherSubject/details/".$val['id']."/".$studid."'>".$subname." ".$etype." (".$val['exam_type'].") - ".$startdt."</a></td>";
                }          
                else
                {
                     $data .= "<td><a href='".$this->base_url."teacherSubject/details/".$val['id']."/".$studid."'>".$subname." ".$etype." - ".$startdt."</a></td>";
                }
                $data .= "</tr>";
            }
            $datavalue['html']= $data;
            return $this->json($datavalue);
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
      
    }
    
    public function details($exmid, $studid)
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $examass_table = TableRegistry::get('exams_assessments');
        $subjects_table = TableRegistry::get('subjects');
        $submitexm_table = TableRegistry::get('submit_exams');
        $class_table = TableRegistry::get('class');
        $retrieve_examass = $examass_table->find()->where(['id' => $exmid])->first();
       
        $retrieve_submtexm = $submitexm_table->find()->where(['exam_id' => $exmid, 'student_id' => $studid ])->first();
        
         $classid = $retrieve_examass['class_id'];
		 $subjectid = $retrieve_examass['subject_id'];
        
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->first() ;
        $retrieve_classname = $class_table->find()->where(['id' => $classid ])->first() ;
        
        $student_table = TableRegistry::get('student');
        $retrieve_students = $student_table->find()->select(['id' ,'f_name' , 'l_name' ])->where(['id' => $studid])->first() ;
        
        $this->set("studentdtl", $retrieve_students); 
        $this->set("subexamdtl", $retrieve_submtexm); 
        $this->set("examdtl", $retrieve_examass); 
        $this->set("class_details", $retrieve_classname); 
        $this->set("subject_details", $retrieve_subjectname); 
        
        $this->viewBuilder()->setLayout('user');
    }
    
    

}

  




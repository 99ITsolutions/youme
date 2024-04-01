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
class ClassGradeController extends AppController
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
        $classid = $this->request->query('gradeid');
		$subjectid = $this->request->query('subid');
		$session_id = $this->Cookie->read('sessionid');
		$compid =$this->request->session()->read('company_id');
		if(!empty($compid)) {
            $subjects_table = TableRegistry::get('subjects');
            $class_table = TableRegistry::get('class');
            $examass_table = TableRegistry::get('exams_assessments');
            $submitexm_table = TableRegistry::get('submit_exams');
            
            $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->first() ;
            $retrieve_classname = $class_table->find()->where(['id' => $classid ])->first() ;
            $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'exams_assessments.title', 'exams_assessments.type', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'student.id', 'student.f_name', 'student.l_name', 'student.adm_no'])->join([
                'submit_exams' => 
    			[
    				'table' => 'submit_exams',
    				'type' => 'LEFT',
    				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
    			],
    			'student' => 
    			[
    				'table' => 'student',
    				'type' => 'LEFT',
    				'conditions' => 'submit_exams.student_id = student.id'
    			]
    		])->where(['submit_exams.status' => 1 ,'exams_assessments.class_id' => $classid, 'exams_assessments.subject_id' => $subjectid, 'exams_assessments.session_id' => $session_id, 'exams_assessments.school_id' => $compid ])->toArray() ;
            
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $this->set("classid", $classid); 
            $this->set("subjectid", $subjectid); 
            $this->set("subject_details", $retrieve_subjectname); 
            $this->set("class_details", $retrieve_classname); 
            $this->set("grades_details", $retrieve_grades); 
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
			
}

  


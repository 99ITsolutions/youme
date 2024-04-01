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
class AllGradesController extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $studid = $this->Cookie->read('stid');
        if(!empty($studid)) {
            $subjects_table = TableRegistry::get('subjects');
            $student_table = TableRegistry::get('student');
            $examass_table = TableRegistry::get('exams_assessments');
            $submitexm_table = TableRegistry::get('submit_exams');
            $classsub_table = TableRegistry::get('class_subjects');
            $subject_table = TableRegistry::get('subjects');
            $empcls_table = TableRegistry::get('employee_class_subjects');
            
            $retrieve_students = $student_table->find()->where(['md5(id)' => $studid ])->first();
            $clsid = $retrieve_students['class'];
            $schoolid = $retrieve_students['school_id'];
        
            $get_subjects = $classsub_table->find()->where(['class_id' => $clsid, 'school_id' => $schoolid])->first();
        
            if($get_subjects->subject_id !='')
            {
                $subjects = explode(',',$get_subjects->subject_id);
                
                $html_content = [];
                foreach($subjects as $subject){
                    $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                    $subjectid[] = $subjects_data->id;
                    $subjectname[] = $subjects_data->subject_name;
                }
                $subjectdata['id'] = $subjectid;
                $subjectdata['subject_name'] = $subjectname;
            }
        
            $get_employees = $empcls_table->find()->select(['employee.id', 'employee.f_name', 'employee.l_name'])->join(
            [
	            'employee' => 
                [
                    'table' => 'employee',
                    'type' => 'LEFT',
                    'conditions' => 'employee.id = employee_class_subjects.emp_id'
                ],
            ])->where(['employee_class_subjects.class_id' => $clsid])->group(['employee.id'])->toArray();
     
            if(!empty($_POST))
            {
                $sub_id = $this->request->data('subjects');
                $exams_ass = $this->request->data('exams_ass');
                $exmtype = $this->request->data('exam_type');
                $tchr_id = $this->request->data('listteacher');
                
                $filters  = '';
                if($tchr_id != "" && $sub_id == "" && $exams_ass == "" && $exmtype == "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid ,  'exams_assessments.teacher_id' =>  $tchr_id])->toArray() ;
                    
                    
                }
                elseif($tchr_id == "" && $sub_id != "" && $exams_ass == "" && $exmtype == "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid ,  'exams_assessments.subject_id' =>  $sub_id])->toArray() ;
                    
                    
                }
                elseif($tchr_id == "" && $sub_id == "" && $exams_ass != "" && $exmtype == "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid ,  'exams_assessments.type' =>  $exams_ass])->toArray() ;
                    
                   
                }
                elseif($tchr_id == "" && $sub_id == "" && $exams_ass == "" && $exmtype != "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid ,  'exams_assessments.exam_type' =>  $exmtype])->toArray() ;
                    
                    
                   
                }
                
                elseif($tchr_id == "" && $sub_id == "" && $exams_ass != "" && $exmtype != "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid , 'exams_assessments.exam_type' =>  $exmtype , 'exams_assessments.type' => $exams_ass ])->toArray() ;
                    
                    
                }
                
                elseif($tchr_id == "" && $sub_id != "" && $exams_ass != "" && $exmtype == "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid , 'exams_assessments.subject_id' =>  $sub_id , 'exams_assessments.type' => $exams_ass ])->toArray() ;
                    
                    
                }
                elseif($tchr_id != "" && $sub_id == "" && $exams_ass != "" && $exmtype == "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid , 'exams_assessments.teacher_id' =>  $tchr_id , 'exams_assessments.type' => $exams_ass ])->toArray() ;
                    
                    
                }
                
                elseif($tchr_id == "" && $sub_id != "" && $exams_ass != "" && $exmtype != "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid ,'exams_assessments.exam_type' => $exmtype, 'exams_assessments.exam_type' => $exmtype, 'exams_assessments.subject_id' =>$sub_id])->toArray() ;
                    
                    
                    
                }
                elseif($tchr_id != "" && $sub_id == "" && $exams_ass != "" && $exmtype != "")
                {
                    $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid , 'exams_assessments.exam_type' => $exmtype, 'exams_assessments.exam_type' =>$exmtype, 'exams_assessments.teacher_id' => $tchr_id])->toArray() ;
                    
                    
                    
                }
                
                
                elseif($tchr_id != "" && $sub_id != "" && $exams_ass != "" && $exmtype != "")
                {
                     $retrieve_grades = $examass_table->find()->select(['exams_assessments.exam_type', 'exams_assessments.exam_period', 'company.comp_name', 'exams_assessments.type', 'subjects.subject_name', 'exams_assessments.id', 'exams_assessments.start_date', 'exams_assessments.max_marks', 'submit_exams.id', 'submit_exams.marks', 'submit_exams.grade', 'employee.id', 'employee.f_name', 'employee.l_name'])->join([
                        'submit_exams' => 
            			[
            				'table' => 'submit_exams',
            				'type' => 'LEFT',
            				'conditions' => 'submit_exams.exam_id = exams_assessments.id'
            			],
            			'employee' => 
            			[
            				'table' => 'employee',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.teacher_id = employee.id'
            			],
            			'subjects' => 
            			[
            				'table' => 'subjects',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.subject_id = subjects.id'
            			],
            			'company' => 
            			[
            				'table' => 'company',
            				'type' => 'LEFT',
            				'conditions' => 'exams_assessments.school_id = company.id'
            			]
            		])->where(['submit_exams.status' => 1 ,'md5(submit_exams.student_id)' => $studid, 'exams_assessments.school_id' => $schoolid , 'exams_assessments.exam_type' => $exmtype, 'exams_assessments.exam_type' => $exmtype , 'exams_assessments.subject_id' => $sub_id , 'exams_assessments.teacher_id' =>  $tchr_id])->toArray() ;
                    
                }
            }
            else
            {
                $retrieve_grades = '';
                $sub_id = '';
                $exams_ass = '';
                $exmtype = '';
                $tchr_id = '';
            }
            $this->set("sub_id", $sub_id);
            $this->set("exams_ass", $exams_ass); 
            $this->set("exmtype", $exmtype);
            $this->set("tchr_id", $tchr_id);
            
            $this->set("grade_details", $retrieve_grades);
            $this->set("emp_details", $get_employees); 
            $this->set("subjectdata", $subjectdata); 
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    
			
}

  


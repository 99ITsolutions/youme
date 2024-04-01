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
class TeacherattendanceController  extends AppController
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
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid');
                $employee_table = TableRegistry::get('employee');
                $empclssub_table = TableRegistry::get('employee_class_subjects');
                if(!empty($tid))
                {
                    $retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
                        [
        		            'employee_class_subjects' => 
                            [
                                'table' => 'employee_class_subjects',
                                'type' => 'LEFT',
                                'conditions' => 'employee.id = employee_class_subjects.emp_id'
                            ],
                            'class' => 
                            [
                                'table' => 'class',
                                'type' => 'LEFT',
                                'conditions' => 'class.id = employee_class_subjects.class_id'
                            ],
                            'subjects' => 
                            [
                                'table' => 'subjects',
                                'type' => 'LEFT',
                                'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                            ],
                            
        
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->toArray();
                        
    				/*$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
                    $schoolid  = $retrieve_employees[0]['school_id'];
                    $tchrid  = $retrieve_employees[0]['id'];
        			foreach($retrieve_employees as $key =>$emp_coll)
            		{
            			$gradeid = explode(",",$emp_coll['grades']);
            			$subid = explode(",",$emp_coll['subjects']);
            			$i = 0;
            			$empgrades = [];
            			foreach($gradeid as $gid)
            			{
            			    $retrieve_class = $class_table->find()->where([ 'id '=> $gid ])->toArray();
            				foreach($retrieve_class as $grad)
            				{
            					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section']. "(".$grad['school_sections'].")";				
            				}
            				$i++;
            				$gradenames = implode(",", $empgrades);
            				
            			}
            			$j = 0;
            			$empsub = [];
            			foreach($subid as $sid)
            			{
            			    $retrieve_subject = $subjects_table->find()->where([ 'id '=> $sid ])->toArray();
            				foreach($retrieve_subject as $subj)
            				{
            					$empsub[$j] = $subj['subject_name'];				
            				}
            				$j++;
            				$subjectnames = implode(",", $empsub);
            				
            			}
            			
            			$emp_coll->subjectName = $subjectnames;
            			$emp_coll->gradesName = $gradenames;
            		}*/	

    				$this->set("employees_details", $retrieve_empclses); 
    				$this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
	
		
            public function studentlist()
            {
               	$tid = $this->Cookie->read('tid');
               	if(!empty($tid))
               	{
               	    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
    				$employee_list = TableRegistry::get('employee');
    				$classsub = explode(",",$this->request->data('classid'));
    				$classid = $classsub[0];
    				$retrieve_emp = $employee_list->find()->select([ 'id', 'school_id'])->where(['md5(id)' => $tid  ])->toArray();
    				$school_id = $retrieve_emp[0]['school_id'];
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['school_id' => $school_id, 'class' => $classid ])->toArray();
    				$data =  '<option value="">Choose Student</option>';
    				$stuids = [];
    				foreach($retrieve_stdnt as $stdntids)
    				{				
    					$stuids[] = $stdntids->id;				
    				}
    				$allstuids = implode(",", $stuids);
    				$data .=  '<option value="all'.$allstuids.'">All</option>';
    				foreach($retrieve_stdnt as $stdnt)
    				{				
    					$data .= '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';				
    				}
    				
    				 return $this->json($data);   
               	}
               	else
               	{
               	    return $this->redirect('/login/') ;   
               	}
            }
            
            public function imstudentlist()
            {
               	$tid = $this->Cookie->read('tid');
               	$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_list = TableRegistry::get('student');
				$employee_list = TableRegistry::get('employee');
				if(!empty($tid))
               	{
    				$classsub = explode(",",$this->request->data('classid'));
    				$classid = $classsub[0];
    				$retrieve_emp = $employee_list->find()->select([ 'id', 'school_id'])->where(['md5(id)' => $tid  ])->toArray();
    				$school_id = $retrieve_emp[0]['school_id'];
    				$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no'])->where(['school_id' => $school_id, 'class' => $classid ])->toArray();
    				$data =  '<option selected="selected">Choose Student</option>';
    				foreach($retrieve_stdnt as $stdnt)
    				{				
    					$data .= '<option value="'.$stdnt->id.'">'.$stdnt->f_name.' '.$stdnt->l_name.' ('.$stdnt->adm_no.')</option>';				
    				}
    				
    				 return $this->json($data);   
                }
               	else
               	{
               	    return $this->redirect('/login/') ;   
               	}
            }
            
            
            

}

  


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
class StudentsubjectsController  extends AppController
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
        
            if(!empty($this->Cookie->read('stid')))
            {
                $stid = $this->Cookie->read('stid'); 
            }
            elseif(!empty($this->Cookie->read('pid')))
            {
                $stid = $this->Cookie->read('pid'); 
            }
            $student_table = TableRegistry::get('student');
            $subjects_table = TableRegistry::get('subjects');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid =$this->request->session()->read('student_id');
    		$session_id = $this->Cookie->read('sessionid');
            if(!empty($stid)) {
            $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.class', 'student.school_id', 'class_subjects.subject_id'])->join(['class_subjects' => 
    							[
    							'table' => 'class_subjects',
    							'type' => 'LEFT',
    							'conditions' => 'class_subjects.class_id = student.class'
    						]
    					])->where(['md5(student.id)' => $stid ])->toArray() ;
    		
    		foreach($retrieve_student_table as $key =>$sub_coll)
    		{
    			$subid = explode(",",$sub_coll['class_subjects']['subject_id']);
    			$i = 0;
    			$subjectsname = [];
    			$subjectsids = [];
    			foreach($subid as $sid)
    			{
    			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;	
    				foreach($retrieve_subjects as $rstd)
    				{
    					$subjectsname[$i] = $rstd['subject_name'];	
    					$subjectsids[$i] = $rstd['id'];	
    				}
    				$i++;
    				$snames = implode(",", $subjectsname);
    				$sids = implode(",", $subjectsids);
    				
    			}
    			
    			$sub_coll->subjects_name = $snames;
    			$sub_coll->subjects_ids = $sids;
    			
    			
    		}	
    		
            
            $this->set("student_subjects", $retrieve_student_table); 
            $this->viewBuilder()->setLayout('user');
            }
            else
            {
                return $this->redirect('/login/') ;    
            }
    }
    
    public function subjects($id)
    {
            $classid = $this->request->data('classId');
            $subId = $this->request->data('subId');
            if(!empty($this->Cookie->read('stid')))
            {
                $stid = $this->Cookie->read('stid'); 
            }
            elseif(!empty($this->Cookie->read('pid')))
            {
                $stid = $this->Cookie->read('pid'); 
            }
            $student_table = TableRegistry::get('student');
            $subjects_table = TableRegistry::get('subjects');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid =$this->request->session()->read('student_id');
    		$session_id = $this->Cookie->read('sessionid');
    
            if(!empty($stid)) {
            $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.class', 'student.school_id', 'class_subjects.subject_id'])->join(['class_subjects' => 
    							[
    							'table' => 'class_subjects',
    							'type' => 'LEFT',
    							'conditions' => 'class_subjects.class_id = student.class'
    						]
    					])->where(['md5(student.id)' => $stid ])->toArray() ;
    		
    		foreach($retrieve_student_table as $key =>$sub_coll)
    		{
    			$subid = explode(",",$sub_coll['class_subjects']['subject_id']);
    			$i = 0;
    			$subjectsname = [];
    			foreach($subid as $sid)
    			{
    			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;	
    				foreach($retrieve_subjects as $rstd)
    				{
    					$subjectsname[$i] = $rstd['subject_name'];				
    				}
    				$i++;
    				$snames = implode(",", $subjectsname);
    				
    			}
    			
    			$sub_coll->subjects_name = $snames;
    			
    			
    		}	
    		
    		$data = ['student_table' => $retrieve_student_table, 'subId' => $subId, 'clsId' => $classid];
		
            return $this->json($data);
            }
            else
            {
                return $this->redirect('/login/') ;    
            }
            
        
    }
    
    public function subjectdtl()
    {   
        $employee_table = TableRegistry::get('employee');
        $emp_cls_sub_table = TableRegistry::get('employee_class_subjects');
        $subjects_table = TableRegistry::get('subjects');
		$subjectid = $this->request->data('subjectid');
		$classid = $this->request->data('classid');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_employee_clssub = $emp_cls_sub_table->find()->where(['subject_id' => $subjectid, 'class_id' => $classid ])->first() ;
      // print_r($retrieve_employee_clssub);
        $tid = $retrieve_employee_clssub['emp_id'];
        $retrieve_employee = $employee_table->find()->where(['id' => $tid])->toArray() ;
        
		$retrieve_subjectName = $subjects_table->find()->where(['id' => $subjectid])->toArray() ;
		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid, 'subname' => $retrieve_subjectName[0]['subject_name']];
		
        return $this->json($data);
    }
    
    public function subjectdtlss()
    {   
        if ($this->request->is('ajax'))
        {
            
            $employee_table = TableRegistry::get('employee');
            $subjects_table = TableRegistry::get('subjects');
    		$subjectid = $this->request->data('subjectid');
    		$classid = $this->request->data('classid');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_employee = $employee_table->find()->where(['subjects LIKE' => "%".$subjectid."%", 'grades LIKE' => "%".$classid."%" ])->toArray() ;
    		$retrieve_subjectName = $subjects_table->find()->where(['id' => $subjectid])->toArray() ;
    		
    		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid, 'subname' => $retrieve_subjectName[0]['subject_name']];
    		
            return $this->json($data);
        }
    }
    
    public function subjectsDetails()
    {   
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('user');
    }
			
}

  


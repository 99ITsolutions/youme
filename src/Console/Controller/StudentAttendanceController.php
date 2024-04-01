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
class StudentAttendanceController   extends AppController
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
                $this->viewBuilder()->setLayout('user');
            }
            
            public function subject()
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $subjects_table = TableRegistry::get('subjects');
                $attndnc_table = TableRegistry::get('attendance');
                $sid =$this->request->session()->read('student_id');
        		$session_id = $this->Cookie->read('sessionid');
                if(!empty($stid))
                {
                    $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.class', 'student.school_id', 'class_subjects.subject_id'])->join(['class_subjects' => 
            							[
            							'table' => 'class_subjects',
            							'type' => 'LEFT',
            							'conditions' => 'class_subjects.class_id = student.class'
            						]
            					])->where(['md5(student.id)' => $stid ])->toArray() ;
            		$stucls = $retrieve_student_table[0]['class'];
            		$subid = explode(",",$retrieve_student_table[0]['class_subjects']['subject_id']);
            		$subjctid = $subid[0];
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
                    $this->set("student_subjects", $retrieve_student_table); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            
            public function day()
            {  $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                $this->viewBuilder()->setLayout('user');
            }

            public function getsclattendance()
            {   
                if(!empty($this->Cookie->read('stid')))
                {
                    $stid = $this->Cookie->read('stid'); 
                }
                elseif(!empty($this->Cookie->read('pid')))
                {
                    $stid = $this->Cookie->read('pid'); 
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_table = TableRegistry::get('student');
                $subjects_table = TableRegistry::get('subjects');
                if(!empty($stid))
                {
                    $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.class', 'student.school_id'])->where(['md5(id)' => $stid ])->toArray() ;
            		$stucls = $retrieve_student_table[0]['class'];
            		$studid = $retrieve_student_table[0]['id'];
            		$school_id = $retrieve_student_table[0]['school_id'];
                    $attndnc_table = TableRegistry::get('attendance_school');
                    $retrieve_attndnc = $attndnc_table->find()->where(['class_id' => $stucls, 'student_id' => $studid, 'school_id' => $school_id ])->toArray() ;
                    return $this->json($retrieve_attndnc);        
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            
            
            public function getattendance()
            {   
                if(!empty($this->Cookie->read('stid')))
                {
                    $stid = $this->Cookie->read('stid'); 
                }
                elseif(!empty($this->Cookie->read('pid')))
                {
                    $stid = $this->Cookie->read('pid'); 
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_table = TableRegistry::get('student');
                $subjects_table = TableRegistry::get('subjects');
                
                if(!empty($stid))
                {
                    $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.class', 'student.school_id', 'class_subjects.subject_id'])->join(['class_subjects' => 
            							[
            							'table' => 'class_subjects',
            							'type' => 'LEFT',
            							'conditions' => 'class_subjects.class_id = student.class'
            						]
            					])->where(['md5(student.id)' => $stid ])->toArray() ;
            		$stucls = $retrieve_student_table[0]['class'];
            		$subid = explode(",",$retrieve_student_table[0]['class_subjects']['subject_id']);
            		$subjctid = $subid[0];
            		$studid = $retrieve_student_table[0]['id'];
            		
                    $attndnc_table = TableRegistry::get('attendance');
                    
                    $retrieve_attndnc = $attndnc_table->find()->where(['subject_id' => $subjctid, 'class_id' => $stucls, 'student_id' => $studid])->toArray() ;
                    
                   // print_r($retrieve_attndnc); die;
                    return $this->json($retrieve_attndnc);      
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            
            public function subjectattendance()
            {   
                if(!empty($this->Cookie->read('stid')))
                {
                    $stid = $this->Cookie->read('stid'); 
                }
                elseif(!empty($this->Cookie->read('pid')))
                {
                    $stid = $this->Cookie->read('pid'); 
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
        		$stucls = $this->request->query('classid');
        		$subjctid = $this->request->query('subjectid');
                $attndnc_table = TableRegistry::get('attendance');
                if(!empty($stid))
                {
                    $retrieve_attndnc = $attndnc_table->find()->where(['subject_id' => $subjctid, 'class_id' => $stucls, 'md5(student_id)' => $stid])->toArray() ;
                    return $this->json($retrieve_attndnc);   
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            


            
}

  


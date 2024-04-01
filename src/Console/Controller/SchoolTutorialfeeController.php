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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SchoolTutorialfeeController   extends AppController
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
                $schoolid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                $tutorialfee_table = TableRegistry::get('tutorial_fee');
                
                $class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $schoolid, 'active' => 1])->toArray() ;
                
                $retrieve_tutorialfee = $tutorialfee_table->find()->select(['tutorial_fee.fee'  ,'tutorial_fee.id'  ,'tutorial_fee.frequency'  , 'class.c_name'  ,  'class.c_section' , 'class.school_sections'  ,'subjects.subject_name', 'session.startyear', 'session.endyear' ])->join([
                    'class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = tutorial_fee.class_id'
                    ]
                ])->join([
                    'subjects' => 
                        [
                        'table' => 'subjects',
                        'type' => 'LEFT',
                        'conditions' => 'subjects.id = tutorial_fee.subject_id'
                    ]
                ])->join([
                    'session' => 
                        [
                        'table' => 'session',
                        'type' => 'LEFT',
                        'conditions' => 'session.id = tutorial_fee.session_id'
                    ]
                ])->where(['tutorial_fee.school_id' => $schoolid  ])->toArray();
                
                $employee_table = TableRegistry::get('employee');
                
        		$current_year = date('Y');
        		$current_m_y = date('F Y');
        		$session_table = TableRegistry::get('session'); //->select(['id' ,'startyear','endyear'])
        		$retrieve_session = $session_table->find()->where([ 'startyear <= '=> $current_year, 'endyear >= '=> $current_year ])->order(['startyear' => 'ASC'])->toArray() ;
        	
        		
        		$this->set("class_details", $retrieve_class);
                $this->set("session_details", $retrieve_session);
                $this->set("tutorial_details", $retrieve_tutorialfee); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function getsubjects()
            {   
                if($this->request->is('post'))
                {
                    $id = $this->request->data['classId'];
                    $schoolid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $schoolid])->first();
                    $html_content='';
                    if($get_subjects->subject_id !=''){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content .= '<option value="">Choose Subjects</option>';
                        
                        foreach($subjects as $subject){
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                            $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'">'.$subjects_data->subject_name.'</option>';
                        }
                    }
                    
                    return $this->json($html_content);

                }  
            }
            
            
            
            
            public function getteachers()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $subjectId = $this->request->data['subjectId'];
                    $schoolid = $this->request->session()->read('company_id');
                    //$start_year  = $this->request->data['sessionId'];
                    $start_year = $this->Cookie->read('sessionid');
                    $employee_subject_table = TableRegistry::get('employee_class_subjects');
                    
                    $employee_table = TableRegistry::get('employee');
                    $get_subjects = $employee_subject_table->find()->where(['class_id' => $id, 'subject_id' => $subjectId])->first(); 
                    $html_content = '';
                    if($get_subjects != ''){
                        $employee_table_data = $employee_table->find()->where(['id' => $get_subjects->emp_id, 'school_id' => $schoolid])->first(); 
                        $html_content = '<option value="">Choose Subjects</option><option value='.$employee_table_data->id.'>'.$employee_table_data->f_name.' '.$employee_table_data->l_name.'</option>';
                    }
                   
                    $freq_data ='';
                    if($start_year){
                        
                        $fee_structure_table = TableRegistry::get('tutorial_fee');
                        $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'subject_id' => $subjectId, 'school_id' => $schoolid, 'session_id' => $start_year])->toArray();
                        
                        
                        $session_table = TableRegistry::get('session');
                        $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                        //print_r($retrieve_session_table[0]->endmonth);exit;
                        $amount =0;
                        if(!empty($retrieve_feestructure_table)){
                            $frequency = $retrieve_feestructure_table[0]->frequency;
                            $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                            
                            if($frequency == 'yearly'){
                                $amount = $retrieve_feestructure_table[0]->fee; 
                                $freq_data .= '<option value='.$srtMonth.'-'.$endMonth.'>'.$srtMonth.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'half yearly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 2; 
                                $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                                $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                                $freq_data .= '<option value='.$srtMonth.'-'.$effectiveDate.'>'.$srtMonth.'-'.$effectiveDate.'</option>';
                                $freq_data .= '<option value='.$effectiveDate2.'-'.$endMonth.'>'.$effectiveDate2.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'quarterly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 4; 
                                $session1 = date('F', strtotime("+2 months", strtotime($srtMonth)));
                                $session2_strt = date('F', strtotime("+1 months", strtotime($session1)));
                                $session2 = date('F', strtotime("+2 months", strtotime($session2_strt)));
                                $session3_strt = date('F', strtotime("+1 months", strtotime($session2)));
                                $session3 = date('F', strtotime("+2 months", strtotime($session3_strt)));
                                $session4_strt = date('F', strtotime("+1 months", strtotime($session3)));
                                $freq_data .= '<option value='.$srtMonth.'-'.$session1.'>'.$srtMonth.'-'.$session1.'</option>';
                                $freq_data .= '<option value='.$session2_strt.'-'.$session2.'>'.$session2_strt.'-'.$session2.'</option>';
                                $freq_data .= '<option value='.$session3_strt.'-'.$session3.'>'.$session3_strt.'-'.$session3.'</option>';
                                $freq_data .= '<option value='.$session4_strt.'-'.$endMonth.'>'.$session4_strt.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'monthly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 12; 
                                $freq_data .= '<option value='.$srtMonth.'>'.$srtMonth.'</option>';
                                for($i=1; $i <= 10; $i++){
                                    $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                                    $srtMonth = $month;
                                    $freq_data .= '<option value='.$month.'>'.$month.'</option>';
                                }
                                $freq_data .= '<option value='.$endMonth.'>'.$endMonth.'</option>';
                            }
                            
                            
                        }
                        
                    }
                    
                   /* $new_content [0] = $html_content;
                    $new_content [1] = $freq_data;
                     $new_content [2] = $amount;*/
                     $new_content = $html_content;
                    return $this->json($new_content);

                }  
            }
            
            public function addtut(){  
                
                if ( $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $schoolid = $this->request->session()->read('company_id');
                    $tid = $this->request->data('teacher_id');
                    $tutorialfee_table = TableRegistry::get('tutorial_fee');
                    
                    $employee_table = TableRegistry::get('employee');
                    $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
                
                    $activ_table = TableRegistry::get('activity');
                    $retrieve_fee = $tutorialfee_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class'),'teacher_id' => $tid,'subject_id' => $this->request->data('subjects'), 'session_id' => $this->request->data('start_year'), 'school_id' => $schoolid  ])->count() ;
                   
                    if($retrieve_fee == 0 )
                    {
                        $fee = $tutorialfee_table->newEntity();
                        $fee->class_id = $this->request->data('class');
                        $fee->subject_id = $this->request->data('subjects');
                        $fee->frequency = $this->request->data('frequency');
                        $fee->session_id = $this->request->data('start_year');
                        $fee->fee = $this->request->data('amount'); 
                        $fee->teacher_id = $this->request->data('teacher_id');
                        $fee->school_id = $schoolid;
                        $fee->created_date = time();
                                     
                        $amt = $this->request->data('amount');       
                        if($amt > 0)
                        {
                            if($saved = $tutorialfee_table->save($fee) )
                            {     
                                $strucid = $saved->id;
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Fee Added Successfully!"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($strucid); 
                                $activity->created = strtotime('now');
                                $save = $activ_table->save($activity) ;
    
                                if($save)
                                {   
                                    $res = [ 'result' => 'success'  , 'tutid' => $strucid ];
                                }
                                else
                                { 
                                    $res = [ 'result' => 'activity'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'failed'  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Amount should not be in negative.'  ];
                        }
                    } 
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
             public function update(){   
                if($this->request->is('post')){
                    $id = $this->request->data['id'];
                    $tutorial_fee_table = TableRegistry::get('tutorial_fee');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_tutorial_fee_table = $tutorial_fee_table->find()->where([ 'id '=> $id])->first() ;
            	    
            	    $class_id = $retrieve_tutorial_fee_table->class_id;
                    $schoolid = $this->request->session()->read('company_id');
                   
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $class_id, 'school_id' => $schoolid])->first(); 
                    $html_content = '';
                    if($get_subjects->subject_id != ''){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content = '<option value="">Choose Subject</option>';
                        
                        foreach($subjects as $subject){
                            
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                            if($subjects_data->id == $retrieve_tutorial_fee_table->subject_id){ $selected_data ="selected"; }else{ $selected_data =""; }
                            $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'" '.$selected_data.'>'.$subjects_data->subject_name.'</option>';
                        }
                    }
                    $subjectId = $retrieve_tutorial_fee_table->subject_id;
                    
                    $employee_subject_table = TableRegistry::get('employee_class_subjects');
                    $employee_table = TableRegistry::get('employee');
                    $get_emp_subjects = $employee_subject_table->find()->where(['class_id' => $class_id, 'subject_id' => $subjectId])->first(); 
                    $hhtml = '';
                    if($get_emp_subjects != ''){
                        $employee_table_data = $employee_table->find()->where(['id' => $get_emp_subjects->emp_id, 'school_id' => $schoolid])->first(); 
                        if($employee_table_data->id == $retrieve_tutorial_fee_table->teacher_id){ $selectedt_data ="selected"; }else{ $selectedt_data =""; }
                        $hhtml = '<option value="">Choose Teacher</option><option value="'.$employee_table_data->id.'" id="tea_'.$employee_table_data->id.'" '.$selectedt_data.'>'.$employee_table_data->f_name.' '.$employee_table_data->l_name.'</option>';
                    }
                    
                    $result = array();
                    $result[0] = $retrieve_tutorial_fee_table;
                    $result[1] = $html_content;
                    $result[2] = $hhtml;
                    
                    return $this->json($result);
                }  
            }
           
            public function edittut()
            {  
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $schoolid = $this->request->session()->read('company_id');
                    $tid = $this->Cookie->read('tid');
                    $tutorial_fee_table = TableRegistry::get('tutorial_fee');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_fee = $tutorial_fee_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class') ,'subject_id' => $this->request->data('subjects') , 'md5(teacher_id)' => $tid , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $schoolid  ])->count() ;

                    if($retrieve_fee == 0 )
                    {   
                        $id = $this->request->data('eid');
                        $class_id = $this->request->data('class');
                        $frequency = $this->request->data('frequency');
                        $amount = $this->request->data('amount');
                        $session = $this->request->data('start_year');
                        $frequency = $this->request->data('frequency');
                        if($amount > 0)
                        {
                            if( $tutorial_fee_table->query()->update()->set(['class_id' => $this->request->data('class') ,'subject_id' => $this->request->data('subjects') , 'fee' => $amount, 'frequency' => $frequency, 'session_id' => $session])->where([ 'id' => $id  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Fee updated successfully"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($id); 
                                $activity->created = strtotime('now');
                                $save = $activ_table->save($activity) ;

                                if($save)
                                {   
                                    $res = [ 'result' => 'success', 'feesid' =>  $id   ];
                                }
                                else
                                { 
                                    $res = [ 'result' => 'activity'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => 'failed'  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Amount should not be in negative.'  ];
                        }
                    } 
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
                    }
                        
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }
            

            public function delete()
            {
                $rid = $this->request->data('val') ;
                $tutorialcontent_table = TableRegistry::get('tutorial_fee');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
                    
                $del = $tutorialcontent_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                if($del)
				{
                    $activity = $activ_table->newEntity();
                    $activity->action =  "successfully Deleted!"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($rid)    ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');

                    if($saved = $activ_table->save($activity) )
                    {
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'not delete'  ];
                }    
                

                return $this->json($res);
            }
            
            public function add(){   
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
        		$class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                
                $session_table = TableRegistry::get('session');
                $retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear'])->order(['startyear' => 'ASC'])->toArray();
                
                $this->set("class_details", $retrieve_class);
                $this->set("session_details", $retrieve_session);
                
                $this->viewBuilder()->setLayout('user');
            }
            
            public function getstudent()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $start_year = $this->request->data['start_year'];
                    $student_table = TableRegistry::get('student');
                    $compid = $this->request->session()->read('company_id');
                    $retrieve_student = $student_table->find()->where(['class' => $id, 'school_id' => $compid])->toArray(); 
                    $all_data = array(); $all_data[0]= $retrieve_student;  $freq_data = ''; $amount ='';
                   
                    if($start_year){
                        $fee_structure_table = TableRegistry::get('tutorial_fee');
                        $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'session_id' => $start_year])->toArray();
                        
                        
                        $session_table = TableRegistry::get('session');
                        $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                        //print_r($retrieve_session_table[0]->endmonth);exit;
                        
                        if(!empty($retrieve_feestructure_table)){
                            $frequency = $retrieve_feestructure_table[0]->frequency;
                            $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                            
                            if($frequency == 'yearly'){
                                $amount = $retrieve_feestructure_table[0]->fee;
                                $freq_data .= '<option value='.$srtMonth.'-'.$endMonth.'>'.$srtMonth.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'half yearly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 2;
                                $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                                $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                                $freq_data .= '<option value='.$srtMonth.'-'.$effectiveDate.'>'.$srtMonth.'-'.$effectiveDate.'</option>';
                                $freq_data .= '<option value='.$effectiveDate2.'-'.$endMonth.'>'.$effectiveDate2.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'quarterly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 4;
                                $session1 = date('F', strtotime("+2 months", strtotime($srtMonth)));
                                $session2_strt = date('F', strtotime("+1 months", strtotime($session1)));
                                $session2 = date('F', strtotime("+2 months", strtotime($session2_strt)));
                                $session3_strt = date('F', strtotime("+1 months", strtotime($session2)));
                                $session3 = date('F', strtotime("+2 months", strtotime($session3_strt)));
                                $session4_strt = date('F', strtotime("+1 months", strtotime($session3)));
                                $freq_data .= '<option value='.$srtMonth.'-'.$session1.'>'.$srtMonth.'-'.$session1.'</option>';
                                $freq_data .= '<option value='.$session2_strt.'-'.$session2.'>'.$session2_strt.'-'.$session2.'</option>';
                                $freq_data .= '<option value='.$session3_strt.'-'.$session3.'>'.$session3_strt.'-'.$session3.'</option>';
                                $freq_data .= '<option value='.$session4_strt.'-'.$endMonth.'>'.$session4_strt.'-'.$endMonth.'</option>';
                            }
                            
                            if($frequency == 'monthly'){
                                $amount = $retrieve_feestructure_table[0]->fee / 12; 
                                $freq_data .= '<option value='.$srtMonth.'>'.$srtMonth.'</option>';
                                for($i=1; $i <= 10; $i++){
                                    $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                                    $srtMonth = $month;
                                    $freq_data .= '<option value='.$month.'>'.$month.'</option>';
                                }
                                $freq_data .= '<option value='.$endMonth.'>'.$endMonth.'</option>';
                            }
                            
                            $amount = round($amount);
                            
                        }
                        
                    }
                    
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $compid])->first(); 
                    
                    $html_content = '';
                    if(!empty($get_subjects)){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content = '<option value="">Choose Subject</option>';
                        
                        foreach($subjects as $subject){
                            
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $compid])->first(); 
                            $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'">'.$subjects_data->subject_name.'</option>';
                        }
                    }
                    
                    
                    $all_data[1]= $freq_data;
                    $all_data[2]= $amount;
                    $all_data[3]= $html_content;
                    
                    return $this->json($all_data);
                }  
        }
        
        public function getstudentsdata(){   
            if($this->request->is('post')){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());    
                $id = $this->request->data['classId'];
                $start_year = $this->request->data['start_year'];
                $student = $this->request->data['student'];
                $id = $this->request->data['classId'];
                $teacher_id = $this->request->data['teacher_id'];
                $subject = $this->request->data['subject'];
                    
                $student_fee_table = TableRegistry::get('student_tutorial_fee');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $stud_table = TableRegistry::get('student');
                
                $fee_structure_table = TableRegistry::get('tutorial_fee');
                $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'teacher_id' => $teacher_id, 'subject_id' => $subject, 'school_id' => $compid, 'session_id' => $start_year])->toArray();
                
                
                $session_table = TableRegistry::get('session');
                $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                $datass=array();
                if(!empty($retrieve_feestructure_table)){
                    $frequency = $retrieve_feestructure_table[0]->frequency;
                    $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                    $yr = $retrieve_session_table[0]->startyear."-".$retrieve_session_table[0]->endyear;
                    if($frequency == 'yearly'){
                        $freq_amount = $retrieve_feestructure_table[0]->fee;
                        $freq_data[0] = $srtMonth.'-'.$endMonth;
                    }
                    
                    if($frequency == 'half yearly'){
                        $freq_amount = $retrieve_feestructure_table[0]->fee / 2;
                        $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                        $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                        $freq_data[0]= $srtMonth.'-'.$effectiveDate;
                        $freq_data[1]= $effectiveDate2.'-'.$endMonth;
                    }
                    
                    if($frequency == 'quarterly'){
                        $freq_amount = $retrieve_feestructure_table[0]->fee / 4;
                        $session1 = date('F', strtotime("+2 months", strtotime($srtMonth)));
                        $session2_strt = date('F', strtotime("+1 months", strtotime($session1)));
                        $session2 = date('F', strtotime("+2 months", strtotime($session2_strt)));
                        $session3_strt = date('F', strtotime("+1 months", strtotime($session2)));
                        $session3 = date('F', strtotime("+2 months", strtotime($session3_strt)));
                        $session4_strt = date('F', strtotime("+1 months", strtotime($session3)));
                        $freq_data[0]= $srtMonth.'-'.$session1;
                        $freq_data[1]= $session2_strt.'-'.$session2;
                        $freq_data[2]=$session3_strt.'-'.$session3;
                        $freq_data[3]= $session4_strt.'-'.$endMonth;
                    }
                    
                    if($frequency == 'monthly'){
                        $freq_amount = $retrieve_feestructure_table[0]->fee / 12;
                        $freq_data[0]= $srtMonth;
                        for($i=1; $i <= 10; $i++){
                            $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                            $srtMonth = $month;
                            $freq_data[$i]= $month;
                        }
                        $freq_data[11]= $endMonth;
                    }
                    
                    $freq_amount = round($freq_amount);
                    
                     $data = '';
                     //$grph = '';
                    foreach($freq_data as $ddata){
                        $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'student_id'=>$student, 'subject_id'=>$subject, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                       $amount =0;
                        foreach($retrieve_studfee_table as $key =>$fee_s){
                            $amount += $fee_s->fee;
                        }
                        
                        $freq_strt = date('m-d-Y', strtotime($srtMonth.' '.$retrieve_session_table[0]->startyear));
                        $freq_endd = date('m-d-Y', strtotime($endMonth.' '.$retrieve_session_table[0]->endyear));
                        $current_S = date('m-d-Y', strtotime($ddata.' '.$retrieve_session_table[0]->startyear));
                        $current_e = date('m-d-Y', strtotime($ddata.' '.$retrieve_session_table[0]->endyear));
                        $current = time();
                        if(strtotime($current_e) > strtotime($freq_endd)){
                            $use_t = $current_S;
                            $aaa= 'aaa';
                        }else{
                            $use_t = $current_e; $aaa= 'bbb';
                        }
                        
                        if(strtotime($use_t) < $current){
                            $due_amt = $freq_amount - $amount;  
                        }else{
                            $due_amt1 = '';
                        }
                        
                        $dueAmt = $freq_amount - $amount; $due_amt1 = '$'.$due_amt; 
                        if($amount > 0){ $action = '<button type="button" data-freq='.$ddata.' data-sty='.$start_year.' data-subjectid='.$subject.' data-teacherid='.$teacher_id.' data-classid='.$id.' data-student='.$student.' class="btn btn-sm btn-outline-secondary edittutfreqfee" data-toggle="modal"  data-target="#edittutfreqfee" title="Edit"><i class="fa fa-edit"></i></button>'; } 
                        else{ $action = ''; }
                        if($due_amt == 0){ $download = '<a href="'.$this->base_url.'/SchoolTutorialfee/pdf/'.$student.'/'.$ddata.'/'.$subject.'/'.$teacher_id.'/'.$id.'/'.$start_year.'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>'; } else{ $download = ''; }
                        $data .=    '<tr>
                                            <td>
                                                <span class="mb-0 font-weight-bold">'.$ddata.'</span>
                                            </td>
                                            <td>$'.$freq_amount.'</td>
                                            <td>$'.$amount.'</td>
                                            <td>'.$due_amt1.'</td>
                                            <td>'.$action.' ' .$download.'</td>;
                                        </tr>';
                                        
                        $amt[] = $amount;
                        $due[] = $dueAmt;
                        
                      
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
                    
                    //echo $lang;
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
                        if($langlbl['id'] == '1363') { $dueamtlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1362') { $paidamtlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '359') { $paidlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1967') { $duelabel = $langlbl['title'] ; } 
                    } 
                    
                    $paidamt = array_sum($amt);
                    $dueamt = array_sum($due);
                    $grph[] = ['label' => $paidamtlabel, 'symbol' => $paidlabel, 'y' => $paidamt ];
                    $grph[] = ['label' => $dueamtlbl, 'symbol' => $duelabel, 'y' => $dueamt ];
                    $datass = ['html' => $data, 'graph' => $grph, 'sessionyear' => $yr];
        		}
                return $this->json($datass);
            }  
        }
        
        public function addstudentfees(){   
                if ($this->request->is('ajax') && $this->request->is('post') )
                { 
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_fee_table = TableRegistry::get('student_tutorial_fee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $class_id = $this->request->data('class');
                    $student_id = $this->request->data('student');
                    $start_year = $this->request->data('start_year');
                    $teacherid = $this->request->data('teacher');
                    $subjectid = $this->request->data('subject');
                    $frequency = $this->request->data('frequency');
                    $amount = $this->request->data('amount');
                    
                    $get_fee_table = TableRegistry::get('student_tutorial_fee'); $query = $get_fee_table->find(); 
                    $am_paid = $query->select(['sum' => $query->func()->sum('student_tutorial_fee.fee')])->where([ 'school_id '=> $compid,'subject_id '=> $subjectid,'session_id '=> $start_year, 'class_id '=> $class_id,'student_id'=>$student_id,'frequency'=>$frequency])->first();
                    if(!empty($am_paid)){ $paid = $am_paid->sum;} else{ $paid =0;  }
                   /* //print_r($paid);exit;
                    $want_pay = $paid + $amount;*/
                    
                    $fee_structure_table = TableRegistry::get('tutorial_fee');
                    $retrieve_feestruc = $fee_structure_table->find()->where(['school_id '=> $compid, 'teacher_id '=> $teacherid,'subject_id '=> $subjectid,'session_id '=> $start_year, 'class_id '=> $class_id])->first();
            
                    if($retrieve_feestruc->frequency == 'yearly'){
                        $tot_amount = $retrieve_feestruc->fee;
                    }
                    
                    if($retrieve_feestruc->frequency == 'half yearly'){
                        $tot_amount = $retrieve_feestruc->fee / 2;
                    }
                    
                    if($retrieve_feestruc->frequency == 'quarterly'){
                        $tot_amount = $retrieve_feestruc->fee / 4;
                    }
                    
                    if($retrieve_feestruc->frequency == 'monthly'){
                        $tot_amount = $retrieve_feestruc->fee / 12; 
                    }
                    $total_amt = round($tot_amount);
                    //$total_amt = round($tot_amount);
                    //$due = $total_amt - $paid;
                    
                    
                    if($amount != $total_amt){
                        $msg = 'Please pay amount $'.$total_amt.'.';  if($paid > 0 ){ $msg = 'Fee already paid.'; }
                        $res = [ 'result' =>  $msg ];
                    }else{
                        $student_login_table = TableRegistry::get('student_tutorial_logins');
                        $count_login = $student_login_table->find()->select(['id' ])->where([ 'school_id '=> $compid, 'teacher_id '=> $teacherid,'subject_id '=> $subjectid,'session_id '=> $start_year, 'class_id '=> $class_id,'student_id'=>$student_id])->count();
                        //print_r($count_login);exit;
                        if($count_login == 0){
                            $stud_table = TableRegistry::get('student');
                           // $adm_no = $stud_table->find()->select(['id' ,'adm_no','f_name','email'])->where(['id' => $student_id])->first();
                            
                            $adm_no = $stud_table->find()->select(['student.adm_no', 'student.f_name', 'student.email', 'student.id', 'class.c_name', 'class.c_section'])->join([
                                'class' => 
                                    [
                                    'table' => 'class',
                                    'type' => 'LEFT',
                                    'conditions' => 'class.id = student.class'
                                ]
                            ])->where(['student.id' => $student_id  ])->first();
                            
                            $pass = str_replace(' ', '', $adm_no->f_name);
                            $login_d = $student_login_table->newEntity();
                            $login_d->class_id = $this->request->data('class');
                            $login_d->student_id = $this->request->data('student');
                            $login_d->session_id = $this->request->data('start_year');
                            $login_d->teacher_id = $this->request->data('teacher');
                            $login_d->subject_id = $this->request->data('subject');
                            $login_d->school_id = $compid;
                            $login_d->email = $adm_no->adm_no.$subjectid;
                            $login_d->password = $pass.'!234';
                            $login_d->created_date = time();
                            $student_login_table->save($login_d);
                            
                            $to = $adm_no->email;
                            $username = $adm_no->f_name;
                            $company_table = TableRegistry::get('company');
                            $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
                            
                            $subjects_table = TableRegistry::get('subjects');
                            $retrieve_sub_name = $subjects_table->find()->where(['id'=> $subjectid ])->first();
                            $sub_name = $retrieve_sub_name->subject_name;
                            $from = $retrieve_comp->email;
                            $name = $retrieve_comp->comp_name;
                            $subject = 'Login Details for Tutorial Center - '.$sub_name.' ('.$adm_no->class['c_name'].'-'.$adm_no->class['c_section'].')';
                            $htmlContent = '<tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;"><multiline>Congratulations! You’ve made a great decision.</multiline></td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline>Let’s get started...</multiline></td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline>Your new account has now been created for Tutorial Center.</multiline></td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline>Please login to your account using the following information: </multiline></td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:0px;  text-align:left;"><multiline> <b>Username:</b> '.$adm_no->adm_no.$subjectid.'</multiline></td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline> <b>Password:</b> '.$pass.'!234</multiline></td></tr>
                            <tr><td class="p0-15-30" style="padding: 0px 0px 10px 0px;"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px; text-align:left;">Thanks</td></tr><tr><td class="text" style="color:#191919; font-size:16px; line-height:30px; text-align:left;">'.$name.'</td></tr></table></td></tr>';
                            $this->sendEmailwithoutattach($to,$from,$name,$subject,$htmlContent,$username,'formalmessage');
                            
                        }
                        $fee = $student_fee_table->newEntity();
                        
                        $fee->class_id = $this->request->data('class');
                        $fee->student_id = $this->request->data('student');
                        $fee->frequency = $this->request->data('frequency');
                        $fee->fee = $this->request->data('amount');
                        $fee->session_id = $this->request->data('start_year');
                        $fee->teacher_id = $this->request->data('teacher');
                        $fee->subject_id = $this->request->data('subject');
                        $fee->school_id = $compid;
                        $fee->submission_date = time();
                        $fee->created_date = time();
                                           
                        if($saved = $student_fee_table->save($fee) )
                        {     
                            $strucid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Tutorial Fees Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($strucid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'  ];
                            }
                            else
                            { 
                                $res = [ 'result' => 'activity'  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'failed'  ];
                        }
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
            public function editfreq(){  
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $class_id = $this->request->data['classid'];
                    $student_id = $this->request->data['student'];
                    $frequency = $this->request->data['freq'];
                    $start_year = $this->request->data['sty'];
                    $teacherid = $this->request->data['teacherid'];
                    $subjectid = $this->request->data['subjectid'];
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_fee_table = TableRegistry::get('student_tutorial_fee');
                    $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid, 'teacher_id '=> $teacherid,'subject_id '=> $subjectid,'session_id '=> $start_year, 'class_id '=> $class_id,'student_id'=>$student_id, 'frequency' => $frequency])->order(['id' => 'ASC'])->toArray();
                    $result ='';
                    foreach($retrieve_studfee_table as $fee){
                        $result .= '
                                <div class="col-md-12">
                                <input type="hidden" id="eid" value="'.$fee->id.'"  name="new_d['.$fee->id.'][id]" >
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">'.date('d M Y',$fee->submission_date).'</span>
                                  </div>
                                  <input type="number" class="form-control" id="esubject_name" required name="new_d['.$fee->id.'][amount]" value="'.$fee->fee.'" placeholder="Amount*" aria-describedby="basic-addon3">
                                </div>
                                </div>
                            
                        </div>';
                    }
                    return $this->json($result);
                }
                
            }
            
            public function edittutfreqfee(){  
            if ($this->request->is('ajax') && $this->request->is('post') ){
                $student_fee_table = TableRegistry::get('student_tutorial_fee');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $n_data = $this->request->data('new_d');
                
                foreach($n_data as $n_d){
                    $id = $n_d['id'];
                    $amount = $n_d['amount'];
                    
                    if( $student_fee_table->query()->update()->set(['fee' => $amount])->where([ 'id' => $id  ])->execute())
                    {   
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Tutorial Fee update successfully!"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($id); 
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity) ;

                        if($save)
                        {   
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        { 
                            $res = [ 'result' => 'activity'  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }          
                }
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];
            }

            return $this->json($res);
        }
        
        public function pdf($student = null, $ddata=null, $subject=null, $teacher=null, $class=null, $session_id=null)
            {
                $compid = $this->request->session()->read('company_id');
                $student_fee_table = TableRegistry::get('student_tutorial_fee');
                $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'teacher_id'=>$teacher,'subject_id'=>$subject,'class_id'=>$class,'student_id'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $session_table = TableRegistry::get('session');
                $retrieve_session = $session_table->find()->where([ 'id '=> $session_id])->first();
                
                $student_table = TableRegistry::get('student');
                $class_table = TableRegistry::get('class');
                $retrieve_student_table = $student_table->find()->select(['student.adm_no'  , 'student.f_name'  , 'student.l_name'  , 'student.id'  , 'student.class'  , 'class.c_name'  , 'class.id'  , 'class.c_section'  ,'student.session_id','session.startyear','session.endyear','session.startmonth','session.endmonth' ])->join([
                    'class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = student.class'
                    ]
                ])->join([
                    'session' => 
                        [
                        'table' => 'session',
                        'type' => 'LEFT',
                        'conditions' => 'session.id = student.session_id'
                    ]
                ])->where(['student.id' => $student  ])->first();
                
                $new_date = date('d/m/Y',$retrieve_studfee_table[0]->submission_date);
                $data_html = '
                <tr><th style="width: 100%; border:1px solid #ccc; text-align:center">Fee Receipt</th></tr>
                <tr>
                <td style="padding:0px !important;">
                <table style="width: 100%; border:1px solid #ccc;">
                    <tr>
                        <td style="width: 60%;">Date: '.$new_date.'</td>
                        <td>Reciept No: '.$retrieve_studfee_table[0]->id.'</td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">Adm No.: '.$retrieve_student_table->adm_no.'</td>
                        <td>Session: '.$retrieve_session->startyear.'-'.$retrieve_session->endyear.'</td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">Name: '.$retrieve_student_table->f_name.' '.$retrieve_student_table->l_name.'</td>
                        <td>Installment: '.$ddata.'</td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">Class: '.$retrieve_student_table->class['c_name'].' - '.$retrieve_student_table->class['c_section'].'</td>
                        <td></td>
                    </tr>
                </table>
                </td>
                </tr>';
                
                $title= $retrieve_student_table->adm_no."_".$retrieve_studfee_table[0]->id;
                $fee_structure_table = TableRegistry::get('tutorial_fee');
                $retrieve_feestruc = $fee_structure_table->find()->where(['school_id' => $compid , 'class_id' =>$retrieve_student_table->class['id'], 'teacher_id'=>$teacher,'subject_id'=>$subject,'session_id'=> $session_id])->first();
            
                $fee_data ='<tr><td style="padding:0px !important;"><table id="fee_detail" style=" width: 100%; border:1px solid #ccc;"><tr><th style="text-align:left">Sr. No.</th> <th style="text-align:left">Description</th> <th style="text-align:left">Due</th><th style="text-align:left">Paid</th></tr>';
            
                if($retrieve_feestruc->frequency == 'yearly'){
                    $amount = $retrieve_feestruc->fee;
                }
                
                if($retrieve_feestruc->frequency == 'half yearly'){
                    $amount = $retrieve_feestruc->fee / 2;
                }
                
                if($retrieve_feestruc->frequency == 'quarterly'){
                    $amount = $retrieve_feestruc->fee / 4;
                }
                
                if($retrieve_feestruc->frequency == 'monthly'){
                    $amount = $retrieve_feestruc->fee / 12; 
                }
                $amount = round($amount);
                $total_amt = round($amount);
                $paid_amt =0;
                foreach($retrieve_studfee_table as $std_fee){
                    $paid_amt += $std_fee->fee;
                }
                $due_amount = $amount - $paid_amt;
                $fee_data .='<tr><td style="text-align:left">1</td><td style="text-align:left">Tutorial Fee</td><td style="text-align:left">'.$due_amount.'</td><td style="text-align:left">'.$amount.'</td></tr>';
                
                $number = $paid_amt;
        		$no = floor($number);
        		$point = round($number - $no, 2) * 100;
        		$hundred = null;
        		$digits_1 = strlen($no);
        		$i = 0;
        		$str = array();
        		$words = array('0' => '', '1' => 'one', '2' => 'Two',
        		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        		'13' => 'Thirteen', '14' => 'Fourteen',
        		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        		'60' => 'Sixty', '70' => 'Seventy',
        		'80' => 'Eighty', '90' => 'Ninety');
        		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        		while ($i < $digits_1) {
        		 $divider = ($i == 2) ? 10 : 100;
        		 $number = floor($no % $divider);
        		 $no = floor($no / $divider);
        		 $i += ($divider == 10) ? 1 : 2;
        		 if ($number) {
        			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        			$str [] = ($number < 21) ? $words[$number] .
        				" " . $digits[$counter] . $plural . " " . $hundred
        				:
        				$words[floor($number / 10) * 10]
        				. " " . $words[$number % 10] . " "
        				. $digits[$counter] . $plural . " " . $hundred;
        		 } else $str[] = null;
        		}
        		$str = array_reverse($str);
        		$amt_words = implode('', $str);
        		
        		
               $fee_data .='</table></td></tr>
               
                <tr>
                    <td style="padding:0px !important;">
                        <table style="width: 100%; border:1px solid #ccc;">
                            <tr>
                                <td style="width: 27%;">Total Amount:</td>
                                <td style="width: 53%;"></td>
                                <td style="width: 20%;">$'.$total_amt.'</td>
                            </tr>
                            <tr>
                                <td style="width: 27%;">Paid Amount:</td>
                                <td style="width: 53%;"></td>
                                <td style="width: 20%;">$'.$paid_amt.'</td>
                            </tr>
                            <tr>
                                <td style="width: 27%;">Amount (In Words):</td>
                                <td style="width: 60%;"> '.$amt_words.' Only (In US Dollar).</td>
                                <td style="width: 13%;"></td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>';
               $school_table = TableRegistry::get('company');
                $retrieve_school = $school_table->find()->where([ 'id' => $compid])->toArray();
                $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:150px !important;">';
                $n =1;
                
                $header = '<style>th,td{ padding: 5px 20px; } th{ background: #ccc;} #fee_detail td{ border: 1px solid #ccc}</style><table style=" width: 100%;">
                            <tbody>
                                <tr>
                                    <td  style="width: 100%;">
                                        <table style="width: 100%;  ">
                                        <tr>
                                            <td  style="width: 100%; float:left; ">
                                                <table style="width: 100%;  ">
                                                    <tr>
                                                        <td>
                                                            <table style="width: 100%;  ">
                                                            <tr>
                                                            <td  style="width: 20%; text-align:left; padding: 5px 0px;"><span> '.$school_logo.' </span></td>
                                                            <td  style="width: 70%; text-align:left; padding: 5px;">
                                                            <p style=" font-size: 22px; font-weight:bold; ">'.ucfirst($retrieve_school[0]['comp_name']).'</p>
                                                            <p> '.ucfirst($retrieve_school[0]['add_1']).' ,  '.ucfirst($retrieve_school[0]['city']).' </p>
                                                            <p> <b> Contact Number: </b>'.ucfirst($retrieve_school[0]['ph_no']).' </p>
                                                            <p> <b> Email: </b>'.ucfirst($retrieve_school[0]['email']).' </p>
                                                            </td>
                                                            </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            
                                        </tr>
                                        </table>
                                    </td>
                            </tr>
                            
                            '.$data_html.' '.$fee_data.' 
                        </tbody>
                        </table>';
                
                //$title = $retrieve_sub[0]['subject_name'];
                
                $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p></div>';
                //print_r($viewpdf); die;
                $dompdf = new Dompdf();
                $dompdf->loadHtml($viewpdf);    
                
                $dompdf->setPaper('A4', 'Portrait');
                $dompdf->render();
                $dompdf->stream($title.".pdf");

                exit(0);
            }
            
            public function students()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $compid = $this->request->session()->read('company_id');
				$class_table = TableRegistry::get('class');
				$tutfee_table = TableRegistry::get('student_tutorial_fee');
				$student_table = TableRegistry::get('student');
				$subjects_table = TableRegistry::get('subjects');
				$retrieve_class = $class_table->find()->where([ 'school_id '=> $compid, 'active' => 1 ])->toArray();
				$retrieve_subject = $subjects_table->find()->where([ 'school_id '=> $compid, 'status' => 1 ])->toArray();
				$retrieve_student = [];
				if(!empty($_POST)){  $subjectid = $this->request->data['subject_list']; $classid = $this->request->data['class_list']; $studentid = $this->request->data['student_list'];
                    $retrieve_student = $student_table->find()->where(['class' => $classid, 'school_id' => $compid])->toArray(); 
				}else{ $subjectid = ""; $classid = "";  $studentid =""; }
				$wherecon['student_tutorial_fee.school_id'] = $compid;
				if($classid != ''){ $wherecon['student_tutorial_fee.class_id'] = $classid;  } if($subjectid != ''){ $wherecon['student_tutorial_fee.subject_id'] = $subjectid; }
				if($studentid != ''){ $wherecon['student_tutorial_fee.student_id'] = $studentid; }
                $retrieve_fee = $tutfee_table->find()->select(['student_tutorial_fee.id', 'student_tutorial_fee.subject_id', 'student_tutorial_fee.student_id','student_tutorial_fee.teacher_id', 'student_tutorial_fee.class_id', 'student_tutorial_fee.frequency', 'subjects.subject_name',  'class.c_name' , 'class.c_section', 'class.school_sections', 'student_tutorial_fee.fee', 'student.f_name', 'student.l_name', 'student_tutorial_fee.submission_date'])
                ->join(['student' => 
						[
						'table' => 'student',
						'type' => 'LEFT',
						'conditions' => 'student.id = student_tutorial_fee.student_id'
					]
				])->join(['class' => 
						[
						'table' => 'class',
						'type' => 'LEFT',
						'conditions' => 'class.id = student_tutorial_fee.class_id'
					]
				])->join(['subjects' => 
						[
						'table' => 'subjects',
						'type' => 'LEFT',
						'conditions' => 'subjects.id = student_tutorial_fee.subject_id'
					]
				])->where($wherecon)->order(['student_tutorial_fee.id' => 'desc'])->toArray() ;
				
				foreach($retrieve_fee as $alogin){
				    
				    $tutlogin_table = TableRegistry::get('student_tutorial_logins');
				    $retrieve_log = $tutlogin_table->find()->where([ 'school_id '=> $compid, 'class_id' => $alogin->class_id, 'subject_id' => $alogin->subject_id, 'student_id' => $alogin->student_id, 'teacher_id' => $alogin->teacher_id ])->first();
				    $alogin->username = $retrieve_log->email;
				    $alogin->password = $retrieve_log->password;
				}
				$this->set("class_details", $retrieve_class); 
				$this->set("subject_details", $retrieve_subject);
				$this->set("student_details", $retrieve_student);
				$this->set("classid", $classid); 
				$this->set("subjectid", $subjectid); 
				$this->set("studentid", $studentid); 
				$this->set("fee_details", $retrieve_fee); 
				$this->viewBuilder()->setLayout('user');
            }
            
            
            public function subjectstudentbyclass()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $student_table = TableRegistry::get('student');
                    $compid = $this->request->session()->read('company_id');
                    $retrieve_student = $student_table->find()->where(['class' => $id, 'school_id' => $compid])->toArray(); 
                    
                    $all_data = array(); $all_data[0]= $retrieve_student; 
                    
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $compid])->first(); 
                    
                    $html_content = '';
                    if(!empty($get_subjects)){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content = '<option value="">Choose Subject</option>';
                        
                        foreach($subjects as $subject){
                            
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $compid])->first(); 
                            $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'">'.$subjects_data->subject_name.'</option>';
                        }
                    }
                    $all_data[1]= $html_content;
                    
                    return $this->json($all_data);
                }  
        }
        
    public function gettchrs()
    {
        $subid = $this->request->data('subid');
        $clsid = $this->request->data('clsid');
        $emp_table = TableRegistry::get('employee');
        $empclssub_table = TableRegistry::get('employee_class_subjects');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_empcls = $empclssub_table->find()->where(['class_id' => $clsid, 'subject_id' => $subid])->toArray() ;
        $empids = [];
        foreach($retrieve_empcls as $emp)
        {
            
            $empids[] = $emp['emp_id'];
        }
        
        $retrieve_emp = $emp_table->find()->where(['id IN' => $empids, 'status' => 1])->toArray() ;
        
       
        $data = '';
        $data .= '<option value="">Choose Teacher</option>';
        foreach($retrieve_emp as $empid)
        {
            $data .= '<option value="'.$empid->id.'">'.$empid->f_name.' '.$empid->l_name.'</option>';
        }
        
        
        return $this->json($data);
    }
        
        public function content()
        { 
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $compid = $this->request->session()->read('company_id');
            $tutorialcontent_table = TableRegistry::get('tutorial_content');
            $class_table = TableRegistry::get('class');
            $retrieve_class = $class_table->find()->where([ 'school_id' => $compid ])->toArray();
            $retrieve_content = $tutorialcontent_table->find()->where([ 'school_id' => $compid ])->toArray();
            $this->set("content_details", $retrieve_content); 
            $this->set("class_details", $retrieve_class); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function getcontent()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $tutcontent_table = TableRegistry::get('tutorial_content');
            $filter = $this->request->data('filter');
            $class = $this->request->data('class_s');
            $subj = $this->request->data('subj');
            if($subj == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                }
            }
            $res = '';
            foreach($retrieve_content as $content)
            {
                if(!empty($content['image'])) 
                { 
                    $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                } else { 
                    $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                }
                
                if($content->file_type == 'video')
                { 
                    $icon = '<i class="fa fa-video-camera"></i>';
                }
                elseif($content->file_type == 'audio')
                {
                    $icon = '<i class="fa fa-headphones"></i>';
                } 
                else
                { 
                    $icon = '<i class="fa fa-file-pdf-o"></i>';
                }
                
                $res .= '<div class="col-sm-2 col_img">
                    <ul id="right_icon">
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#edittutcontent" class="edittutcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="'.$this->base_url.'schoolTutorialfee/delete" class=" js-sweetalert " title="Delete" data-str="Tutorial content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'. $this->base_url.'schoolTutorialfee/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                    </ul>'.
                    $img.'
                    <div class="set_icon">'.$icon.'</div>
                </div>';
            }
            
            return $this->json($res);
        }
        
        public function getsubjectsss()
        {   
            if($this->request->is('post'))
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('classId');
                $schoolid = $this->request->session()->read('company_id');
                if($id != "all")
                {
                    $separator = "subjects";
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $schoolid])->first();
                    $html_content='';
                    if($get_subjects->subject_id !=''){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content .= '<option value="">Choose Subjects</option>';
                         $html_content .= '<option value="all">All</option>';
                        
                        foreach($subjects as $subject){
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $schoolid])->first(); 
                            $html_content .= '<option value="'.$subjects_data->id.'" id="sub_'.$subjects_data->id.'">'.$subjects_data->subject_name.'</option>';
                        }
                    }
                }
                else
                {
                    $separator = "contents";
                    $tutcontent_table = TableRegistry::get('tutorial_content');
                    $filter = $this->request->data('filter');
                    
                    if($filter == "newest")
                    {
                        $retrieve_content = $tutcontent_table->find()->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                    }
                    
                    $html_content = '';
                    foreach($retrieve_content as $content)
                    {
                        if(!empty($content['image'])) 
                        { 
                            $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                        } else { 
                            $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                        }
                        
                        if($content->file_type == 'video')
                        { 
                            $icon = '<i class="fa fa-video-camera"></i>';
                        }
                        elseif($content->file_type == 'audio')
                        {
                            $icon = '<i class="fa fa-headphones"></i>';
                        } 
                        else
                        { 
                            $icon = '<i class="fa fa-file-pdf-o"></i>';
                        }
                        
                        $html_content .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#edittutcontent" class="edittutcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="'.$this->base_url.'schoolTutorialfee/delete" class=" js-sweetalert " title="Delete" data-str="Tutorial content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                <li> <a href="'. $this->base_url.'schoolTutorialfee/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                        </div>';
                    }
                }
                
                $data['abc'] = $html_content;
                $data['sep'] = $separator;
                
                return $this->json($data);

            }  
        }
        public function getfilter()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $tutcontent_table = TableRegistry::get('tutorial_content');
            $filter = $this->request->data('filter');
            $class = $this->request->data('class_s');
            $subj = $this->request->data('subj');
            if(!empty($class) && !empty($subj))
            {
                if(($class != "all") && ($subj == "all"))
                {
                    if($filter == "newest")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'class_id' => $class, 'subject_id' => $subj])->order(['id' => 'desc'])->toArray() ;
                    }
                }
            }
            elseif(!empty($class) && empty($subj))
            {
                if($class != "all")
                {
                    if($filter == "newest")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'class_id' => $class])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_content = $tutcontent_table->find()->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                    }
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $tutcontent_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
            }
            $res = '';
            foreach($retrieve_content as $content)
            {
                if(!empty($content['image'])) 
                { 
                    $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                } else { 
                    $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                }
                
                if($content->file_type == 'video')
                { 
                    $icon = '<i class="fa fa-video-camera"></i>';
                }
                elseif($content->file_type == 'audio')
                {
                    $icon = '<i class="fa fa-headphones"></i>';
                } 
                else
                { 
                    $icon = '<i class="fa fa-file-pdf-o"></i>';
                }
                
                $res .= '<div class="col-sm-2 col_img">
                    <ul id="right_icon">
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#edittutcontent" class="edittutcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="'.$this->base_url.'schoolTutorialfee/delete" class=" js-sweetalert " title="Delete" data-str="Tutorial content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'. $this->base_url.'schoolTutorialfee/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                    </ul>'.
                    $img.'
                    <div class="set_icon">'.$icon.'</div>
                </div>';
            }
            
            return $this->json($res);
        }
        
        public function viewcontent($id)
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $tutcontent_table = TableRegistry::get('tutorial_content');
            $tutcomm_table = TableRegistry::get('tutorial_comments');
            $student_table = TableRegistry::get('student');
            $employee_table = TableRegistry::get('employee');
            $company_table = TableRegistry::get('company');
            
            
            $retrieve_tutcontent = $tutcontent_table->find()->where(['md5(id)' => $id])->toArray();
            
			$retrieve_comments = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent' => 0])->toArray();
			$retrieve_replies = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent !=' => 0])->toArray();
			
			foreach($retrieve_comments as $key =>$comm)
    		{
    		    $addedby = $comm['added_by'];
    		    $schoolid = $comm['school_id'];
    			$userid = $comm['user_id'];
    			$teacherid = $comm['teacher_id'];
    			//$i = 0;
    			$subjectsname = [];
    			if($addedby == "superadmin")
    			{
    			    $comm->user_name = "You-Me Global Education";
    			}
    			else if($addedby == 'school')
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$comm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}
        		else if($addedby == 'student')
        			{
        			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
        				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
        			}
        		else if($addedby == 'teacher')
        			{
        			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
        				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
        			}
    		}
			$retrieve_replies = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent !=' => 0])->toArray();
			
			foreach($retrieve_replies as $rkey => $replycomm)
    		{
    		    $addedby = $replycomm['added_by'];
    			$schoolid = $replycomm['school_id'];
    			$userid = $replycomm['user_id'];
    			$teacherid = $replycomm['teacher_id'];
    			//$i = 0;
    			$subjectsname = [];
    			if($addedby == "superadmin")
    			{
    			    $replycomm->user_name = "You-Me Global Education";
    			}
    			else if($addedby == 'school')
    			{
    			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
    				$replycomm->user_name = $retrieve_school[0]['comp_name'];
    				
    			}
    			else if($addedby == 'student')
    			{
    			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
    				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
    			}
    			else if($addedby == 'teacher')
    			{
    			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
    				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
    			}
    		}
            
            $this->set("content_details", $retrieve_tutcontent); 
            $this->set("comments_details", $retrieve_comments); 
            $this->set("replies_details", $retrieve_replies); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function addtutorialcontent()
        {
            
            if ($this->request->is('ajax') && $this->request->is('post') )
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tutorialcontent_table = TableRegistry::get('tutorial_content');
                $activ_table = TableRegistry::get('activity');
                $compid = $this->request->session()->read('company_id');
                
                $employee_table = TableRegistry::get('employee');
                /*$tid = $this->Cookie->read('tid');
                $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid ])->first();
                
                $teacher_id = $retrieve_employees->id;*/

                $video_type = "";  $you_count = 0;
                
                if($this->request->data('file_type') == "video")
                {
                    $link = $this->request->data('file_link');
                    $file_youtube = strpos($link, "youtube");
                    if($file_youtube != false)
                    {
                        $youex = explode("watch?v=",$link);
                        $you_count = count($youex);
                        if($you_count >1){
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                    }
                    
                    $file_vimeo =  strpos($link, "vimeo");
                    if($file_vimeo != false)
                    {
                        $file_link = $link;
                        $video_type = "vimeo";
                    }
                    
                    
                    
                    $filess = $file_link;
                }
                elseif($this->request->data('file_type') == "audio")
                {  
                    if(!empty($this->request->data['file_upload']['name']))
                    {   
                        if($this->request->data['file_upload']['type'] == "audio/mpeg" )
                        {
                            $filess =  $this->request->data['file_upload']['name'];
                            $filename = $this->request->data['file_upload']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename; 
                            if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['file_upload'] = $filename; 
                            }
                        }    
                    }
                    else
                    {
                        $filess = "";
                    }
                    
                }
                else
                {  
                    if(!empty($this->request->data['file_upload']['name']))
                    {   
                        if($this->request->data['file_upload']['type'] == "application/pdf" )
                        {
                            $filess =  $this->request->data['file_upload']['name'];
                            $filename = $this->request->data['file_upload']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['file_upload'] = $filename; 
                            }
                        }    
                    }
                    else
                    {
                        $filess = "";
                    }
                    
                }
                
                
                if(!empty($_POST['slim'][0] ))
                {
                    foreach($_POST['slim'] as $slim)
                    {
                        $abc = json_decode($slim);
                         
                        $cropped_image = $abc->output->image;
                        list($type, $cropped_image) = explode(';', $cropped_image);
                        list(, $cropped_image) = explode(',', $cropped_image);
                        $cropped_image = base64_decode($cropped_image);
                        $coverimg = date('ymdgis').'.png';
                        
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$coverimg; 
                        file_put_contents($uploadfile, $cropped_image);
                    }
                }
                else
                {
                    $coverimg = "";
                }
                
                  //print_r($coverimg);exit;
                if(!empty($filess))
                {
                    $knowledge = $tutorialcontent_table->newEntity();
                    $knowledge->file_type = $this->request->data('file_type');
                    $knowledge->title = $this->request->data('title');
                    $knowledge->created_date = time();
                    $knowledge->description = $this->request->data('desc');
                    $knowledge->links = $filess;
                    //$knowledge->teacher_id = $teacher_id;
                    $knowledge->class_id = $this->request->data('class_id');
                    $knowledge->subject_id = $this->request->data('subject_id');
                    $knowledge->image = $coverimg;
                    $knowledge->status = "1";
                    $knowledge->active = 0;
                    $knowledge->school_id = $compid;
                    $knowledge->video_type = $video_type;
                    
                    $class_id = $this->request->data('class_id');
                    $subject_id = $this->request->data('subject_id');
                    
                    //if($pass == 'pass'){
                        if($saved = $tutorialcontent_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Knowledge Base Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'];
                            }
                            else
                            { 
                                $res = [ 'result' => 'activity'  ];
                            }
                               
                        }
                        else
                        {
                            $res = [ 'result' => 'failed'  ];
                        }
                    /*}else{
                        $res = [ 'result' => 'Cover Image size should be within 182x230 px '  ];
                    }*/
                }
                else
                {
                    $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format'  ];
                }
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];

            }
            return $this->json($res);
        }
        
        public function edittutcontent()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tutcontent_table = TableRegistry::get('tutorial_content');
                $activ_table = TableRegistry::get('activity');
                $compid = $this->request->session()->read('company_id');
                $id = $this->request->data('ekid');
                $file_type = $this->request->data('efile_type');
                $file_title = $this->request->data('etitle');
                $file_description = $this->request->data('edesc');
                
                
    
                $classId = $this->request->data('class_id');
                $subjectid = $this->request->data('subject_id');
            
                
                if(!empty($_POST['slim'][0] ))
                {
                    foreach($_POST['slim'] as $slim)
                    {
                        $abc = json_decode($slim);
                         
                        $cropped_image = $abc->output->image;
                        list($type, $cropped_image) = explode(';', $cropped_image);
                        list(, $cropped_image) = explode(',', $cropped_image);
                        $cropped_image = base64_decode($cropped_image);
                        $coverimg = date('ymdgis').'.png';
                        
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$coverimg; 
                        file_put_contents($uploadfile, $cropped_image);
                    }
                }
                else
                {
                    $coverimg = $this->request->data('ecoverimage');
                }
                
                
                
                
                if($this->request->data('efile_type') == "video")
                {
                   // $filess = $this->request->data('efile_link');
                    $link = $this->request->data('efile_link');
                    $file_youtube = strpos($link, "youtube");
                    if($file_youtube != false)
                    {
                        $youex = explode("watch?v=",$link);
                        if(!empty($youex[1]))
                        {
                            $file_link  = $youex[0]."embed/".$youex[1];
                        }
                        else
                        {
                            $file_link = $link;
                        }
                        $video_type = "youtube";
                    }
                    
                    $file_vimeo =  strpos($link, "vimeo");
                    if($file_vimeo != false)
                    {
                        $file_link = $link;
                        $video_type = "vimeo";
                    }
                    
                    
                    
                    $filess = $file_link;
                    
                }
                elseif($this->request->data('efile_type') == "audio")
                {  
                    if(!empty($this->request->data['efile_upload']['name']))
                    {   
                        if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                        {
                            $filess =  $this->request->data['efile_upload']['name'];
                            $filename = $this->request->data['efile_upload']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename; 
                            if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['efile_upload'] = $filename; 
                            }
                        }    
                    }
                    else
                    {
                        $filess = $this->request->data['efileupload'];
                    }
                    
                }
                else
                {  
                    if(!empty($this->request->data['efile_upload']['name']))
                    {   
                        if($this->request->data['efile_upload']['type'] == "application/pdf" )
                        {
                            $filess =  $this->request->data['efile_upload']['name'];
                            $filename = $this->request->data['efile_upload']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['efile_upload'] = $filename; 
                            }
                        }    
                    }
                    else
                    {
                        $filess = $this->request->data['efileupload'];
                    }
                    
                }
                
                if(!empty($filess))
                {
    				$status = 0;
                                          
                    if($tutcontent_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'class_id' => $classId, 'subject_id' => $subjectid, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                    {   
                        $knowid = $id;
    
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Tutorial Updated Successfully!"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($knowid); 
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity) ;
    
                        if($save)
                        {   
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        { 
                            $res = [ 'result' => 'activity'  ];
                        }
                           
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format'  ];
                }
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];
    
            }
            return $this->json($res);
        }
        
        public function addcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('tutorial_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    //$comments->user_id = $this->request->session()->read('student_id');
                    //$comments->teacher_id = $this->request->data('teacherid');
                    $comments->tutorial_content_id = $this->request->data('kid');
		            $comments->created_date = time();
		            $comments->added_by = "school";
                    $comments->school_id = $this->request->data('schoolid');
                    $comments->parent = 0;
                   // print_r($comments);exit;
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity) ;

                        if($save)
                        {   
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        { 
                            $res = [ 'result' => 'activity'  ];
                        }
                           
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function replycomments(){
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('tutorial_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->tutorial_content_id = $this->request->data('r_kid');
                    //$comments->user_id = $this->request->session()->read('student_id');
                    //$comments->teacher_id = $this->request->data('teacher_id');
                    $comments->school_id = $this->request->data('skul_id');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity) ;

                        if($save)
                        {   
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        { 
                            $res = [ 'result' => 'activity'  ];
                        }
                           
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'  ];
                    }
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
}

  


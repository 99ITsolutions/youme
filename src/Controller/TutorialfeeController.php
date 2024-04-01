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
use \Imagick;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TutorialfeeController   extends AppController
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
                $schoolid = $this->request->session()->read('company_id');
                $tid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                $tutorialfee_table = TableRegistry::get('tutorial_fee');
                
                $retrieve_tutorialfee = $tutorialfee_table->find()->select(['tutorial_fee.fee','tutorial_fee.id' ,'tutorial_fee.frequency'  , 'class.c_name'  ,  'class.c_section' , 'class.school_sections'  ,'subjects.subject_name', 'session.startyear', 'session.endyear' ])->join([
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
                ])->where(['md5(tutorial_fee.teacher_id)' => $tid , 'tutorial_fee.school_id' => $schoolid  ])->toArray();
                
                $employee_table = TableRegistry::get('employee');
                $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
                
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
        				$empgrades = array_unique($empgrades);
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
        		}	
        		
        		$current_year = date('Y');
        		$current_m_y = date('F Y');
        		$session_table = TableRegistry::get('session'); //->select(['id' ,'startyear','endyear'])
        		$retrieve_session = $session_table->find()->where([ 'startyear <= '=> $current_year, 'endyear >= '=> $current_year ])->order(['startyear' => 'ASC'])->toArray() ;
        	
        	    $retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section', 'class.school_sections', 'class.id'])->join(
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
                    

                ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['class.id'])->toArray();
                $this->set("session_details", $retrieve_session);
                $this->set("empcls_details", $retrieve_empclses); 
				$this->set("employees_details", $retrieve_employees); 
                $this->set("tutorial_details", $retrieve_tutorialfee); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function view($id)
            {   
                $schoolid = $this->request->session()->read('company_id');
                $tid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                $tutorialfee_table = TableRegistry::get('tutorial_fee');
                
                $retrieve_tutorialfee = $tutorialfee_table->find()->where(['md5(id)' => $id ])->first();
                //print_r($retrieve_tutorialfee);exit;
                $employee_table = TableRegistry::get('employee');
                $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
                
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
        		}	
        		
        		$current_year = date('Y');
        		$current_m_y = date('F Y');
        		$session_table = TableRegistry::get('session'); //->select(['id' ,'startyear','endyear'])
        		$retrieve_session = $session_table->find()->where([ 'startyear <= '=> $current_year, 'endyear >= '=> $current_year ])->order(['startyear' => 'ASC'])->toArray() ;
        		/*$get_s_array = array(); $i=0;
        		foreach($retrieve_session as $sess){
        		    $st_date = $sess->startmonth.' '.$sess->startyear;
        		    $en_date = $sess->endmonth.' '.$sess->endyear;
        		    
        		    if(strtotime($current_m_y) >= strtotime($st_date)  && strtotime($current_m_y) <= strtotime($en_date)){
        		        $get_s_array[$i]['id'] = $sess->id;
        		        $get_s_array[$i]['startyear'] = $sess->startyear;
        		        $get_s_array[$i]['endyear'] = $sess->endyear;
        		    }
        		    $i++;
        		}*/
        		
                $this->set("session_details", $retrieve_session);

				$this->set("employees_details", $retrieve_employees); 
                $this->set("tutorial_details", $retrieve_tutorialfee); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function addtut(){  
                
                if ( $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $schoolid = $this->request->session()->read('company_id');
                    $tid = $this->Cookie->read('tid');
                    $tutorialfee_table = TableRegistry::get('tutorial_fee');
                    
                    $employee_table = TableRegistry::get('employee');
                    $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
                
                    $activ_table = TableRegistry::get('activity');
                    $retrieve_fee = $tutorialfee_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class'),'md5(teacher_id)' => $tid,'subject_id' => $this->request->data('subjects'), 'school_id' => $schoolid  ])->count() ;
                    
                    if($retrieve_fee == 0 )
                    {
                        $fee = $tutorialfee_table->newEntity();
                        $fee->class_id = $this->request->data('class');
                        $fee->subject_id = $this->request->data('subjects');
                        $fee->frequency = $this->request->data('frequency');
                        $fee->session_id = $this->request->data('start_year');
                        $fee->fee = $this->request->data('amount');
                        $fee->teacher_id = $retrieve_employees->id;
                        $fee->school_id = $schoolid;
                        $fee->created_date = time();
                                           
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
                    $class_table = TableRegistry::get('class');
                    $subjects_table = TableRegistry::get('subjects');
                    $retrieve_tutorial_fee_table = $tutorial_fee_table->find()->where([ 'id '=> $id])->toArray() ;
                	$this->request->session()->write('LAST_ACTIVE_TIME', time());
                    return $this->json($retrieve_tutorial_fee_table);
                }  
            }
           
            public function edittut(){  
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    
                    $schoolid = $this->request->session()->read('company_id');
                    $tid = $this->Cookie->read('tid');
                    $tutorial_fee_table = TableRegistry::get('tutorial_fee');
                    $activ_table = TableRegistry::get('activity');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_fee = $tutorial_fee_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class') ,'subject_id' => $this->request->data('subjects') , 'md5(teacher_id)' => $tid , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $schoolid  ])->count() ;

                    if($retrieve_fee == 0 )
                    {   
                        $id = $this->request->data('eid');
                        $class_id = $this->request->data('class');
                        $frequency = $this->request->data('frequency');
                        $amount = $this->request->data('amount');
                        $session = $this->request->data('start_year');
                        $frequency = $this->request->data('frequency');
                        
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
                $tutorial_fee_table = TableRegistry::get('tutorial_fee');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                $del = $tutorial_fee_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
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
            
            public function addstudentfees(){   
                if ($this->request->is('ajax') && $this->request->is('post') )
                { 
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_fee_table = TableRegistry::get('student_fee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $fee = $student_fee_table->newEntity();
                    
                    $fee->class_id = $this->request->data('class');
                    $fee->student_id = $this->request->data('student');
                    $fee->frequency = $this->request->data('frequency');
                    $fee->amount = $this->request->data('amount');
                    $fee->start_year = $this->request->data('start_year');
                    $fee->school_id = $compid;
                    $fee->submission_date = time();
                    $fee->created_date = time();
                                       
                    if($saved = $student_fee_table->save($fee) )
                    {     
                        $strucid = $saved->id;
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Fee Structure Added"  ;
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
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
            public function viewchart()
            {   
                $schoolid = $this->Cookie->read('id');
                $studentid = 3;
                $class= 1;
                $year = 1;
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_fee_table = TableRegistry::get('student_fee');
                $fee_table = TableRegistry::get('fee_structure');
                $stud_table = TableRegistry::get('student');
                $session_table = TableRegistry::get('session');
                $compid = $this->request->session()->read('company_id');
                $retrieve_session = $session_table->find()->where([ 'id '=> $year])->toArray() ;
                $retrieve_feestrcture = $fee_table->find()->where([ 'md5(school_id) '=> $schoolid, 'class_id' => $class])->order(['id' => 'DESC'])->toArray() ;
                $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid])->order(['id' => 'DESC'])->toArray() ;
                
                /*foreach($retrieve_studfee_table as $key =>$fee_s)
        		{
        		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['id' => $fee_s->class_id])->toArray();
        			 $fee_s->c_name = $retrieve_class[0]->c_name;
        			 $fee_s->c_section = $retrieve_class[0]->c_section;
        			 
        			 $retrieve_class = $stud_table->find()->select(['id' ,'f_name', 'l_name','email'])->where(['id' => $fee_s->student_id])->toArray();
        			 $fee_s->f_name = $retrieve_class[0]->f_name;
        			 $fee_s->l_name = $retrieve_class[0]->l_name;
        			 $fee_s->email = $retrieve_class[0]->email;
        		}*/
        		
                $this->set("feestructure_details", $retrieve_studfee_table); 
                $this->set("feestre_details", $retrieve_feestrcture); 
                $this->set("session_details", $retrieve_session); 
                $this->viewBuilder()->setLayout('user');
            }
            
            
            public function pdf($student = null, $ddata=null)
            {
                $compid = $this->request->session()->read('company_id');
                $student_fee_table = TableRegistry::get('student_fee');
                $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'student_id'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        <td>Session:'.$retrieve_student_table->session['startyear'].' '.$retrieve_student_table->session['startyear'].'</td>
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
                $fee_structure_table = TableRegistry::get('fee_structure');
                $feedetail_table = TableRegistry::get('feedetail');
                $retrieve_feestruc = $fee_structure_table->find()->where(['school_id' => $compid , 'class_id' =>$retrieve_student_table->class['id'], 'start_year'=> $retrieve_studfee_table[0]->start_year])->first();
                
                $retrieve_feedtl = $feedetail_table->find()->select(['feedetail.id'  , 'feedetail.amount'  ,'feehead.head_name' ])->join([
                    'feehead' => 
                        [
                        'table' => 'feehead',
                        'type' => 'LEFT',
                        'conditions' => 'feehead.id = feedetail.fee_h_id'
                    ]
                ])->where(['feedetail.fee_s_id' => $retrieve_feestruc->id  ])->order(['feehead.id' => 'ASC'])->toArray();
                    $i=0;
                    $fee_data ='<tr><td style="padding:0px !important;"><table id="fee_detail" style=" width: 100%; border:1px solid #ccc;"><tr><th style="text-align:left">Sr. No.</th> <th style="text-align:left">Description</th> <th style="text-align:left">Due</th><th style="text-align:left">Paid</th></tr>';
                foreach($retrieve_feedtl as $fee){
                    // print_r($fee);exit;
                    if($retrieve_feestruc->frequency == 'yearly'){
                        $amount = $fee->amount;
                    }
                    
                    if($retrieve_feestruc->frequency == 'half yearly'){
                        $amount = $fee->amount / 2;
                    }
                    
                    if($retrieve_feestruc->frequency == 'quarterly'){
                        $amount = $fee->amount / 4;
                    }
                    
                    if($retrieve_feestruc->frequency == 'monthly'){
                        $amount = $fee->amount / 12; 
                    }
                    $amount = round($amount);
                    $j = $i+1;
                    $fee_data .='<tr><td style="text-align:left">'.$j.'</td><td style="text-align:left">'.$fee->feehead['head_name'].'</td><td style="text-align:left">'.$amount.'</td><td style="text-align:left">'.$amount.'</td></tr>';
                    $i++;
                }
                
                if($retrieve_feestruc->frequency == 'yearly'){
                    $total_amt = $retrieve_feestruc->amount;
                }
                
                if($retrieve_feestruc->frequency == 'half yearly'){
                    $total_amt = $retrieve_feestruc->amount / 2;
                }
                
                if($retrieve_feestruc->frequency == 'quarterly'){
                    $total_amt = $retrieve_feestruc->amount / 4;
                }
                
                if($retrieve_feestruc->frequency == 'monthly'){
                    $total_amt = $retrieve_feestruc->amount / 12;
                }
                    $total_amt = round($total_amt);
                $paid_amt =0;
                foreach($retrieve_studfee_table as $std_fee){
                    $paid_amt += $std_fee->amount;
                }
                
                
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
            
            
            public function subjects(){   
				
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid');
                //$sessionid = $this->Cookie->read('session');
                $employee_table = TableRegistry::get('employee');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				$notification_table = TableRegistry::get('notification');
                $schoolid  = $retrieve_employees[0]['school_id'];
                $tchrid  = $retrieve_employees[0]['id'];
                
                $rcve_nottzCount = $notification_table->find()->where(['sent_notify' => 1, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'teachers']] ])->toArray() ;
                
                if(!empty($rcve_nottzCount))
                {
                    $countNotifctn = [];
                    foreach($rcve_nottzCount as $notzcount)
                    {
                        if($notzcount['notify_to'] == "all")
                        {
                            $countNotifctn[] = 1;
                        }
                        else
                        {
                            $tchrids = explode(",", $notzcount['teacher_ids']);
                          
                            if(in_array($tchrid, $tchrids))
                            {
                                $countNotifctn[] = 1;
                            }
                            else
                            {
                                $countNotifctn[] = 0;
                            }
                        }
                    }
                    $countNoti = array_sum($countNotifctn);
                    
                }
                else
                {
                    $countNoti = 0;
                }
                $this->set("total_count", $countNoti); 
				
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

				$this->set("employees_details", $retrieve_empclses); 
				

				$this->viewBuilder()->setLayout('user');
            }
            
            public function add($classid, $subjectid){  
                
                $tid = $this->Cookie->read('tid');
                //print_r($_COOKIE);
                $sessionid = $this->Cookie->read('sessionid');
                $compid = $this->request->session()->read('company_id');
                $tutorialcontent_table = TableRegistry::get('tutorial_content');
                $tutorialstud_table = TableRegistry::get('student_tutorial_logins');
                $retrieve_content = $tutorialcontent_table->find()->where([ 'md5(teacher_id)' => $tid, 'class_id' => $classid, 'subject_id' => $subjectid, 'school_id' => $compid ])->toArray();
                
                $retrieve_studtut = $tutorialstud_table->find()->select(['student.f_name', 'student.l_name', 'student.id'])->join([
                    'student' =>
                        [
                            'table' => 'student',
                            'type' => 'LEFT',
                            'conditions' => 'student.id = student_tutorial_logins.student_id'
                        ]
                    ])->where([ 'md5(teacher_id)' => $tid, 'student_tutorial_logins.session_id' => $sessionid, 'student_tutorial_logins.class_id' => $classid, 'student_tutorial_logins.subject_id' => $subjectid, 'student_tutorial_logins.school_id' => $compid ])->toArray();
                
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $class_table = TableRegistry::get('class');
                $subject_table = TableRegistry::get('subjects');
                
                   
                $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $classid])->first() ;
                $retsub = $subject_table->find()->where(['id' => $subjectid])->first() ;
                $classname = $retclass['c_name']."-".$retclass['c_section']. "(".$retclass['school_sections'] .")";
                $subname = $retsub['subject_name'];
                
                $this->set("studtut", $retrieve_studtut); 
                $this->set("classId", $classid); 
                $this->set("subjectid", $subjectid); 
                $this->set("classname", $classname); 
                $this->set("subname", $subname); 
                $this->set("content_details", $retrieve_content); 
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
                    $tid = $this->Cookie->read('tid');
                    $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid ])->first();
                    
                    $teacher_id = $retrieve_employees->id;

                    $video_type = "";  $you_count = 0;
                    $nopages = 0;
                    $dirname = "";
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
                        if($this->request->data('videotypes') == "d.tube")
                    	{
                    	    
                    		$file_link = $this->request->data('dtube_video');
                    		$video_type = "d.tube";
                    	}
                        
                        
                        $filess = $file_link;
                    }
                    elseif($this->request->data('file_type') == "audio")
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  time().$this->request->data['file_upload']['name'];
                                $filename = $filess;
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $filess = $filename; 
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
                                $filess =  time().$this->request->data['file_upload']['name'];
                                $filename = $filess;
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $filess = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        $nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                        
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
                    
                    if($nopages > 21)
                    {
                        $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    }
                    else {
                    if(!empty($filess))
                    {
                        $knowledge = $tutorialcontent_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
                        $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
                        $knowledge->teacher_id = $teacher_id;
                        $knowledge->student_id = implode(",", $this->request->data['studid']);
                        $knowledge->class_id = $this->request->data('class_id');
                        $knowledge->subject_id = $this->request->data('subject_id');
                        $knowledge->image = $coverimg;
                        $knowledge->status = "1";
                        $knowledge->active = 0;
                        $knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->numpages = $nopages;
                        $knowledge->dirname = $dirname;
                        
                        $class_id = $this->request->data('class_id');
                        $subject_id = $this->request->data('subject_id');
                        
                        //if($pass == 'pass'){
                            if($saved = $tutorialcontent_table->save($knowledge) )
                            {   
                                $clsid = $saved->id;
                                
                                if($this->request->data('file_type') == "pdf")
                                {
                                    mkdir($dirname);
                                    $numpages = $nopages-1;
        
                                    for ($x = 0; $x <= $numpages; $x++) {
                                        $save_to    = $dirname."/".$filename.$x.'.jpg';  
                                        $im = new Imagick();
                                        $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                                        $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                                        $im->setImageFormat('jpg');
                                        $im = $im->flattenImages();
                                        $im ->writeImages($save_to, true);
                                        header('Content-Type: image/jpeg');
                                        sleep(2);
                                    }
                                }
        
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Knowledge Base Added"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($clsid); 
                                $activity->created = strtotime('now');
                                $save = $activ_table->save($activity) ;
        
                                if($save)
                                {   
                                    $res = [ 'result' => 'success' , 'class'=> $class_id, 'subject'=> $subject_id];
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format. Size of file not more than 5MB'  ];
                    } }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            
            public function deletecontent()
            {
                $rid = $this->request->data('val') ;
                $tutorial_content_table = TableRegistry::get('tutorial_content');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
                    
                $del = $tutorial_content_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
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
            
            public function editcontent(){   
                if($this->request->is('post')){
                    $id = $this->request->data['id'];
                    $tutorial_content_table = TableRegistry::get('tutorial_content');
                    $retrieve_tutorial_content_table = $tutorial_content_table->find()->where([ 'id '=> $id])->first() ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $clsid = $retrieve_tutorial_content_table['class_id'];
                    $compid = $this->request->session()->read('company_id');
                    $clssub_table = TableRegistry::get('class_subjects');
                    $sub_table = TableRegistry::get('subjects');
                    $retrieve_clssub = $clssub_table->find()->where(['class_id' => $clsid, 'school_id' => $compid, 'status' => 1])->first() ;
                    $subject_ids = $retrieve_clssub['subject_id'];
                    $ids = explode(",", $subject_ids);
                    
                   
                    $data = '';
                    foreach($ids as $sids)
                    {
                        $retrieve_sub = $sub_table->find()->where(['id' => $sids])->first() ;
                        //$subids[] = $retrieve_sub['subject_name'];
                        $data .= '<option value="'.$sids.'">'.$retrieve_sub['subject_name'].'</option>';
                    }
                    
                    $content['getdata'] = $retrieve_tutorial_content_table;
                    $content['subjects'] = $data;
                    
                    return $this->json($content);
                }  
            }
            
            public function viewcontent($id)
            {  
                $tutcontent_table = TableRegistry::get('tutorial_content');
                $tutcomm_table = TableRegistry::get('tutorial_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
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
            
            public function edittutcontent()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $tutcontent_table = TableRegistry::get('tutorial_content');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $classId = $this->request->data('class_id');
                    $subjectid = $this->request->data('subject_id');
                
                    $studid = implode(",", $this->request->data('estudid'));
                    
                    
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
                    
                    
                    
                    $nopages = 0;
                    $dirname = '';
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
                        
                        if($this->request->data('evideotypes') == "d.tube")
                	    {
                    		$file_link = $this->request->data('edtube_video');
                    		$video_type = "d.tube";
                    		$filess = $file_link;
                    	}    
                        
                        $filess = $file_link;
                        
                    }
                    elseif($this->request->data('efile_type') == "audio")
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  time().$this->request->data['efile_upload']['name'];
                                $filename = $filess;
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $filename = $filename; 
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
                                $filess = time().$this->request->data['efile_upload']['name'];
                                $filename = $filess;
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                   $filename = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        $nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                        
                    }
                    
                    if($nopages > 21)
                    {
                        $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    }
                    else {
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($tutcontent_table->query()->update()->set(['numpages' => $nopages, 'student_id' => $studid, 'dirname' => $dirname, 'file_type' => $file_type ,'video_type' => $video_type, 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
                            
                            if($this->request->data('efile_type') == "pdf")
                            {
                                mkdir($dirname);
                                $numpages = $nopages-1;
    
                                for ($x = 0; $x <= $numpages; $x++) {
                                    $save_to    = $dirname."/".$filename.$x.'.jpg';  
                                    $im = new Imagick();
                                    $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                                    $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                                    $im->setImageFormat('jpg');
                                    $im = $im->flattenImages();
                                    $im ->writeImages($save_to, true);
                                    header('Content-Type: image/jpeg');
                                    sleep(2);
                                }
                            }
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Tutorial Updated Successfully!"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'  , 'class'=>$classId , 'subject'=>$subjectid ];
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format. Size of file not more than 5MB'  ];
                    } }
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
                    $comments->teacher_id = $this->request->data('teacherid');
                    $comments->tutorial_content_id = $this->request->data('kid');
		            $comments->created_date = time();
		            $comments->added_by = "teacher";
                    //$comments->school_id = $this->request->data('schoolid');
                    $comments->parent = 0;
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
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $comments_table = TableRegistry::get('tutorial_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->tutorial_content_id = $this->request->data('r_kid');
                    $comments->teacher_id = $this->request->data('teacher_id');
                    //$comments->school_id = $this->request->data('skul_id');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "teacher";
                                          
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
            
            public function viewtutcontent()
            {
                $tutcontent_table = TableRegistry::get('tutorial_content');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter = $this->request->data('filter');
                $compid = $this->request->session()->read('company_id');
                $class = $this->request->data('class_s');
                $subj = $this->request->data('subj');
                $compid = $this->request->session()->read('company_id');
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
                        $retrieve_content = $tutcontent_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                $res = '';
                foreach($retrieve_content as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="'.$this->base_url.'tutorialfee/deletecontent" class=" js-sweetalert " title="Delete" data-str="Tutorial content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'tutorialfee/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                    </div>';
                }
                
                return $this->json($res);
            }
            public function students()
            {   
                $compid = $this->request->session()->read('company_id');
                $tid = $this->Cookie->read('tid');
				$class_table = TableRegistry::get('class');
				$tutfee_table = TableRegistry::get('student_tutorial_fee');
				$student_table = TableRegistry::get('student');
				$subjects_table = TableRegistry::get('subjects');
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				$employee_table = TableRegistry::get('employee');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
				
    			$gradeid = explode(",",$retrieve_employees['grades']);
    			$subid = explode(",",$retrieve_employees['subjects']);
    			$all_class = []; $i=0;
    			foreach($gradeid as $gid)
    			{
    			    $retrieve_class = $class_table->find()->where([ 'id '=> $gid ])->first();
    				$all_class[$i]['c_name'] = $retrieve_class['c_name'];	
    				$all_class[$i]['c_section'] = $retrieve_class['c_section'];	
    				$all_class[$i]['school_sections'] = $retrieve_class['school_sections'];	
    				$all_class[$i]['id'] = $gid;
    				$i++;
    			}
    			
    			$all_sub = []; $j=0;
    			foreach($subid as $sid)
    			{
    			    $retrieve_subject = $subjects_table->find()->where([ 'id '=> $sid ])->first();
    				$all_sub[$j]['subject_name'] = $retrieve_subject['subject_name'];	
    				$all_sub[$j]['id'] = $sid;	
    				$j++;
    			}
				if(!empty($_POST)){  $subjectid = $this->request->data['subject_list']; $classid = $this->request->data['class_list']; }else{ $subjectid = ""; $classid = "";  }
				$wherecon['student_tutorial_fee.school_id'] = $compid; $wherecon['md5(student_tutorial_fee.teacher_id)'] = $tid;
				if($classid != ''){ $wherecon['student_tutorial_fee.class_id'] = $classid;  } if($subjectid != ''){ $wherecon['student_tutorial_fee.subject_id'] = $subjectid; }
                $retrieve_fee = $tutfee_table->find()->select(['student_tutorial_fee.id', 'student_tutorial_fee.subject_id', 'student_tutorial_fee.student_id','student_tutorial_fee.teacher_id', 'student_tutorial_fee.class_id', 'student_tutorial_fee.frequency', 'subjects.subject_name',  'class.c_name' , 'class.c_section', 'student_tutorial_fee.fee', 'student.f_name', 'student.l_name', 'student_tutorial_fee.submission_date'])
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
				
				$this->set("class_details", $all_class); 
				$this->set("subject_details", $all_sub); 
				$this->set("classid", $classid); 
				$this->set("subjectid", $subjectid); 
				$this->set("fee_details", $retrieve_fee); 
				$this->viewBuilder()->setLayout('user');
            }
}

  


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
class FeesController  extends AppController
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
                $fee_structure_table = TableRegistry::get('fee_structure');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $session_table = TableRegistry::get('session'); 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))
                {
                    $retrieve_feestructure_table = $fee_structure_table->find()->where([ 'fee_structure.school_id '=> $compid])->order(['id' => 'DESC'])->toArray() ;
                    
                    foreach($retrieve_feestructure_table as $key =>$fee_s)
            		{
            		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_s->class_id])->toArray();
            			 $fee_s->c_name = $retrieve_class[0]->c_name;
            			 $fee_s->c_section = $retrieve_class[0]->c_section;
            			 $fee_s->school_section = $retrieve_class[0]->school_sections;
            			 
            			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_s->start_year])->toArray() ;
            			 $fee_s->start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
            		}
            		
            		$class_table = TableRegistry::get('class');
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                    
                    $this->set("class_details", $retrieve_class);
                    
                    
                    $retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear'])->order(['startyear' => 'ASC'])->toArray() ;
                    $this->set("session_details", $retrieve_session);
                    
                    $this->set("feestructure_details", $retrieve_feestructure_table); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            
            public function vieweditfee()
            {   
                $student_fee_table = TableRegistry::get('student_fee');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $stud_table = TableRegistry::get('student');
                if(!empty($compid))
                {
                    $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid])->order(['id' => 'DESC'])->toArray() ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    foreach($retrieve_studfee_table as $key =>$fee_s)
            		{
            		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_s->class_id])->toArray();
            			 $fee_s->c_name = $retrieve_class[0]->c_name;
            			 $fee_s->c_section = $retrieve_class[0]->c_section;
            			 $fee_s->school_section = $retrieve_class[0]->school_sections;
            			 
            			 $retrieve_class = $stud_table->find()->select(['id' ,'f_name', 'l_name','email'])->where(['id' => $fee_s->student_id])->toArray();
            			 $fee_s->f_name = $retrieve_class[0]->f_name;
            			 $fee_s->l_name = $retrieve_class[0]->l_name;
            			 $fee_s->email = $retrieve_class[0]->email;
            		}
            		
                    $this->set("feestructure_details", $retrieve_studfee_table); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function add()
            {   
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                    
                    $session_table = TableRegistry::get('session');
                    $retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear'])->order(['startyear' => 'ASC'])->toArray() ;
                    
                    $this->set("class_details", $retrieve_class);
                    $this->set("session_details", $retrieve_session);
                    
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            public function addstructure(){   
                if ($this->request->is('ajax') && $this->request->is('post') )
                { 
                    $fee_structure_table = TableRegistry::get('fee_structure');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($compid))
                    {
                        $retrieve_fee = $fee_structure_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class'), 'start_year' => $this->request->data('start_year'), 'school_id' => $compid  ])->count() ;
                        
                        if($retrieve_fee == 0 )
                        {
                            $fee = $fee_structure_table->newEntity();
                            $fee->class_id = $this->request->data('class');
                            $fee->frequency = $this->request->data('frequency');
                            $fee->amount = $this->request->data('amount');
                            $fee->start_year = $this->request->data('start_year');
    						$fee->status = 1;
                            $fee->school_id = $compid;
                            $fee->created_date = time();
                            $lang = $this->Cookie->read('language');
                            if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                                if($langlbl['id'] == '1649') { $amtneg = $langlbl['title'] ; } 
                            } 
                            
                            $amount = $this->request->data('amount');
                            if($amount > 0)
                            {
                                if($saved = $fee_structure_table->save($fee) )
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
                                        $res = [ 'result' => 'success'  , 'feesid' => $strucid ];
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
                                $res = ['result' => $amtneg];
                            }
                        } 
                        else
                        {
                            $res = [ 'result' => 'exist'  ];
                        } 
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
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
                    $fee_structure_table = TableRegistry::get('fee_structure');
                    $class_table = TableRegistry::get('class');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_feestructure_table = $fee_structure_table->find()->where([ 'fee_structure.id '=> $id])->toArray() ;
                    foreach($retrieve_feestructure_table as $key =>$fee_s)
            		{
            		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_s->class_id])->toArray();
            			 $fee_s->c_name = $retrieve_class[0]->c_name;
            			 $fee_s->c_section = $retrieve_class[0]->c_section;
            			 $fee_s->school_section = $retrieve_class[0]->school_sections;
            		}
                    return $this->json($retrieve_feestructure_table);
                }  
            }
            
            public function editfreq(){  
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $class_id = $this->request->data['classid'];
                    $student_id = $this->request->data['student'];
                    $frequency = $this->request->data['freq'];
                    $start_year = $this->request->data['sty'];
                    $compid = $this->request->session()->read('company_id');
                   
                    $student_fee_table = TableRegistry::get('student_fee');
                    if(!empty($compid))
                    {
                        $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid, 'start_year '=> $start_year, 'class_id '=> $class_id,'student_id'=>$student_id, 'frequency' => $frequency])->order(['id' => 'ASC'])->toArray();
                        $result ='';
                        foreach($retrieve_studfee_table as $fee){
                            $result .= '
                                    <div class="col-md-12">
                                    <input type="hidden" id="eid" value="'.$fee->id.'"  name="new_d['.$fee->id.'][id]" >
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">'.date('d M Y',$fee->submission_date).'</span>
                                      </div>
                                      <input type="number" class="form-control" id="esubject_name" required name="new_d['.$fee->id.'][amount]" value="'.$fee->amount.'" placeholder="Amount*" aria-describedby="basic-addon3">
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">   
                                            <label>Memo *</label>
                                            <textarea class="form-control" name="cashmemo" required="required" id="cashmemo">'.$fee->cashmemo.'</textarea>
                                        </div>
                                    </div>
                                
                            </div>';
                        }
                        return $this->json($result);
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
                    }
                    // print_r($this->request->data['classid']);exit;
                }
                
            }
            
            public function editstruc(){  
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $fee_structure_table = TableRegistry::get('fee_structure');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    if(!empty($compid))
                    {
                        $retrieve_struc = $fee_structure_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class') , 'start_year' => $this->request->data('start_year') , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $compid  ])->count() ;
                        $lang = $this->Cookie->read('language');
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1649') { $amtneg = $langlbl['title'] ; } 
                        } 
                        if($retrieve_struc == 0 )
                        {   
                            $id = $this->request->data('eid');
                            $class_id = $this->request->data('class');
                            $frequency = $this->request->data('frequency');
                            $amount = $this->request->data('amount');
                            $start_year = $this->request->data('start_year');
                            if($amount > 0) {
                                if( $fee_structure_table->query()->update()->set(['class_id' => $class_id , 'frequency' => $frequency , 'amount' => $amount, 'start_year' => $start_year ])->where([ 'id' => $id  ])->execute())
                                {   
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Fee structure update"  ;
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
                                $res = [ 'result' => $amtneg ];
                            }
                        } 
                        else
                        {
                            $res = [ 'result' => 'exist'  ];
                        }           
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }
            
            public function editfee(){  
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $student_fee_table = TableRegistry::get('student_fee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $id = $this->request->data('eid');
                    $class_id = $this->request->data('class');
                    $frequency = $this->request->data('frequency');
                    $amount = $this->request->data('amount');
                    $start_year = $this->request->data('start_year');
                    $student = $this->request->data('student');
                    
                    
                    if( $student_fee_table->query()->update()->set(['class_id' => $class_id , 'frequency' => $frequency , 'student' => $student , 'amount' => $amount, 'start_year' => $start_year ])->where([ 'id' => $id  ])->execute())
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fee structure update"  ;
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
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }
            
        public function editfreqfee(){  
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $student_fee_table = TableRegistry::get('student_fee');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $n_data = $this->request->data('new_d');
                $cashmemo = $this->request->data('cashmemo');
                $totfreqamt = $this->request->data('totfreqfee');
                $amt = [];
                $amtpaid = [];
                foreach($n_data as $n_d){
                    $amtpaid[] = $n_d['amount'];
                    if($n_d['amount'] >= 0)
                    { 
                        $amt[] = 1; 
                    }
                    else
                    {
                        $amt[] = 0;
                    }
                }
                
                $paideditamt = array_sum($amtpaid);
                $amt_neg = 0;
                if(in_array("0", $amt))
                {
                    $amt_neg = 1;
                }
                
                $leftamt = $totfreqamt-$paideditamt;
                $lang = $this->Cookie->read('language');
                if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1649') { $amtneg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1647') { $amtexce = $langlbl['title'] ; } 
                    } 
                
                if($leftamt >= 0) 
                {
                    if($amt_neg != '1')
                    {
                        foreach($n_data as $n_d){
                            $id = $n_d['id'];
                            $amount = $n_d['amount'];
                            
                            if( $student_fee_table->query()->update()->set(['amount' => $amount, 'cashmemo' => $cashmemo])->where([ 'id' => $id  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Fee structure update"  ;
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
                         $res = [ 'result' => $amtneg ];
                    }
                }
                else
                {
                    $res = [ 'result' => $amtexce  ];
                }
            }
            else
            {
                $res = [ 'result' => 'invalid operation'  ];
            }

            return $this->json($res);
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
                    if(!empty($compid))
                    {
                        $retrieve_student = $student_table->find(all,  ['order' => ['l_name'=>'asc']])->where(['class' => $id, 'school_id' => $compid, 'session_id' => $start_year])->toArray(); 
                        $all_data = array(); $all_data[0]= $retrieve_student;  $freq_data = ''; $amount ='';
                       
                        if($start_year){
                            $fee_structure_table = TableRegistry::get('fee_structure');
                            $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'start_year' => $start_year])->toArray();
                            
                            
                            $session_table = TableRegistry::get('session');
                            $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                            //print_r($retrieve_session_table[0]->endmonth);exit;
                            
                            if(!empty($retrieve_feestructure_table)){
                                $frequency = $retrieve_feestructure_table[0]->frequency;
                                $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                                
                                if($frequency == 'yearly'){
                                    $amount = $retrieve_feestructure_table[0]->amount;
                                    $freq_data .= '<option value='.$srtMonth.'-'.$endMonth.'>'.$srtMonth.'-'.$endMonth.'</option>';
                                }
                                
                                if($frequency == 'half yearly'){
                                    $amount = $retrieve_feestructure_table[0]->amount / 2;
                                    $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                                    $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                                    $freq_data .= '<option value='.$srtMonth.'-'.$effectiveDate.'>'.$srtMonth.'-'.$effectiveDate.'</option>';
                                    $freq_data .= '<option value='.$effectiveDate2.'-'.$endMonth.'>'.$effectiveDate2.'-'.$endMonth.'</option>';
                                }
                                
                                if($frequency == 'quarterly'){
                                    $amount = $retrieve_feestructure_table[0]->amount / 4;
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
                                    $amount = $retrieve_feestructure_table[0]->amount / 12; 
                                    $freq_data .= '<option value='.$srtMonth.'>'.$srtMonth.'</option>';
                                    for($i=1; $i <= 11; $i++){
                                        $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                                        $srtMonth = $month;
                                        $freq_data .= '<option value='.$month.'>'.$month.'</option>';
                                    }
                                    //$freq_data .= '<option value='.$endMonth.'>'.$endMonth.'</option>';
                                }
                                
                                $amount = round($amount);
                                
                            }
                            
                        }
                        $all_data[1]= $freq_data;
                        $all_data[2]= $amount;
                        
                        return $this->json($all_data);
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
                    }
                }  
        }
        
        public function getstudentsData(){   
            if($this->request->is('post')){
                    
                $id = $this->request->data['classId'];
                $start_year = $this->request->data['start_year'];
                $student = $this->request->data['student'];
                 $this->request->session()->write('LAST_ACTIVE_TIME', time());   
                    
                $student_fee_table = TableRegistry::get('student_fee');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $stud_table = TableRegistry::get('student');
                
                $fee_structure_table = TableRegistry::get('fee_structure');
                if(!empty($compid))
                {
                    $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'start_year' => $start_year])->toArray();
                    
                    
                    $session_table = TableRegistry::get('session');
                    $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                    
                    if(!empty($retrieve_feestructure_table)){
                        $frequency = $retrieve_feestructure_table[0]->frequency;
                        $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                        $yr = $retrieve_session_table[0]->startyear."-".$retrieve_session_table[0]->endyear;
                        if($frequency == 'yearly'){
                            $freq_amount = $retrieve_feestructure_table[0]->amount;
                            $freq_data[0] = $srtMonth.'-'.$endMonth;
                        }
                         //print_r($freq_data);exit;
                        if($frequency == 'half yearly'){
                            $freq_amount = $retrieve_feestructure_table[0]->amount / 2;
                            $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                            $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                            $freq_data[0]= $srtMonth.'-'.$effectiveDate;
                            $freq_data[1]= $effectiveDate2.'-'.$endMonth;
                        }
                        
                        if($frequency == 'quarterly'){
                            $freq_amount = $retrieve_feestructure_table[0]->amount / 4;
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
                            $freq_amount = $retrieve_feestructure_table[0]->amount / 12;
                            $freq_data[0]= $srtMonth;
                            for($i=1; $i <= 11; $i++){
                                $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                                $srtMonth = $month;
                                $freq_data[$i]= $month;
                            }
                            //$freq_data[11]= $endMonth;
                        }
                        
                        $freq_amount = round($freq_amount);
                        
                         $data = '';
                         //$grph = '';
                        foreach($freq_data as $ddata){
                            $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'student_id'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                           $amount =0;
                            foreach($retrieve_studfee_table as $key =>$fee_s){
                                $amount += $fee_s->amount;
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
                            if($amount > 0){ $action = '<button type="button" data-freq='.$ddata.' data-totamt = '.$freq_amount.' data-sty='.$start_year.' data-classid='.$id.' data-student='.$student.' class="btn btn-sm btn-outline-secondary editfreqfee" data-toggle="modal"  data-target="#editfreqfee" title="Edit"><i class="fa fa-edit"></i></button>'; } 
                            else{ $action = ''; }
                            if($due_amt == 0){ $download = '<a href="'.$this->base_url.'fees/pdf/'.$student.'/'.$ddata.'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>'; } else{ $download = ''; }
                            
                            $sclsub_table = TableRegistry::get('school_subadmin');
            				if($this->Cookie->read('logtype') == md5('School Subadmin'))
            				{
            				    
            					$sclsub_id = $this->Cookie->read('subid');
            					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
            					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
            					//print_r($scl_privilage); 
            				}
            				
            				if($this->Cookie->read('logtype') == md5('School Subadmin'))
    						{
    							$action = in_array(39, $scl_privilage) ? $action : "";
    							$download = in_array(40, $scl_privilage) ? $download : "";
    						}
    						else
    						{
    							$action = $action;
    							$download = $download;
    						}
                            
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
                            
                            
                            //$grph[] = ['label' => $ddata, 'symbol' => $ddata, 'y' => $due_amt];
                            
                          
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
                        if($langlbl['id'] == '1812') { $paidamttt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1813') { $dueamttt = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1358') { $paidd = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1359') { $dueee = $langlbl['title'] ; } 
                    } 
                    
                    
                        $paidamt = array_sum($amt);
                        $dueamt = array_sum($due);
                        $grph[] = ['label' => $paidamttt, 'symbol' => $paidd, 'y' => $paidamt ];
                        $grph[] = ['label' => $dueamttt , 'symbol' => $dueee, 'y' => $dueamt ];
                       // $graph[] = $grph;
                        $datass = ['html' => $data, 'graph' => $grph, 'sessionyear' => $yr];
            		}
                    return $this->json($datass);
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }  
        }

        public function status(){   
            //print_r($this->request->data('sts'));exit;
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $fee_structure_table = TableRegistry::get('fee_structure');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $fee_structure_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $fee_structure_table->query()->update()->set([ 'active' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Fee Structure status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($uid)    ;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }


            public function delete()
            {
                $rid = $this->request->data('val') ;
                $fee_structure_table = TableRegistry::get('fee_structure');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
                    
                $del = $fee_structure_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
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
                    $lang = $this->Cookie->read('language');
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1649') { $amtneg = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1647') { $amtexce = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1648') { $onlineamt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1650') { $allotamt = $langlbl['title'] ; } 
                    } 
                    
                    if(!empty($compid))
                    {
                        $totalamt = $this->request->data('totalamt');
                        $amt = $this->request->data('amount');
                        $fee = $student_fee_table->newEntity();
                        $fee->cashmemo = $this->request->data('cashmemo');
                        $fee->class_id = $this->request->data('class');
                        $fee->student_id = $this->request->data('student');
                        $fee->frequency = $this->request->data('frequency');
                        $fee->amount = $this->request->data('amount');
                        $fee->start_year = $this->request->data('start_year');
                        $fee->payment_mode = $this->request->data('paymode');
                        $fee->school_id = $compid;
                        $fee->submission_date = time();
                        $fee->created_date = time();                 
                        $amount = $this->request->data('amount');
                        $submittedamt = 0;
                        $stufee = $student_fee_table->find()->select(['amount'])->where(['class_id' => $this->request->data('class'), 'start_year' => $this->request->data('start_year'), 'frequency' => $this->request->data('frequency'), 'student_id'=> $this->request->data('student') ])->toArray();
                        if(!empty($stufee))
                        {
                            foreach($stufee as $stufe)
                            {
                                $subamt[] = $stufe['amount'];
                            }
                            $submittedamt = array_sum($subamt);
                        }
                        $amttopay = $totalamt - $amount - $submittedamt;
                        if($amount > 0)
                        {
                            if($amttopay >= 0)
                            {
                                
                                if($this->request->data('paymode') == "cash")
                                {
                                    if($saved = $student_fee_table->save($fee) )
                                    {     
                                        $strucid = $saved->id;
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Fee student Added"  ;
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
                                    //return $this->redirect('/login/') ;    
                                    $res = [ 'result' =>  $onlineamt ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $allotamt ." $".$submittedamt  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => $amtneg  ];
                        }
                    }
                    else
                    {
                        return $this->redirect('/login/') ;   
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
                if(!empty($schoolid))
                {
                    $student_fee_table = TableRegistry::get('student_fee');
                    $fee_table = TableRegistry::get('fee_structure');
                    $stud_table = TableRegistry::get('student');
                    $session_table = TableRegistry::get('session');
                    $compid = $this->request->session()->read('company_id');
                    $retrieve_session = $session_table->find()->where([ 'id '=> $year])->toArray() ;
                    $retrieve_feestrcture = $fee_table->find()->where([ 'md5(school_id) '=> $schoolid, 'class_id' => $class])->order(['id' => 'DESC'])->toArray() ;
                    $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid])->order(['id' => 'DESC'])->toArray() ;
                    
            		
                    $this->set("feestructure_details", $retrieve_studfee_table); 
                    $this->set("feestre_details", $retrieve_feestrcture); 
                    $this->set("session_details", $retrieve_session); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            
          public function pdf($student = null, $ddata=null)
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
                    if($langlbl['id'] == '360') { $duelbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '359') { $paidlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1362') { $paidamtlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '81') { $namelbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '130') { $studnolbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2032') { $feercptlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '643') { $contctnolbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2033') { $recnolbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1957') { $instlmntlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '238') { $sesslbl = $langlbl['title'] ; } 
                    
                    if($langlbl['id'] == '2034') { $amontwrdlbl = $langlbl['title'] ; } 
                    if($langlbl['id'] == '2035') { $totlamtlbl = $langlbl['title'] ; } 
                    
                    
                } 
                
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_fee_table = TableRegistry::get('student_fee');
                if(!empty($compid))
                {
                    $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'student_id'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                    
                    $student_table = TableRegistry::get('student');
                    $class_table = TableRegistry::get('class');
                    $retrieve_student_table = $student_table->find()->select(['student.adm_no'  , 'student.f_name'  , 'student.l_name'  , 'student.id'  , 'student.class'  , 'class.c_name'  , 'class.id'  , 'class.c_section', 'class.school_sections'  ,'student.session_id','session.startyear','session.endyear','session.startmonth','session.endmonth' ])->join([
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
                    <tr><th style="width: 100%; border:1px solid #ccc; text-align:center">'.$feercptlbl.'</th></tr>
                    <tr>
                    <td style="padding:0px !important;">
                    <table style="width: 100%; border:1px solid #ccc;">
                        <tr>
                            <td style="width: 60%;">Date: '.$new_date.'</td>
                            <td>'.$recnolbl.': '.$retrieve_studfee_table[0]->id.'</td>
                        </tr>
                        <tr>
                            <td style="width: 60%;">'.$studnolbl.'.: '.$retrieve_student_table->adm_no.'</td>
                            <td>'.$sesslbl.':'.$retrieve_student_table->session['startyear'].'-'.$retrieve_student_table->session['endyear'].'</td>
                        </tr>
                        <tr>
                            <td style="width: 60%;">'.$namelbl.': '.$retrieve_student_table->l_name.' '.$retrieve_student_table->f_name.'</td>
                            <td>'.$instlmntlbl.': '.$ddata.'</td>
                        </tr>
                        <tr>
                            <td style="width: 60%;">'.$clslbl.': '.$retrieve_student_table->class['c_name'].' - '.$retrieve_student_table->class['c_section'].' ('.$retrieve_student_table->class['school_sections'] .') </td>
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
                        $fee_data ='<tr><td style="padding:0px !important;"><table id="fee_detail" style=" width: 100%; border:1px solid #ccc;"><tr><th style="text-align:left">Sr. No.</th> <th style="text-align:left">Description</th> <th style="text-align:left">'.$duelbl.'</th><th style="text-align:left">'.$paidlbl.'</th></tr>';
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
                                    <td style="width: 27%;">'.$totlamtlbl.':</td>
                                    <td style="width: 53%;"></td>
                                    <td style="width: 20%;">$'.$total_amt.'</td>
                                </tr>
                                <tr>
                                    <td style="width: 27%;">'.$paidamtlbl.':</td>
                                    <td style="width: 53%;"></td>
                                    <td style="width: 20%;">$'.$paid_amt.'</td>
                                </tr>
                                <tr>
                                    <td style="width: 27%;">'.$amontwrdlbl.':</td>
                                    <td style="width: 60%;"> '.$amt_words.' Only (In US Dollar).</td>
                                    <td style="width: 13%;"></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>';
                   $school_table = TableRegistry::get('company');
                    $retrieve_school = $school_table->find()->where([ 'id' => $compid])->toArray();
                    $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:150px !important; background-color:#ffffff !important;">';
                      //  $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school[0]['comp_logo'].'" style="width:150px !important; background-color:#ffffff !important;">';


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
                                                                <td  style="width: 20%; text-align:left; padding: 5px 0px; background:#ffffff !important;"><span> '.$school_logo.' </span></td>
                                                                <td  style="width: 70%; text-align:left; padding: 5px;">
                                                                <p style=" font-size: 22px; font-weight:bold; ">'.ucfirst($retrieve_school[0]['comp_name']).'</p>
                                                                <p> '.ucfirst($retrieve_school[0]['add_1']).' ,  '.ucfirst($retrieve_school[0]['city']).' </p>
                                                                <p> <b> '.$contctnolbl.': </b>'.ucfirst($retrieve_school[0]['ph_no']).' </p>
                                                                <p> <b> E-mail: </b>'.ucfirst($retrieve_school[0]['email']).' </p>
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
                   // print_r($viewpdf); die;
                    $dompdf = new Dompdf();
                    $dompdf->loadHtml($viewpdf);    
                    
                    $dompdf->setPaper('A4', 'Portrait');
                    $dompdf->render();
                    $dompdf->stream($title.".pdf");
    
                    exit(0);
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

}

  


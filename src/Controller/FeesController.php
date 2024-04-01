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
            
    public function selclass()
    {   
        $compid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $sclsctn = $this->request->data('sclsctn');
        //print_r($_POST);
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            if(($sclsctn == "Maternelle"))
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                $data = '<option value="">Choose Class</option>';
                foreach($retrieve_class as $cls)
                {
                    if($cls->school_sections == "Maternelle" || $cls->school_sections == "Creche") {
                        $data .= '<option value="'.$cls->id.'" selected>'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                    }
                }
                
            }
            if($sclsctn == "Primaire")
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections' => "Primaire", 'school_id' => $compid, 'active' => 1])->toArray() ;
                $data = '<option value="">Choose Class</option>';
                foreach($retrieve_class as $cls)
                {
                    $data .= '<option value="'.$cls->id.'" selected>'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                }
                
            }
            if($sclsctn == "secondaire")
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections !=' => "Maternelle", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Primaire", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Creche", 'school_id' => $compid, 'active' => 1])->toArray() ;
                $data = '<option value="">Choose Class</option>';
                foreach($retrieve_class as $cls)
                {
                    $data .= '<option value="'.$cls->id.'" selected>'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                }
            }
            return $this->json($data);
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
            
    public function addstructure()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $fee_structure_table = TableRegistry::get('fee_structure');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($compid))
            {
                $classes = $this->request->data['class'];
                $structure_ids = [];
                foreach($classes as $clss)
                {
                $retrieve_fee = $fee_structure_table->find()->select(['id' ])->where(['class_id' => $clss, 'start_year' => $this->request->data('start_year'), 'school_id' => $compid  ])->count() ;
                
                if($retrieve_fee == 0 )
                {
                    $fee = $fee_structure_table->newEntity();
                    $fee->class_id = $clss;
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
                        $saved = $fee_structure_table->save($fee);
                        $structure_ids[] = $saved->id;
                    }
                    else
                    {
                        $res = ['result' => $amtneg];
                    }
                } 
                else
                {
                    $res = [ 'result' => 'exist', 'class' => $clss  ];
                } 
                }
                
                if(!empty($structure_ids))
                {    
                    $countstruc = count($structure_ids);
                    $strucids = implode(",", $structure_ids);
                    if($countstruc < 6)
                    {
                        $strucid = implode(",", $structure_ids);
                    }
                    else
                    {
                        $strucid = "session";
                    }
                    $this->request->session()->write('fee_detailingids', $strucids);
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Fee Structure Added"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('id');
                    $activity->value = $strucid; 
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
                return $this->redirect('/login/') ;   
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }

        return $this->json($res);
    }
    
    public function update() 
    {   
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
            
    public function editfreq()
    {  
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
        }
    }
    
    public function editstruc()
    {  
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
            
    public function editfee()
    {  
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
            
    public function editfreqfee()
    {  
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
        
    public function getstudentsData()
    {   
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
            $discountstud_table = TableRegistry::get('discount_student');
            $feedetail_table = TableRegistry::get('feedetail');
            $feehead_table = TableRegistry::get('feehead');
            
            if(!empty($compid))
            {
                $session_table = TableRegistry::get('session');
                $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                $yr = $retrieve_session_table[0]->startyear."-".$retrieve_session_table[0]->endyear;
                
                $retrieve_feestructure = $fee_structure_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'start_year' => $start_year])->first();
                $retrieve_detailing = $feedetail_table->find('all')->select(['feehead.head_name', 'feedetail.fee_h_id', 'feedetail.fee_s_id', 'feedetail.id', 'feedetail.amount'])->join([
                    'feehead' =>
                        [
                            'table' => 'feehead',
                            'type' => 'LEFT',
                            'conditions' => 'feehead.id = feedetail.fee_h_id'
                        ]
                    
                    ])->where(['feedetail.fee_s_id' => $retrieve_feestructure['id']])->toArray();
                
                $checkcoupon = $discountstud_table->find()->where(['session_id' => $start_year, 'student_id' => $student, 'class_id' => $id])->count();
                if(!empty($checkcoupon))
                {
                    $availcoupon = 1;
                }
                else
                {
                    $availcoupon = 0;
                }
                    
                if(!empty($retrieve_detailing))
                {
                    $data = '';
                    $count = count($retrieve_detailing);
                    $totalamt = 0;
                    $totaldue = 0;
                    $totalpaid = 0;
                    $totaldiscount = 0;
                    foreach($retrieve_detailing as $key => $val) 
                    {
                        $retriev_studfee = $student_fee_table->find()->where(['class_id' => $id, 'school_id '=> $compid, 'student_id'=>$student, 'fee_h_id' => $val['fee_h_id'] ])->order(['id' => 'DESC'])->toArray();
                        $amount = 0;
                        $paydate = '';
                        $paymode = '';
                        $coupnamt = 0;
                        if(!empty($retriev_studfee))
                        {
                            $mode = [];
                            foreach($retriev_studfee as $key =>$fee_s){
                                $amount += $fee_s->amount;
                                $mode[] = $fee_s->payment_mode;
                                $coupnamt += $fee_s->coupon_amt;
                            }
                        }
                        $due = $val['amount']-$amount-$coupnamt;
                        
                        $totalamt += $val['amount'];
                        $totaldue += $due;
                        
                        if($due == 0)
                        {
                            $action ='';
                        }
                        else
                        {
                            if($count != 0 && $due != 0)
                            {
                                $action = '<a href="javascript:void(0);" data-dueamt = "'.$due.'" data-availcoupn = "'.$availcoupon.'" data-student = "'.$student.'" data-class = "'.$id.'" data-session = "'.$start_year.'" data-fee_d_id="'.$val['id'].'" data-amount="'.$val['amount'].'" data-fee_s_id="'.$val['fee_s_id'].'" data-fee_h_id="'.$val['fee_h_id'].'" title="Add" class="btn btn-info studntfee" data-toggle="modal" data-target="#addfee"><i class="fa fa-plus"></i></a>';
                                $count = 0;
                            }
                            else
                            {
                                $action = '';
                            }
                            
                        }
                        if($amount == 0)
                        {
                            $download = '';
                        }
                        else
                        {
                            $download = '<a href="'.$this->base_url.'fees/pdf/'.$student.'/'.$val['fee_h_id'].'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>';
                            //$download = '<a href="#" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>';
                        }
                        
                        $sclsub_table = TableRegistry::get('school_subadmin');
        				if($this->Cookie->read('logtype') == md5('School Subadmin'))
        				{
        				    
        					$sclsub_id = $this->Cookie->read('subid');
        					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
        					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
        				}
        				
        				if($this->Cookie->read('logtype') == md5('School Subadmin'))
						{
							$action = in_array(38, $scl_privilage) ? $action : "";
							$download = in_array(40, $scl_privilage) ? $download : "";
						}
						else
						{
							$action = $action;
							$download = $download;
						}
                        
                        $data .= '<tr>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$val['feehead']['head_name'].'</span>
                            </td>
                            <td>$'.$val['amount'].'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> $'.$due.'</td>
                            <td>'.$action.' ' .$download.'</td>;
                        </tr>';
                        
                        if(!empty($retriev_studfee))
                        {
                            foreach($retriev_studfee as $key =>$fee_s){
                                $amt_coupn = '';
                                if(!empty($fee_s->coupon_amt))
                                {
                                    $amt_coupn = "$".$fee_s->coupon_amt;
                                    $totaldiscount += $fee_s->coupon_amt;
                                }
                                $totalpaid += $fee_s->amount;
                                
                                $daystrt = date("d-m-Y", $fee_s->created_date)." 00:01";
                                $dayend = date("d-m-Y", $fee_s->created_date)." 23:59";
                                
                                $strt_day = strtotime($daystrt);
                                $end_day = strtotime($dayend);
                                $now = time();
                                $edit = '';
                                if(($now >= $strt_day) && ($now <= $end_day))
                                {
                                    $edit = '<a href="javascript:void(0);" data-dueamt = "'.$due.'" data-availcoupn = "'.$availcoupon.'" data-id="'.$fee_s->id.'" data-student = "'.$student.'" data-class = "'.$id.'" data-session = "'.$start_year.'" data-fee_d_id="'.$val['id'].'" data-amount="'.$val['amount'].'" data-fee_s_id="'.$val['fee_s_id'].'" data-fee_h_id="'.$val['fee_h_id'].'" title="Edit" class="btn btn-outline-secondary estudntfee" data-toggle="modal" data-target="#editfee"><i class="fa fa-edit"></i></a>';
                                    //$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
                                    
                                }
                                $transdate = '';
                                if($fee_s->trans_date != "")
                                {
                                    $transdate = date("d/m/Y", $fee_s->trans_date);
                                }
                                $data .= '<tr>
                                    <td></td>
                                    <td></td>
                                    <td>'.$fee_s->payment_mode.'</td>
                                    <td>'.date("d/m/Y", $fee_s->created_date).'</td>
                                    <td>'.$fee_s->trans_id.'</td>
                                    <td>'.$transdate.'</td>
                                    <td>'.$amt_coupn.'</td>
                                    <td> $'.$fee_s->amount.'</td>
                                    <td></td>
                                    <td>'.$edit.'</td>;
                                </tr>';
                            }
                        }
                        
                    }
                    //print_r($data); die;
                    $data .= '<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>;
                    </tr>';
                    
                    $data .= '<tr>
                        <td><b>Total</b></td>
                        <td><b> $'.$totalamt.'</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b> $'.$totaldiscount.'</b></td>
                        <td><b> $'.$totalpaid.'</b></td>
                        <td><b> $'.$totaldue.'</b></td>
                        <td></td>;
                    </tr>';
                    
                    $paid_percent = round(($totalpaid/$totalamt)*100,2);
                    $due_percent = round(($totaldue/$totalamt)*100,2);
                    $discount_percent = round(($totaldiscount/$totalamt)*100,2);
                    
                    $data .= '<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>'.$discount_percent.'% </b></td>
                        <td><b>'.$paid_percent.'% </b></td>
                        <td><b>'.$due_percent.'% </b></td>
                        <td></td>;
                    </tr>';
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
                        if($langlbl['id'] == '2165') { $discamttt = $langlbl['title'] ; } 
                        if($langlbl['id'] == '2166') { $disc = $langlbl['title'] ; } 
                    } 
                
                    $grph[] = ['label' => $paidd, 'y' => $totalpaid, 'percent' => $paid_percent."%" ];
                    $grph[] = ['label' => $dueee, 'y' => $totaldue, 'percent' => $due_percent."%"];
                    $grph[] = ['label' => $disc, 'y' => $totaldiscount, 'percent' => $discount_percent."%" ];
                    
        		$datass = ['html' => $data, 'graph' => $grph, 'sessionyear' => $yr];
                return $this->json($datass);
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }  
    }
    
    public function getcoupon()
    {
        $discountstud_table = TableRegistry::get('discount_student');
        $clsid = $this->request->data['clsid'];
        $session = $this->request->data['session'];
        $studid = $this->request->data['studid'];
        $amt = $this->request->data['amt'];
        $this->request->session()->write('LAST_ACTIVE_TIME', time());  
        
        $getdiscoupons = $discountstud_table->find()->select(['feediscount.id', 'feediscount.discount_name','feediscount.percentage_amount','feediscount.amount'])->join([
            'feediscount' =>
            [
                'table' => 'feediscount',
                'type' => 'LEFT',
                'conditions' => 'feediscount.id = discount_student.discount_id'    
            ]
            ])->where(['discount_student.session_id' => $session, 'discount_student.student_id' => $studid, 'discount_student.class_id' => $clsid])->toArray();
        
        $feedis = '<option value="">Choose Coupon</option>';
        $coupnamt = '<option value="">Choose Amount</option>';
        foreach($getdiscoupons as $getcoupons)
        {
            if($getcoupons['feediscount']['percentage_amount'] == "amount")
            {
                $feedis .= '<option value="'.$getcoupons['feediscount']['id'].'" >'. $getcoupons['feediscount']['discount_name']." ( $". $getcoupons['feediscount']['amount'].")".'</option>';
            }
            else
            {
                $feedis .= '<option value="'.$getcoupons['feediscount']['id'].'" >'. $getcoupons['feediscount']['discount_name']." ( ". $getcoupons['feediscount']['amount']."% )".'</option>';
            }
        }
        
        $data = ['coupn' => $feedis ];
        return $this->json($data);
        
    }
    
    public function getcouponamt()
    {
        //print_r($_POST);
        $discount_table = TableRegistry::get('feediscount');
        $discid = $this->request->data('discid');
        $amt = $this->request->data('amt');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());  
        
        $getdiscoupons = $discount_table->find()->where(['id' => $discid])->toArray();
        
        $coupnamt = '<option value="">Choose Amount</option>';
        foreach($getdiscoupons as $getcoupons)
        {
            if($getcoupons['percentage_amount'] == "amount")
            {
                $coupnamt .= '<option value="'.$getcoupons['amount'].'">'.$getcoupons['amount'].'</option>';
            }
            else
            {
                $percnt = $getcoupons['amount'];
                $disamt = ($amt*$percnt)/100;
                $coupnamt .= '<option value="'.$disamt.'">'.$disamt.'</option>';
            }
        }
        
        $data = ['coupnamt' => $coupnamt, 'disamt' => $disamt ];
        return $this->json($data);
        
    }

    public function status()
    {   
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
            
    public function addstudentfees() 
    {   
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
                $coupon_amt = $this->request->data('couponamt');
                if(empty($coupon_amt))
                {
                    $coupon_amt = 0;
                }
                
                $fee = $student_fee_table->newEntity();
                $fee->cashmemo = $this->request->data('cashmemo');
                $fee->class_id = $this->request->data('classid');
                $fee->student_id = $this->request->data('studentid');
                $fee->frequency = $this->request->data('frequency');
                $fee->amount = $this->request->data('amount');
                $fee->start_year = $this->request->data('sessionid');
                $fee->session_id = $this->request->data('sessionid');
                $fee->payment_mode = $this->request->data('paymode');
                $fee->school_id = $compid;
                $fee->submission_date = time();
                $fee->created_date = time();   
                
                $fee->fee_h_id = $this->request->data('feehead');
                $fee->fee_d_id = $this->request->data('feedetail');
                $fee->fee_s_id = $this->request->data('feestructure');
                $fee->coupon_id = $this->request->data('couponid');
                $fee->coupon_amt = $this->request->data('couponamt');
                $fee->trans_id = $this->request->data('transid');
                $fee->trans_date = strtotime($this->request->data('transdate'));
                $checktransid = 0;
                if(!empty($this->request->data('transid')))
                {
                    $checktransid = $student_fee_table->find()->select(['id'])->where(['trans_id' => $this->request->data('transid'), 'school_id'=> $compid ])->count();
                }
                $coupnavail = $student_fee_table->find()->where(['class_id' => $this->request->data('classid'), 'start_year' => $this->request->data('sessionid'), 'student_id'=> $this->request->data('studentid') ])->toArray();
                $coupavail[] = 0;
                
                if(!empty($this->request->data('couponid')))
                { 
                    if(!empty($coupnavail))
                    { 
                        $coupavail = [];
                        foreach($coupnavail as $stufe)
                        { //print_r($stufe); 
                            if(!empty($stufe['coupon_id']))
                            {
                                //echo $stufe['coupon_id'];
                                if($this->request->data('couponid') == $stufe['coupon_id']):
                                    $coupavail[] = 1;
                                else:
                                    $coupavail[] = 0;
                                endif;
                            }
                            else
                            {
                                $coupavail[] = 0;
                            }
                        }
                        $submittedamt = array_sum($subamt);
                    }
                }
                //print_r($coupavail); die;
                $amount = $this->request->data('amount');
                $submittedamt = 0;
                $stufee = $student_fee_table->find()->select(['amount'])->where(['fee_h_id' => $this->request->data('feehead'),'class_id' => $this->request->data('classid'), 'start_year' => $this->request->data('sessionid'), 'student_id'=> $this->request->data('studentid') ])->toArray();
                if(!empty($stufee))
                {
                    foreach($stufee as $stufe)
                    {
                        $subamt[] = $stufe['amount'];
                    }
                    $submittedamt = array_sum($subamt);
                }
                $amttopay = $totalamt - $amount - $submittedamt - $coupon_amt;
                
                
                if(in_array("1", $coupavail))
                {
                    $res = [ 'result' => "You already grab the coupon."  ];
                }
                else
                {
                    /*if($checktransid == 0)
                    {*/
                    if($amount > 0)
                    {
                        if($amttopay >= 0)
                        {
                            /*if($this->request->data('paymode') == "cash")
                            {*/
                            //print_r($fee); die;
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
                            /*}
                            else
                            {
                                //return $this->redirect('/login/') ;    
                                $res = [ 'result' =>  $onlineamt ];
                            }*/
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
                    /*}
                    else
                    {
                        $res = ['result' => "This Trans. ID already exist."];
                    }*/
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
        $compid = $this->request->session()->read('company_id');
        $schoolid = md5($compid);
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
    
    public function feereport()
    {   
        $compid = $this->request->session()->read('company_id');
        $schoolid = md5($compid);
        $this->viewBuilder()->setLayout('user');
    }
            
    public function pdf($student = null, $feehead =null)
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
    		
        }
        else
        {
            $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
            foreach($retrieve_langlabel as $langlabel)
            {
                $langlabel->title = $langlabel['french_label'];
            }
            
    		$words = array('0' => '', '1' => 'Un', '2' => 'Deux',
    		'3' => 'Trois', '4' => 'Quatre', '5' => 'Cinq', '6' => 'Six',
    		'7' => 'Sept', '8' => 'Huit', '9' => 'Neuve',
    		'10' => 'Dix', '11' => 'Onze', '12' => 'Douze',
    		'13' => 'Treize', '14' => 'Quatorze',
    		'15' => 'Quinze', '16' => 'Seize', '17' => 'Dix-sept',
    		'18' => 'Dix-huit', '19' =>'Dix-neuf', '20' => 'Vingt',
    		'30' => 'Trente', '40' => 'Quarante', '50' => 'Cinquante',
    		'60' => 'Soixante', '70' => 'Soixante-dix',
    		'80' => 'Quatre-vingt', '90' => 'Quatre-vingt-dix');
    		$digits = array('', 'Cent', 'Mille', 'Lakh', 'Crore');
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
            if($langlbl['id'] == '2160') { $lbl2160 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2162') { $couplbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2178') { $Lbl2178 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2179') { $sclseal = $langlbl['title'] ; } 
            if($langlbl['id'] == '2161') { $lbl2161 = $langlbl['title'] ; } 
        } 
        
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $student_fee_table = TableRegistry::get('student_fee');
        $feehead_table = TableRegistry::get('feehead');
        if(!empty($compid))
        {
            $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid, 'student_id'=>$student, 'fee_h_id' => $feehead])->order(['id' => 'DESC'])->toArray();
            
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
            
            $retrieve_feehead = $feehead_table->find()->where([ 'id' => $feehead])->first();
            $ddata = ucfirst($retrieve_feehead['head_name']);
            
            $new_date = date('d/m/Y',$retrieve_studfee_table[0]->submission_date);
            $data_html = ' <tr><th style="width: 100%; border:1px solid #ccc; text-align:center">'.$feercptlbl.'</th></tr>
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
            
            $retrieve_feedtl = $feedetail_table->find()->select(['feedetail.id', 'feedetail.amount' ])->where([ 'feedetail.fee_h_id' => $feehead, 'feedetail.fee_s_id' => $retrieve_feestruc->id ])->order(['id' => 'ASC'])->first();
            //print_r($retrieve_feedtl);
            $i=0;
            $fee_data ='<tr><td style="padding:0px !important;">
                <table id="fee_detail" style=" width: 100%; border:1px solid #ccc;">
                <tr>
                    <th style="text-align:left">Sr. No.</th> 
                    <th style="text-align:left">'.$lbl2161.'</th> 
                    <th style="text-align:left">'.$lbl2160.'</th> 
                    <th style="text-align:left">Trans. Id</th> 
                    <th style="text-align:left">Trans. Date</th> 
                    <th style="text-align:left">'.$paidlbl.'</th>
                </tr>';
            
            $retrieve_studfee = $student_fee_table->find()->where([ 'school_id '=> $compid, 'student_id'=>$student, 'fee_h_id' => $feehead])->order(['id' => 'DESC'])->toArray();
            
            foreach($retrieve_studfee as $fee){
                $amount = $fee->amount;
                $amount = round($amount);
                $j = $i+1;
                $transdate = '';
                if($fee->trans_date != "")
                {
                    $transdate = date("d/m/Y", $fee->trans_date);
                }
                
                $fee_data .='<tr>
                    <td style="text-align:left">'.$j.'</td>
                    <td style="text-align:left">'.date("d/m/Y", $fee->submission_date).'</td>
                    <td style="text-align:left">'.ucfirst($fee->payment_mode).'</td>
                    <td style="text-align:left">'.$fee->trans_id.'</td>
                    <td style="text-align:left">'.$transdate.'</td>
                    <td style="text-align:left">'.$amount.'</td>
                </tr>';
                $i++;
            }
        
            
            $total_amt = round($retrieve_feedtl->amount);
            
            $paid_amt =0;
            $disc_amt = 0;
            foreach($retrieve_studfee_table as $std_fee){
                $paid_amt += $std_fee->amount;
                $disc_amt += $std_fee->coupon_amt;
            }
            
            $due_amt = $total_amt-$paid_amt-$disc_amt;
        
        
            $number = $paid_amt;
    		$no = floor($number);
    		$point = round($number - $no, 2) * 100;
    		$hundred = null;
    		$digits_1 = strlen($no);
    		$i = 0;
    		$str = array();
    		
    		
    		
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
                            <td style="width: 27%;">'.$paidamtlbl.':</td>
                            <td style="width: 53%;"></td>
                            <td style="width: 20%;">$'.$paid_amt.'</td>
                        </tr>
                        <tr>
                            <td style="width: 27%;">'.$couplbl.':</td>
                            <td style="width: 53%;"></td>
                            <td style="width: 20%;">$'.$disc_amt.'</td>
                        </tr>
                        <tr>
                            <td style="width: 27%;">'.$duelbl.':</td>
                            <td style="width: 53%;"></td>
                            <td style="width: 20%;">$'.$due_amt.'</td>
                        </tr>
                        <tr>
                            <td style="width: 27%;">'.$totlamtlbl.':</td>
                            <td style="width: 53%;"></td>
                            <td style="width: 20%;">$'.$total_amt.'</td>
                        </tr>
                        
                        <tr>
                            <td style="width: 27%;">'.$amontwrdlbl.':</td>
                            <td style="width: 60%;"> '.$amt_words.' ('.$Lbl2178.').</td>
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
                                                        <p> <b> E-mail: </b>'.$retrieve_school[0]['email'].' </p>
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
            
            $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p>
            </div><div style="text-align:right; margin-top:70px;">'.$sclseal.'</div>';
            //print_r($viewpdf); die;
            $dompdf = new Dompdf();
            $options = $dompdf->getOptions();
    		$options->setIsHtml5ParserEnabled(true);
    		$options->set('isRemoteEnabled', true);
        	$dompdf->setOptions($options);
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
    
    public function groupclass()
    {
        $this->viewBuilder()->setLayout('user');
        
        $feediscount_table = TableRegistry::get('feediscount');
        $discount_table = TableRegistry::get('discount_student');
        $class_table = TableRegistry::get('class');
        $compid =$this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessid =  $this->Cookie->read('sessionid');
        if(!empty($compid))
        {
            $retrieve_feedis = $feediscount_table->find()->where(['school_id' => $compid])->toArray();
            $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray();
            $this->set("class_details", $retrieve_class);  
            
            $session_table = TableRegistry::get('session');
            $getsession = $session_table->find()->toArray(); 
            $this->set("session_details", $getsession);
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function consolidated()
    {
        if(!empty($_POST))
        {
            $strtdt = $this->request->data('startdate');
            $enddt = $this->request->data('enddate');
            $compid = $this->request->session()->read('company_id');
            $strtdte = strtotime($this->request->data('startdate')." 00:01");
            $enddte = strtotime($this->request->data('enddate')." 23:59");
            
            $studfee_table = TableRegistry::get('student_fee');
            $stud_table = TableRegistry::get('student');
            $class_table = TableRegistry::get('class');
            $feehead_table = TableRegistry::get('feehead');
            
            $getstudfee = $studfee_table->find()->where(['created_date >=' => $strtdte, 'created_date <=' => $enddte, 'school_id' => $compid])->order(['created_date' => 'DESC'])->toArray(); 
            foreach($getstudfee as $feestu)
            {
                $getstudnm = $stud_table->find()->where(['id' => $feestu['student_id']])->first(); 
                $feestu->stud_name = $getstudnm['l_name']." ".$getstudnm['f_name'];
                
                $getclsnm = $class_table->find()->where(['id' => $feestu['class_id']])->first(); 
                $feestu->cls_name = $getclsnm['c_name']."-".$getclsnm['c_section']." (".$getclsnm['school_sections'].")";
                
                $getheadnm = $feehead_table->find()->where(['id' => $feestu['fee_h_id']])->first(); 
                $feestu->head_name = $getheadnm['head_name'];
            }
            //print_r($getstudfee); die;
            $this->set("studfee", $getstudfee);
            $this->set("startdate", $strtdt);
            $this->set("enddate", $enddt);
            $styledr = "style='display:block;'";
            $this->set("styledr", $styledr);
        }
        else
        {
            $styledr = "style='display:none;'";
            $this->set("styledr", $styledr);
        }
        $this->viewBuilder()->setLayout('user');
    }
    
    public function completedue()
    {
        $this->viewBuilder()->setLayout('user');
        
        $feediscount_table = TableRegistry::get('feediscount');
        $discount_table = TableRegistry::get('discount_student');
        $class_table = TableRegistry::get('class');
        $compid =$this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessid =  $this->Cookie->read('sessionid');
        if(!empty($compid))
        {
            $retrieve_feedis = $feediscount_table->find()->where(['school_id' => $compid])->toArray();
            $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray();
            $this->set("class_details", $retrieve_class);  
            
            $session_table = TableRegistry::get('session');
            $getsession = $session_table->find()->toArray(); 
            $this->set("session_details", $getsession);
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }

    public function getreport()
    {
        //print_r($_POST); die;
        $clsid = $this->request->data('cls');
        $sctn = $this->request->data('sctn');
        $class_tbl = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        if($clsid == "all")
        {
            $classpriv = explode(",", $sctn);
            $clasids = [];
            if(in_array("Maternelle", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    if($cls->school_sections == "Maternelle" || $cls->school_sections == "Creche") {
                        $clasids[] = $cls->id;
                    }
                }
            }
            if(in_array("Primaire", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections' => "Primaire", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $clasids[] = $cls->id;
                }
            }
            if(in_array("secondaire", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections !=' => "Maternelle", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Primaire", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Creche", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $clasids[] = $cls->id;
                }
            }
            $clsids = $clasids;
            $count_cls = count($clsids); 
        }
        else
        {
            /*$clsids = explode(",", $clasid);
            $count_cls = count($clsids);*/
            $count_cls = 1;
        }
        
        
        $session = $this->request->data('session');
        $class_tbl = TableRegistry::get('class');
        $student_tbl = TableRegistry::get('student');
        $session_tbl = TableRegistry::get('session');
        $studentfee_tbl = TableRegistry::get('student_fee');
        $feehead_tbl = TableRegistry::get('feehead');
        $feedtl_tbl = TableRegistry::get('feedetail');
        $feestruc_tbl = TableRegistry::get('fee_structure');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_session = $session_tbl->find()->where([ 'id' => $session])->first();
        $sessname = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        
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
            if($langlbl['id'] == '147') { $studname = $langlbl['title'] ; } 
            if($langlbl['id'] == '345') { $amt = $langlbl['title'] ; } 
            if($langlbl['id'] == '359') { $paid = $langlbl['title'] ; } 
            if($langlbl['id'] == '360') { $due = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $discnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1812') { $paidamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1813') { $dueamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1358') { $paidd = $langlbl['title'] ; } 
            if($langlbl['id'] == '1359') { $dueee = $langlbl['title'] ; } 
            if($langlbl['id'] == '2165') { $discamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $disc = $langlbl['title'] ; } 
            if($langlbl['id'] == '2174') { $porcnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2183') { $grndttl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2185') { $annamt = $langlbl['title'] ; } 
        } 
        
        if($count_cls != 1)
        {
            /*$get_cls = $class_tbl->find()->where(['school_id' => $compid])->toArray();
            $clsids = [];
            foreach($get_cls as $cls)
            {
                $clsids[] = $cls['id'];
            }*/
            
            $cls_struc = $feestruc_tbl->find()->where(['class_id IN' => $clsids, 'start_year' => $session])->toArray();
            $feestrucs = [];
            foreach($cls_struc as $strc)
            {
                $feestrucs[] = $strc['id'];
            }
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id IN' => $feestrucs])->order(['id' => 'ASC'])->group(['fee_h_id'])->toArray();
            $feedesc ='';
            $thd = '';
            
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center;">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
            } 
            $thd_ann = '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2">Classes</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center;">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.$thd_ann.'    
                    </tr></thead>';
            $tblbody = '<tbody>';
            foreach($clsids as $clsid)
            {
                $get_cls = $class_tbl->find()->where(['id' => $clsid])->first();
                //$clsid = $cls['id'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                    
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
                    //print_r($feedtlinstl);
                    $totalamt = 0;
                    if(!empty($feedtlinstl))
                    {
                        $totalamt = $feedtlinstl['amount'];
                    }
                    //echo $totalamt;
                    $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
                    $totalnostud = count($get_stud);
                    $totalstudamt = $totalnostud*$totalamt;
                    $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        if(!empty($sfee['coupon_amt']))
                        {
                            $disamt += $sfee['coupon_amt'];
                        }
                    }
                    
                    $dueamt = $totalstudamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right;">$'.$paidamt.'</td><td style="text-align:right;">$'.$dueamt.'</td><td style="text-align:right;">$'.$disamt.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$totalstudamt.'</td>';
                
                    $annual_amt[] = $totalstudamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                $tdttl = '<td style="text-align:right;">$'.$annual_p.'</td><td style="text-align:right;">$'.$annual_d.'</td><td style="text-align:right;">$'.$annual_di.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$annual_t.'</td>';
                
                $tblbody .= '<tr>
                    <td>'.ucfirst($get_cls['c_name']."-".$get_cls['c_section']." (".$get_cls['school_sections'].")").'</td>
                    '.$tdb.$tdttl.'
                </tr>';
            }
            
            $ttlsum = '';
            $ttlper = '';
            $graphttl = [];
            $graphpaid = [];
            $graphdisc = [];
            $graphdue = [];
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            foreach($feedtl as $dtl)
            {
                $tamt = [];
                $arrta = 0;
                foreach($clsids as $cls)
                {
                    $cls_strucid = $feestruc_tbl->find()->where(['class_id' =>$cls, 'start_year' => $session])->first();
                    
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
                
                    $totalamt = 0;
                    if(!empty($feedtlinstl))
                    {
                        $totalamt = $feedtlinstl['amount'];
                    }
                    //echo $totalamt;
                    $get_stud = $student_tbl->find()->where(['class' => $cls, 'session_id' => $session])->toArray();
                    $totalnostud = count($get_stud);
                    $tamt[] = $totalnostud*$totalamt;
                    
                }    
                //print_r($tamt);
                $arrtat = array_sum($tamt);
                $arrta += $arrtat;
                $studfee = $studentfee_tbl->find()->where(['fee_h_id' => $dtl['fee_h_id'], 'class_id IN' => $clsids, 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right;"><b>$'.$arrpd.'</b></td><td style="text-align:right;"><b>$'.$arrdu.'</b></td><td style="text-align:right;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center;"><b>'.$paidper.'%</b></td><td style="text-align:center;"><b>'.$dueper.'%</b></td><td style="text-align:center;"><b>'.$discper.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                
                $graphttl[] = $arrta;
                $graphpaid[] = $arrpd;
                $graphdisc[] = $arrdi;
                $graphdue[] = $arrdu;
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right;"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$ann_gt_tt.'</b></td>';
            
            $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
            $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
            $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            
            $annu_percnt .= '<td style="text-align:center;"><b>'.$paidper_ann.'%</b></td><td style="text-align:center;"><b>'.$dueper_ann.'%</b></td><td style="text-align:center;"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                
                
            //die;
            $tblbody .= '<tr>
                    <td><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>
                </tbody>';
            $tbldata = $tblhead.$tblbody;
            
            $totalpaid = array_sum($graphpaid);
            $totaldue = array_sum($graphdue);
            $totaldiscount = array_sum($graphdisc);
            $totalamount = array_sum($graphttl);
            
            $paid_percent = round(($totalpaid/$totalamount)*100,2);
            $due_percent = round(($totaldue/$totalamount)*100,2);
            $discount_percent = round(($totaldiscount/$totalamount)*100,2);
            
            $grph[] = ['label' => $paidd, 'y' => $totalpaid, 'percent' => $paid_percent."%" ];
            $grph[] = ['label' => $dueee, 'y' => $totaldue, 'percent' => $due_percent."%"];
            $grph[] = ['label' => $disc, 'y' => $totaldiscount, 'percent' => $discount_percent."%" ];
            
            $res = ['tblhead' => $tblhead, 'tblbody' => $tblbody, 'tabledata' => $tbldata, 'graph' => $grph, 'sessionyear' => $sessname, 'feedtl' => $feedtl, 'count_cls' => $count_cls];
        }
        else
        {
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
            
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id']])->order(['id' => 'ASC'])->toArray();
            
            $feedesc ='';
            $thd = '';
            $graphttl = [];
            $graphpaid = [];
            $graphdisc = [];
            $graphdue = [];
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center;">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
                $arrta = 0;
                $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
            
                $totalamt = 0;
                if(!empty($feedtlinstl))
                {
                    $totalamt = $feedtlinstl['amount'];
                }
                
                $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
                $totalnostud = count($get_stud);
                $arrta = $totalnostud*$totalamt;
                    
                $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right;"><b>$'.$arrpd.'</b></td><td style="text-align:right;"><b>$'.$arrdu.'</b></td><td style="text-align:right;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center;"><b>'.$paidper.'%</b></td><td style="text-align:center;"><b>'.$dueper.'%</b></td><td style="text-align:center;"><b>'.$discper.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                
                $graphttl[] = $arrta;
                $graphpaid[] = $arrpd;
                $graphdisc[] = $arrdi;
                $graphdue[] = $arrdu;
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right;"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$ann_gt_tt.'</b></td>';
            if($ann_gt_tt == 0):
                $paidper_ann = 0;
                $dueper_ann = 0;
                $discper_ann = 0;  
            else:
                $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
                $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
                $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            endif;
            $annu_percnt .= '<td style="text-align:center;"><b>'.$paidper_ann.'%</b></td><td style="text-align:center;"><b>'.$dueper_ann.'%</b></td><td style="text-align:center;"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                
            
            $thd .= '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2">'.$studname.'</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center;">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $dtl['fee_h_id'] ])->first();
                    $totalamt = $feedtlinstl['amount'];
                    $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'] ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        $disamt += $sfee['coupon_amt'];
                    }
                    $dueamt = $totalamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right;">$'.$paidamt.'</td><td style="text-align:right;">$'.$dueamt.'</td><td style="text-align:right;">$'.$disamt.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$totalamt.'</td>';
                
                    $annual_amt[] = $totalamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                $tdttl = '<td style="text-align:right;">$'.$annual_p.'</td><td style="text-align:right;">$'.$annual_d.'</td><td style="text-align:right;">$'.$annual_di.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$annual_t.'</td>';
                
                $tblbody .= '<tr>
                    <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                    '.$tdb.$tdttl.'
                </tr>';
            }
            $tblbody .= '<tr>
                    <td><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>';
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
            
            $totalpaid = array_sum($graphpaid);
            $totaldue = array_sum($graphdue);
            $totaldiscount = array_sum($graphdisc);
            $totalamount = array_sum($graphttl);
            
            $paid_percent = round(($totalpaid/$totalamount)*100,2);
            $due_percent = round(($totaldue/$totalamount)*100,2);
            $discount_percent = round(($totaldiscount/$totalamount)*100,2);
            
            $grph[] = ['label' => $paidd, 'y' => $totalpaid, 'percent' => $paid_percent."%" ];
            $grph[] = ['label' => $dueee, 'y' => $totaldue, 'percent' => $due_percent."%"];
            $grph[] = ['label' => $disc, 'y' => $totaldiscount, 'percent' => $discount_percent."%" ];
            
            $res = ['tblhead' => $tblhead, 'tblbody' => $tblbody, 'tabledata' => $tbldata, 'graph' => $grph, 'sessionyear' => $sessname, 'feedtl' => $feedtl, 'count_cls' => $count_cls];
        }
        return $this->json($res);
    }
    
    public function downloadreport($session, $sctn, $clasid)
    {
        $class_tbl = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        if($clasid == "all")
        {
            $classpriv = explode(",", $sctn);
            $clsids = [];
            //print_r($classpriv);
            if(in_array("Maternelle", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    if($cls->school_sections == "Maternelle" || $cls->school_sections == "Creche") {
                        $clsids[] = $cls->id;
                    }
                }
            }
            if(in_array("Primaire", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections' => "Primaire", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $clsids[] = $cls->id;
                }
            }
            if(in_array("secondaire", $classpriv))
            {
                $retrieve_class = $class_tbl->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections !=' => "Maternelle", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Primaire", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Creche", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $clsids[] = $cls->id;
                }
            }
            $clsids = $clsids;
            $count_cls = count($clsids); 
            //print_r($clsids);
        }
        else
        {
            /*$clsids = explode(",", $clasid);
            $count_cls = count($clsids);*/
            $count_cls = 1;
        }
        /*$clsid = $this->request->data('cls');
        $session = $this->request->data('session');*/
        $class_tbl = TableRegistry::get('class');
        $session_tbl = TableRegistry::get('session');
        $student_tbl = TableRegistry::get('student');
        $school_tbl = TableRegistry::get('company');
        $studentfee_tbl = TableRegistry::get('student_fee');
        $feehead_tbl = TableRegistry::get('feehead');
        $feedtl_tbl = TableRegistry::get('feedetail');
        $feestruc_tbl = TableRegistry::get('fee_structure');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
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
            if($langlbl['id'] == '147') { $studname = $langlbl['title'] ; } 
            if($langlbl['id'] == '345') { $amt = $langlbl['title'] ; } 
            if($langlbl['id'] == '359') { $paid = $langlbl['title'] ; } 
            if($langlbl['id'] == '360') { $due = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $discnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '643') { $contctnolbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '9') { $claslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '238') { $sesslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2174') { $porcnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2183') { $grndttl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2185') { $annamt = $langlbl['title'] ; } 
        } 
        
        $retrieve_session = $session_tbl->find()->where([ 'id' => $session])->first();
        $sessname = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        
        $retrieve_school = $school_tbl->find()->where([ 'id' => $compid])->toArray();
        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important; height:100px; background-color:#ffffff !important;">';
        $clsname = '';
        if($count_cls == 1)
        {   
            $get_clsname = $class_tbl->find()->where(['id' => $clasid])->first();
            $clsname = $get_clsname['c_name']."-".$get_clsname['c_section']." (".$get_clsname['school_sections'].")";
            
            $datacls = '<div  style="width: 35%; text-align:left; padding:5px;float: left;">
                    <p style="margin: 5px 0 !important;"> <b> '.$claslbl.': </b>'.$clsname.' </p>
                    <p style="margin: 5px 0 !important;"> <b> '.$sesslbl.': </b>'.$sessname.' </p>
                </div>';
        }
        else
        {   
            $datacls = '<div  style="width: 35%; text-align:left; padding:5px;float: left;">
                    <p style="margin: 5px 0 !important;"> <b> '.$sesslbl.': </b>'.$sessname.' </p>
                </div>';
        }
        
        if($count_cls != 1)
        {
            /*$get_cls = $class_tbl->find()->where(['school_id' => $compid])->toArray();
            $clsids = [];
            foreach($get_cls as $cls)
            {
                $clsids[] = $cls['id'];
            }*/
            //print_r($clsids); die;
            $cls_struc = $feestruc_tbl->find()->where(['class_id IN' => $clsids, 'start_year' => $session])->toArray();
            $feestrucs = [];
            foreach($cls_struc as $strc)
            {
                $feestrucs[] = $strc['id'];
            }
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id IN' => $feestrucs])->order(['id' => 'ASC'])->group(['fee_h_id'])->toArray();
            $feedesc ='';
            $thd = '';
            
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center;  border: 1px solid #ddd">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th style="border: 1px solid #ddd;">'.$paid.'</th><th style="border: 1px solid #ddd;">'.$due.'</th><th style="border: 1px solid #ddd;">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
            } 
            $thd_ann = '<th style="border: 1px solid #ddd;">'.$paid.'</th><th style="border: 1px solid #ddd;">'.$due.'</th><th style="border: 1px solid #ddd;">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2" style="border: 1px solid #ddd;">Classes</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center; border: 1px solid #ddd;">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.$thd_ann.'    
                    </tr></thead>';
            $tblbody = '<tbody>';
            foreach($clsids as $clsid)
            {
                $get_cls = $class_tbl->find()->where(['id' => $clsid])->first();
                //$clsid = $cls['id'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                    
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
                    //print_r($feedtlinstl);
                    $totalamt = 0;
                    if(!empty($feedtlinstl))
                    {
                        $totalamt = $feedtlinstl['amount'];
                    }
                    //echo $totalamt;
                    $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
                    $totalnostud = count($get_stud);
                    $totalstudamt = $totalnostud*$totalamt;
                    $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        if(!empty($sfee['coupon_amt']))
                        {
                            $disamt += $sfee['coupon_amt'];
                        }
                    }
                    
                    $dueamt = $totalstudamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right; border: 1px solid #ddd;">$'.$paidamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$dueamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$disamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$totalstudamt.'</td>';
                
                    $annual_amt[] = $totalstudamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                $tdttl = '<td style="text-align:right; border: 1px solid #ddd;">$'.$annual_p.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_d.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_di.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_t.'</td>';
                
                $tblbody .= '<tr>
                    <td style="border: 1px solid #ddd;">'.ucfirst($get_cls['c_name']."-".$get_cls['c_section']." (".$get_cls['school_sections'].")").'</td>
                    '.$tdb.$tdttl.'
                </tr>';
            }
            
            $ttlsum = '';
            $ttlper = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            foreach($feedtl as $dtl)
            {
                $tamt = [];
                $arrta = 0;
                foreach($clsids as $cls)
                {
                    $cls_strucid = $feestruc_tbl->find()->where(['class_id' =>$cls, 'start_year' => $session])->first();
                    
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
                
                    $totalamt = 0;
                    if(!empty($feedtlinstl))
                    {
                        $totalamt = $feedtlinstl['amount'];
                    }
                    //echo $totalamt;
                    $get_stud = $student_tbl->find()->where(['class' => $cls, 'session_id' => $session])->toArray();
                    $totalnostud = count($get_stud);
                    $tamt[] = $totalnostud*$totalamt;
                    
                }    
                //print_r($tamt);
                $arrtat = array_sum($tamt);
                $arrta += $arrtat;
                $studfee = $studentfee_tbl->find()->where(['fee_h_id' => $dtl['fee_h_id'], 'class_id IN' => $clsids, 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right; border: 1px solid #ddd"><b>$'.$arrpd.'</b></td><td style="text-align:right; border: 1px solid #ddd"><b>$'.$arrdu.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center; border: 1px solid #ddd"><b>'.$paidper.'%</b></td><td style="text-align:center; border: 1px solid #ddd"><b>'.$dueper.'%</b></td><td style="text-align:center; border: 1px solid #ddd"><b>'.$discper.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right; border: 1px solid #ddd"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right; border: 1px solid #ddd"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right; border: 1px solid #ddd"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_tt.'</b></td>';
            
            $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
            $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
            $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            
            $annu_percnt .= '<td style="text-align:center; border: 1px solid #ddd"><b>'.$paidper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd"><b>'.$dueper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                
            $tblbody .= '<tr>
                    <td style="border: 1px solid #ddd;"><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>
                </tbody>';
            $tbldata = $tblhead.$tblbody;
        }
        else
        {
            $clsid = $clasid ;
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
            
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id']])->order(['id' => 'ASC'])->toArray();
            
            $feedesc ='';
            $thd = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center; border: 1px solid #ddd;">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th style="border: 1px solid #ddd;">'.$paid.'</th><th style="border: 1px solid #ddd;">'.$due.'</th><th style="border: 1px solid #ddd;">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
                $arrta = 0;
                $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
            
                $totalamt = 0;
                if(!empty($feedtlinstl))
                {
                    $totalamt = $feedtlinstl['amount'];
                }
                
                $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
                $totalnostud = count($get_stud);
                $arrta = $totalnostud*$totalamt;
                    
                $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right;border: 1px solid #ddd;"><b>$'.$arrpd.'</b></td><td style="text-align:right;border: 1px solid #ddd;"><b>$'.$arrdu.'</b></td><td style="text-align:right;border: 1px solid #ddd;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center; border: 1px solid #ddd;"><b>'.$paidper.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>'.$dueper.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>'.$discper.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_tt.'</b></td>';
            if($ann_gt_tt == 0):
                $paidper_ann = 0;
                $dueper_ann = 0;
                $discper_ann = 0;  
            else:
                $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
                $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
                $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            endif;
            $annu_percnt .= '<td style="text-align:center; border: 1px solid #ddd;"><b>'.$paidper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>'.$dueper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                
            
            $thd .= '<th style="border: 1px solid #ddd;">'.$paid.'</th><th style="border: 1px solid #ddd;">'.$due.'</th><th style="border: 1px solid #ddd;">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2" style="border: 1px solid #ddd;">'.$studname.'</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center; border: 1px solid #ddd;">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $dtl['fee_h_id'] ])->first();
                    $totalamt = $feedtlinstl['amount'];
                    $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'] ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        $disamt += $sfee['coupon_amt'];
                    }
                    $dueamt = $totalamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right; border: 1px solid #ddd;">$'.$paidamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$dueamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$disamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$totalamt.'</td>';
                
                    $annual_amt[] = $totalamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                $tdttl = '<td style="text-align:right; border: 1px solid #ddd;">$'.$annual_p.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_d.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_di.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_t.'</td>';
                
                $tblbody .= '<tr>
                    <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                    '.$tdb.$tdttl.'
                </tr>';
            }
            $tblbody .= '<tr>
                    <td><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>';
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
        }
        
        $header = '<div style=" width: 100%;">
                <div style="width: 12%; text-align:left; padding:25px 0px 5px 10px; background:#ffffff !important; float: left;">
                    <span> '.$school_logo.' </span>
                </div>
                <div  style="width: 50%; text-align:center; padding:5px;float: left;">
                    <p style=" font-size: 22px; font-weight:bold; margin: 5px 0 !important;">'.ucfirst($retrieve_school[0]['comp_name']).'</p>
                    <p style="margin: 5px 0 !important;"> '.ucfirst($retrieve_school[0]['add_1']).' ,  '.ucfirst($retrieve_school[0]['city']).' </p>
                    <p style="margin: 5px 0 !important;"> <b> '.$contctnolbl.': </b>'.ucfirst($retrieve_school[0]['ph_no']).' </p>
                    <p style="margin: 5px 0 !important;"> <b> E-mail: </b>'.$retrieve_school[0]['email'].' </p>
                </div>
                '.$datacls.'
            </div>';
        
        $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p>
        </div><div><table style=" width: 100%; border:1px solid #ccc; padding-top:150px; ">'.$tbldata.'</table></div>';
        //print_r($viewpdf); die;
        $title = "FeeReport";
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
        $dompdf->loadHtml($viewpdf);    
        
        $dompdf->setPaper('A4', 'Landscape');
        $dompdf->render();
        $dompdf->stream($title.".pdf");

        exit(0);
    }
    
    public function downloadconsolidated($strtdte, $enddte)
    {
        $compid = $this->request->session()->read('company_id');
        $studfee_table = TableRegistry::get('student_fee');
        $stud_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $feehead_table = TableRegistry::get('feehead');
        
        $getstudfee = $studfee_table->find()->where(['created_date >=' => $strtdte, 'created_date <=' => $enddte, 'school_id' => $compid])->order(['created_date' => 'DESC'])->toArray(); 
        
        $dbdata = [];
        $coupm_arr = 0;
        $amt_arr = 0;
        foreach($getstudfee as $feestu)
        {
            if($feestu['coupon_amt'] != "") { 
                $ca = "$".$feestu['coupon_amt'];
                $coupm_arr += $feestu['coupon_amt'];
            } else { 
                $ca = ''; 
            } 
            $amt_arr += $feestu['amount'];
            
            $getstudnm = $stud_table->find()->where(['id' => $feestu['student_id']])->first(); 
            $feestud['stud_name'] = $getstudnm['l_name']." ".$getstudnm['f_name'];
            
            $getclsnm = $class_table->find()->where(['id' => $feestu['class_id']])->first(); 
            $feestud['cls_name'] = $getclsnm['c_name']."-".$getclsnm['c_section']." (".$getclsnm['school_sections'].")";
            
            $getheadnm = $feehead_table->find()->where(['id' => $feestu['fee_h_id']])->first(); 
            $feestud['head_name'] = $getheadnm['head_name'];
            
            $feestud['payment_date'] = date("d/m/Y", $feestu['created_date']);
            $feestud['payment_mode'] = $feestu['payment_mode'];
            $feestud['trans_id'] = $feestu['trans_id'];
            $feestud['couponamt'] = $ca;
            $feestud['amount'] = "$".$feestu['amount'];
            
            $dbdata[] = $feestud;
            
        }
        
        $fs['stud_name'] = '';
        $fs['cls_name'] = '';
        $fs['head_name'] = '';
        $fs['payment_date'] = '';
        $fs['payment_mode'] = '';
        $fs['trans_id'] = '';
        $fs['couponamt'] = "$".$coupm_arr;
        $fs['amount'] = "$".$amt_arr;
        array_push($dbdata, $fs);
        
        //print_r($dbdata); die;
        $title = 'ConsolidatedReport.csv';
        $this->setResponse($this->getResponse()->withDownload($title));
        $_header = array('Student Name','Class','Description','Payment Date', 'Payment Date', 'Trans. ID' , 'Coupon Amount' , 'Paid Amount');
        
        $_serialize = 'dbdata';
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('dbdata', '_header' , '_serialize'));
        
    }
    
    public function getcls()
    {
        //print_r($_POST['selclses']); die;
        $compid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $classpriv = $this->request->data['selclses'];
        $func = $this->request->data('func');
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
        foreach($retrieve_langlabel as $langlbl) {
            if($langlbl['id'] == '2095') { $lbl2095 = $langlbl['title'] ; }
        }
        
        $data = '<option value="">Choose Class</option>';
        $clsids = [];
        if(!empty($classpriv))
        {
            if($func == "gc") {
                if(in_array("Maternelle", $classpriv))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                    foreach($retrieve_class as $cls)
                    {
                        if($cls->school_sections == "Maternelle" || $cls->school_sections == "Creche") {
                            $clsids[] = $cls->id;
                        }
                    }
                }
                if(in_array("Primaire", $classpriv))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections' => "Primaire", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                    foreach($retrieve_class as $cls)
                    {
                        $clsids[] = $cls->id;
                    }
                }
                if(in_array("secondaire", $classpriv))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections !=' => "Maternelle", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Primaire", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Creche", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                    foreach($retrieve_class as $cls)
                    {
                        $clsids[] = $cls->id;
                    }
                }
                $clsidss = implode(",", $clsids);
                //$data .= '<option value="'.$clsidss.'">'. $lbl2095 .'</option>';
                $data .= '<option value="all">'. $lbl2095 .'</option>';
            }
            if(in_array("Maternelle", $classpriv))
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    if($cls->school_sections == "Maternelle" || $cls->school_sections == "Creche") {
                        $data .= '<option value="'.$cls->id.'">'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                    }
                }
                
            }
            if(in_array("Primaire", $classpriv))
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections' => "Primaire", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $data .= '<option value="'.$cls->id.'">'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                }
                
            }
            if(in_array("secondaire", $classpriv))
            {
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_sections !=' => "Maternelle", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Primaire", 'school_id' => $compid, 'active' => 1])->andwhere(['school_sections !=' => "Creche", 'school_id' => $compid, 'active' => 1])->order(['id' => 'ASC'])->toArray() ;
                foreach($retrieve_class as $cls)
                {
                    $data .= '<option value="'.$cls->id.'">'.$cls->c_name.'-'.$cls->c_section.'('.$cls->school_sections.')</option>';
                }
            }
        }
        return $this->json($data);
       
    }
    
    public function getcoupon1()
    {
        $discountstud_table = TableRegistry::get('discount_student');
        $studfee_table = TableRegistry::get('student_fee');
        $clsid = $this->request->data['clsid'];
        $session = $this->request->data['session'];
        $studid = $this->request->data['studid'];
        $amt = $this->request->data['amt'];
        $id = $this->request->data('id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());  
        
        $getstudfee = $studfee_table->find()->where(['id' => $id])->first();
        if(!empty($getstudfee['trans_date']))
        {
            $getstudfee['transaction_date'] = date("Y-m-d", $getstudfee['trans_date']);
        }
        else
        {
            $getstudfee['transaction_date'] = '';
        }
        
        $getdiscoupons = $discountstud_table->find()->select(['feediscount.id', 'feediscount.discount_name','feediscount.percentage_amount','feediscount.amount'])->join([
            'feediscount' =>
            [
                'table' => 'feediscount',
                'type' => 'LEFT',
                'conditions' => 'feediscount.id = discount_student.discount_id'    
            ]
            ])->where(['discount_student.session_id' => $session, 'discount_student.student_id' => $studid, 'discount_student.class_id' => $clsid])->toArray();
        
        $feedis = '<option value="">Choose Coupon</option>';
        $coupnamt = '<option value="">Choose Amount</option>';
        foreach($getdiscoupons as $getcoupons)
        {
            if($getcoupons['feediscount']['percentage_amount'] == "amount")
            {
                $feedis .= '<option value="'.$getcoupons['feediscount']['id'].'" >'. $getcoupons['feediscount']['discount_name']." ( $". $getcoupons['feediscount']['amount'].")".'</option>';
            }
            else
            {
                $feedis .= '<option value="'.$getcoupons['feediscount']['id'].'" >'. $getcoupons['feediscount']['discount_name']." ( ". $getcoupons['feediscount']['amount']."% )".'</option>';
            }
        }
        
        $data = ['coupn' => $feedis, 'studfee' => $getstudfee];
        return $this->json($data);
        
    }
    
    public function editstudentfees() 
    {   
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
                $coupon_amt = $this->request->data('couponamt');
                if(empty($coupon_amt))
                {
                    $coupon_amt = 0;
                }
                
                $coupnavail = $student_fee_table->find()->where(['id !=' => $this->request->data('id'), 'class_id' => $this->request->data('classid'), 'start_year' => $this->request->data('sessionid'), 'student_id'=> $this->request->data('studentid') ])->toArray();
                $coupavail[] = 0;
                if(!empty($this->request->data('couponid')))
                {
                    if(!empty($coupnavail))
                    {
                        $coupavail = [];
                        foreach($coupnavail as $stufe)
                        {
                            if(!empty($stufe['coupon_id']))
                            {
                                if($this->request->data('couponid') == $stufe['coupon_id']):
                                    $coupavail[] = 1;
                                else:
                                    $coupavail[] = 0;
                                endif;
                            }
                            else
                            {
                                $coupavail[] = 0;
                            }
                        }
                        $submittedamt = array_sum($subamt);
                    }
                }
                
                $amount = $this->request->data('amount');
                $id = $this->request->data('id');
                $submittedamt = 0;
                $stufee = $student_fee_table->find()->select(['amount'])->where(['id !=' => $this->request->data('id'), 'fee_h_id' => $this->request->data('feehead'),'class_id' => $this->request->data('classid'), 'start_year' => $this->request->data('sessionid'), 'student_id'=> $this->request->data('studentid') ])->toArray();
                if(!empty($stufee))
                {
                    foreach($stufee as $stufe)
                    {
                        $subamt[] = $stufe['amount'];
                    }
                    $submittedamt = array_sum($subamt);
                }
                $amttopay = $totalamt - $amount - $submittedamt - $coupon_amt;
                
                $cashmemo = $this->request->data('cashmemo');
                $amount = $this->request->data('amount');
                $payment_mode = $this->request->data('paymode');
                $coupon_id = $this->request->data('couponid');
                $coupon_amt = $this->request->data('couponamt');
                $trans_id = $this->request->data('transid');
                $trans_date = strtotime($this->request->data('transdate'));
                //print_r($_POST);
                if(in_array("1", $coupavail))
                {
                    $res = [ 'result' => "You already grab the coupon."  ];
                }
                else
                {
                    if($amount > 0)
                    {
                        if($amttopay >= 0)
                        {
                            if($student_fee_table->query()->update()->set(['trans_id' => $trans_id, 'trans_date' => $trans_date, 'cashmemo' => $cashmemo, 'amount' => $amount, 'payment_mode' => $payment_mode, 'coupon_id' => $coupon_id, 'coupon_amt' => $coupon_amt ])->where([ 'id' => $id ])->execute())
                            {     
                                $strucid = $id;
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Fee student updated"  ;
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
                            $res = [ 'result' => $allotamt ." $".$submittedamt  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => $amtneg  ];
                    }
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
    
    public function getinstdesc()
    {
        $compid = $this->request->session()->read('company_id');
        $feestruc_table = TableRegistry::get('fee_structure');
        $feedtl_table = TableRegistry::get('feedetail');
        $feehead_table = TableRegistry::get('feehead');
        $sessionid = $this->request->data('sess');
        
        $clsid = $this->request->data('clsid');
        $retrieve_strcu = $feestruc_table->find()->where(['class_id' => $clsid, 'start_year' => $sessionid, 'school_id' => $compid])->first() ;
                
        $data = '<option value="">Choose Value</option>';
        if(!empty($retrieve_strcu))
        {
            $struc_id = $retrieve_strcu['id'];
            $retrieve_detlng = $feedtl_table->find()->where(['fee_s_id' => $struc_id, 'session_id' => $sessionid, 'school_id' => $compid])->order(['id' => 'ASC'])->toArray() ;
            foreach($retrieve_detlng as $dtl)
            {
                $retrieve_head = $feehead_table->find()->where(['id' => $dtl['fee_h_id'] ])->first() ;
                $data .= '<option value="'.$retrieve_head->id.'">'.ucfirst($retrieve_head->head_name).'</option>';
            }
            $data .= '<option value="annual">Annual Amount</option>';
        }
        return $this->json($data);
       
    }
    
    public function getcdreport()
    {
        $clsid = $this->request->data('cls');
        $session = $this->request->data('sess');
        $cd = $this->request->data('cd');
        $desc = $this->request->data('desc');
        
        $class_tbl = TableRegistry::get('class');
        $student_tbl = TableRegistry::get('student');
        $session_tbl = TableRegistry::get('session');
        $studentfee_tbl = TableRegistry::get('student_fee');
        $feehead_tbl = TableRegistry::get('feehead');
        $feedtl_tbl = TableRegistry::get('feedetail');
        $feestruc_tbl = TableRegistry::get('fee_structure');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_session = $session_tbl->find()->where([ 'id' => $session])->first();
        $sessname = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        
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
            if($langlbl['id'] == '147') { $studname = $langlbl['title'] ; } 
            if($langlbl['id'] == '345') { $amt = $langlbl['title'] ; } 
            if($langlbl['id'] == '359') { $paid = $langlbl['title'] ; } 
            if($langlbl['id'] == '360') { $due = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $discnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1812') { $paidamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1813') { $dueamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1358') { $paidd = $langlbl['title'] ; } 
            if($langlbl['id'] == '1359') { $dueee = $langlbl['title'] ; } 
            if($langlbl['id'] == '2165') { $discamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $disc = $langlbl['title'] ; } 
            if($langlbl['id'] == '2174') { $porcnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2183') { $grndttl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2185') { $annamt = $langlbl['title'] ; } 
        } 
        
        if($desc == "annual")
        {
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
        
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id']])->order(['id' => 'ASC'])->toArray();
            
            $feedesc ='';
            $thd = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
                $stuids = [];
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $dtl['fee_h_id'] ])->first();
                    $totalamt = $feedtlinstl['amount'];
                    $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'] ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        $disamt += $sfee['coupon_amt'];
                    }
                    $dueamt = $totalamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right;">$'.$paidamt.'</td><td style="text-align:right;">$'.$dueamt.'</td><td style="text-align:right;">$'.$disamt.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$totalamt.'</td>';
                
                    $annual_amt[] = $totalamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                
                $amtleft = $annual_t-($annual_p+$annual_di);
                if($amtleft == 0 && $cd == "completed")
                {
                    $stuids[] = $stud['id'];
                    $tdttl = '<td style="text-align:right;">$'.$annual_p.'</td><td style="text-align:right;">$'.$annual_d.'</td><td style="text-align:right;">$'.$annual_di.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$annual_t.'</td>';
                    
                    $tblbody .= '<tr>
                        <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.$tdttl.'
                    </tr>';
                }
                else if($amtleft != 0 && $cd == "dues")
                {
                    $stuids[] = $stud['id'];
                    $tdttl = '<td style="text-align:right;">$'.$annual_p.'</td><td style="text-align:right;">$'.$annual_d.'</td><td style="text-align:right;">$'.$annual_di.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$annual_t.'</td>';
                    
                    $tblbody .= '<tr>
                        <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.$tdttl.'
                    </tr>';
                }
            }
            
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center;">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
                $arrta = 0;
                $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
            
                $totalamt = 0;
                if(!empty($feedtlinstl))
                {
                    $totalamt = $feedtlinstl['amount'];
                }
                if(!empty($stuids)):
                $get_stud = $student_tbl->find()->where(['class' => $clsid, 'id IN' => $stuids, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
                $totalnostud = count($stuids);
                $arrta = $totalnostud*$totalamt;
                    
                $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'student_id IN' => $stuids, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right;"><b>$'.$arrpd.'</b></td><td style="text-align:right;"><b>$'.$arrdu.'</b></td><td style="text-align:right;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center;"><b>'.$paidper.'%</b></td><td style="text-align:center;"><b>'.$dueper.'%</b></td><td style="text-align:center;"><b>'.$discper.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                endif;
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right;"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right;"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$ann_gt_tt.'</b></td>';
            if($ann_gt_tt == 0):
                $paidper_ann = 0;
                $dueper_ann = 0;
                $discper_ann = 0;  
            else:
                $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
                $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
                $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            endif;
            $annu_percnt .= '<td style="text-align:center;"><b>'.$paidper_ann.'%</b></td><td style="text-align:center;"><b>'.$dueper_ann.'%</b></td><td style="text-align:center;"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border-right: 1px solid #000;"><b>100%</b></td>';
                
            
            $thd .= '<th>'.$paid.'</th><th>'.$due.'</th><th>'.$discnt.'</th><th style="border-right: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2">'.$studname.'</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center;">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
            if(!empty($stuids)):        
            $tblbody .= '<tr>
                    <td><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>';
            endif;
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
           
            
            $res = ['tblhead' => $tblhead, 'tblbody' => $tblbody, 'tabledata' => $tbldata, 'feedtl' => $feedtl];
        }
        else
        {
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            
            $feedesc ='';
            $thd = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
            $stuids = [];
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
               
                
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $desc ])->first();
                $totalamt = $feedtlinstl['amount'];
                $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $desc ])->toArray();
                $paidamt = 0;
                $disamt = 0;
                foreach($studfee as $sfee)
                {
                    $paidamt += $sfee['amount'];
                    $disamt += $sfee['coupon_amt'];
                }
                $dueamt = $totalamt-$paidamt-$disamt;
                $tdb .= '<td style="text-align:right;">$'.$paidamt.'</td><td style="text-align:right;">$'.$dueamt.'</td><td style="text-align:right;">$'.$disamt.'</td><td style="text-align:right; border-right: 1px solid #000;">$'.$totalamt.'</td>';
            
                $amtleft = $totalamt-($paidamt+$disamt);
                if($amtleft == 0 && $cd == "completed")
                {
                    $stuids[] = $stud['id'];
                    
                    $tblbody .= '<tr>
                        <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.'
                    </tr>';
                }
                else if($amtleft != 0 && $cd == "dues")
                {
                    $stuids[] = $stud['id'];
                    
                    $tblbody .= '<tr>
                        <td>'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.'
                    </tr>';
                }
            }
            //print_r($stuids); die;
            
            $feehead = $feehead_tbl->find()->where(['id' => $desc])->first();
            $feedesc .= '<th colspan="4" style="text-align:center;">'.$feehead['head_name'].'</th>';
            
            $thd .= '<th style="text-align:right;">'.$paid.'</th><th style="text-align:right;">'.$due.'</th><th style="text-align:right;">'.$discnt.'</th><th style="text-align:right; border-right: 1px solid #ddd;">'.$amt.'</th>';
            $arrta = 0;
            $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $desc, 'session_id' => $session ])->first();
        
            $totalamt = 0;
            if(!empty($feedtlinstl))
            {
                $totalamt = $feedtlinstl['amount'];
            }
            if(!empty($stuids)):  
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'id IN' => $stuids, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
            $totalnostud = count($stuids);
            $arrta = $totalnostud*$totalamt;
                
            $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'student_id IN' => $stuids, 'fee_h_id' => $desc, 'session_id' => $session ])->toArray();
           
            $arrpd = 0;
            $arrdu = 0;
            $arrdi = 0;
            foreach($studfee as $sfee)
            {
                $arrpd += $sfee['amount'];
                if(!empty($sfee['coupon_amt']))
                {
                    $arrdi += $sfee['coupon_amt'];
                }
            }
                
            $arrdu = $arrta-$arrpd-$arrdi;
            $ttlsum .= '<td style="text-align:right;"><b>$'.$arrpd.'</b></td><td style="text-align:right;"><b>$'.$arrdu.'</b></td><td style="text-align:right;"><b>$'.$arrdi.'</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>$'.$arrta.'</b></td>';
            
            $gt_totl[] = $arrta;
            $gt_paid[] = $arrpd;
            $gt_due[] = $arrdu;
            $gt_dis[] = $arrdi;
            
            $paidper = round((($arrpd/$arrta)*100),2);
            $dueper = round((($arrdu/$arrta)*100),2);
            $discper = round((($arrdi/$arrta)*100),2);
            
            $ttlper .= '<td style="text-align:right;"><b>'.$paidper.'%</b></td><td style="text-align:right;"><b>'.$dueper.'%</b></td><td style="text-align:right;"><b>'.$discper.'%</b></td><td style="text-align:right; border-right: 1px solid #000;"><b>100%</b></td>';
            
            $tblbody .= '<tr>
                    <td><b>'.$grndttl.'</b></td>
                    '.$ttlsum.'
                </tr>
                <tr>
                    <td><b>'.$porcnt.'</b></td>
                    '.$ttlper.'
                </tr>';
            endif;
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2">'.$studname.'</th>
                        '.$feedesc.'
                        
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
                    
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
           
            
            $res = ['tblhead' => $tblhead, 'tblbody' => $tblbody, 'tabledata' => $tbldata, 'feedtl' => $feedtl];
        }
        return $this->json($res);
    }
    
    public function downloadcdreport($session, $clsid, $cd, $desc)
    {
        $class_tbl = TableRegistry::get('class');
        $student_tbl = TableRegistry::get('student');
        $session_tbl = TableRegistry::get('session');
        $studentfee_tbl = TableRegistry::get('student_fee');
        $feehead_tbl = TableRegistry::get('feehead');
        $school_tbl = TableRegistry::get('company');
        $feedtl_tbl = TableRegistry::get('feedetail');
        $feestruc_tbl = TableRegistry::get('fee_structure');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_session = $session_tbl->find()->where([ 'id' => $session])->first();
        $sessname = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        
        $retrieve_school = $school_tbl->find()->where([ 'id' => $compid])->toArray();
        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important; height:100px; background-color:#ffffff !important;">';
           
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
            if($langlbl['id'] == '147') { $studname = $langlbl['title'] ; } 
            if($langlbl['id'] == '345') { $amt = $langlbl['title'] ; } 
            if($langlbl['id'] == '359') { $paid = $langlbl['title'] ; } 
            if($langlbl['id'] == '360') { $due = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $discnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1812') { $paidamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1813') { $dueamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '1358') { $paidd = $langlbl['title'] ; } 
            if($langlbl['id'] == '1359') { $dueee = $langlbl['title'] ; } 
            if($langlbl['id'] == '2165') { $discamttt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2166') { $disc = $langlbl['title'] ; } 
            if($langlbl['id'] == '2174') { $porcnt = $langlbl['title'] ; } 
            if($langlbl['id'] == '2183') { $grndttl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2185') { $annamt = $langlbl['title'] ; } 
            if($langlbl['id'] == '643') { $contctnolbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '9') { $claslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '238') { $sesslbl = $langlbl['title'] ; } 
        } 
        
        if($desc == "annual")
        {
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
        
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id']])->order(['id' => 'ASC'])->toArray();
            
            $feedesc ='';
            $thd = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
                $stuids = [];
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
                $annual_amt = [];
                $annual_paid = [];
                $annual_due = [];
                $annual_dis = [];
                foreach($feedtl as $dtl)
                {
                    $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $dtl['fee_h_id'] ])->first();
                    $totalamt = $feedtlinstl['amount'];
                    $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $dtl['fee_h_id'] ])->toArray();
                    $paidamt = 0;
                    $disamt = 0;
                    foreach($studfee as $sfee)
                    {
                        $paidamt += $sfee['amount'];
                        $disamt += $sfee['coupon_amt'];
                    }
                    $dueamt = $totalamt-$paidamt-$disamt;
                    $tdb .= '<td style="text-align:right; border:1px solid #ddd">$'.$paidamt.'</td><td style="text-align:right; border:1px solid #ddd">$'.$dueamt.'</td><td style="text-align:right; border:1px solid #ddd">$'.$disamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$totalamt.'</td>';
                
                    $annual_amt[] = $totalamt;
                    $annual_paid[] = $paidamt;
                    $annual_due[] = $dueamt;
                    $annual_dis[] = $disamt;
                    
                } 
                
                $annual_t = array_sum($annual_amt);
                $annual_p = array_sum($annual_paid);
                $annual_d = array_sum($annual_due);
                $annual_di = array_sum($annual_dis);
                
                $amtleft = $annual_t-($annual_p+$annual_di);
                if($amtleft == 0 && $cd == "completed")
                {
                    $stuids[] = $stud['id'];
                    $tdttl = '<td style="text-align:right; border:1px solid #ddd">$'.$annual_p.'</td><td style="text-align:right; border:1px solid #ddd">$'.$annual_d.'</td><td style="text-align:right; border:1px solid #ddd">$'.$annual_di.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_t.'</td>';
                    
                    $tblbody .= '<tr>
                        <td style="border:1px solid #ddd">'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.$tdttl.'
                    </tr>';
                }
                else if($amtleft != 0 && $cd == "dues")
                {
                    $stuids[] = $stud['id'];
                    $tdttl = '<td style="text-align:right; border:1px solid #ddd">$'.$annual_p.'</td><td style="text-align:right; border:1px solid #ddd">$'.$annual_d.'</td><td style="text-align:right; border:1px solid #ddd">$'.$annual_di.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$annual_t.'</td>';
                    
                    $tblbody .= '<tr>
                        <td style="border:1px solid #ddd">'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.$tdttl.'
                    </tr>';
                }
            }
            
            foreach($feedtl as $dtl)
            {
                $feehead = $feehead_tbl->find()->where(['id' => $dtl['fee_h_id']])->first();
                $feedesc .= '<th colspan="4" style="text-align:center; border:1px solid #ddd">'.$feehead['head_name'].'</th>';
                
                $thd .= '<th style="border:1px solid #ddd">'.$paid.'</th><th style="border:1px solid #ddd">'.$due.'</th><th style="border:1px solid #ddd">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
                $arrta = 0;
                $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->first();
            
                $totalamt = 0;
                if(!empty($feedtlinstl))
                {
                    $totalamt = $feedtlinstl['amount'];
                }
                if(!empty($stuids)):
                $get_stud = $student_tbl->find()->where(['class' => $clsid, 'id IN' => $stuids, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
                $totalnostud = count($stuids);
                $arrta = $totalnostud*$totalamt;
                    
                $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'student_id IN' => $stuids, 'fee_h_id' => $dtl['fee_h_id'], 'session_id' => $session ])->toArray();
               
                $arrpd = 0;
                $arrdu = 0;
                $arrdi = 0;
                foreach($studfee as $sfee)
                {
                    $arrpd += $sfee['amount'];
                    if(!empty($sfee['coupon_amt']))
                    {
                        $arrdi += $sfee['coupon_amt'];
                    }
                }
                
                $arrdu = $arrta-$arrpd-$arrdi;
                $ttlsum .= '<td style="text-align:right; border:1px solid #ddd"><b>$'.$arrpd.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$arrdu.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$arrdi.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$arrta.'</b></td>';
                
                $gt_totl[] = $arrta;
                $gt_paid[] = $arrpd;
                $gt_due[] = $arrdu;
                $gt_dis[] = $arrdi;
                
                $paidper = round((($arrpd/$arrta)*100),2);
                $dueper = round((($arrdu/$arrta)*100),2);
                $discper = round((($arrdi/$arrta)*100),2);
                
                $ttlper .= '<td style="text-align:center; border:1px solid #ddd"><b>'.$paidper.'%</b></td><td style="text-align:center; border:1px solid #ddd"><b>'.$dueper.'%</b></td><td style="text-align:center; border:1px solid #ddd"><b>'.$discper.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                endif;
            }
            $ann_gt_tt = array_sum($gt_totl);
            $ann_gt_pd = array_sum($gt_paid);
            $ann_gt_di = array_sum($gt_dis);
            $ann_gt_du = array_sum($gt_due);
            
            $annu_sum .= '<td style="text-align:right; border:1px solid #ddd"><b>$'.$ann_gt_pd.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$ann_gt_du.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$ann_gt_di.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$ann_gt_tt.'</b></td>';
            if($ann_gt_tt == 0):
                $paidper_ann = 0;
                $dueper_ann = 0;
                $discper_ann = 0;  
            else:
                $paidper_ann = round((($ann_gt_pd/$ann_gt_tt)*100),2);
                $dueper_ann = round((($ann_gt_du/$ann_gt_tt)*100),2);
                $discper_ann = round((($ann_gt_di/$ann_gt_tt)*100),2);   
            endif;
            $annu_percnt .= '<td style="text-align:center; border:1px solid #ddd"><b>'.$paidper_ann.'%</b></td><td style="text-align:center; border:1px solid #ddd"><b>'.$dueper_ann.'%</b></td><td style="text-align:center; border:1px solid #ddd"><b>'.$discper_ann.'%</b></td><td style="text-align:center; border: 1px solid #ddd;"><b>100%</b></td>';
                
            
            $thd .= '<th style="border:1px solid #ddd">'.$paid.'</th><th style="border:1px solid #ddd">'.$due.'</th><th style="border:1px solid #ddd">'.$discnt.'</th><th style="border: 1px solid #ddd;">'.$amt.'</th>';
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2" style="border:1px solid #ddd">'.$studname.'</th>
                        '.$feedesc.'
                        <th colspan="4" style="text-align:center; border:1px solid #ddd">'.$annamt.'</th>
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
            if(!empty($stuids)):        
            $tblbody .= '<tr>
                    <td style="border:1px solid #ddd"><b>'.$grndttl.'</b></td>
                    '.$ttlsum.$annu_sum.'
                </tr>
                <tr>
                    <td style="border:1px solid #ddd"><b>'.$porcnt.'</b></td>
                    '.$ttlper.$annu_percnt.'
                </tr>';
            endif;
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
           
            
            $res = ['tblhead' => $tblhead, 'tblbody' => $tblbody, 'tabledata' => $tbldata, 'feedtl' => $feedtl];
        }
        else
        {
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'session_id' => $session])->toArray();
            $cls_struc = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            
            $feedesc ='';
            $thd = '';
            $gt_totl = [];
            $gt_paid = [];
            $gt_due = [];
            $gt_dis = [];
            
            $tblbody = '<tbody>';
            if(!empty($get_stud)):
            $stuids = [];
            foreach($get_stud as $stud)
            {
                $stuid = $stud['id'];
                $clsid = $stud['class'];
                $tdb = '';
               
                
                $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_struc['id'], 'fee_h_id' => $desc ])->first();
                $totalamt = $feedtlinstl['amount'];
                $studfee = $studentfee_tbl->find()->where(['student_id' => $stuid, 'class_id' => $clsid, 'fee_h_id' => $desc ])->toArray();
                $paidamt = 0;
                $disamt = 0;
                foreach($studfee as $sfee)
                {
                    $paidamt += $sfee['amount'];
                    $disamt += $sfee['coupon_amt'];
                }
                $dueamt = $totalamt-$paidamt-$disamt;
                $tdb .= '<td style="text-align:right; border:1px solid #ddd">$'.$paidamt.'</td><td style="text-align:right; border:1px solid #ddd">$'.$dueamt.'</td><td style="text-align:right; border:1px solid #ddd">$'.$disamt.'</td><td style="text-align:right; border: 1px solid #ddd;">$'.$totalamt.'</td>';
            
                $amtleft = $totalamt-($paidamt+$disamt);
                if($amtleft == 0 && $cd == "completed")
                {
                    $stuids[] = $stud['id'];
                    
                    $tblbody .= '<tr>
                        <td style="border:1px solid #ddd">'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.'
                    </tr>';
                }
                else if($amtleft != 0 && $cd == "dues")
                {
                    $stuids[] = $stud['id'];
                    
                    $tblbody .= '<tr>
                        <td style="border:1px solid #ddd">'.ucfirst($stud['l_name']." ".$stud['f_name']).'</td>
                        '.$tdb.'
                    </tr>';
                }
            }
            //print_r($stuids); die;
            
            $feehead = $feehead_tbl->find()->where(['id' => $desc])->first();
            $feedesc .= '<th colspan="4" style="text-align:center;  border:1px solid #ddd;">'.$feehead['head_name'].'</th>';
            
            $thd .= '<th style="text-align:right; border:1px solid #ddd">'.$paid.'</th><th style="text-align:right; border:1px solid #ddd">'.$due.'</th><th style="text-align:right; border:1px solid #ddd">'.$discnt.'</th><th style="text-align:right; border: 1px solid #ddd; ">'.$amt.'</th>';
            $arrta = 0;
            $cls_strucid = $feestruc_tbl->find()->where(['class_id' => $clsid, 'start_year' => $session])->first();
            $feedtlinstl = $feedtl_tbl->find()->where(['fee_s_id' => $cls_strucid['id'], 'fee_h_id' => $desc, 'session_id' => $session ])->first();
        
            $totalamt = 0;
            if(!empty($feedtlinstl))
            {
                $totalamt = $feedtlinstl['amount'];
            }
            if(!empty($stuids)):  
            $get_stud = $student_tbl->find()->where(['class' => $clsid, 'id IN' => $stuids, 'session_id' => $session])->order(['l_name' => 'ASC'])->toArray();
            $totalnostud = count($stuids);
            $arrta = $totalnostud*$totalamt;
                
            $studfee = $studentfee_tbl->find()->where(['class_id' => $clsid, 'student_id IN' => $stuids, 'fee_h_id' => $desc, 'session_id' => $session ])->toArray();
           
            $arrpd = 0;
            $arrdu = 0;
            $arrdi = 0;
            foreach($studfee as $sfee)
            {
                $arrpd += $sfee['amount'];
                if(!empty($sfee['coupon_amt']))
                {
                    $arrdi += $sfee['coupon_amt'];
                }
            }
                
            $arrdu = $arrta-$arrpd-$arrdi;
            $ttlsum .= '<td style="text-align:right; border:1px solid #ddd"><b>$'.$arrpd.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$arrdu.'</b></td><td style="text-align:right; border:1px solid #ddd"><b>$'.$arrdi.'</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>$'.$arrta.'</b></td>';
            
            $gt_totl[] = $arrta;
            $gt_paid[] = $arrpd;
            $gt_due[] = $arrdu;
            $gt_dis[] = $arrdi;
            
            $paidper = round((($arrpd/$arrta)*100),2);
            $dueper = round((($arrdu/$arrta)*100),2);
            $discper = round((($arrdi/$arrta)*100),2);
            
            $ttlper .= '<td style="text-align:right; border:1px solid #ddd"><b>'.$paidper.'%</b></td><td style="text-align:right; border:1px solid #ddd"><b>'.$dueper.'%</b></td><td style="text-align:right; border:1px solid #ddd"><b>'.$discper.'%</b></td><td style="text-align:right; border: 1px solid #ddd;"><b>100%</b></td>';
            
            $tblbody .= '<tr>
                    <td style="border:1px solid #ddd"><b>'.$grndttl.'</b></td>
                    '.$ttlsum.'
                </tr>
                <tr>
                    <td style="border:1px solid #ddd"><b>'.$porcnt.'</b></td>
                    '.$ttlper.'
                </tr>';
            endif;
            $tblhead = '<thead class="thead-dark"><tr>
                        <th rowspan="2" style="text-align:left; border:1px solid #ddd">'.$studname.'</th>
                        '.$feedesc.'
                        
                    </tr>
                    <tr>
                        '.$thd.'    
                    </tr></thead>';
                    
            endif;
            $tblbody .= '</tbody>';
            $tbldata = $tblhead.$tblbody;
        }
        
        $get_clsname = $class_tbl->find()->where(['id' => $clsid])->first();
        $clsname = $get_clsname['c_name']."-".$get_clsname['c_section']." (".$get_clsname['school_sections'].")";
        
        $datacls = '<div  style="width: 35%; text-align:left; padding:5px;float: left;">
                <p style="margin: 5px 0 !important;"> <b> '.$claslbl.': </b>'.$clsname.' </p>
                <p style="margin: 5px 0 !important;"> <b> '.$sesslbl.': </b>'.$sessname.' </p>
            </div>';
        $header = '<div style=" width: 100%;">
                <div style="width: 12%; text-align:left; padding:25px 0px 5px 10px; background:#ffffff !important; float: left;">
                    <span> '.$school_logo.' </span>
                </div>
                <div  style="width: 50%; text-align:center; padding:5px;float: left;">
                    <p style=" font-size: 22px; font-weight:bold; margin: 5px 0 !important;">'.ucfirst($retrieve_school[0]['comp_name']).'</p>
                    <p style="margin: 5px 0 !important;"> '.ucfirst($retrieve_school[0]['add_1']).' ,  '.ucfirst($retrieve_school[0]['city']).' </p>
                    <p style="margin: 5px 0 !important;"> <b> '.$contctnolbl.': </b>'.ucfirst($retrieve_school[0]['ph_no']).' </p>
                    <p style="margin: 5px 0 !important;"> <b> E-mail: </b>'.$retrieve_school[0]['email'].' </p>
                </div>
                '.$datacls.'
            </div>';
        
        $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p>
        </div><div><table style=" width: 100%; border:1px solid #ccc; padding-top:150px; ">'.$tbldata.'</table></div>';
        //print_r($viewpdf); die;
        $title = "Completed-Due FeeReport";
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
        $dompdf->loadHtml($viewpdf);    
        
        $dompdf->setPaper('A4', 'Landscape');
        $dompdf->render();
        $dompdf->stream($title.".pdf");

        exit(0);
        
    }
    
    /******************Canteen ****************/
    public function canteen()
    {   
        $canteenfee_table = TableRegistry::get('canteen_fund');
        $compid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session'); 
        $student_table = TableRegistry::get('student'); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_canteenfee = $canteenfee_table->find()->where([ 'canteen_fund.school_id '=> $compid])->order(['id' => 'DESC'])->toArray() ;
            
            foreach($retrieve_canteenfee as $key =>$fee_c)
    		{
    		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_c->class_id])->toArray();
    			 $fee_c->c_name = $retrieve_class[0]->c_name;
    			 $fee_c->c_section = $retrieve_class[0]->c_section;
    			 $fee_c->school_section = $retrieve_class[0]->school_sections;
    			 
    			 $retrieve_stud = $student_table->find()->select(['id' ,'adm_no','f_name', 'l_name'])->where(['id' => $fee_c->student_id])->toArray() ;
    			 $fee_c->student = $retrieve_stud[0]->l_name.'-'.$retrieve_stud[0]->f_name;
    			 $fee_c->adm = $retrieve_stud[0]->adm_no;
    			 
    			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_c->session_id])->toArray() ;
    			 $fee_c->start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
    		}
    		
    		$class_table = TableRegistry::get('class');
            $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
            
            $this->set("class_details", $retrieve_class);
            
            
            $retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear'])->order(['startyear' => 'ASC'])->toArray() ;
            $this->set("session_details", $retrieve_session);
            
            $this->set("feecanteen_details", $retrieve_canteenfee); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function getstud()
    {   
        if($this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->request->data['clsid'];
            $start_year = $this->request->data['start_year'];
            $student_table = TableRegistry::get('student');
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid))
            {
                $retrieve_student = $student_table->find(all,  ['order' => ['l_name'=>'asc']])->where(['class' => $id, 'status' => 1, 'school_id' => $compid, 'session_id' => $start_year])->toArray(); 
                $all_data = '<option value="">Choose student<option>'; 
                
                foreach($retrieve_student as $std)
                {
                    $all_data .= '<option value="'.$std['id'].'">'.$std['l_name'].' '.$std['f_name'].' ('.$std['adm_no'].')<option>'; 
                }
                return $this->json($all_data);
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }  
    }
    
    public function addcanteen()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $cf_table = TableRegistry::get('canteen_fund');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($compid))
            {
                $classes = $this->request->data['class'];
                $student = $this->request->data['student'];
                $structure_ids = [];
                
                    $fee = $cf_table->newEntity();
                    $fee->class_id = $classes;
                    $fee->student_id = $student;
                    $fee->amount = $this->request->data('amount');
                    $fee->daily_limit = $this->request->data('daily_limit');
                    $fee->deposit_by = $this->request->data('deposit_by');
                    $fee->session_id = $this->request->data('start_year');
					//$fee->status = 1;
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
                        if($saved = $cf_table->save($fee))
                        {     
                            $strucid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Student canteen fee Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = $strucid; 
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
                return $this->redirect('/login/') ;   
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
    }
    
    public function cfdelete()
    {
        $rid = $this->request->data('val') ;
        $canteenfee_table = TableRegistry::get('canteen_fund');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
            
        $del = $canteenfee_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
        if($del)
		{
            $activity = $activ_table->newEntity();
            $activity->action =  "student canteen fee successfully Deleted!"  ;
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
    
    public function updatecanteen() 
    {   
        if($this->request->is('post')){
            $id = $this->request->data['id'];
            $canteenfee_table = TableRegistry::get('canteen_fund');
            $class_table = TableRegistry::get('class');
            $student_table = TableRegistry::get('student');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_canteenfee = $canteenfee_table->find()->where([ 'id' => $id])->first() ;
            
            $retrieve_student = $student_table->find()->where([ 'class' => $retrieve_canteenfee['class_id'], 'status' => 1, 'session_id' => $retrieve_canteenfee['session_id'] ])->toArray() ;
            $data = '<option value="">Choose Student</option>';
            foreach($retrieve_student as $student)
            {
                $sel = '';
                if($student['id'] == $retrieve_canteenfee['student_id'])
                {
                    $sel = 'selected';
                }
                $data .= '<option value="'.$student['id'].'" '.$sel.'>'.$student['l_name']." ".$student['f_name'].' ('.$student['adm_no'].')</option>';
            }
            
            $all['student'] = $data;
            $all['timediff'] = time();
            $all['canteen'] = $retrieve_canteenfee;
            return $this->json($all);
        }  
    }
    
    public function egetstud()
    {   
        if($this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->request->data['clsid'];
            $stuid = $this->request->data['stuid'];
            $start_year = $this->request->data['start_year'];
            $student_table = TableRegistry::get('student');
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid))
            {
                $retrieve_student = $student_table->find(all,  ['order' => ['l_name'=>'asc']])->where(['class' => $id, 'status' => 1, 'school_id' => $compid, 'session_id' => $start_year])->toArray(); 
                $all_data = '<option value="">Choose student<option>'; 
                if($stuid == "class")
                {
                    foreach($retrieve_student as $std)
                    {
                        $all_data .= '<option value="'.$std['id'].'">'.$std['l_name'].' '.$std['f_name'].' ('.$std['adm_no'].')<option>'; 
                    }
                }
                else
                {
                    foreach($retrieve_student as $std)
                    {
                        $sel = '';
                        if($std['id'] == $stuid) {
                            $sel = "selected";
                        }
                        $all_data .= '<option value="'.$std['id'].'" '.$sel.'>'.$std['l_name'].' '.$std['f_name'].' ('.$std['adm_no'].')<option>'; 
                    }
                }
                return $this->json($all_data);
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }  
    }
    
    public function editcanteen()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $cf_table = TableRegistry::get('canteen_fund');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($compid))
            {
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
                $classes = $this->request->data('class');
                $student = $this->request->data('estudent');
                $id = $this->request->data('eid');
                $class_id = $classes;
                $student_id = $student;
                $amount = $this->request->data('amount');
                $session_id = $this->request->data('start_year');
                $school_id = $compid;
                $daily_limit = $this->request->data('daily_limit');
                $deposit_by = $this->request->data('deposit_by');
                
                $amount = $this->request->data('amount');
                if($amount > 0)
                {
                    if($update = $cf_table->query()->update()->set(['class_id' => $class_id , 'deposit_by' => $deposit_by, 'daily_limit' => $daily_limit, 'student_id' => $student_id , 'amount' => $amount, 'session_id' => $session_id ])->where([ 'id' => $id  ])->execute())
                    {     
                        $strucid = $id;
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Student canteen fee updated"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = $strucid; 
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
                return $this->redirect('/login/') ;   
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
    }
    
    public function canteenfundreport()
    {
        if(!empty($_POST))
        {
            $strtdt = $this->request->data('startdate');
            $enddt = $this->request->data('enddate');
            $strtdte = strtotime($this->request->data('startdate')." 00:01");
            $enddte = strtotime($this->request->data('enddate')." 23:59");
            
            $canteenfee_table = TableRegistry::get('canteen_fund');
            $compid = $this->request->session()->read('company_id');
            $class_table = TableRegistry::get('class');
            $session_table = TableRegistry::get('session'); 
            $student_table = TableRegistry::get('student'); 
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            
            $retrieve_canteenfee = $canteenfee_table->find()->where(['created_date >=' => $strtdte, 'created_date <=' => $enddte, 'canteen_fund.school_id '=> $compid])->order(['id' => 'ASC'])->toArray() ;
            
            foreach($retrieve_canteenfee as $key =>$fee_c)
    		{
    		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_c->class_id])->toArray();
    			 $fee_c->c_name = $retrieve_class[0]->c_name;
    			 $fee_c->c_section = $retrieve_class[0]->c_section;
    			 $fee_c->school_section = $retrieve_class[0]->school_sections;
    			 
    			 $retrieve_stud = $student_table->find()->select(['id' ,'adm_no','f_name', 'l_name'])->where(['id' => $fee_c->student_id])->toArray() ;
    			 $fee_c->student = $retrieve_stud[0]->l_name.'-'.$retrieve_stud[0]->f_name;
    			 $fee_c->adm = $retrieve_stud[0]->adm_no;
    			 
    			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_c->session_id])->toArray() ;
    			 $fee_c->start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
    		}
    		$this->set("feecanteen_details", $retrieve_canteenfee); 
            $this->set("studfee", $getstudfee);
            $this->set("startdate", $strtdt);
            $this->set("enddate", $enddt);
            $styledr = "style='display:block;'";
            $this->set("styledr", $styledr);
        }
        else
        {
            $styledr = "style='display:none;'";
            $this->set("styledr", $styledr);
        }
        $this->viewBuilder()->setLayout('user');
    }
    
    public function downloadcanteenfund($strtdte, $enddte)
    {
        $canteenfee_table = TableRegistry::get('canteen_fund');
        $compid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session'); 
        $school_table = TableRegistry::get('company'); 
        $student_table = TableRegistry::get('student'); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $rowdata = '';   
        $ttlamt = [];
        $retrieve_canteenfee = $canteenfee_table->find()->where(['created_date >=' => $strtdte, 'created_date <=' => $enddte, 'canteen_fund.school_id '=> $compid])->order(['id' => 'ASC'])->toArray() ;
        foreach($retrieve_canteenfee as $key =>$fee_c)
		{
		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_c->class_id])->toArray();
			 $fee_c->c_name = $retrieve_class[0]->c_name;
			 $fee_c->c_section = $retrieve_class[0]->c_section;
			 $fee_c->school_section = $retrieve_class[0]->school_sections;
			 
			 $retrieve_stud = $student_table->find()->select(['id' ,'adm_no','f_name', 'l_name'])->where(['id' => $fee_c->student_id])->toArray() ;
			 $fee_c->student = $retrieve_stud[0]->l_name.'-'.$retrieve_stud[0]->f_name;
			 $fee_c->adm = $retrieve_stud[0]->adm_no;
			 
			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_c->session_id])->toArray() ;
			 $fee_c->start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
		}
        
        foreach($retrieve_canteenfee as $value){
            if(!empty($sclsub_details[0]))
            { 
                if(strtolower($value['sclsection']) == "creche" || strtolower($value['sclsection']) == "maternelle") {
                    $clsmsg = "kindergarten";
                }
                elseif(strtolower($value['sclsection']) == "primaire") {
                    $clsmsg = "primaire";
                }
                else
                {
                    $clsmsg = "secondaire";
                }
                $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                if(in_array($clsmsg, $subpriv)) { 
                    $show = 1;
                }
                else
                {
                    $show = 0;
                }
            } else { 
                $show = 1;
            }
            if($show == 1) { $ttlamt[] = $value['amount'];
            $rowdata .= '<tr>
                <td style="border:1px solid #ccc">
                    <span>'. $value['student'] .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'.$value['adm'].'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span class="font-weight-bold">'.$value['c_name']."-".$value['c_section']." (".$value['school_section'].")" .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'.$value['start_year'].'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'. "$".$value['amount'].'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'. "$".$value['daily_limit'].'</span>
                </td>
                
                <td style="border:1px solid #ccc">
                    <span>'. date("d-m-Y h:i A", $value['created_date']) .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'. ucfirst($value['deposit_by']) .'</span>
                </td>
            </tr>';
            }
        }
        $rowdata .= '<tr>
            <td colspan="4" style="border:1px solid #ccc">
                <b>Total</b>
            </td>
            <td style="border:1px solid #ccc">
                <b>'. "$".array_sum($ttlamt) .'</b>
            </td>
            <td style="border:1px solid #ccc"></td>
            <td style="border:1px solid #ccc"></td>
            <td style="border:1px solid #ccc"></td>
        </tr>';
       
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $compid])->toArray();
        $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    
	    $lang = $this->Cookie->read('language');
        if($lang != "") { 
            $lang = $lang; 
        } else { 
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
	    foreach($retrieve_langlabel as $langlbl) { 
            if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; }
            if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
            if($langlbl['id'] == '337') { $lbl337 = $langlbl['title'] ; }
            if($langlbl['id'] == '321') { $lbl321 = $langlbl['title'] ; }
            if($langlbl['id'] == '322') { $lbl322 = $langlbl['title'] ; }
            if($langlbl['id'] == '2237') { $lbl2237 = $langlbl['title'] ; }
            if($langlbl['id'] == '2238') { $lbl2238 = $langlbl['title'] ; }
            if($langlbl['id'] == '2249') { $lbl2249 = $langlbl['title'] ; }
        } 
       
	    $header = '<table style=" width: 100%;">
        		    <tbody>
        			    <tr>
        			        <td  style="width: 100%;">
        			            <table style="width: 100%;  ">
            			        <tr>
                    			    <td  style="width: 33%; float:left; ">
                    			        <table style="width: 100%;  ">
                    			            <tr>
                    						    <th style="width: 100%; text-align:center;"><span> '.$school_logo.' </span></th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($retrieve_school[0]['comp_name']).'</th>
                    						</tr>
                    					</table>
                    			    </td>
                    				<td style="width: 66%; float:left; text-align:center;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:left; font-size: 16px;">Canteen Fund Report: '.date("d/m/Y",$strtdte)." - ".date("d/m/Y",$enddte).'</th>
                    						</tr>
                    					</table>
                    				</td>
        			            </tr>
        			            </table>
        			        </td>
        			</tr>
        			<tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">'. $lbl147.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl130.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl337.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl321.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl322.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2237.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2238.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2249.'</th>
                                    </tr>
                                </thead>
                                <tbody > 
                                    '.$rowdata.'
                                </tbody>
        					</table>
            			</td>
        			</tr>
        			<tr>
            			<td style="width: 100%;">
            			'.$remark.'
            			</td>
            		</tr>
        		</tbody>
        		</table>';
	
	    $title =  "Canteenfundreport". date("d-m-Y",$strtdte)."to".date("d-m-Y",$enddte);
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;">  
	    <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);
        
    }
}

  


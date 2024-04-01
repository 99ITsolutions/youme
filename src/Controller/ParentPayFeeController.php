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
class ParentPayFeeController extends AppController
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
                $data = [];
                $student_table = TableRegistry::get('student');
                $sid =$this->request->session()->read('student_id');
                if(!empty($stid))
                {
                $retrieve_student_table = $student_table->find()->where(['md5(student.id)' => $stid ])->toArray() ;
        		$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $student_fee_table = TableRegistry::get('student_fee');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $stud_table = TableRegistry::get('student');
                
                $session_table = TableRegistry::get('session');
                $retrieve_session_table = $session_table->find()->toArray();
                
                $retrieve_session = $session_table->find()->where(['id' => $retrieve_student_table[0]->session_id ])->first();
                
                $retrieve_class = $class_table->find()->where(['id' => $retrieve_student_table[0]->class ])->first();
                $cls_sess = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections']. ") -". $retrieve_session['startyear']."-".$retrieve_session['endyear']  ;
                
                $fee_structure_table = TableRegistry::get('fee_structure');
                $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $retrieve_student_table[0]->class, 'school_id' => $retrieve_student_table[0]->school_id, 'start_year' => $retrieve_session_table[0]->id])->toArray();
                
                if(!empty($retrieve_feestructure_table)){
                    $frequency = $retrieve_feestructure_table[0]->frequency;
                    $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                    
                    if($frequency == 'yearly'){
                        $freq_amount = $retrieve_feestructure_table[0]->amount;
                        $freq_data[0] = $srtMonth.'-'.$endMonth;
                    }
                    
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
                    
                    
                   
                    $i =0;
                     
                    foreach($freq_data as $ddata){
                        $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $retrieve_student_table[0]->school_id,'md5(student_id)'=>$stid, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                       
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
                        }else{
                            $use_t = $current_e; 
                        }
                        
                        if(strtotime($use_t) < $current){
                            $due_amt = $freq_amount - $amount; 
                        }else{
                            $due_amt = '';
                        }
                        $data[$i]['installment'] = $ddata;
                        $data[$i]['amount'] = $freq_amount;
                        $data[$i]['paid'] = $amount;
                        $data[$i]['pending'] = $due_amt;
                        $i++;
                        
                    }
                    
        		}
        	
        		//print_r($data); die;
        		$studentid = $retrieve_student_table[0]['id'];
                $this->set("session_details", $retrieve_session_table); 
                $this->set("student_fee", $data); 
                $this->set("freq", $freq_data);
                $this->set("amt", $freq_amount);
                $this->set("student", $studentid);
                $this->set("studentdtl", $retrieve_student_table);
                $this->set("cls_sess", $cls_sess);
                
                $this->viewBuilder()->setLayout('usersa');
                }
                else
                {
                    return $this->redirect('/login/') ;    
                }
            }
            
            public function getstudentsData()
            {   
            if($this->request->is('post'))
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());   
                $start_year = $this->request->data['start_year'];
                if(!empty($this->Cookie->read('stid')))
                {
                    $student = $this->Cookie->read('stid'); 
                }
                elseif(!empty($this->Cookie->read('pid')))
                {
                    $student = $this->Cookie->read('pid'); 
                }
                    
                $student_fee_table = TableRegistry::get('student_fee');
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');
                $stud_table = TableRegistry::get('student');
                
                //$retrieve_classid = $stud_table->find()->select(['class'])->where(['md5(id)' => $student, 'session_id' => $start_year])->toArray();
                $retrieve_classid = $stud_table->find()->select(['class'])->where(['md5(id)' => $student])->toArray();
                $id = $retrieve_classid[0]['class'];
                
                $fee_structure_table = TableRegistry::get('fee_structure');
                $retrieve_feestructure_table = $fee_structure_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'start_year' => $start_year])->toArray();
                //print_r($retrieve_feestructure_table);
                
                $session_table = TableRegistry::get('session');
                $retrieve_session_table = $session_table->find()->where(['id' => $start_year])->toArray();
                $yr = $retrieve_session_table[0]->startyear."-".$retrieve_session_table[0]->endyear;
                if(!empty($retrieve_feestructure_table)){
                    $frequency = $retrieve_feestructure_table[0]->frequency;
                    $srtMonth = ucfirst($retrieve_session_table[0]->startmonth); $endMonth = ucfirst($retrieve_session_table[0]->endmonth);
                    
                    if($frequency == 'yearly'){
                        $freq_data[0] = $srtMonth.'-'.$endMonth;
                    }
                    
                    if($frequency == 'half yearly'){
                        $effectiveDate = date('F', strtotime("+5 months", strtotime($srtMonth)));
                        $effectiveDate2 = date('F', strtotime("+1 months", strtotime($effectiveDate)));
                        $freq_data[0]= $srtMonth.'-'.$effectiveDate;
                        $freq_data[1]= $effectiveDate2.'-'.$endMonth;
                    }
                    
                    if($frequency == 'quarterly'){
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
                        $freq_data[0]= $srtMonth;
                        for($i=1; $i <= 11; $i++){
                            $month = date('F', strtotime("+1 months", strtotime($srtMonth)));
                            $srtMonth = $month;
                            $freq_data[$i]= $month;
                        }
                        //$freq_data[11]= $endMonth;
                    }
                    
                     $data = '';
                     //$grph = '';
                    foreach($freq_data as $ddata){
                        $retrieve_studfee_table = $student_fee_table->find()->where([ 'school_id '=> $compid,'md5(student_id)'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                     
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
                            $due_amt = $retrieve_feestructure_table[0]->amount - $amount; 
                        }else{
                            $due_amt = '';
                        }
                        
                        $dueAmt = $retrieve_feestructure_table[0]->amount - $amount; 
                        $data .=    '<tr>
                                            <td>
                                                <span class="mb-0 font-weight-bold">'.$ddata.'</span>
                                            </td>
                                            <td>'.$retrieve_feestructure_table[0]->amount.'</td>
                                            <td>'.$amount.'</td>
                                            <td>'.$due_amt.'</td>
                                        </tr>';
                                        
                        $amt[] = $amount;
                        $due[] = $dueAmt;
                        
                        
                        //$grph[] = ['label' => $ddata, 'symbol' => $ddata, 'y' => $due_amt];
                        
                      
                    }
                    $paidamt = array_sum($amt);
                    $dueamt = array_sum($due);
                    $grph[] = ['label' => "Paid Amount", 'symbol' => "Paid", 'y' => $paidamt ];
                    $grph[] = ['label' => "Due Amount", 'symbol' => "Due", 'y' => $dueamt ];
                   // $graph[] = $grph;
                    
                    $frequen = '';
                    $frequen .= '<option value="">Choose Frequency</option>';
                    foreach($freq_data as $freq)
                    {
                        $frequen .= '<option value="'.$freq.'">'.$freq.'</option>';
                    }
                    $datass = ['html' => $data, 'graph' => $grph, 'sessionyear' => $yr, 'frequency' => $frequen, 'amount' => $amount];
        		}
                return $this->json($datass);
            }  
        }
        
            public function pdf($student = null, $ddata=null)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                //$compid = $this->request->session()->read('company_id');
                $student_fee_table = TableRegistry::get('student_fee');
                
                $student_table = TableRegistry::get('student');
                $class_table = TableRegistry::get('class');
                $retrieve_student_table = $student_table->find()->select(['student.adm_no', 'student.school_id'  , 'student.f_name'  , 'student.l_name'  , 'student.id'  , 'student.class'  , 'class.c_name'  , 'class.id'  , 'class.c_section'  ,'student.session_id','session.startyear','session.endyear','session.startmonth','session.endmonth' ])->join([
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
                
               $compid = $retrieve_student_table->school_id;
                $retrieve_studfee_table = $student_fee_table->find()->where([ 'student_id'=>$student, 'frequency' => $ddata])->order(['id' => 'DESC'])->toArray();
                //print_r($retrieve_studfee_table); die;
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
                $title= $retrieve_student_table->adm_no."_".$retrieve_studfee_table[0]->id;
                $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p></div>';
                //print_r($viewpdf); die;
                $dompdf = new Dompdf();
                $dompdf->loadHtml($viewpdf);    
                
                $dompdf->setPaper('A4', 'Portrait');
                $dompdf->render();
                $dompdf->stream($title.".pdf");

                exit(0);
            }
            
              public function addstudentonlinfees(){   
                if ($this->request->is('ajax') && $this->request->is('post') )
                { 
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_fee_table = TableRegistry::get('student_fee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    if(!empty($compid))
                    {
                        
                        $totalamt = $this->request->data('totalamt');
                        $amt = $this->request->data('amount');
                        $fee = $student_fee_table->newEntity();
                        
                        $fee->class_id = $this->request->session()->read('class_id');
                        $fee->student_id = $this->request->session()->read('student_id');
                        $fee->frequency = $this->request->data('frequency');
                        $fee->amount = $this->request->data('amount');
                        $fee->start_year = $this->request->session()->read('session_id');
                        $fee->payment_mode = "online";
                        $fee->school_id = $this->request->session()->read('company_id');
                        $fee->submission_date = time();
                        $fee->created_date = time();
                       
                        $stude = $this->request->session()->read('student_id');
                        $sesion = $this->request->session()->read('session_id');
                        $cls = $this->request->session()->read('class_id');
                        
                        $amount = $this->request->data('amount');
                        $submittedamt = 0;
                        $stufee = $student_fee_table->find()->select(['amount'])->where(['class_id' => $cls, 'start_year' => $sesion, 'frequency' => $this->request->data('frequency'), 'student_id'=> $stude ])->toArray();
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
                                return $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr');
                                /*if($saved = $student_fee_table->save($fee) )
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
                                }*/
                            }
                            else
                            {
                                $res = [ 'result' => 'You can\'t pay more than alloted amount. Already paid amount $'.$submittedamt  ];
                            }
                        }
                        else
                        {
                            $res = [ 'result' => 'Amount should not be in negative.'  ];
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
            
        public function paymoney()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $action = '';
            $posted = array();
            $_POST['udf1'] = "udf1";
            $_POST['udf2'] = "udf2";
            $_POST['udf3'] = "udf3";
            $_POST['udf4'] = "udf4";
            $_POST['udf5'] = "udf5";
           
            if(!empty($_POST)) {
                foreach($_POST as $key => $value) {
                    $posted[$key] = $value;
                }
            }
           
            $formError = 0;
            if(empty($posted['txnid'])) {
            // Generate random transaction id
                $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            } else {
                $txnid = $posted['txnid'];
            }
            $hash = '';
            $posted['productinfo'] = "onlinepay fee";
            
            $MERCHANT_KEY = "HxRFGQ3S";
            // Merchant Salt as provided by Payu
            $SALT = "Svasp6HzCU";
            // Change to https://secure.payu.in for LIVE mode
            $PAYU_BASE_URL = "https://test.payu.in";
            // Hash Sequence
            //$hash=hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstName.'|'.$email.'|'.$udf1.'|'.$udf2.'|'.$udf3.'|'.$udf4.'|'.$udf5.'||||||'.$salt);
            $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
            if(empty($posted['hash']) && sizeof($posted) > 0) 
            {
                if(empty($posted['key']) || empty($posted['txnid'])|| empty($posted['amount'])|| empty($posted['firstname'])|| empty($posted['email'])|| empty($posted['phone'])|| empty($posted['productinfo'])|| empty($posted['surl'])|| empty($posted['furl'])|| empty($posted['service_provider'])) {
                    $formError = 1;
                } else {
                    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
                    $hashVarsSeq = explode('|', $hashSequence);
                    $hash_string = '';
                    foreach($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                    $hash_string .= '|';
                    }
                    $hash_string .= $SALT;
                    
                    $hash = strtolower(hash('sha512', $hash_string));
                  
                    $action = $PAYU_BASE_URL . '/_payment';
                }
            } 
            elseif(!empty($posted['hash'])) 
            {
               
                $hash = $posted['hash'];
                $action = $PAYU_BASE_URL . '/_payment';
            }
            
            return $this->redirect($action) ;  
        }
        
        public function request()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $hash=hash('sha512', $_POST['key'].'|'.$_POST['txnid'].'|'.$_POST['amount'].'|'.$_POST['pinfo'].'|'.$_POST['fname'].'|'.$_POST['email'].'|||||'.$_POST['udf5'].'||||||'.$_POST['salt']);
            $json=array();
            //echo $hash;
            $json['success'] = $hash;
            //echo json_encode($json);
           
            return $this->json($json);
            
        }
}

  


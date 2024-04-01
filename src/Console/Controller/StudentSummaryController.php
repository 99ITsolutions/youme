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
class StudentSummaryController   extends AppController
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
                $sclid = $this->Cookie->read('id'); 
                if($sclid == "")
                {
                    $compid = $this->request->session()->read('company_id');
                    $sclid = md5($compid);
                }
                $school_table = TableRegistry::get('company');
                $class_table = TableRegistry::get('class');
                $session_table = TableRegistry::get('session');
                if(!empty($sclid))
                {
                    $retrieve_classes = $class_table->find()->where(['md5(school_id)' => $sclid, 'active' => 1])->toArray();
                
                    if(!empty($_POST))
                    {
                        $searchid = $this->request->data('searchform');
                        
                        $subject_table = TableRegistry::get('subjects');
                        $school_table = TableRegistry::get('company');
                        $class_table = TableRegistry::get('class');
                        $student_table = TableRegistry::get('student');
                        $session_table = TableRegistry::get('session');
                        $feestruct_table = TableRegistry::get('fee_structure');
                        $studentfee_table = TableRegistry::get('student_fee');
                        $studentfee_table = TableRegistry::get('student_fee');
                        $sclattendance_table = TableRegistry::get('attendance_school');
                        $submitexm_table = TableRegistry::get('submit_exams');
                        $examsass_table = TableRegistry::get('exams_assessments');
                    
                        $retrieve_school = $school_table->find()->where([ 'md5(id)' => $sclid])->toArray();
                        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important;">';
                        $working_days = $retrieve_school[0]['present_days'];
                        if($searchid  == 1)
                        {
                            $student_no = $this->request->data('student_no');
                            $sessionid = $this->request->data('start_year');
                            $student_exist = $student_table->find()->where(['adm_no' => $student_no, 'md5(school_id)' => $sclid, 'session_id' => $sessionid, 'status' => 1])->count();
                            $retrieve_student = $student_table->find()->where(['adm_no' => $student_no, 'md5(school_id)' => $sclid, 'session_id' => $sessionid, 'status' => 1])->first();
                            $studentid = $retrieve_student['id'];
                            $classid = $retrieve_student['class'];
                            
                        }
                        else
                        {
                            $studentid = $this->request->data('student');
                            $classid = $this->request->data('class');
                            $sessionid = $this->request->data('start_year');
                            $student_exist = 1;
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
                        if($langlbl['id'] == '49') { $contctlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '136') { $claslabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '238') { $sesslabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1955') { $feesrep = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1956') { $attndreport = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1958') { $workinglabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1959') { $presentlabel = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1960') { $absentlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1957') { $instalmntlbl = $langlbl['title'] ; } 
                        if($langlbl['id'] == '345') { $amtlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '359') { $paidlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1967') { $duelabel = $langlbl['title'] ; } 
                        
                        if($langlbl['id'] == '1961') { $gradereportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1962') { $examreportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '388') { $titlelabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1963') { $assignreportlabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1964') { $maxmrklabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1965') { $obtmarklabel = $langlbl['title'] ; } 
                        if($langlbl['id'] == '1966') { $gradelabel = $langlbl['title'] ; } 
                    } 
                    
                        if($student_exist == 1)
                        {
                        
                            $retrieve_student = $student_table->find()->where(['id' => $studentid, 'status' => 1])->first();
                            $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                            $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                            
                            $admno = $retrieve_student['adm_no'];
                            $studentname = $retrieve_student['f_name']." ".$retrieve_student['l_name']. " (".$retrieve_student['adm_no'].") ";
                            $emergency_contact = $retrieve_student['emergency_number'];
                            $image = '<img src="img/'. $retrieve_student['pic'].'" style="width:60px !important;">';
                            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']."(".$retrieve_class['school_sections'].")";
                            $session_year = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                            
                            
                            /**************Grades************/
                            
                            $retrieve_submitexm = $submitexm_table->find()->where(['student_id' => $studentid,  'md5(school_id)' => $sclid])->toArray();
        
                            $examreport = "";
                            $assreport = "";
                            foreach($retrieve_submitexm as $submitexm)
                            {
                                
                                 
                                $examid = $submitexm['exam_id'];
                                $retrieve_examass = $examsass_table->find()->where(['id' => $examid])->first();
                                $retrieve_subjectname = $subject_table->find()->where(['id' => $retrieve_examass['subject_id']])->first();
                                //print_r($retrieve_examass);
                                if($retrieve_examass['type'] == "Exams")
                                {
                                    $title = $retrieve_subjectname['subject_name']. " ". $retrieve_examass['type']. " (". $retrieve_examass['exam_type'].")";
                                    $max_marks = $retrieve_examass['max_marks'];
                                    $get_marks = $submitexm['marks'];
                                    $grade = $submitexm['grade'];
                                    
                                    $examreport .= '<tr>
                                        <td style="width: 40%;">'.$title.'</td>
                                        <td style="width: 20%; text-align:center;">'.$max_marks.'</td>
                                        <td style="width: 20%; text-align:center;">'.$get_marks.'</td>
                                        <td style="width: 20%; text-align:center;">'.$grade.'</td>
                                    </tr>';
                                }
                                elseif($retrieve_examass['type'] == "Assessment")
                                {
                                    $title = $retrieve_subjectname['subject_name']. " ". $retrieve_examass['type'];
                                    $max_marks = $retrieve_examass['max_marks'];
                                    $get_marks = $submitexm['marks'];
                                    $grade = $submitexm['grade'];
                                    
                                    $assreport .= '<tr>
                                        <td style="width: 40%;">'.$title.'</td>
                                        <td style="width: 20%; text-align:center;">'.$max_marks.'</td>
                                        <td style="width: 20%; text-align:center;">'.$get_marks.'</td>
                                        <td style="width: 20%; text-align:center;">'.$grade.'</td>
                                    </tr>';
                                }
                            }
                        
                           // die;
                            /**************Fees**************/
                            
                            $retrieve_totalfee = $feestruct_table->find()->where(['class_id' => $classid, 'start_year' => $sessionid, 'md5(school_id)' => $sclid,  'active' => 1])->first();
                            $retrieve_feepaid = $studentfee_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'start_year' => $sessionid, 'md5(school_id)' => $sclid])->toArray();
                            
                            $fees = array();
                            foreach($retrieve_feepaid as $stu_fee)
                            {
                                $fees[] = $stu_fee['amount'];
                            }
                            $months_fee = ucfirst($retrieve_session['startmonth'])."-".ucfirst($retrieve_session['endmonth']);
                            $total_fees = $retrieve_totalfee['amount'];
                            $fee_paid = array_sum($fees);
                            $due_fees = $total_fees-$fee_paid;
                            
                            /***********Attendance**************/
                            $frmdate = date('m', strtotime($retrieve_session['startmonth']));
                            $tdate = date('m', strtotime($retrieve_session['endmonth']));
                            $month = date('m', strtotime($retrieve_session['endmonth']));
                            $fromdate = $retrieve_session['startyear']."-".$frmdate."-01";
                            if($month == "01" || $month == "03"  || $month == "05" || $month == "07" || $month == "08" || $month == "10"  || $month == "12")
                            {
                                $todate = $retrieve_session['endyear']."-".$tdate."-31";
                            }
                            if($month == "04" || $month == "06"  || $month == "09" || $month == "11")
                            {
                                $todate = $retrieve_session['endyear']."-".$tdate."-30";
                            }
                            if($month == "02")
                            {
                                $todate = $retrieve_session['endyear']."-".$tdate."-28";
                            }
                            
                            
                            $present_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Present' ])->count();
                            $absent_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Absent' ])->count();
                            $leave_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Leave' ])->count();
                            
                            
                            $feesreport = '
                                
                                <tr>
                                    <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$feesrep.'</th>
                                </tr>
                                <tr>
                                    <td style="padding:0px !important;">
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <td>
                                                    <table style="width: 100%; border:1px solid #ccc;">
                                                        <tr>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$instalmntlbl.'</th>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$amtlabel.'</th>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$paidlabel.'</th>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$duelabel.'</th>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:center;" >'.$months_fee.'</td>
                                                            <td style="text-align:center;">$'.$total_fees.'</td>
                                                            <td style="text-align:center;">$'.$fee_paid.'</td>
                                                            <td style="text-align:center;">$'.$due_fees.'</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p id="feesreport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                                
                            $attendancereport = '
                                <tr>
                                    <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$attndreport.'</th>
                                </tr>
                                <tr>
                                    <td style="padding:0px !important;">
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <td>
                                                    <table style="width: 100%; border:1px solid #ccc;">
                                                        <tr>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$workinglabel.'</th>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$presentlabel.'</th>
                                                            <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">'.$absentlabel.'</th>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:center;">'.$working_days.'</td>
                                                            <td style="text-align:center;" >'.$present_attendance.' </td>
                                                            <td style="text-align:center;">'.$absent_attendance.'</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p id="attendancereport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                                
                            $gradereport = '
                                <tr>
                                    <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradereportlabel.'</th>
                                </tr>
                                <tr>
                                    <td style="padding:0px !important;">
                                        <table style="width: 100%; border:1px solid #ccc;">
                                            <tr>
                                                <th style="width: 50%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$examreportlabel.'</th>
                                                <th style="width: 50%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$assignreportlabel.'</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <table style="width: 100%; border:1px solid #ccc;">
                                                        <tr>
                                                            <th style="width: 40%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$titlelabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$maxmrklabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$obtmarklabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradelabel.'</th>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 50%;">
                                                    <table style="width: 100%; border:1px solid #ccc;">
                                                        <tr>
                                                            <th style="width: 40%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$titlelabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$maxmrklabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$obtmarklabel.'</th>
                                                            <th style="width: 20%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">'.$gradelabel.'</th>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <table style="width: 100%; border:1px solid #ccc;">'
                                                        .$examreport.
                                                    '</table>
                                                </td>
                                                <td style="width: 50%;">
                                                    <table style="width: 100%; border:1px solid #ccc;">'
                                                        .$assreport.
                                                    '</table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                            
                            $header = '<style> td p { margin-bottom:5px; } </style><table style=" width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td  style="width: 100%;">
                                                <table style="width: 100%;  ">
                                                <tr>
                                                    <td  style="width: 100%; float:left; ">
                                                        <table style="width: 100%;  ">
                                                            <tr>
                                                                <td>
                                                                    <table style="width: 100%;">
                                                                    <tr>
                                                                    <td  style="width: 35%; text-align:left; padding: 5px;"><span> '.$school_logo.' </span></td>
                                                                    <td  style="width: 50%; text-align:left; padding: 5px;">
                                                                        <span style="width: 17%; float:left;"> '.$image.' </span>
                                                                        <span style="width: 73%;"> 
                                                                        <p style=" font-size: 17px; font-weight:bold; margin:10px 0px 0px 0px !important;">'.ucwords($studentname).'</p>
                                                                        <p> <b>'.$contctlabel.': </b>'.$emergency_contact.' </p>
                                                                        </span>
                                                                    </td>
                                                                    <td  style="width: 25%; text-align:left; padding: 5px;">
                                                                        <p style="  margin:0px !important;"> <b> '.$claslabel.': </b>'.$classname.' </p>
                                                                        <p> <b> '.$sesslabel.': </b>'.$session_year.' </p>
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
                                        <tr>
                                            <td style="width:100%">
                                                <table style="width: 100%;">
                                                    <tr>
                                                        <td style="width:50%">
                                                            <table style="width: 100%;">'
                                                            .$feesreport.
                                                            '</table>
                                                        </td>
                                                        <td style="width:50%">
                                                            <table style="width: 100%;">'
                                                            .$attendancereport.
                                                            '</table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        <tr>'
                                        .$gradereport.
                                    '</tbody>
                                </table>';
                                
                                //print_r($header); die;
                        
                            $viewpage = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc">'.$header.'</div>';
                            $style = 'style="display:none;"';
                            $searchicon = 'style="display:block;"';
                            $dnloadreport = 'style="display:block;"';
                            $closeicon = 'style="display:none;"';
                            $error = "";
                        }
                        else
                        {
                            $error = "Student with this Student Number Doesn't exist";
                            $viewpage = '';
                            $style = '';
                            $fee_paid = '';
                            $due_fees = '';
                            $present_attendance = '';
                            $absent_attendance = '';
                            $searchicon = 'style="display:none;"';
                            $closeicon = 'style="display:none;"';
                            $dnloadreport = 'style="display:none;"';
                        }
                    }
                    else
                    {
                        $viewpage = '';
                        $style = '';
                        $fee_paid = '';
                        $due_fees = '';
                        $present_attendance = '';
                        $absent_attendance = '';
                        $searchicon = 'style="display:none;"';
                        $closeicon = 'style="display:none;"';
                        $dnloadreport = 'style="display:none;"';
                        $error = "";
                        
                    }
                    $this->set("error", $error); 
                    $this->set("downloadreport", $dnloadreport); 
                    $this->set("present", $present_attendance); 
                    $this->set("absent", $absent_attendance); 
                    $this->set("paid", $fee_paid); 
                    $this->set("due", $due_fees); 
                    $this->set("style", $style); 
                    $this->set("searchicon", $searchicon); 
                    $this->set("closeicon", $closeicon); 
                    $this->set("viewpage", $viewpage); 
                    $this->set("class_details", $retrieve_classes); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;
                }
            }
            
            public function clssummaryreport()
            {
                if($this->request->is('post'))
                {
                    $sclid = $this->Cookie->read('id');
                    if($sclid == "")
                    {
                        $compid = $this->request->session()->read('company_id');
                        $sclid = md5($compid);
                    }
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $school_table = TableRegistry::get('company');
                    $class_table = TableRegistry::get('class');
                    $student_table = TableRegistry::get('student');
                    $session_table = TableRegistry::get('session');
                    $feestruct_table = TableRegistry::get('fee_structure');
                    $studentfee_table = TableRegistry::get('student_fee');
                    $studentfee_table = TableRegistry::get('student_fee');
                    $sclattendance_table = TableRegistry::get('attendance_school');
                    $school_table = TableRegistry::get('company');
                    
                    if(!empty($sclid))
                    {
                
                        $retrieve_school = $school_table->find()->where([ 'md5(id)' => $sclid])->toArray();
                        $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:100px !important;">';
                        
                        $studentid = $this->request->data('student');
                        $classid = $this->request->data('class');
                        $sessionid = $this->request->data('start_year');
                        
                        $retrieve_student = $student_table->find()->where(['id' => $studentid, 'status' => 1])->first();
                        $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                        $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                        
                        $admno = $retrieve_student['adm_no'];
                        $studentname = $retrieve_student['f_name']." ".$retrieve_student['l_name']. " (".$retrieve_student['adm_no'].") ";
                        $emergency_contact = $retrieve_student['emergency_number'];
                        $image = '<img src="img/'. $retrieve_student['pic'].'" style="width:100px !important;">';
                        $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                        $session_year = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                        
                        /**************Fees**************/
                        
                        $retrieve_totalfee = $feestruct_table->find()->where(['class_id' => $classid, 'start_year' => $sessionid, 'md5(school_id)' => $sclid,  'active' => 1])->first();
                        $retrieve_feepaid = $studentfee_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'start_year' => $sessionid, 'md5(school_id)' => $sclid])->toArray();
                        
                        $fees = array();
                        foreach($retrieve_feepaid as $stu_fee)
                        {
                            $fees[] = $stu_fee['amount'];
                        }
                        $months_fee = ucfirst($retrieve_session['startmonth'])."-".ucfirst($retrieve_session['endmonth']);
                        $total_fees = $retrieve_totalfee['amount'];
                        $fee_paid = array_sum($fees);
                        $due_fees = $total_fees-$fee_paid;
                        
                        /***********Attendance**************/
                        $frmdate = date('m', strtotime($retrieve_session['startmonth']));
                        $tdate = date('m', strtotime($retrieve_session['endmonth']));
                        $month = date('m', strtotime($retrieve_session['endmonth']));
                        $fromdate = $retrieve_session['startyear']."-".$frmdate."-01";
                        if($month == "01" || $month == "03"  || $month == "05" || $month == "07" || $month == "08" || $month == "10"  || $month == "12")
                        {
                            $todate = $retrieve_session['endyear']."-".$tdate."-31";
                        }
                        if($month == "04" || $month == "06"  || $month == "09" || $month == "11")
                        {
                            $todate = $retrieve_session['endyear']."-".$tdate."-30";
                        }
                        if($month == "02")
                        {
                            $todate = $retrieve_session['endyear']."-".$tdate."-28";
                        }
                        
                        
                        $present_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Present' ])->count();
                        $absent_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Absent' ])->count();
                        $leave_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'md5(school_id)' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Leave' ])->count();
                    
                        $feesreport = '
                            <tr>
                                <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">Fees Report</th>
                            </tr>
                            <tr>
                                <td style="padding:0px !important;">
                                    <table style="width: 100%; border:1px solid #ccc;">
                                        <tr>
                                            <td style="width: 50%;">
                                                <table style="width: 100%; border:1px solid #ccc;">
                                                    <tr>
                                                        <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">Installment</th>
                                                        <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">Amount</th>
                                                        <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">Paid</th>
                                                        <th style="text-align:center; background:#f2f2f2; padding: 5px 0;">Due</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;" >'.$months_fee.'</td>
                                                        <td style="text-align:center;">'.$total_fees.'</td>
                                                        <td style="text-align:center;">'.$fee_paid.'</td>
                                                        <td style="text-align:center;">'.$due_fees.'</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="width: 50%;">
                                                <p id="feesreport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
                            
                        $attendancereport = '
                            <tr>
                                <th style="width: 100%; border:1px solid #ccc; text-align:center; background:#f2f2f2; padding: 5px 0;">Attendance Report</th>
                            </tr>
                            <tr>
                                <td style="padding:0px !important;">
                                    <table style="width: 100%; border:1px solid #ccc;">
                                        <tr>
                                            <td style="width: 50%;">
                                                <table style="width: 100%; border:1px solid #ccc;">
                                                    <tr>
                                                        <td style="text-align:left;" ><b> Total Present:  </b>'.$present_attendance.' Days</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;"><b> Total Absent:  </b>'.$absent_attendance.' Days</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;"><b> Total Leave:  </b>'.$leave_attendance.' Days</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="width: 50%;">
                                                <p id="attendancereport" style="height: 370px; width: 100%; margin-top:50px;"></p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
                        
                        $header = '<style> td p { margin-bottom:5px; } </style><table style=" width: 100%;">
                                <tbody>
                                    <tr>
                                        <td  style="width: 100%;">
                                            <table style="width: 100%;  ">
                                            <tr>
                                                <td  style="width: 100%; float:left; ">
                                                    <table style="width: 100%;  ">
                                                        <tr>
                                                            <td>
                                                                <table style="width: 100%;">
                                                                <tr>
                                                                <td  style="width: 25%; text-align:left; padding: 5px;"><span> '.$image.' </span></td>
                                                                <td  style="width: 50%; text-align:left; padding: 5px;">
                                                                    <p style=" font-size: 22px; font-weight:bold; margin:0px !important;">'.ucwords($studentname).'</p>
                                                                    <p> <b> Contact No: </b>'.$emergency_contact.' </p>
                                                                </td>
                                                                <td  style="width: 25%; text-align:left; padding: 5px;">
                                                                    <p> <b> Class: </b>'.$classname.' </p>
                                                                    <p> <b> Session: </b>'.$session_year.' </p>
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
                                    </tr>'
                                    .$feesreport.$attendancereport.
                                '</tbody>
                            </table>';
                            
                            //print_r($header); die;
                    
                        $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ccc"> <p>'.$header.'</p></div>';
                        $title = "Summary_report_".$studentname."_".$admno."_".$session_year;
                        $dompdf = new Dompdf();
                        $dompdf->loadHtml($viewpdf);    
                        
                        $dompdf->setPaper('A4', 'Portrait');
                        $dompdf->render();
                        $dompdf->stream($title.".pdf", array("Attachment" => false));
        
                        exit(0);
                    }
                    else
                    {
                         return $this->redirect('/login/') ;
                    }
                }
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
                        $retrieve_student = $student_table->find()->where(['class' => $id, 'school_id' => $compid, 'session_id' => $start_year])->toArray(); 
                        $all_data = array(); $all_data[0]= $retrieve_student;  
                        
                        return $this->json($all_data);
                    }
                    else
                    {
                         return $this->redirect('/login/') ;
                    }
                }  
            }

            
}

  


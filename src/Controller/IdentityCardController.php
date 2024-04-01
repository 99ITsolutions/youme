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
class IdentityCardController   extends AppController
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
                //print_r($lang); die;
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sclid = $this->request->session()->read('company_id');; 
                if(!empty($sclid)) {
                $school_table = TableRegistry::get('company');
                $class_table = TableRegistry::get('class');
                $session_table = TableRegistry::get('session');
                $retrieve_classes = $class_table->find()->where(['school_id' => $sclid, 'active' => 1])->toArray();
                
                if($this->Cookie->read('logtype') == md5('School Subadmin'))
        		{
        		    $sclsub_table = TableRegistry::get('school_subadmin');
        			$sclsub_id = $this->Cookie->read('subid');
        			$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $sclid ])->first() ; 
        			$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
        			$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
        		}
                
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
                
                    $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
                    $school_logo = '<img src="img/'. $retrieve_school[0]['comp_logo'].'" style="width:85px !important;">';
                    $working_days = $retrieve_school[0]['present_days'];
                    $school_name =  $retrieve_school[0]['comp_name'].", ".$retrieve_school[0]['city'];
                    if($searchid  == 1)
                    {
                        $student_no = $this->request->data('student_no');
                        $start_year = $this->request->data('start_year');
                        
                        $student_exist = $student_table->find()->where(['adm_no' => $student_no, 'school_id' => $sclid, 'session_id' => $start_year, 'status' => 1])->count();
                        $retrieve_student = $student_table->find()->where(['adm_no' => $student_no, 'school_id' => $sclid, 'session_id' => $start_year, 'status' => 1])->first();
                        $studentid = $retrieve_student['id'];
                        $classid = $retrieve_student['class'];
                        $sessionid = $this->request->data('start_year');
                    }
                    else
                    {
                        $studentid = $this->request->data('student');
                        $classid = $this->request->data('class');
                        $sessionid = $this->request->data('start_year');
                        $student_exist = 1;
                    }
                    
                    $cls = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                    if($this->Cookie->read('logtype') == md5('School Subadmin'))
        			{
        			    //print_r($cls);
                        if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                            $clsmsg = "kindergarten";
                        }
                        elseif(strtolower($cls['school_sections']) == "primaire") {
                            $clsmsg = "primaire";
                        }
                        else
                        {
                            $clsmsg = "secondaire";
                        }
                        
                        if(in_array($clsmsg, $subpriv)) { 
                            $show = 1;
                        }
                        else
                        {
                            $show = 0;
                        }
        			}
        			else
        			{
        			     $show = 1;
        			}
        		    if($show == 1) {
                    
                        if($student_exist == 1)
                        {
                        
                            $retrieve_student = $student_table->find()->where(['id' => $studentid, 'status' => 1])->first();
                            $retrieve_class = $class_table->find()->where(['id' => $classid, 'active' => 1])->first();
                            $retrieve_session = $session_table->find()->where(['id' => $sessionid])->first();
                            
                            $admno = $retrieve_student['adm_no'];
                            $studentname = $retrieve_student['f_name']." ".$retrieve_student['l_name'];
                            $emergency_contact = $retrieve_student['emergency_number'];
                            if(!empty($retrieve_student['pic']))
                            {
                                $stupic = $retrieve_student['pic'];
                            }
                            else
                            {
                                $stupic = "male.jpg";
                            }
                            $image = '<img src="img/'. $stupic.'" style="width:90px !important; height:80px !important;">';
                            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
                            $session_year = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
                            if(!empty($retrieve_student['resi_add2']))
                            {
                                $add2 = $retrieve_student['resi_add2'].", ";
                            }
                            else
                            {
                                $add2 = "";
                            }
                            $address = $retrieve_student['resi_add1'].", ".$add2. $retrieve_student['city'];
                            foreach($retrieve_langlabel as $langlbl) { 
                                if($langlbl['id'] == '130') { $studno = $langlbl['title'] ; } 
                                if($langlbl['id'] == '150') { $grade = $langlbl['title'] ; } 
                                if($langlbl['id'] == '133') { $father = $langlbl['title'] ; }
                                if($langlbl['id'] == '134') { $mother = $langlbl['title'] ; } 
                                if($langlbl['id'] == '135') { $mobile = $langlbl['title'] ; } 
                                if($langlbl['id'] == '145') { $dob = $langlbl['title'] ; } 
                                if($langlbl['id'] == '151') { $blood = $langlbl['title'] ; } 
                                if($langlbl['id'] == '301') { $addr = $langlbl['title'] ; } 
                                if($langlbl['id'] == '2102') { $princ = $langlbl['title'] ; } 
                            }
                            
                            $header = '<style> td p { margin-bottom:5px; } </style><table style=" width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td  style="width: 100%;">
                                                <table style="width: 100%;  ">
                                                <tr>
                                                    <td  style="width: 100%; float:left; ">
                                                        <table style="width: 100%;  ">
                                                            <tr style="border-bottom:1px solid #000">
                                                                <td>
                                                                    <table style="width: 100%;">
                                                                    <tr>
                                                                    <td  style="width: 35%; text-align:left; padding: 0px 5px;"><span> '.$school_logo.' </span></td>
                                                                    <td  style="width: 65%; text-align:left; padding: 0px 5px;">
                                                                        <span style="font-size: 16px; font-weight:bold; font-family:unset;">'.ucwords($school_name).'</span>
                                                                    </td>
                                                                    </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td>
                                                                    <table style="width: 100%;  margin-top: 5px;">
                                                                    <tr>
                                                                    <td  style="width: 33%;"></td>
                                                                    <td  style="width: 33%; text-align:center; border:1px solid #ccc;"><span> '.$image.' </span></td>
                                                                    <td  style="width: 33%;"></td>
                                                                    </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td>
                                                                    <table style="width: 100%; ">
                                                                    <tr>
                                                                    <td  style="text-align:center;font-weight:bold;background: #ffa818;font-family: sans-serif;"><span> '.ucwords($studentname).' </span></td>
                                                                    </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td><table style="width: 100%;" id="column_td">
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left">'. $studno.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($admno).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$grade.' </td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($classname).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$father.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span>Mr. '.ucwords($retrieve_student['s_f_name']).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$mother.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span>Ms. '.ucwords($retrieve_student['s_m_name']).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$mobile.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($emergency_contact).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left">'.$addr.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucfirst($address).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$dob.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.date("d-m-Y", strtotime($retrieve_student['dob'])).' </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 5%;"></td>
                                                                        <td style="width: 40%; text-align:left"> '.$blood.'</td>
                                                                        <td style="width: 5%; text-align:left">: </td>
                                                                        <td style="text-align:left; width: 45%; font-family: sans-serif;"><span> '.ucwords($retrieve_student['bloodgroup']).' </span></td>
                                                                    </tr>
                                                                    
                                                                </table></td>
                                                            </tr>
                                                            <tr >
                                                                <td>
                                                                    <table style="width: 100%; ">
                                                                    <tr>
                                                                    <td  style="text-align:right;font-weight:bold;font-family: sans-serif;"><span> '.$princ.' </span></td>
                                                                    </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr >
                                                                <td>
                                                                    <table style="width: 100%; ">
                                                                    <tr>
                                                                    <td  style="text-align:center;font-weight:bold;background: #292929; color:#ffffff;font-family: sans-serif;"><span> '.$session_year.' </span></td>
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
                                        </tbody>
                                </table>';
                                
                                //print_r($header); die;
                        
                            $viewpage = '<div style=" width:35%; margin:0 auto; font-weight: bold; font-size: 15px; border:1px solid #ccc">'.$header.'</div>';
                            $style = 'style="display:none;"';
                            $searchicon = 'style="display:block;"';
                            $dnloadreport = 'style="display:block;"';
                            $closeicon = 'style="display:none;"';
                            $error = "";
                            $adm_no = $admno;
                        }
                        else
                        {
                            foreach($retrieve_langlabel as $langlbl) 
                            { 
                                if($langlbl['id'] == '1976') { $studnodoesexst = $langlbl['title'] ; } 
                            } 
                
                            $error = $studnodoesexst;
                            $viewpage = '';
                            $style = '';
                            $searchicon = 'style="display:none;"';
                            $closeicon = 'style="display:none;"';
                            $dnloadreport = 'style="display:none;"';
                            $adm_no = "";
                        }
        		    }
        		    else
        		    {
        		        $error ="You don't have access of this class students.";
        		        $viewpage = '';
                        $style = '';
                        $searchicon = 'style="display:none;"';
                        $closeicon = 'style="display:none;"';
                        $dnloadreport = 'style="display:none;"';
                        $adm_no = "";
        		    }
                }
                else
                {
                    $viewpage = '';
                    $style = '';
                    $searchicon = 'style="display:none;"';
                    $closeicon = 'style="display:none;"';
                    $dnloadreport = 'style="display:none;"';
                    $error = "";
                    $adm_no = "";
                    
                }
                $this->set("error", $error); 
                $this->set("downloadreport", $dnloadreport); 
                $this->set("adm_no", $adm_no); 
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
            
            public function idcard()
            {
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $sclid = $this->request->session()->read('company_id');  
                    $school_table = TableRegistry::get('company');
                    $class_table = TableRegistry::get('class');
                    $student_table = TableRegistry::get('student');
                    $session_table = TableRegistry::get('session');
                    $feestruct_table = TableRegistry::get('fee_structure');
                    $studentfee_table = TableRegistry::get('student_fee');
                    $studentfee_table = TableRegistry::get('student_fee');
                    $sclattendance_table = TableRegistry::get('attendance_school');
                    $school_table = TableRegistry::get('company');
                    if(!empty($sclid)) {
                    $retrieve_school = $school_table->find()->where([ 'id' => $sclid])->toArray();
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
                    
                    $retrieve_totalfee = $feestruct_table->find()->where(['class_id' => $classid, 'start_year' => $sessionid, 'school_id' => $sclid,  'active' => 1])->first();
                    $retrieve_feepaid = $studentfee_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'start_year' => $sessionid, 'school_id' => $sclid])->toArray();
                    
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
                    
                    
                    $present_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Present' ])->count();
                    $absent_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Absent' ])->count();
                    $leave_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'student_id' => $studentid, 'school_id' => $sclid, 'date >=' => $fromdate, 'date <=' => $todate, 'title' => 'Leave' ])->count();
                    
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
            


            
}

  


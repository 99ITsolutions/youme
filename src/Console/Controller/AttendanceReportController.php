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
class AttendanceReportController  extends AppController
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
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        if(!empty($compid))
        {
            $retrieve_class = $class_table->find()->select(['id' ,'c_name','c_section' ,'active'  ])->where(['school_id' => $compid])->toArray() ;
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_classgrade = $class_table->find()->select(['id' ,'c_name' ])->where(['school_id' => $compid])->group(['c_name'])->toArray() ;
            $error = "";
            $filters = '';
            if(!empty($_POST))
            {
                $filters = "filters";
                $cname = $this->request->data('grades') ;
                $sclsectn = $this->request->data('sections') ;
                $classes = $this->request->data('classes') ;
                
                if( ($cname != "") && ($sclsectn != "") && ($classes != "") )
                {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'c_section' => $classes, 'school_sections' => $sclsectn])->toArray() ;
                }
                elseif( ($cname != "") && ($sclsectn != ""))
                {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'school_sections' => $sclsectn])->toArray() ;
                }
                elseif($cname != "")
                {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname])->toArray() ;
                }
                else
                {
                    $retrieve_class = $class_table->find()->where(['school_id' => $compid ])->toArray() ;
                }
            }
            
            $this->set("filters", $filters); 
            $this->set("error", $error); 
            $this->set("grade_details", $retrieve_classgrade); 
            $this->set("class_details", $retrieve_class); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;    
        }
    }
        
    public function getdata()
    {
		if ($this->request->is('ajax') && $this->request->is('post'))
		{		
			$class_table = TableRegistry::get('class');
            $compid = $this->request->session()->read('company_id');
			$employee_table = TableRegistry::get('employee');
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
			$retrieve_class = $class_table->find()->select(['id' ,'c_name','c_section' ,'active' , 'school_sections' ])->where([ 'school_id' => $compid, 'active' => 1])->order(['school_sections' => 'ASC'])->toArray() ;
		

			if($this->Cookie->read('logtype') == md5('Employee'))
			{
				$employee_id = $this->Cookie->read('id');
				$retrieve_employee = $employee_table->find()->select(['privilages' ])->where(['md5(id)'=> $employee_id, 'school_id'=> $compid ])->toArray() ; 
            
				$emp_privilage = explode(',',$retrieve_employee[0]['privilages']) ;
			}
            $month = date('m');
            $data = "";
            $datavalue = array();
            foreach ($retrieve_class as $value) 
			{   
				$data .=  '<tr>
                        <td class="width45">
                    <label class="fancy-checkbox">
                            <input class="checkbox-tick" type="checkbox" name="checkbox">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                    </td>
                    <td>
                        <span>'.$value['c_section'].'</span>
                    </td>
                    <td>
                        <span>'.$value['school_sections'].'</span>
                    </td>
                    <td><a href="'.$this->baseurl.'AttendanceReport/view/'.$value['id'].'/'.$month.'" class="btn btn-sm btn-outline-secondary" title="Report"><i class="fa fa-user"></i></a></td>
                </tr>';
            }
            $datavalue['html']= $data;
            return $this->json($datavalue);
        }
    }
    public function getsclsctns()
    {
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        $c_name = $this->request->data('cname');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid)) {
            $retrieve_classgrade = $class_table->find()->select(['id' ,'school_sections' ])->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->group(['school_sections'])->toArray() ;
            
            $data = "";
            $data .= '<option value="">Choose Sections</option>';
            foreach($retrieve_classgrade as $cls)
            {
                $data .= '<option value="'.$cls['school_sections'].'">'.$cls['school_sections'].'</option>';
            }
        
		    $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $c_name])->order(['school_sections' => 'ASC'])->toArray() ;
            $month = date('m');
            $tabledata = "";
            $datavalue = array();
            foreach ($retrieve_class as $value) 
			{
				$tabledata .=  '<tr>
                        <td class="width45">
                        <label class="fancy-checkbox">
                                <input class="checkbox-tick" type="checkbox" name="checkbox">
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['c_section'].'</span>
                        </td>
                        <td>
                            <span>'.$value['school_sections'].'</span>
                        </td>
                        <td><a href="'.$this->baseurl.'AttendanceReport/view/'.$value['id'].'/'.$month.'" class="btn btn-sm btn-outline-secondary" title="Report"><i class="fa fa-user"></i></a></td>
                    </tr>';
            }
            
            $datavalue['html'] = $data;
            $datavalue['tabledata'] = $tabledata; 

            return $this->json($datavalue);
        }
        else
        {
             return $this->redirect('/login/') ;    
        }
    }
            
    public function getclssctns()
    {
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $month = date('m');
        if(!empty($compid)) {
            $c_name = $this->request->data('cname');
            $sclsectn = $this->request->data('sclsectn');
            
            $retrieve_classgrade = $class_table->find()->select(['id' ,'c_section' ])->where(['school_id' => $compid, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;
            
            $data = "";
            $data .= '<option value="">Choose Classes</option>';
            foreach($retrieve_classgrade as $cls)
            {
                $data .= '<option value="'.$cls['c_section'].'">'.$cls['c_section'].'</option>';
                
            }
            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $c_name, 'school_sections' => $sclsectn])->toArray() ;

            $tabledata = "";
            $datavalue = array();
            foreach ($retrieve_class as $value) 
			{
				$tabledata .=  '<tr>
                        <td class="width45">
                        <label class="fancy-checkbox">
                                <input class="checkbox-tick" type="checkbox" name="checkbox">
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['c_section'].'</span>
                        </td>
                        <td>
                            <span>'.$value['school_sections'].'</span>
                        </td>
                        <td><a href="'.$this->baseurl.'AttendanceReport/view/'.$value['id'].'/'.$month.'" class="btn btn-sm btn-outline-secondary" title="Report"><i class="fa fa-user"></i></a></td>
                    </tr>';
                
            }
            
            $datavalue['html'] = $data;
            $datavalue['tabledata'] = $tabledata; 
            return $this->json($datavalue);
        }
        else
        {
             return $this->redirect('/login/') ;    
        }
    }
            
    public function getsctns()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        $c_name = $this->request->data('cname');
        $sclsectn = $this->request->data('sclsectn');
        $sec = $this->request->data('classes');
        $month = date('m');
        if(!empty($compid)) {
            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_section' => $sec, 'c_name' => $c_name, 'school_sections' => $sclsectn])->order(['school_sections' => 'ASC'])->toArray() ;
            
            $tabledata = "";
            $datavalue = array();
            foreach ($retrieve_class as $value) 
			{
				$tabledata .=  '<tr>
                        <td class="width45">
                        <label class="fancy-checkbox">
                                <input class="checkbox-tick" type="checkbox" name="checkbox">
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$value['c_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['c_section'].'</span>
                        </td>
                        <td>
                            <span>'.$value['school_sections'].'</span>
                        </td>
                        <td><a href="'.$this->baseurl.'AttendanceReport/view/'.$value['id'].'/'.$month.'" class="btn btn-sm btn-outline-secondary" title="Report"><i class="fa fa-user"></i></a></td>
                    </tr>';
            }
            
            $datavalue['tabledata'] = $tabledata;
            return $this->json($datavalue);
        }
        else
        {
             return $this->redirect('/login/') ;    
        }
    }
            
    public function view($classid, $month)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $holidays_table = TableRegistry::get('holidays');
        $session_table = TableRegistry::get('session');
        $sclattendance_table = TableRegistry::get('attendance_school');
		$retrieve_class = $class_table->find()->where([ 'id' => $classid])->first() ;
		
		$sessionid = $this->Cookie->read('sessionid');
		$retrieve_session = $session_table->find()->where([ 'id' => $sessionid])->first() ;
		
		$sclid = $this->request->session()->read('company_id');
		$retrieve_students = $student_table->find()->where([ 'class' => $classid, 'session_id' => $sessionid, 'school_id' => $sclid])->toArray() ;
		
		$retrieve_holidays = $holidays_table->find()->where([ 'school_id' => $sclid])->toArray() ;
		
		//$month = date('m'); 
		$year = date('Y');
		$month = $month;
		$strtdate = $year."-".$month."-01";
		$enddate = $year."-".$month."-31";
		
		//$retrieve_attendance = $attendance_table->find()->where(['class_id' => $classid, 'school_id' => $sclid, 'attdate >=' => $strtdate, 'attdate <=' => $enddate ])->toArray() ;
		$retrieve_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'school_id' => $sclid, 'date >=' => $strtdate, 'date <=' => $enddate ])->toArray() ;
		
		$this->set("session_details", $retrieve_session); 
		$this->set("attend_details", $retrieve_attendance); 
		$this->set("stud_details", $retrieve_students); 
		$this->set("holiday_details", $retrieve_holidays); 
        $this->set("class_details", $retrieve_class); 
        $this->set("mnth", $month); 
        $this->viewBuilder()->setLayout('user');
    }
    
    public function updatesclatend()
    {
        $sclattendance_table = TableRegistry::get('attendance_school');
        $activ_table = TableRegistry::get('activity');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		$sessionid = $this->Cookie->read('sessionid');
		$sclid = $this->request->session()->read('company_id');
		
		$attendance = $this->request->data('attendance');
		if($attendance == 'P')
        {
            $attndnc = 'Present';
        }
        elseif($attendance == 'E')
        {
            $attndnc = 'Exception';
        }
        else
        {
            $attndnc = 'Absent';
        }
        $reason = $this->request->data('reason');
        $seldate = date('Y-m-d', strtotime($this->request->data('seldate')));
        $studid = $this->request->data('studid');
        $classid = $this->request->data('classid');
        
        $retrieve_attendancecount = $sclattendance_table->find()->where(['class_id' => $classid, 'date' => $seldate, 'student_id' => $studid ])->count();
        
        if($retrieve_attendancecount == 0)
        {
            $attend = $sclattendance_table->newEntity();
            $attend->class_id = $classid;
            $attend->school_id = $sclid;
            $attend->date = $seldate;
            $attend->student_id = $studid;
            $attend->title = $attndnc;
            $attend->reason = $reason;
            $attend->created_date = time();
            
            
            if($saved = $sclattendance_table->save($attend))
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "school attendance added!"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = $saved->id ;
                $activity->origin = $this->Cookie->read('tid')   ;
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
                $res = ['result' => 'notsaved'];
            }
        }
        else
        {
            $retrieve_attendance = $sclattendance_table->find()->where(['class_id' => $classid, 'date' => $seldate, 'student_id' => $studid ])->first();
            $update = $sclattendance_table->query()->update()->set(['reason' => $reason,'title' => $attndnc])->where(['id' => $retrieve_attendance['id'] ])->execute();
            				    
    		if($update)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "school attendance updated!"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($retrieve_attendance['id'] )    ;
                $activity->origin = $this->Cookie->read('tid')   ;
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
                $res = ['result' => 'notsaved'];
            }
        }
		return $this->json($res);
    }
    
    public function updatesubatend()
    {
        $sclattendance_table = TableRegistry::get('attendance');
        $activ_table = TableRegistry::get('activity');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		$sessionid = $this->Cookie->read('sessionid');
		$sclid = $this->request->session()->read('company_id');
		
		$attendance = $this->request->data('attendance');
		if($attendance == 'P')
        {
            $attndnc = 'Present';
        }
        elseif($attendance == 'E')
        {
            $attndnc = 'Exception';
        }
        else
        {
            $attndnc = 'Absent';
        }
        $reason = $this->request->data('reason');
        $seldate = date('Y-m-d', strtotime($this->request->data('seldate')));
        $studid = $this->request->data('studid');
        $classid = $this->request->data('classid');
        $subid = $this->request->data('subid');
        
        $retrieve_attendancecount = $sclattendance_table->find()->where(['subject_id' => $subid, 'class_id' => $classid, 'date' => $seldate, 'student_id' => $studid ])->count();
        
        if($retrieve_attendancecount == 0)
        {
            $attend = $sclattendance_table->newEntity();
            $attend->subject_id = $subid;
            $attend->class_id = $classid;
            $attend->school_id = $sclid;
            $attend->date = $seldate;
            $attend->attdate = $seldate;
            $attend->student_id = $studid;
            $attend->title = $attndnc;
            $attend->attendance = $attndnc;
            $attend->reason = $reason;
            $attend->created_date = time();
            
            
            if($saved = $sclattendance_table->save($attend))
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "school attendance added!"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = $saved->id ;
                $activity->origin = $this->Cookie->read('tid')   ;
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
                $res = ['result' => 'notsaved'];
            }
        }
        else
        {
            $retrieve_attendance = $sclattendance_table->find()->where(['subject_id' => $subid, 'class_id' => $classid, 'date' => $seldate, 'student_id' => $studid ])->first();
            $update = $sclattendance_table->query()->update()->set(['reason' => $reason,'title' => $attndnc, 'attendance' => $attndnc])->where(['id' => $retrieve_attendance['id'] ])->execute();
            				    
    		if($update)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "school attendance updated!"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($retrieve_attendance['id'] )    ;
                $activity->origin = $this->Cookie->read('tid')   ;
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
                $res = ['result' => 'notsaved'];
            }
        }
		return $this->json($res);
    }
    
    public function subjectlisting($sid, $month, $clsid)
    {
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $clssub_table = TableRegistry::get('class_subjects');
        $subj_table = TableRegistry::get('subjects');
        $holidays_table = TableRegistry::get('holidays');
        $sessionid = $this->Cookie->read('sessionid');
		$sclid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_class = $class_table->find()->where([ 'id' => $clsid])->first();
        $retrieve_student = $student_table->find()->where([ 'id' => $sid])->first();
        
        $retrieve_classsub = $clssub_table->find()->where([ 'class_id' => $retrieve_class['id']])->first();
        $subjectids = explode(",", $retrieve_classsub['subject_id']);
        
        $retrieve_subjct = $subj_table->find()->where([ 'id IN' => $subjectids])->toArray();
        $retrieve_holidays = $holidays_table->find()->where(['session_id' => $sessionid, 'school_id' => $sclid])->toArray() ;
        
        $this->set("sub_details", $retrieve_subjct); 
        $this->set("stud_details", $retrieve_student); 
        $this->set("class_details", $retrieve_class);
        $this->set("month", $month); 
        $this->set("holiday_details", $retrieve_holidays);
        
        $this->viewBuilder()->setLayout('user');
    }
    
    public function getmonthreport()
    {
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $holidays_table = TableRegistry::get('holidays');
        $attendance_table = TableRegistry::get('attendance_school');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $classid = $this->request->data('clsid');
       
		//$retrieve_class = $class_table->find()->where([ 'id' => $classid])->first() ;
		
		$sessionid = $this->Cookie->read('sessionid');
		$sclid = $this->request->session()->read('company_id');
		$retrieve_students = $student_table->find()->where([ 'class' => $classid, 'session_id' => $sessionid, 'school_id' => $sclid])->toArray() ;
		
		$retrieve_holidays = $holidays_table->find()->where(['school_id' => $sclid])->toArray() ;
		
		$month = $this->request->data('month');
		$year = date('Y');
		$fromdata = "09-".$month."-".$year;
        $monthname = date("F", strtotime($fromdata));
		
		$strtdate = $year."-".$month."-01";
		$enddate = $year."-".$month."-31";
		
		$retrieve_attendance = $attendance_table->find()->where(['class_id' => $classid, 'school_id' => $sclid, 'date >=' => $strtdate, 'date <=' => $enddate ])->toArray() ;
		
		$lang = $this->Cookie->read('language');	
		if($lang != "") { $lang = $lang; } else { $lang = 2; }
        $language_table = TableRegistry::get('language_translation');
        if($lang == 1)
        {
            $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
            foreach($retrieve_langlabel as $langlabel)
            {     $langlabel->title = $langlabel['english_label'];  }
        }
        else
        {
            $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
            foreach($retrieve_langlabel as $langlabel)
            { $langlabel->title = $langlabel['french_label']; }
        }
        $tablehead = '';  
        $tablebody = '';  
        foreach($retrieve_langlabel as $langlbl) 
        { 
            if($langlbl['id'] == '147') { $th1 = $langlbl['title'] ; }
            if($langlbl['id'] == '277') { $th2 = $langlbl['title'] ; } 
        } 
        $throws = '';
		if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
            { 
                for($i = 1; $i<= 31; $i++)
                { 
                    $throws .= '<th>'.$i.'</th>';
                }  
            } 
            elseif($month == "02")
            { for($i = 1; $i<= 28; $i++){ 
                 $throws .= '<th>'.$i.'</th>';
            }  } 
            else
            { for($i = 1; $i<= 30; $i++){ 
                 $throws .= '<th>'.$i.'</th>';
            }  } 
		
		$tablehead = '<tr>
            <th class="name-col" width="35%">'.$th1.'</th>
            <th class="name-col">'.$th2.'</th>
            <th class="name-col">Absent</th>
            <th class="name-col">Exception</th>
            '.$throws.'
            <th class="name-col">Exception Details</th>
        </tr>';
        
        
        foreach($retrieve_holidays as $holi) { 
        	$holidates[] = $holi['date'];
        }
        foreach($retrieve_attendance as $att) { 
        	$studids[] = $att['student_id'];
        	$attdates[] = $att['attdate'];
        }
        $tablebody = '';                      
        foreach($retrieve_students as $stud) { 
            if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
            { 
                $strtdate = $year."-".$month."-01";
                $enddate = $year."-".$month."-31";
            }
            elseif($month == "02")
            {
                $strtdate = $year."-".$month."-01";
                $enddate = $year."-".$month."-28";
            }
            else
            {
                $strtdate = $year."-".$month."-01";
                $enddate = $year."-".$month."-30";
            }
            $sid = $stud['id'];
            
            $gettotal = $this->totalpresent($strtdate, $enddate, $sid);
            
            //print_r($gettotal);
            $tdrows = '';
            if(($month == "01") || ($month == "03") || ($month == "05") || ($month == "07") || ($month == "08") || ($month == "10") || ($month == "12"))
            { 
                for($i = 1; $i<= 31; $i++) { 
                    //$datecol = $i."-".$month."-".$year;
                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                    $datecol = $j."-".$month."-".$year;
                    $revdatecol = $year."-".$month."-".$j;
                    $dayofcolumn = date('w', strtotime($datecol));
                    if(in_array($revdatecol, $holidates))
                    {
                        $holiday = 1;
                    }
                    else
                    {
                        $holiday = 0;
                    }
                                                
                    if($dayofcolumn == 0) 
                    { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    } 
                    elseif($holiday == 1) { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    }
                    else { 
                        if($dateatt = $this->checkattendance($revdatecol, $stud['id']))
                        { 
                            
                            if($dateatt == "P") { $attbg = "style='background-color: green;color: #FFF;'" ; }
                            elseif($dateatt == "E") { $attbg =  "style='background-color: yellow;'" ; }
                            elseif($dateatt == "A") { $attbg =  "style='background-color: red;color: #FFF;'"; }
                            else { $attbg = ''; }
                            
                            if($dateatt != "")
                            {
                                $tdrows .= '<td class="attend-col" '. $attbg .' ><a href="javascript:void(0)" class="attendupdate attendcolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="'. $dateatt .'">'. $dateatt .'</a></td>';
                            } 
                            else
                            { 
                                $tdrows .= '<td class="attend-col" '. $attbg  .' ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                            }
                        }
                        else
                        { 
                            $tdrows .= '<td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                        }
                    }
                } 
            }
            elseif($month == "02")
            { 
                for($i = 1; $i<= 28; $i++)
                { 
                    $datecol = $i."-".$month."-".$year;
                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                    $revdatecol = $year."-".$month."-".$j;
                    $dayofcolumn = date('w', strtotime($datecol));
                    if(in_array($revdatecol, $holidates))
                    {
                        $holiday = 1;
                    }
                    else
                    {
                        $holiday = 0;
                    }
                    if($dayofcolumn == 0) 
                    { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    } 
                    elseif($holiday == 1) { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    }
                    else { 
                        if($dateatt = $this->checkattendance($revdatecol, $stud['id']))
                        { 
                            
                            if($dateatt == "P") { $attbg = "style='background-color: green;color: #FFF;'" ; }
                            elseif($dateatt == "E") { $attbg =  "style='background-color: yellow;'" ; }
                            elseif($dateatt == "A") { $attbg =  "style='background-color: red;color: #FFF;'"; }
                            else { $attbg = ''; }
                            
                            if($dateatt != "")
                            {
                                $tdrows .= '<td class="attend-col" '. $attbg .' ><a href="javascript:void(0)" class="attendupdate attendcolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="'. $dateatt .'">'. $dateatt .'</a></td>';
                            } 
                            else
                            { 
                                $tdrows .= '<td class="attend-col" '. $attbg  .' ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                            }
                        }
                        else
                        { 
                            $tdrows .= '<td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                        }
                    }
                }  
            } 
            else
            { 
                for($i = 1; $i<= 30; $i++) 
                { 
                    $datecol = $i."-".$month."-".$year;
                    if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
                    $revdatecol = $year."-".$month."-".$j;
                    $dayofcolumn = date('w', strtotime($datecol));
                    if(in_array($revdatecol, $holidates))
                    {
                        $holiday = 1;
                    }
                    else
                    {
                        $holiday = 0;
                    }
                    if($dayofcolumn == 0) 
                    { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    } 
                    elseif($holiday == 1) { 
                        $tdrows .= '<td class="attend-col" style="background-color: blue;color: #FFF;">H</td>';
                    }
                    else { 
                        if($dateatt = $this->checkattendance($revdatecol, $stud['id']))
                        { 
                            
                            if($dateatt == "P") { $attbg = "style='background-color: green;color: #FFF;'" ; }
                            elseif($dateatt == "E") { $attbg =  "style='background-color: yellow;'" ; }
                            elseif($dateatt == "A") { $attbg =  "style='background-color: red;color: #FFF;'"; }
                            else { $attbg = ''; }
                            
                            if($dateatt != "")
                            {
                                $tdrows .= '<td class="attend-col" '. $attbg .' ><a href="javascript:void(0)" class="attendupdate attendcolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'"  data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="'. $dateatt .'">'. $dateatt .'</a></td>';
                            } 
                            else
                            { 
                                $tdrows .= '<td class="attend-col" '. $attbg  .' ><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                            }
                        }
                        else
                        { 
                            $tdrows .= '<td class="attend-col"><a href="javascript:void(0)" class="attendupdate attendnocolor" data-date="'. $datecol .'" data-studname="'. $stud['l_name'].' '.$stud['f_name'] .'"  data-clsid="'. $classid.'" data-monthname = "'. $monthname .'" data-studid="'. $stud['id'] .'" data-attend="">N/A</a></td>';
                        }
                    }
                }  
            } 
                                        
            $tablebody .= '<tr class="student">
                <td class="name-col"><a href="'.$this->baseurl.'../../subjectlisting/'.$stud['id'].'/'. $month .'/'.$classid.'">'. $stud['l_name']." ".$stud['f_name'] .'</a></td>
                <td class="attend-col">'. $gettotal['present'] .'</td>
                <td class="attend-col">'. $gettotal['absent'] .'</td>
                <td class="attend-col">'. $gettotal['exception']  .'</td>
                '.$tdrows.'                       
                <td class="attend-col">'.$gettotal['reason'] .'</td>
                </tr>';
        }
        
		$result['tablehead'] = $tablehead;
		$result['tablebody'] = $tablebody;
		return $this->json($result);
		
		
    }
    
    private function checkattendance($revdatecol, $studid){
        
        //$replies = mysqli_query($con, "SELECT * FROM `attendance` WHERE student_id = '".$studid."' AND attdate =  '".$revdatecol."' ");
        $attendance_table = TableRegistry::get('attendance_school');
        
		$retrieve_attendance = $attendance_table->find()->where([ 'student_id' => $studid, 'date' => $revdatecol])->toArray() ;
        $data = [];
        foreach($retrieve_attendance as $getdata)
        {
          $data[] = $getdata['title'];
        }
        if(in_array("Exception", $data))
        {
          $attendance = "E";
        }
        elseif(in_array("Present", $data))
        {
          $attendance = "P";
        }
        elseif(in_array("Absent", $data))
        {
          $attendance = "A";
        }
        else
        {
          $attendance = "";
        }
        //print_r($attendance); die;
        return $attendance;
    }
    
    private function totalpresent($strtdate, $enddate, $studid) 
    {
        $strt = explode("-", $strtdate);
        $end = explode("-", $enddate);
        $attendance_table = TableRegistry::get('attendance_school');
        
        for($i = 1; $i<= $end[2]; $i++)
        {
            if($i >= 1 && $i <= 9) { $j = "0".$i; } else { $j = $i; }
            $date = $strt[0]."-".$strt[1]."-".$j;
            //$getatt = mysqli_query($con, "SELECT * FROM `attendance` WHERE student_id = '".$studid."' AND attdate =  '".$date."'  ");
		    $retrieve_attendance = $attendance_table->find()->where([ 'student_id' => $studid, 'date' => $date])->toArray() ;
            $data = [];
            $exception = "";
            $present = "";
            $absent = "";
            $reason = '';
            foreach($retrieve_attendance as $getdata)
            {
              $data[] = $getdata['title'];
              $resn[] = $getdata['reason'];
            }
            if(in_array("Exception", $data))
            {
                $exception .= "1,";
                $searhkey= array_search("Exception", $data);
                $reason .= $date." (". $resn[$searhkey] .")";
            }
            elseif(in_array("Present", $data))
            {
                $present .= "1,";
            }
            elseif(in_array("Absent", $data))
            {
                $absent .= "1,";
            }
            else
            {
                $notentr = "";
            }
            //echo $exception;
            $exc= explode(",", $exception);
            $excp = count($exc)-1;
            $excptn[] = $excp;
            
            $pre= explode(",", $present);
            $pres = count($pre)-1;
            $prsnt[] = $pres;
            
            $ab= explode(",", $absent);
            $abs = count($ab)-1;
            $absnt[] = $abs;
            $reson[] = $reason;
        }
        
        $excptn = array_sum($excptn);
        $prsnt = array_sum($prsnt);
        $absnt = array_sum($absnt); 
        $reson = implode(" ", $reson);
        $dataattendance['exception'] = $excptn;
        $dataattendance['absent'] = $absnt;
        $dataattendance['present'] = $prsnt;
        $dataattendance['reason'] = $reson;
        return $dataattendance;
    }
    
    public function studentlist()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $id = $this->request->data['classid'];
        $start_year = $this->request->data['start_year'];
        $student_table = TableRegistry::get('student');
        $compid = $this->request->session()->read('company_id');
        if(!empty($compid))
        {
            $retrieve_student = $student_table->find(all,  ['order' => ['l_name'=>'asc']])->where(['class' => $id, 'school_id' => $compid, 'session_id' => $start_year])->toArray(); 
            $all_data = "<option value=''>Select Student</option>";
            
            foreach($retrieve_student as $student)
            {
                $all_data .= "<option value='".$student['id']."'>".$student['l_name']."-".$student['f_name']." (". $student['adm_no'] ." )</option>";
            }
            
            return $this->json($all_data);
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
    
    
            
}



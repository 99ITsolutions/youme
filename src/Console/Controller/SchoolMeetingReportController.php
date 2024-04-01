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
class SchoolMeetingReportController extends AppController
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
        //print_r($_SESSION); die;
        $sclid = $this->Cookie->read('id'); 
        
        $school_table = TableRegistry::get('company');
        $retrieve_schools = $school_table->find()->where(['status' => 1])->toArray();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $class_table = TableRegistry::get('class');
        $classsub_table = TableRegistry::get('class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $meetings_table = TableRegistry::get('meeting_link');
        $emp_table = TableRegistry::get('employee');
        
		$session_id = $this->Cookie->read('sessionid');
		
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
            if($langlbl['id'] == '1611') { $strtdt = $langlbl['title'] ; } 
        } 
		
		
		if(!empty($_POST))
		{
		    if($this->request->data('searchby') == "2")
		    {
    		    $sclids = $this->request->data('schoolid');
    		    $clsid = $this->request->data('class');
    		    $subjctid = $this->request->data('subjects');
    		    
    		    $startdate = strtotime($this->request->data('startdate')." 12:00 AM");
    		    $enddate = strtotime($this->request->data('enddate')." 11:59 PM");
    		    
    		    $retrieve_classsub = $classsub_table->find()->where(['school_id' => $sclids, 'class_id' => $clsid, 'status' => 1 ])->first() ;
    		    $subj = explode(",", $retrieve_classsub['subject_id']);
    		    
    		    foreach($subj as $sub)
    		    {
    		        $retrieve_sub = $subjects_table->find()->where(['id' => $sub ])->first() ;
    		        $subjectnames[] = $retrieve_sub['subject_name'];
    		        $subjectids[] = $retrieve_sub['id'];
    		    }
    		    $retrieve_class = $class_table->find()->where(['school_id' => $sclids ])->toArray() ;
        		if($startdate < $enddate)
    		    {    
        		    $retrieve_meetings = $meetings_table->find()->where(['school_id' => $sclids, 'schedule_datetime >=' => $startdate, 'schedule_datetime <=' => $enddate, 'class_id' => $clsid, 'subject_id' => $subjctid, 'session_id' => $session_id ])->order(['id' => 'desc'])->toArray() ;
    		    
        		    foreach($retrieve_meetings as $meet)
        		    {
        		        $tchrid  = $meet['teacher_id'];
        		        $retrieve_emp = $emp_table->find()->where(['id' => $tchrid ])->first() ;
        		        
        		        $meet->tchr_name = $retrieve_emp['f_name']." ".$retrieve_emp['l_name'];
        		    }
        		     $error = '';
		        }
    		    else
    		    {
    		        $retrieve_meetings = '';
    		        $error = $strtdt;
    		    }
    		    $enddate1 = '';
		        $startdate1 = '';
		        $tchrid = '';
		        $sclidss = '';
		        $retrieve_emps = '' ;
		    }
		    elseif($this->request->data('searchby') == "1")
		    {
    		    $sclidss = $this->request->data('schoolid');
    		    $tchrid = $this->request->data('listteacher');
    		    $retrieve_emps = $emp_table->find()->where(['id' => $tchrid ])->toArray() ;
    		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
    		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
    		    if($startdate1 < $enddate1)
    		    {
        		    $retrieve_meetings = $meetings_table->find()->where(['school_id' => $sclidss, 'schedule_datetime >=' => $startdate1, 'schedule_datetime <=' => $enddate1, 'teacher_id' => $tchrid, 'session_id' => $session_id ])->order(['id' => 'desc'])->toArray() ;
    		    
    		       
        		    foreach($retrieve_meetings as $meet)
        		    {
        		        $tchrid  = $meet['teacher_id'];
        		        $retrieve_emp = $emp_table->find()->where(['id' => $tchrid ])->first() ;
        		        
        		        $meet->tchr_name = $retrieve_emp['f_name']." ".$retrieve_emp['l_name'];
        		    }
        		    $error = '';
    		    }
    		    else
    		    {
    		        $retrieve_meetings = '';
    		        $error = $strtdt;
    		    }
    		    $enddate = '';
		        $startdate = '';
		        $clsid = '';
		        $subjctid = '';
		        $subjectids = [];
		        $subjectnames = [];
		        $retrieve_class = '';
		        $sclids ='';
		    }
		}
		else
		{
		    $subjectids = [];
		    $subjectnames = [];
		    $retrieve_meetings = '';
		    $clsid = '';
		    $subjctid = '';
		    $sclids = '';
		    $sclidss = '';
		    $retrieve_class = '';
		    $enddate = '';
		    $startdate = '';
		    $enddate1 = '';
		    $startdate1 = '';
		    $tchrid = '';
		    $retrieve_emps = '';
		    $error = '';
		}
		$this->set("school_details", $retrieve_schools);
		$this->set("error", $error);
		$this->set("startdate", $startdate);
		$this->set("enddate", $enddate);
		
		$this->set("tchrdetails", $retrieve_emps); 
		$this->set("tchrid", $tchrid);
		
		$this->set("startdate1", $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("sclids", $sclidss);
		
		$this->set("subjectids", $subjectids);
		$this->set("subjectnames", $subjectnames);
		$this->set("subjctid", $subjctid);
		$this->set("clsid", $clsid);
		$this->set("sclid", $sclids);
		$this->set("meetdetails", $retrieve_meetings);
		$this->set("classdetails", $retrieve_class);
        $this->viewBuilder()->setLayout('usersa');        
    }
    
    public function getsubjects()
    {
        $id = $this->request->data('classId');
        $compid = $this->request->data('schoolid');
        $clssub_table = TableRegistry::get('class_subjects');
        $sub_table = TableRegistry::get('subjects');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_clssub = $clssub_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'status' => 1])->first() ;
            $subject_ids = $retrieve_clssub['subject_id'];
            $ids = explode(",", $subject_ids);
            
           
            $data = '';
            $data .= '<option value="">Choose Subjects</option>';
            foreach($ids as $sids)
            {
                $retrieve_sub = $sub_table->find()->where(['id' => $sids])->first() ;
                //$subids[] = $retrieve_sub['subject_name'];
                $data .= '<option value="'.$sids.'">'.$retrieve_sub['subject_name'].'</option>';
            }
            
            
            return $this->json($data);
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function gettchrs()
    {
        $id = $this->request->data('sclid');
        $emp_table = TableRegistry::get('employee');
        $retrieve_emp = $emp_table->find()->where(['school_id' => $id, 'status' => 1])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
       
        $data = '';
        $data .= '<option value="">Choose Teacher</option>';
        foreach($retrieve_emp as $empid)
        {
            $data .= '<option value="'.$empid->id.'">'.$empid->f_name.' '.$empid->l_name.'</option>';
        }
        
        
        return $this->json($data);
    }
}

  


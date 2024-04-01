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
class MeetingReportController extends AppController
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
        $class_table = TableRegistry::get('class');
        $classsub_table = TableRegistry::get('class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $meetings_table = TableRegistry::get('meeting_link');
        $emp_table = TableRegistry::get('employee');
        if(!empty($sclid)) {
		$session_id = $this->Cookie->read('sessionid');
		$retrieve_class = $class_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
		$retrieve_empss = $emp_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
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
                        if($langlbl['id'] == '1611') { $strtend = $langlbl['title'] ; } 
                        
                    } 
                    
		
		if(!empty($_POST))
		{
		    if($this->request->data('searchby') == "2")
		    {
    		    $clsid = $this->request->data('class');
    		    $subjctid = $this->request->data('subjects');
    		    
    		    $startdate = strtotime($this->request->data('startdate')." 12:00 AM");
    		    $enddate = strtotime($this->request->data('enddate')." 11:59 PM");
    		    
    		    
    		    $retrieve_classsub = $classsub_table->find()->where(['md5(school_id)' => $sclid, 'class_id' => $clsid, 'status' => 1 ])->first() ;
    		    $subj = explode(",", $retrieve_classsub['subject_id']);
    		    
    		    foreach($subj as $sub)
    		    {
    		        $retrieve_sub = $subjects_table->find()->where(['id' => $sub ])->first() ;
    		        $subjectnames[] = $retrieve_sub['subject_name'];
    		        $subjectids[] = $retrieve_sub['id'];
    		    }
    		    
		        if($startdate < $enddate)
		        {
        		    $retrieve_meetings = $meetings_table->find()->where(['md5(school_id)' => $sclid, 'class_id' => $clsid, 'schedule_datetime >=' => $startdate, 'schedule_datetime <=' => $enddate, 'subject_id' => $subjctid, 'session_id' => $session_id ])->order(['id' => 'desc'])->toArray() ;
    		    
    		       
        		    foreach($retrieve_meetings as $meet)
        		    {
        		        $tchrids  = $meet['teacher_id'];
        		        $retrieve_emp = $emp_table->find()->where(['id' => $tchrids ])->first() ;
        		        
        		        $meet->tchr_name = $retrieve_emp['f_name']." ".$retrieve_emp['l_name'];
        		    }
        		    $error = '';
		        }
    		    else
    		    {
    		        $retrieve_meetings = '';
    		        $error = $strtend;
    		    }
    		    
		        $tchrid ='';
		        $startdate1 = '';
		        $enddate1 = '';
		        
		    }
		    elseif($this->request->data('searchby') == "1")
		    {
		        $clsid = '';
		        $subjctid ='';
		        $subjectids = [];
		        $subjectnames = [];
		        $startdate = '';
		        $enddate = '';
		        
		        $tchrid = $this->request->data('listteacher');
    		    
    		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
    		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
    		    if($startdate1 < $enddate1) 
    		    {
        		    $retrieve_meetings = $meetings_table->find()->where(['md5(school_id)' => $sclid, 'schedule_datetime >=' => $startdate1, 'schedule_datetime <=' => $enddate1, 'teacher_id' => $tchrid, 'session_id' => $session_id ])->order(['id' => 'desc'])->toArray() ;
    		    
    		    
        		    foreach($retrieve_meetings as $meet)
        		    {
        		        $tchrids  = $meet['teacher_id'];
        		        $retrieve_emp = $emp_table->find()->where(['id' => $tchrids ])->first() ;
        		        
        		        $meet->tchr_name = $retrieve_emp['f_name']." ".$retrieve_emp['l_name'];
        		    }
        		    $error = "";
    		    }
    		    else
    		    {
    		        $retrieve_meetings = '';
    		        $error = $strtend;
    		    }
		    }
		}
		else
		{
		    $subjectids = [];
		    $subjectnames = [];
		    $retrieve_meetings = '';
		    $clsid = '';
		    $subjctid = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $startdate = '';
	        $enddate = '';
	        $tchrid = '';
	        $error = '';
		}
		$this->set('error', $error);
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("startdate", $startdate);
		$this->set("enddate", $enddate);
		$this->set("tchrid", $tchrid);
		
		$this->set('tchrdetails', $retrieve_empss);
		$this->set("subjectids", $subjectids);
		$this->set("subjectnames", $subjectnames);
		$this->set("subjctid", $subjctid);
		$this->set("clsid", $clsid);
		$this->set("meetdetails", $retrieve_meetings);
		$this->set("classdetails", $retrieve_class);
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
}

  


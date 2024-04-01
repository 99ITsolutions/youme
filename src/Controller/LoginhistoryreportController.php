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
class LoginhistoryreportController extends AppController
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
     
     
    public function teacherreport()
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
        $logtrack_table = TableRegistry::get('track_logged');
        $emp_table = TableRegistry::get('employee');
        
        if(!empty($sclid)) {
		$session_id = $this->Cookie->read('sessionid');
		//$retrieve_class = $class_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
		$retrieve_empss = $emp_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
		
		if(!empty($_POST))
		{
	        $tchrid = $this->request->data['listteacher'];
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('startdate')." 11:59 PM");
		    
	        //print_r($tchrid); die;
	        if(in_array("all", $tchrid))
	        {
	            $tids = [];
	            foreach($retrieve_empss as $emps)
		        {
		            $tids[] = $emps['id'];
		        }
		        $retrieve_emps = $emp_table->find()->select(['id', 'f_name', 'l_name'])->where(['id IN' => $tids ])->toArray() ;
	        }
	        else
	        {
	            $retrieve_emps = $emp_table->find()->select(['id', 'f_name', 'l_name'])->where(['id IN' => $tchrid ])->toArray() ;
	        }
		    foreach($retrieve_emps as $emp)
		    {
    	        $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $emp['id'], 'type' => 'teacher'])->order(['id' => 'DESC'])->toArray() ;
    		    $logintime = [];
		        $logouttime =[];
    		    foreach($retrieve_log as $log)
    		    {
    		        $logintime[] = $log->login_time;
    		        $logouttime[] = $log->logout_time;
    		    }
    		    
    		    $emp->login_time = min($logintime);
    		    
    		    $maxlogin = max($logintime);
    		    $key = array_search($maxlogin, $logintime);
    		    //$emp->logout_time = max($logouttime);
    		    $emp->logout_time = $logouttime[$key];
		    }
		}
		else
		{
		    $startdate1 = '';
	        $tchrid = '';
	        $retrieve_emps ='';
		}
		$this->set('error', $error);
		$this->set('startdate1', $startdate1);
		$this->set("tchrid", $tchrid);
		$this->set('tchrdetails', $retrieve_empss);
		$this->set("logdetails", $retrieve_emps);
		
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function activitytrackertchr()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        if(!empty($sclid)) {
            $id = $this->request->data('id');
            $strtdate = $this->request->data('strtdate'); 
            $logtrack_table = TableRegistry::get('logged_tracking');
            $tracklog_table = TableRegistry::get('track_logged');
            $str = $this->request->data('str');
            $startdate1 = strtotime($strtdate." 12:00 AM");
    	    $enddate1 = strtotime($strtdate." 11:59 PM");
    	    $data = '';
    	    if($startdate1 < $enddate1) 
    	    {
    		    $retrieve_logs = $tracklog_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $id, 'type' => $str])->order(['id' => 'DESC'])->toArray() ;
    	        
    		    foreach($retrieve_logs as $log)
    		    {
    		        $trackid = $log['id'];
    		        $retrieve_log = $logtrack_table->find()->where(['track_id' => $trackid])->order(['logged_tracking.id' => 'DESC'])->toArray() ;
    		        
    		        $logouttime = '';
                    $duration = '';
                    $minutes = '';
                    $hour = '';
                    
                    if($log->logout_time != "")
                    {
                        $logouttime = date('h:i A',$log->logout_time);
                        $difference = date_diff($log->login_time, $log->logout_time); 
                        $hour = $difference->h;
                        
                        $minutes = $difference->days * 24 * 60;
                        $minutes += $difference->h * 60;
                        $minutes += $difference->i;
                        
                        
                        if($hour == "" && $minutes == "0")
                        {
                            $duration = "00:00";
                        }
                        elseif($hour == "")
                        {
                            $duration = "00:".$minutes;
                        }
                        else
                        {
                            $duration = $hour.":".$minutes;
                        }
                    }

                    $totalduration = $log->logout_time - $log->login_time; 
                 
                    if($totalduration < 60 && $logouttime != ""){ $myduration = $totalduration." Seconds";}
                    else if($totalduration > 60 && $totalduration < 3600 && $logouttime != ""){ 
                        $minduration = $totalduration/60;
                        $myduration =  round($minduration)." Minutes";
                       }    
                    else{
                         $myduration = "";
                    }
    		        
    		        if(!empty($retrieve_log))
    		        {
    		            
    		            if(empty($log->logout_through) && empty($log->logout_time))
    		            {
    		                $lgth = "Online";
    		            }
    		            elseif(empty($log->logout_through) && ($log->logout_time != ""))
    		            {
    		                $lgth = "User Logout";
    		            }
    		            else
    		            {
    		                $lgth = $log->logout_through;
    		            }
    		            
        		        $data .= '<div class="row" style="margin-top: 15px;">
                            <div class="col-md-12" ><b>Date:</b> '. date('M d, Y',$log->login_time) .'</div>
                            <div class="container row clearfix">
                                <div class="col-md-3"><b>Login Duration:</b> 
                                    '. date('h:i A',$log->login_time).' - '.$logouttime. ' ('. $myduration .')
                                </div>
                            </div>
                            <div class="col-md-12" ><b>Logout Through:</b> '. $lgth .'</div>
                            <div class="col-md-12" style="margin-top: 30px;">
                                <table class="table table-bordered" id="trackact_table"> 
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Page Name</th>
                                            <th>Page Link</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
    		        
    		            $getrows = '';
            		    foreach($retrieve_log as $value)
            		    {
            		        $getrows .= '<tr>
            		            <td>
                                    <span>'.date("h:i:s A", $value['time']).'</span>
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.ucfirst($value['menu_tracking']).'</span>
                                </td>
                                <td>
                                    <span>'.$value['fullurl'].'  </span>
                                </td>
                            </tr>';
            		    }
            		    
            		    $data .= $getrows.'</tbody>
                                </table>
                            </div>
                        </div>';
    		        }
    		    }
    	    }
    	    //echo $data; die;
            return $this->json($data);
    		    
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function activitytracker()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        
        $trackid = $this->request->data('id'); 
        $logtrack_table = TableRegistry::get('logged_tracking');
        $tracklog_table = TableRegistry::get('track_logged');
        
        if(!empty($sclid)) {
    	    $retrieve_log = $logtrack_table->find()->where(['track_id' => $trackid])->order(['logged_tracking.id' => 'DESC'])->toArray() ;
	        $data = '';
	        if(!empty($retrieve_log))
	        {
    		    foreach($retrieve_log as $value)
    		    {
    		        $data .= '<tr>
    		            <td>
                            <span>'.date("h:i:s A", $value['time']).'</span>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.ucfirst($value['menu_tracking']).'</span>
                        </td>
                        <td>
                            <span>'.$value['fullurl'].'</span>
                        </td>
                        
                    </tr>';
    		    }
	        }
	        else
	        {
	            $data .= '<tr><td colspan="3" style="text-align:center;">No Activity found</td></tr>';
	        }
		    return $this->json($data);
    		    
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function studentreport()
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
        $logtrack_table = TableRegistry::get('track_logged');
        $stud_table = TableRegistry::get('student');
        $retrieve_students = '';
        if(!empty($sclid)) 
        {
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_classes = $class_table->find()->where(['md5(school_id)' => $sclid, 'active' => 1])->toArray();
            
		
		if(!empty($_POST))
		{
		    
		    //print_r($_POST); die;
	        $clsid = $this->request->data('class');
	        $studid = $this->request->data('student');
	        $str = $this->request->data('str');
	        $retrieve_students = $stud_table->find()->where(['class' => $clsid, 'session_id' => $session_id ])->toArray() ;
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('startdate')." 11:59 PM");
    		  
		    foreach($retrieve_students as $emp)
		    {
    	        $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $emp['id'], 'type' => 'student'])->order(['id' => 'DESC'])->toArray() ;
    		    $logintime = [];
		        $logouttime =[];
    		    foreach($retrieve_log as $log)
    		    {
    		        $logintime[] = $log->login_time;
    		        $logouttime[] = $log->logout_time;
    		    }
    		    
    		    $emp->login_time = min($logintime);
    		    
    		    $maxlogin = max($logintime);
    		    $key = array_search($maxlogin, $logintime);
    		    //$emp->logout_time = max($logouttime);
    		    $emp->logout_time = $logouttime[$key];
		    }
		    
		   
		}
		else
		{
		    //$retrieve_log = '';
		    $retrieve_students = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $studid = '';
		}
		$this->set('sessionid', $session_id);
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
	
		$this->set("clsid", $clsid);
		$this->set("studid", $studid);
		//$this->set('studdetails', $retrieve_students);
		
		$this->set("logdetails", $retrieve_students);
		$this->set("classdetails", $retrieve_classes);
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function subadminreport()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        
        $logtrack_table = TableRegistry::get('track_logged');
        $sclsub_table = TableRegistry::get('school_subadmin');
        
        if(!empty($sclid)) {
		$session_id = $this->Cookie->read('sessionid');
		$retrieve_sclsubs = $sclsub_table->find()->where(['md5(school_id)' => $sclid, 'status' => 1 ])->toArray() ;
		
		if(!empty($_POST))
		{
	        $subid = $this->request->data['listsclsub'];
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('startdate')." 11:59 PM");
		    
	        if(in_array("all", $subid))
	        {
	            $subids = [];
	            foreach($retrieve_sclsubs as $sclsub)
		        {
		            $subids[] = $sclsub['id'];
		        }
		        $retrieve_sclsub = $sclsub_table->find()->select(['id', 'fname', 'lname'])->where(['id IN' => $subids ])->toArray() ;
	        }
	        else
	        {
	            $retrieve_sclsub = $sclsub_table->find()->select(['id', 'fname', 'lname'])->where(['id IN' => $subid ])->toArray() ;
	        }
		    foreach($retrieve_sclsub as $sclsu)
		    {
		        $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $sclsu['id'], 'type' => 'school_subadmin'])->order(['id' => 'DESC'])->toArray() ;
    		    
    	        $logintime = [];
		        $logouttime =[];
    		    foreach($retrieve_log as $log)
    		    {
    		        $logintime[] = $log->login_time;
    		        $logouttime[] = $log->logout_time;
    		    }
    		    
    		    $sclsu->login_time = min($logintime);
    		    
    		    $maxlogin = max($logintime);
    		    $key = array_search($maxlogin, $logintime);
    		    $sclsu->logout_time = $logouttime[$key];
		    }
		    
		}
		else
		{
		    $retrieve_log = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $subid = '';
	        $error = '';
		}
		
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("subid", $subid);
		
		$this->set('subscl_details', $retrieve_sclsubs);
		$this->set("logdetails", $retrieve_sclsub);
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function parentreport()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        
        $logtrack_table = TableRegistry::get('track_logged');
        $parentld_table = TableRegistry::get('parent_logindetails');
        $stud_table = TableRegistry::get('student');
        
        if(!empty($sclid)) {
		$session_id = $this->Cookie->read('sessionid');
		
		$retrieve_parent = $stud_table->find()->select(['parent_logindetails.id', 'parent_logindetails.parent_email'])->join([
    		    'parent_logindetails' => 
                [
                    'table' => 'parent_logindetails',
                    'type' => 'LEFT',
                    'conditions' => 'parent_logindetails.id = student.parent_id '
                ]
		    ])->where(['md5(school_id)' => $sclid, 'status' => 1, 'session_id' => $session_id ])->toArray() ;
		$retrieve_parent = array_unique($retrieve_parent);
		//print_r($retrieve_parent); die;
		
		if(!empty($_POST))
		{
	        $parid = $this->request->data('listparent');
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
		    if($startdate1 < $enddate1) 
		    {
    		    $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $parid, 'type' => 'parent'])->order(['id' => 'DESC'])->toArray() ;
		    
    		    $error = "";
		    }
		    else
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
		        $retrieve_log = '';
		        $error = $strtend;
		    }
		   
		}
		else
		{
		    $retrieve_log = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $parid = '';
	        $error = '';
		}
		$this->set('error', $error);
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("parid", $parid);
		
		$this->set('par_details', $retrieve_parent);
		$this->set("logdetails", $retrieve_log);
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
}

  


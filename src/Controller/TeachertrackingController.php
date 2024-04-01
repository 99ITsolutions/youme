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
//use PhpSpreadsheet\Reader\Xlsx;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TeachertrackingController extends AppController
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
     
     
    public function activitytracker()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        if(!empty($sclid)) {
            $stuidid = $this->request->data('stuid');
            $strtdate = $this->request->data('strtdate'); 
            $logtrack_table = TableRegistry::get('logged_tracking');
            $tracklog_table = TableRegistry::get('track_logged');
        
            $startdate1 = strtotime($strtdate." 12:00 AM");
    	    $enddate1 = strtotime($strtdate." 11:59 PM");
    	    $data = '';
    	    if($startdate1 < $enddate1) 
    	    {
    		    $retrieve_logs = $tracklog_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $stuidid, 'type' => 'student'])->order(['id' => 'DESC'])->toArray() ;
    	        
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
    		            //$gg = "qwrty";
        		        $data .= '<div class="row" style="margin-top: 15px;">
                            <div class="col-md-12" ><b>Date:</b> '. date('M d, Y',$log->login_time) .'</div>
                            <div class="container row clearfix">
                                <div class="col-md-3"><b>Login Duration:</b> 
                                    '. date('h:i A',$log->login_time).' - '.$logouttime. ' ('. $myduration .')
                                </div>
                            </div>
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
    
    public function studentreport()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tchrid = $this->Cookie->read('tid'); 
        if($tchrid == "")
        {
            $tid = $this->request->session()->read('tchr_id');
            $tchrid = md5($tid);
        }
        $class_table = TableRegistry::get('class');
        $empcls_table = TableRegistry::get('employee_class_subjects');
        $session_table = TableRegistry::get('session');
        $logtrack_table = TableRegistry::get('track_logged');
        $stud_table = TableRegistry::get('student');
        $retrieve_students = '';
        $clsidss = [];
        if(!empty($tchrid)) 
        {
            $retrieve_empcls = $empcls_table->find()->where(['md5(emp_id)' => $tchrid])->toArray();
            foreach($retrieve_empcls as $empcls)
            {
                $clsidss[] = $empcls['class_id'];
            }
            $clsids = array_unique($clsidss);
          
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_classes = $class_table->find()->where(['id IN' => $clsids, 'active' => 1])->toArray();
            
		
		if(!empty($_POST))
		{
	        $clsid = $this->request->data('class');
	        $retrieve_students = $stud_table->find()->select(['id', 'f_name', 'l_name'])->where(['class' => $clsid, 'session_id' => $session_id ])->toArray() ;
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('startdate')." 11:59 PM");
		    foreach($retrieve_students as $stud)
		    {
		        $stud->stud_name = $stud['f_name']." ".$stud['l_name'];
    		    $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $stud['id'], 'type' => 'student'])->order(['id' => 'DESC'])->toArray() ;
		        $logintime = [];
		        $logouttime =[];
    		    foreach($retrieve_log as $log)
    		    {
    		        $logintime[] = $log->login_time;
    		        $logouttime[] = $log->logout_time;
    		    }
    		    $style = "style='display:block;'";
    		    
    		    $stud->login_time = min($logintime);
    		    $stud->logout_time = max($logouttime);
		    }
		    
		}
		else
		{
		    $retrieve_students = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $style = "style='display:none;'";
		}
		
		
		$this->set('sessionid', $session_id);
		$this->set('startdate1', $startdate1);
		$this->set("style", $style);
		$this->set("clsid", $clsid);
		$this->set("enddate1", $enddate1);
		$this->set("logdetails", $retrieve_students);
		$this->set("classdetails", $retrieve_classes);
        $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function downloadreport()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tchrid = $this->Cookie->read('tid'); 
        $tid = $this->request->session()->read('tchr_id');
        
        $logtrack_table = TableRegistry::get('track_logged');
        $stud_table = TableRegistry::get('student');
        $track_table = TableRegistry::get('logged_tracking');
        $cls_table = TableRegistry::get('class');
        $session_id = $this->Cookie->read('sessionid');
		$clsid = $this->request->query('clsid');
        $retrieve_students = $stud_table->find()->where(['class' => $clsid, 'session_id' => $session_id ])->toArray() ;
	    
	    $startdate1 = $this->request->query('strtdate');
	    $enddate1 = $this->request->query('enddate');
	    
	    $reportname = 'download_excel/studenttrackerreport_'.$this->request->query('strtdate').'.xls';
	    $fileName = 'studenttrackerreport_'.$this->request->query('strtdate');
        
        $retrieve_cls = $cls_table->find()->where(['id' => $clsid ])->first() ;
        
        $clsname = $retrieve_cls['c_name'].'-'.$retrieve_cls['c_section'].' ('. $retrieve_cls['school_sections']. ')';
	    $date = date('d M, Y', $startdate1);
        $html = '<table  cellspacing="1" cellpadding="2" border="1" class="dataTable" width="100%">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>   
                    <th>Time Duration</th>
                    <th>Activity Tracker</th>
                </tr>
            </thead>
            <tbody>';
	    
	    foreach($retrieve_students as $stud)
	    {
		    $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $stud['id'], 'type' => 'student'])->order(['id' => 'DESC'])->toArray() ;
	        $data = [];
            $dbdata = [];
		    foreach($retrieve_log as $value)
		    {
		        $logouttime = '';
                $duration = '';
                $minutes = '';
                $hour = '';
                                            
                if($value->logout_time != "")
                {
                    $logouttime = date('h:i A',$value->logout_time);
                    $difference = date_diff($value->login_time, $value->logout_time); 
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
    
                $totalduration = $value->logout_time - $value->login_time; 
             
                if($totalduration < 60 && $logouttime != ""){ $myduration = $totalduration." Seconds";}
                else if($totalduration > 60 && $totalduration < 3600 && $logouttime != ""){ 
                    $minduration = $totalduration/60;
                    $myduration =  round($minduration)." Minutes";
                   }    
                else{
                     $myduration = "";
                }
                                            
    
                $html .= '<tr>
                    <td>'. $stud['f_name']." ".$stud['l_name'] .'</td>
                    <td>'. date('M d, Y',$value->login_time) .'</td>
                    <td>'. date('h:i A',$value->login_time) .'</td>
                    <td>'. $logouttime .'</td>
                    <td>'.$myduration.'</td>
                    <td></td>
                </tr>';
        
                $retrieve_track = $track_table->find()->where(['track_id' => $value->id])->order(['logged_tracking.id' => 'DESC'])->toArray() ;
    		    foreach($retrieve_track as $value)
    		    {
    		        $html .= '<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($value['menu_tracking']).' -- ('. date("h:i:s A", $value['time']) .')</span></td>
                    </tr>';
    		        
    		    }
		    }
            
	    }
	    
	    $html .= '</table>';
	    $viewpdf = '<div style=" width:100%; height:10px font-family: Times New Roman; font-size: 18px;"><p style="text-align:center; font-weight:bold; margin:0 auto;">Student Tracking Report'.$clsname . ' ('.$date.')</p></div>';
	    $viewpdf .= '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; "> <p>'.$html.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Landscape');
		$dompdf->render();
		$dompdf->stream($fileName.".pdf", array("Attachment" => false));

        exit(0);
	    
        /*$reportHeader='Students Tracker Report Generated on  '.date("Y-m-d").' <br><br>';
        $mainHeader = '<table><tr><td colspan="2"><h2>'.$reportname.'</h2></td></tr></table>';
        $finalHtml=$html;

        if(substr($finalHtml,(strlen($finalHtml)-8),strlen($finalHtml))!="</table>")
        {
            $finalHtml=$finalHtml."</table>";
        }

        $filepointer=fopen($reportname,'wb');
        fwrite($filepointer,$finalHtml);
        fclose($filepointer);
        print_r($finalHtml); die;
        
        header("location:".$reportname);*/
    }
    
}

  


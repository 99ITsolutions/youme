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
class SchoolteacherdairyController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
    **/
     
    public function index()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        $compid = $this->request->session()->read('company_id');
        $sclid = md5($compid);
        
        $class_table = TableRegistry::get('class');
        $empcls_list = TableRegistry::get('employee_class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $scl_table = TableRegistry::get('company');
        $emp_table = TableRegistry::get('employee');
        
        if(!empty($sclid)) 
        {
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_empss = $emp_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
    		$clsid = '';
	        $subjctid = '';
	        $tchrid = '';
	        $startdate = '';
	        
    		if(!empty($_POST))
    		{
		        $clsid = $this->request->data('teachercls');
		        $subjctid = $this->request->data('subjct');
		        $tchrid = $this->request->data('listteacher');
		        $startdate = $this->request->data('startdate');
        		    
                $retrieve_emp = $emp_table->find()->where(['id' => $tchrid ])->first();
        		$teachername = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
        		
        		$retrieve_session = $session_table->find()->where(['id' => $session_id ])->first();
        		$session = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        		
        		$retrieve_scl = $scl_table->find()->where(['id' => $compid ])->first();
        		$scllogo = $retrieve_scl['comp_logo'];
        		
        		$retrieve_subj = $subjects_table->find()->where(['id' => $subjctid ])->first();
        		$subjname = $retrieve_subj['subject_name'];
        		
        		$retrieve_cls = $class_table->find()->where(['id' => $clsid ])->first();
        		$clsname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']." (".$retrieve_cls['school_sections'] .")";
        		
        		$retrieve_empclas = $empcls_list->find()->where(['emp_id' => $tchrid ])->group(['class_id'])->toArray();
        		foreach($retrieve_empclas as $empcls)
        		{
        		    $clasid = $empcls['class_id'];
        		    $getclass = $class_table->find()->where(['id' => $clasid ])->first();
        		    $empcls->classname = $getclass['c_name']." ". $getclass['c_section']." (". $getclass['school_sections'].")";
        		}
        		
        		//print_r($retrieve_empclas); die;
        		$retrieve_empclassub = $empcls_list->find()->where(['emp_id' => $tchrid, 'class_id' => $clsid ])->toArray();
        		foreach($retrieve_empclassub as $empclssub)
        		{
        		    $subid = $empclssub['subject_id'];
        		    $getsub = $subjects_table->find()->where(['id' => $subid ])->first();
        		    $empclssub->subname = $getsub['subject_name'];
        		}
        	}
    		
    		$this->set('error', $error);
    		$this->set('clsname', $clsname);
    		$this->set("subjname", $subjname);
    		$this->set("scllogo", $scllogo);
    		$this->set("session", $session);
    		$this->set('tchrdetails', $retrieve_empss);
    		
    		$this->set("subjname", $subjname);
    		$this->set("teachername", $teachername);
    		$this->set("empclas", $retrieve_empclas);
    		$this->set("empclassub", $retrieve_empclassub);
    		
    		$this->set("clsid", $clsid);
    		$this->set("startdate", $startdate);
    		$this->set("subjctid", $subjctid);
    		$this->set("tchrid", $tchrid);
    		
            $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function getclass()
    {
   	    $empcls_list = TableRegistry::get('employee_class_subjects');
   	    $emp_list = TableRegistry::get('employee');
        $class_list = TableRegistry::get('class');
        $session_list = TableRegistry::get('session');
		$tid = $this->request->data('tid');
		$sessionid = $this->Cookie->read('sessionid');
		
		$retrieve_emp = $emp_list->find()->where(['id' => $tid ])->first();
		$teachername = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
		
		$retrieve_session = $session_list->find()->where(['id' => $sessionid ])->first();
		$session = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
		
        $retrieve_empclas = $empcls_list->find()->where(['emp_id' => $tid ])->group(['class_id'])->toArray();
        $cls = '';
        $cls .= '<option value="">Choose Class</option>';
		foreach($retrieve_empclas as $empcls)
		{
		    $clsid = $empcls['class_id'];
		    $getclass = $class_list->find()->where(['id' => $clsid ])->first();
		
		    $cls .= '<option value="'.$getclass['id'].'">'.$getclass['c_name']." ". $getclass['c_section'].' ('. $getclass['school_sections'].') </option>';
		}
		
		$data = ['tname' => $teachername, 'classes' => $cls, 'session' => $session ];
		return $this->json($data);
    } 

    public function getsubjcls()
    {
   	    $empcls_list = TableRegistry::get('employee_class_subjects');
        $subject_list = TableRegistry::get('subjects');
		$tid = $this->request->data('tid');
		$clsid = $this->request->data('clsid');
	
        $retrieve_empclas = $empcls_list->find()->where(['emp_id' => $tid, 'class_id' => $clsid ])->toArray();
        $sub = '';
        $sub .= '<option value="">Choose Subject</option>';
		foreach($retrieve_empclas as $empcls)
		{
		    $subid = $empcls['subject_id'];
		    $getsub = $subject_list->find()->where(['id' => $subid ])->first();
		
		    $sub .= '<option value="'.$getsub['id'].'">'.$getsub['subject_name'].'</option>';
		}
		
		//$data = ['tname' => $teachername, 'classes' => $cls, 'session' => $session ];
		return $this->json($sub);
    }
    
    public function addtdairy()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->Cookie->read('id'); 
            $sessionid = $this->Cookie->read('sessionid'); 
            $compid = $this->request->session()->read('company_id');
            
            //print_r($_POST); die;
            $tchrdairy_table = TableRegistry::get('teacher_dairy');
            $activ_table = TableRegistry::get('activity');
            
            $tcherid = $this->request->data('tchrid');
            $clsid = $this->request->data('clsid');
            $subid = $this->request->data('subid');
            $wksdate = $this->request->data['wksdate'];
            $wkedate = $this->request->data['wkedate'];
            $hours = $this->request->data['noofhrs'];
            $matires = $this->request->data['matires'];
            $matires_v = $this->request->data['matiresv'];
            $obectifs = $this->request->data['obectifs'];
            $observation = $this->request->data['observation'];
            
            foreach($wksdate as $key => $val)
            {
                $td = $tchrdairy_table->newEntity();
                $td->school_id = $compid;
                $td->class_id = $clsid;
        		$td->subject_id = $subid;
        		$td->teacher_id = $tcherid;
                $td->start_date = $val;
                $td->end_date = $wkedate[$key];
                $td->str_sd = strtotime($val." 12:00 AM");
                $td->str_ed = strtotime($wkedate[$key]." 11:59 PM");
                $td->hours = $hours[$key];
                $td->matieres_school = $matires[$key];
                $td->matires_v = $matires_v[$key];
                $td->obectifs_operational = $obectifs[$key];
                $td->observation = $observation[$key];
                
                $td->created_date = time();
                
                //print_r($td); die;
                $savedup = $tchrdairy_table->save($td);
                
                if($savedup)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                { 
                    $res = [ 'result' => 'failed'  ];
                }   
            }
            
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
    
    public function view()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        $compid = $this->request->session()->read('company_id');
        $sclid = md5($compid);
        
        $class_table = TableRegistry::get('class');
        $tdairy_table = TableRegistry::get('teacher_dairy');
        $empcls_list = TableRegistry::get('employee_class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $scl_table = TableRegistry::get('company');
        $emp_table = TableRegistry::get('employee');
        
        if(!empty($sclid)) 
        {
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_empss = $emp_table->find()->where(['md5(school_id)' => $sclid ])->toArray() ;
    		$clsid = '';
	        $subjctid = '';
	        $tchrid = '';
	        $startdate = '';
	        $enddate = '';
	        $dwnldrprt = 'style="display:none; "';
    		if(!empty($_POST))
    		{
		        $clsid = $this->request->data('teachercls');
		        $subjctid = $this->request->data('subjct');
		        $tchrid = $this->request->data('listteacher');
		        $startdate = $this->request->data('startdate');
		        $enddate = $this->request->data('enddate');
		        
		        $sd = strtotime($this->request->data('startdate'));
		        $ed = strtotime($this->request->data('enddate'));
		        
		        $get_tdairy = $tdairy_table->find()->where(['teacher_id' => $tchrid, 'subject_id' => $subjctid, 'class_id' => $clsid])->toArray();
		        
                $retrieve_emp = $emp_table->find()->where(['id' => $tchrid ])->first();
        		$teachername = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
        		
        		$retrieve_session = $session_table->find()->where(['id' => $session_id ])->first();
        		$session = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
        		
        		$retrieve_scl = $scl_table->find()->where(['id' => $compid ])->first();
        		$scllogo = $retrieve_scl['comp_logo'];
        		
        		$retrieve_subj = $subjects_table->find()->where(['id' => $subjctid ])->first();
        		$subjname = $retrieve_subj['subject_name'];
        		
        		$retrieve_cls = $class_table->find()->where(['id' => $clsid ])->first();
        		$clsname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']." (".$retrieve_cls['school_sections'] .")";
        		
        		$retrieve_empclas = $empcls_list->find()->where(['emp_id' => $tchrid ])->group(['class_id'])->toArray();
        		foreach($retrieve_empclas as $empcls)
        		{
        		    $clasid = $empcls['class_id'];
        		    $getclass = $class_table->find()->where(['id' => $clasid ])->first();
        		    $empcls->classname = $getclass['c_name']." ". $getclass['c_section']." (". $getclass['school_sections'].")";
        		}
        		
        		//print_r($retrieve_empclas); die;
        		$retrieve_empclassub = $empcls_list->find()->where(['emp_id' => $tchrid, 'class_id' => $clsid ])->toArray();
        		foreach($retrieve_empclassub as $empclssub)
        		{
        		    $subid = $empclssub['subject_id'];
        		    $getsub = $subjects_table->find()->where(['id' => $subid ])->first();
        		    $empclssub->subname = $getsub['subject_name'];
        		}
        		$dwnldrprt = 'style="display:block; "';
        		
        	}
    		$this->set('sd', $sd);
    		$this->set("ed", $ed);
    		
    		$this->set('get_tdairy', $get_tdairy);
    		$this->set('clsname', $clsname);
    		$this->set("subjname", $subjname);
    		$this->set("scllogo", $scllogo);
    		$this->set("session", $session);
    		$this->set('tchrdetails', $retrieve_empss);
    		
    		$this->set("subjname", $subjname);
    		$this->set("teachername", $teachername);
    		$this->set("empclas", $retrieve_empclas);
    		$this->set("empclassub", $retrieve_empclassub);
        	$this->set('dwnldrprt', $dwnldrprt);		
    		$this->set("clsid", $clsid);
    		$this->set("startdate", $startdate);
    		$this->set("enddate", $enddate);
    		$this->set("subjctid", $subjctid);
    		$this->set("tchrid", $tchrid);
    		
            $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function downloadreport($clsid, $subid, $strtdate, $enddate, $tid)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = md5($tid); 
        $compid = $this->request->session()->read('company_id');
        $sclid = md5($compid);
        
        $class_table = TableRegistry::get('class');
        $tdairy_table = TableRegistry::get('teacher_dairy');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $scl_table = TableRegistry::get('company');
        $emp_table = TableRegistry::get('employee');
        $session_id = $this->Cookie->read('sessionid');
        
        $retrieve_empss = $emp_table->find()->where(['md5(id)' => $tid ])->first() ;
    	$tchrid = $retrieve_empss['id'];
		        
        $sd = strtotime($strtdate." 12:00 AM");
        $ed = strtotime($enddate. " 11:59 PM");
		        
        $get_tdairy = $tdairy_table->find()->where(['teacher_id' => $tchrid, 'subject_id' => $subid, 'class_id' => $clsid])->toArray();
        
        $retrieve_emp = $emp_table->find()->where(['id' => $tchrid ])->first();
		$teachername = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
		
		$retrieve_session = $session_table->find()->where(['id' => $session_id ])->first();
		$session = $retrieve_session['startyear']."-".$retrieve_session['endyear'];
		
		$retrieve_scl = $scl_table->find()->where(['id' => $compid ])->first();
		$scllogo = '<img src="img/'. $retrieve_scl['comp_logo'].'" style="width:75px !important;">';
		
		$retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first();
		$subjname = $retrieve_subj['subject_name'];
		
		$retrieve_cls = $class_table->find()->where(['id' => $clsid ])->first();
		$clsname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']." (".$retrieve_cls['school_sections'] .")";
		
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
            if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '136') { $clslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '365') { $sublbl = $langlbl['title'] ; }
            if($langlbl['id'] == '1448') { $tchrlbl = $langlbl['title'] ; }
            if($langlbl['id'] == '2025') { $stdttymlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2026') { $endtymlbl = $langlbl['title'] ; }
            if($langlbl['id'] == '2109') { $lbl2109 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2193') { $lbl2193 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2194') { $lbl2194 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2195') { $lbl2195 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2196') { $lbl2196 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2197') { $lbl2197 = $langlbl['title'] ; } 
            
            
        } 
		$sclyear = 'School Year';
		
		$dairies = '';
		foreach($get_tdairy as $td)
        {
            if(($td['str_sd'] >= $sd) && ($td['str_ed'] <= $ed))
            {
    		    $dairies .= '<tr>
    			    <td>
    			        <table style="width: 100%; text-align:center;">
        			        <tr>
                				<td style="width: 21%; padding:0px 6px; border: 1px solid">
                                    '.date("M d, Y", strtotime($td["start_date"]))." - ".date("M d, Y", strtotime($td["end_date"])).'
                                </td>
                                <td style="width: 9%; padding:0px 6px; border: 1px solid">
                                    '.$td['hours'].'
                                </td>
                                <td style="width: 12%; padding:0px 6px; border: 1px solid">
                                    '.ucfirst($td['matieres_school']).'
                                </td>
                                <td style="width: 12%; padding:0px 6px; border: 1px solid">
                                    '.ucfirst($td['matieres']).'
                                </td>
                                <td style="width: 15%; padding:0px 6px; border: 1px solid">
                                    '.$td['obectifs_operational'].'
                                </td>
                                <td style="width: 15%; padding:0px 6px; border: 1px solid">
                                    '.ucfirst($td['matieres_v']).'
                                </td>
                                <td style="width: 16%; padding:0px 6px; border: 1px solid">
                                    '.$td['observation'].'
                                </td>
    			            </tr>
    		            </table>
    			    </td>
    			</tr>';
            }
		}
		
        $header = '<table style=" width: 100%;">
        		    <tbody>
        			    <tr>
        			        <td style="width: 100%;">
        			            <table style="width: 100%;  ">
            			        <tr>
                    			    <td  style="width: 25%; float:left; ">
                    			        <table style="width: 100%;  ">
                    			            <tr>
                    						    <th style="width: 100%; text-align:center;"><span> '.$scllogo.' </span></th>
                    						</tr>
                    					</table>
                    			    </td>
                    				<td style="width: 37%; float:left; text-align:center;">
                    					<table style="width: 100%;  ">
                    						<tr>
                						        <th style=" font-size: 14px;">
                						            <span><b>'.$clslbl.': </b></span><span>'.$clsname.'</span>
                						        </th>
                						    </tr>
                						    <tr>
                    						    <th style="width: 100%; text-align:center; font-size: 14px;"><span><b>'.$sublbl.': </b></span><span> '. $subjname.' </span></th>
                    						</tr>
                    					</table>
                    				</td>
                    				<td style="width: 38%;  float:left; text-align:right;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$tchrlbl.': </span>'.$teachername.'</th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:right;  font-size: 14px;"><span>'.$sclyear.': </span>'.$session.'</th>
                    						</tr>
                    					</table>
                    				</td>
        			            </tr>
    			            </table>
    			        </td>
        			</tr>
        			<tr>
        			    <td>
        			        <table style="width: 100%; font-size:17px; text-align:center; font-weight:bold">
        			            <tr>
                    				<td style="width: 21%; padding:6px; border: 1px solid">
                                        '.$lbl2193.'
                                    </td>
                                    <td style="width: 9%; padding:6px; border: 1px solid">
                                        '.$lbl2194.'
                                    </td>
                                    <td style="width: 12%; padding:6px; border: 1px solid">
                                        '.$lbl2195.' ('.$scllbl.')
                                    </td>
                                    <td style="width: 12%; padding:6px; border: 1px solid">
                                        '.$lbl2195.' ('.$lbl2109.')
                                    </td>
                                    <td style="width: 15%; padding:6px; border: 1px solid">
                                        '.$lbl2196.'
                                    </td>
                                    <td style="width: 15%; padding:6px; border: 1px solid">
                                        '.$lbl2197.'
                                    </td>
                                    <td style="width: 16%; padding:6px; border: 1px solid">
                                        Observations
                                    </td>
        			            </tr>
    			            </table>
        			    </td>
        			</tr>
        			'.$dairies.'
        		</tbody>
    		</table>';
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
	    
	    $dompdf = new Dompdf();
		$dompdf->loadHtml($viewpdf);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => false));
        exit(0);
            
    }
}

  


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
class ReportcardReportController extends AppController
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
            $cls = $this->request->data('class'); 
            $sessn = $this->request->data('session'); 
            $psr_table = TableRegistry::get('parentsignature_report');
            $stud_table = TableRegistry::get('student');
            $cls_table = TableRegistry::get('class');
            $sess_table = TableRegistry::get('session');
            
            $retrieve_stud = $stud_table->find()->where(['id' => $stuidid])->first() ;
            $studname = $retrieve_stud['l_name']." ".$retrieve_stud['f_name'];
            $retrieve_cls = $cls_table->find()->where(['id' => $cls])->first() ;
            $clsname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']." (".$retrieve_cls['school_sections'].")";
            $retrieve_sess = $sess_table->find()->where(['id' => $sessn])->first() ;
            $sessname = $retrieve_sess['startyear']."-".$retrieve_sess['endyear'];
            
            $data = '';
            $data .= '<div class="row" style="margin-top: 15px;">
                <div class="col-md-12" ><b>Student Name:</b> '. $studname .'</div>
                <div class="col-md-12" ><b>Student No. :</b> '. $retrieve_stud['adm_no'] .'</div>
                <div class="col-md-12" ><b>Class:</b> '. $clsname .'</div>
                <div class="col-md-12" ><b>Session:</b> '. $sessname .'</div>
                
                <div class="col-md-12" style="margin-top: 30px;">
                    <table class="table table-bordered" id="trackact_table"> 
                        <thead>
                            <tr>
                                <th>School Publish Date</th>
                                <th>E-signature Status</th>
                                <th>Parent Signed Date</th>
                            </tr>
                        </thead>
                        <tbody>';
    	    $retrieve_logs = $psr_table->find()->where(['student_id' => $stuidid, 'class_id' => $cls])->order(['id' => 'ASC'])->toArray() ;
    	    $getrows = '';
		    foreach($retrieve_logs as $value)
		    {
		        $esign = 'Signed';
		        if($value['signature'] == "")
		        {
		            $esign = 'Not Signed';
		        }
		        $esigndate = '';
		        if($value['parent_update_signature'] != "")
		        {
		            $esigndate = date("d-m-Y", $value['parent_update_signature']);
		        }
		        $getrows .= '<tr>
		            <td>
                        <span>'.date("d-m-Y", $value['school_publish_date']).'</span>
                    </td>
                    <td>
                        <span>'.$esign.'</span>
                    </td>
                    <td>
                        <span>'.$esigndate.'  </span>
                    </td>
                </tr>';
		    }
		    $data .= $getrows.'</tbody>
                    </table>
                </div>
            </div>';
    	    
            return $this->json($data);
    		    
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function index()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        
        $class_table = TableRegistry::get('class');
        $reportcard_table = TableRegistry::get('reportcard');
        $session_table = TableRegistry::get('session');
        $stud_table = TableRegistry::get('student');
        $retrieve_students = '';
        $clsids = [];
        if(!empty($compid)) 
        {
    		$session_id = $this->request->data('session');
    		$retrieve_classes = $class_table->find()->where(['school_id' => $compid, 'active' => 1])->toArray();
            $retrieve_session = $session_table->find()->toArray();
		
    		if(!empty($_POST))
    		{
    	        $clsid = $this->request->data('class');
    	        $retrieve_students = $stud_table->find()->select(['id', 'f_name', 'l_name', 'adm_no', 'email'])->where(['class' => $clsid, 'session_id' => $session_id ])->toArray() ;
    		    
    		    foreach($retrieve_students as $stud)
    		    {
    		        //echo $stud['id'];
        		    $retrieve_rpcd = $reportcard_table->find()->where(['classids' => $clsid, 'stuid' => $stud['id'], 'publish' => 1 ])->first() ;
        		    //print_r($retrieve_rpcd);
        		    if(empty($retrieve_rpcd))
        		    {
        		        $stud->publish_date = '';
        		    }
        		    else
        		    {
        		        $stud->publish_date = date("d-m-Y", $retrieve_rpcd->publish_date);
        		    }
        		    
    		    }
    		}
    		else
    		{
    		    $retrieve_students = '';
    		}
    		//die;
    		
    		$this->set('sessionid', $session_id);
    		$this->set("clsid", $clsid);
    		$this->set("sessiondtl", $retrieve_session);
    		$this->set("logdetails", $retrieve_students);
    		$this->set("classdetails", $retrieve_classes);
            $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
}

  


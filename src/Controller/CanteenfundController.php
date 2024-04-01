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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CanteenfundController extends AppController
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
        $stid = $this->Cookie->read('pid'); 
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $message_table = TableRegistry::get('parent_message');
        $company_table = TableRegistry::get('company');
        $sid =$this->request->session()->read('parent_id');
        //$compid = $this->request->session()->read('company_id');
        $sessionid = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($sid))
        {
    		$retrieve_stud = $student_table->find()->where(['session_id' => $sessionid, 'parent_id' => $sid])->toArray() ;
            $this->set("studlist", $retrieve_stud);
            $this->set("message_details", $retrieve_msg_table); 
            $this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
    
    public function getscl()
    {
        $studid = $this->request->data('studid');
        $sid = $this->request->data('studid');
        
        $canteenfee_table = TableRegistry::get('canteen_fund');
        $compid = $this->request->session()->read('company_id');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session'); 
        $student_table = TableRegistry::get('student'); 
        $comp_table = TableRegistry::get('company'); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_stud = $student_table->find()->where(['id' => $studid])->first() ;
        
        $retrieve_scl = $comp_table->find()->select(['comp_name'])->where(['id' => $retrieve_stud['school_id']])->first() ;
        $data['sclname'] = $retrieve_scl['comp_name'];
        
        $retrieve_canteenfee = $canteenfee_table->find()->where([ 'canteen_fund.student_id '=> $studid])->order(['id' => 'DESC'])->toArray() ;
            
        foreach($retrieve_canteenfee as $key =>$fee_c)
		{
		     $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $fee_c->class_id])->toArray();
			 $c_name = $retrieve_class[0]->c_name;
			 $c_section = $retrieve_class[0]->c_section;
			 $school_section = $retrieve_class[0]->school_sections;
			 
			 $retrieve_stud = $student_table->find()->select(['id' ,'adm_no','f_name', 'l_name'])->where(['id' => $fee_c->student_id])->toArray() ;
			 $student = $retrieve_stud[0]->l_name.'-'.$retrieve_stud[0]->f_name;
			 $adm = $retrieve_stud[0]->adm_no;
			 
			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_c->session_id])->toArray() ;
			 $start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
    	
            $listmsgs .= '<tr>
                <td class="width45">
                    <span>'.$adm.'</span>
                </td>
                <td class="width45">
                    <span>'.$student.'</span>
                </td>
                <td>
                    <span class="font-weight-bold">'. $c_name ."-".$c_section." (".$school_section.")" .'</span>
                </td>
                <td>
                    <span>'.$start_year.'</span>
                </td>
                <td>
                    <span>'. "$".$fee_c['amount'] .'</span>
                </td>
                <td>
                    <span>'. "$".$fee_c['daily_limit'] .'</span>
                </td>
                <td>
                    <span>'. ucfirst($fee_c['deposit_by']) .'</span>
                </td>
                <td>
                    <span>'. date("d-m-Y h:i A", $fee_c['created_date']) .'</span>
                </td>
            </tr>';
            
            $n++;
        }
        
        $data['list'] = $listmsgs;
        $data['studid'] = $studid;
        $data['dpdf'] = '<a href="canteenfund/downloadpdf/'.$studid.'" class="btn btn-success">Download Report</a>';
        return $this->json($data);
        
    }
    
    public function downloadpdf($studid)
    {
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
            if($langlbl['id'] == '130') { $lbl130 = $langlbl['title'] ; } 
            if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
            if($langlbl['id'] == '337') { $lbl337 = $langlbl['title'] ; } 
            if($langlbl['id'] == '321') { $lbl321 = $langlbl['title'] ; } 
            if($langlbl['id'] == '322') { $lbl322 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2237') { $lbl2237 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2238') { $lbl2238 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2338') { $lbl2338 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2339') { $lbl2339 = $langlbl['title'] ; } 
        } 
        
        $canteenfee_table = TableRegistry::get('canteen_fund');
        $class_table = TableRegistry::get('class');
        $session_table = TableRegistry::get('session'); 
        $student_table = TableRegistry::get('student'); 
        $comp_table = TableRegistry::get('company'); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_stud = $student_table->find()->where(['id' => $studid])->first() ;
        
        $rowdata = '';
        if(!empty($retrieve_stud)) {
            $retrieve_comp = $comp_table->find()->select(['comp_name', 'comp_logo'])->where(['id' => $retrieve_stud['school_id']])->first() ;
            $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_comp['comp_logo'].'" style="width:75px !important;">';
            $retrieve_class = $class_table->find()->where(['id' => $retrieve_stud['class']])->first() ;
            $studinfo = '<p>
                <b>'.$lbl147.': </b>'.$retrieve_stud['l_name'].' '.$retrieve_stud['f_name'].'<br/>
                <b>'.$lbl130.' : </b>'.$retrieve_stud['adm_no'].'<br/>
                <b>'.$lbl337.': </b>'.$retrieve_class['c_name'].'-'.$retrieve_class['c_section'].' ('.$retrieve_class['school_sections'].' <br/>
            </p>';
        }
	    
	    $retrieve_canteenfee = $canteenfee_table->find()->where([ 'canteen_fund.student_id '=> $studid])->order(['id' => 'DESC'])->toArray() ;
        $tamt = [];
        if(!empty($retrieve_canteenfee)):
        foreach($retrieve_canteenfee as $key =>$fee_c)
		{
			 $retrieve_start_year = $session_table->find()->select(['id' ,'startyear','endyear'])->where(['id' => $fee_c->session_id])->toArray() ;
			 $start_year = $retrieve_start_year[0]->startyear.'-'.$retrieve_start_year[0]->endyear;
    	
            $rowdata .= '<tr>
                <td style="border:1px solid #ccc">
                    <span>'.$start_year.'</span>
                </td>
                <td style="border:1px solid #ccc; text-align:right;">
                    <span>'. "$".$fee_c['amount'] .'</span>
                </td>
                <td style="border:1px solid #ccc; text-align:right;">
                    <span>'. "$".$fee_c['daily_limit'] .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'. ucfirst($fee_c['deposit_by']) .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span>'. date("d-m-Y h:i A", $fee_c['created_date']) .'</span>
                </td>
            </tr>';
            $tamt[] = $fee_c['amount'];
            $n++;
        }
        $ttlamt = array_sum($tamt);
        
        $rowdata .= '<tr>
                <td style="border:1px solid #ccc">
                    <span>Total Deposit Amount</span>
                </td>
                <td style="border:1px solid #ccc; text-align:right;">
                    <span>'. "$".$ttlamt .'</span>
                </td>
                <td style="border:1px solid #ccc">
                    <span> </span>
                </td>
                <td style="border:1px solid #ccc">
                    <span> </span>
                </td>
                <td style="border:1px solid #ccc">
                    <span> </span>
                </td>
            </tr>';
        endif;
	    $header = '<table style=" width: 100%;">
        		    <tbody>
        			    <tr>
        			        <td  style="width: 100%;">
        			            <table style="width: 100%;  ">
        			            
            			        <tr>
                    			    <td  style="width: 33%; float:left; ">
                    			        <table style="width: 100%;  ">
                    			            <tr>
                    						    <th style="width: 100%; text-align:center;"><span> '.$school_logo.' </span></th>
                    						</tr>
                    						<tr>
                    						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($retrieve_comp['comp_name']).'</th>
                    						</tr>
                    					</table>
                    			    </td>
                    				<td style="width: 66%; float:left; text-align:center;">
                    					<table style="width: 100%;  ">
                    						<tr>
                    						    <th style="width: 100%; text-align:left; font-size: 16px;">'.$studinfo .'</th>
                    						</tr>
                    					</table>
                    				</td>
        			            </tr>
        			            </table>
        			        </td>
        			</tr>
        			<tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">'. $lbl321 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl322 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2237 .'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2338.'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2238 .'</th>
                                    </tr>
                                </thead>
                                <tbody style="border:1px solid #ccc"> 
                                    '.$rowdata.'
                                </tbody>
        					</table>
            			</td>
        			</tr>
        		</tbody>
        		</table>';
	
	    $title =  "Downloadfundreport". $retrieve_stud['adm_no'];
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> 
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;">'.$lbl2339.'</p>
	    <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);
    }
}

  


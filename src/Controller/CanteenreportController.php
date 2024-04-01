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
class CanteenreportController extends AppController
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
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function vendorreport()
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $retrieve_vendor = $vendor_table->find()->where(['status' => 1])->toArray(); 
        $this->set("vendor_details", $retrieve_vendor); 
        $vndrid = '';
        $str_sdate = '';
        $str_edate = '';
        $downloadpdf = '';
        if(!empty($_POST))
        {
            $vndrid = $this->request->data('vendor');
            $sdate = $this->request->data('startdate');
            $edate = $this->request->data('enddate');
            
            $str_sdate = strtotime($sdate." 12:01 AM");
            $str_edate = strtotime($edate." 11:59 PM");
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; } 
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
                if($langlbl['id'] == '2350') { $lbl2350 = $langlbl['title'] ; } 
            } 
            $downloadpdf = '<a href="https://you-me-globaleducation.org/school/Canteenreport/downloadvendorreport/'.$vndrid.'/'.$str_sdate.'/'.$str_edate.'" class="btn btn-success">'.$lbl2350.'</a>';
            
            $retrieve_cso = $cso_table->find()->select(['company.comp_name', 'date', 'id', 'order_status' ])->join([
                'company' => 
                [
                    'table' => 'company',
                    'type' => 'LEFT',
                    'conditions' => 'company.id = canteen_student_order.school_id'
                ]
            ])->where(['vendor_id' => $vndrid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate  ])->group(['canteen_student_order.date'])->order(['str_date' => 'DESC'])->toArray(); 
            
            foreach($retrieve_cso as $cso)
            {
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $ttlamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'quantity', 'total_amount'])->where(['vendor_id' => $vndrid, 'date' => $cso['date'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $ttlamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->ttlamt = array_sum($ttlamt);
            }
            
        }
        $this->set("downloadpdf", $downloadpdf); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("startdate", $str_sdate); 
        $this->set("enddate", $str_edate); 
        $this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadvendorreport($vndrid, $ssdate, $sedate)
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
        
        $vendor_table = TableRegistry::get('canteen_vendor');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
        
            
            $str_sdate = $ssdate;
            $str_edate = $sedate;
            $retrieve_vendor = $vendor_table->find()->where(['status' => 1, 'id' => $vndrid])->first(); 
            
            $retrieve_cso = $cso_table->find()->select(['company.comp_name', 'date', 'id', 'order_status'])->join([
                'company' => 
                [
                    'table' => 'company',
                    'type' => 'LEFT',
                    'conditions' => 'company.id = canteen_student_order.school_id'
                ]
            ])->where(['vendor_id' => $vndrid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.date'])->toArray(); 
            
            foreach($retrieve_cso as $cso)
            {
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $ttlamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['vendor_id' => $vndrid, 'date' => $cso['date'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $ttlamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                        $ttlamt[] = 0;
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->ttlamt = array_sum($ttlamt);
            }
        $rowdata = '';
        $undel = [];
        $cncld = [];
        $pendn = [];
        $delvr = [];
        $tdamt = [];
        if(!empty($retrieve_cso)) {
            foreach($retrieve_cso as $value)
            {
                $rowdata .= '<tr>
                    <td style="border:1px solid #ccc">'. $value['company']['comp_name'] .'</td>
                    <td style="border:1px solid #ccc">'. $value['date'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->undelivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->canclld .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->pendng .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->delivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".$value->ttlamt .'</td>
                </tr>';
                
                $undel[] = $value->undelivr;
                $cncld[] = $value->canclld;
                $pendn[] = $value->pendng;
                $delvr[] = $value->delivr;
                $tdamt[] = $value->ttlamt;
            } 
            
            $rowdata .= '<tr>
                    <td style="border:1px solid #ccc" colspan="2"><b>Total</b></td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($undel) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($cncld).'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($pendn) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($delvr) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".array_sum($tdamt) .'</td>
                </tr>';
        }
        
        /*$schoolid = $this->request->session()->read('company_id');
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
        $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';*/
	    $n =1;
	    
	    foreach($retrieve_langlabel as $langlbl) { 
            if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
        } 
       
	    $header = '<table style=" width: 100%;">
    		        <tbody>
        		    <tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">School Name</th>   
                                        <th style="border:1px solid #ccc">Date</th>
                                        <th style="border:1px solid #ccc">Food Quantity Undeliver </th>
                                        <th style="border:1px solid #ccc">Food Quantity Cancel </th>
                                        <th style="border:1px solid #ccc">Food Quantity Pending </th>
                                        <th style="border:1px solid #ccc">Food Quantity Sold</th>
                                        <th style="border:1px solid #ccc">Total amount (food deliver)</th> 
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
	
	    $title =  "Vendorreport_". $order_no;
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> 
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;"> Vendor Report - '.$retrieve_vendor['vendor_company'].' (Date: '.date('d-m-Y', $ssdate).' to '.date('d-m-Y', $sedate).') </p>
	    <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);
    }
    
    public function schoolreport()
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $retrieve_school = $comp_table->find()->where(['status' => 1])->toArray(); 
        $this->set("school_details", $retrieve_school); 
        $vndrid = '';
        $str_sdate = '';
        $str_edate = '';
        $downloadpdf = '';
        $sclid ='';
        $get_vendor = '';
        if(!empty($_POST))
        {
            $sclid = $this->request->data('school');
            $vndrid = $this->request->data['vendor'];
            $sdate = $this->request->data('startdate');
            $edate = $this->request->data('enddate');
            
            $cv_table = TableRegistry::get('vendor_assign_school');
        
            $getcv_data = $cv_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $sclid .'\',vendor_assign_school.school_ids)')))->toArray();
            $vendorss = [];
            if(!empty($getcv_data))
            {
                foreach($getcv_data as $cv_data)
                {
                    $get_vendor = $vendor_table->find()->where(['id' => $cv_data['vendor_id'] ])->first();
                    $vendorcomp[] = $get_vendor['vendor_company'];
                    $vendoridss[] = $get_vendor['id'];
                }
                $vendorss['vendor_company'] = $vendorcomp;
                $vendorss['id'] = $vendoridss;
            }
            //print_r($vendorss); die;
            $str_sdate = strtotime($sdate." 12:01 AM");
            $str_edate = strtotime($edate." 11:59 PM");
            
            if(in_array("all", $vndrid))
            { 
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                    ]
                ])->where(['school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            
                $vndrs = "all";
            }
            else
            {  
                $vndrs = implode(",", $vndrid);
                
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.vendor_id IN' => $this->request->data['vendor'], 'school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate  ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            }
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; } 
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
                if($langlbl['id'] == '2350') { $lbl2350 = $langlbl['title'] ; } 
            } 
            $downloadpdf = '<a href="https://you-me-globaleducation.org/school/Canteenreport/downloadschoolreport/'.$vndrs.'/'.$sclid.'/'.$str_sdate.'/'.$str_edate.'" class="btn btn-success">'.$lbl2350.'</a>';
            
            
            foreach($retrieve_cso as $cso)
            { 
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $sclid, 'date' => $cso['date'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->tammt = array_sum($tamt);
            }
            
        }
        $this->set("vendor_details", $vendorss); 
        $this->set("downloadpdf", $downloadpdf); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("startdate", $str_sdate); 
        $this->set("enddate", $str_edate); 
        $this->set("compid", $sclid); 
        $this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadschoolreport($vndrids, $sclid, $ssdate, $sedate)
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
        
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $retrieve_school = $comp_table->find()->where(['status' => 1, 'id' => $sclid])->first(); 
            
        $vndrid = explode(",", $vndrids);
        $str_sdate = $ssdate;
        $str_edate = $sedate;
        if(in_array("all", $vndrid))
        {
            $retrieve_vendorss = $vendor_table->find()->where(['status' => 1])->toArray(); 
        }
        else
        {
            $retrieve_vendorss = $vendor_table->find()->where(['id IN' => $vndrid])->toArray(); 
        }
        $vendname = [];
        foreach($retrieve_vendorss as $vendr)
        {
            $vendname[] = $vendr['vendor_company'];
        }
        
        $vendrall = implode(",", $vendname); 
            
            if(in_array("all", $vndrid))
            { 
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                    ]
                ])->where(['school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            
                $vndrs = "all";
            }
            else
            {  
                $vndrs = implode(",", $vndrid);
                
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.vendor_id IN' => $this->request->data['vendor'], 'school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate  ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            }
           
            foreach($retrieve_cso as $cso)
            { 
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $sclid, 'date' => $cso['date'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->tammt = array_sum($tamt);
            }
        $rowdata = '';
        $undel = [];
        $cncld = [];
        $pendn = [];
        $delvr = [];
        $tdamt = [];
        if(!empty($retrieve_cso)) {
            foreach($retrieve_cso as $value)
            {
                $rowdata .= '<tr>
                    <td style="border:1px solid #ccc">'. $value['canteen_vendor']['vendor_company'] .'</td>
                    <td style="border:1px solid #ccc">'. $value['date'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->undelivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->canclld .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->pendng .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->delivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".$value->tammt .'</td>
                </tr>';
                
                $undel[] = $value->undelivr;
                $cncld[] = $value->canclld;
                $pendn[] = $value->pendng;
                $delvr[] = $value->delivr;
                $tdamt[] = $value->tammt;
            } 
            
            $rowdata .= '<tr>
                    <td style="border:1px solid #ccc" colspan="2"><b>Total</b></td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($undel) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($cncld).'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($pendn) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($delvr) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".array_sum($tdamt) .'</td>
                </tr>';
        }
        
        
        //$retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $sclid])->toArray();
        $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    
	    foreach($retrieve_langlabel as $langlbl) { 
            if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
            
        } 
       
	    $header = '<table style=" width: 100%;">
    		        <tbody>
    		        <tr>
    		            <td>
    		                <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                	    <td style=" width:30%; text-align:center !important; float:left;"> '.$school_logo.' </td>
                                	    <td style=" width:70%; text-align:center !important; float:right; font-weight:bold; font-size:16px;"> Vendor Report - '.$vendrall.'<br> (Date: '.date('d-m-Y', $ssdate).' to '.date('d-m-Y', $sedate).') </td>
	                                </tr>
	                           </thead>
	                        </table>
    		            </td>
    		        </tr>
        		    <tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">Vendor Company</th>   
                                        <th style="border:1px solid #ccc">Date</th>
                                        <th style="border:1px solid #ccc">Food Quantity Undeliver </th>
                                        <th style="border:1px solid #ccc">Food Quantity Cancel </th>
                                        <th style="border:1px solid #ccc">Food Quantity Pending </th>
                                        <th style="border:1px solid #ccc">Food Quantity Sold</th>
                                        <th style="border:1px solid #ccc">Total amount (food deliver)</th> 
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
	
	    $title =  "Vendorreport_". $order_no;
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);
    }
    
    public function viewscldtl($sclid, $vndrid, $date)
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $retrieve_school = $comp_table->find()->where(['status' => 1,'id' => $sclid])->first(); 
        $retrieve_vendor = $vendor_table->find()->where(['status' => 1,'id' => $vndrid])->first(); 
        
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'food_item.food_img', 'date', 'id', 'order_status', 'vendor_id', 'total_amount', 'quantity', 'food_amount' ])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ]
        ])->where(['canteen_student_order.vendor_id ' => $vndrid, 'school_id' => $sclid, 'date' => $date  ])->group(['food_id','order_status'])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
        $pend = [];
        $deli = [];
        $cncl = [];
        $udel = [];
        $tamt = [];    
        foreach($retrieve_cso as $cso)
        { 
            if($cso['order_status'] == 0)
            {
                $pend[] = $cso['quantity'];
            }
            elseif($cso['order_status'] == 1)
            {
                $deli[] = $cso['quantity'];
                $tamt[] = $cso['total_amount'];
            }
            elseif($cso['order_status'] == 2)
            {
                $cncl[] = $cso['quantity'];
            }
            else
            {
                $udel[] = $cso['quantity'];
            }
            
            $cso->pendng = array_sum($pend);
            $cso->delivr = array_sum($deli);
            $cso->undelivr = array_sum($udel);
            $cso->canclld = array_sum($cncl);
            $cso->tammt = array_sum($tamt);
        }
        $this->set("vendor_details", $retrieve_vendor); 
        $this->set("school_details", $retrieve_school); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("sdate", $date); 
        $this->set("compid", $sclid); 
        $this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function parentreport()
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $student_table = TableRegistry::get('student');
        $sid =$this->request->session()->read('parent_id');
        $sessionid = $this->request->session()->read('session_id');
        
		$retrieve_stud = $student_table->find()->where(['session_id' => $sessionid, 'parent_id' => $sid])->toArray() ;
        $this->set("studlist", $retrieve_stud);
        $studid = '';
        $vndrid = '';
        $str_sdate = '';
        $str_edate = '';
        $downloadpdf = '';
        $sclid ='';
        if(!empty($_POST))
        {
            $studid = $this->request->data('student');
            $sdate = $this->request->data('startdate');
            $edate = $this->request->data('enddate');
            
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; } 
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
                if($langlbl['id'] == '2350') { $lbl2350 = $langlbl['title'] ; } 
            } 
            
            $retrieve_studs = $student_table->find()->where(['id' => $studid])->first() ;
            $retrieve_scl = $comp_table->find()->where([ 'id' => $retrieve_studs['school_id'] ])->first() ;
            $sclname = $retrieve_scl['comp_name'];
            
            $str_sdate = strtotime($sdate." 12:01 AM");
            $str_edate = strtotime($edate." 11:59 PM");
             
            $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id', 'order_no', 'remark' ])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                ]
            ])->where(['student_id' => $studid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.order_no'])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
            $downloadpdf = '<a href="https://you-me-globaleducation.org/school/Canteenreport/downloadparentreport/'.$studid.'/'.$str_sdate.'/'.$str_edate.'" class="btn btn-success">'.$lbl2350.'</a>';
            //echo "fv"; die;
            foreach($retrieve_cso as $cso)
            { 
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['order_no' => $cso['order_no'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->tammt = array_sum($tamt);
            }
            
        }
        $this->set("downloadpdf", $downloadpdf); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("startdate", $str_sdate); 
        $this->set("enddate", $str_edate); 
        $this->set("studid", $studid); 
        $this->set("sclname", $sclname);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function viewparntdtl($orderno)
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $stud_table = TableRegistry::get('student');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'food_item.food_img', 'canteen_vendor.vendor_company', 'date', 'school_id', 'student_id', 'id', 'order_status', 'vendor_id', 'total_amount', 'quantity', 'food_amount' ])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ],
            'canteen_vendor' => 
            [
                'table' => 'canteen_vendor',
                'type' => 'LEFT',
                'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
            ]
        ])->where(['canteen_student_order.order_no ' => $orderno ])->group(['food_id','order_status'])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
        $retrieve_school = $comp_table->find()->where(['status' => 1,'id' => $retrieve_cso[0]['school_id'] ])->first(); 
        $retrieve_student = $stud_table->find()->where(['status' => 1,'id' => $retrieve_cso[0]['student_id'] ])->first(); 
        $date = $retrieve_cso[0]['date'];
        $pend = [];
        $deli = [];
        $cncl = [];
        $udel = [];
        $tamt = [];    
        foreach($retrieve_cso as $cso)
        { 
            if($cso['order_status'] == 0)
            {
                $pend[] = $cso['quantity'];
            }
            elseif($cso['order_status'] == 1)
            {
                $deli[] = $cso['quantity'];
                $tamt[] = $cso['total_amount'];
            }
            elseif($cso['order_status'] == 2)
            {
                $cncl[] = $cso['quantity'];
            }
            else
            {
                $udel[] = $cso['quantity'];
            }
            
            $cso->pendng = array_sum($pend);
            $cso->delivr = array_sum($deli);
            $cso->undelivr = array_sum($udel);
            $cso->canclld = array_sum($cncl);
            $cso->tammt = array_sum($tamt);
        }
        $this->set("school_details", $retrieve_school); 
        $this->set("student_details", $retrieve_student); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("sdate", $date); 
        $this->set("orderno", $orderno); 
        //$this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadparentreport($studid, $ssdate, $esdate)
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $sid =$this->request->session()->read('parent_id');
        $sessionid = $this->request->session()->read('session_id');
       
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
            
            if($langlbl['id'] == '2334') { $lbl2334 = $langlbl['title'] ; }
            if($langlbl['id'] == '2340') { $lbl2340 = $langlbl['title'] ; }
            if($langlbl['id'] == '2341') { $lbl2341 = $langlbl['title'] ; }
            if($langlbl['id'] == '2342') { $lbl2342 = $langlbl['title'] ; }
            if($langlbl['id'] == '2343') { $lbl2343 = $langlbl['title'] ; }
            if($langlbl['id'] == '2344') { $lbl2344 = $langlbl['title'] ; }
            if($langlbl['id'] == '2345') { $lbl2345 = $langlbl['title'] ; }
            if($langlbl['id'] == '2346') { $lbl2346 = $langlbl['title'] ; }
            if($langlbl['id'] == '2347') { $lbl2347 = $langlbl['title'] ; }
            if($langlbl['id'] == '2348') { $lbl2348 = $langlbl['title'] ; }
            if($langlbl['id'] == '2349') { $lbl2349 = $langlbl['title'] ; }
        } 
        
            $retrieve_studs = $student_table->find()->where(['id' => $studid])->first() ;
            $retrieve_scl = $comp_table->find()->where([ 'id' => $retrieve_studs['school_id'] ])->first() ;
            $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_scl['comp_logo'].'" style="width:75px !important;">';
            $sclname = $retrieve_scl['comp_name'];
            $rowdata = '';
            $str_sdate = $ssdate;
            $str_edate = $esdate;
            
            if(!empty($retrieve_studs)) {
                
                $retrieve_class = $class_table->find()->where(['id' => $retrieve_studs['class']])->first() ;
                $studinfo = '<p>
                    <b>'.$lbl147.': </b>'.$retrieve_studs['l_name'].' '.$retrieve_studs['f_name'].'<br/>
                    <b>'.$lbl130.' : </b>'.$retrieve_studs['adm_no'].'<br/>
                    <b>'.$lbl337.': </b>'.$retrieve_class['c_name'].'-'.$retrieve_class['c_section'].' ('.$retrieve_class['school_sections'].' <br/>
                    <b>Date: </b>'.date('d-m-Y', $ssdate).' to '.date('d-m-Y', $esdate).'
                </p>';
            }
             
            $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id', 'order_no', 'remark' ])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                ]
            ])->where(['student_id' => $studid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.order_no'])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
            foreach($retrieve_cso as $cso)
            { 
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = [];
                $fooditem = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity', 'food_item.food_name'])->join([
                    'food_item' => 
                        [
                            'table' => 'food_item',
                            'type' => 'LEFT',
                            'conditions' => 'food_item.id = canteen_student_order.food_id'
                        ],
                    ])->where(['order_no' => $cso['order_no'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                    $fooditem [] = $cso1['food_item']['food_name'];
                }
                $pendng[] = array_sum($pend);
                $delivr[] = array_sum($deli);
                $undelivr[] = array_sum($udel);
                $canclld[] = array_sum($cncl);
                $tammt[] = array_sum($tamt);
                $fooditems = implode(",", $fooditem);
                
                $rowdata .= '<tr><td style="border:1px solid #ccc; text-align:center">'. $cso['order_no'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $cso['canteen_vendor']['vendor_company'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $cso['date'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'.$fooditems.'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($udel) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($cncl) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($pend) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($deli) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".array_sum($tamt) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $cso->remark .'</td></tr>';
            }
            
            $rowdata .= '<tr><td colspan="4" style="border:1px solid #ccc; text-align:center">Total</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($undelivr) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($canclld) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($pendng) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($delivr) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".array_sum($tammt) .'</td>
                    <td style="border:1px solid #ccc; text-align:center"></td></tr>';
            
        $header = '<table style=" width: 100%;">
	        <tbody>
	        <tr>
	            <td>
	                <table style="width: 100%; border:1px solid #ccc">
						<thead>
                            <tr>
                        	    <td  style="width: 33%; float:left; ">
                			        <table style="width: 100%; ">
                			            <tr>
                						    <th style="width: 100%; text-align:center;"><span> '.$school_logo.' </span></th>
                						</tr>
                						<tr>
                						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($sclname).'</th>
                						</tr>
                					</table>
                			    </td>
                			    <td style="width: 66%; float:left; text-align:center;">
                					<table style="width: 100%; ">
                						<tr>
                						    <th style="width: 100%; text-align:left; font-size: 16px;">'.$studinfo .'</th>
                						</tr>
                					</table>
                				</td>
                        	</tr>
                       </thead>
                    </table>
	            </td>
	        </tr>
		    <tr>
    			<td style="width: 100%;">
    			    <table style="width: 100%; border:1px solid #ccc">
						<thead>
                            <tr>
                                <th style="border:1px solid #ccc">'. $lbl2342 .'</th>
                                <th style="border:1px solid #ccc">'. $lbl2343 .'</th>   
                                <th>Date</th>
                                <th style="border:1px solid #ccc">'. $lbl2344 .'</th>
                                <th style="border:1px solid #ccc">'. $lbl2345 .'</th>
                                <th style="border:1px solid #ccc">'. $lbl2346 .'</th>
                                <th style="border:1px solid #ccc">'. $lbl2347 .'</th>
                                <th style="border:1px solid #ccc">'. $lbl2348 .'</th> 
                                <th style="border:1px solid #ccc">'. $lbl2349 .'</th>
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
            
        $title =  "Studentreport_". $retrieve_studs['adm_no'];
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);    
        
    }
    
    public function schoolvendorreport()
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        
        $cv_table = TableRegistry::get('vendor_assign_school');
        
        $getcv_data = $cv_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $compid .'\',vendor_assign_school.school_ids)')))->toArray();
        $vendorss = [];
        if(!empty($getcv_data))
        {
            foreach($getcv_data as $cv_data)
            {
                $get_vendor = $vendor_table->find()->where(['id' => $cv_data['vendor_id'] ])->first();
                $vendorcomp[] = $get_vendor['vendor_company'];
                $vendoridss[] = $get_vendor['id'];
            }
            $vendorss['vendor_company'] = $vendorcomp;
            $vendorss['id'] = $vendoridss;
        }
      
        $vndrid = '';
        $str_sdate = '';
        $str_edate = '';
        $downloadpdf = '';
        if(!empty($_POST))
        {
            $sclid = $compid;
            $vndrid = $this->request->data['vendor'];
            $sdate = $this->request->data('startdate');
            $edate = $this->request->data('enddate');
            
            $str_sdate = strtotime($sdate." 12:01 AM");
            $str_edate = strtotime($edate." 11:59 PM");
            
            if(in_array("all", $vndrid))
            { 
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                    ]
                ])->where(['school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            
                $vndrs = "all";
            }
            else
            {  
                $vndrs = implode(",", $vndrid);
                
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.vendor_id IN' => $this->request->data['vendor'], 'school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate  ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            }
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; } 
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
                if($langlbl['id'] == '2350') { $lbl2350 = $langlbl['title'] ; } 
            } 
            $downloadpdf = '<a href="https://you-me-globaleducation.org/school/Canteenreport/downloadschoolvreport/'.$vndrs.'/'.$str_sdate.'/'.$str_edate.'" class="btn btn-success">'.$lbl2350.'</a>';
            
            
            foreach($retrieve_cso as $cso)
            { 
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $sclid, 'date' => $cso['date'] ])->toArray();
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = []; 
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->tammt = array_sum($tamt);
            }
            
        }
        $this->set("vendor_details", $vendorss); 
        $this->set("downloadpdf", $downloadpdf); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("startdate", $str_sdate); 
        $this->set("enddate", $str_edate); 
        $this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('user');
    }
    
    public function downloadschoolvreport($vndrids, $ssdate, $sedate)
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
        
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->request->session()->read('company_id');
        $retrieve_school = $comp_table->find()->where(['status' => 1, 'id' => $sclid])->first(); 
            
        $vndrid = explode(",", $vndrids);
        $str_sdate = $ssdate;
        $str_edate = $sedate;
        if(in_array("all", $vndrid))
        {
            $retrieve_vendorss = $vendor_table->find()->where(['status' => 1])->toArray(); 
        }
        else
        {
            $retrieve_vendorss = $vendor_table->find()->where(['id IN' => $vndrid])->toArray(); 
        }
        $vendname = [];
        foreach($retrieve_vendorss as $vendr)
        {
            $vendname[] = $vendr['vendor_company'];
        }
        
        $vendrall = implode(",", $vendname); 
            
            if(in_array("all", $vndrid))
            { 
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id' 
                    ]
                ])->where(['school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            
                $vndrs = "all";
            }
            else
            {  
                $vndrs = implode(",", $vndrid);
                
                $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'date', 'id', 'order_status', 'vendor_id' ])->join([
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.vendor_id IN' => $vndrid, 'school_id' => $sclid, 'str_date >=' => $str_sdate, 'str_date <=' => $str_edate  ])->group(['canteen_student_order.date', 'canteen_student_order.vendor_id'])->order(['str_date' => 'DESC'])->toArray(); 
            }
           
            foreach($retrieve_cso as $cso)
            { 
                $pend = [];
                $deli = [];
                $cncl = [];
                $udel = [];
                $tamt = [];
                $retrieve_cso1 = $cso_table->find()->select(['date', 'id', 'order_status', 'total_amount', 'quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $sclid, 'date' => $cso['date'] ])->toArray();
                foreach($retrieve_cso1 as $cso1)
                {
                    if($cso1['order_status'] == 0)
                    {
                        $pend[] = $cso1['quantity'];
                    }
                    elseif($cso1['order_status'] == 1)
                    {
                        $deli[] = $cso1['quantity'];
                        $tamt[] = $cso1['total_amount'];
                    }
                    elseif($cso1['order_status'] == 2)
                    {
                        $cncl[] = $cso1['quantity'];
                    }
                    else
                    {
                        $udel[] = $cso1['quantity'];
                    }
                }
                $cso->pendng = array_sum($pend);
                $cso->delivr = array_sum($deli);
                $cso->undelivr = array_sum($udel);
                $cso->canclld = array_sum($cncl);
                $cso->tammt = array_sum($tamt);
            }
        $rowdata = '';
        $undel = [];
        $cncld = [];
        $pendn = [];
        $delvr = [];
        $tdamt = [];
        if(!empty($retrieve_cso)) {
            foreach($retrieve_cso as $value)
            {
                $rowdata .= '<tr>
                    <td style="border:1px solid #ccc">'. $value['canteen_vendor']['vendor_company'] .'</td>
                    <td style="border:1px solid #ccc">'. $value['date'] .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->undelivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->canclld .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->pendng .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. $value->delivr .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".$value->tammt .'</td>
                </tr>';
                
                $undel[] = $value->undelivr;
                $cncld[] = $value->canclld;
                $pendn[] = $value->pendng;
                $delvr[] = $value->delivr;
                $tdamt[] = $value->tammt;
            } 
            
            $rowdata .= '<tr>
                    <td style="border:1px solid #ccc" colspan="2"><b>Total</b></td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($undel) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($cncld).'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($pendn) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. array_sum($delvr) .'</td>
                    <td style="border:1px solid #ccc; text-align:center">'. "$".array_sum($tdamt) .'</td>
                </tr>';
        }
        
        
        //$retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $sclid])->toArray();
        $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    
	    foreach($retrieve_langlabel as $langlbl) { 
            if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
            
            if($langlbl['id'] == '2343') { $lbl2343 = $langlbl['title'] ; }
            if($langlbl['id'] == '2344') { $lbl2344 = $langlbl['title'] ; }
            if($langlbl['id'] == '2345') { $lbl2345 = $langlbl['title'] ; }
            if($langlbl['id'] == '2346') { $lbl2346 = $langlbl['title'] ; }
            if($langlbl['id'] == '2347') { $lbl2347 = $langlbl['title'] ; }
            if($langlbl['id'] == '2348') { $lbl2348 = $langlbl['title'] ; }
            if($langlbl['id'] == '2349') { $lbl2349 = $langlbl['title'] ; }
            
            if($langlbl['id'] == '2355') { $lbl2355 = $langlbl['title'] ; }
        } 
       
	    $header = '<table style=" width: 100%;">
    		        <tbody>
    		        <tr>
    		            <td>
    		                <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                	    <td style=" width:30%; text-align:center !important; float:left;"> '.$school_logo.' </td>
                                	    <td style=" width:70%; text-align:center !important; float:right; font-weight:bold; font-size:16px;"> '.$lbl2355.' - '.$vendrall.'<br> (Date: '.date('d-m-Y', $ssdate).' to '.date('d-m-Y', $sedate).') </td>
	                                </tr>
	                           </thead>
	                        </table>
    		            </td>
    		        </tr>
        		    <tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%;">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">'. $lbl2343 .'</th>   
                                        <th style="border:1px solid #ccc">Date</th>
                                        <th style="border:1px solid #ccc">'. $lbl2344 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2345 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2346 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2347 .'</th>
                                        <th style="border:1px solid #ccc">'. $lbl2348 .'</th>
                                        
                                    </tr>
                                </thead>
                                <tbody> 
                                    '.$rowdata.'
                                </tbody>
        					</table>
            			</td>
        			</tr>
        		</tbody>
        		</table>';
	
	    $title =  "Vendorreport_". $order_no;
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;"> <p>'.$header.'</p></div>';
	    //print_r($viewpdf); die;
	    $dompdf = new Dompdf();
	    $options = $dompdf->getOptions();
		$options->setIsHtml5ParserEnabled(true);
		$options->set('isRemoteEnabled', true);
    	$dompdf->setOptions($options);
    	
		$dompdf->loadHtml($viewpdf);	
		
		$dompdf->setPaper('A4', 'Landscape');
		$dompdf->render();
		$dompdf->stream($title.".pdf", array("Attachment" => true));

        exit(0);
    }
    
    public function viewsclvdtl($vndrid, $date)
    {
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $sclid = $this->request->session()->read('company_id');

        $retrieve_school = $comp_table->find()->where(['status' => 1,'id' => $sclid])->first(); 
        $retrieve_vendor = $vendor_table->find()->where(['status' => 1,'id' => $vndrid])->first(); 
        
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'food_item.food_img', 'date', 'id', 'order_status', 'vendor_id', 'total_amount', 'quantity', 'food_amount' ])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ]
        ])->where(['canteen_student_order.vendor_id ' => $vndrid, 'school_id' => $sclid, 'date' => $date  ])->group(['food_id'])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
        $pend = [];
        $deli = [];
        $cncl = [];
        $udel = [];
        $tamt = [];    
        foreach($retrieve_cso as $cso)
        { 
            if($cso['order_status'] == 0)
            {
                $pend[] = $cso['quantity'];
            }
            elseif($cso['order_status'] == 1)
            {
                $deli[] = $cso['quantity'];
                $tamt[] = $cso['total_amount'];
            }
            elseif($cso['order_status'] == 2)
            {
                $cncl[] = $cso['quantity'];
            }
            else
            {
                $udel[] = $cso['quantity'];
            }
            
            $cso->pendng = array_sum($pend);
            $cso->delivr = array_sum($deli);
            $cso->undelivr = array_sum($udel);
            $cso->canclld = array_sum($cncl);
            $cso->tammt = array_sum($tamt);
        }
        $this->set("vendor_details", $retrieve_vendor); 
        $this->set("school_details", $retrieve_school); 
        $this->set("cso_details", $retrieve_cso); 
        $this->set("sdate", $date); 
        $this->set("compid", $sclid); 
        $this->set("vendrid", $vndrid);
        $this->viewBuilder()->setLayout('user');
    }
            
}

  


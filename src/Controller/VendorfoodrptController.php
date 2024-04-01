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
class VendorfoodrptController extends AppController
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
        $vendor_table = TableRegistry::get('canteen_vendor');
        $comp_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $fss_table = TableRegistry::get('food_serve_scl');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());

        $retrieve_school = $comp_table->find()->where(['status' => 1])->toArray(); 
        $this->set("school_details", $retrieve_school); 
        
        
        if(!empty($_POST))
        {
            $edate = date("d-m-Y", strtotime($this->request->data('enddate')));
            $sdate = date("d-m-Y", strtotime($this->request->data('startdate')));
            
            $edates = strtotime($this->request->data('enddate')." 11:59 PM");
            $sdates = strtotime($this->request->data('startdate')." 12:01 AM");
            
            $sclid = $this->request->data('school');
            $vndrids = $this->request->data['vendor'];
            
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
            
            $vndrurl = implode(",",$vndrids);
            if(in_array("all", $vndrids))
            {
                $vndrurl = "all";
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'canteen_vendor.vendor_company', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
                    'food_item' => 
                    [
                        'table' => 'food_item',
                        'type' => 'LEFT',
                        'conditions' => 'food_item.id = canteen_student_order.food_id'
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = canteen_student_order.school_id'
                    ],
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
            }
            else
            {
                $vendorid = $vndrids;
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'canteen_vendor.vendor_company', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'vendor_id', 'timings'])->join([
                    'food_item' => 
                    [
                        'table' => 'food_item',
                        'type' => 'LEFT',
                        'conditions' => 'food_item.id = canteen_student_order.food_id'
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = canteen_student_order.school_id'
                    ],
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.vendor_id IN' => $vndrids, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
            }
            foreach($retrieve_cso as $cso)
            {
                $quantityQuery = $cso_table->find()->select(['quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $cso['school_id'], 'food_id' => $cso['food_id'], ' date ' =>  $cso['date']])->toArray(); 
                $quant = [];
                foreach($quantityQuery as $cso1)
                {
                    $quant[] = $cso1['quantity'];
                }
                $cso->quant = array_sum($quant);
            }
            $downloadpdf = "<a href='vendorfoodrpt/downloadfoodreport/".$vndrurl."/".$sclid."/".$sdates."/".$edates."' class='btn btn-success'>Download Food Order Report</a>";
            
        }
        else
        {
            $retrieve_cso = '';
            $sdate = '';
            $edate = '';
            $downloadpdf = "";
        }
        $this->set("vendor_details", $vendorss); 
        $this->set("vendrid", $vndrids);
        $this->set("downloadpdf", $downloadpdf);
        $this->set("sclid", $sclid);
        $this->set("strtdate1", $sdate);
        $this->set("enddate1", $edate);
        $this->set("cso_details", $retrieve_cso);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadfoodreport($vndridds, $sclid, $ssdate, $sedate)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $cv_table = TableRegistry::get('canteen_vendor');
            
        $vndrids = explode(",",$vndridds);
        //print_r($vndrids); die;
        $retrieve_scl = $scl_table->find()->select(['comp_name'])->where([ 'id' => $sclid])->first();
        
        
            if(in_array("all", $vndrids))
            {
                $vndrurl = "all";
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'canteen_vendor.vendor_company', 'vendor_id', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
                    'food_item' => 
                    [
                        'table' => 'food_item',
                        'type' => 'LEFT',
                        'conditions' => 'food_item.id = canteen_student_order.food_id'
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = canteen_student_order.school_id'
                    ],
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.str_date >=' => $ssdate, 'canteen_student_order.str_date <=' => $sedate])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
                $retrieve_cv = $cv_table->find()->select(['vendor_company'])->where([ 'status' => 1])->toArray(); 
            }
            else
            {
                $vendorid = $vndrids;
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'canteen_vendor.vendor_company', 'vendor_id', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'vendor_id', 'timings'])->join([
                    'food_item' => 
                    [
                        'table' => 'food_item',
                        'type' => 'LEFT',
                        'conditions' => 'food_item.id = canteen_student_order.food_id'
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = canteen_student_order.school_id'
                    ],
                    'canteen_vendor' => 
                    [
                        'table' => 'canteen_vendor',
                        'type' => 'LEFT',
                        'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                    ]
                ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.vendor_id IN' => $vndrids, 'canteen_student_order.str_date >=' => $ssdate, 'canteen_student_order.str_date <=' => $sedate])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
                
                $retrieve_cv = $cv_table->find()->select(['vendor_company'])->where([ 'id IN' => $vndrids])->toArray(); 
            }
            
        $vndrs = [];
        foreach($retrieve_cv as $cv)
        {
            $vndrs[] = $cv['vendor_company'];
        }
        $vendornames =  implode(",", $vndrs);
        $rowdata = '';
        foreach($retrieve_cso as $cso)
        {
            $quantityQuery = $cso_table->find()->select(['quantity'])->where(['vendor_id' => $cso['vendor_id'], 'school_id' => $cso['school_id'], 'food_id' => $cso['food_id'], 'str_date >=' => $ssdate, 'str_date <=' => $sedate])->toArray(); 
            $quant = [];
            foreach($quantityQuery as $cso1)
            {
                $quant[] = $cso1['quantity'];
            }
            $quant = array_sum($quant);
            
            $rowdata .= '<tr><td style="border:1px solid #ccc">'. ucfirst($cso['company']['comp_name']) .'</td>
                <td style="border:1px solid #ccc">'. ucfirst($cso['canteen_vendor']['vendor_company']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;"><img src="https://you-me-globaleducation.org/school/c_food/'. $cso['food_item']['food_img'] .'" width="50px" /></td>
                <td style="border:1px solid #ccc; text-align:center;">'. ucfirst($cso['food_item']['food_name']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. $quant .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. $cso->date .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. $cso->timings .'</td>
            </tr>';
        }
        
        
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
            if($langlbl['id'] == '2209') { $lbl2209 = $langlbl['title'] ; }
            if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; }
            if($langlbl['id'] == '2222') { $lbl2222 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2223') { $lbl2223 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2224') { $lbl2224 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2225') { $lbl2225 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2226') { $lbl2226 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2208') { $lbl2208 = $langlbl['title'] ; }
            if($langlbl['id'] == '2228') { $lbl2228 = $langlbl['title'] ; }
            if($langlbl['id'] == '566') { $lbl566 = $langlbl['title'] ; }
            if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
            if($langlbl['id'] == '637') { $lbl637 = $langlbl['title'] ; }
        } 
        
        $header = '<table style=" width: 100%;">
    		        <tbody>
        		    <tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">'.$lbl637.'</th>   
                                        <th style="border:1px solid #ccc">Vendor Company</th>  
                                        <th style="border:1px solid #ccc">'.$lbl2209.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2208.' </th>
                                        <th style="border:1px solid #ccc">'.$lbl2226.'</th>   
                                        <th style="border:1px solid #ccc">Dates</th>
                                        <th style="border:1px solid #ccc">'.$lbl2222.' </th>
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
        
            
        $title =  "FoodOrderreport_". date("d-m-Y",$sdates)." to ".date("d-m-Y",$edates);
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> 
	    <h3 style=" width:100%; text-align:center !important;">Food Order Report</h3>
	    <h4 style=" width:100%; text-align:center !important;">Vendor Company: '.$vendornames.'</h4>
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;"> School Report - '.$retrieve_scl['comp_name'].' (Date: '.date('d-m-Y', $ssdate).' to '.date('d-m-Y', $sedate).') </p>
	    <p>'.$header.'</p></div>';
	    
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
    
 
}

  


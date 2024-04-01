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
class CvendordashboardController  extends AppController
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
        $vas_table = TableRegistry::get('vendor_assign_school');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
		$school_table = TableRegistry::get('company');
		$vendorid = $this->request->session()->read('vendor_id');
		
		$retrieve_vendrdtl = $vas_table->find()->where(['vendor_id' => $vendorid ])->first();
		$scl_ids = explode(",", $retrieve_vendrdtl['school_ids']);
	    $retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id IN' => $scl_ids ])->toArray();
	    
		$this->set("sclinfo", $retrieve_scldtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function schoollist()
    {   
        $vas_table = TableRegistry::get('vendor_assign_school');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
		$school_table = TableRegistry::get('company');
		$vendorid = $this->request->session()->read('vendor_id');
		
		$retrieve_vendrdtl = $vas_table->find()->where(['vendor_id' => $vendorid ])->first();
		$scl_ids = explode(",", $retrieve_vendrdtl['school_ids']);
	    $retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id IN' => $scl_ids ])->toArray();
	    
		$this->set("sclinfo", $retrieve_scldtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function foodlist()
    {   
        $vas_table = TableRegistry::get('vendor_assign_school');
        $vtf_table = TableRegistry::get('vendor_to_food');
        $fi_table = TableRegistry::get('food_item');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
		$school_table = TableRegistry::get('company');
		$vendorid = $this->request->session()->read('vendor_id');
		
		$retrieve_vendrdtl = $vas_table->find()->where(['vendor_id' => $vendorid ])->first();
		$assignid = $retrieve_vendrdtl['id'];
	    $retrieve_fooddtl = $vtf_table->find()->select(['food_item.food_name', 'food_item.food_img', 'food_item.details', 'food_item.id', 'price'])->join([ 
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = vendor_to_food.food_id'
                ]
	        ])->where(['assign_id' => $assignid ])->toArray();
	    
		$this->set("foodinfo", $retrieve_fooddtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function scldata($id)
    {
        $fss_table = TableRegistry::get('food_serve_scl');
        $school_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
    	$retrieve_fssdtl = $fss_table->find()->where(['school_id' => $id ])->group(['class_section'])->toArray();     
    	$retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id' => $id ])->first();
	    
	    $this->set("sclinfo", $retrieve_scldtl); 
        $this->set("fssinfo", $retrieve_fssdtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function foodserve($id, $sctn)
    {
        $fss_table = TableRegistry::get('food_serve_scl');
        $school_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $vendorid = $this->request->session()->read('vendor_id');
    	$retrieve_fssdtl = $fss_table->find()->where(['school_id' => $id, 'class_section' => $sctn, 'vendor_id' => $vendorid ])->toArray();     
    	$retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id' => $id ])->first();
	    
	    $this->set("sclinfo", $retrieve_scldtl); 
        $this->set("fssinfo", $retrieve_fssdtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function listfoodserve($id)
    {
        $fss_table = TableRegistry::get('food_serve_scl');
        $school_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $vas_table = TableRegistry::get('vendor_assign_school');
        $vtf_table = TableRegistry::get('vendor_to_food');
        $fi_table = TableRegistry::get('food_item');
        
		
        
    	$retrieve_fssdtl = $fss_table->find()->where(['id' => $id])->first(); 
    	$food_ids = explode(",", $retrieve_fssdtl['food_ids']);
    	
    	$vendorid = $retrieve_fssdtl['vendor_id'];
        $retrieve_vendrdtl = $vas_table->find()->where(['vendor_id' => $vendorid ])->first();
        $assign_id = $retrieve_vendrdtl['id'];
        
        
        $foodprice = [];
        $foodlist = [];
        foreach($food_ids as $fid)
        {
            $retrieve_fpdtl = $vtf_table->find()->where(['food_id' => $fid, 'assign_id' => $assign_id])->first(); 
        	$foodprice['price'] = $retrieve_fpdtl['price'];
        	
        	$retrieve_fpdtl = $fi_table->find()->where(['id' => $fid ])->first(); 
        	$foodprice['name'] = $retrieve_fpdtl['food_name'];
        	$foodprice['image'] = $retrieve_fpdtl['food_img'];
        	
        	$foodlist[]= $foodprice;
        }
    	$retrieve_scldtl = $school_table->find()->select(['id', 'comp_name'])->where(['id' => $id ])->first();
	    
	    $this->set("foodlist", $foodlist); 
	    $this->set("sclinfo", $retrieve_scldtl); 
        $this->set("fssinfo", $retrieve_fssdtl); 
		$this->viewBuilder()->setLayout('usersa');
    }
    
    public function foodquantity()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $vendorid = $this->request->session()->read('vendor_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        
        $getscl = $scl_table->find()->select(['id','comp_name'])->where(['status' => 1])->toArray(); 
        $this->set("scl_details", $getscl);  
        
        if(!empty($_POST))
        {
            $edate = date("d-m-Y", strtotime($this->request->data('enddate')));
            $sdate = date("d-m-Y", strtotime($this->request->data('startdate')));
            
            $edates = strtotime($this->request->data('enddate')." 11:59 PM");
            $sdates = strtotime($this->request->data('startdate')." 12:01 AM");
            
            $sclids = $this->request->data['schoolid'];
            $sclurl = implode(",",$sclids);
            if(in_array("all", $sclids))
            {
                $sclid = "all";
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
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
                    ]
                ])->where(['canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
            }
            else
            {
                $sclid = $sclids;
                $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
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
                    ]
                ])->where(['canteen_student_order.school_id IN' => $sclids, 'canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
            }
            foreach($retrieve_cso as $cso)
            {
                $quantityQuery = $cso_table->find()->select(['quantity'])->where(['vendor_id' => $vendorid, 'school_id' => $cso['school_id'], 'food_id' => $cso['food_id'], 'str_date >=' => $sdates, 'str_date <=' => $edates])->toArray(); 
                $quant = [];
                foreach($quantityQuery as $cso1)
                {
                    $quant[] = $cso1['quantity'];
                }
                $cso->quant = array_sum($quant);
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
                if($langlbl['id'] == '2386') { $lbl2386 = $langlbl['title'] ; } 
            } 
            $downloadpdf = "<a href='downloadfoodreport/".$sclurl."/".$sdates."/".$edates."' class='btn btn-success'>".$lbl2386."</a>";
            
        }
        else
        {
            $retrieve_cso = '';
            $sdate = '';
            $edate = '';
            $downloadpdf = "";
        }
        $this->set("downloadpdf", $downloadpdf);
        $this->set("sclid", $sclid);
        $this->set("strtdate1", $sdate);
        $this->set("enddate1", $edate);
        $this->set("cso_details", $retrieve_cso);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadfoodreport($sclidss, $sdates, $edates)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $cv_table = TableRegistry::get('canteen_vendor');
        $vendorid = $this->request->session()->read('vendor_id');
        
        $retrieve_cv = $cv_table->find()->select(['vendor_company'])->where([ 'id' => $vendorid])->first();
            
        $sclids = explode(",",$sclidss);
        if(in_array("all", $sclids))
        {
            $sclid = "all";
            $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
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
                ]
            ])->where(['canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
            
            $retrieve_school = $scl_table->find()->select(['comp_name'])->where([ 'status' => 1])->toArray();
            
        }
        else
        {
            $sclid = $sclids;
            $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'company.comp_name', 'food_item.food_img', 'food_id', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
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
                ]
            ])->where(['canteen_student_order.school_id IN' => $sclids, 'canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.str_date >=' => $sdates, 'canteen_student_order.str_date <=' => $edates])->group(['canteen_student_order.date', 'canteen_student_order.food_id'])->toArray(); 
        
            $retrieve_school = $scl_table->find()->select(['comp_name'])->where([ 'id IN' => $sclids])->toArray();
        
        }
        $scls = [];
        foreach($retrieve_school as $scl)
        {
            $scls[] = $scl['comp_name'];
        }
        $sclnames =  implode(",", $scls);
        $rowdata = '';
        foreach($retrieve_cso as $cso)
        {
            $quantityQuery = $cso_table->find()->select(['quantity'])->where(['vendor_id' => $vendorid, 'school_id' => $cso['school_id'], 'food_id' => $cso['food_id'], 'str_date >=' => $sdates, 'str_date <=' => $edates])->toArray(); 
            $quant = [];
            foreach($quantityQuery as $cso1)
            {
                $quant[] = $cso1['quantity'];
            }
            $quant = array_sum($quant);
            
            $rowdata .= '<tr><td style="border:1px solid #ccc">'. ucfirst($cso['company']['comp_name']) .'</td>
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
            
            if($langlbl['id'] == '2388') { $lbl2388 = $langlbl['title'] ; }
            if($langlbl['id'] == '2389') { $lbl2389 = $langlbl['title'] ; }
            if($langlbl['id'] == '2400') { $lbl2400 = $langlbl['title'] ; }
            
        } 
        
        $header = '<table style=" width: 100%;">
    		        <tbody>
        		    <tr>
            			<td style="width: 100%;">
            			    <table style="width: 100%; border:1px solid #ccc">
        						<thead>
                                    <tr>
                                        <th style="border:1px solid #ccc">'.$lbl637.'</th>   
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
	    <h3 style=" width:100%; text-align:center !important;">'.$lbl2400.'</h3>
	    <h4 style=" width:100%; text-align:center !important;">'.$lbl2388.': '.$retrieve_cv['vendor_company'].'</h4>
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;"> '.$lbl2389.' - '.$sclnames.' (Date: '.date('d-m-Y', $sdates).' to '.date('d-m-Y', $edates).') </p>
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
    
    public function orderinfo()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $cso_table = TableRegistry::get('canteen_student_order');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $order_no = $this->request->data('order_no');
            $vendorid = $this->request->session()->read('vendor_id');
            $retrieve_orders = $cso_table->find()->select(['company.comp_name', 'class.c_name', 'date', 'timings', 'class.c_section', 'class.school_sections', 'student.f_name', 'student.adm_no', 'student.l_name', 'food_item.food_name', 'food_item.food_img', 'food_item.details', 'id', 'order_no', 'food_amount', 'quantity', 'total_amount', 'order_status', 'order_no'])->join([
                'company' => 
                [
                    'table' => 'company',
                    'type' => 'LEFT',
                    'conditions' => 'company.id = canteen_student_order.school_id'
                ],
                'student' => 
                [
                    'table' => 'student',
                    'type' => 'LEFT',
                    'conditions' => 'student.id = canteen_student_order.student_id'
                ],
                'class' => 
                [
                    'table' => 'class',
                    'type' => 'LEFT',
                    'conditions' => 'class.id = student.class'
                ],
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = canteen_student_order.food_id'
                ]
            ])->where(['order_no' => $order_no, 'vendor_id' => $vendorid ])->toArray(); 
            
            
            if(!empty($retrieve_orders)) {
                $studinfo = '<p>
                <b>Student Name: </b>'.$retrieve_orders[0]['student']['l_name'].' '.$retrieve_orders[0]['student']['f_name'].'<br/>
                <b>Student No. : </b>'.$retrieve_orders[0]['student']['adm_no'].'<br/>
                <b>Order No. : </b>'.$order_no.'<br/>
                <b>Date/Timings : </b>'.$retrieve_orders[0]['date'].'/'.$retrieve_orders[0]['timings'].'<br/>
                <b>Class: </b>'.$retrieve_orders[0]['class']['c_name'].'-'.$retrieve_orders[0]['class']['c_section'].' ('.$retrieve_orders[0]['class']['school_sections'].' <br/>
                <b>School: </b>'.$retrieve_orders[0]['company']['comp_name'].'
                </p>';
                $data = '';
                $delvral = [];
                foreach($retrieve_orders as $val)
                {
                    if($val['order_status'] == 0)
                    {
                        $sts = "Pending";
                        $delvral[] = $val['id'];
                    }
                    elseif($val['order_status'] == 1)
                    {
                        $sts = "Delivered";
                        
                    }
                    elseif($val['order_status'] == 2)
                    {
                        $sts = "Cancelled";
                    }
                    elseif($val['order_status'] == 3 || $val['order_status'] == 4)
                    {
                        $sts = "Undelivered";
                    }
                    $data .= '<tr>
                        <td><img src="c_food/'. $val['food_item']['food_img'].'" style="width:80px !important; background-color:#ffffff !important;"></td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$val['food_item']['food_name'].'</span>
                        </td>
                        <td>'.$val['quantity'].'</td>
                        <td>$'.$val['food_amount'].'</td>
                        <td>$'.$val['total_amount'].'</td>
                        <td><a href="javascript:void(0);" data-id="'.$val['id'].'" id="foodid'.$val['id'].'" data-osts = "'.$val['order_status'].'" data-str = "Order Status" data-url="Cvendordashboard/ordersts" class="btn btn-outline-secondary changeosts">'.$sts.'</a></td>
                    </tr>';
                }
                $dal = implode("@", $delvral);
                $delall = '';
                if(!empty($delvral)) {
                    $delall =  '<a href="javascript:void(0);" class="btn btn-success deliverallfd" data-ids="'.$dal.'" >Deliver All</a>';
                }
                $res = ['result' => "success", 'studinfo' => $studinfo, 'data' => $data, 'da' => $delall , 'orderid' => $order_no ];
            }
            else
            {
                $res = ['result' => "No data found. Please check the order no."];
            }
        }
        else
        {
            $res = ['result' => "invalid operation"];
        }
        return $this->json($res);
    }
    
    public function ordersts()
    {
        $uid = $this->request->data('val') ;
        $sts = $this->request->data('sts') ;
        $cso_table = TableRegistry::get('canteen_student_order');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sid = $cso_table->find()->select(['id'])->where(['id'=> $uid ])->first();
		$status = 1;
        if($sid)
        {   
            $stats = $cso_table->query()->update()->set([ 'order_status' => $status ])->where([ 'id' => $uid  ])->execute();
			
            if($stats)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Order status changed"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($uid);
                $activity->origin = $this->Cookie->read('id')   ;
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
                $res = [ 'result' => 'Status Not changed'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }

        return $this->json($res);
    }
    
    public function orderstsall()
    {
        $uid = explode("@", $this->request->data('val'));
        //print_r($uid);
        $cso_table = TableRegistry::get('canteen_student_order');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $status = 1;
        foreach($uid as $id)
        {
            //echo $id;
            $sid = $cso_table->find()->select(['id'])->where(['id'=> $id ])->first();
            if($sid)
            {   
                $stats = $cso_table->query()->update()->set([ 'order_status' => $status ])->where([ 'id' => $id  ])->execute();
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }
        }
        if($stats)
        {
            $activity = $activ_table->newEntity();
            $activity->action =  "Order status changed"  ;
            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            $activity->value = md5($uid);
            $activity->origin = $this->Cookie->read('id')   ;
            $activity->created = strtotime('now');
            
            $order_no = $this->request->data('order_no');
            $vendorid = $this->request->session()->read('vendor_id');
            $retrieve_orders = $cso_table->find()->select(['company.comp_name', 'class.c_name', 'date', 'timings', 'class.c_section', 'class.school_sections', 'student.f_name', 'student.adm_no', 'student.l_name', 'food_item.food_name', 'food_item.food_img', 'food_item.details', 'id', 'order_no', 'food_amount', 'quantity', 'total_amount', 'order_status', 'order_no'])->join([
                'company' => 
                [
                    'table' => 'company',
                    'type' => 'LEFT',
                    'conditions' => 'company.id = canteen_student_order.school_id'
                ],
                'student' => 
                [
                    'table' => 'student',
                    'type' => 'LEFT',
                    'conditions' => 'student.id = canteen_student_order.student_id'
                ],
                'class' => 
                [
                    'table' => 'class',
                    'type' => 'LEFT',
                    'conditions' => 'class.id = student.class'
                ],
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = canteen_student_order.food_id'
                ]
            ])->where(['order_no' => $order_no, 'vendor_id' => $vendorid ])->toArray(); 
            $data = '';
            foreach($retrieve_orders as $val)
            {
                if($val['order_status'] == 0)
                {
                    $sts = "Pending";
                }
                elseif($val['order_status'] == 1)
                {
                    $sts = "Delivered";
                    
                }
                elseif($val['order_status'] == 2)
                {
                    $sts = "Cancelled";
                }
                elseif($val['order_status'] == 3 || $val['order_status'] == 4)
                {
                    $sts = "Undelivered";
                }
                $data .= '<tr>
                    <td><img src="c_food/'. $val['food_item']['food_img'].'" style="width:80px !important; background-color:#ffffff !important;"></td>
                    <td>
                        <span class="mb-0 font-weight-bold">'.$val['food_item']['food_name'].'</span>
                    </td>
                    <td>'.$val['quantity'].'</td>
                    <td>$'.$val['food_amount'].'</td>
                    <td>$'.$val['total_amount'].'</td>
                    <td><a href="javascript:void(0);" data-id="'.$val['id'].'" id="foodid'.$val['id'].'" data-osts = "'.$val['order_status'].'" data-str = "Order Status" data-url="Cvendordashboard/ordersts" class="btn btn-outline-secondary changeosts">'.$sts.'</a></td>
                </tr>';
            }
            //$res['data'] = $data;
            if($saved = $activ_table->save($activity) )
            {
                $res = [ 'result' => 'success' , 'data' => $data ];
            }
            else
            {
                $res = [ 'result' => 'failed', 'data' => $data  ];
            }
        }
        else
        {
            $res = [ 'result' => 'Status Not changed', 'data' => $data   ];
        }  
        //print_r($data); die;
        return $this->json($res);
    }
    
    public function foodquantitydetail($foodid, $schoolid, $date)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $vendorid = $this->request->session()->read('vendor_id');
        $fi_table = TableRegistry::get('food_item');
        
        $getscl = $scl_table->find()->select(['comp_name'])->where(['status' => 1, 'id' => $schoolid])->first(); 
        $getfoodname = $fi_table->find()->select(['food_name'])->where([ 'id' => $foodid])->first(); 
        $downloadpdf = "<a href='https://you-me-globaleducation.org/school/cvendordashboard/downloadfooddetalling/".$foodid."/".$schoolid."/".$date."' class='btn btn-success'>Download Food Detailing Report</a>";
        
        $this->set("downloadpdf", $downloadpdf);  
        $this->set("seldate", $date);  
        $this->set("scl_details", $getscl);  
        $this->set("foodname", $getfoodname);  
         
        $sclid = $schoolid;
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'student.l_name', 'student.f_name', 'student.adm_no', 'remark', 'food_item.food_img', 'food_id', 'order_no', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ],
            'student' => 
            [
                'table' => 'student',
                'type' => 'LEFT',
                'conditions' => 'student.id = canteen_student_order.student_id'
            ]
        ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.food_id' => $foodid, 'canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.date ' => $date])->toArray(); 
            
        
        $this->set("cso_details", $retrieve_cso);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function downloadfooddetalling($foodid, $schoolid, $date)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $fi_table = TableRegistry::get('food_item');
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $cv_table = TableRegistry::get('canteen_vendor');
        $vendorid = $this->request->session()->read('vendor_id');
        
        $retrieve_cv = $cv_table->find()->select(['vendor_company'])->where([ 'id' => $vendorid])->first();
        
        $fi_table = TableRegistry::get('food_item');
        
        $getscl = $scl_table->find()->select(['comp_name'])->where(['status' => 1, 'id' => $schoolid])->first(); 
        $getfoodname = $fi_table->find()->select(['food_name'])->where([ 'id' => $foodid])->first(); 
       
        $sclid = $schoolid;
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'student.l_name', 'student.f_name', 'student.adm_no', 'remark', 'food_item.food_img', 'food_id', 'order_no', 'student_id', 'school_id', 'id', 'food_amount', 'quantity', 'date', 'timings'])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ],
            'student' => 
            [
                'table' => 'student',
                'type' => 'LEFT',
                'conditions' => 'student.id = canteen_student_order.student_id'
            ]
        ])->where(['canteen_student_order.school_id' => $sclid, 'canteen_student_order.food_id' => $foodid, 'canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.date ' => $date])->toArray(); 
            
        
        $rowdata = '';
   
        foreach($retrieve_cso as $value)
        {
            $rowdata .= '<tr>
                <td style="border:1px solid #ccc; text-align:center;">'. ucfirst($value['order_no']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. ucfirst($value['student']['adm_no']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. ucfirst($value['student']['l_name']." ".$value['student']['f_name']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;"><img src="https://you-me-globaleducation.org/school/c_food/'. $value['food_item']['food_img'] .'" width="50px" /></td>
                <td style="border:1px solid #ccc; text-align:center;">'. ucfirst($value['food_item']['food_name']) .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. $value['quantity'] .'</td>
                <td style="border:1px solid #ccc; text-align:center;">'. $value->remark .'</td>
            </tr> ';
            
            $quant[] = $value['quantity'];
        }
        $rowdata .= '<tr>
                <td style="border:1px solid #ccc; text-align:center;" colspan="5">Total</td>
                <td style="border:1px solid #ccc; text-align:center;">'. array_sum($quant) .'</td>
                <td style="border:1px solid #ccc; text-align:center;"> </td>
            </tr> ';
        
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
                                        <th style="border:1px solid #ccc">Order No</th>     
                                        <th style="border:1px solid #ccc">Student No.</th>     
                                        <th style="border:1px solid #ccc">Student Name</th>
                                        <th style="border:1px solid #ccc">'.$lbl2209.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2208.' </th>
                                        <th style="border:1px solid #ccc">'.$lbl2226 .'</th>  
                                        <th>Remarks(if any allergy)</th>
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
        
            
        $title =  "FoodOrderreport_". $date;
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> 
	    <h3 style=" width:100%; text-align:center !important;">Food Detailing Report</h3>
	    <h4 style=" width:100%; text-align:center !important;">Vendor Company: '.$retrieve_cv['vendor_company'].'</h4>
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px;"> School Report: '.$getscl['comp_name'].' (Date: '.$date.') </p>
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;">Food Name: '.$getfoodname['food_name'].'</p>
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

  


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
class CanteenController  extends AppController
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
        $compid = $this->request->session()->read('company_id');
        $vendor_table = TableRegistry::get('canteen_vendor');
        $vas_table = TableRegistry::get('vendor_assign_school');
        
        $retrieve_vendor = $vas_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $compid .'\',vendor_assign_school.school_ids)')))->select(['canteen_vendor.vendor_company', 'canteen_vendor.logo', 'vendor_assign_school.vendor_id', 'vendor_assign_school.id'])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = vendor_assign_school.vendor_id'
                ]
            ])->toArray(); 
        $ci_table = TableRegistry::get('canteen_image');
        $retrieve_ci = $ci_table->find()->first() ;
        $this->set("ci_details", $retrieve_ci);
        $this->set("vendor_details", $retrieve_vendor);
        if($_SESSION['dashb'] == "kinder")
        {
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            $this->viewBuilder()->setLayout('user');
        }
    }
    
    public function gettime()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $clsid = $this->request->session()->read('class_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        $class_table = TableRegistry::get('class');
        $vas_table = TableRegistry::get('vendor_assign_school');
        //echo $this->request->data('seldate');
        $date = strtotime($this->request->data('seldate'));
        $weekday = date("l", $date);
        
        $retrieve_class = $class_table->find()->select(['school_sections'])->where(['id' => $clsid])->first(); 
        if(strtolower($retrieve_class['school_sections']) == "maternelle" || strtolower($retrieve_class['school_sections']) == "creche")
        {
            $cls = "Kindergarten";
        }
        elseif(strtolower($retrieve_class['school_sections']) == "primaire" || strtolower($retrieve_class['school_sections']) == "primary")
        {
            $cls = "Primary";
        }
        else
        {
            $cls = "Senior";
        }
        
        $lang = $this->Cookie->read('language');	
		if($lang != "") { $lang = $lang; }
        else { $lang = 2; }
        
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
            if($langlbl['id'] == '2262') { $lbl2262 = $langlbl['title'] ; } 
        } 
        
        $retrieve_timings = $fss_table->find()->select(['timings'])->where(['school_id' => $compid, 'class_section' => $cls, 'weekday' => $weekday ])->group(['timings'])->toArray(); 
        //print_r($retrieve_timings);
        $data = '<option value="" >'.$lbl2262.'</option>';
        foreach($retrieve_timings as $timings)
        {
            $data .= '<option value="'.$timings['timings'].'">'.$timings['timings'].'</option>';
        }
        
        return $this->json($data);
    }
    
    public function getfeaturevendors()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $time = $this->request->data('gettime');
        
        $seldate = $this->request->data('seldate');
        $data['seldate'] = $seldate;
        $data['seltime'] = $time;
        
        return $this->json($data);
    }
    
    public function featurevendor()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $clsid = $this->request->session()->read('class_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        $class_table = TableRegistry::get('class');
        $vas_table = TableRegistry::get('vendor_assign_school');
        
        $date = strtotime($this->request->query('seldate'));
        //$date = strtotime($seldate);
        $weekday = date("l", $date);
        $time = $this->request->query('gettime');
        //$time = $gettime;
        $retrieve_class = $class_table->find()->select(['school_sections'])->where(['id' => $clsid])->first(); 
        if(strtolower($retrieve_class['school_sections']) == "maternelle" || strtolower($retrieve_class['school_sections']) == "creche")
        {
            $cls = "Kindergarten";
        }
        elseif(strtolower($retrieve_class['school_sections']) == "primaire" || strtolower($retrieve_class['school_sections']) == "primary")
        {
            $cls = "Primary";
        }
        else
        {
            $cls = "Senior";
        }
        
        $retrieve_vendors = $fss_table->find()->select(['timings', 'vendor_id', 'weekday', 'order_booking_closed', 'canteen_vendor.vendor_company', 'canteen_vendor.logo', 'id'])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = food_serve_scl.vendor_id'
                ]
            ])->where(['school_id' => $compid, 'class_section' => $cls, 'weekday' => $weekday, 'timings' => $time ])->toArray(); 
       
        $this->set("vendor_details", $retrieve_vendors);
        $seldate = $this->request->query('seldate');
        $this->set("seldate", $seldate);
        $this->set("seltime", $time);
        if($_SESSION['dashb'] == "kinder")
        {
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            $this->viewBuilder()->setLayout('user');
        }
        
       
    }
    
    public function vendorfood($seldate, $id)
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $stid = $this->request->session()->read('student_id');
        $vendor_table = TableRegistry::get('canteen_vendor');
        $vas_table = TableRegistry::get('vendor_assign_school');
        $vtf_table = TableRegistry::get('vendor_to_food');
        $fss_table = TableRegistry::get('food_serve_scl');
        $clsid = $this->request->session()->read('class_id');
        $class_table = TableRegistry::get('class');
        $cso_table = TableRegistry::get('cart_data');
        $retrieve_data = $fss_table->find()->where(['id' => $id ])->first();  
        $foodids = explode(",", $retrieve_data['food_ids']);
        $retrieve_food = [];
        foreach($foodids as $fid):
            $get_food = $vtf_table->find()->select(['food_item.food_name', 'food_item.food_img', 'food_item.details', 'assign_id', 'food_id','price'])->join([
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = vendor_to_food.food_id'
                ]
            ])->where(['vendor_id' => $retrieve_data['vendor_id'], 'food_id' => $fid ])->first(); 
            $retrieve_food[] = $get_food;
        endforeach;   
        
        
        //$date = strtotime($seldate);
        $weekday = date("l", $seldate);
        $time = $retrieve_data['timings']; 
        $retrieve_class = $class_table->find()->select(['school_sections'])->where(['id' => $clsid])->first(); 
        if(strtolower($retrieve_class['school_sections']) == "maternelle" || strtolower($retrieve_class['school_sections']) == "creche")
        {
            $cls = "Kindergarten";
        }
        elseif(strtolower($retrieve_class['school_sections']) == "primaire" || strtolower($retrieve_class['school_sections']) == "primary")
        {
            $cls = "Primary";
        }
        else
        {
            $cls = "Senior";
        }
        //echo $cls; die;
        $retrieve_vendorfood = $fss_table->find()->select(['timings', 'vendor_id', 'weekday', 'order_booking_closed', 'canteen_vendor.vendor_company', 'canteen_vendor.logo', 'id'])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = food_serve_scl.vendor_id'
                ]
            ])->where(['food_serve_scl.school_id' => $compid, 'class_section' => $cls, 'weekday' => $weekday, 'timings' => $time , 'food_serve_scl.id !=' => $id])->toArray(); 
        //print_r($retrieve_vendorfood); die;
        $this->set("vendorfood_details", $retrieve_vendorfood);
        
        $retrieve_vendor = $vendor_table->find()->where(['id' => $retrieve_data['vendor_id']])->first();
        
        $sdate = date("d-m-Y", $seldate);
        $retrieve_cartdata = $cso_table->find()->select(['quantity', 'price'])->where(['student_id' => $stid, 'timings' => $time, 'date' => $sdate ])->toArray(); 
        $quant = [];
        $price = [];
        foreach($retrieve_cartdata as $cartdata)
        {
            $quant[] = $cartdata['quantity'];
            $price[] = $cartdata['quantity']*$cartdata['price'];
        }
        //print_r($price); die;
        $quantity = array_sum($quant);
        $price = array_sum($price);
        $this->set("tquantity", $quantity);
        $this->set("tprice", $price);
        
        $this->set("vendor_details", $retrieve_vendor);
        $this->set("food_details", $retrieve_food);
        $this->set("seldate", $seldate);
        $this->set("seltime", $retrieve_data['timings']);
        if($_SESSION['dashb'] == "kinder")
        {
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            $this->viewBuilder()->setLayout('user');
        }
    }
    
    public function vendorfoodlist()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $vendor_table = TableRegistry::get('canteen_vendor');
        $vas_table = TableRegistry::get('vendor_assign_school');
        $vtf_table = TableRegistry::get('vendor_to_food');
        $fss_table = TableRegistry::get('food_serve_scl');
        $clsid = $this->request->session()->read('class_id');
        $class_table = TableRegistry::get('class');
        
        $id = $this->request->data('id');
        $seldate = $this->request->data('strdate');
        
        $lang = $this->Cookie->read('language');	
    		if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
            
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
                if($langlbl['id'] == '2292') { $lbl2292 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2290') { $lbl2290 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2289') { $lbl2289 = $langlbl['title'] ; } 
            } 
        
        $retrieve_data = $fss_table->find()->where(['id' => $id ])->first();  
        $foodids = explode(",", $retrieve_data['food_ids']);
        $retrieve_food = [];
        foreach($foodids as $fid):
            $get_food = $vtf_table->find()->select(['food_item.food_name', 'food_item.food_img', 'food_item.details', 'assign_id', 'food_id','price'])->join([
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = vendor_to_food.food_id'
                ]
            ])->where(['vendor_id' => $retrieve_data['vendor_id'], 'food_id' => $fid ])->first(); 
            $retrieve_food[] = $get_food;
        endforeach;  
        $fdvlist = '';
        foreach($retrieve_food as $value) { 
            $fdvlist .= '<div class="col-md-3 col-6 align-center foodimg">  
                <a class="example-image-link" href="'. $this->baseurl .'c_food/'. $value['food_item']['food_img'] .'" data-lightbox="example-1">
                    <img src="../../../c_food/'. $value['food_item']['food_img'] .'"  class="example-image img">
                </a>
            </div>
            <div class="col-md-3 col-6 foodcontent ">  
                
                <div class="fodcontnt">
                    <span>'. $value['food_item']['food_name'] .'</span>
                    <br>
                    <span>$'. $value['price'] .'</span>
                </div>
                <div class="remvcrt">
                    <a href="javascript:void(0)" data-fooddt ="'. $value['food_item']['details'] .'" title="view food details" class="btn btn-outline-secondary removecart" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                </div>
                <br>
                <div class="cont">
                    <div class="crtdiv">
                        <span class="qty">
                            <span class="dec'. $value['food_id'] .'">
                                <i class="fa fa-minus-square" aria-hidden="true"></i>
                            </span>
                            <span class="num'. $value['food_id'] .'">
                            1
                            </span>
                            <span class="inc'. $value['food_id'] .'">
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                            </span>
                        </span>
                        <button id="btn'. $value['food_id'] .'" type="button" class="cart" data-foodid="'. $value['food_id'] .'" data-foodprice ="'. $value['price'] .'" data-vndrid="'. $retrieve_data['vendor_id'] .'"><i class="fa fa-shopping-cart" aria-hidden="true"></i> '.$lbl2292.'</button>
                    </div>
                </div>
            </div>';
        }
      
        $weekday = date("l", $seldate);
        $time = $retrieve_data['timings']; 
        $retrieve_class = $class_table->find()->select(['school_sections'])->where(['id' => $clsid])->first(); 
        if(strtolower($retrieve_class['school_sections']) == "maternelle" || strtolower($retrieve_class['school_sections']) == "creche")
        {
            $cls = "Kindergarten";
        }
        elseif(strtolower($retrieve_class['school_sections']) == "primaire" || strtolower($retrieve_class['school_sections']) == "primary")
        {
            $cls = "Primary";
        }
        else
        {
            $cls = "Senior";
        }
       
        $retrieve_vendorfood = $fss_table->find()->select(['timings', 'vendor_id', 'weekday', 'order_booking_closed', 'canteen_vendor.vendor_company', 'canteen_vendor.logo', 'id'])->join([
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = food_serve_scl.vendor_id'
                ]
            ])->where(['food_serve_scl.school_id' => $compid, 'class_section' => $cls, 'weekday' => $weekday, 'timings' => $time , 'food_serve_scl.id !=' => $id])->toArray(); 
        $vendorlist = '';
        foreach($retrieve_vendorfood as $value):
            $vendorlist .= '<div class="col-md-4">  
                <div class="box">
                    <img src="'.$this->baseurl.'../../../canteen/'.$value['canteen_vendor']['logo'].'" >
                    <div class="box-content">
                        <h3 class="title">'. $value['canteen_vendor']['vendor_company'] .'</h3>
                        <a href="javascript:void(0);" class="vendorclick" data-strdate="'. $seldate .'" data-id="'. $value['id'] .'">'.$lbl2289.'</a>
                    </div>
                </div>
            </div>';
        endforeach;
        
        
        $retrieve_vendor = $vendor_table->find()->where(['id' => $retrieve_data['vendor_id']])->first();
        $data['vendorname'] = $retrieve_vendor['vendor_company'];
        $data['chnagevendor'] = $vendorlist;
        $data['foodlistvndr'] = $fdvlist;
        
        return $this->json($data);
    }
    
    public function cartdata()
    {
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $stid = $this->request->session()->read('student_id');
        $sessid = $this->request->session()->read('session_id');
        $clsid = $this->request->session()->read('class_id');
        $cso_table = TableRegistry::get('cart_data');
        $activ_table = TableRegistry::get('activity');
        
        $fid = $this->request->data('fid');
        $vid = $this->request->data('vid');
        $fp = $this->request->data('fp');
        $qnty = $this->request->data('qnty');
        $stime = $this->request->data('stime');
        $sdate = date("d-m-Y", $this->request->data('sdate'));
        
        $retrieve_cart = $cso_table->find()->select(['id'])->where(['food_id' => $fid, 'timings' => $stime, 'date' => $sdate, 'vendor_id' => $vid, 'student_id' => $stid ])->first(); 
        
        if($qnty >= 1)
        {
            if(empty($retrieve_cart))
            {
                $cso = $cso_table->newEntity();
                        
                $cso->vendor_id = $vid;
        		$cso->food_id = $fid;
        		$cso->class_id = $clsid;
                $cso->session_id = $sessid;
                $cso->student_id = $stid;
                $cso->school_id = $compid;
                $cso->quantity = $qnty;
                $cso->price = $fp;
                $cso->timings = $stime;
                $cso->date = $sdate;
        		$cso->added_on = time();
                
                $saved = $cso_table->save($cso);
            }
            else
            {
                $saved = $cso_table->query()->update()->set(['quantity' => $qnty, 'price' => $fp])->where([ 'id' => $retrieve_cart['id']  ])->execute();
            }
            if($saved)
            {   
                $retrieve_cartdata = $cso_table->find()->select(['quantity', 'price'])->where(['student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->toArray(); 
                $quant = [];
                $price = [];
                foreach($retrieve_cartdata as $cartdata)
                {
                    $quant[] = $cartdata['quantity'];
                    $price[] = $cartdata['quantity']*$cartdata['price'];
                    
                }
                
                $data['quantity'] = array_sum($quant);
                $data['price'] = array_sum($price);
                
                
                $retrieve_foodamt = $cso_table->find()->select(['quantity', 'price'])->where(['food_id' => $fid, 'vendor_id' => $vid, 'student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->first();
                $data['foodprice'] = $retrieve_foodamt['price']*$retrieve_foodamt['quantity'];
                
                $activity = $activ_table->newEntity();
                $activity->action =  "Cart data added/update"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('id');
                $activity->value = md5($sclid); 
                $activity->created = strtotime('now');
                $save = $activ_table->save($activity) ;
    
                if($save)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                { 
                    $res = [ 'result' => 'activity'  ];
                }
            }
            else
            { 
                $res = [ 'result' => 'failed'  ]; 
            }
        }
        else
        {
            $del = $cso_table->query()->delete()->where([ 'id' => $retrieve_cart['id'] ])->execute(); 
            
            if($del)
            {   
                $retrieve_cartdata = $cso_table->find()->select(['quantity', 'price'])->where(['student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->toArray(); 
                $quant = [];
                $price = [];
                foreach($retrieve_cartdata as $cartdata)
                {
                    $quant[] = $cartdata['quantity'];
                    $price[] = $cartdata['quantity']*$cartdata['price'];
                    
                }
                
                $retrieve_cartdata = $cso_table->find()->select(['canteen_vendor.vendor_company', 'food_item.food_name', 'food_item.food_img', 'id', 'price', 'quantity', 'food_id', 'vendor_id'])->join([
                        'food_item' => 
                        [
                            'table' => 'food_item',
                            'type' => 'LEFT',
                            'conditions' => 'food_item.id = cart_data.food_id'
                        ],
                        'canteen_vendor' => 
                        [
                            'table' => 'canteen_vendor',
                            'type' => 'LEFT',
                            'conditions' => 'canteen_vendor.id = cart_data.vendor_id'
                        ]
                    ])->where(['student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->toArray(); 
                $foodlist = '';   
                foreach($retrieve_cartdata as $value) {
                    $foodlist .= '<div class="col-md-3 col-sm-4 align-center foodimg">  
                        <a class="example-image-link" href="'.$this->baseurl.'c_food/'. $value['food_item']['food_img'] .'" data-lightbox="example-1">
                            <img src="'.$this->baseurl.'c_food/'. $value['food_item']['food_img'] .'"  class="example-image img">
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 foodcontent ">  
                        <span>'. $value['canteen_vendor']['vendor_company'] .'</span>
                        <br>
                        <span>'. $value['food_item']['food_name'] .'</span>
                        <br>
                        <span>$'. $value['quantity']*$value['price'] .'</span>
                    
                        <br>
                        <div class="cont">
                            <div class="crtdiv">
                                <span class="qty">
                                    <span id="dec'. $value['food_id'] .'" class="dec_cart" data-foodid="'. $value['food_id'] .'" data-foodprice ="'. $value['price'] .'" data-vndrid="'. $value['vendor_id'] .'">
                                        <i class="fa fa-minus-square" aria-hidden="true"></i>
                                    </span>
                                    <span class="num'. $value['food_id'] .'">
                                    '. $value['quantity'] .'
                                    </span>
                                    <span id="inc'. $value['food_id'] .'"  class="inc_cart" data-foodid="'. $value['food_id'] .'" data-foodprice ="'. $value['price'] .'" data-vndrid="'. $value['vendor_id'] .'">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </span>
                                </span>
                                <button id="btn'. $value['food_id'] .'" type="button" class="cart_clk" data-foodid="'. $value['food_id'] .'" data-foodprice ="'. $value['price'] .'" data-vndrid="'. $value['vendor_id'] .'"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>';
                }
                
                $data['quantity'] = array_sum($quant);
                $data['price'] = array_sum($price);
                $data['cartdata'] = $foodlist;
                
                $activity = $activ_table->newEntity();
                $activity->action =  "Cart data removed"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('id');
                $activity->value = md5($sclid); 
                $activity->created = strtotime('now');
                $save = $activ_table->save($activity) ;
    
                if($save)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                { 
                    $res = [ 'result' => 'activity'  ];
                }
            }
            else
            { 
                $res = [ 'result' => 'failed'  ]; 
            }
        }
        return $this->json($data);
    }
    
    public function viewcart()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $stid = $this->request->session()->read('student_id');
        $sessid = $this->request->session()->read('session_id');
        $clsid = $this->request->session()->read('class_id');
        $cd_table = TableRegistry::get('cart_data');
        $stud_table = TableRegistry::get('student');
        $cf_table = TableRegistry::get('canteen_fund');
        $cso_table = TableRegistry::get('canteen_student_order');
        
        $sdate = date("d-m-Y", $this->request->query('sdate'));
        $stime = $this->request->query('stime');
        $retrieve_cartdata = $cd_table->find()->select(['canteen_vendor.vendor_company', 'food_item.food_name', 'food_item.food_img', 'id', 'price', 'quantity', 'food_id', 'vendor_id'])->join([
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = cart_data.food_id'
            ],
            'canteen_vendor' => 
            [
                'table' => 'canteen_vendor',
                'type' => 'LEFT',
                'conditions' => 'canteen_vendor.id = cart_data.vendor_id'
            ]
        ])->where(['student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->toArray(); 
        
        $stud_dtl = $stud_table->find()->select(['canteen_pincode'])->where(['id' => $stid ])->first();  
        $cantn_pin = $stud_dtl['canteen_pincode'];
        
        $cartamt = $cd_table->find()->select(['price','quantity'])->where(['student_id' => $stid, 'timings' => $stime, 'date' => $sdate ])->toArray();  
        //print_r($cartamt); die;
        $carta = [];
        foreach($cartamt as $ca)
        {
            $carta[] = $ca['price']*$ca['quantity'];
        }
        $amtcart = array_sum($carta);
        
        $addedbal = $cf_table->find()->select(['total_amt' => 'sum(amount)'])->where([ 'student_id' => $stid, 'session_id' => $sessid ])->first(); 
        $amtadded = $addedbal['total_amt'];
        $dailylim = $cf_table->find()->select(['daily_limit'])->where([ 'student_id' => $stid, 'session_id' => $sessid ])->last(); 
        $dailylimt = $dailylim['daily_limit'];
        $osts = ['0', '1', '4'];
        $spentbal = $cso_table->find()->select(['tamt' => 'sum(total_amount)'])->where([ 'student_id' => $stid, 'session_id' => $sessid, 'order_status IN' => $osts  ])->first(); 
        $amtspent = 0;
        if(!empty($spentbal)):
            $amtspent = $spentbal['tamt'];
        endif;
        
        $balnce = $amtadded-$amtspent;
        
        $ttspentdayamt = $cso_table->find()->select(['ttamt' => 'sum(total_amount)'])->where([ 'student_id' => $stid, 'session_id' => $sessid, 'date' => $sdate  ])->group(['date'])->first(); 
        if(!empty($ttspentdayamt))
        {
            $ttspentdayamt = $ttspentdayamt['ttamt'];
        }
        else
        {
            $ttspentdayamt = 0;
        }
        //print_r(); die;
        $this->set('ttspentdayamt',$ttspentdayamt);
        $this->set('canteenpin',$cantn_pin);
        $this->set('cartamt',$amtcart);
        $this->set('balance',$balnce);
        $this->set('dailylimt', $dailylimt);
        $this->set('cartdata',$retrieve_cartdata);
        $this->set("seldate", $this->request->query('sdate'));
        $this->set("seltime", $stime);
        if($_SESSION['dashb'] == "kinder")
        {
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            $this->viewBuilder()->setLayout('user');
        }
    }
    
    public function removecart()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $cso_table = TableRegistry::get('cart_data');
        
        $id = $this->request->data('id');
        $del = $cso_table->query()->delete()->where([ 'id' => $id ])->execute(); 
            
        if($del)
        {   
            $res = true;
        }
        else
        {
            $res = false;
        }
        
        return $this->json($res);
    }
    
    public function placeorder()
    {
        //print_r($_POST); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $stid = $this->request->session()->read('student_id');
        $sessid = $this->request->session()->read('session_id');
        $clsid = $this->request->session()->read('class_id');
        $cd_table = TableRegistry::get('cart_data');
        $cf_table = TableRegistry::get('canteen_fund');
        $cso_table = TableRegistry::get('canteen_student_order');
        
        $sdate = date("d-m-Y", $this->request->data('sdate'));
        $stime = $this->request->data('stime');
        $stdate = date("d-m-Y", $this->request->data('sdate'))." 11:59 PM" ;
        $weekday = date("l", $this->request->data('sdate'));
        
        $remark = $this->request->data('remark');
        
        $cartdata = $cd_table->find()->where(['student_id' => $stid, 'date' => $sdate, 'timings' => $stime ])->toArray();
        $getorderno = $cso_table->find()->select(['order_no'])->last();
        $ordno = explode("Y", $getorderno['order_no']);
        $orderno = $ordno[1]+1;
        $orderno = "Y".$orderno;
        foreach($cartdata as $cdata)
        {
            $cso = $cso_table->newEntity();
            $cso->order_no = $orderno;    
            $cso->vendor_id = $cdata['vendor_id'];
    		$cso->food_id = $cdata['food_id'];
    		$cso->class_id = $cdata['class_id'];
            $cso->session_id = $sessid;
            $cso->student_id = $cdata['student_id'];
            $cso->school_id = $compid;
            $cso->quantity = $cdata['quantity'];
            $cso->food_amount = $cdata['price'];
            $cso->total_amount = $cdata['price']*$cdata['quantity'];
            $cso->date = $sdate;
            $cso->str_date = strtotime($stdate);
            $cso->timings = $stime;
            $cso->weekday = $weekday;
    		$cso->created_date = time();
    		$cso->order_status = 0;
    		$cso->remark = $remark;
            
            $saved = $cso_table->save($cso);
            
            if($saved)
            {
                $del = $cd_table->query()->delete()->where(['id' => $cdata['id'] ])->execute();
                $res = ['result' => "success", 'orderno' => $orderno];
            }
            else
            {
                $res = ['result' => "failed"];
            }
        }
        return $this->json($res);
    }
    
    public function foodhistory()
    {
        $cso_table = TableRegistry::get('canteen_student_order');
        $stud_table = TableRegistry::get('student');
        $student_id = $this->request->session()->read('student_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        //$sessid = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $stid = $this->request->session()->read('student_id');
        $sessid = $this->request->session()->read('session_id');
        
        $cf_table = TableRegistry::get('canteen_fund');
        $addedbal = $cf_table->find()->select(['total_amt' => 'sum(amount)'])->where([ 'student_id' => $stid, 'session_id' => $sessid ])->first(); 
        $amtadded = $addedbal['total_amt'];
        $osts = ['0','1','4'];
        $spentbal = $cso_table->find()->select(['tamt' => 'sum(total_amount)'])->where([ 'student_id' => $stid, 'session_id' => $sessid, 'order_status IN' => $osts ])->first(); 
        $amtspent = 0;
        if(!empty($spentbal)):
            $amtspent = $spentbal['tamt'];
        endif;
        
        $balnce = $amtadded-$amtspent;
        if(empty(($_POST)))
        {
            $strtdate = '';
            $enddate = '';
            $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'order_no', 'created_date', 'food_item.food_img', 'canteen_vendor.id','canteen_vendor.f_name', 'canteen_vendor.l_name', 'canteen_vendor.vendor_company', 'food_id', 'student_id', 'id', 'food_amount', 'quantity', 'total_amount', 'order_status', 'date', 'timings', 'weekday', 'remark'])->join([
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
            ])->where(['canteen_student_order.student_id' => $student_id])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        }
        else
        {
            $strtdate = strtotime($this->request->data('startdate')." 12:01 AM");
            $enddate = strtotime($this->request->data('enddate')." 11:59 PM"); 
            $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'order_no', 'created_date', 'food_item.food_img', 'canteen_vendor.id','canteen_vendor.f_name', 'canteen_vendor.l_name', 'canteen_vendor.vendor_company', 'food_id', 'student_id', 'id', 'food_amount', 'quantity', 'total_amount', 'order_status', 'date', 'timings', 'weekday', 'remark'])->join([
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
            ])->where(['canteen_student_order.student_id' => $student_id, 'canteen_student_order.str_date >=' => $strtdate, 'canteen_student_order.str_date <=' => $enddate ])->order(['canteen_student_order.id' => 'DESC'])->toArray(); 
        
            $strtdate1 = date("d-m-Y", strtotime($this->request->data('startdate')));
            $enddate1 = date("d-m-Y", strtotime($this->request->data('enddate'))); 
        }
        $this->set("balance", $balnce);
        $this->set("amtadded", $amtadded);
        $this->set("amtspent", $amtspent);
        $this->set("strtdate1", $strtdate1);
        $this->set("enddate1", $enddate1);
        $this->set("cso_details", $retrieve_cso);
        if($_SESSION['dashb'] == "kinder")
        {
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            $this->viewBuilder()->setLayout('user');
        }
    }
    
    public function cancel()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $uid = $this->request->data('val') ;
        $sts = $this->request->data('sts') ;
        $cso_table = TableRegistry::get('canteen_student_order');
        $activ_table = TableRegistry::get('activity');

        $userid = $cso_table->find()->select(['id'])->where(['id'=> $uid ])->first();
		$status = $sts == 1 ? 0 : 1;
        if($userid)
        {   
            $stats = $cso_table->query()->update()->set([ 'order_status' => 2])->where([ 'id' => $uid  ])->execute();
			
            if($stats)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Student cancelled food item"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($uid)    ;
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
                $res = [ 'result' => 'not delete'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }

        return $this->json($res);
    }
            
    public function orderinfo()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $lang = $this->Cookie->read('language');	
    		if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
            
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
                if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
                if($langlbl['id'] == '723') { $lbl723 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2268') { $lbl2268 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2010') { $lbl2010 = $langlbl['title'] ; } 
                if($langlbl['id'] == '136') { $lbl136 = $langlbl['title'] ; } 
                if($langlbl['id'] == '635') { $lbl635 = $langlbl['title'] ; } 
                
                if($langlbl['id'] == '2272') { $lbl2272 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2273') { $lbl2273 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2274') { $lbl2274 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2275') { $lbl2275 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2276') { $lbl2276 = $langlbl['title'] ; } 
            } 
        
            
            $cso_table = TableRegistry::get('canteen_student_order');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $order_no = $this->request->data('order_no');
            $studid = $this->request->session()->read('student_id');
            $retrieve_orders = $cso_table->find()->select(['canteen_vendor.vendor_company', 'remark', 'company.comp_name', 'class.c_name', 'date', 'timings', 'class.c_section', 'class.school_sections', 'student.f_name', 'student.adm_no', 'student.l_name', 'food_item.food_name', 'food_item.food_img', 'food_item.details', 'id', 'order_no', 'food_amount', 'quantity', 'total_amount', 'order_status', 'order_no'])->join([
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
                'canteen_vendor' => 
                [
                    'table' => 'canteen_vendor',
                    'type' => 'LEFT',
                    'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
                ],
                'food_item' => 
                [
                    'table' => 'food_item',
                    'type' => 'LEFT',
                    'conditions' => 'food_item.id = canteen_student_order.food_id'
                ]
            ])->where(['order_no' => $order_no, 'student_id' => $studid ])->toArray(); 
            
            
            if(!empty($retrieve_orders)) {
                if($retrieve_orders[0]['remark'] != "") {
                    $remark = "Remarks: ".$retrieve_orders[0]['remark'];
                }
                else
                {
                    $remark = '';
                }
                $studinfo = '<p>
                <b>'.$lbl147.': </b>'.$retrieve_orders[0]['student']['l_name'].' '.$retrieve_orders[0]['student']['f_name'].'<br/>
                <b>'.$lbl723.' : </b>'.$retrieve_orders[0]['student']['adm_no'].'<br/>
                <b>'.$lbl2268.' : </b>'.$order_no.'<br/>
                <b>'.$lbl2010.' : </b>'.$retrieve_orders[0]['date'].'/'.$retrieve_orders[0]['timings'].'<br/>
                <b>'.$lbl136.': </b>'.$retrieve_orders[0]['class']['c_name'].'-'.$retrieve_orders[0]['class']['c_section'].' ('.$retrieve_orders[0]['class']['school_sections'].' <br/>
                <b>'.$lbl635.': </b>'.$retrieve_orders[0]['company']['comp_name'].'
                </p>';
                $data = '';
                foreach($retrieve_orders as $val)
                {
                    if($val['order_status'] == 0)
                    {
                        $sts = $lbl2276;
                        $styl = 'style="border:1px solid #ccc; background-color: #ff9e0c !important; border-color: #ff9e0c !important; color: #ffffff !important;"';
                    }
                    elseif($val['order_status'] == 1)
                    {
                        $sts = $lbl2273;
                        $styl = 'style="border:1px solid #ccc; background-color: #428000 !important; border-color: #428000 !important; color: #ffffff !important;"';
                    }
                    elseif($val['order_status'] == 2)
                    {
                        $sts = $lbl2274;
                        $styl = 'style="border:1px solid #ccc; background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important;"';
                    }
                    elseif($val['order_status'] == 3 || $val['order_status'] == 4)
                    {
                        $sts = $lbl2272;
                        $styl = 'style="border:1px solid #ccc; background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important;"';
                    }
                    $data .= '<tr>
                        <td>'.$val['canteen_vendor']['vendor_company'].'</td>
                        <td><img src="../c_food/'. $val['food_item']['food_img'].'" style="width:80px !important; background-color:#ffffff !important;"></td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$val['food_item']['food_name'].'</span>
                        </td>
                        <td>'.$val['quantity'].'</td>
                        <td>$'.$val['food_amount'].'</td>
                        <td>$'.$val['total_amount'].'</td>
                        <td><a href="javascript:void(0);" data-id="'.$val['id'].'" id="foodid'.$val['id'].'" data-osts = "'.$val['order_status'].'" data-str = "Order Status" data-url="Cvendordashboard/ordersts" class="btn changeosts" '.$styl.'>'.$sts.'</a></td>
                    </tr>';
                }
                $invoice = '<a href="downloadinvoice/'.$order_no.'"  class="btn btn-outline-secondary"><i class="fa fa-download"></i></a>';
                $res = ['result' => "success", 'studinfo' => $studinfo, 'data' => $data, 'invoice' => $invoice, 'remark' => $remark ];
            }
            else
            {
                $res = ['result' => "No data found"];
            }
        }
        else
        {
            $res = ['result' => "invalid operation"];
        }
        return $this->json($res);
    }
    
    public function downloadinvoice($orderid =null)
    {
        $lang = $this->Cookie->read('language');	
    		if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
            
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
                if($langlbl['id'] == '147') { $lbl147 = $langlbl['title'] ; } 
                if($langlbl['id'] == '723') { $lbl723 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2268') { $lbl2268 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2010') { $lbl2010 = $langlbl['title'] ; } 
                if($langlbl['id'] == '136') { $lbl136 = $langlbl['title'] ; } 
                if($langlbl['id'] == '635') { $lbl635 = $langlbl['title'] ; } 
                
                if($langlbl['id'] == '2272') { $lbl2272 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2273') { $lbl2273 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2274') { $lbl2274 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2275') { $lbl2275 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2276') { $lbl2276 = $langlbl['title'] ; } 
                if($langlbl['id'] == '2287') { $lbl2287 = $langlbl['title'] ; } 
            } 
        
        $cso_table = TableRegistry::get('canteen_student_order');
        $school_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $order_no = $orderid;
        $studid = $this->request->session()->read('student_id');
        $retrieve_orders = $cso_table->find()->select(['canteen_vendor.vendor_company', 'remark', 'company.comp_name', 'class.c_name', 'date', 'timings', 'class.c_section', 'class.school_sections', 'student.f_name', 'student.adm_no', 'student.l_name', 'food_item.food_name', 'food_item.food_img', 'food_item.details', 'id', 'order_no', 'food_amount', 'quantity', 'total_amount', 'order_status', 'order_no'])->join([
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
            'canteen_vendor' => 
            [
                'table' => 'canteen_vendor',
                'type' => 'LEFT',
                'conditions' => 'canteen_vendor.id = canteen_student_order.vendor_id'
            ],
            'food_item' => 
            [
                'table' => 'food_item',
                'type' => 'LEFT',
                'conditions' => 'food_item.id = canteen_student_order.food_id'
            ]
        ])->where(['order_no' => $order_no, 'student_id' => $studid ])->toArray(); 
        $rowdata = '';
        $tamt = [];
        $famt = [];
        $quant = [];
        if(!empty($retrieve_orders)) {
            if($retrieve_orders[0]['remark'] != "") {
                $remark = "Remarks: ".$retrieve_orders[0]['remark'];
            }
            else
            {
                $remark = '';
            }
            
            $studinfo = '<p>
                <b>'.$lbl147.': </b>'.$retrieve_orders[0]['student']['l_name'].' '.$retrieve_orders[0]['student']['f_name'].'<br/>
                <b>'.$lbl723.' : </b>'.$retrieve_orders[0]['student']['adm_no'].'<br/>
                <b>'.$lbl2268.' : </b>'.$order_no.'<br/>
                <b>'.$lbl2010.' : </b>'.$retrieve_orders[0]['date'].'/'.$retrieve_orders[0]['timings'].'<br/>
                <b>'.$lbl136.': </b>'.$retrieve_orders[0]['class']['c_name'].'-'.$retrieve_orders[0]['class']['c_section'].' ('.$retrieve_orders[0]['class']['school_sections'].' <br/>
            </p>';
            foreach($retrieve_orders as $val)
            {
                
                if($val['order_status'] == 0)
                {
                    $sts = $lbl2276;
                    $styl = 'style="border:1px solid #ccc; background-color: #ff9e0c !important; border-color: #ff9e0c !important; color: #ffffff !important;"';
                }
                elseif($val['order_status'] == 1)
                {
                    $sts = $lbl2273;
                    $styl = 'style="border:1px solid #ccc; background-color: #428000 !important; border-color: #428000 !important; color: #ffffff !important;"';
                }
                elseif($val['order_status'] == 2)
                {
                    $sts = $lbl2274;
                    $styl = 'style="border:1px solid #ccc; background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important;"';
                }
                elseif($val['order_status'] == 3 || $val['order_status'] == 4)
                {
                    $sts = $lbl2272;
                    $styl = 'style="border:1px solid #ccc; background-color: #dc3545 !important; border-color: #dc3545 !important; color: #ffffff !important;"';
                }
                $rowdata .= '<tr>
                    <td style="border:1px solid #ccc">'.$val['canteen_vendor']['vendor_company'].'</td>
                    <td style="border:1px solid #ccc; text-align:center;"><img src="https://you-me-globaleducation.org/school/c_food/'. $val['food_item']['food_img'].'" style="width:80px !important; background-color:#ffffff !important;"></td>
                    <td style="border:1px solid #ccc; ">
                        <span class="mb-0 font-weight-bold">'.$val['food_item']['food_name'].'</span>
                    </td>
                    <td style="border:1px solid #ccc; text-align:center;">'.$val['quantity'].'</td>
                    <td style="border:1px solid #ccc; text-align:right;">$'.$val['food_amount'].'</td>
                    <td style="border:1px solid #ccc; text-align:right;">$'.$val['total_amount'].'</td>
                    <td '.$styl.'>'.$sts.'</td>
                </tr>';
                if($val['order_status'] == 1) {
                $quant[] = $val['quantity'];
                $famt[] = $val['food_amount'];
                $tamt[] = $val['total_amount'];
                }
            }  
            $rowdata .= '<tr>
                    <td style="border:1px solid #ccc" colspan="3">Total</td>
                    
                    <td style="border:1px solid #ccc; text-align:center;">'.array_sum($quant).'</td>
                    <td style="border:1px solid #ccc; text-align:right;">$'.array_sum($famt).'</td>
                    <td style="border:1px solid #ccc; text-align:right;">$'.array_sum($tamt).'</td>
                    <td style="border:1px solid #ccc"></td>
                </tr>';
        }
       
        $schoolid = $this->request->session()->read('company_id');
        $retrieve_school = $school_table->find()->select(['comp_name', 'comp_logo'])->where([ 'id' => $schoolid])->toArray();
        $school_logo = '<img src="https://you-me-globaleducation.org/school/img/'. $retrieve_school[0]['comp_logo'].'" style="width:75px !important;">';
	    $n =1;
	    
	    foreach($retrieve_langlabel as $langlbl) { 
            if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
            if($langlbl['id'] == '2281') { $lbl2281 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2282') { $lbl2282 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2283') { $lbl2283 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2284') { $lbl2284 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2285') { $lbl2285 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2286') { $lbl2286 = $langlbl['title'] ; } 
            if($langlbl['id'] == '2265') { $lbl2265 = $langlbl['title'] ; } 
        } 
       
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
                    						    <th style="width: 100%; text-align:center;  font-size: 14px;">'.ucfirst($retrieve_school[0]['comp_name']).'</th>
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
        						<thead style="border:1px solid #ccc">
                                    <tr>
                                        <th style="border:1px solid #ccc">'.$lbl2281.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2282.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2283.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2284.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2285.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2265.'</th>
                                        <th style="border:1px solid #ccc">'.$lbl2286.'</th>
                                    </tr>
                                </thead>
                                <tbody style="border:1px solid #ccc"> 
                                    '.$rowdata.'
                                </tbody>
        					</table>
            			</td>
        			</tr>
        			<tr>
            			<td style="width: 100%;">
            			'.$remark.'
            			</td>
            		</tr>
        		</tbody>
        		</table>';
	
	    $title =  "Invoicenumber_". $order_no;
	    
	    $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px; border:1px solid #ddd;"> 
	    <p style=" width:100%; text-align:center !important; font-weight:bold; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:4px;"> '.$lbl2287.' </p>
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

  




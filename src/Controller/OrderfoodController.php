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
use PhpSpreadsheet\Reader\Xlsx;
use Dompdf\Dompdf;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class OrderfoodController  extends AppController
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
        //$vendor_table = TableRegistry::get('canteen_vendor');
        $vas_table = TableRegistry::get('vendor_assign_school');
        $scl_table = TableRegistry::get('company');
        $cso_table = TableRegistry::get('canteen_student_order');
        $vendorid = $this->request->session()->read('vendor_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        $stylecss = 'style="display:none"';
        $error = '';
        $delivrs = '';
        if(!empty($_POST))
        {
            $sclid = $this->request->data('schoolid');
            $sctn = implode("," ,$this->request->data['chosesctn']);
            $date = date("d-m-Y", strtotime($this->request->data('enddate')));
            $time = $this->request->data('timings');
            $delivr = $this->request->data['deliver_un'];
            $wdate = strtotime($this->request->data('enddate'));
            $weekday = date("l", $wdate);
            $delivrs = implode("," ,$this->request->data['deliver_un']);
            $cls = $this->request->data['chosesctn'];
            $retrieve_timings = $fss_table->find()->select(['timings'])->where(['school_id' => $sclid, 'class_section IN' => $cls, 'weekday' => $weekday ])->group(['timings'])->toArray(); 
           
        
            $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'order_no', 'class.c_name', 'class.c_section', 'class.school_sections', 'food_item.food_img', 'student.id','student.f_name', 'student.l_name', 'student.adm_no', 'food_id', 'student_id', 'id', 'food_amount', 'quantity', 'total_amount', 'order_status', 'date', 'timings', 'weekday'])->join([
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
                ],
                'class' => 
                [
                    'table' => 'class',
                    'type' => 'LEFT',
                    'conditions' => 'class.id = student.class'
                ]
            ])->where(['canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.order_status IN' => $delivr, 'canteen_student_order.school_id' => $sclid, 'canteen_student_order.date' => $date, 'canteen_student_order.timings' => $time])->toArray(); 
        
            if(empty($retrieve_cso))
            {
                $error = "No data found";
            }
            else
            {
                $stylecss = 'style="display:block"';
            }
        }
        else
        {
            $retrieve_cso = '';
            $sclid = '';
            $timingss = '';
            $date = '';
            $sctn = '';
            $retrieve_timings = '';
        }
        $this->set("sclids", $sclid);
        $this->set("enddate1", $date);
        $this->set("sctn", $sctn);
        $this->set("time", $time);
        $this->set("stylecss", $stylecss);
        $this->set("time_opt", $retrieve_timings);
        $retrieve_vas = $vas_table->find()->select(['school_ids'])->where(['vendor_id' => $vendorid ])->first(); 
        $sclids = explode(",", $retrieve_vas['school_ids']);
        $this->set("deliv", $delivrs);
        $retrieve_scl = $scl_table->find()->select(['comp_name', 'id'])->where(['id IN' => $sclids])->toArray(); 
        $this->set("scl_details", $retrieve_scl);
        $this->set("cso_details", $retrieve_cso);
        $this->set("error", $error);
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function gettime()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->data('scl_id');
        $clsid = $this->request->session()->read('class_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        $class_table = TableRegistry::get('class');
        $vas_table = TableRegistry::get('vendor_assign_school');
       
        $date = strtotime($this->request->data('seldate'));
        $weekday = date("l", $date);
        
        $cls = $this->request->data('sctn');
        $retrieve_timings = $fss_table->find()->select(['timings'])->where(['school_id' => $compid, 'class_section IN' => $cls, 'weekday' => $weekday ])->group(['timings'])->toArray(); 
       
        $data = '<option value="" >Select Timings</option>';
        foreach($retrieve_timings as $timings)
        {
            $data .= '<option value="'.$timings['timings'].'">'.$timings['timings'].'</option>';
        }
        
        return $this->json($data);
    }
    
    public function ostatus()
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
            $stats = $cso_table->query()->update()->set([ 'order_status' => $status])->where([ 'id' => $uid  ])->execute();
			
            if($stats)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Student Food Order status changed"  ;
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
    
    public function deliverallfood()
    {
        $uid = $this->request->data['val'] ; 
        if(empty($uid[0]))
        {
            array_splice($uid, 0, 1);
        }
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $aid = implode(",", $uid);
        $cso_table = TableRegistry::get('canteen_student_order');
        $activ_table = TableRegistry::get('activity');

        foreach($uid as $ids)
        {
            $stats = $cso_table->query()->update()->set([ 'order_status' => 1])->where([ 'id ' => $ids  ])->execute();
        }
        
		
        if($stats)
        {
            $activity = $activ_table->newEntity();
            $activity->action =  "Order status changed"  ;
            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            $activity->value =    $aid;
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
       

        return $this->json($res);
    }
    
    public function export($sclid, $date, $time, $delvs)
    {
        $cso_table = TableRegistry::get('canteen_student_order');
        $scl_table = TableRegistry::get('company');
        $retrieve_scl = $scl_table->find()->select(['comp_name'])->where(['id' => $sclid])->first(); 
        $sclname = 'FoodReport_'.$date.'('.$time.')_'.$retrieve_scl['comp_name'].'.csv';
        $vendorid = $this->request->session()->read('vendor_id');
        $fss_table = TableRegistry::get('food_serve_scl');
        $delvry = explode("~", $delvs);
        $retrieve_cso = $cso_table->find()->select(['food_item.food_name', 'order_no', 'class.c_name', 'class.c_section', 'class.school_sections', 'food_item.food_img', 'student.id','student.f_name', 'student.l_name', 'student.adm_no', 'food_id', 'student_id', 'id', 'food_amount', 'quantity', 'total_amount', 'order_status', 'date', 'timings', 'weekday'])->join([
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
            ],
            'class' => 
            [
                'table' => 'class',
                'type' => 'LEFT',
                'conditions' => 'class.id = student.class'
            ]
        ])->where(['canteen_student_order.vendor_id' => $vendorid, 'canteen_student_order.order_status IN' => $delvry, 'canteen_student_order.school_id' => $sclid, 'canteen_student_order.date' => $date, 'canteen_student_order.timings' => $time])->toArray(); 
    
        $lang = $this->Cookie->read('language');
        if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
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
        
        foreach($retrieve_langlabel as $langlbl) 
        { 
            if($langlbl['id'] == '9') { $lbl9 = $langlbl['title'] ; }
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
        } 

        $data = array();
		if(!empty($retrieve_cso))
		{
    		foreach ($retrieve_cso as $value) 
	        {
	            $os = $value->order_status == 0 ? "Undeliver":"Delivered";
		        $data[] = array(
		            'order_no' => $value->order_no, 
		            'name' => ucfirst($value['student']['l_name']." ".$value['student']['f_name']."-".$value['student']['adm_no']), 
		            'class' => ucfirst($value['class']['c_name']."-".$value['class']['c_section']." (".$value['class']['school_sections'].")"), 
		            'foodname' => ucfirst($value['food_item']['food_name']), 
		            'quantity' =>  $value->quantity, 
		            'price' => '$'. $value->food_amount, 
		            'totalamount' => '$'. $value->total_amount, 
		            'order_status' => $os, 
		            'date' => $value->date, 
		            'timings' => $value->timings);
	        }
		}
		

        $filename = $sclname;
        $this->setResponse($this->getResponse()->withDownload($filename));
        $_header = array('Order No.', $lbl147 , $lbl9, $lbl2208, $lbl2226, $lbl2223, $lbl2224, $lbl2225, 'Date', $lbl2222);
        $_serialize = 'data';
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('data', '_header' , '_serialize'));
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
         
}

  




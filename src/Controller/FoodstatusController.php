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
class FoodstatusController extends AppController
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
        $sclid = $this->Cookie->read('id'); 
        
        $school_table = TableRegistry::get('company');
        $retrieve_schools = $school_table->find()->where(['status' => 1])->toArray();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $cso_table = TableRegistry::get('canteen_student_order');
		$session_id = $this->Cookie->read('sessionid');
		
		if(!empty($_POST))
		{
		    $sclidss = $this->request->data('schoolid');
		    $vndrid = $this->request->data('vendors');
		    
		    $cv_table = TableRegistry::get('vendor_assign_school');
            $vendor_table = TableRegistry::get('canteen_vendor'); 
            $getcv_data = $cv_table->find('all',array('conditions' => array('FIND_IN_SET(\''. $sclidss .'\',vendor_assign_school.school_ids)')))->toArray();
            $vdata = '';
            if(!empty($getcv_data))
            {
                foreach($getcv_data as $cv_data)
                {
                    $sel = '';
                    $get_vendor = $vendor_table->find()->where(['id' => $cv_data['vendor_id'] ])->first();
                    
                    if($vndrid == $get_vendor['id']) { $sel = "selected"; } 
                    $vdata .= '<option value="'.$get_vendor['id'].'" '.$sel.' >'.$get_vendor['l_name'].' '.$get_vendor['f_name'].' ('.$get_vendor['vendor_company'].')</option>';
                }
            }
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
		    if($startdate1 < $enddate1)
		    {
    		    $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'company.comp_name', 'student.f_name', 'student.l_name', 'student.adm_no', 'food_item.food_name', 'id', 'order_no', 'quantity', 'food_amount', 'total_amount', 'timings', 'date'])-> join([
    		        'student' => 
    					[
    					'table' => 'student',
    					'type' => 'LEFT',
    					'conditions' => 'student.id = canteen_student_order.student_id'
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
    					],
    				'food_item' => 
    					[
    					'table' => 'food_item',
    					'type' => 'LEFT',
    					'conditions' => 'food_item.id = canteen_student_order.food_id'
    					]
    		        ])->where(['canteen_student_order.order_status' => 4, 'vendor_id' => $vndrid, 'canteen_student_order.school_id' => $sclidss, 'canteen_student_order.str_date >= ' => $startdate1, 'canteen_student_order.str_date <= ' => $enddate1  ])->order(['canteen_student_order.id' => 'DESC' ])->toArray();   
		    }
		    else
		    {
		        $retrieve_cso = '';
		        $error = "Start date should be less than End date";
		    }
		}
		else
		{
		    $startdate1 = '';
    		$enddate1 = '';
    		$sclidss = '';
    		$vndrid = '';
		    $retrieve_cso = $cso_table->find()->select(['canteen_vendor.vendor_company', 'company.comp_name', 'student.f_name', 'student.l_name', 'student.adm_no', 'food_item.food_name', 'id', 'order_no', 'quantity', 'food_amount', 'total_amount', 'timings', 'date'])-> join([
		        'student' => 
					[
					'table' => 'student',
					'type' => 'LEFT',
					'conditions' => 'student.id = canteen_student_order.student_id'
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
					],
				'food_item' => 
					[
					'table' => 'food_item',
					'type' => 'LEFT',
					'conditions' => 'food_item.id = canteen_student_order.food_id'
					]
		        ])->where(['canteen_student_order.order_status' => 4])->order(['canteen_student_order.id' => 'DESC' ])->toArray();   
		    $error = '';
		}
		$this->set("vendordata", $vdata);
		$this->set("school_details", $retrieve_schools);
		$this->set("csodetails", $retrieve_cso);
		$this->set("error", $error);
		$this->set("vndrid", $vndrid);
		$this->set("startdate1", $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("sclids", $sclidss);
        $this->viewBuilder()->setLayout('usersa');        
    }
    
    public function ostatus()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $uid = $this->request->data('id') ;
        $sts = $this->request->data('sts') ;
        $cso_table = TableRegistry::get('canteen_student_order');
        $activ_table = TableRegistry::get('activity');

        $userid = $cso_table->find()->select(['id'])->where(['id'=> $uid ])->first();
		//$status = $sts == 1 ? 0 : 1;
        if($userid)
        {   
            $stats = $cso_table->query()->update()->set([ 'order_status' => $sts])->where([ 'id' => $uid  ])->execute();
			
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
    
    public function vndrfoodsts()
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
        $sts = $this->request->data('sts') ; 
        foreach($uid as $ids)
        {
            $stats = $cso_table->query()->update()->set([ 'order_status' => $sts])->where([ 'id ' => $ids  ])->execute();
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
}

  


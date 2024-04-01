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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class FeediscountController  extends AppController
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
        $this->viewBuilder()->setLayout('user');
        $feediscount_table = TableRegistry::get('feediscount');
        $compid =$this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_feehead = $feediscount_table->find()->where(['school_id' => $compid])->toArray();
            $this->set("feehead_details", $retrieve_feehead);  
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    public function addfeehead()
    {
        if ($this->request->is('ajax') && $this->request->is('post') ){

            $feediscount_table = TableRegistry::get('feediscount');
            $activ_table = TableRegistry::get('activity');
            $compid =$this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($compid))
            {
            $retrieve_feehead = $feediscount_table->find()->select(['id'  ])->where(['discount_name' => $this->request->data('discount_name'), 'school_id' => $compid  ])->count() ;
            
            if($retrieve_feehead == 0 ){
                $feehead = $feediscount_table->newEntity();

				$feehead->discount_name =  $this->request->data('discount_name');
				$feehead->percentage_amount =  $this->request->data('percentage_amount');
				$feehead->amount = $this->request->data('amount');
                $feehead->school_id = $compid;
                $feehead->added_date = strtotime('now');
                /*$feehead->coupon_code = $this->request->data('coupon_code');
                $feehead->valid_date = $this->request->data('valid_date');*/
                
                if($saved = $feediscount_table->save($feehead) ){
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Fee Discount Created"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                    $activity->value = md5($saved->id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) ){
                        $res = [ 'result' => 'success'  ];
                    }
                    else{
                $res = [ 'result' => 'activity not saved'  ];

                    }

                }
                else{
                    $res = [ 'result' => 'fee not saved'  ];
                }
            }    
            else{
                $res = [ 'result' => 'exist'  ];
            }    
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);

    }
    public function update()
    {   
        if($this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->request->data['id'];
            $feediscount_table = TableRegistry::get('feediscount');
            $update_feehead = $feediscount_table->find()->where(['id' => $id])->toArray(); 
			//$months = explode(',' , $update_feehead[0]['months']);  
            $data = ['name' => $update_feehead[0]['discount_name'], 'percentge' => $update_feehead[0]['percentage_amount'], 'amount' => $update_feehead[0]['amount'], 'code' => $update_feehead[0]['coupon_code'], 'vdate' => $update_feehead[0]['valid_date']];
            return $this->json($data);
        }  
    }
    public function editfeehead()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $feediscount_table = TableRegistry::get('feediscount');
            $activ_table = TableRegistry::get('activity');
            $compid =$this->request->session()->read('company_id');
            if(!empty($compid))
            {
            $retrieve_feehead = $feediscount_table->find()->select(['id'  ])->where(['discount_name' => $this->request->data('discount_name'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid ])->count() ;
            
            if($retrieve_feehead == 0 ){

                $id = $this->request->data('id');
				$discount_name =  $this->request->data('discount_name');
				$percnt =  $this->request->data('percentage_amount');
				$amt = $this->request->data('amount');
				$coupon_code = $this->request->data('coupon_code');
                $valid_date = $this->request->data('valid_date');

                if( $feediscount_table->query()->update()->set(['discount_name' => $discount_name, 'percentage_amount' => $percnt, 'amount' => $amt ])->where([ 'id' => $id  ])->execute())
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Fee Discount Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                    $activity->value = md5($id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) )
                    {
                        $res = [ 'result' => 'success'  ];

                    }
                    else
                    {
                        $res = [ 'result' => 'activity not saved'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'fee head not updated'  ];
                }
            } 
            else
            {
                $res = [ 'result' => 'exist'  ];
            }
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];

        }


        return $this->json($res);

    }
    public function delete()
    {
        $rid = $this->request->data('val') ;
        $feediscount_table = TableRegistry::get('feediscount');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $feeheadid = $feediscount_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        if($feeheadid)
        {   
            if($feediscount_table->delete($feediscount_table->get($rid)))
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Fee Head Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($rid)    ;
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
    public function getdata()
	{
        if ($this->request->is('ajax') && $this->request->is('post'))
		{
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
			$feediscount_table = TableRegistry::get('feediscount');
			$compid =$this->request->session()->read('company_id');
			//$session_id = $this->Cookie->read('sessionid') ;
			$employee_table = TableRegistry::get('employee');
			$feedetail_table = TableRegistry::get('feedetail');
            if(!empty($compid))
            {
			$retrieve_feehead = $feediscount_table->find()->where(['school_id' => $compid ])->toArray();
        
			$data = "";
			$datavalue = array();
			$sclsub_table = TableRegistry::get('school_subadmin');
			if($this->Cookie->read('logtype') == md5('School Subadmin'))
			{
			    
				$sclsub_id = $this->Cookie->read('subid');
				$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
				$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				//print_r($scl_privilage); 
			}
			
			$lang = $this->Cookie->read('language');   
            if($lang != "")
            {
                $lang = $lang;
            }
            else
            {
                $lang = 2;
            }
            
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
                if($langlbl['id'] == '345') { $amt = $langlbl['title'] ; } 
                if($langlbl['id'] == '2174') { $percnt = $langlbl['title'] ; } 
            } 
			
			foreach ($retrieve_feehead as $value) 
			{
				$studentsname = [];
				$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editfeediscount" data-toggle="modal"  data-target="#editfeehead" title="Edit"><i class="fa fa-edit"></i></button>';   
				$delete = '<button type="button" data-url="feediscount/delete" data-id='.$value['id'].' data-str="Coupon Discount" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';

                $d = '';
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$e = in_array(35, $scl_privilage) ? $edit : "";
					$d = in_array(36, $scl_privilage) ? $delete : "";
				}
				else
				{
					$e = $edit;
					$d = $delete;
				}
				
				if($value['feedtlid'] == "")
				{
					$de = $d;
				}
				else
				{
					$de = "";
				}
				if($value['percentage_amount'] == "amount")
				{
			        $amtper = $amt;
				}
				else
				{
				    $amtper = $percnt;
				}
				

				$data .=  '<tr>
							<td class="width45">
							<label class="fancy-checkbox">
									<input class="checkbox-tick" type="checkbox" name="checkbox">
									<span></span>
								</label>
							</td>
							<td>
								<span class="mb-0 font-weight-bold">'.ucwords($value['discount_name']).'</span>
							</td>
							<td>
								<span>'.ucwords($amtper).'</span>
							</td>
							<td>
								<span> '.$value['amount'].'</span>
							</td>
				
							<td>
								<span>'.date('d-m-Y',$value['added_date']).'</span>
							</td>
							<td>
								'.$e.$de.'     
							</td>
						</tr>';
					
			}
        
			$datavalue['html']= $data;  
			return $this->json($datavalue);
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }
    }
    
    public function applystudents()
    {
        $this->viewBuilder()->setLayout('user');
        $feediscount_table = TableRegistry::get('feediscount');
        $feestud_table = TableRegistry::get('student_fee');
        $class_table = TableRegistry::get('class');
        $discount_table = TableRegistry::get('discount_student');
        $compid =$this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessid =  $this->Cookie->read('sessionid');
        if(!empty($compid))
        {
            $retrieve_feedis = $feediscount_table->find()->where(['school_id' => $compid])->toArray();
            $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray();
            $this->set("class_details", $retrieve_class);  
            
            $retrieve_dis = $discount_table->find()->select(['discount_student.id', 'discount_student.student_id','discount_student.discount_id', 'discount_student.class_id', 'session.startyear','session.endyear','feediscount.discount_name', 'feediscount.amount', 'feediscount.percentage_amount', 'student.f_name', 'student.l_name', 'student.email','student.adm_no','class.c_name','class.c_section','class.school_sections' ])->join([
                'student' =>
                [
                    'table' => 'student',
                    'type' => 'LEFT',
                    'conditions' => 'student.id = discount_student.student_id'    
                ],
                'class' =>
                [
                    'table' => 'class',
                    'type' => 'LEFT',
                    'conditions' => 'class.id = discount_student.class_id'    
                ],
                'feediscount' =>
                [
                    'table' => 'feediscount',
                    'type' => 'LEFT',
                    'conditions' => 'feediscount.id = discount_student.discount_id'    
                ],
                'session' =>
                [
                    'table' => 'session',
                    'type' => 'LEFT',
                    'conditions' => 'session.id = discount_student.session_id'    
                ]
            ])->where(['discount_student.school_id' => $compid, 'discount_student.session_id' => $sessid])->toArray();
            
            foreach($retrieve_dis as $feedis)
            {
                //echo ",".$feedis['id']."-";
                $retrieve_studfee = $feestud_table->find()->where(['student_id' => $feedis['student_id'], 'class_id' => $feedis['class_id'], 'coupon_id' => $feedis['discount_id']])->count();
                $feedis->coupontaken = 0;
                if($retrieve_studfee != 0)
                {
                    $feedis->coupontaken = 1;
                }
            }
            //print_r($retrieve_dis); 
            //die;
            
            $session_table = TableRegistry::get('session');
            $getsession = $session_table->find()->toArray(); 
            $this->set("session_details", $getsession);  
            
            $this->set("dis_details", $retrieve_dis);  
            $this->set("fee_dis", $retrieve_feedis);  
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function getsessionchngedata()
    {
        $feediscount_table = TableRegistry::get('feediscount');
        $class_table = TableRegistry::get('class');
        $discount_table = TableRegistry::get('discount_student');
        $compid =$this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessid =  $this->request->data('sessionid');
        $clsid =  $this->request->data('cls');
            
        $retrieve_dis = $discount_table->find()->select(['discount_student.id','session.startyear','session.endyear','feediscount.discount_name', 'feediscount.amount', 'feediscount.percentage_amount', 'student.f_name', 'student.l_name', 'student.email','student.adm_no','class.c_name','class.c_section','class.school_sections' ])->join([
            'student' =>
            [
                'table' => 'student',
                'type' => 'LEFT',
                'conditions' => 'student.id = discount_student.student_id'    
            ],
            'class' =>
            [
                'table' => 'class',
                'type' => 'LEFT',
                'conditions' => 'class.id = discount_student.class_id'    
            ],
            'feediscount' =>
            [
                'table' => 'feediscount',
                'type' => 'LEFT',
                'conditions' => 'feediscount.id = discount_student.discount_id'    
            ],
            'session' =>
            [
                'table' => 'session',
                'type' => 'LEFT',
                'conditions' => 'session.id = discount_student.session_id'    
            ]
        ])->where(['discount_student.class_id' => $clsid, 'discount_student.session_id' => $sessid])->toArray();
        $data = '';
        foreach($retrieve_dis as $dis) 
        { 
            if($dis['feediscount']['percentage_amount'] == "amount") {
                $couponamt = "$".$dis['feediscount']['amount']; 
            } else { 
                $couponamt = $dis['feediscount']['amount']."%";
            }
             
            $data .= '<tr>
                <td>'. $dis['session']['startyear']."-".$dis['session']['endyear'] .'</td>
                <td>'. $dis['student']['l_name']." ".$dis['student']['f_name'] .'</td>
                <td>'. $dis['student']['adm_no'] .'</td>
                <td>'. $dis['class']['c_name']."-".$dis['class']['c_section']." (".$dis['class']['school_sections'].")" .'</td>
                <td>'. $dis['feediscount']['discount_name'] .'</td>
                <td>'. $couponamt .'</td>
                <td>
                    <button type="button" data-id="'. $dis['id'] .'" class="btn btn-sm btn-outline-secondary editstudentcoupn" data-toggle="modal"  data-target="#editstudentcoupn" title="Edit"><i class="fa fa-edit"></i></button>
                    <button type="button" data-id="'.$dis['id'].'" data-url="coupondelete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Coupon Delete" data-str="Student Coupon " data-type="confirm"><i class="fa fa-trash-o"></i></button>
                </td>
            </tr>';
        } 
        
        return $this->json($data); 
        
    }
    
    public function getstudnt()
    {
        $id = $this->request->data('cls');
        $compid =$this->request->session()->read('company_id');
        $sessionid = $this->Cookie->read('sessionid');
        
        $data = '';
        $studnet_tbl = TableRegistry::get('student');
        $data .= '<option value="">Choose Student</option>';
        $getstudnet = $studnet_tbl->find()->where(['school_id' => $compid, 'session_id' => $sessionid, 'status' => 1, 'class' => $id ])->toArray();
        $ids = [];
        if(!empty($getstudnet)) {
            foreach($getstudnet as $stud)
            {
                $ids[] = $stud['id'];
            }
            $stuids =implode(",", $ids);
            $data .= '<option value="'.$stuids.'~^all">All</option>';
            
            foreach($getstudnet as $stud)
            {
                $data .= '<option value="'.$stud['id'].'">'.$stud['l_name']." ".$stud['f_name']." (".$stud['email'] .') </option>';
            }
        }
        
        return $this->json($data);
    }
    
    public function addstuddiscount()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $discount_table = TableRegistry::get('discount_student');
            $activ_table = TableRegistry::get('activity');
            $compid =$this->request->session()->read('company_id');
            $sessionid = $this->Cookie->read('sessionid');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            
            $clsid = $this->request->data('class');
            $studids = explode("~^", $this->request->data['student'][0]);
                if($studids[1] == "all")
                {
                    $stdids = explode(",", $studids[0]);
                }
                else 
                {
                    $stdids = $this->request->data['student'];
                }
            $couponid = $this->request->data('discount_coupon');
            if(!empty($compid))
            {
                foreach($stdids as $studid) {
                $retrieve_discount = $discount_table->find()->select(['id'])->where(['session_id' => $sessionid, 'student_id' => $studid, 'class_id' => $clsid, 'school_id' => $compid , 'discount_id' => $couponid ])->count() ;
            
                if($retrieve_discount == 0 ){
                    $discount = $discount_table->newEntity();
                    $discount->discount_id =  $couponid;
    				$discount->student_id =  $studid;
    				$discount->class_id =  $clsid;
    				$discount->session_id = $sessionid;
                    $discount->school_id = $compid;
                    $discount->created_date = strtotime('now');
                
                    if($saved = $discount_table->save($discount) ){
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Student fee coupon applied"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                        $activity->value = md5($saved->id)   ;
                        $activity->origin = $this->Cookie->read('id')   ;
                        $activity->created = strtotime('now');
                        if($saved = $activ_table->save($activity) ){
                            $res = [ 'result' => 'success'  ];
                        }
                        else{
                            $res = [ 'result' => 'activity not saved'  ];
                        }
    
                    }
                    else{
                        $res = [ 'result' => 'data not saved'  ];
                    }
                }    
                else{
                    $res = [ 'result' => 'Student have already the same coupon '  ];
                } 
                }
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);

    }
    public function coupondelete()
    {
        $rid = $this->request->data('val') ; 
        $discount_table = TableRegistry::get('discount_student');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $feeheadid = $discount_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        if($feeheadid)
        {   
            if($discount_table->delete($discount_table->get($rid)))
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Fee Coupon of Student Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($rid)    ;
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
    
    public function coupnupdate()
    {   
        if($this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->request->data['id']; 
            $feediscount_table = TableRegistry::get('feediscount');
            $discountstu_table = TableRegistry::get('discount_student');
            $student_table = TableRegistry::get('student');
            $class_table = TableRegistry::get('class');
            
            $compid =$this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sessid =  $this->Cookie->read('sessionid');
            $discountstudent = $discountstu_table->find()->where(['id' => $id])->first(); 
            
            $getstdnt = $student_table->find()->where(['school_id' => $compid, 'class' => $discountstudent['class_id'], 'session_id' => $discountstudent['session_id'] ])->toArray(); 
            $student = '<option value="">Choose Student</option>';
            foreach($getstdnt as $val)
            {
                $selc = '';
                if($val['id'] == $discountstudent['student_id'])
                {
                    $selc = 'selected';
                }
                $student .= '<option value="'.$val['id'].'" '.$selc.'>'. $val['l_name']." ". $val['f_name'].'</option>';
            }
            
            $getclass = $class_table->find()->where(['school_id' => $compid])->toArray(); 
            $class = '<option value="">Choose Class</option>';
            foreach($getclass as $val)
            {
                $selc = '';
                if($val['id'] == $discountstudent['class_id'])
                {
                    $selc = 'selected';
                }
                $class .= '<option value="'.$val['id'].'" '.$selc.'>'. $val['c_name']."-". $val['c_section']." (".$val['school_sections'].")".'</option>';
            }
            
            $getfeedis = $feediscount_table->find()->where(['school_id' => $compid])->toArray(); 
            $feedis = '<option value="">Choose Coupon</option>';
            foreach($getfeedis as $val)
            {
                $selc = '';
                if($val['id'] == $discountstudent['discount_id'])
                {
                    $selc = 'selected';
                }
                $feedis .= '<option value="'.$val['id'].'" '.$selc.'>'. $val['discount_name']." (".$val['percentage_amount']."-". $val['amount'].")".'</option>';
            }
            $data = ['student' => $student, 'class' => $class, 'discount' => $feedis ];
            return $this->json($data);
        }  
    }
    
    public function editdiscstudent()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $discount_table = TableRegistry::get('discount_student');
            $activ_table = TableRegistry::get('activity');
            $compid =$this->request->session()->read('company_id');
            if(!empty($compid))
            {
            $retrieve_discount = $discount_table->find()->select(['id'])->where(['discount_id' => $this->request->data('discount_coupon'), 'student_id' => $this->request->data('student'), 'id IS NOT' => $this->request->data('id')  , 'school_id' => $compid ])->count() ;
            
            if($retrieve_discount == 0 ) {

                $id = $this->request->data('id');

                if( $discount_table->query()->update()->set(['class_id' => $this->request->data('class'), 'student_id' => $this->request->data('student'), 'discount_id' => $this->request->data('discount_coupon') ])->where([ 'id' => $id  ])->execute())
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Coupon Discount Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                    $activity->value = md5($id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) )
                    {
                        $res = [ 'result' => 'success'  ];

                    }
                    else
                    {
                        $res = [ 'result' => 'activity not saved'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'Student coupon not updated'  ];
                }
            } 
            else
            {
                $res = [ 'result' => 'This student already have same coupon.'  ];
            }
            }
            else
            {
                return $this->redirect('/login/') ;   
            }
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
        
}

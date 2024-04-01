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
class FeedetailController  extends AppController
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
            public function index(){

                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->query('id'); 
                $feedetail_table = TableRegistry::get('feedetail');
                $feehead_table = TableRegistry::get('feehead');
                $fee_structure_table = TableRegistry::get('fee_structure');
                $class_table = TableRegistry::get('class');
                $compid =$this->request->session()->read('company_id');
                
                if(!empty($compid))
                {
                
                    $retrieve_feeset = $fee_structure_table->find()->where(['fee_structure.id' => $id ])->toArray();
    		        //print_r($retrieve_feeset);
    				$session_id = $retrieve_feeset[0]['start_year']; 
    
                    $retrieve_feedetail = $feedetail_table->find()->select(['feedetail.id'  , 'feedetail.amount'  ,'feehead.head_name' ])->join([
                        'feehead' => 
                            [
                            'table' => 'feehead',
                            'type' => 'LEFT',
                            'conditions' => 'feehead.id = feedetail.fee_h_id'
                        ]
                    ])->where(['feedetail.school_id' => $compid , 'feedetail.session_id' => $session_id ])->toArray();
    
                    
    
                    $retrieve_feehead = $feehead_table->find()->select(['id' ,'head_name'])->where(['school_id' => $compid  ])->toArray();
    
                    $retrieve_class = $class_table->find()->select(['id', 'c_name', 'c_section', 'school_sections'])->where(['id' => $retrieve_feeset[0]['class_id']  ])->toArray(); 
                    if(!empty($retrieve_feedetail))
                    {
                        foreach($retrieve_feedetail as $feedetail)
                        {
                            $totlamt[] = $feedetail[0]['amount'];
                        }
                        $amtleft = array_sum($totlamt);
                    }
                    else
                    {
                        $amtleft = 0;
                    }
                    
                    $this->set("amtleft", $amtleft); 
                    $this->set("class_details", $retrieve_class); 
                    $this->set("feedetail_details", $retrieve_feedetail);  
                    $this->set("feehead_details", $retrieve_feehead); 
                    $this->set("feeset_details", $retrieve_feeset); 
                    
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }


            public function addfeedet()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
					$feestpid = $this->request->data('fee_s_id');
                    $feedetail_table = TableRegistry::get('feedetail');
                    $feestruc_table = TableRegistry::get('fee_structure');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($compid)) 
                    {
    		            $retrieve_feehead = $feedetail_table->find()->select(['id'  ])->where(['fee_h_id' => $this->request->data('head_name'),  'fee_s_id' => $feestpid ])->count() ;
                        $session_id = $this->request->data('session_id');
                        if($retrieve_feehead == 0 )
                        {
                            $feedet = $feedetail_table->newEntity();
                            $feedet->fee_s_id =  $this->request->data('fee_s_id');
                            $feedet->fee_h_id =  $this->request->data('head_name');
                            $feedet->amount =  $this->request->data('amount');
                            $feedet->school_id = $compid;
    			            $feedet->session_id = $this->request->data('session_id');
    			            $retrieve_tamt = $feestruc_table->find()->select(['amount'])->where([ 'id' => $feestpid ])->toArray() ;
    			            //print_r($retrieve_tamt);
    			            $totalamt = $retrieve_tamt[0]['amount'];
    			            $feedet->created_date = time();
    			            
    			            $lang = $this->Cookie->read('language');
    			            if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                                if($langlbl['id'] == '1646') { $feestamt = $langlbl['title'] ; } 
                            } 
    			            
    			            $retrieve_feedtlamt = $feedetail_table->find()->select(['amount'])->where([ 'fee_s_id' => $feestpid, 'session_id' => $session_id ])->toArray() ;
    			            if(!empty($retrieve_feedtlamt))
    			            {
                                foreach($retrieve_feedtlamt as $amtu)
                                {
                                    $ftamt[] = $amtu['amount'];
                                }
                                $fdtlamt = array_sum($ftamt);
                                $dtlamttl = $fdtlamt + $this->request->data('amount');
    			            }
    			            else
    			            {
    			                $dtlamttl = 0;
    			            }
                            
                            if($totalamt >= $dtlamttl)
                            {
                                if($saved = $feedetail_table->save($feedet) ){
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Fee Detail Created"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($saved->id)   ;
                                    $activity->origin = $this->Cookie->read('id')   ;
                                    $activity->created = strtotime('now');
                                    if($saved = $activ_table->save($activity) )
                                    {
                                        $retrieve_amt = $feedetail_table->find()->select(['amount'])->where([ 'fee_s_id' => $feestpid, 'session_id' => $session_id ])->toArray() ;
                                        foreach($retrieve_amt as $amt)
                                        {
                                            $tamt[] = $amt['amount'];
                                        }
                                        $amtused = array_sum($tamt);
                                        $amtleft = $totalamt - $amtused;
                                        $res = [ 'result' => 'success' , 'amt_left' => $amtleft];
                                    }
                                    else
                                    {
                                        $res = [ 'result' => 'activity not saved'  ];
                                    }
                                }
                                else{
                                    $res = [ 'result' => 'fee not saved'  ];
                                }
                            }
                            else
                            {
                                $res = [ 'result' => $feestamt ];
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
                if($this->request->is('post')){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data['id'];
                
                $feedetail_table = TableRegistry::get('feedetail');

                $update_feedetail = $feedetail_table->find()->select(['fee_s_id', 'fee_h_id', 'amount'])->where(['id' => $id])->toArray(); 
                    
                $data = ['setup' => $update_feedetail[0]['fee_s_id'], 'head' => $update_feedetail[0]['fee_h_id'], 'amount' => $update_feedetail[0]['amount'] ];
                
                return $this->json($data);

                }  
            }



            public function editfeedet(){
                if ($this->request->is('ajax') && $this->request->is('post')){

                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $feedetail_table = TableRegistry::get('feedetail');
                    $feestruc_table = TableRegistry::get('fee_structure');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');
		            $feesturcid = $this->request->data('fee_s_id');
                    $retrieve_feehead = $feedetail_table->find()->select(['id'  ])->where(['fee_s_id' => $this->request->data('class'), 'id IS NOT' => $this->request->data('id') ])->count() ;
                    
                    $retrieve_feestrc = $feestruc_table->find()->where(['id' => $feesturcid])->toArray() ;
                    $session_id = $retrieve_feestrc[0]['start_year'];
                    $totalamt = $retrieve_feestrc[0]['amount'];
                    $lang = $this->Cookie->read('language');
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1646') { $feestamt = $langlbl['title'] ; } 
                    } 
                            
                            
                    if($retrieve_feehead == 0 )
                    {
                        $retrieve_feedtlamt = $feedetail_table->find()->where(['id IS NOT' => $this->request->data('id'), 'fee_s_id' => $feesturcid ])->toArray() ;
                        if(!empty($retrieve_feedtlamt))
			            {
                            foreach($retrieve_feedtlamt as $amtu)
                            {
                                $ftamt[] = $amtu['amount'];
                            }
                            $fdtlamt = array_sum($ftamt);
                            $dtlamttl = $fdtlamt + $this->request->data('amount');
			            }
			            else
			            {
			                $dtlamttl = 0;
			            }
                        $id = $this->request->data('id');
                        $head_name =  $this->request->data('head_name') ;
                        $amount =  $this->request->data('amount') ;
                        
                        if($totalamt >= $dtlamttl)
                        {
                            if( $feedetail_table->query()->update()->set([ 'fee_h_id' => $head_name , 'amount'=>$amount ])->where([ 'id' => $id  ])->execute())
                            {
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Fee Detail Updated"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($id)   ;
                                $activity->origin = $this->Cookie->read('id')   ;
                                $activity->created = strtotime('now');
                                if($saved = $activ_table->save($activity) )
                                {
                                    $retrieve_amt = $feedetail_table->find()->select(['amount'])->where([ 'fee_s_id' => $feesturcid])->toArray() ;
                                    foreach($retrieve_amt as $amt)
                                    {
                                        $tamt[] = $amt['amount'];
                                    }
                                    $amtused = array_sum($tamt);
                                    $amtleft = $totalamt - $amtused;
                                    $res = [ 'result' => 'success' , 'amt_left' => $amtleft];
        
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
                            $res = [ 'result' => $feestamt ];
                        }
                    } 
                    else
                    {
                        $res = [ 'result' => 'exist'  ];
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
                $feedetail_table = TableRegistry::get('feedetail');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $feeheadid = $feedetail_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($feeheadid)
                {   
                    if($feedetail_table->delete($feedetail_table->get($rid)))
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Fee Detail Deleted"  ;
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
            

            public function getdata(){
                if ($this->request->is('ajax') && $this->request->is('post'))
                {
					$feestpid =$this->request->data('id');
					$feedetail_table = TableRegistry::get('feedetail');
					$compid =$this->request->session()->read('company_id');
					$session_id = $this->Cookie->read('sessionid');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_feedetail = $feedetail_table->find()->select(['feedetail.id'  , 'feedetail.amount'  ,'feehead.head_name' ])->join([
                        'feehead' => 
                            [
                            'table' => 'feehead',
                            'type' => 'LEFT',
                            'conditions' => 'feehead.id = feedetail.fee_h_id'
                        ]
                    ])->where(['feedetail.fee_s_id' => $feestpid  ])->toArray();
                    
                    //print_r($retrieve_feedetail); die;
                    
                    if(!empty($retrieve_feedetail))
                    {
                        foreach($retrieve_feedetail as $feedetail)
                        {
                            $totlamt[] = $feedetail['amount'];
                        }
                        $amtleft = array_sum($totlamt);
                    }
                    else
                    {
                        $amtleft = 0;
                    }

                $data = "";
                $datavalue = array();
                foreach ($retrieve_feedetail as $value) {
                    
                $data .=  '<tr>
                            <td class="width45">
                            <label class="fancy-checkbox">
                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.$value['feehead']['head_name'].'</span>
                            </td>
                            <td>
                                <span>$'.$value['amount'].'</span>
                            </td>
                            <td>
                                <button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editfeedets" data-toggle="modal"  data-target="#editfeedet" title="Edit"><i class="fa fa-edit"></i></button>
                            
                                <button type="button" data-url="feedetail/delete" data-id='.$value['id'].' data-str="Fee detail" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>';

                    
                }
                
                $datavalue['html']= $data;
                $datavalue['amt_left']= $amtleft;
                return $this->json($datavalue);

                }
            }
			
	        public function updatesetupclass()
            {   
                if($this->request->is('post')){

                $classId = $this->request->data('classId');
                $Id = $this->request->data('Id');
                $feeset_table = TableRegistry::get('feesetup');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $update_class = $feeset_table->query()->update()->set([ 'class' => $classId ])->where([ 'id' => $Id  ])->execute();
				$res = [ 'result' => 'updated'  ];
					return $this->json($res);

                }  
            }
			
	        public function updatesetupsess()
            {   
                if($this->request->is('post')){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $sess_name = $this->request->data('sessName');
                $Id = $this->request->data('Id');
                $feeset_table = TableRegistry::get('feesetup');

                $update_class = $feeset_table->query()->update()->set([ 'sess_name' => $sess_name ])->where([ 'id' => $Id  ])->execute();
				if($update_class)
				{
					$res = [ 'result' => 'updated'  ];
					return $this->json($res);
				}
				

                }  
            }
            
            public function view()
            {
                if($this->request->is('post'))
                {
                    $strucid = $this->request->data('id');
                    $fee_structure_table = TableRegistry::get('fee_structure');
                    $feedetail_table = TableRegistry::get('feedetail');
                    $retrieve_feestruc = $fee_structure_table->find()->where(['id' => $strucid  ])->toArray() ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    //$retrieve_feedtl = $feedetail_table->find()->where(['fee_s_id' => $strucid  ])->toArray() ;
                    $retrieve_feedtl = $feedetail_table->find()->select(['feedetail.id'  , 'feedetail.amount'  ,'feehead.head_name' ])->join([
                        'feehead' => 
                            [
                            'table' => 'feehead',
                            'type' => 'LEFT',
                            'conditions' => 'feehead.id = feedetail.fee_h_id'
                        ]
                    ])->where(['feedetail.fee_s_id' => $strucid  ])->toArray();
                    
                    $data = "";
                    $datavalue = array();
                    foreach ($retrieve_feedtl as $value) 
                    {
                        $data .=  '<tr>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['feehead']['head_name'].'</span>
                                </td>
                                <td>
                                    <span>$'.$value['amount'].'</span>
                                </td>
                            </tr>';
                    }
                    
                    $datavalue['html']= $data;
                    $datavalue['feestruc']= $retrieve_feestruc;
                    return $this->json($datavalue);
                    
                }
            }


    }

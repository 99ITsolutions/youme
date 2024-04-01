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
class BalanceController  extends AppController
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

                $balance_table = TableRegistry::get('balance');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_balance = $balance_table->find()->select(['id', 'acc_no', 's_name' , 's_f_name'  ,'bal_amt'])->where([ 'school_id'=> $compid , 'session_id' => $session_id ])->toArray();

                $this->set("balance_details", $retrieve_balance); 
                $this->viewBuilder()->setLayout('user');

            }

            public function add(){

                $student_table = TableRegistry::get('student');
                $compid = $this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		

                $retrieve_student_acc = $student_table->find()->distinct('acc_no')->select(['id', 'acc_no' ])->where([ 'school_id'=> $compid , 'session_id' => $session_id  ])->toArray();

                $this->set("student_acc_details", $retrieve_student_acc); 

                $this->viewBuilder()->setLayout('user');
            }

            public function addbal(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $compid = $this->request->session()->read('company_id');  
		    $session_id = $this->Cookie->read('sessionid');
                    $balance_table = TableRegistry::get('balance');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_balance = $balance_table->find()->select(['id' ])->where(['acc_no' => $this->request->data('acc_no'), 'school_id'=> $compid , 'session_id' => $session_id ])->count() ;
                    
                    if($retrieve_balance == 0 )
                    {   

                        $balance = $balance_table->newEntity();

                        $balance->acc_no =  $this->request->data('acc_no')  ;
                        $balance->s_name =  $this->request->data('s_name')  ;
                        $balance->s_f_name = $this->request->data('s_f_name') ;
                        $balance->bal_amt = $this->request->data('bal_amt') ;
                        $balance->school_id = $compid;
			$balance->session_id = $session_id;
            
                        if($saved = $balance_table->save($balance) )
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Balance Created"  ;
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
                                $res = [ 'result' => 'balance not saved'  ];
                            }
                            
                    } 
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);

            }
			public function studentinfo(){
                
                $student_table = TableRegistry::get('student');
                $compid = $this->request->session()->read('company_id');
				$accId = $this->request->data('accId');
				$retrieve_student = $student_table->find()->select(['id', 's_name', 's_f_name' ])->where([ 'acc_no'=> $accId , 'school_id' => $compid  ])->toArray();

                $this->set("student_details", $retrieve_student);
                $this->viewBuilder()->setLayout('user');
            }

            public function edit($bid){
                
                $student_table = TableRegistry::get('student');
                $balance_table = TableRegistry::get('balance');
                $compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

                $retrieve_balance = $balance_table->find()->select(['id', 'acc_no', 's_name' , 's_f_name'  ,'bal_amt'])->where([ 'school_id'=> $compid , 'md5(id)' => $bid ])->toArray();

                $retrieve_acc = $balance_table->find()->select([ 'acc_no'])->where([ 'school_id'=> $compid , 'session_id'=> $session_id ])->toArray();

                $retrieve_student = $student_table->find()->select(['id', 's_name', 's_f_name' ])->where([ 'school_id'=> $compid , 'session_id' => $session_id ])->toArray();


                $this->set("student_details", $retrieve_student);
                $this->set("balance_details", $retrieve_balance); 
                $this->set("acc_details", $retrieve_acc); 
                $this->viewBuilder()->setLayout('user');
            }

            public function editbal()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $balance_table = TableRegistry::get('balance');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');  
		   
		    $session_id = $this->Cookie->read('sessionid');
		
                    $retrieve_balance = $balance_table->find()->select(['id' ])->where(['acc_no' => $this->request->data('acc_no') , 'id <>' => $this->request->data('id'), 'session_id' => $session_id , 'school_id'=> $compid ])->count() ;
                    
                    if($retrieve_balance == 0 )
                    { 
                        $bid = $this->request->data('id');
                        $acc_no = $this->request->data('acc_no');
                        //$s_name = $this->request->data('s_name');
                        //$s_f_name = $this->request->data('s_f_name');
                        $bal_amt = $this->request->data('bal_amt');
                       
                        if( $balance_table->query()->update()->set([ 'acc_no' => $acc_no ,  'bal_amt' => $bal_amt ])->where([ 'id' => $bid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Balance update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($bid); 
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
                        $res = [ 'result' => 'exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }

            public function view($bid){
                
                $student_table = TableRegistry::get('student');
                $balance_table = TableRegistry::get('balance');
                $compid = $this->request->session()->read('company_id');
		
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_balance = $balance_table->find()->select(['id', 'acc_no', 's_name' , 's_f_name'  ,'bal_amt'])->where([ 'school_id'=> $compid , 'md5(id)' => $bid ])->toArray();

                $retrieve_student = $student_table->find()->select(['id', 's_name', 's_f_name' ])->where(['session_id'=> $session_id , 'school_id'=> $compid  ])->toArray();


                $this->set("student_details", $retrieve_student);
                $this->set("balance_details", $retrieve_balance); 
                $this->viewBuilder()->setLayout('user');
            } 

            public function delete()
            {
                $sid = (int) $this->request->data('val') ; 
                $balance_table = TableRegistry::get('balance');
                $activ_table = TableRegistry::get('activity');
                
                $blid = $balance_table->find()->select(['id'])->where(['id'=> $sid ])->first();
                
                if($blid)
                {   
                    $del = $balance_table->query()->delete()->where([ 'id' => $sid ])->execute(); 
                    if($del)
                    { 
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Balance Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($sid)    ;
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


            public function getdetail()
            {   
                if($this->request->is('post'))
                {

                $acc_no = $this->request->data['acc'];
		
		$compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

                $student_table = TableRegistry::get('student');
                $balance_table = TableRegistry::get('balance');
                
                $get_student_details = $balance_table->find()->select([ 's_name', 's_f_name' ])->where([ 'acc_no'=> $acc_no , 'session_id'=> $session_id , 'school_id'=> $compid   ])->toArray();
                
                $data = ['s_name' => $get_student_details[0]['s_name'] , 's_f_name'=>$get_student_details[0]['s_f_name']];

                return $this->json($data);

                }  
            }
	    
	    public function getstdntdetail()
            {   
                if($this->request->is('post'))
                {

                $acc_no = $this->request->data['acc'];

                $student_table = TableRegistry::get('student');
                $balance_table = TableRegistry::get('balance');
                
		$compid = $this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

                $get_student_details = $student_table->find()->select([ 's_name', 's_f_name' ])->where([ 'acc_no'=> $acc_no ,'session_id'=> $session_id , 'school_id'=> $compid   ])->toArray();
                
                $data = ['s_name' => $get_student_details[0]['s_name'] , 's_f_name'=>$get_student_details[0]['s_f_name']];

                return $this->json($data);

                }  
            }
		

            public function getfather()
            {   
                if($this->request->is('ajax') && $this->request->is('get') ){

                $name = $this->request->query['name'];
                
                $student_table = TableRegistry::get('student');
                $setting_table = TableRegistry::get('stdnt_h_setting');
                $compid =$this->request->session()->read('company_id');

		$session_id = $this->Cookie->read('sessionid');

                $colname = $setting_table->find()->select(['col_type'])->where(['session_id'=> $session_id  ,'school_id'=>$compid])->toArray();
                $col_type = explode(',', $colname[0]['col_type']);
                    
                    $student = array();
                    
                    array_push($student,'student.s_name','student.id','student.acc_no');

                    if(in_array("Student Name", $col_type)){
                        array_push($student,'student.s_name');
                    }
                    if(in_array("Father Name", $col_type)){
                        array_push($student,'student.s_f_name');  
                    }
                    if(in_array("Mother Name", $col_type)){
                        array_push($student,'student.s_m_name');
                    }
                    if(in_array("Account Number", $col_type)){
                        array_push($student,'student.acc_no');
                    }
                    if(in_array("Admission Number", $col_type)){
                        array_push($student,'student.adm_no'); 
                    }
                    if(in_array("Address", $col_type)){
                        array_push($student,'resi_add1'); 
                    }
                    if(in_array("Class", $col_type)){
                        array_push($student,'class.c_name'); 
                    }
                    if(in_array("Section", $col_type)){
                       array_push($student,'class.c_section'); 
                    }
                    if(in_array("Session", $col_type)){
                        array_push($student,'c_sess_name'); 
                    } 

                $get_student_name = $student_table->find()->select($student)->join(['class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = student.class'
                    ]
                ])->where(['student.s_name LIKE' => "%$name%" , 'student.school_id'=> $compid ])->toArray(); 

                
                return $this->json($get_student_name);

                } 

            }
			
		public function importbalance()
            {   			
                if ( $this->request->is('post'))
                {  			

			$schoolid = $this->request->session()->read('company_id');

			$session_id = $this->Cookie->read('sessionid');
			
                    $balance_table = TableRegistry::get('balance');
                    $activ_table = TableRegistry::get('activity');
					
                    if(!empty($this->request->data['file']['name']))
                    {
                        $fileexe = explode('.', $this->request->data['file']['name']);
                    
                        if($fileexe[1] =='csv')
                        {

                            $filename = $this->request->data['file']['tmp_name'];                           
                            $handle = fopen($filename, "r");                            
                            $header = fgetcsv($handle);
                            $i = 0;                            
							while (($row = fgetcsv($handle)) !== FALSE) 
                            {						
                                $i++;
                                //$adm_no++;
                                $data = array();
                                $acc_no = $row[0];								
								$amt = $row[1];		

								$retrieve_balance = $balance_table->find()->select(['id' ])->where(['acc_no' => $acc_no, 'school_id'=> $schoolid])->count() ;
                    
								if($retrieve_balance == 0 )
								{  
									$student_table = TableRegistry::get('student');                
									$get_student_details = $student_table->find()->select([ 's_name', 's_f_name' ])->where([ 'acc_no'=> $acc_no  ])->toArray();
								
									$bal_amt = $balance_table->newEntity();
									
									$bal_amt->acc_no =  $acc_no;
									$bal_amt->bal_amt =  $amt;
									$bal_amt->s_name =  $get_student_details[0]['s_name'];
									$bal_amt->s_f_name =  $get_student_details[0]['s_f_name'];
									$bal_amt->school_id =  $schoolid;
									$bal_amt->session_id = $session_id;							
									
									$saved = $balance_table->save($bal_amt);
									
									
								} 
								else
								{
									$saved = $balance_table->query()->update()->set(['bal_amt' => $amt ])->where([ 'acc_no' => $acc_no  ])->execute();
								} 
								
								if($saved)
								{  
									$res = [ 'result' => 'success'  ];
								}
								else{
									$res = [ 'result' => 'Error! File not uploaded.'  ];
								}

                            }
							
                        fclose($handle);                        
                        }
                        else
                        {
                            $res = [ 'result' => 'Only csv format file are allowed'];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Empty file'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);    

            }
}
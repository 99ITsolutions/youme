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
//use \stdClass;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class EnquiryController  extends AppController
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
			
			
			$school_id =$this->request->session()->read('company_id');
			$enquiry_form = TableRegistry::get('enquiry_form');
			$form_fields = TableRegistry::get('form_values');
			
			$retrieve_form = $enquiry_form->find()->select([ 'enquiry_form.id', 'enquiry_form.title', 'enquiry_form.link', 'enquiry_form.created_date', 'count' => "COUNT(DISTINCT form_values.unique_id)"])->join(['form_values' => 
							[
							'table' => 'form_values',
							'type' => 'LEFT',
							'conditions' => 'form_values.form_id = enquiry_form.id'
						]
					])->where(['school_id' => $school_id ])->group(['enquiry_form.id'])->toArray(); 
			
			$this->set("form_details", $retrieve_form);   
			$this->viewBuilder()->setLayout('user');

		}        
            

        public function add(){

			$this->viewBuilder()->setLayout('user');
			
		}
		
		
        
        public function addform(){
			if ($this->request->is('post'))
			{
	
				$enquiryfields_table = TableRegistry::get('enquiry_form_fields');
				$enquiry_table = TableRegistry::get('enquiry_form'); 
				$picture = "";
				$background = "";			
				
				if(!empty($this->request->data['picture']['name']))
				{   
					if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
					{
						$picture =  $this->request->data['picture']['name'];
						$filename = $this->request->data['picture']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['picture']['tmp_name'], $uploadfile))
						{
							$this->request->data['picture'] = $filename; 
						}
					}    
				}
				
				if(!empty($this->request->data['background']['name']))
				{   
					if($this->request->data['background']['type'] == "image/jpeg" || $this->request->data['background']['type'] == "image/jpg" || $this->request->data['background']['type'] == "image/png" )
					{
						$background =  $this->request->data['background']['name'];
						$filename = $this->request->data['background']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['background']['tmp_name'], $uploadfile))
						{
							$this->request->data['background'] = $filename; 
						}
					}    
				}
				
				
				$n_email = $this->request->data('n_email') == "on" ? "1" :"0";
				$c_email = $this->request->data('c_email') == "on" ? "1" :"0";

				$enquiry = $enquiry_table->newEntity();

				$enquiry->title =  $this->request->data('title')  ;
				$enquiry->notification_email =  $n_email  ;
				$enquiry->confirmation_email = $c_email ;
				$enquiry->instruction = $this->request->data('instructions') ;
				$enquiry->notes = $this->request->data('notes') ;
				$enquiry->school_id =  $this->request->data('school_id')  ;
				$enquiry->logo =  $picture  ;
				$enquiry->background =  $background  ;
				$enquiry->created_date =  time()  ;
				
				//$title = explode(" ",$enquiry->title);
				//$page_link = implode("_", $title);
				$enquiry->link = $enquiry->title." Form";
				
				if($saved = $enquiry_table->save($enquiry) )
				{	
					//$enquiryfields = $enquiryfields_table->newEntity();
					$label =  $this->request->data('label') ;
					//echo $label;
					foreach($label as $label_key=>$name):	
					
						$enquiryfields = $enquiryfields_table->newEntity();					
						$enquiryfields->form_id = $saved->id;
						$enquiryfields->fields = $name;
						$enquiryfields->name = $this->request->data['inputname'][$label_key]  ;						
						$enquiryfields->type = $this->request->data['type'][$label_key] ;
						$enquiryfields->options = $this->request->data['options'][$label_key];
						
						$fields = $enquiryfields_table->save($enquiryfields);
						//echo $label_key;
						 //print_r($enquiryfields);
					endforeach;
					if( $fields)
					{
						$res = [ 'result' => 'success'  ];
						//return $this->redirect('/enquiry');
					}
					else
					{
						$res = [ 'result' => 'form fields not saved'  ];
					}

				}
				else{
					$res = [ 'result' => 'form not saved'  ];
				}                  

            }
			else
			{
				$res = [ 'result' => 'invalid operation'  ];

			}


            return $this->json($res);

        }
		
		 public function export($fid)
            {   			
                $form_fields = TableRegistry::get('enquiry_form_fields');
				$form_values = TableRegistry::get('form_values');
				$headers_val = array();
                $col = $form_fields->find()->select(['fields', 'id'])->where(['md5(form_id)' => $fid   ])->toArray() ;  
				//$headers_val[] = 'unique_id';
				foreach($col as $header):				
					$headers_val[] = $header->fields;				
				endforeach;
				$header= count($headers_val);
				
				$file_url = $_SERVER['DOCUMENT_ROOT'].'/school/webroot/file.csv';
				
				$file = fopen($file_url, 'w');
				fputcsv($file, $headers_val);
				
				$uni_id = $form_values->find()->select(['unique_id'])->where(['md5(form_id)' => $fid   ])->toArray() ; 
				$i = 0 ;
				foreach($uni_id as $key){
					$uid[] = $key->unique_id;
				}
				$uid = array_unique($uid);
				foreach($uid as $_uid):
					// $field_id[] = $key->field_id;
					$field_value = $form_values->find()->select(['value'])->where(['unique_id' => $_uid ])->toArray();
					
					foreach ($field_value as $line) { 
						
							$row[$i][] = ($line->value ?: 'N/A');					
					
					}
					$i++;
				
				endforeach;
			
				
				foreach($row as $_row){
					fputcsv($file, $_row);

				}
               
				
				fclose($file);
            
            
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary"); 
            header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
            
            readfile($file_url);

				
				exit();
					
						
            }


		public function edit($fid){

			$enquiryfields_table = TableRegistry::get('enquiry_form_fields');
			$enquiry_table = TableRegistry::get('enquiry_form'); 

			$retrieve_form = $enquiry_table->find()->select(['id', 'school_id', 'title' , 'logo' , 'background' , 'instruction', 'notes', 'notification_email' , 'confirmation_email' ])->where(['md5(id)' => $fid   ])->toArray();

			$retrieve_fields = $enquiryfields_table->find()->select([ 'fields' , 'name', 'options', 'type' ])->where([ 'md5(form_id)' => $fid  ])->toArray();

			$this->set("field_details", $retrieve_fields);
			$this->set("form_details", $retrieve_form); 
			$this->viewBuilder()->setLayout('user');
		}            

        public function editform(){
			
            if ($this->request->is('post') )
			{	
	
				$fid = $this->request->data('id');
				
				$enquiryfields_table = TableRegistry::get('enquiry_form_fields');
				$enquiry_table = TableRegistry::get('enquiry_form'); 
				 //$picture = $this->request->data['picture']['name'];
				 //$background = $this->request->data['background']['name'];
				
				if(!empty($this->request->data['picture']['name']))
				{   
					if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
					{
						$picture =  $this->request->data['picture']['name'];
						$filename = $this->request->data['picture']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['picture']['tmp_name'], $uploadfile))
						{
							$this->request->data['picture'] = $filename; 
						}
					}    
				}
				else
				{
					 $picture =  $this->request->data('apicture');
				}
				
				if(!empty($this->request->data['background']['name']))
				{   
					if($this->request->data['background']['type'] == "image/jpeg" || $this->request->data['background']['type'] == "image/jpg" || $this->request->data['background']['type'] == "image/png" )
					{
						$background =  $this->request->data['background']['name'];
						$filename = $this->request->data['background']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['background']['tmp_name'], $uploadfile))
						{
							$this->request->data['background'] = $filename; 
						}
					}    
				}
				else
				{
					 $background =  $this->request->data('abckground');
				}
				
				
				$n_email = $this->request->data('n_email') == "on" ? "1" :"0";
				$c_email = $this->request->data('c_email') == "on" ? "1" :"0";

				$enquiry = $enquiry_table->newEntity();

				 $title =  $this->request->data('title')  ;
				 $notification_email =  $n_email  ;
				 $confirmation_email = $c_email ;
				 $instruction = $this->request->data('instructions') ;
				 $notes = $this->request->data('notes') ;
				 $school_id =  $this->request->data('school_id')  ;
				 $logo =  $picture  ;
				 $background =  $background  ;
				$created_date =  time()  ;
				
				
					
				$update = $enquiry_table->query()->update()->set([  'title' => $title , 'notification_email' => $notification_email, 'confirmation_email' => $confirmation_email , 'instruction' => $instruction, 'notes' => $notes, 'school_id' => $school_id, 'logo' => $logo , 'background' => $background  ])->where(['id' =>  $fid ])->execute();
				
				if($update)
				{	$enquiryfields = $enquiryfields_table->newEntity();
					$del_fields = $enquiryfields_table->query()->delete()->where([ 'form_id' => $fid ])->execute();
					//$enquiryfields = $enquiryfields_table->newEntity();
					$label =  $this->request->data('label') ;
					//echo $label;
					foreach($label as $label_key=>$name):	
					
						$enquiryfields = $enquiryfields_table->newEntity();					
						$enquiryfields->form_id = $fid;
						$enquiryfields->fields = $name;
						$enquiryfields->name = $this->request->data['inputname'][$label_key]  ;						
						$enquiryfields->type = $this->request->data['type'][$label_key] ;
						$enquiryfields->options = $this->request->data['options'][$label_key];
						
						$fields = $enquiryfields_table->save($enquiryfields);
						//echo $label_key;
						 //print_r($enquiryfields);
					endforeach;
					if( $fields)
					{
						$res = [ 'result' => 'success'  ];
						return $this->redirect('/enquiry');
					}
					else
					{
						$res = [ 'result' => 'form fields not saved'  ];
					}

				}
				else{
					$res = [ 'result' => 'form not saved'  ];
				}                  

            }
			else
			{
				$res = [ 'result' => 'invalid operation'  ];

			}


            return $this->json($res);

            }            
            
        public function delete()
            {   

                $fid = $this->request->data('val') ;
                $enquiryfields_table = TableRegistry::get('enquiry_form_fields');
				$enquiry_table = TableRegistry::get('enquiry_form'); 
                $activ_table = TableRegistry::get('activity');

                $userid = $enquiry_table->find()->select(['id'])->where(['id'=> $fid ])->first();

                if($userid)
                {   
					$del = $enquiry_table->query()->delete()->where([ 'id' => $fid ])->execute(); 
                    if($del)
                    {
						$del_fields = $enquiryfields_table->query()->delete()->where([ 'form_id' => $fid ])->execute(); 
						if($del_fields)
						{
							$activity = $activ_table->newEntity();
							$activity->action =  "Form Deleted"  ;
							$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
							$activity->value = $fid    ;
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


        public function edituserprofile(){
            if ($this->request->is('ajax') && $this->request->is('post') ){
                    
                    $company_table = TableRegistry::get('company');
                    $activ_table = TableRegistry::get('activity');
                    
                    if(!empty($this->request->data['picture']['name']))
                    {   
                        if($this->request->data['picture']['type'] == "image/jpeg" || $this->request->data['picture']['type'] == "image/jpg" || $this->request->data['picture']['type'] == "image/png" )
                        {    
                            $picture =  $this->request->data['picture']['name'];
                            $filename = $this->request->data['picture']['name'];
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['picture']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['picture'] = $filename; 
                            }
                        }    
                    }
                    else
                    {
                        $picture =  $this->request->data('apicture');
                    }

                    

                    $comp_name =  $this->request->data('name')  ;
                    $email =  $this->request->data('email')  ;
                    $phone = $this->request->data('phone') ;
                    $opassword = $this->request->data('opassword') ;
                    $password = $this->request->data('password') ;
                    $cpassword = $this->request->data('cpassword') ;
                    $userid = $this->Cookie->read('id');
                    $modified = strtotime('now');

                    if(!empty($picture))
                    {
                        if($comp_name != ""   || $email != ""  && $phone != ""  )
                        {
                            if($update_company = $company_table->query()->update()->set([  'comp_name' => $comp_name , 'email' => $email, 'ph_no' => $phone , 'comp_logo' => $picture  ])->where(['md5(id)' =>  $userid ])->execute()){
                                $activity = $activ_table->newEntity();
                                $activity->action =  "User Data Updated"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            
                                $activity->value = md5($userid)   ;
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
                                $res = [ 'result' => 'profile not updated'  ];
    
                            }
    
                            if($opassword != "" && $password != "" && $cpassword  != "" && $password == $cpassword ){
                                if($update_task = $company_table->query()->update()->set([  'password' => $password ])->where(['md5(id)' =>  $userid ])->execute()){
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "User Password Updated"  ;
                                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                
                                    $activity->value = md5($userid)   ;
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
                                    $res = [ 'result' => 'password not updated'  ];
        
                                }
                            }
                            else if($opassword != "" && ($password == "" || $cpassword  == "" || $password != $cpassword) ){
                                if($password == ""){
                                    $res = [ 'result' => 'password is required'  ];
                                }
                                elseif($cpassword == ""){
                                    $res = [ 'result' => 'confirm password is required'  ];
                                }
                                elseif($password != $cpassword){
                                    $res = [ 'result' => 'password and confirm password doesn\'t match'  ];
                                }
                            }
    
                        }
                        else
                        {
                            $res = [ 'result' => 'empty'  ];
    
                        }
                    }
                    else
                    { 
                        $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                    }    
            }
            else{
                $res = [ 'result' => 'Invalid Operation'  ];
            }


           return $this->json($res);

        }

}

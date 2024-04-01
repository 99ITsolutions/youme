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
			$reg_table = TableRegistry::get('registeration');
			$class_table = TableRegistry::get('class');
			$session_table = TableRegistry::get('session');
			
			
			$retrieve_form = $reg_table->find()->select([ 'registeration.id',  'registeration.registeration_id', 'registeration.english_marks', 'registeration.hindi_marks', 'registeration.maths_marks', 'registeration.remarks_result', 'registeration.form_admitted', 'class.c_name', 'class.c_section', 'registeration.s_name', 'registeration.father_name', 'registeration.mother_name', 'registeration.father_contact_no', 'registeration.created_date', 'session.startyear', 'session.endyear', 'registeration.adm_no'])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = registeration.class'
						]
					])->join(['session' => 
							[
							'table' => 'session',
							'type' => 'LEFT',
							'conditions' => 'session.id = registeration.session_id'
						]
					])->where(['registeration.school_id' => $school_id ])->toArray(); 
			
			$this->set("form_details", $retrieve_form);   
			$this->viewBuilder()->setLayout('user');

		}        
            

       public function add()
            {   
                $state_table = TableRegistry::get('states');
                $class_table = TableRegistry::get('class');
                $student_table = TableRegistry::get('student');
                $discount_table = TableRegistry::get('discount');
                $route_table = TableRegistry::get('routes');
                $stopage_table = TableRegistry::get('routechg');
		$reg_table = TableRegistry::get('registeration');
                $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');

                $retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $compid , 'session_id'=> $session_id ])->toArray();

                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => 101  ])->toArray() ;

                $retrieve_student = $student_table->find()->select(['id'])->where(['school_id' => $compid ])->last();
				
		$retrieve_reg = $reg_table->find()->select(['id'])->where(['school_id' => $compid ])->last();

                $retrieve_student_list = $student_table->find()->select(['id','s_name'])->where(['school_id' => $compid ])->toArray();

                $retrieve_discount = $discount_table->find()->select(['id' , 'code', 'dscr' ])->where(['school_id' => $compid, 'session_id'=>$session_id ])->toArray();

                $retrieve_route = $route_table->find()->select(['id' , 'route_name' ])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();

                $retrieve_stopage = $stopage_table->find()->select(['id' , 'village'])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();

                $this->set("state_details", $retrieve_state);
                $this->set("class_details", $retrieve_class);
                $this->set("student_details", $retrieve_student);
                $this->set("student_list_details", $retrieve_student_list);
                $this->set("discount_details", $retrieve_discount);
                $this->set("route_details", $retrieve_route);
                $this->set("stopage_details", $retrieve_stopage);
				$this->set("reg_details", $retrieve_reg);
				
				
					$setting_table = TableRegistry::get('stdnt_h_setting');
					
			
					$student = array();
					   
					$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid])->toArray();
					
					if(!empty($colname)){
						$col_type = explode(',', $colname[0]['col_type']);
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
					}
					else
					{
						array_push($student,'student.s_name','student.id','student.acc_no');	
					}

					   

					$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.school_id'=> $compid,  'student.stud_left !='=> 'Yes'  ])->toArray(); 
					
				$this->set("sibling_details", $retrieve_siblings);
                $this->viewBuilder()->setLayout('user');
            }
		
		
        
        public function addform(){
			
			if ($this->request->is('post'))
			{
	
				//$enquiryfields_table = TableRegistry::get('enquiry_form_fields');
				$reg_table = TableRegistry::get('registeration'); 
				 $compid = $this->request->data('school_id')  ;
				 $session_id = $this->Cookie->read('sessionid');
				$picture = "";
				$father_pic = "";	
				$mother_pic = "";
				
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
				
				if(!empty($this->request->data['gr1_path']['name']))
				{   
					if($this->request->data['gr1_path']['type'] == "image/jpeg" || $this->request->data['gr1_path']['type'] == "image/jpg" || $this->request->data['gr1_path']['type'] == "image/png" )
					{
						$father_pic =  $this->request->data['gr1_path']['name'];
						$filename = $this->request->data['gr1_path']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['gr1_path']['tmp_name'], $uploadfile))
						{
							$this->request->data['gr1_path'] = $filename; 
						}
					}    
					
				}
				
				if(!empty($this->request->data['gr2_path']['name']))
				{   
					if($this->request->data['gr2_path']['type'] == "image/jpeg" || $this->request->data['gr2_path']['type'] == "image/jpg" || $this->request->data['gr2_path']['type'] == "image/png" )
					{
						$mother_pic =  $this->request->data['gr2_path']['name'];
						$filename = $this->request->data['gr2_path']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['gr2_path']['tmp_name'], $uploadfile))
						{
							$this->request->data['gr2_path'] = $filename; 
						}
					}    
				}
				
				
				

				$regis = $reg_table->newEntity();
				
				$regis->school_id =  $this->request->data('school_id')  ;				
				$regis->registeration_id =  $this->request->data('reg_no')  ;				
				$regis->s_name = $this->request->data('s_name')  ;				
				$regis->class = $this->request->data('class') ;
				$regis->dob = $this->request->data('dob') ;
				$regis->age = $this->request->data('std_age') ;
				$regis->aadhar_no =  $this->request->data('aadhar') ;
				$regis->gender =  $this->request->data('gender') ;
				$regis->only_child =  $this->request->data('child') ;
				$regis->category =  $this->request->data('cat') ;
				
				$regis->father_name =  $this->request->data('s_f_name')  ;
				$regis->father_dob =  $this->request->data('fatherdob')  ;
				$regis->father_qualification = $this->request->data('fatherqual')  ;
				$regis->father_aadhar = $this->request->data('fatheraadhar') ;
				$regis->father_occupation = $this->request->data('fatheroccupation') ;
				$regis->father_designation =  $this->request->data('fatherdesignation')  ;
				$regis->father_organization_name =  $this->request->data('fatherorg')  ;
				$regis->father_office_address =  $this->request->data('fatheroffice')  ;
				$regis->father_contact_no =  $this->request->data('fathercontact')  ;
				$regis->father_annual_income = $this->request->data('fatherincome') ;
				$regis->father_pan_no =  $this->request->data('fatherPan')  ;
				$regis->father_email =  $this->request->data('fatheremail')  ;
				
				$regis->mother_name =  $this->request->data('s_m_name')  ;
				$regis->mother_dob =  $this->request->data('motherdob')  ;
				$regis->mother_qualification = $this->request->data('motherqual')  ;
				$regis->mother_aadhar = $this->request->data('motheraadhar') ;
				$regis->mother_occupation = $this->request->data('motheroccupation') ;
				$regis->mother_designation =  $this->request->data('motherdesignation')  ;
				$regis->mother_organization_name =  $this->request->data('motherorg')  ;
				$regis->mother_office_address =  $this->request->data('motheroffice')  ;
				$regis->mother_contact_no = $this->request->data('mothercontact')  ;
				$regis->mother_annual_income = $this->request->data('motherincome') ;
				$regis->mother_pan_no =  $this->request->data('motherPan')  ;
				$regis->mother_email =  $this->request->data('motheremail')  ;
				
				 
				$regis->local_guardian =  $this->request->data('guardian')  ;
				$regis->relation =  $this->request->data('relation')  ;
				$regis->local_address =  $this->request->data('resi_add1')  ;
				$regis->state =  $this->request->data('state')  ;
				$regis->city = $this->request->data('city')  ;
				$regis->local_contact_no = $this->request->data('phone_resi') ;
				$regis->local_email_id = $this->request->data('email') ;				
				$regis->transport_required =  $this->request->data('transport')  ;
				$regis->stoppage = $this->request->data('stopage') ;
				$regis->transport_amount =  $this->request->data('transport_amt')  ;
				
				$regis->last_school =  $this->request->data('last_school')  ;
				$regis->last_class_passed =  $this->request->data('last_class')  ;
				$regis->session =  $this->request->data('last_session')  ;
				
				
				$regis->passport_photo = $this->request->data('passport_photo')  ;
				$regis->previous_class_report = $this->request->data('report_card')  ;
				$regis->school_leaving_certificate = $this->request->data('slc')  ;
				$regis->dob_certificate = $this->request->data('dob_certificate')  ;
				$regis->affidavit = $this->request->data('affidavit')  ;
				$regis->aadhar_card_document = $this->request->data('photocopy_aadhar')  ;
				$regis->student_pic = $picture ;
				$regis->mother_pic = $mother_pic;
				$regis->father_pic = $father_pic;
				$regis->reg_date = $this->request->data('reg_date')  ;
				$regis->created_date = time();
				$regis->session_id = $session_id;
				
				$regis->sibling_details1 =  $this->request->data('sibl_in_scl')  ;
				$regis->sibling_details2 =  $this->request->data('sibl_in_scl1')  ;
				$regis->sibling_details3 =  $this->request->data('sibl_in_scl2')  ;
				$regis->amount = $this->request->data('amount') ;
			
				 $retrieve_student = $reg_table->find()->select(['id'  ])->where(['local_email_id' => $this->request->data('email') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;

                 $retrieve_student_uidai = $reg_table->find()->select(['id' ])->where(['aadhar_no' => $this->request->data('aadhar') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;
                    
				$ph_sms_length= strlen($this->request->data('phone_resi'));
				$fathercontact= strlen($this->request->data('fathercontact'));
				$mothercontact= strlen($this->request->data('mothercontact'));
				$aadhar_length= strlen($this->request->data('aadhar'));
				
				$state =  $this->request->data('state')  ;
				$city = $this->request->data('city')  ;
				$reg_date = $this->request->data('reg_date')  ;
				if($retrieve_student == 0)
				{
					if($retrieve_student_uidai == 0)
					{
						if($aadhar_length == 12)
						{
							if($ph_sms_length > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('phone_resi')))
							{
								if($fathercontact > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('fathercontact')))
								{
									/*if($mothercontact > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('mothercontact')))
									{ */
										
										if($saved = $reg_table->save($regis) )
										{
											$id = $saved->id;
											$update = $reg_table->query()->update()->set([  'school_id' => $compid , 'state' => $state, 'city' => $city , 'session_id' => $session_id , 'reg_date' => $reg_date ])->where(['id' =>  $id ])->execute();
											if($update)
											{
												$res = [ 'result' => 'success'  ];
											}
											
										}
										else{
											$res = [ 'result' => 'form not saved'  ];
										}
									/*}
									else
									{
										$res = [ 'result' => 'Mother Contact number should be atleast 10 digit and numeric digit only'  ];
									} */
								}
								else
								{
									$res = [ 'result' => 'Father Contact number should be atleast 10 digit and numeric digit only'  ];
								}
							}
							else
							{
								$res = [ 'result' => 'Contact number should be atleast 10 digit and numeric digit only'  ];
							}
						}
						else
						{
							$res = [ 'result' => 'Aadhar card number must be 12 digit'  ];
						}
					}
					else
					{
						$res = [ 'result' => 'Aadhar Card already exist'  ];
					}
				}
				else
				{
					$res = [ 'result' => 'Email already exist'  ];
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


		 public function edit($id)
            {   
				$reg_table = TableRegistry::get('registeration'); 
                $state_table = TableRegistry::get('states');
                $class_table = TableRegistry::get('class');
                $student_table = TableRegistry::get('student');
                $discount_table = TableRegistry::get('discount');
                $route_table = TableRegistry::get('routes');
                $stopage_table = TableRegistry::get('routechg');
				$reg_table = TableRegistry::get('registeration');
                $compid =$this->request->session()->read('company_id');
				$session_id = $this->Cookie->read('sessionid');
				$city_table = TableRegistry::get('cities');

                $retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $compid , 'session_id'=> $session_id ])->toArray();

                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => 101  ])->toArray() ;

                $retrieve_student = $student_table->find()->select(['id'])->where(['school_id' => $compid ])->last();
				
				//$retrieve_reg = $reg_table->find()->select(['id'])->where(['school_id' => $compid ])->last();

                $retrieve_student_list = $student_table->find()->select(['id','s_name'])->where(['school_id' => $compid ])->toArray();

                $retrieve_discount = $discount_table->find()->select(['id' , 'code', 'dscr' ])->where(['school_id' => $compid, 'session_id'=>$session_id ])->toArray();
				
				

                $retrieve_route = $route_table->find()->select(['id' , 'route_name' ])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();

                $retrieve_stopage = $stopage_table->find()->select(['id' , 'village'])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();
				
				$retrieve_reg = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id', 'age', 'amount'])->where(['md5(id)' => $id ])->toArray();
				
				$retrieve_city = $city_table->find()->select(['id' ,'name'])->where([ 'state_id' => $retrieve_reg[0]['state'] ])->toArray() ;

                $this->set("state_details", $retrieve_state);
                $this->set("class_details", $retrieve_class);
                $this->set("student_details", $retrieve_student);
                $this->set("student_list_details", $retrieve_student_list);
                $this->set("discount_details", $retrieve_discount);
                $this->set("route_details", $retrieve_route);
                $this->set("stopage_details", $retrieve_stopage);
				$this->set("reg_details", $retrieve_reg);
				$this->set("city_details", $retrieve_city);
				
					$setting_table = TableRegistry::get('stdnt_h_setting');
					
			
					$student = array();
					   
					$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid])->toArray();
					
					if(!empty($colname)){
						$col_type = explode(',', $colname[0]['col_type']);
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
					}
					else
					{
						array_push($student,'student.s_name','student.id','student.acc_no');	
					}

					   

					$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.school_id'=> $compid ,  'student.stud_left !='=> 'Yes' ])->toArray(); 
					
				$this->set("sibling_details", $retrieve_siblings);
                $this->viewBuilder()->setLayout('user');
            }
			
		public function view($id)
            {   
				$reg_table = TableRegistry::get('registeration'); 
                $state_table = TableRegistry::get('states');
                $class_table = TableRegistry::get('class');
                $student_table = TableRegistry::get('student');
                $discount_table = TableRegistry::get('discount');
                $route_table = TableRegistry::get('routes');
                $stopage_table = TableRegistry::get('routechg');
		$reg_table = TableRegistry::get('registeration');
                $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$city_table = TableRegistry::get('cities');

                $retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $compid , 'session_id'=> $session_id ])->toArray();

                $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => 101  ])->toArray() ;

                $retrieve_student = $student_table->find()->select(['id'])->where(['school_id' => $compid ])->last();
				
				//$retrieve_reg = $reg_table->find()->select(['id'])->where(['school_id' => $compid ])->last();

                $retrieve_student_list = $student_table->find()->select(['id','s_name'])->where(['school_id' => $compid ])->toArray();

                $retrieve_discount = $discount_table->find()->select(['id' , 'code', 'dscr' ])->where(['school_id' => $compid, 'session_id'=>$session_id ])->toArray();
				
				

                $retrieve_route = $route_table->find()->select(['id' , 'route_name' ])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();

                $retrieve_stopage = $stopage_table->find()->select(['id' , 'village'])->where(['school_id' => $compid ,'session_id'=>$session_id ])->toArray();
				
		$retrieve_reg = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id', 'age', 'amount'])->where(['md5(id)' => $id ])->toArray();
				
		$retrieve_city = $city_table->find()->select(['id' ,'name'])->where([ 'state_id' => $retrieve_reg[0]['state'] ])->toArray() ;

                $this->set("state_details", $retrieve_state);
                $this->set("class_details", $retrieve_class);
                $this->set("student_details", $retrieve_student);
                $this->set("student_list_details", $retrieve_student_list);
                $this->set("discount_details", $retrieve_discount);
                $this->set("route_details", $retrieve_route);
                $this->set("stopage_details", $retrieve_stopage);
				$this->set("reg_details", $retrieve_reg);
				$this->set("city_details", $retrieve_city);
				
					$setting_table = TableRegistry::get('stdnt_h_setting');
					
			
					$student = array();
					   
					$colname = $setting_table->find()->select(['col_type'])->where(['school_id'=>$compid])->toArray();
					
					if(!empty($colname)){
						$col_type = explode(',', $colname[0]['col_type']);
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
					}
					else
					{
						array_push($student,'student.s_name','student.id','student.acc_no');	
					}

					   

					$retrieve_siblings = $student_table->find()->select($student)->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.school_id'=> $compid,  'student.stud_left !='=> 'Yes' ])->toArray(); 
					
				$this->set("sibling_details", $retrieve_siblings);
                $this->viewBuilder()->setLayout('user');
            }
		 

        public function editform(){
			
			if ($this->request->is('post'))
			{
	
				$reg_table = TableRegistry::get('registeration'); 
				$compid = $this->request->data('school_id')  ;
				$id = $this->request->data('form_id')  ;
				$session_id = $this->Cookie->read('sessionid');
				
				
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
					$picture =  $this->request->data('epicture');
				}
				
				
				if(!empty($this->request->data['gr1_path']['name']))
				{   
					if($this->request->data['gr1_path']['type'] == "image/jpeg" || $this->request->data['gr1_path']['type'] == "image/jpg" || $this->request->data['gr1_path']['type'] == "image/png" )
					{
						$father_pic =  $this->request->data['gr1_path']['name'];
						$filename = $this->request->data['gr1_path']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['gr1_path']['tmp_name'], $uploadfile))
						{
							$this->request->data['gr1_path'] = $filename; 
						}
					}    
					
				}
				else
				{
					$father_pic =  $this->request->data('egr1_path');
				}
				
				if(!empty($this->request->data['gr2_path']['name']))
				{   
					if($this->request->data['gr2_path']['type'] == "image/jpeg" || $this->request->data['gr2_path']['type'] == "image/jpg" || $this->request->data['gr2_path']['type'] == "image/png" )
					{
						$mother_pic =  $this->request->data['gr2_path']['name'];
						$filename = $this->request->data['gr2_path']['name'];
						$uploadpath = 'img/';
						$uploadfile = $uploadpath.$filename;
						if(move_uploaded_file($this->request->data['gr2_path']['tmp_name'], $uploadfile))
						{
							$this->request->data['gr2_path'] = $filename; 
						}
					}    
				}
				else
				{
					$mother_pic =  $this->request->data('egr2_path');
				}
				
				
				

				$regis = $reg_table->newEntity();
				
				$school_id =  $this->request->data('school_id')  ;				
				$registeration_id =  $this->request->data('reg_no')  ;				
				$s_name = $this->request->data('s_name')  ;				
				$class = $this->request->data('class') ;
				$dob = $this->request->data('dob') ;
				$aadhar_no =  $this->request->data('aadhar') ;
				$gender =  $this->request->data('gender') ;
				$only_child =  $this->request->data('child') ;
				$category =  $this->request->data('cat') ;
				 
				 
				 
				$father_name =  $this->request->data('s_f_name')  ;
				$father_dob =  $this->request->data('fatherdob')  ;
				$father_qualification = $this->request->data('fatherqual')  ;
				$father_aadhar = $this->request->data('fatheraadhar') ;
				$father_occupation = $this->request->data('fatheroccupation') ;
				$father_designation =  $this->request->data('fatherdesignation')  ;
				$father_organization_name =  $this->request->data('fatherorg')  ;
				$father_office_address =  $this->request->data('fatheroffice')  ;
				$father_contact_no =  $this->request->data('fathercontact')  ;
				$father_annual_income = $this->request->data('fatherincome') ;
				$father_pan_no =  $this->request->data('fatherPan')  ;
				$father_email =  $this->request->data('fatheremail')  ;
				
				$mother_name =  $this->request->data('s_m_name')  ;
				$mother_dob =  $this->request->data('motherdob')  ;
				$mother_qualification = $this->request->data('motherqual')  ;
				$mother_aadhar = $this->request->data('motheraadhar') ;
				$mother_occupation = $this->request->data('motheroccupation') ;
				$mother_designation =  $this->request->data('motherdesignation')  ;
				$mother_organization_name =  $this->request->data('motherorg')  ;
				$mother_office_address =  $this->request->data('motheroffice')  ;
				$mother_contact_no = $this->request->data('mothercontact')  ;
				$mother_annual_income = $this->request->data('motherincome') ;
				$mother_pan_no =  $this->request->data('motherPan')  ;
				$mother_email =  $this->request->data('motheremail')  ;
				
				
				
				 
				$local_guardian =  $this->request->data('guardian')  ;
				$relation =  $this->request->data('relation')  ;
				$local_address =  $this->request->data('resi_add1')  ;
				$state =  $this->request->data('state')  ;
				$city = $this->request->data('city')  ;
				$local_contact_no = $this->request->data('phone_resi') ;
				$local_email_id = $this->request->data('email') ;				
				$transport_required =  $this->request->data('transport')  ;
				$stoppage = $this->request->data('stopage') ;
				$transport_amount =  $this->request->data('transport_amt')  ;
				
				$last_school =  $this->request->data('last_school')  ;
				$last_class_passed =  $this->request->data('last_class')  ;
				$session =  $this->request->data('last_session')  ;
				
				
				
				$passport_photo = $this->request->data('passport_photo')  ;
				$previous_class_report = $this->request->data('report_card')  ;
				$school_leaving_certificate = $this->request->data('slc')  ;
				$dob_certificate = $this->request->data('dob_certificate')  ;
				$affidavit = $this->request->data('affidavit')  ;
				$aadhar_card_document = $this->request->data('photocopy_aadhar')  ;
				$student_pic = $picture ;
				$mother_pic = $mother_pic;
				$father_pic = $father_pic;
				$reg_date = $this->request->data('reg_date')  ;
				$created_date = time();
				$session_id = $session_id;
				
				$sibling_details1 =  $this->request->data('sibl_in_scl')  ;
				$sibling_details2 =  $this->request->data('sibl_in_scl1')  ;
				$sibling_details3 =  $this->request->data('sibl_in_scl2')  ;
				
				$age = $this->request->data('std_age')  ;
				$amount = $this->request->data('amount')  ;
			
				 $retrieve_student = $reg_table->find()->select(['id'  ])->where(['local_email_id' => $this->request->data('email') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;

                 $retrieve_student_uidai = $reg_table->find()->select(['id' ])->where(['aadhar_no' => $this->request->data('aadhar') , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;
                    
				$ph_sms_length= strlen($this->request->data('phone_resi'));
				$fathercontact= strlen($this->request->data('fathercontact'));
				$mothercontact= strlen($this->request->data('mothercontact'));
				$aadhar_length= strlen($this->request->data('aadhar'));
				
				$state =  $this->request->data('state')  ;
				$city = $this->request->data('city')  ;
				$reg_date = $this->request->data('reg_date')  ;
				/*if($retrieve_student == 0)
				{
					if($retrieve_student_uidai == 0)
					{ */
						if($aadhar_length == 12)
						{
							if($ph_sms_length > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('phone_resi')))
							{
								if($fathercontact > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('fathercontact')))
								{
									/*if($mothercontact > 9 && preg_match ("/^[+0-9]*$/", $this->request->data('mothercontact')))
									{ */
										$update = $reg_table->query()->update()->set(['s_name' => $s_name, 'class' => $class, 'dob' => $dob, 'aadhar_no' => $aadhar_no, 'gender' => $gender, 'only_child' => $only_child, 'category' => $category, 'father_name' => $father_name, 'father_dob' => $father_dob, 'father_qualification' => $father_qualification, 'father_occupation' => $father_occupation, 'father_designation' => $father_designation, 'father_aadhar' => $father_aadhar, 'father_organization_name' => $father_organization_name, 'father_office_address' => $father_office_address, 'father_contact_no' => $father_contact_no, 'father_annual_income' => $father_annual_income, 'father_pan_no' => $father_pan_no, 'father_email' => $father_email, 'mother_name' => $mother_name, 'mother_dob' => $mother_dob, 'mother_qualification' => $mother_qualification, 'mother_aadhar' => $mother_aadhar, 'mother_occupation' => $mother_occupation, 'mother_designation' => $mother_designation, 'mother_organization_name' => $mother_organization_name, 'mother_office_address' => $mother_office_address, 'mother_contact_no' => $mother_contact_no, 'mother_annual_income' => $mother_annual_income, 'mother_pan_no' => $mother_pan_no, 'mother_email' => $mother_email, 'local_guardian' => $local_guardian, 'relation' => $relation, 'local_address' => $local_address, 'state' => $state, 'city' => $city, 'local_contact_no' => $local_contact_no, 'local_email_id' => $local_email_id, 'transport_required' => $transport_required, 'stoppage' => $stoppage, 'transport_amount' => $transport_amount, 'last_school' => $last_school, 'last_class_passed' => $last_class_passed, 'session' => $session, 'passport_photo' => $passport_photo, 'previous_class_report' => $previous_class_report, 'school_leaving_certificate' => $school_leaving_certificate, 'dob_certificate' => $dob_certificate, 'affidavit' => $affidavit, 'aadhar_card_document' => $aadhar_card_document, 'student_pic' => $student_pic, 'mother_pic' => $mother_pic, 'father_pic' => $father_pic, 'reg_date' => $reg_date, 'session_id' => $session_id, 'sibling_details1' => $sibling_details1, 'sibling_details2' => $sibling_details2, 'sibling_details3' => $sibling_details3 , 'age' => $age, 'amount' => $amount])->where(['id' =>  $id ])->execute();
										if($update)
										{
											$res = [ 'result' => 'success'  ];
										}
										else
										{
											$res = [ 'result' => 'form not saved'  ];
										}
									/*}
									else
									{
										$res = [ 'result' => 'Mother Contact number should be atleast 10 digit and numeric digit only'  ];
									} */
								}
								else
								{
									$res = [ 'result' => 'Father Contact number should be atleast 10 digit and numeric digit only'  ];
								}
							}
							else
							{
								$res = [ 'result' => 'Contact number should be atleast 10 digit and numeric digit only'  ];
							}
						}
						else
						{
							$res = [ 'result' => 'Aadhar card number must be 12 digit'  ];
						}
					/*}
					else
					{
						$res = [ 'result' => 'Aadhar Card already exist'  ];
					}
				}
				else
				{
					$res = [ 'result' => 'Email already exist'  ];
				} */
				             

            }
			else
			{
				$res = [ 'result' => 'invalid operation'  ];

			}


            return $this->json($res);

        }
            
        public function delete()
            {   

                $rid = $this->request->data('val') ;
                
				$reg_table = TableRegistry::get('registeration'); 
                $activ_table = TableRegistry::get('activity');

                $userid = $reg_table->find()->select(['id'])->where(['id'=> $rid ])->first();

                if($userid)
                {   
					$del = $reg_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                    if($del)
                    {						
						$activity = $activ_table->newEntity();
						$activity->action =  "Form Deleted"  ;
						$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
						$activity->value = $rid    ;
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
		
		public function addmarks(){
			$this->viewBuilder()->layout(false);
			if ($this->request->is('post'))
			{	
				$reg_table = TableRegistry::get('registeration');
				$formid =  $this->request->data('formid')  ;
                $emarks =  $this->request->data('emarks')  ;
				$hmarks =  $this->request->data('hmarks')  ;
				$mmarks =  $this->request->data('mmarks')  ;
				$remarks =  $this->request->data('remarks')  ;
				
				
				$update_marks = $reg_table->query()->update()->set(['english_marks' => $emarks, 'hindi_marks' => $hmarks, 'maths_marks' => $mmarks, 'remarks_result' => $remarks])->where(['id' =>  $formid ])->execute();
				if($update_marks){
					 $res = [ 'result' => 'success'  ];
				}
				else
				{
					$res = [ 'result' => 'marks not updated'  ];
				}
				
			}
			else{
				$res = [ 'result' => 'Invalid Operation'  ];

			}
			
			return $this->json($res);
		}
		
		public function editmarks(){
			$this->viewBuilder()->layout(false);
			if ($this->request->is('post'))
			{	
				$reg_table = TableRegistry::get('registeration');
				$formid =  $this->request->data('eformid')  ;
                $emarks =  $this->request->data('emarks')  ;
				$hmarks =  $this->request->data('hmarks')  ;
				$mmarks =  $this->request->data('mmarks')  ;
				$remarks =  $this->request->data('remarks')  ;
				
				
				$update_marks = $reg_table->query()->update()->set(['english_marks' => $emarks, 'hindi_marks' => $hmarks, 'maths_marks' => $mmarks, 'remarks_result' => $remarks])->where(['id' =>  $formid ])->execute();
				if($update_marks){
					 $res = [ 'result' => 'success'  ];
				}
				else
				{
					$res = [ 'result' => 'marks not updated'  ];
				}
				
			}
			else{
				$res = [ 'result' => 'Invalid Operation'  ];

			}
			
			return $this->json($res);
		}
		

		 public function cakePdf()
            {
                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $CakePdf->template('cake_pdf', 'default');
                $CakePdf->viewVars($this->viewVars);
                $pdf = $CakePdf->write(APP . 'Files' . DS . 'Output.pdf');
                echo $pdf;
                die();
				
				

            }
			

		public function regslippdf($id)
		{
			
				$student_table = TableRegistry::get('student');
				$activ_table = TableRegistry::get('activity');
				$school_table = TableRegistry::get('company');
				$school_id =$this->request->session()->read('company_id');
				$reg_table = TableRegistry::get('registeration');
				$session_table = TableRegistry::get('session');
				$class_table = TableRegistry::get('class');
				
				
				$session_id = $this->Cookie->read('sessionid');
				
				$retrieve_reg = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id', 'amount'])->where(['md5(id)' => $id ])->toArray();
				
				$retrieve_school = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
                    'states' => 
                        [
                        'table' => 'states',
                        'type' => 'LEFT',
                        'conditions' => 'states.id = company.state'
                    ],
                    'cities' => 
                        [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'conditions' => 'cities.id = company.city'
                    ]

                ])->where(['company.id '=> $school_id])->first() ;
				
				$retrieve_session = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
				$retrieve_class = $class_table->find()->select(['c_name', 'c_section' ])->where(['id '=> $retrieve_reg[0]['class']])->first() ;
				$this->set("class_details", $retrieve_class);
				$this->set("reg_details", $retrieve_reg);
				$this->set("school_details", $retrieve_school);
				$this->set("session_details", $retrieve_session);
                $this->viewBuilder()->setLayout('user');
		}

		
	public function pdf($id = null)
            {	
				
				$student_table = TableRegistry::get('student');
				$activ_table = TableRegistry::get('activity');
				$school_table = TableRegistry::get('company');
				$school_id =$this->request->session()->read('company_id');
				$reg_table = TableRegistry::get('registeration');
				$session_table = TableRegistry::get('session');
				$class_table = TableRegistry::get('class');
				
				$baseurl = $this->base_url;
				$session_id = $this->Cookie->read('sessionid');
				
				$reg_details = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id', 'amount'])->where(['md5(id)' => $id ])->toArray();
				
				$school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
                    'states' => 
                        [
                        'table' => 'states',
                        'type' => 'LEFT',
                        'conditions' => 'states.id = company.state'
                    ],
                    'cities' => 
                        [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'conditions' => 'cities.id = company.city'
                    ]

                ])->where(['company.id '=> $school_id])->first() ;
				
				$session_details = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
				$class_details = $class_table->find()->select(['c_name', 'c_section' ])->where(['id '=> $reg_details[0]['class']])->first() ;
				
				
				   $number = $reg_details[0]['amount'];
				   $no = floor($number);
				   $point = round($number - $no, 2) * 100;
				   $hundred = null;
				   $digits_1 = strlen($no);
				   $i = 0;
				   $str = array();
				   $words = array('0' => '', '1' => 'one', '2' => 'Two',
					'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
					'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
					'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
					'13' => 'Thirteen', '14' => 'Fourteen',
					'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
					'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
					'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
					'60' => 'Sixty', '70' => 'Seventy',
					'80' => 'Eighty', '90' => 'Ninety');
				   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
				   while ($i < $digits_1) {
					 $divider = ($i == 2) ? 10 : 100;
					 $number = floor($no % $divider);
					 $no = floor($no / $divider);
					 $i += ($divider == 10) ? 1 : 2;
					 if ($number) {
						$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
						$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
						$str [] = ($number < 21) ? $words[$number] .
							" " . $digits[$counter] . $plural . " " . $hundred
							:
							$words[floor($number / 10) * 10]
							. " " . $words[$number % 10] . " "
							. $digits[$counter] . $plural . " " . $hundred;
					 } else $str[] = null;
				  }
				  $str = array_reverse($str);
				  $amt_words = implode('', $str);
				  
				 
               $dompdf = new Dompdf();
                $dompdf->loadHtml('				
				<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">                
				<div class="row" style="display:flex; width:100%;">
					<div class="col-sm-3 mt-3">
						<img src="img/'.$school_details["comp_logo"].'" height="100" width="80" alt="school logo">
					</div>
					<div class="col-sm-6 text-center" style="text-align:center">
						<span style="font-size:22px">'.$school_details['comp_name'].'</span><br>
						<span>'.$school_details['add_1']." ".$school_details['cities']['name']
							." ".$school_details['states']['name']."-".$school_details['zipcode'].'</span><br>
						<span>Email Address : '.$school_details['email'].'</span> <br>
						<span><b>Registration Slip For the Session '.$session_details['startyear']."-". $session_details['endyear'].'</b></span>
					</div>
					<div class="col-sm-3 mt-3" style="text-align:right">
						<span>Parent Copy</span>
					</div>					
				</div>                
				
			                

                <div class="row" style="width:100% !important; display:flex; ">							
	                <div style="float:left; width:60% !important; padding: 10px 20px;">
						<div><span>Registration No: </span>  '.$session_details['startyear']."-". $session_details['endyear']."/". $reg_details[0]['registeration_id'].'</div>
						<div><span>Student Name: </span> '. ucfirst($reg_details[0]['s_name']) .'</div>
						<div><span>Fathers Name: </span> '. ucfirst($reg_details[0]['father_name']) .'</div>
						<div><span>Gender: </span> '. $reg_details[0]['gender'].'</div>
						<div><span>Apply for Class: </span> '. ucfirst($class_details['c_name']) .'</div>
					</div>
					<div style="float:left; width:40% !important; padding: 10px 20px;">
						<div><span>Date of Birth: </span> '. ucfirst($reg_details[0]['dob']) .'</div>
						<div><span>Registration Date: </span> '. $reg_details[0]['reg_date'] .'</div>
						<div><span>Registration Fee (in Rs.): </span> '. $reg_details[0]['amount'].'</div>
					</div>
	               
	            </div>  

				
				
				<div class="row" style="width:100% !important; "> 
					
	                <div style="text-align:left;  width:69% !important;"><b>Amount in Words: </b> '. $amt_words .' Only</div>	
					<div style="text-align:right !important;  width:30% !important;"><b>Authorised Signatory</b></div>	
	            </div> 
	            
                        	
            </div>
			<div style="border:1px dashed #000; width:100% !important; "></div>
			
			<div class="card" style="margin-top:50px !important;">
                <div class="header">
                	<div class="row" style="display:flex">
	                	<div class="col-sm-3 mt-3">
	                		<img src="img/'.$school_details["comp_logo"].'" class="ml-5" height="100" width="80" alt="school logo">
	                	</div>
	                	<div class="col-sm-6 text-center" style="text-align:center">
							<span style="font-size:22px">'.$school_details['comp_name'].'</span><br>
							<span>'.$school_details['add_1']." ".$school_details['cities']['name']
								." ".$school_details['states']['name']."-".$school_details['zipcode'].'</span><br>
							<span>Email Address : '.$school_details['email'].'</span> <br>
							<span><b>Registration Slip For the Session '.$session_details['startyear']."-". $session_details['endyear'].'</b></span>
						</div>
						<div class="col-sm-3 mt-3" style="text-align:right">
	                		<span>School Copy</span>
	                	</div>
						
                	</div>
                </div>
                
                <div class="row container mb-3">
	                <div style="float:left; width:60% !important; padding: 10px 20px;">
						<div><span>Registration No: </span> '.$session_details['startyear']."-". $session_details['endyear']."/". $reg_details[0]['registeration_id'].'</div>
						<div><span>Student Name: </span> '. ucfirst($reg_details[0]['s_name']) .'</div>
						<div><span>Fathers Name: </span> '. ucfirst($reg_details[0]['father_name']) .'</div>
						<div><span>Gender: </span> '. $reg_details[0]['gender'].'</div>
						<div><span>Apply for Class: </span> '. ucfirst($class_details['c_name']) .'</div>
					</div>
					<div style="float:left; width:40% !important; padding: 10px 20px;">
						<div><span>Date of Birth: </span> '. ucfirst($reg_details[0]['dob']) .'</div>
						<div><span>Registration Date: </span> '. $reg_details[0]['reg_date'] .'</div>
						<div><span>Registration Fee (in Rs.): </span> '. $reg_details[0]['amount'].'</div>
					</div>
	               
	            </div>  

				
				<div class="row container">
	                <div style="text-align:left;  width:70% !important;"><span><b>Amount in Words: </b></span> '. $amt_words .' <span> Only </span> </div>	
					<div style="text-align:right !important;   width:30% !important;"><b>Authorised Signatory</b></div>	
	            </div> 
	            
                        	
            </div>
		</div>
	</div>
</div>

				
				
				');
                
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
				
                // Render the HTML as PDF
                $dompdf->render();
               
                // Output the generated PDF to Browser
                $dompdf->stream();
				
				//print_r($dompdf); die;
            
            }

		
		
		public function formaccepted($id)
		{
			$this->viewBuilder()->layout(false);
				$student_table = TableRegistry::get('student');
				$activ_table = TableRegistry::get('activity');
				$school_table = TableRegistry::get('company');
				$balance_table = TableRegistry::get('balance');
				$reg_table = TableRegistry::get('registeration');
				
				
				$session_id = $this->Cookie->read('sessionid');
				
				$retrieve_reg = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id'])->where(['md5(id)' => $id ])->toArray();
				
				 $compid = $retrieve_reg[0]['school_id'] ; 
				 $reg_formid = $retrieve_reg[0]['id'];
				
				$retrieve_adm_no = $student_table->find()->select(['id'])->where(['school_id' => $compid ])->last();
				

				$retrieve_student = $student_table->find()->select(['id'  ])->where(['email' => $retrieve_reg[0]['local_email_id'] , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;

				$retrieve_student_uidai = $student_table->find()->select(['id' ])->where(['aadhar' => $retrieve_reg[0]['aadhar_no'] , 'school_id'=> $compid , 'session_id'=> $session_id ])->count() ;

				$retrieve_student_acc = $student_table->find()->select(['id' ])->where(['acc_no' => $this->request->data('acc_no') , 'school_id'=> $compid , 'session_id'=> $session_id])->count() ;
				
				$retrieve_class_roll = $student_table->find()->select(['roll_no' ])->where(['class' => $retrieve_reg[0]['class'] , 'session_id'=> $session_id , 'school_id'=> $compid ])->last() ;	
				
				$ph_sms_length= strlen($this->request->data('mobile_for_sms'));
				$aadhar_length= strlen($this->request->data('aadhar'));

				$cyear = date("Y");  
				$nyear = $cyear+1;                     
	
				if($retrieve_student == 0 )
				{   
					if($retrieve_student_uidai == 0 )
					{
						$adm_num = 1000 + $retrieve_adm_no['id']  ;
						$student = $student_table->newEntity();
						$now = time();
						$student->c_sess_name = $cyear."-".$nyear;
						$student->adm_no =  $adm_num;
						$student->s_name =  $retrieve_reg[0]['s_name']  ;
						$student->pic =  $retrieve_reg[0]['student_pic']  ;
						$student->mobile_no = $retrieve_reg[0]['local_contact_no'] ;
						$student->s_f_name = $retrieve_reg[0]['father_name'] ;
						$student->s_m_name = $retrieve_reg[0]['mother_name'] ;
						$student->guardian_name = "";
						$student->dob = $retrieve_reg[0]['dob'] ;
						$student->adm_dt = date('Y-m-d', strtotime($now)) ;
								$student->roll_no = $retrieve_class_roll['roll_no'] +1 ;
						$student->bloodgroup = "" ;
						$student->cat = $retrieve_reg[0]['category'] ;
						$student->n_cat = "" ;
						$student->f_occ = $retrieve_reg[0]['father_occupation'] ;
						$student->acc_no = $adm_num;
						
						$student->class = $retrieve_reg[0]['class'] ;
						$student->national = $this->request->data('national') ;
						$student->resi_add1 = $retrieve_reg[0]['local_address'] ;
						$student->resi_add2 = "" ;
						$student->state = $retrieve_reg[0]['state'] ;
						$student->city = $retrieve_reg[0]['city'] ;
						$student->phone_resi = $retrieve_reg[0]['local_contact_no'] ;
						
						$student->phone_off = $retrieve_reg[0]['father_contact_no'];
						$student->srn_no = "" ;
						$student->gender =  $retrieve_reg[0]['gender'] ;
						$student->mobile_for_sms =  $retrieve_reg[0]['father_contact_no'];
						$student->m_income =  $retrieve_reg[0]['father_annual_income']  ;
						$student->aadhar =  $retrieve_reg[0]['aadhar_no'] ;
						$student->cbse_regno =  ""  ;
						$student->dis_code =  ""  ;
						$student->boarder =  "" ;
						$student->s_age =  $this->request->data('s_age')  ;
						$student->org_adm_no =  ""  ;
						$student->a_route_no =  ""  ;
						$student->d_route_no =  ""  ;
						$student->stopage =  $retrieve_reg[0]['stoppage'] ;
						$student->sibl_in_scl =  $retrieve_reg[0]['sibling_details1'] ;
						$student->email =  $retrieve_reg[0]['local_email_id'] ;
						$student->password = "" ;
						$student->bank_acc_no =  ""  ;
						$student->ifsc =  ""  ;
						$student->dnd =  "" ;
						$student->device_id =  ""  ;
						$student->oth_infor =  ""  ;
						$student->stud_left =  ""  ;
						$student->left_dt =  "";
						$student->school_id = $retrieve_reg[0]['school_id'] ;
						$student->gr1_path = $retrieve_reg[0]['father_pic'] ;
						$student->gr2_path = $retrieve_reg[0]['mother_pic'] ;
						$student->gr3_path = "";
						$student->session_id = $session_id;


						if($saved = $student_table->save($student) )
						{   
							$newstdntid = $saved->id;

							if($retrieve_student_acc == 0 )
							{
								$balance = $balance_table->newEntity();

								$balance->acc_no =  $adm_num; 
								$balance->s_name =  $retrieve_reg[0]['s_name'] ;
								$balance->s_f_name =  $retrieve_reg[0]['father_name'] ;
								$balance->bal_amt = 0 ;
								$balance->school_id = $retrieve_reg[0]['school_id'] ;
								$balance->session_id = $retrieve_reg[0]['session_id'] ;
								
								if($savedbal = $balance_table->save($balance) )
								{
								
									$activity = $activ_table->newEntity();
									$activity->action =  "Student Created"  ;
									$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
				
									$activity->value = md5($newstdntid)   ;
									$activity->origin = $this->Cookie->read('id')   ;
									$activity->created = strtotime('now');

									if($saved = $activ_table->save($activity) ){
										$update_form = $reg_table->query()->update()->set([  'form_admitted' => 1, 'adm_no' => $adm_num ])->where(['id' =>  $reg_formid ])->execute();
										$res = [ 'result' => 'success'  ];
										
										return $this->redirect('/enquiry');
			
									}
									else{
										$res = [ 'result' => 'activity not saved'  ];
			
									}
								}
								else{
									$res = [ 'result' => 'balance not update'  ];
								}
							}
							else{

								$retrieve_s_name = $balance_table->find()->select(['s_name' ])->where([ 'acc_no'=> $this->request->data('acc_no')  ])->toArray();

								$ret_stdnt_name = explode(",",$retrieve_s_name[0]['s_name']);
								$get_stdnt_name = explode(",",$this->request->data('s_name'));

								$mrgname = array_merge($ret_stdnt_name, $get_stdnt_name);
								$mrg_s_name = implode(',', $mrgname);

								if( $balance_table->query()->update()->set([ 's_name' => $mrg_s_name ])->where([ 'acc_no' => $this->request->data('acc_no')  ])->execute())
									{   
										$activity = $activ_table->newEntity();
										$activity->action =  "Student Created"  ;
										$activity->ip =  $_SERVER['REMOTE_ADDR'] ;
										$activity->origin = $this->Cookie->read('id');
										$activity->value = md5($newstdntid); 
										$activity->created = strtotime('now');
										$save = $activ_table->save($activity) ;

										if($save)
										{   
											$update_form = $reg_table->query()->update()->set([  'form_admitted' => 1 ])->where(['id' =>  $reg_formid ])->execute();
											$res = [ 'result' => 'success'  ];
											return $this->redirect('/enquiry');
										}
										else
										{ 
											$res = [ 'result' => 'activity'  ];
											return $this->redirect('/enquiry');
										}
									}
									else
									{
										$res = [ 'result' => 'failed'  ];
										return $this->redirect('/enquiry');
									}
							}    
	
						}
						else{
							$res = [ 'result' => 'student not saved'  ];
							return $this->redirect('/enquiry');
						}
                                   
                               
						     
                        } 
                        else{
                            $res = [ 'result' => 'uidai'  ];
							return $this->redirect('/enquiry');
                        }    
                    } 
                    else{
                        $res = [ 'result' => 'exist'  ];
						$this->Flash->set('Email already exist.', ['key' => 'alert']);
						return $this->redirect('/enquiry');
                    }
					
                }


	
	public function regformpdf($id = null)
            {
                
                $student_table = TableRegistry::get('student');
                $activ_table = TableRegistry::get('activity');
                $school_table = TableRegistry::get('company');
                $school_id =$this->request->session()->read('company_id');
                $reg_table = TableRegistry::get('registeration');
                $session_table = TableRegistry::get('session');
                $class_table = TableRegistry::get('class');
		$stopage_table = TableRegistry::get('routechg');

                    
                $session_id = $this->Cookie->read('sessionid');
                
		$reg_details = $reg_table->find()->select(['id', 'school_id', 'registeration_id', 's_name', 'class', 'dob', 'aadhar_no', 'gender', 'only_child', 'category', 'father_name', 'father_dob', 'father_qualification', 'father_aadhar', 'father_occupation', 'father_designation', 'father_organization_name', 'father_office_address', 'father_contact_no', 'father_annual_income', 'father_pan_no', 'father_email', 'mother_name', 'mother_dob', 'mother_qualification', 'mother_aadhar', 'mother_occupation', 'mother_organization_name', 'mother_designation', 'mother_office_address', 'mother_contact_no', 'mother_annual_income', 'mother_pan_no', 'mother_email', 'local_guardian', 'relation', 'local_address', 'state', 'city', 'local_contact_no', 'local_email_id', 'last_school', 'last_class_passed', 'session', 'transport_required', 'stoppage', 'transport_amount', 'sibling_details1', 'sibling_details2', 'sibling_details3', 'passport_photo', 'previous_class_report', 'school_leaving_certificate', 'dob_certificate', 'affidavit', 'aadhar_card_document', 'student_pic', 'father_pic', 'mother_pic', 'reg_date', 'created_date', 'session_id', 'amount'])->where(['md5(id)' => $id ])->toArray();
	
                $school_details = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([
                    'states' => 
                        [
                        'table' => 'states',
                        'type' => 'LEFT',
                        'conditions' => 'states.id = company.state'
                    ],
                    'cities' => 
                        [
                        'table' => 'cities',
                        'type' => 'LEFT',
                        'conditions' => 'cities.id = company.city'
                    ]

                ])->where(['company.id '=> $school_id])->first() ;
                
                $session_details = $session_table->find()->select(['startyear', 'endyear' ])->where(['id '=> $session_id])->first() ;
                $class_details = $class_table->find()->select(['c_name', 'c_section' ])->where(['id '=> $reg_details[0]['class'] ])->first();
		$last_class_details = $class_table->find()->select(['c_name', 'c_section' ])->where(['id '=> $reg_details[0]['last_class_passed'] ])->first();
                $stopage_details = $stopage_table->find()->select(['village'])->where(['id '=> $reg_details[0]['stoppage']])->first();        

		$sibling1name = "";
                $sibling1class = "";
                $sibling1section = "";
                if(!empty($reg_details[0]['sibling_details1'])){

                $sibling_details1 = $student_table->find()->select(['student.s_name', 'class.c_name' , 'class.c_section' ])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.id '=> $reg_details[0]['sibling_details1']])->first() ;
                	
                	$sibling1name = $sibling_details1['s_name'];
                	$sibling1class = $sibling_details1['class']['c_name'];
                	$sibling1section = $sibling_details1['class']['c_section'];
                }

                $sibling2name = "";
                $sibling2class = "";
                $sibling2section = "";
                if(!empty($reg_details[0]['sibling_details2'])){

				$sibling_details2 = $student_table->find()->select(['student.s_name', 'class.c_name' , 'class.c_section' ])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.id '=> $reg_details[0]['sibling_details2']])->first() ;

			$sibling2name = $sibling_details2['s_name'];
                	$sibling2class = $sibling_details2['class']['c_name'];
                	$sibling2section = $sibling_details2['class']['c_section'];

				}

		$sibling3name = "";
                $sibling3class = "";
                $sibling3section = "";
				if(!empty($reg_details[0]['sibling_details3'])){

				$sibling_details3 = $student_table->find()->select(['student.s_name', 'class.c_name' , 'class.c_section' ])->join(['class' => 
							[
							'table' => 'class',
							'type' => 'LEFT',
							'conditions' => 'class.id = student.class'
						]
					])->where(['student.id '=> $reg_details[0]['sibling_details3']])->first() ;   

			$sibling3name = $sibling_details3['s_name'];
                	$sibling3class = $sibling_details3['class']['c_name'];
                	$sibling3section = $sibling_details3['class']['c_section'];  
               }
               
	        $dompdf = new Dompdf();
                       $dompdf->loadHtml('<div style="width: 100%;">
	<table style="margin-top:100px; width: 100%;">
		<tbody>
			<tr>
				<td colspan="2">
					<table style="border:1px solid black; width: 100%; padding-left: 10px;">
						<tr>
							<td>Registration No.: <span style="font-weight: bold">'. $reg_details[0]['registeration_id'] .'</span></td>
							<td class="txtcntr" colspan="2">Registration Date :<span style="font-weight: bold">'. date('d-m-Y',$reg_details[0]['created_date']) .'</span></td>
						</tr>
						<tr>
							<td>Class Applied For :<span style="font-weight: bold">'. $class_details['c_name'] .'</span></td>
							<td colspan="2">Aadhar Card No.:<span style="font-weight: bold">'. $reg_details[0]['aadhar_no'] .'</span> </td>
						</tr>
						<tr>
							<td>Student Name (As on Aadhar Card) :<span style="font-weight: bold">'. $reg_details[0]['s_name'] .'</span></td>
							<td colspan="2">Category :<span style="font-weight: bold">'. $reg_details[0]['category'] .'</span></td>
						</tr>
						<tr>
							<td>Date of Birth(dd/mm/yyyy):<span style="font-weight: bold"> '. date('d-m-Y',strtotime($reg_details[0]['dob'])) .'</span></td>
							<td >Gender : <span style="font-weight: bold">'. $reg_details[0]['gender'] .'</span></td>
							<td >Only Child : <span style="font-weight: bold">'. $reg_details[0]['only_child'] .'</span></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="border:1px solid black; width: 100%; height: 70px; ">
						<tr>
							<th></th>
							<th colspan="2" style="padding-right: 60px; padding-bottom: 15px;">PARENTS DETAILS</th>
						</tr>
						<tr>
							<th style="width: 40%;"></th>
							<th style="width: 32%; text-align: left;">Father Details</th>
							<th style="width: 28%; text-align: left;">Mother Details</th>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="details" style="width: 100%; border-collapse: collapse; ">
						<tr>
							<td style="border: 1px solid black;">Name :</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_name'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_name'] .'</td>
						</tr>
						<tr >
							<td style="border: 1px solid black;">Date of Birth :</td>
							<td colspan="2" style="border: 1px solid black;">'. date('d-m-Y',strtotime($reg_details[0]['father_dob'])) .'</td>
							<td colspan="2" style="border: 1px solid black;">'. date('d-m-Y',strtotime($reg_details[0]['mother_dob'])) .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Qualification :</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_qualification'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_qualification'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Aadhaar Card no.:</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_aadhar'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_aadhar'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Occupation :</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_occupation'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_occupation'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Designation :</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_designation'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_designation'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Organization Name : </td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_organization_name'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_organization_name'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Office Address :</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_office_address'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_office_address'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Annual Income :</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['father_annual_income'] .'</td>
							<td style="border: 1px solid black;">PAN No.:'. $reg_details[0]['father_pan_no'] .'</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['mother_annual_income'] .'</td>
							<td style="border: 1px solid black;">PAN No.:'. $reg_details[0]['mother_pan_no'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Contact No.:</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_contact_no'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_contact_no'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">Email ID:</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['father_email'] .'</td>
							<td colspan="2" style="border: 1px solid black;">'. $reg_details[0]['mother_email'] .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="height: 90px; width:100%; border:1px solid black;">
						<tr>
							<td>Local Guardian:'. $reg_details[0]['local_guardian'] .' </td>
							<td>Relation:'. $reg_details[0]['relation'] .' </td>
							<td colspan="2">Last School Attended:'. $reg_details[0]['last_school'] .'</td>
						</tr>
						<tr>
							<td colspan="2" rowspan="3" >Local Address: '. $reg_details[0]['local_address'] .'</td>
							<td colspan="2">Last Class with Session:'. $last_class_details['c_name'] .' '. $reg_details[0]['session'] .' </td>
						</tr>
						<tr>
							<td colspan="2">Email ID:'. $reg_details[0]['local_email_id'] .'</td>
						</tr>
						<tr>	
							<td colspan="2">Transport Required: '. $reg_details[0]['transport_required'] .'</td>
						</tr>
						<tr>
							<td colspan="2">Contact No.: '. $reg_details[0]['local_contact_no'] .'</td>
							<td colspan="2">Bus Stop:'. $stopage_details['village'] .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; vertical-align: top;">
					<table style="border:1px solid black; width: 100%; ">
						<tr>
							<th style="text-align: left; padding-left: 10px;"><u>SIBLINGS DETAILS</u></th>
						</tr>
						<tr>
							<td style="padding-left: 20px;">'. $sibling1name." ( ". $sibling1class ." ". $sibling1section .' )</td>
						</tr>
						<tr>
							<td style="padding-left: 20px;">'. $sibling2name." ( ". $sibling2class ." ". $sibling2section .' )</td>
						</tr>
						<tr>
							<td style="padding-left: 20px;">'. $sibling3name." ( ". $sibling3class ." ". $sibling3section .' )</td>
						</tr>
					</table>
				</td>
				<td style="width: 50%; ">
					<table class="details" style="width: 100%; border-collapse: collapse;">
						<tr>
							<th style="border: 1px solid black;"><u>Documents Required</u></th>
							<th style="border: 1px solid black;"><u>Submitted</u></th>
						</tr>
						<tr>
							<td style="border: 1px solid black;">1. Passport Sized Photographs</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['passport_photo'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">2. Photocopies of Report Card</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['previous_class_report'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">3. Original School Leaving Certificate</td>				
							<td style="border: 1px solid black;">'. $reg_details[0]['school_leaving_certificate'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">4. Date of Birth Certificate</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['dob_certificate'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">5. One affidavit containig Details</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['affidavit'] .'</td>
						</tr>
						<tr>
							<td style="border: 1px solid black;">6. Photocopies of Aadhaar Card</td>
							<td style="border: 1px solid black;">'. $reg_details[0]['aadhar_card_document'] .'</td>
						</tr>
						
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="padding: 10px 10px 20px 10px; width: 100%;" >
						<tr>
							<th colspan="2" style="text-align: left; padding-left:10px; "><u>Undertaking</u></th>
						</tr>
						<tr>
							<td colspan="2">I hereby certify that the information given above is correct. However, I understand that if at any stage it is found that the information given by me is incorrect/false, or if I fail to submit the required documents within the committed date, the registration / admission of my ward is liable to be cancelled. I fully understand that mere submission of the registration form of my child does not guarantee admission in the school. I also agree that the decision of the Principal regarding the admission process will be final and binding on me.
							</td>
						</tr>
						<tr>
							<td style="padding-top: 50px;">.....................................................</td>
							<td style="padding-top: 50px;">.....................................................</td>
						</tr>
						<tr>
							<td style="font-weight: bold">SIGNATURE OF FATHER</td>
							<td style="font-weight: bold">SIGNATURE OF MOTHER</td>
						</tr>
						<tr >
							<td colspan="2" style="font-weight: bold">Note: Incomplete form will not be considered under any circumstances.</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

</div>
		');
                
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
                // Render the HTML as PDF
                $dompdf->render();
                
                // Output the generated PDF to Browser
                $dompdf->stream();
            
            }
               
                

            


}

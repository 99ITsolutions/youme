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
class EmployeesController  extends AppController
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
                $teacher_table = TableRegistry::get('employee');
                $role_table = TableRegistry::get('employeeroles');
                $compid = $this->request->session()->read('company_id');

                $retrieve_teacher_table = $teacher_table->find()->select(['employee.id' , 'employee.e_code', 'employee.e_name','employee.mobile_no' ,'employee.quali','employee.doj'  , 'employee.dob' , 'employeeroles.name' ])->join(['employeeroles' => 
                        [
                        'table' => 'employeeroles',
                        'type' => 'LEFT',
                        'conditions' => 'employeeroles.id = employee.role'
                    ]
                ])->where([ 'employee.school_id '=> $compid])->toArray() ;
                
                $this->set("teacher_details", $retrieve_teacher_table); 
                $this->viewBuilder()->setLayout('user');
            }

            public function add()
            {   
                $role_table = TableRegistry::get('employeeroles');
                $school_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');

                $retrieve_role = $role_table->find()->select(['id' , 'name' ])->where([ 'status' => '1' , 'school_id '=> $compid])->toArray() ;

                $this->set("role_details", $retrieve_role);  
                $this->viewBuilder()->setLayout('user');
            }

            public function addemp(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $compid = $this->request->session()->read('company_id');  
                    $teacher_table = TableRegistry::get('employee');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['mobile_no' => $this->request->data('phone'), 'school_id' => $compid ])->count() ;
                    
                    if($retrieve_teacher == 0 )
                    {   
                        $role_table = TableRegistry::get('schoolownrole');
                        $retrieve_privilages = $role_table->find()->select(['privilage'])->where(['id' => $this->request->data('role') , 'school_id '=> $compid ])->first() ;
                        //$privilages = $retrieve_privilages->privilage ;

                        if(!empty($this->request->data['picture']))
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

                        if(!empty($picture))
                        {
                            $teacher = $teacher_table->newEntity();

                            $teacher->e_code =  $this->request->data('code')  ;
                            $teacher->e_name =  $this->request->data('name')  ;
                            $teacher->address = $this->request->data('address') ;
                            $teacher->quali = $this->request->data('qualification') ;
                            $teacher->f_name = $this->request->data('f_name') ;
                            $teacher->dob = date('Y-m-d', strtotime($this->request->data('dob'))) ;
                            $teacher->doj = date('Y-m-d', strtotime($this->request->data('doj'))) ;
                            $teacher->teachers = $this->request->data('teacher') ;
                            $teacher->amount = $this->request->data('amount') ;
                            $teacher->left = $this->request->data('left') ;
                            $teacher->mobile_no =  $this->request->data('phone')  ;
                            $teacher->pict =  $picture  ;
                            
                            /* Data not inserting*/
                            $email =  $this->request->data('email')  ;
                            $password = $this->request->data('password') ;
                            $role =  $this->request->data('role') ;
                            $department_name =  $this->request->data('department_name') ;
                            $date_c_start = date('Y-m-d', strtotime($this->request->data('date_c_start')));
                            $date_c_end = date('Y-m-d', strtotime($this->request->data('date_c_end')));
                            //$teacher->created = strtotime('now');
                        
                            if($saved = $teacher_table->save($teacher) )
                            {   
                                $teacherid = $saved->id;

                                if( $teacher_table->query()->update()->set([ 'school_id' => $compid  ,'email' => $email, 'password' => $password ,'role' => $role ,'department_name'=>$department_name,  'c_prd_fr'=> $date_c_start , 'c_prd_t'=>$date_c_end ])->where([ 'id' => $teacherid  ])->execute())
                                { 
                                    $activity = $activ_table->newEntity();
                                    $activity->action =  "Teacher Created"  ;
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
                                else
                                {
                                    $res = [ 'result' => 'Teacher record not insert properly'  ];
                                }    
            
                            }
                            else{
                                $res = [ 'result' => 'Teacher not saved'  ];
                            }
                        }
                        else
                        { 
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }     
                    } 
                    else{
                        $res = [ 'result' => 'A teacher with this mobile number is already exist'  ];
                    }

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }

            public function edit($id)
            {   
                $teacher_table = TableRegistry::get('employee');
                $role_table = TableRegistry::get('employeeroles');
                $compid = $this->request->session()->read('company_id');  

                $retrieve_teacher = $teacher_table->find()->select(['id' ,'e_code' , 'e_name' ,'address','mobile_no' , 'email' , 'password' , 'role' ,'lefts', 'department_name' , 'c_prd_t','c_prd_fr' , 'quali' , 'f_name' , 'dob' , 'doj' , 'pict' ,'teachers' , 'amount' ])->where([ 'md5(id)' => $id  ])->toArray() ;

                $retrieve_role = $role_table->find()->select(['id' , 'name' ])->where([ 'status' => 1 , 'school_id'=> $compid ])->toArray() ;
                
                $this->set("role_details", $retrieve_role); 
                $this->set("teacher_details", $retrieve_teacher);  
                $this->viewBuilder()->setLayout('user');
            } 


            public function editemp()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $teacher_table = TableRegistry::get('employee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');  

                    $retrieve_teacher = $teacher_table->find()->select(['id'  ])->where(['mobile_no' => $this->request->data('phone') , 'id <>' => $this->request->data('id') , 'school_id'=> $compid ])->count() ;
                    
                    if($retrieve_teacher == 0 )
                    { 
                        if(!empty($this->request->data['picture']))
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
                            
                        }else
                        {
                            $picture =  $this->request->data('apicture');
                        }
                        
                        $tid = $this->request->data('id');
                        $e_code = $this->request->data('e_code');
                        $e_name = $this->request->data('e_name');
                        $address = $this->request->data('address');
                        $quali = $this->request->data('qualification');
                        $f_name = $this->request->data('f_name');
                        $email = $this->request->data('email');
                        $password = $this->request->data('password');
                        $phone = $this->request->data('phone');
                        $dob = date('Y-m-d', strtotime($this->request->data('dob')));
                        $doj = date('Y-m-d', strtotime($this->request->data('doj')));
                        $teacher = $this->request->data('teacher');
                        $amount = $this->request->data('amount');
                        $role = $this->request->data('role');
                        $left = $this->request->data('left');
                        $department_name = $this->request->data('department_name');
                        $date_c_start = date('Y-m-d', strtotime($this->request->data('date_c_start')));
                        $date_c_end = date('Y-m-d', strtotime($this->request->data('date_c_end')));

                        $role_table = TableRegistry::get('schoolownrole');
                            $retrieve_privilages = $role_table->find()->select(['privilage'])->where(['id' => $this->request->data('role'), 'school_id'=> $compid  ])->first() ;
                        $privilages = $retrieve_privilages->privilage ;
                        
                        if(!empty($picture))
                        {    
                            if( $teacher_table->query()->update()->set([ 'e_code' => $e_code , 'e_name' => $e_name, 'address' => $address, 'quali'=>$quali, 'f_name'=>$f_name, 'email' => $email,  'mobile_no' => $phone, 'password' => $password , 'dob' => $dob ,'doj'=>$doj , 'teachers' => $teacher , 'pict'=> $picture , 'amount' => $amount , 'role' => $role , 'department_name'=> $department_name , 'lefts' => $left , 'c_prd_fr'=> $date_c_start , 'c_prd_t'=> $date_c_end ])->where([ 'id' => $tid  ])->execute())
                            {   
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Teacher update"  ;
                                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                                $activity->origin = $this->Cookie->read('id');
                                $activity->value = md5($tid); 
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
                            $res = [ 'result' => 'Only Jpeg, Jpg and png allowed'  ];
                        }    
                    }
                    else
                    {
                        $res = [ 'result' => 'A teacher with this mobile number is already exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }  

            public function export()
            {   
                $teacher_table = TableRegistry::get('employee');

                $data = $teacher_table->find()->select(['id' ,'e_code' ,'e_name','address' ,'mobile_no','email' , 'f_name' , 'quali' ])->toArray() ;  
                
                
                $this->setResponse($this->getResponse()->withDownload('file.csv'));
                $_header = array('Id', 'Teacher COde' , 'Teacher Name' , 'Address' ,'Mobile Number' , 'Email' , 'Father Name' , 'Qualification');
                $_serialize = 'data';
                $this->viewBuilder()->setClassName('CsvView.csv');
                $this->set(compact('data', '_header' , '_serialize'));
            }


            public function import()
            {   
                if ($this->request->is('post') )
                {
                    $teacher_table = TableRegistry::get('employee');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');

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
                                $data = array();
                             /*foreach ($row as  $value) {*/
                                    
                                    $code = $row[0];
                                    $name = $row[1]; 
                                    $address = $row[2];
                                    $f_name = $row[3];  
                                    $phone = $row[4];
                                    $qualification = $row[5];
                                    $department_name = $row[6];
                                    $dob = $row[7];
                                    $doj = $row[8];
                                    $email = $row[9];
                                    $password = $row[10];
                                    $salary = $row[11];
                                    $date_c_start = $row[12]; 
                                    $date_c_end = $row[13];    

                                    $teacher = $teacher_table->newEntity();

                                    $teacher->e_code =  $code ;
                                    $teacher->e_name =  $name  ;
                                    $teacher->address = $address ;
                                    $teacher->quali = $qualification ;
                                    $teacher->f_name = $f_name;
                                    $teacher->dob = date('Y-m-d', strtotime($dob)) ;
                                    $teacher->doj = date('Y-m-d', strtotime($doj)) ;
                                    $teacher->amount = $salary ;
                                    $teacher->mobile_no =  $phone ;
                                    $teacher->email =  $email ;
                                    $teacher->password =  $password ;
                                    $teacher->department_name =  $department_name ;
                                    $teacher->c_prd_fr = date('Y-m-d', strtotime($date_c_start));
                                    $teacher->c_prd_t = date('Y-m-d', strtotime($date_c_end));
                                    $teacher->school_id = $compid;

                                    if($saved = $teacher_table->save($teacher) )
                                    {   
                                        $activity = $activ_table->newEntity();
                                        $activity->action =  "Teachers Created"  ;
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
                                        $res = [ 'result' => 'Teacher not saved'  ];
                                    }
                                //}
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



            public function delete()
            {
                $rid = $this->request->data('val') ;
                $employee_table = TableRegistry::get('employee');
                $activ_table = TableRegistry::get('activity');
                
                $roleid = $employee_table->find()->select(['id'])->where(['id'=> $rid ])->first();
                if($roleid)
                {   
                    
                    $del = $employee_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                    if($del)
					{
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Teacher Deleted"  ;
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


            public function view($id)
            {   
                $teacher_table = TableRegistry::get('employee');
                $role_table = TableRegistry::get('schoolownrole');
                $compid = $this->request->session()->read('company_id');  

                $retrieve_teacher = $teacher_table->find()->select(['id' ,'e_code' , 'e_name' ,'address','mobile_no' , 'email' , 'password' , 'role' ,'lefts', 'department_name' , 'c_prd_t','c_prd_fr' , 'quali' , 'f_name' , 'dob' , 'doj' , 'pict' ,'teachers' , 'amount' ])->where([ 'md5(id)' => $id  ])->toArray() ;

                $retrieve_role = $role_table->find()->select(['id' , 'name' ])->where([ 'status' => 1 , 'school_id'=> $compid ])->toArray() ;
                
                $this->set("role_details", $retrieve_role); 
                $this->set("teacher_details", $retrieve_teacher);  
                $this->viewBuilder()->setLayout('user');
            } 





}

  


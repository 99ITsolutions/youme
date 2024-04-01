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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ParentCasesController extends AppController
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
                $compid = $this->request->session()->read('company_id');
                $company_table = TableRegistry::get('company');
                $class_table = TableRegistry::get('class');
                $message_table = TableRegistry::get('parent_message');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid)) 
                {
                    $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                    
                    $student_table = TableRegistry::get('student');
                    foreach($retrieve_msg_table as $key =>$msg)
            		{
            		    $msg->sender = ''; 
            		    if($msg->from_type == 'parent'){
            		        
                		    $msg->student_no = ''; 
                		    $msg->classname = '';
            		        $retrieve_student = $student_table->find()->where(['id'=> $msg->student_id ])->first();
            		        
            		        if(!empty($retrieve_student))
            		        {
                		        $msg->sender = $retrieve_student->f_name.' '.$retrieve_student->l_name; 
                		        $msg->student_no = $retrieve_student->adm_no; 
                		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_student->class ])->first();
                		        if(!empty($retrieve_class))
                		        {
                		            $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section." (".$retrieve_class->school_sections.")"; 
                		            //$msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section. "(".$retrieve_class->school_sections.")"; 
            		                $msg->sclsection = $retrieve_class->school_sections;
                		        }
            		        }
            		        
            		    }else if($msg->from_type == 'school'){
            		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
            		        if(!empty($retrieve_comp)) {
            		            $msg->sender = $retrieve_comp->comp_name; 
            		        }
            		        $msg->student_no = "";
            		        $msg->classname = "";
            		        $msg->sclsection = "";
            		    }
            		    
            		    $count_read = $message_table->find()->where(['parent'=> $msg->id, 'to_type'=>'school', 'read_msg'=>'0'])->count();
            		    //print_r($count_read);exit;
            		    $msg->read_count = $count_read; 
            		}
                    $this->set("message_details", $retrieve_msg_table);  
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;  
                }
            }
            
            public function getstudent(){   
                if($this->request->is('post')){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $student_table = TableRegistry::get('student');
                    $compid = $this->request->session()->read('company_id');
                    $retrieve_student = $student_table->find()->where(['class' => $id, 'school_id' => $compid])->toArray(); 
                    
                    return $this->json($retrieve_student);
                }  
            }
            
            public function view($id){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = base64_decode($id);
                $compid = $this->request->session()->read('company_id');
                $message_table = TableRegistry::get('parent_message');
                $retrieve_unreadmsg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id, 'to_type'=> 'school'])->toArray();
                foreach($retrieve_unreadmsg as $unread)
                {
                    //print_r($unread); 
                    if($unread['read_msg'] == 0)
                    {
                        $message_table->query()->update()->set([ 'read_msg'=> '1', 'read_datetime' => time() ])->where([ 'id' => $unread['id']  ])->execute();
                
                    }
                }
                
                //$message_table->query()->update()->set([ 'read_msg'=> '1'  , 'read_datetime' => time()])->where([ 'id' => $id  ])->orWhere(['parent' => $id, 'to_type'=> 'school'])->execute();
                
                $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
        		$all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();
        		
        		$company_table = TableRegistry::get('company');
        		$student_table = TableRegistry::get('student');
        		$class_table = TableRegistry::get('class');
        		foreach($all_msg as $key =>$msg){
        		    if($msg->from_type == 'parent'){
        		        $retrieve_student = $student_table->find()->where(['id'=> $msg->student_id ])->first();
        		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_student->class ])->first();
        		        $msg->sender = $retrieve_student->f_name.' '.$retrieve_student->l_name; 
        		        $msg->student_no = $retrieve_student->adm_no; 
        		        $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section." (".$retrieve_class->school_sections.")"; 
        		    }else if($msg->from_type == 'school'){
        		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
        		        $msg->sender = $retrieve_comp->comp_name; 
        		        $msg->student_no = "";
        		        $msg->classname = "";
        		    }
        		}
        		$this->set("id", $id); 
                $this->set("all_messages", $all_msg); 
                $this->set("get_messages", $retrieve_msg); 
                $this->viewBuilder()->setLayout('user');
            }
            
           
            public function addmessage(){   
                if ($this->request->is('ajax') && $this->request->is('post') ){
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $message_table = TableRegistry::get('parent_message');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id=$this->request->data('id');
                    
                    if(!empty($compid)) {
                        $msg = $message_table->newEntity();
                        if($this->request->data('id')){
                            
                            $msg->parent = $this->request->data('id');	 
                            $msg->subject = '' ;
                            $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
                            $msg->to_type = $retrieve_msg->from_type;
                            $msg->to_id = $retrieve_msg->from_id;
                            $msg->student_id = $retrieve_msg->student_id;
                            $msg->from_id = $compid;
                            $msg->from_type ='school';
                            $msg->message = $this->request->data('message');
                            $msg->school_id = $compid;
                            $msg->created_date = time();
                        }
                    
                        if(!empty($this->request->data['upload_file'])){   
                            $filename = time().$this->request->data['upload_file']['name'];
                            $uploadpath = 'messages/';
                            $uploadfile = $uploadpath.$filename;
                            if(move_uploaded_file($this->request->data['upload_file']['tmp_name'], $uploadfile))
                            {
                                $msg->attachment = $filename; 
                            }
                        }
                                       
                        if($saved = $message_table->save($msg) ){     
                            $strucid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Message Send"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($strucid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'  , 'msgid' => $strucid ];
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
                         return $this->redirect('/login/') ;  
                    }
                     
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
           

}

  


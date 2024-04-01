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
class TutoringCenterController   extends AppController
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
                $sessionid = $this->request->session()->read('session_id'); 
                $compid = $this->request->session()->read('company_id'); 
                $classid = $this->request->session()->read('class_id'); 
                $student_id = $this->request->session()->read('student_id'); 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $studenttutorial_table = TableRegistry::get('student_tutorial_logins');
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                $emp_table = TableRegistry::get('employee');
                
                $retrieve_tutorsub = $studenttutorial_table->find()->where(['class_id' => $classid, 'session_id' => $sessionid, 'school_id' => $compid , 'student_id' => $student_id ])->toArray();
               
                foreach($retrieve_tutorsub as $key => $val)
                {
    			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['school_id' => $compid, 'id' => $val['subject_id'] ])->first() ;	
    				$snames = $retrieve_subjects['subject_name'];
        				
        			$retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'id' => $val['class_id']])->first() ;
        			$cnames = $retrieve_class['c_name']."-".$retrieve_class['c_section']. "(". $retrieve_class['school_sections'].")";
        			
        			$retrieve_emp = $emp_table->find()->select(['id' ,'f_name', 'l_name'])->where(['school_id' => $compid, 'id' => $val['teacher_id']])->first() ;
        			$enames = $retrieve_emp['f_name']." ".$retrieve_emp['l_name'];
        			
        			$val->subjects_name = $snames;
        			$val->grades_name = $cnames;
        			$val->emp_name = $enames;
        		
                }
                $this->set("tutorsub_details", $retrieve_tutorsub); 
				$this->viewBuilder()->setLayout('user');
            
            }
            
            
            public function subjects($tid, $classid, $subjectid )
            {   
                $compid = $this->request->session()->read('company_id');
                $tutorialcontent_table = TableRegistry::get('tutorial_content');
                $retrieve_content = $tutorialcontent_table->find()->where([ 'teacher_id' => $tid, 'class_id' => $classid, 'subject_id' => $subjectid, 'school_id' => $compid ])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->set("classId", $classid); 
                $this->set("subjectid", $subjectid); 
                $this->set("tid", $tid); 
                $this->set("content_details", $retrieve_content); 
				$this->viewBuilder()->setLayout('user');
            }

            public function add()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }
            
            /*public function addknowledge()
            {
               
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $knowledge_table = TableRegistry::get('knowledge_base');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        
                        
                        $filess = $file_link;
                    }
                    elseif($this->request->data('file_type') == "audio")
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['type'] == "application/pdf" )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->file_title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->file_description = $this->request->data('desc');
                        $knowledge->file_link_name = $filess;
    					$knowledge->active = 0;
                        $knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Knowledge Base Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }

            public function edit()
            {   
                $id = $this->request->data('id');
                $knowledge_table = TableRegistry::get('knowledge_base');
				$retrieve_knowledge = $knowledge_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
                //$this->viewBuilder()->setLayout('user');
            }
            
            public function editknowledge()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('knowledge_base');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        
                        
                        $filess = $file_link;
                        
                    }
                    elseif($this->request->data('efile_type') == "audio")
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['type'] == "application/pdf" )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'file_description' => $file_description, 'file_link_name' => $filess, 'file_title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Knowledge Base Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function view($id)
            {  
                $knowledge_table = TableRegistry::get('knowledge_base');
                $knowcomm_table = TableRegistry::get('knowledge_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($schoolid != null)
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$comm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}
        			if($userid != null)
        			{
        			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
        				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    
        			 $schoolid = $replycomm['school_id'];
        			 $userid = $replycomm['user_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($schoolid != null)
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$replycomm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}
        			if($userid != null)
        			{
        			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
        				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
        			}
        			
        			
        			
        		}
        		
        		
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function listing()
            {  
                $id = $this->request->data('id') ;
                $knowcomm_table = TableRegistry::get('knowledge_comments');
				$retrieve_comments = $knowcomm_table->find()->where(['parent' => $id])->toArray();
				
				//print_r($retrieve_comments); die;
                return $this->json($retrieve_comments);
            }



			public function delete()
            {
				
                $kbid = $this->request->data('val') ;
                $knowledge_table = TableRegistry::get('knowledge_base');
                
                $kid = $knowledge_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
                        
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
            }*/
            
            public function tutoringcenter()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $email = $this->request->data['email'];
                    $password = $this->request->data['password'];
                    $compid =$this->request->session()->read('company_id');
                    $tutorid = $this->request->data['tutorid'];
                    $student_login_table = TableRegistry::get('student_tutorial_logins');
                    $login_data = $student_login_table->find()->select(['id','teacher_id','class_id', 'subject_id' ])->where(['email '=> $email, 'password'=>$password, 'id' => $tutorid, 'school_id'=>$compid])->first();
                   
                    //$count_login = count($login_data); 
                   //print_r($login_data); die;
                    if(!empty($login_data)){
                        $res = [ 'result' => 'success', 'data'=>$login_data  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'Invalid Email Id & Password'  ];
                    }
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            
            public function viewtutcontent()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tutcontent_table = TableRegistry::get('tutorial_content');
                $filter = $this->request->data('filter');
                if($filter == "newest")
                {
                    $retrieve_content = $tutcontent_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $tutcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_content as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'audio')
                    {
                        $icon = '<i class="fa fa-headphones"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="'. $this->base_url.'tutoringCenter/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
            
             public function viewcontent($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tutcontent_table = TableRegistry::get('tutorial_content');
                $tutcomm_table = TableRegistry::get('tutorial_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                
                
                $retrieve_tutcontent = $tutcontent_table->find()->where(['md5(id)' => $id])->toArray();
                
				$retrieve_comments = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else if($addedby == 'school')
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$comm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}
        			else if($addedby == 'student')
        			{
        			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
        				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
        			}
        			if($addedby == 'teacher')
        			{
        			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
        				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
        			}
        		}
				$retrieve_replies = $tutcomm_table->find()->where(['md5(tutorial_content_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else if($addedby == 'school')
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$replycomm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}
        			else if($addedby == 'student')
        			{
        			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
        				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
        			}
        			else if($addedby == 'teacher')
        			{
        			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
        				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
        			}
        			
        			
        		}
                //print_r($replycomm);exit;
                $this->set("content_details", $retrieve_tutcontent); 
                $this->set("comments_details", $retrieve_comments); 
                $this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function addcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('tutorial_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->user_id = $this->request->session()->read('student_id');
                    $comments->teacher_id = $this->request->data('teacherid');
                    $comments->tutorial_content_id = $this->request->data('kid');
		            $comments->created_date = time();
		            $comments->added_by = "student";
                    //$comments->school_id = $this->request->data('schoolid');
                    $comments->parent = 0;
                   // print_r($comments);exit;
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function replycomments(){
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('tutorial_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->tutorial_content_id = $this->request->data('r_kid');
                    $comments->user_id = $this->request->session()->read('student_id');
                    $comments->teacher_id = $this->request->data('teacher_id');
                    //$comments->school_id = $this->request->data('skul_id');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "student";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
        public function tutorialMeetings($tid, $classid, $subjectid )
        {   
            $meetinglink_table = TableRegistry::get('meeting_link');
            $subject_table = TableRegistry::get('subjects');
            $class_table = TableRegistry::get('class');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
            $retrieve_sub = $subject_table->find()->where(['id' => $subjectid ])->first();
            
            $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']. "(". $retrieve_cls['school_sections'].")";
            $subjectname = $retrieve_sub['subject_name'];
            
            $retrieve_links = $meetinglink_table->find()->where([ 'generate_for' => 'Tutoring Center', 'teacher_id' => $tid, 'class_id' => $classid, 'subject_id' => $subjectid ])->toArray();
            $this->set("link_details", $retrieve_links); 
            $this->set("classname", $classname); 
            $this->set("subjectname", $subjectname); 
            $this->set("subjectid", $subjectid); 
            $this->set("classid", $classid); 
            $this->set("tid", $tid); 
    		$this->viewBuilder()->setLayout('user');
            
        }
}

  


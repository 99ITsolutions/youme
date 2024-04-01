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
                $studid = $this->request->session()->read('student_id');
                $tutorialcontent_table = TableRegistry::get('tutorial_content');
                $retrieve_content = $tutorialcontent_table->find()->where([ 'teacher_id' => $tid, 'class_id' => $classid, 'subject_id' => $subjectid, 'school_id' => $compid ])->toArray();
                foreach($retrieve_content as $content)
                {
                    if($content['student_id'] == "")
                    {
                        $content->display = 1;
                    }
                    else
                    {
                        $stuid = explode(",", $content['student_id']);
                        if(in_array($studid, $stuid))
                        {
                            $content->display = 1;
                        }
                        else
                        {
                            $content->display = 0;
                        }
                    }
                }
                
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
            
            public function tutoringcenter()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $email = trim($this->request->data['email']);
                    $password = trim($this->request->data['password']);
                    $compid = trim($this->request->session()->read('company_id'));
                    $tutorid = trim($this->request->data['tutorid']);
                    $student_login_table = TableRegistry::get('student_tutorial_logins');
                    
                    $login_data = $student_login_table->find()->select(['id','teacher_id','class_id', 'subject_id' ])->where(['email'=> $email, 'password'=> $password, 'id' => $tutorid, 'school_id'=>$compid])->first();
                   
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
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
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

  


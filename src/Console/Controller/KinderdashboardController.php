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
class KinderdashboardController  extends AppController
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
        $sclid = $this->request->session()->read('school_id');
        $stid = $this->request->session()->read('student_id'); 
        $kinderdash_table = TableRegistry::get('kinderdash');
		$student_table = TableRegistry::get('student');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		if(!empty($sclid))
		{
    		$retrieve_kinderdash = $kinderdash_table->find()->where([ 'school_id'=> $sclid ])->order(['id' => 'ASC'])->toArray();
    	
    		$this->set("kinderdash_details", $retrieve_kinderdash); 
    		$this->viewBuilder()->setLayout('kinder');
		}
		else
		{
		    return $this->redirect('/login/') ;   
		}
    }
    public function activity($id)
    {   
        //print_r($_SESSION); die;
		$kinderlib_table = TableRegistry::get('kindergarten_library');
		$kinderdash_table = TableRegistry::get('kinderdash');
        $compid = $this->request->session()->read('company_id');
        $clsid = $this->request->session()->read('class_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_kinderlib = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $id, 'class_id' => $clsid  ])->toArray() ;
            $cls_table = TableRegistry::get('class');
            $sub_table = TableRegistry::get('subjects');
            foreach($retrieve_kinderlib as $kinderlib)
            {
                $retrieve_class = $cls_table->find()->where(['id' => $kinderlib['class_id']  ])->first() ;
                $retrieve_subj = $sub_table->find()->where(['id' => $kinderlib['subject_id']  ])->first() ;
                $kinderlib->subject = $retrieve_subj['subject_name'];
                $kinderlib->classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            }
        
            $retrieve_kinderdash = $kinderdash_table->find()->where(['school_id' => $compid, 'id' => $id  ])->first() ;
            $dashname = $retrieve_kinderdash['dash_name'];
            $dashid = $retrieve_kinderdash['id'];
            $this->set("kinderlib", $retrieve_kinderlib); 
            $this->set("dashname", $dashname); 
            $this->set("dashid", $dashid); 
            $this->viewBuilder()->setLayout('kinder');
        }
		else
		{
		    return $this->redirect('/login/') ;   
		}
    }
    
    public function virtualclass()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$kindervirtual_table = TableRegistry::get('kinder_virtualclass');
        $subject_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        $classid = $this->request->session()->read('class_id');
        
        $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
        $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section'];
        
        $retrieve_links = $kindervirtual_table->find()->where(['class_id' => $classid])->toArray();
        $this->set("link_details", $retrieve_links); 
        $this->set("classname", $classname);
        $this->set("classid", $classid); 
		$this->viewBuilder()->setLayout('kinder');
    }
    
    public function links($classid, $subjectid)
    {
        
        $kindervirtual_table = TableRegistry::get('kinder_virtualclass');
        $subject_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
        $retrieve_sub = $subject_table->find()->where(['id' => $subjectid ])->first();
        
        $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section'];
        $subjectname = $retrieve_sub['subject_name'];
        
        $retrieve_links = $kindervirtual_table->find()->where(['class_id' => $classid, 'subject_id' => $subjectid ])->toArray();
        $this->set("link_details", $retrieve_links); 
        $this->set("classname", $classname); 
        $this->set("subjectname", $subjectname); 
        $this->set("subjectid", $subjectid); 
        $this->set("classid", $classid); 
		$this->viewBuilder()->setLayout('kinder');
    }
    
    public function getmeetingsts()
    {
        $id = $this->request->data('id');
        $kindervirtual_table = TableRegistry::get('kinder_virtualclass');
        $student_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $studid = $_SESSION['student_id'];
        $getstud = $student_table->find()->where([ 'id' => $studid  ])->first();
        $getdtaa = $kindervirtual_table->find()->where([ 'id' => $id  ])->first();
        
        $fname = implode("+", explode(" ", $getstud['f_name']));
        $lname = implode("+", explode(" ", $getstud['l_name']));
        $studname = trim($fname).trim($lname);
        $meetingsts['data'] = $getdtaa['meeting_status'];
        $meetingsts['studname'] = $studname;
        $meetingsts['meetingID'] = $getdtaa['meeting_id'];
        
        $secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA";
        $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
        $string4 = "joinmeetingID=". $getdtaa['meeting_id']."&password=111&fullName=".$studname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
        $sh4 = sha1($string4);
        $meetingsts['checksumm'] = $sh4;
        
        $this->Cookie->write('meetingid', $getdtaa['meeting_id'],  time()+1000000000000000 );
        
        return $this->json($meetingsts);
    }
    
    public function view($id)
    {  
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $knowcomm_table = TableRegistry::get('kindergarten_library_comments');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$retrieve_knowledge = $kinderlib_table->find()->where(['md5(id)' => $id])->toArray();
		$retrieve_comments = $knowcomm_table->find()->where(['md5(kinderlib_id)' => $id, 'parent' => 0])->toArray();
		$retrieve_replies = $knowcomm_table->find()->where(['md5(kinderlib_id)' => $id, 'parent !=' => 0])->toArray();
		
		foreach($retrieve_comments as $key =>$comm)
		{
		    $schoolid = $comm['school_id'];
			$userid = $comm['user_id'];
			$teacherid = $comm['teacher_id'];
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
			if($teacherid != null)
			{
			    $retrieve_teachers = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
				$comm->user_name = $retrieve_teachers[0]['f_name']. " ". $retrieve_teachers[0]['l_name'];
			}
		}
		$retrieve_replies = $knowcomm_table->find()->where(['md5(kinderlib_id)' => $id, 'parent !=' => 0])->toArray();
		
		foreach($retrieve_replies as $rkey => $replycomm)
		{
            $schoolid = $replycomm['school_id'];
            $userid = $replycomm['user_id'];
            $teacherid = $replycomm['teacher_id'];
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
			if($teacherid != null)
			{
			    $retrieve_teachers = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
				$replycomm->user_name = $retrieve_teachers[0]['f_name']. " ". $retrieve_teachers[0]['l_name'];
			}
		}
		$this->set("knowledge_details", $retrieve_knowledge); 
		$this->set("comments_details", $retrieve_comments); 
		$this->set("replies_details", $retrieve_replies); 
        $this->viewBuilder()->setLayout('kinder');
    }
    
    public function addcomment()
    {
        
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('kindergarten_library_comments');
            $activ_table = TableRegistry::get('activity');
            $studid = $this->request->session()->read('student_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->kinderlib_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->parent = 0;
            $comments->user_id = $studid;
                                  
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
    
    public function replycomments()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('kindergarten_library_comments');
            $activ_table = TableRegistry::get('activity');
            $student_id = $this->request->session()->read('student_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->kinderlib_id = $this->request->data('r_kid');
            $comments->created_date = time();
            $comments->parent = $this->request->data('comment_id');
            $comments->user_id = $student_id;
                                  
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
    
    public function filteractivities()
    {
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $filter = $this->request->data('filter');
        $dashid = $this->request->data('dashid');
        $compid = $this->request->session()->read('company_id');
        $clsid = $this->request->session()->read('class_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            if($filter == "newest")
            {
                $retrieve_content = $kinderlib_table->find()->where(['school_id' => $compid, 'class_id' => $clsid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'pdf', 'class_id' => $clsid, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'audio', 'class_id' => $clsid, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'video', 'class_id' => $clsid, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
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
                
                $cls_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $retrieve_class = $cls_table->find()->where(['id' => $content['class_id']  ])->first() ;
                $retrieve_subj = $sub_table->find()->where(['id' => $content['subject_id']  ])->first() ;
                $subjectname = $retrieve_subj['subject_name'];
                $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            
                $res .= '<div class="col-sm-3 col_img">
                        <a href="'.$this->base_url.'kinderdashboard/view/'. md5($content['id']) .'" class="viewknow" ><div class="set_icon">'.$icon.'</div>'.
                    $img.'
                    </a>
                    <p class="title" style="color:#000"><b>Title:</b>'. $content['title'].'</p>
                    <p class="title" style="color:#000"><b>Class:</b>'. $classname.'</p>
                    <p class="title" style="color:#000"><b>Subjects:</b>'. $subjectname.'</p>
                </div>';
                
            }
            
            return $this->json($res);
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
}

  


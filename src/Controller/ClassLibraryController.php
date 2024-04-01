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
class ClassLibraryController   extends AppController
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
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
        
        $student_table = TableRegistry::get('student');
        $subjects_table = TableRegistry::get('subjects');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sid =$this->request->session()->read('student_id');
		$session_id = $this->Cookie->read('sessionid');

        if(!empty($stid)) {
        $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.subjects', 'student.library_access', 'student.class', 'student.school_id', 'class_subjects.subject_id'])->join(['class_subjects' => 
							[
							'table' => 'class_subjects',
							'type' => 'LEFT',
							'conditions' => 'class_subjects.class_id = student.class'
						]
					])->where(['md5(student.id)' => $stid ])->toArray() ;
		
		foreach($retrieve_student_table as $key =>$sub_coll)
		{
			$subid = explode(",",$sub_coll['class_subjects']['subject_id']);
			$i = 0;
			$subjectsname = [];
			$subjectids = [];
			foreach($subid as $sid)
			{
			    $retrieve_subjects = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $sid])->toArray() ;	
				foreach($retrieve_subjects as $rstd)
				{
					$subjectsname[$i] = $rstd['subject_name'];
					$subjectids[$i] = $rstd['id'];
				}
				$i++;
				$snames = implode(",", $subjectsname);
				$subids = implode(",", $subjectids);
			}
			$sub_coll->subjects_name = $snames;
			$sub_coll->subjects_ids = $subids;
		}	
        
        $this->set("student_subjects", $retrieve_student_table); 
        $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function library($classid, $subjectid)
    {
        $compid = $this->request->session()->read('company_id');
        $content_table = TableRegistry::get('school_library');
        if(!empty($compid)) {
        $retrieve_content = $content_table->find()->where([ 'school_id' => $compid , 'class_id' => $classid, 'subject_id' => $subjectid])->toArray();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$class_table = TableRegistry::get('class');
        $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $classid, 'active' => 1])->first() ;
        $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']. "(". $retrieve_class['school_sections'].")";
        
        $subjects_table = TableRegistry::get('subjects');
        $retrieve_sub = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $subjectid, 'status' => 1])->first() ;
        $subname = $retrieve_sub['subject_name'];
        
        $this->set("cls_name", $classname);
        $this->set("sub_name", $subname);
        $this->set("classid", $classid);
        $this->set("subjectid", $subjectid);
        $this->set("content_details", $retrieve_content); 
		$this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function viewcontent($id)
    {  
        $libcontent_table = TableRegistry::get('school_library');
        $libcomm_table = TableRegistry::get('library_comments');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_libcontent = $libcontent_table->find()->where(['md5(id)' => $id])->toArray();
        
		$retrieve_comments = $libcomm_table->find()->where(['md5(lib_content_id)' => $id, 'parent' => 0])->toArray();
		$retrieve_replies = $libcomm_table->find()->where(['md5(lib_content_id)' => $id, 'parent !=' => 0])->toArray();
		
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
			else
			{
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
    			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
    				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
    			}
			}
		}
		$retrieve_replies = $libcomm_table->find()->where(['md5(lib_content_id)' => $id, 'parent !=' => 0])->toArray();
		
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
			else
			{
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
    			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
    				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
    			}
			}
		}
        
        $this->set("content_details", $retrieve_libcontent); 
        $this->set("comments_details", $retrieve_comments); 
        $this->set("replies_details", $retrieve_replies); 
        $this->viewBuilder()->setLayout('user');
    }

    public function addcomment()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('library_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($this->Cookie->read('stid')))
            {
                $stid = $this->Cookie->read('stid'); 
            }
            elseif(!empty($this->Cookie->read('pid')))
            {
                $stid = $this->Cookie->read('pid'); 
            }
            if(!empty($stid)) {
            $stuent_table = TableRegistry::get('student');
		    $retrieve_student = $stuent_table->find()->where([ 'md5(id)' => $stid, 'status' => 1 ])->first();
		    
		    $studentid = $retrieve_student['id'];
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->lib_content_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->added_by = "student";
            $comments->user_id = $studentid;
            $comments->parent = 0;
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
    		    return $this->redirect('/login/') ;    
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
            $comments_table = TableRegistry::get('library_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($this->Cookie->read('stid')))
            {
                $stid = $this->Cookie->read('stid'); 
            }
            elseif(!empty($this->Cookie->read('pid')))
            {
                $stid = $this->Cookie->read('pid'); 
            }
        
            if(!empty($stid)) {
            $stuent_table = TableRegistry::get('student');
		    $retrieve_student = $stuent_table->find()->where([ 'md5(id)' => $stid, 'status' => 1 ])->first();
		    
		    $studentid = $retrieve_student['id'];
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->lib_content_id = $this->request->data('r_kid');
            $comments->user_id = $studentid;
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
    		    return $this->redirect('/login/') ;    
    		}
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
    }
    
    public function viewlibcontent()
    {
        $libcontent_table = TableRegistry::get('school_library');
        $filter = $this->request->data('filter');
        $cls_id = $this->request->data('class_id');
        $sub_id = $this->request->data('subject_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if($cls_id != "" && $sub_id != "")
        {
            if($sub_id == "all") 
            {
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
            }
            
        }
        elseif($cls_id != "")
        {
            if($cls_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
        else
        {
            if($filter == "newest")
            {
                $retrieve_content = $libcontent_table->find()->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_content = $libcontent_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_content = $libcontent_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
            }
        }
        $res = '';
        foreach($retrieve_content as $content)
        {
            if(!empty($content['image'])) 
            { 
                $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
            } else { 
                $img = '<img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                <a href="'. $this->base_url.'classLibrary/viewcontent/'. md5($content['id']) .'" title="view" target="_blank">'.
                $img.'
                <div class="set_icon">'.$icon.'</div>
                 <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                </a>
            </div>';
        }
        
        return $this->json($res);
    }


            
}

  


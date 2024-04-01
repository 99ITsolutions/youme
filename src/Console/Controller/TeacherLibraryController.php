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
class TeacherLibraryController extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $subjects_table =  TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $tid = $this->Cookie->read('tid');
        $employee_table = TableRegistry::get('employee');
        if(!empty($tid))
        {
    		$retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id', 'subjects.id', 'subjects.subject_name'])->join(
                        [
        		            'employee_class_subjects' => 
                            [
                                'table' => 'employee_class_subjects',
                                'type' => 'LEFT',
                                'conditions' => 'employee.id = employee_class_subjects.emp_id'
                            ],
                            'class' => 
                            [
                                'table' => 'class',
                                'type' => 'LEFT',
                                'conditions' => 'class.id = employee_class_subjects.class_id'
                            ],
                            'subjects' => 
                            [
                                'table' => 'subjects',
                                'type' => 'LEFT',
                                'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                            ],
                            
        
                        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->toArray();
                        
    		
    		/*foreach($retrieve_employees as $key =>$emp_coll)
    		{
    			$gradeid = explode(",",$emp_coll['grades']);
    			$subid = explode(",",$emp_coll['subjects']);
    			$i = 0;
    			$empgrades = [];
    			foreach($gradeid as $gid)
    			{
    			    $retrieve_class = $class_table->find()->where([ 'id '=> $gid ])->toArray();
    				foreach($retrieve_class as $grad)
    				{
    					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section']. "(".$grad['school_sections'].")";					
    				}
    				$i++;
    				$gradenames = implode(",", $empgrades);
    				
    			}
    			$j = 0;
    			$empsub = [];
    			foreach($subid as $sid)
    			{
    			    $retrieve_subject = $subjects_table->find()->where([ 'id '=> $sid ])->toArray();
    				foreach($retrieve_subject as $subj)
    				{
    					$empsub[$j] = $subj['subject_name'];				
    				}
    				$j++;
    				$subjectnames = implode(",", $empsub);
    				
    			}
    			
    			$emp_coll->subjectName = $subjectnames;
    			
    			$emp_coll->gradesName = $gradenames;
    		}	
    		$this->set("employees_details", $retrieve_employees); */
    		$this->set("employees_details", $retrieve_empclses);
    		$this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function library($classid, $subjectid)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('tid');
        $compid = $this->request->session()->read('company_id');
        $content_table = TableRegistry::get('school_library');
        if(!empty($compid))
        {
            $retrieve_content = $content_table->find()->where([ 'school_id' => $compid , 'class_id' => $classid, 'subject_id' => $subjectid])->toArray();
            
    		$class_table = TableRegistry::get('class');
            $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $classid, 'active' => 1])->first() ;
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']. "(".$retrieve_class['school_sections'].")";	;
            
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
            $tid = $this->Cookie->read('tid');
            $employee_table = TableRegistry::get('employee');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($tid))
            {
    		    $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
    		    
    		    $tchrid = $retrieve_employees['id'];
                
                $comments = $comments_table->newEntity();
                $comments->comments = $this->request->data('comment_text');
                $comments->lib_content_id = $this->request->data('kid');
                $comments->created_date = time();
                $comments->added_by = "teacher";
                $comments->teacher_id = $tchrid;
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
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
            
            $comments_table = TableRegistry::get('library_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tid = $this->Cookie->read('tid');
            $employee_table = TableRegistry::get('employee');
            if(!empty($tid))
            {
    		    $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
    		    $tchrid = $retrieve_employees['id'];
                
                $comments = $comments_table->newEntity();
                $comments->comments = $this->request->data('reply_text');
                $comments->lib_content_id = $this->request->data('r_kid');
                $comments->teacher_id = $tchrid;
                //$comments->school_id = $this->request->data('skul_id');
                $comments->created_date = time();
                $comments->parent = $this->request->data('comment_id');
                $comments->added_by = "teacher";
                                      
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
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $libcontent_table = TableRegistry::get('school_library');
                $filter = $this->request->data('filter');
                $cls_id = $this->request->data('class_id');
                $sub_id = $this->request->data('subject_id');
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
                        $img = '<img src ="https://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                        <a href="'. $this->base_url.'teacherLibrary/viewcontent/'. md5($content['id']) .'" title="view" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        </a>
                    </div>';
                }
                
                return $this->json($res);
            }

            
}

  


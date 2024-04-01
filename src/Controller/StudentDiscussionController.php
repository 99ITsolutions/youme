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
class StudentDiscussionController extends AppController
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
        if(!empty($_GET))
        {
            $classid = $this->request->query('clsid');
            $subjectid = $this->request->query('subid');
        }
        else
        {
            $classid = $this->request->data('classId');
            $subjectid = $this->request->data('subId');
        }
		
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $subjects_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        $discussion_table = TableRegistry::get('discussion');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        
        $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$stdid = $this->Cookie->read('stid');
		if(!empty($compid))
		{
            $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->first() ;
            $retrieve_classname = $class_table->find()->where(['id' => $classid ])->first() ;
    		
    		$this->set("schoolid", $compid); 
    		$this->set("classid", $classid); 
            $this->set("subjectid", $subjectid); 
            $this->set("subject_details", $retrieve_subjectname); 
            $this->set("class_details", $retrieve_classname); 
            
            $retrieve_comments = $discussion_table->find()->where(['session_id' => $session_id, 'school_id' => $compid, 'subject_id' => $subjectid, 'class_id' => $classid, 'parent' => 0])->toArray();
    		$retrieve_replies = $discussion_table->find()->where(['session_id' => $session_id, 'school_id' => $compid, 'subject_id' => $subjectid, 'class_id' => $classid, 'parent !=' => 0])->toArray();
		
    		foreach($retrieve_comments as $key =>$comm)
    		{
    		    $addedby = $comm['added_by'];
    			$userid = $comm['student_id'];
    			$teacherid = $comm['teacher_id'];
    			$subjectsname = [];
    			if($addedby == "superadmin")
    			{
    			    $comm->user_name = "You-Me Global Education";
    			}
    			else
    			{
        			/*if($schoolid != null)
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$comm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}*/
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
    		$retrieve_replies = $discussion_table->find()->where(['session_id' => $session_id, 'school_id' => $compid, 'subject_id' => $subjectid, 'class_id' => $classid, 'parent !=' => 0])->toArray();
    		
    		foreach($retrieve_replies as $rkey => $replycomm)
    		{
    		    $addedby = $replycomm['added_by'];
    			$schoolid = $replycomm['school_id'];
    			$userid = $replycomm['student_id'];
    			$teacherid = $replycomm['teacher_id'];
    			//$i = 0;
    			$subjectsname = [];
    			if($addedby == "superadmin")
    			{
    			    $replycomm->user_name = "You-Me Global Education";
    			}
    			else
    			{
        			/*if($schoolid != null)
        			{
        			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
        				$replycomm->user_name = $retrieve_school[0]['comp_name'];
        				
        			}*/
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
                
            $this->set("comments_details", $retrieve_comments); 
            $this->set("replies_details", $retrieve_replies);
            
            $this->viewBuilder()->setLayout('user');
		}
		else
		{
		     return $this->redirect('/login/') ;
		}
    }
            
            
    public function replycomments()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $discussion_table = TableRegistry::get('discussion');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $stid = $this->request->session()->read('student_id');
            $session_id = $this->Cookie->read('sessionid');
            if($stid)
            {
                $discussion = $discussion_table->newEntity();
                
                $discussion->comments = $this->request->data('reply_text');
                $discussion->session_id = $session_id;
                $discussion->created_date = time();
                $discussion->parent = $this->request->data('comment_id');
                $discussion->student_id = $stid;
                $discussion->school_id = $compid;
                $discussion->class_id = $this->request->data('clsid');
                $discussion->subject_id = $this->request->data('subid');
                $discussion->added_by = "student";
                                      
                if($saved = $discussion_table->save($discussion) )
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
        
        echo json_encode($res);
    }
    
    public function addcomment()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $discussion_table = TableRegistry::get('discussion');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $stid = $this->request->session()->read('student_id');
            $session_id = $this->Cookie->read('sessionid');
		    if(!empty($stid))
		    {
                $discussion = $discussion_table->newEntity();
                $discussion->comments = $this->request->data('comment_text');
                $discussion->student_id = $stid;
                $discussion->created_date = time();
                $discussion->parent = 0;
                $discussion->school_id = $compid;
                $discussion->session_id = $session_id;
                $discussion->class_id = $this->request->data('classid');
                $discussion->subject_id = $this->request->data('subjectid');
                $discussion->added_by = "student";
                
                if($saved = $discussion_table->save($discussion) )
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
}

  


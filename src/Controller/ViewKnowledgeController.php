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
class ViewKnowledgeController   extends AppController
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
        $knowledge_table = TableRegistry::get('knowledge_base');
        $compid =$this->request->session()->read('company_id');
		$session_id = $this->Cookie->read('sessionid');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
    	if(!empty($compid)) {
            $retrieve_videos = $knowledge_table->find()->where(['school_id' => $compid])->toArray() ;
            $this->set("content_details", $retrieve_videos); 
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }

            
    public function view($id)
    {  
        $knowledge_table = TableRegistry::get('knowledge_base');
        $knowcomm_table = TableRegistry::get('knowledge_comments');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
		$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
		
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
		
		$student_table = TableRegistry::get('student');
        //$compid =$this->request->session()->read('company_id');
		//$session_id = $this->Cookie->read('sessionid');
		if(!empty($this->Cookie->read('stid')))
		{
		    $std_id = $this->Cookie->read('stid');
		}
		elseif(!empty($this->Cookie->read('pid')))
		{
		    $std_id = $this->Cookie->read('pid');
		}
        $student_id = "";
		if(!empty($std_id))
		{
		    $retrieve_student = $student_table->find()->select(['id'])->where(['md5(id)' => $std_id])->toArray() ;
		    $student_id = $retrieve_student[0]['id'];
		    
		}
		$this->set("knowledge_details", $retrieve_knowledge); 
		$this->set("student_id", $student_id); 
		$this->set("comments_details", $retrieve_comments); 
		$this->set("replies_details", $retrieve_replies); 
        $this->viewBuilder()->setLayout('user');
    }
            
            
    public function replycomments()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('knowledge_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            if(!empty($this->Cookie->read('stid')))
			{
			    $std_id = $this->Cookie->read('stid');
			}
			elseif(!empty($this->Cookie->read('pid')))
			{
			    $std_id = $this->Cookie->read('pid');
			}
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->knowledge_id = $this->request->data('r_kid');
            $comments->created_date = time();
            $comments->parent = $this->request->data('comment_id');
            if(!empty($std_id))
		    {
                $comments->user_id = $this->request->data('ruser_id');
		    }
		    else
		    {
		        $comments->school_id = $this->request->data('ruser_id');
		    }
            //$comments->school_id = $this->request->data('skul_id');
                                  
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
    
    public function addcomment()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('knowledge_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            
		    if(!empty($this->Cookie->read('stid')))
			{
			    $std_id = $this->Cookie->read('stid');
			}
			elseif(!empty($this->Cookie->read('pid')))
			{
			    $std_id = $this->Cookie->read('pid');
			}
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->knowledge_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->parent = 0;
            
            if(!empty($std_id))
		    {
                $comments->user_id = $this->request->data('user_id');
		    }
		    else
		    {
		        $comments->school_id = $this->request->data('user_id');
		    }
            
                                  
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
            
}

  


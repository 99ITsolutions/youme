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
class KinderApplicationsController extends AppController
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
        $application_table = TableRegistry::get('application_data');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_app = $application_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
            $this->set("know_details", $retrieve_app); 
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
            
    public function view($id)
    {  
        $application_table = TableRegistry::get('application_data');
        $apps_comments_table = TableRegistry::get('application_data_comments');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $compid = $this->request->session()->read('company_id');
        
		$retrieve_knowledge = $application_table->find()->where(['md5(id)' => $id])->toArray();
		$retrieve_comments = $apps_comments_table->find()->where(['md5(app_id)' => $id, 'parent' => 0])->toArray();
		$retrieve_replies = $apps_comments_table->find()->where(['md5(app_id)' => $id, 'parent !=' => 0])->toArray();
		
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
		$retrieve_replies = $apps_comments_table->find()->where(['md5(app_id)' => $id, 'parent !=' => 0])->toArray();
		
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
		
		$this->set("school_id", $compid); 
		$this->set("knowledge_details", $retrieve_knowledge); 
		$this->set("comments_details", $retrieve_comments); 
		$this->set("replies_details", $retrieve_replies); 
        $this->viewBuilder()->setLayout('kinder');
    }
    
    public function replycomments()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('application_data_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $studid = $this->request->session()->read('student_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($studid))
            {
                $comments = $comments_table->newEntity();
                $comments->comments = $this->request->data('reply_text');
                $comments->app_id = $this->request->data('r_kid');
                $comments->created_date = time();
                $comments->parent = $this->request->data('comment_id');
                $comments->added_by = "student";
                $comments->user_id = $studid;
                                      
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
    
    public function addcomment()
    {
        //print_r($_POST); die;
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('application_data_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $studid = $this->request->session()->read('student_id');
            if(!empty($studid))
            {
                $comments = $comments_table->newEntity();
                $comments->comments = $this->request->data('comment_text');
                $comments->app_id = $this->request->data('kid');
                $comments->created_date = time();
                $comments->parent = 0;
                $comments->added_by = "student";
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

  


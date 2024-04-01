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
class DropboxController extends AppController
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
        $gc_table = TableRegistry::get('guide_content');
        $subject_table = TableRegistry::get('subjects');
        $emp_table = TableRegistry::get('employee');
        $clssub_table = TableRegistry::get('class_subjects');
        //print_r($_SESSION);
        $stid = $this->request->session()->read(['student_id']);
        $clsid = $this->request->session()->read(['class_id']);
        $sclid = $this->request->session()->read(['company_id']);
        
        $retrieve_gc = $gc_table->find()->where(['class_id' => $clsid, 'status' => 1])->order(['id' => 'desc'])->toArray() ;
        
        $retrieve_clssub = $clssub_table->find()->where(['class_id' => $clsid, 'school_id' => $sclid, 'status' => 1])->first() ;
        
        $subids = explode(",", $retrieve_clssub->subject_id);
        $subjects = [];
        $subject_id = [];
        $subjc = [];
        foreach($subids as $sid)
        {
            $retrieve_subj = $subject_table->find()->where(['id' => $sid ])->first() ;
            
            if(!empty($retrieve_subj['subject_name']))
            {
                $subjects[] = $retrieve_subj['subject_name'];
                $subject_id[] = $retrieve_subj['id'];
            }

        }
        
        foreach($retrieve_gc as $gc)
        {
            $retrieve_sub = $subject_table->find()->where(['id' => $gc['subject_id'] ])->first() ;
            $gc->subject_name = $retrieve_sub['subject_name'];
            
            $retrieve_emp = $emp_table->find()->where(['id' => $gc['teacher_id'] ])->first() ;
            $gc->teacher_name = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
        }
        //print_r($retrieve_gc); die;
        
        $retrieve_title = $gc_table->find()->where(['class_id' => $clsid, 'status' => 1])->group(['title'])->order(['id' => 'desc'])->toArray() ;
        $this->set("subjects", $subjects); 
        $this->set("sid", $subject_id); 
        $this->set("title_details", $retrieve_title); 
        $this->set("know_details", $retrieve_gc);
        $this->viewBuilder()->setLayout('user');
    }
        
	public function viewmachine()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $knowledge_table = TableRegistry::get('guide_content');
        $filter = $this->request->data('filter');
        $subjfilter = $this->request->data('subjfilter');
        $title = $this->request->data('title');
        $subject_table = TableRegistry::get('subjects');
        $emp_table = TableRegistry::get('employee');
        
        //print_r($_SESSION);
        $clsid = $this->request->session()->read(['class_id']);
        
        //print_r($_POST);
        
        if($subjfilter != "" && $title != "")
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where(['subject_id' => $subjfilter, 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'subject_id' => $subjfilter, 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'subject_id' => $subjfilter, 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'subject_id' => $subjfilter, 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
        }
        else if($subjfilter != "" && $title == "")
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where(['subject_id' => $subjfilter, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'subject_id' => $subjfilter, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'subject_id' => $subjfilter, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'subject_id' => $subjfilter, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
        }
        else if($subjfilter == "" && $title != "")
        {
            
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where(['title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title, 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
        }
        else
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where(['class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'class_id' => $clsid])->order(['id' => 'desc'])->toArray() ;
            }
        }
        $res = '';
        
        
        foreach($retrieve_know as $content)
        {
            
            $retrieve_sub = $subject_table->find()->where(['id' => $content['subject_id'] ])->first() ;
            $subject_name = $retrieve_sub['subject_name'];
            
            $retrieve_emp = $emp_table->find()->where(['id' => $content['teacher_id'] ])->first() ;
            $teacher_name = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
            
            if(!empty($content['image'])) 
            { 
                $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
            } else { 
                $img = '<img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
            }
            
            if($content->file_type == 'video')
            { 
                $icon = '<i class="fa fa-video-camera"></i>';
            }
            elseif($content->file_type == 'word')
            {
                $icon = '<i class="fa fa-file-word-o"></i>';
            } 
            else
            { 
                $icon = '<i class="fa fa-file-pdf-o"></i>';
            }
            
            $cours = '<b>Cours: </b>'. ucfirst($subject_name)."<br>";
            $tchr = '<b>Teacher Name: </b>'. ucfirst($teacher_name);
            
            $res .= '<div class="col-sm-2 col_img">
                <a href="'. $this->base_url.'dropbox/viewdropbox/'. md5($content['id']) .'" class="viewknow" >'.
                $shreicon.$img.'
                <div class="set_icon">'.$icon.'</div>
				</a>
				<p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>'.
                $cours.$tchr.'</p>
                </a>
            </div>';
        }
        
        return $this->json($res);
    }

	public function viewdropbox($id)
    {  
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $knowledge_table = TableRegistry::get('guide_content');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $compid = $this->request->session()->read('company_id');
        
        $tid = $this->Cookie->read('tid'); 
        $employee_table = TableRegistry::get('employee');
        $empclssub_table = TableRegistry::get('employee_class_subjects');
        
        $retrieve_empclses = $employee_table->find()->select(['class.c_name', 'class.c_section',  'class.school_sections', 'class.id'])->join(
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
            ]
        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['class.id'])->toArray();
        
		$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
		
		$gc_table = TableRegistry::get('guide_content');
        foreach($retrieve_knowledge as $know)
        {
            $retrieve_gc = $gc_table->find()->where(['guide_id' => $know['id']])->first() ;
            $know->shared = "";
            if(!empty($retrieve_gc))
            {
                $know->shared = "shared";
            }
        }
		
	
	
		$this->set("empclses_details", $retrieve_empclses); 	
		$this->set("school_id", $compid); 
		$this->set("knowledge_details", $retrieve_knowledge); 
        $this->viewBuilder()->setLayout('userwj');
    }

	public function addmachinecomment()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('machine_learning_comments');
            $activ_table = TableRegistry::get('activity');
            $tchrid = $this->request->session()->read('tchr_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->knowledge_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->parent = 0;
            $comments->added_by = "teacher";
            $comments->teacher_id = $tchrid;
                                  
            if($saved = $comments_table->save($comments) )
            {   
                $clsid = $saved->id;

                $activity = $activ_table->newEntity();
                $activity->action =  "Machine Learning Comment Added"  ;
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
    
	public function machinelearnreplycomments()
    {
        //print_r($_POST); die;
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('machine_learning_comments');
            $activ_table = TableRegistry::get('activity');
            $tchrid = $this->request->session()->read('tchr_id');
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->knowledge_id = $this->request->data('r_kid');
            $comments->created_date = time();
            $comments->parent = $this->request->data('comment_id');
            $comments->added_by = "teacher";
			$comments->teacher_id = $tchrid;
                                  
            if($saved = $comments_table->save($comments) )
            {   
                $clsid = $saved->id;

                $activity = $activ_table->newEntity();
                $activity->action =  "Machine Learning Reply Comment Added"  ;
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

    public function titlefilter()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $knowledge_table = TableRegistry::get('guide_content');
        $subject_table = TableRegistry::get('subjects');
        $emp_table = TableRegistry::get('employee');
        
        $filter = $this->request->data('filter');
        $subjfilter = $this->request->data('subjfilter');
        $title = $this->request->data('title');
        if($subjfilter != "" && $title != "")
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where([ 'subject_id' => $subjfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf',  'subject_id' => $subjfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'subject_id' => $subjfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video',  'subject_id' => $subjfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
        }
        else if($subjfilter == "" && $title != "")
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->where([ 'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',   'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
            }
        }
        else
        {
            if($filter == "newest")
            {
                $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
            }
        }
        $res = '';
        
        foreach($retrieve_know as $content)
        {
            $retrieve_sub = $subject_table->find()->where(['id' => $content['subject_id'] ])->first() ;
            $subject_name = $retrieve_sub['subject_name'];
            
            $retrieve_emp = $emp_table->find()->where(['id' => $content['teacher_id'] ])->first() ;
            $teacher_name = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
            
            if(!empty($content['image'])) 
            { 
                $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
            } else { 
                $img = '<img src ="http://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                
            $cours = '<b>Cours: </b>'. ucfirst($subject_name)."<br>";
            $tchr = '<b>Teacher Name: </b>'. ucfirst($teacher_name);
            
            
            
            $shreicon = '';
            if($content['shared'] == "shared") { 
                $shreicon = '<ul id="right_icon"><li><i class="fa fa-share"></i></li></ul>';
            }
        
            $res .= '<div class="col-sm-2 col_img">
                <a href="'. $this->base_url.'dropbox/viewdropbox/'. md5($content['id']) .'" class="viewknow" >'.
                $shreicon.$img.'
                <div class="set_icon">'.$icon.'</div>
                <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        '.$cours.$tchr.'</p>
				</a>
            </div>';
            
        }
        
        return $this->json($res);
    }
            
}

  


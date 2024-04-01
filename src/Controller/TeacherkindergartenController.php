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
use \Imagick;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TeacherkindergartenController  extends AppController
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
        $sclid = $this->request->session()->read('company_id');
        $tchrid = $this->request->session()->read('tchr_id'); 
        $kinderdash_table = TableRegistry::get('kinderdash');
		$student_table = TableRegistry::get('student');
		
		$retrieve_kinderdash = $kinderdash_table->find()->where([ 'school_id'=> $sclid ])->order(['id' => 'ASC'])->toArray();
	
		$this->set("kinderdash_details", $retrieve_kinderdash); 
		$this->viewBuilder()->setLayout('user');
    }
    public function activity($id)
    {   
		$kinderlib_table = TableRegistry::get('kindergarten_library');
		$kinderdash_table = TableRegistry::get('kinderdash');
        $compid = $this->request->session()->read('company_id');
        $tid = $this->Cookie->read('tid'); 
        $employee_table = TableRegistry::get('employee');
        $empclssub_table = TableRegistry::get('employee_class_subjects');
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
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
            

        ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->group(['employee_class_subjects.class_id'])->toArray();
        $clsids = [];
        foreach($retrieve_empclses as $empclses)
        {
            $clsids[] = $empclses['class']['id'];
        }
        $retrieve_kinderlib = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $id, 'class_id IN' => $clsids  ])->toArray() ;
        
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
        $this->set("empcls_details", $retrieve_empclses); 
        $this->set("dashid", $dashid); 
        $this->viewBuilder()->setLayout('user');
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
        $this->viewBuilder()->setLayout('user');
    }
    public function addcomment()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $comments_table = TableRegistry::get('kindergarten_library_comments');
            $activ_table = TableRegistry::get('activity');
            $tchrid = $this->request->session()->read('tchr_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->kinderlib_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->parent = 0;
            $comments->teacher_id = $tchrid;
                                  
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
            $tchrid = $this->request->session()->read('tchr_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->kinderlib_id = $this->request->data('r_kid');
            $comments->created_date = time();
            $comments->parent = $this->request->data('comment_id');
            $comments->teacher_id = $tchrid;
                                  
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $cls_id = $this->request->data('clsid');
        $sub_id = $this->request->data('subid');
        $compid = $this->request->session()->read('company_id');
        if($cls_id != "" && $sub_id != "")
        {
            if($sub_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
        elseif($cls_id != "")
        {
            if($cls_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                } 
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
        else
        {
            if($filter == "newest")
            {
                $retrieve_content = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "pdf")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "audio")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
            elseif($filter == "video")
            {
                $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
            }
        }
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $res = '';
        foreach($retrieve_content as $content)
        {
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
            
            
        
            $retrieve_class = $cls_table->find()->where(['id' => $content['class_id']  ])->first() ;
            $retrieve_subj = $sub_table->find()->where(['id' => $content['subject_id']  ])->first() ;
            $subjectname = $retrieve_subj['subject_name'];
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            
            $res .= '<div class="col-sm-3 col_img">
                    <div class="set_icon">'.$icon.'</div>
                    <ul id="right_icon">
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'] .'" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="../delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'.$this->base_url.'Teacherkindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                    </ul>'.
                $img.'
                <p class="title" style="color:#000"><b>Title:</b>'. $content['title'].'</p>
                <p class="title" style="color:#000"><b>Class:</b>'. $classname.'</p>
                <p class="title" style="color:#000"><b>Subjects:</b>'. $subjectname.'</p>
            </div>';
            
        }
        
        return $this->json($res);
    }
    
    public function virtualclass()
    {   
		$compid = $this->request->session()->read('company_id');
		$tchrid = $this->request->session()->read('tchr_id'); 
        $virtualcls_table = TableRegistry::get('kinder_virtualclass');
        $class_table = TableRegistry::get('class');
        $emp_table = TableRegistry::get('employee_class_subjects');
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $retrieve_cls = $emp_table->find()->select(['class.c_name', 'class.id', 'class.c_section', 'class.school_sections'])->join(['class' => 
            [
                'table' => 'class',
                'type' => 'LEFT',
                'conditions' => 'class.id = employee_class_subjects.class_id'
            ]
        ])->where(['employee_class_subjects.emp_id' => $tchrid ])->order(['class.school_sections' => 'ASC'])->group(['employee_class_subjects.class_id'])->toArray();
                     
        
        $retrieve_links = $virtualcls_table->find()->select(['id', 'class_id', 'status', 'created_date', 'meeting_status', 'meeting_id', 'schedule_date', 'schedule_datetime', 'expirelink_datetime', 'meeting_link', 'teacher_id', 'class.c_name', 'class.id', 'class.c_section', 'class.school_sections', 'meeting_name'])->join(['class' => 
            [
                'table' => 'class',
                'type' => 'LEFT',
                'conditions' => 'class.id = kinder_virtualclass.class_id'
            ]
        ])->where(['kinder_virtualclass.school_id' => $compid, 'kinder_virtualclass.teacher_id' => $tchrid ])->order(['kinder_virtualclass.id' => 'desc'])->toArray();
        
        
        $this->set("link_details", $retrieve_links); 
        $this->set("class_details", $retrieve_cls); 
        
		$this->viewBuilder()->setLayout('user');
    }
    public function generatemeeting()
    {  
        if ( $this->request->is('post') && $this->request->is('ajax') )
        {
            $schoolid = $this->request->session()->read('company_id');
            $tchrid = $this->request->session()->read('tchr_id'); 
            $virtualcls_table = TableRegistry::get('kinder_virtualclass');
            $session_id = $this->Cookie->read('sessionid');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $meeting = $virtualcls_table->newEntity();
            $meeting->class_id = $this->request->data('classid');
            //$meeting->subject_id = $this->request->data('subjectid');
            $meeting->schedule_date = $this->request->data('start_date');
            $meeting->schedule_datetime = strtotime($this->request->data('start_date'));
            $meeting->meeting_name = implode("+", explode(" ",$this->request->data('meeting_name')));
            $meeting->meeting_id = $this->request->data('meeting_id');
            $meeting->status = 0;
            $meeting->school_id = $schoolid;
            $meeting->teacher_id = $tchrid;
            $meeting->session_id = $session_id;
            $meeting->created_date = time();
            $meeting->expirelink_datetime = strtotime($this->request->data('end_date'));
            
            $lang = $this->Cookie->read('language');	
    			if($lang != "") { $lang = $lang; }
                else { $lang = 2; }
            
            
                $language_table = TableRegistry::get('language_translation');
                if($lang == 1)
                {
                    $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                    
                    foreach($retrieve_langlabel as $langlabel)
                    {
                        $langlabel->title = $langlabel['english_label'];
                    }
                }
                else
                {
                    $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                    foreach($retrieve_langlabel as $langlabel)
                    {
                        $langlabel->title = $langlabel['french_label'];
                    }
                }
            
                foreach($retrieve_langlabel as $langlbl) 
                { 
                    if($langlbl['id'] == '1943') { $sdlted = $langlbl['title'] ; } 
                } 
            
                   
            $st = strtotime($this->request->data('start_date'));
            $et = strtotime($this->request->data('end_date'));
            if($st < $et)       
            {
                if($saved = $virtualcls_table->save($meeting) )
                {     
                    $strucid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Virtual Class Generated Successfully!"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('tid');
                    $activity->value = md5($strucid); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;

                    if($save)
                    {   
                        $res = [ 'result' => 'success' ];
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
                $res = [ 'result' => $sdlted  ];
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
    
    public function deletevirtual()
    {
        $rid = $this->request->data('val') ;
        $virtualcls_table = TableRegistry::get('kinder_virtualclass');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());   
        $del = $virtualcls_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
        if($del)
		{
            $activity = $activ_table->newEntity();
            $activity->action =  "Virtual Class successfully Deleted!"  ;
            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
            $activity->value = md5($rid)    ;
            $activity->origin = $this->Cookie->read('id')   ;
            $activity->created = strtotime('now');

            if($saved = $activ_table->save($activity) )
            {
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = [ 'result' => 'failed'  ];
            }
        }
        else
        {
            $res = [ 'result' => 'not delete'  ];
        }    
        return $this->json($res);
    }
    
    public function updatemeetingsts()
    {
        $id = $this->request->data('id');
        $link = $this->request->data('link');
        $virtualcls_table = TableRegistry::get('kinder_virtualclass');
        $emp_table = TableRegistry::get('employee');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_links = $virtualcls_table->find()->where([ 'id' => $id ])->first();
        $tchrid = $retrieve_links['teacher_id'];
        
        
        $BBB_SECRET = 'ILsPVi1WZGMeThb0saOns0441E9HhqxyjjuJzWE2Eky';
        $BBB_URL = 'https://meeting.you-me-globaleducation.org/bigbluebutton/ymge/';
        
        $bbb = new BigBlueButton($BBB_URL,$BBB_SECRET);
        $meetingID = $this->request->data('meetingID');
        $meetingName = $this->request->data('meetingID').'-'.$retrieve_links['meeting_name'];
        $meetingName = str_replace("+"," ", $meetingName);
        $attendee_password = '111';
        $moderator_password = '222';
        $duration = '30';
        $urlLogout = 'https://you-me-globaleducation.org/school/conference/kmeetingleft/'.$meetingID;
        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setLogoutUrl($urlLogout);
        if ($isRecordingTrue) {
        	$createMeetingParams->setRecord(true);
        	$createMeetingParams->setAllowStartStopRecording(true);
        	$createMeetingParams->setAutoStartRecording(true);
        }
        $response = $bbb->createMeeting($createMeetingParams);
        
        if ($response->getReturnCode() == 'FAILED') {
        	$res['data'] = "failed";
        } else{
        
            /*$secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
            
            
            $meeting_id =   $this->request->data('meetingID');
            $meeting_name = $this->request->data('meetingID').'-'.$retrieve_links['meeting_name'];
            $logo = "https://you-me-globaleducation.org/You-Me-live.png";
            $logout = urlencode("https://you-me-globaleducation.org/ConferenceMeet/callback.php?meetingID=".$meeting_id);
            
            
            $string = "createname=".$meeting_name."&meetingID=".$meeting_id."&attendeePW=111&moderatorPW=222&logo=".$logo."&meta_endCallbackUrl=".$logout.$secret;
            $sh = sha1($string);
        
            $url ='https://meeting.you-me-globaleducation.org/bigbluebutton/api/create?name='.$meeting_name.'&meetingID='.$meeting_id.'&attendeePW=111&moderatorPW=222&logo='.$logo.'&meta_endCallbackUrl='.$logout.'&checksum='.$sh;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $result = curl_exec($ch);
            curl_close($ch);*/
            
            
            $retrieve_tchrs = $emp_table->find()->where([ 'id' => $tchrid ])->first();
            
            $fname = implode("+", explode(" ", $retrieve_tchrs['f_name']));
            $lname = implode("+", explode(" ", $retrieve_tchrs['l_name']));
            $sclname = trim($fname)."+".$lname;
            $exresult = explode(" ", $result);
            //echo $exresult[0]; 
            
            $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');
            $sclname = strtr($sclname, $accents);
            
            /*$exresult1 = explode("YOUME", $exresult[0]);
            $ress = explode("<returncode>", $exresult1[0]); 
            
            $createresult = explode("</returncode>", $ress[1]); 
            
            
            if($exresult1[0] == "SUCCESS" || $createresult[0] == "SUCCESS")
            {*/
                //echo "hi";
                
                $update = $virtualcls_table->query()->update()->set(['meeting_status' => 1, 'meeting_id' => $meetingID])->where([ 'id' => $id  ])->execute();
                if($update)
                {
                    $password ='222' ;
                    $name= $sclname;
                    $name = str_replace("+"," ", $name);
                    $this->Cookie->write('meetingid', $meetingID,  time()+1000000000000000 );
                    $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
                    $joinMeetingParams->setRedirect(true);
                    
                    $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                    
                    $res['data'] = $url;
                            
                    /*$this->Cookie->write('meetingid', $meeting_id,  time()+1000000000000000 );
                    $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                    $string4 = "joinmeetingID=".$meeting_id."&password=222&fullName=".$sclname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
                    $sh4 = sha1($string4);
                    $res['data'] = "success";
                    $res['checksumm'] = $sh4;
                    $res['tchrname'] = $sclname;*/
                   
                }
                else
                {
                    $res['data'] = "failed";
                }
            /*}
            else
            {
                $res['data'] = "failed";
            }*/
        }
            return $this->json($res);
    }
    
    public function adddiscovery()
    { 
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            /*print_r($_FILES);
            die;*/
            $kinderlib_table = TableRegistry::get('kindergarten_library');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $video_type = "";
            if($this->request->data('file_type') == "video")
            {
                $link = $this->request->data('file_link');
                $file_youtube = strpos($link, "youtube");
                if($file_youtube != false)
                {
                    $youex = explode("watch?v=",$link);
                    $file_link  = $youex[0]."embed/".$youex[1];
                    $video_type = "youtube";
                }
                
                $file_vimeo =  strpos($link, "vimeo");
                if($file_vimeo != false)
                {
                    $file_link = $link;
                    $video_type = "vimeo";
                }
                if($this->request->data('videotypes') == "d.tube")
            	{
            		$file_link = $this->request->data('dtube_video');
            		$video_type = "d.tube";
            	}
                
                $filess = $file_link;
            }
            elseif($this->request->data('file_type') == "audio")
            {  
                if(!empty($this->request->data['file_upload']['name']))
                {   
                    $fille = explode(".", $this->request->data['file_upload']['name']);
                    //if($this->request->data['file_upload']['type'] == "audio/mpeg" || $fille[1] == "mp3")
                    if($this->request->data['file_upload']['type'] == "audio/mpeg")
                    {
                        $filess =  time().$this->request->data['file_upload']['name'];
                        $filename = $filess;
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$filename; 
                        if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                        {
                            $filess = $filename; 
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
                        $filess =  time().$this->request->data['file_upload']['name'];
                        $filename = $filess;
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$filename;
                        if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                        {
                            $filess = $filename; 
                        }
                    }    
                }
                else
                {
                    $filess = "";
                }
                
                $im = new Imagick();
                $im->pingImage('img/'.$filename);
                $nopages = $im->getNumberImages();
                $dirname = "Ebook".time();
            }
           
            if(!empty($_POST['slim'][0]))
            {
                foreach($_POST['slim'] as $slim)
                {
                    $abc = json_decode($slim);
                     
                    $cropped_image = $abc->output->image;
                    list($type, $cropped_image) = explode(';', $cropped_image);
                    list(, $cropped_image) = explode(',', $cropped_image);
                    $cropped_image = base64_decode($cropped_image);
                    $coverimg = date('ymdgis').'.png';
                    
                    $uploadpath = 'img/';
                    $uploadfile = $uploadpath.$coverimg; 
                    file_put_contents($uploadfile, $cropped_image);
                }
            }
            else
            {
                $coverimg = "";
            }
            
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
        
        
            $language_table = TableRegistry::get('language_translation');
            if($lang == 1)
            {
                $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                
                foreach($retrieve_langlabel as $langlabel)
                {
                    $langlabel->title = $langlabel['english_label'];
                }
            }
            else
            {
                $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                foreach($retrieve_langlabel as $langlabel)
                {
                    $langlabel->title = $langlabel['french_label'];
                }
            }
        
            foreach($retrieve_langlabel as $langlbl) 
            { 
                if($langlbl['id'] == '1952') { $duinpdfaudvid = $langlbl['title'] ; } 
            } 
            
            if($nopages > 21)
                {
                    $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                }
                else {
            if(!empty($filess))
            {
                $kinderlib = $kinderlib_table->newEntity();
                $kinderlib->file_type = $this->request->data('file_type');
                $kinderlib->title = $this->request->data('title');
	            $kinderlib->created_date = time();
                $kinderlib->description = $this->request->data('desc');
                $kinderlib->links = $filess;
				$kinderlib->status = 1;
                $kinderlib->school_id = $compid;
                $kinderlib->video_type = $video_type;
                $kinderlib->image = $coverimg;
                $kinderlib->kinderdash_id = $this->request->data('activityid');
                $kinderlib->numpages = $nopages;
                $kinderlib->dirname = $dirname;
                
                $kinderlib->class_id = $this->request->data('class');
                $kinderlib->subject_id = $this->request->data('subjects');
                
                //print_r($kinderlib); die;
                
                                  
                if($saved = $kinderlib_table->save($kinderlib) )
                {   
                    $kid = $saved->id;
                    
                    if($this->request->data('file_type') == "pdf")
                    {
                        mkdir($dirname);
                        $numpages = $nopages-1;

                        for ($x = 0; $x <= $numpages; $x++) {
                            $save_to    = $dirname."/".$filename.$x.'.jpg';  
                            $im = new Imagick();
                            $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                            $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                            $im->setImageFormat('jpg');
                            $im = $im->flattenImages();
                            $im ->writeImages($save_to, true);
                            header('Content-Type: image/jpeg');
                            sleep(2);
                        }
                    }

                    $activity = $activ_table->newEntity();
                    $activity->action =  "kinder activity content Added"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('id');
                    $activity->value = md5($kid); 
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
                $res = [ 'result' => $duinpdfaudvid  ];
            } }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
    }     
    
    public function editdiscover()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $id = $this->request->data('id');
        $kinderlib_table = TableRegistry::get('kindergarten_library');
		$retrieve_kinderlib = $kinderlib_table->find()->where(['id' => $id])->toArray();
		return $this->json($retrieve_kinderlib);
    }
    
    public function editdiscovery()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $kinderlib_table = TableRegistry::get('kindergarten_library');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $id = $this->request->data('ekid');
            $file_type = $this->request->data('efile_type');
            $file_title = $this->request->data('etitle');
            $file_description = $this->request->data('edesc');
            
            
            
            if(!empty($_POST['slim'][0] ))
            {
                foreach($_POST['slim'] as $slim)
                {
                    $abc = json_decode($slim);
                     
                    $cropped_image = $abc->output->image;
                    list($type, $cropped_image) = explode(';', $cropped_image);
                    list(, $cropped_image) = explode(',', $cropped_image);
                    $cropped_image = base64_decode($cropped_image);
                    $coverimg = date('ymdgis').'.png';
                    
                    $uploadpath = 'img/';
                    $uploadfile = $uploadpath.$coverimg; 
                    file_put_contents($uploadfile, $cropped_image);
                }
            }
            else
            {
                $coverimg = $this->request->data('ecoverimage');
            }
            
            $nopages = 0;
            $dirname = '';
            
            if($this->request->data('efile_type') == "video")
            {
                $link = $this->request->data('efile_link');
                $file_youtube = strpos($link, "youtube");
                if($file_youtube != false)
                {
                    $youex = explode("watch?v=",$link);
                    if(!empty($youex[1]))
                    {
                        $file_link  = $youex[0]."embed/".$youex[1];
                    }
                    else
                    {
                        $file_link = $link;
                    }
                    $video_type = "youtube";
                }
                
                $file_vimeo =  strpos($link, "vimeo");
                if($file_vimeo != false)
                {
                    $file_link = $link;
                    $video_type = "vimeo";
                }
                if($this->request->data('evideotypes') == "d.tube")
        	    {
            		$file_link = $this->request->data('edtube_video');
            		$video_type = "d.tube";
            		$filess = $file_link;
            	}    
                $filess = $file_link;
                
            }
            elseif($this->request->data('efile_type') == "audio")
            {  
                if(!empty($this->request->data['efile_upload']['name']))
                {   
                    if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                    {
                        $filess =  time().$this->request->data['efile_upload']['name'];
                        $filename = $filess;
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
                        $filess =  time().$this->request->data['efile_upload']['name'];
                        $filename = $filess;
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
                    $filename = '';
                    $filess = $this->request->data['efileupload'];
                }
                if($filename != "") {
                    $im = new Imagick();
                    $im->pingImage('img/'.$filename);
                    $nopages = $im->getNumberImages();
                    $dirname = "Ebook".time();
                }
                else
                {
                    $retrieve_kinderlib = $kinderlib_table->find()->where(['id' => $id])->first() ;
                    $nopages = $retrieve_kinderlib['numpages'];
                    $dirname = $retrieve_kinderlib['dirname'];
                }
            }
            
            $lang = $this->Cookie->read('language');	
			if($lang != "") { $lang = $lang; }
            else { $lang = 2; }
        
        
            $language_table = TableRegistry::get('language_translation');
            if($lang == 1)
            {
                $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                
                foreach($retrieve_langlabel as $langlabel)
                {
                    $langlabel->title = $langlabel['english_label'];
                }
            }
            else
            {
                
                $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                foreach($retrieve_langlabel as $langlabel)
                {
                    $langlabel->title = $langlabel['french_label'];
                }
            }
        
            foreach($retrieve_langlabel as $langlbl) 
            { 
                if($langlbl['id'] == '1952') { $duinpdfaudvid = $langlbl['title'] ; } 
            } 
            $clsid = $this->request->data('eclass');
            $subid = $this->request->data('esubjects');
            
            if($nopages > 21)
                {
                    $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                }
                else {
            if(!empty($filess))
            {
				$status = 1;
                                      
                if($kinderlib_table->query()->update()->set(['numpages' => $nopages, 'dirname' => $dirname, 'class_id' => $clsid, 'video_type' => $video_type, 'subject_id' => $subid , 'file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                {   
                    $knowid = $id;
                    
                    if($this->request->data('efile_type') == "pdf")
                    {
                        mkdir($dirname);
                        $numpages = $nopages-1;

                        for ($x = 0; $x <= $numpages; $x++) {
                            $save_to    = $dirname."/".$filename.$x.'.jpg';  
                            $im = new Imagick();
                            $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                            $im->readImage('img/'.$filename.'['.$x.']');    //[0] for the first page
                            $im->setImageFormat('jpg');
                            $im = $im->flattenImages();
                            $im ->writeImages($save_to, true);
                            header('Content-Type: image/jpeg');
                            sleep(2);
                        }
                    }
                            
                    $activity = $activ_table->newEntity();
                    $activity->action =  "kinder discovery content Updated"  ;
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
                $res = [ 'result' => $duinpdfaudvid  ];
            } }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
    }
    public function delete()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $kbid = $this->request->data('val') ;
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        
        $kid = $kinderlib_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
        if($kid)
        {   
            
			$del = $kinderlib_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
            if($del)
            {
				$del_knowledge = $kinderlib_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
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
    }
    
    public function filteractivitiessub()
    {
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $filter = $this->request->data('filter');
        $dashid = $this->request->data('dashid');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $cls_id = $this->request->data('clsid');
        $sub_id = $this->request->data('subid');
        $compid = $this->request->session()->read('company_id');
        if($cls_id != "" && $sub_id != "")
        {
            if($sub_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
        elseif($cls_id != "")
        {
            if($cls_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                } 
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
         
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $res = '';
        foreach($retrieve_content as $content)
        {
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
            
            
        
            $retrieve_class = $cls_table->find()->where(['id' => $content['class_id']  ])->first() ;
            $retrieve_subj = $sub_table->find()->where(['id' => $content['subject_id']  ])->first() ;
            $subjectname = $retrieve_subj['subject_name'];
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            
            $res .= '<div class="col-sm-3 col_img">
                    <div class="set_icon">'.$icon.'</div>
                    <ul id="right_icon">
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'] .'" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="../delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'.$this->base_url.'Teacherkindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                    </ul>'.
                $img.'
                <p class="title" style="color:#000"><b>Title:</b>'. $content['title'].'</p>
                <p class="title" style="color:#000"><b>Class:</b>'. $classname.'</p>
                <p class="title" style="color:#000"><b>Subjects:</b>'. $subjectname.'</p>
            </div>';
            
        }
        
        return $this->json($res);
    }
    
    public function getsubjecttchr()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $clsid = $this->request->data('clsid');
        $subid = $this->request->data('subid');
        $subject_table = TableRegistry::get('subjects');
        $empcls_table = TableRegistry::get('employee_class_subjects');
        $teacherid = $this->Cookie->read('tid');
        $data = "";
        $data .= '<option value="">Choose Subjects</option>';
        if($clsid != "all")
        {
            $retrieve_sub = $empcls_table->find()->select(['subjects.subject_name', 'subjects.id'])->join(
                [
    	            'subjects' => 
                    [
                        'table' => 'subjects',
                        'type' => 'LEFT',
                        'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                    ]
    
                ])->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id' => $clsid ])->toArray();
                
            
            $data .= '<option value="all">All</option>';
            if($subid == "")
            {
                foreach($retrieve_sub as $subj)
                {
                    $data .= '<option value="'.$subj['subjects']['id'].'">'.$subj['subjects']['subject_name'].'</option>';
                }
            }
            else
            {
                foreach($retrieve_sub as $subj)
                {
                    $sel ='';
                    if($subj['subjects']['id'] == $subid)
                    {
                        $sel = "selected";
                    }
                    $data .= '<option value="'.$subj['subjects']['id'].'" '.$sel.'>'.$subj['subjects']['subject_name'].'</option>';
                }
            }
        }
            
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $filter = $this->request->data('filter');
        $dashid = $this->request->data('dashid');
        
        $cls_id = $this->request->data('clsid');
        $compid = $this->request->session()->read('company_id');
        if($cls_id != "")
        {
            if($cls_id == "all")
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                } 
            }
            else
            {
                if($filter == "newest")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $kinderlib_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid, 'kinderdash_id' => $dashid])->order(['id' => 'desc'])->toArray() ;
                }
            }
        }
         
        $cls_table = TableRegistry::get('class');
        $sub_table = TableRegistry::get('subjects');
        $res = '';
        foreach($retrieve_content as $content)
        {
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
            
            
        
            $retrieve_class = $cls_table->find()->where(['id' => $content['class_id']  ])->first() ;
            $retrieve_subj = $sub_table->find()->where(['id' => $content['subject_id']  ])->first() ;
            $subjectname = $retrieve_subj['subject_name'];
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            
            $res .= '<div class="col-sm-3 col_img">
                    <div class="set_icon">'.$icon.'</div>
                    <ul id="right_icon">
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'] .'" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="../delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'.$this->base_url.'Teacherkindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                    </ul>'.
                $img.'
                <p class="title" style="color:#000"><b>Title:</b>'. $content['title'].'</p>
                <p class="title" style="color:#000"><b>Class:</b>'. $classname.'</p>
                <p class="title" style="color:#000"><b>Subjects:</b>'. $subjectname.'</p>
            </div>';
            
        }
         
        $subjects['viewdata'] = $res;  
        $subjects['subjectname'] = $data;
        return $this->json($subjects);
		
    }
}

  


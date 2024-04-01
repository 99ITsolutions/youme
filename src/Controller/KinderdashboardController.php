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

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;

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
		
		$codeagree_table = TableRegistry::get('code_agreement');
        $codeconduct_table = TableRegistry::get('code_conduct');
		$studid = $this->request->session()->read('student_id');
		$sclid = $this->request->session()->read('company_id');
		$codeconduct = $codeconduct_table->find()->where(['school_id' => $sclid])->first();
		if(!empty($codeconduct)) {
		$codeagree = $codeagree_table->find()->where([ 'student_id'=> $studid, 'school_id' => $sclid, 'code_id' => $codeconduct['id'] ])->first();
		//print_r($codeconduct); die;
		if(empty($codeagree))
		{
		    $ca = $codeagree_table->newEntity();
            $ca->student_id =  $studid;
            $ca->school_id =  $sclid;
            $ca->code_id = $codeconduct['id']   ;
            $ca->status = 0 ;
            $ca->read_date = time();
            $saved = $codeagree_table->save($ca);
		}
		
		$codeagree = $codeagree_table->find()->where([ 'student_id'=> $studid, 'school_id' => $sclid, 'code_id' => $codeconduct['id'] ])->first();
		}
		else
		{
		    $codeagree = [];
		}
		$this->set("codeconduct", $codeconduct);
		$this->set("codeagree", $codeagree);
		
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
        
        $BBB_SECRET = 'ILsPVi1WZGMeThb0saOns0441E9HhqxyjjuJzWE2Eky';
        $BBB_URL = 'https://meeting.you-me-globaleducation.org/bigbluebutton/ymge/';
        $bbb = new BigBlueButton($BBB_URL,$BBB_SECRET);
        $meetingID = $getdtaa['meeting_id'];
        
        $isMeetingRunningParams = new IsMeetingRunningParameters($meetingID);
        $result = $url = $bbb->isMeetingRunning($isMeetingRunningParams); 
        if($result->isRunning()){
        
            $fname = implode("+", explode(" ", $getstud['f_name']));
            $lname = implode("+", explode(" ", $getstud['l_name']));
            $studname = trim($fname).trim($lname);
            
            $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');
            $studname = strtr($studname, $accents);
            
            $password ='111' ;
            $name= $studname;
            $name = str_replace("+"," ", $name);
            $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
            $joinMeetingParams->setRedirect(true);
            $this->Cookie->write('meetingid', $meetingID,  time()+1000000000000000 );
    
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
        
            $meetingsts['data'] = $getdtaa['meeting_status'];
        }else{
            $meetingsts['data'] = 0;
        }
           
            
        
            $meetingsts['data'] = $getdtaa['meeting_status'];
            $meetingsts['studname'] = $studname;
            $meetingsts['meetingID'] = $getdtaa['meeting_id'];
            $meetingsts['url'] = $url;
            
            /*$secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
            $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
            $string4 = "joinmeetingID=". $getdtaa['meeting_id']."&password=111&fullName=".$studname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
            $sh4 = sha1($string4);
            $meetingsts['checksumm'] = $sh4;
            
            $this->Cookie->write('meetingid', $getdtaa['meeting_id'],  time()+1000000000000000 );*/
        
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
    
    public function kinderdropbox()
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
        $this->viewBuilder()->setLayout('kinder');
    }
    
    public function viewdropbox($id)
    {  
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $knowledge_table = TableRegistry::get('guide_content');
        $student_table = TableRegistry::get('student');
        $employee_table = TableRegistry::get('employee');
        $company_table = TableRegistry::get('company');
        $compid = $this->request->session()->read('company_id');
        
        
        
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
		
	
		
		$this->set("school_id", $compid); 
		$this->set("knowledge_details", $retrieve_knowledge); 
        $this->viewBuilder()->setLayout('kinderwj');
    }
    
    public function updateagreement()
    {
       // print_r($_POST); die;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $id = $this->request->data('id'); 
        $sts = $this->request->data('agreement'); 
        $stid = $this->Cookie->read('stid'); 
        $codeagree_table = TableRegistry::get('code_agreement');
        $activ_table = TableRegistry::get('activity');
        if(!empty($stid))
        {
            
            $update = $codeagree_table->query()->update()->set([ 'status'=> $sts , 'read_date' => time() ])->where([ 'id' => $id  ])->execute();
            if($update)
            {     
                $res = [ 'result' => "success"  ];
            }
            else
            {
                $res = [ 'result' => 'error occured.'  ];
            }
        }
        else
        {
             return $this->redirect('/login/') ;
        }
        return $this->json($res);
    }

}

  


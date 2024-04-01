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
use \Imagick;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class KnowledgeController   extends AppController
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
                $retrieve_videos = $knowledge_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $knowledge_table->find()->where(['school_id' => $compid])->group('file_title')->order(['id' => 'desc'])->toArray() ;
                $this->set("content_details", $retrieve_videos); 
                $this->set("title_details", $retrieve_title); 
                $this->viewBuilder()->setLayout('user');
            }

            public function add()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }
            
            public function addknowledge()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;
                    if(!empty($_POST['slim'][0]))
                    {
                        
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                            if($abc->input->size <= $max_allfilezise){
                                $cropped_image = $abc->output->image;
                                list($type, $cropped_image) = explode(';', $cropped_image);
                                list(, $cropped_image) = explode(',', $cropped_image);
                                $cropped_image = base64_decode($cropped_image);
                                $image_name = date('ymdgis').'.png';
                                
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$image_name; 
                                file_put_contents($uploadfile, $cropped_image);
                            }else{
                                $res = [ 'result' => 'Cover Image size must be 2MB OR less than 2MB'  ];
                                 return $this->json($res);
                            }
                        }
                    }
                    else
                    {
                        $image_name = "";
                    }
                    $knowledge_table = TableRegistry::get('knowledge_base');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $video_type = "";
                    $nopages = 0;
                    $dirname = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        if(!empty($link))
                        {
                            $file_youtube = strpos($link, "youtube");
                            if($file_youtube != false)
                            {
                                $embed = strpos($link, "embed");
                                if($embed != false)
                                {
                                    $file_link = $link;
                                }
                                else
                                {
                                    $youex = explode("watch?v=",$link);
                                    $file_link  = $youex[0]."embed/".$youex[1];
                                }
                                $video_type = "youtube";
                            }
                        
                            $file_vimeo =  strpos($link, "vimeo");
                            if($file_vimeo != false)
                            {
                                $file_link = $link;
                                $video_type = "vimeo";
                            }
                            
                        }
                        elseif($this->request->data('videotypes') == "d.tube")
                        {
                            
                            $file_link = $this->request->data('dtube_video');
                            $video_type = "d.tube";
                            $filess = $file_link;
                        }
                            
                        else
                        {
                            $filess = "";
                        }
                    }
                    elseif($this->request->data('file_type') == "audio")
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['size'] <= $max_audiofilezise){ 
                                if($this->request->data['file_upload']['type'] == "audio/mpeg" )
                                {
                                    $filess =  time().$this->request->data['file_upload']['name'];
                                    $filename = $filess;
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename; 
                                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                }  
                            }else{
                                 $res = [ 'result' => 'Please upload file less than 5MB'  ];
                                 return $this->json($res);
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
                        {   if($this->request->data['file_upload']['size'] <= $max_pdffilezise){
                                if($this->request->data['file_upload']['type'] == "application/pdf" )
                                {
                                    $filess =  time().$this->request->data['file_upload']['name'];
                                    $filename = $filess;
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                } 
                            }else{
                                 $res = [ 'result' => 'Please upload 5MB OR less than 5MB file only.'  ];
                                 return $this->json($res);
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
                    if($nopages > 200000000)
                    {
                        $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    }
                    else {
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->file_title = $this->request->data('title');
                        $knowledge->created_date = time();
                        $knowledge->file_description = $this->request->data('desc');
                        $knowledge->file_link_name = $filess;
                        $knowledge->active = 0;
                        $knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $image_name;
                        $knowledge->numpages = $nopages;
                        $knowledge->dirname = $dirname;
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
                            
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
                            $activity->action =  "Knowledge Base Added"  ;
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format and In video, file link is mandatory. Size of file not more than 5MB '  ];
                    }
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }

            public function edit()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $knowledge_table = TableRegistry::get('knowledge_base');
                $retrieve_knowledge = $knowledge_table->find()->where(['id' => $id])->toArray();
                return $this->json($retrieve_knowledge);
                //$this->viewBuilder()->setLayout('user');
            }
            
            public function editknowledge()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;

                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                            if($abc->input->size <= $max_allfilezise){
                                $cropped_image = $abc->output->image;
                                list($type, $cropped_image) = explode(';', $cropped_image);
                                list(, $cropped_image) = explode(',', $cropped_image);
                                $cropped_image = base64_decode($cropped_image);
                                $image_name = date('ymdgis').'.png';
                                
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$image_name; 
                                file_put_contents($uploadfile, $cropped_image);
                            }else{
                                $res = [ 'result' => 'Cover Image size must be 2MB OR less than 2MB'  ];
                                 return $this->json($res);
                            }
                        }
                    }
                    else
                    {
                        $image_name = $_POST['cover_image'];
                    }
                    
                    $nopages = 0;
                    $dirname = '';
                    $knowledge_table = TableRegistry::get('knowledge_base');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        if(!empty($link))
                        {
                            $file_youtube = strpos($link, "youtube");
                            if($file_youtube != false)
                            {
                                $embed = strpos($link, "embed");
                                if($embed != false)
                                {
                                    $file_link = $link;
                                }
                                else
                                {
                                    $youex = explode("watch?v=",$link);
                                    $file_link  = $youex[0]."embed/".$youex[1];
                                }
                                $video_type = "youtube";
                            }
                            
                            $file_vimeo =  strpos($link, "vimeo");
                            if($file_vimeo != false)
                            {
                                $file_link = $link;
                                $video_type = "vimeo";
                            }
                            $filess = $file_link;
                        }
                        elseif($this->request->data('evideotypes') == "d.tube")
                            {
                                $file_link = $this->request->data('edtube_video');
                                $video_type = "d.tube";
                                $filess = $file_link;
                            }    
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    elseif($this->request->data('efile_type') == "audio")
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['size'] <= $max_audiofilezise){ 
                                if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                                {
                                    $filess =  time().$this->request->data['efile_upload']['name'];
                                    $filename = $filess;
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename; 
                                    if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                }else{
                                     $res = [ 'result' => 'Upload a valid file'  ];
                                     return $this->json($res);
                                } 
                            }else{
                                 $res = [ 'result' => 'Please upload file less than 5MB'  ];
                                 return $this->json($res);
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
                            if($this->request->data['efile_upload']['size'] <= $max_pdffilezise){

                                if($this->request->data['efile_upload']['type'] == "application/pdf" )
                                {
                                    $filess = time().$this->request->data['efile_upload']['name'];
                                    $filename = $filess;
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                    {
                                        $filename = $filename; 
                                    }
                                }else{
                                     $res = [ 'result' => 'Upload a valid file'  ];
                                     return $this->json($res);
                                }  
                            }else{
                                 $res = [ 'result' => 'Please upload 5MB OR less than 5MB file only.'  ];
                                 return $this->json($res);
                            }     
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        $nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                        
                    }
                    if($nopages > 200000000)
                    {
                        $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    }
                    else {
                    
                    if(!empty($filess))
                    {
                        $status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['numpages' => $nopages, 'dirname' => $dirname,'image' => $image_name, 'video_type' => $video_type, 'file_type' => $file_type , 'file_description' => $file_description, 'file_link_name' => $filess, 'file_title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
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
                            $activity->action =  "Knowledge Base Updated"  ;
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
                        $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format and In video file link is mandatory. Size of file not more than 5MB '  ];
                    }
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
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
                $retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
                
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
                
                
                $this->set("knowledge_details", $retrieve_knowledge); 
                $this->set("comments_details", $retrieve_comments); 
                $this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function listing()
            {  
                $id = $this->request->data('id') ;
                $knowcomm_table = TableRegistry::get('knowledge_comments');
                $retrieve_comments = $knowcomm_table->find()->where(['parent' => $id])->toArray();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                //print_r($retrieve_comments); die;
                return $this->json($retrieve_comments);
            }



            public function delete()
            {
                
                $kbid = $this->request->data('val') ;
                $knowledge_table = TableRegistry::get('knowledge_base');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $knowledge_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
                    $del = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
                        $del_knowledge = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
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
            
            public function replycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('knowledge_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
                    $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->school_id = $this->request->data('skul_id');
                                          
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
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('knowledge_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
                    $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->school_id = $this->request->data('schoolid');
                                          
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
            
            public function viewknowcontent()
            {
                $libcontent_table = TableRegistry::get('knowledge_base');
                $filter = $this->request->data('filter');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
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
                    
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'viewKnowledge/view/'. md5($content['id']) .'" title="view" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->file_title) .'</p>
                    </div>';
                    
                    
                }
                
                return $this->json($res);
            }
            
           
            
            public function knowcontent()
            {
                $knowledge_table = TableRegistry::get('knowledge_base');
                $filter = $this->request->data('filter');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $title = $this->request->data('title');
                
                //print_r($_POST);
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                //print_r($retrieve_know);
                $res = '';
                foreach($retrieve_know as $content)
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
                    
                    $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li><a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editknowledge" id="editknow" class=" editknow"><i class="fa fa-edit"></i></a></li>
                                <li><button type="button" data-id="'.$content['id'].'" data-url="knowledge/delete" class="js-sweetalert " title="Delete" data-str="Knowledge" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                <li><a href="'. $this->base_url.'knowledge/view/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->file_title) .'</p>
                    </div>';
                    
                    
                }
                
                return $this->json($res);
            }

            public function titlefilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tbl = $this->request->data('tbl');
                if($tbl == "knowledge")
                {
                    $knowledge_table = TableRegistry::get('knowledge_base');
                }
                if($tbl == "leadership")
                {
                    $knowledge_table = TableRegistry::get('leadership');
                }
                if($tbl == "mentorship")
                {
                    $knowledge_table = TableRegistry::get('mentorship');
                }
                if($tbl == "internship")
                {
                    $knowledge_table = TableRegistry::get('internship');
                }
                if($tbl == "newtechnologies")
                {
                    $knowledge_table = TableRegistry::get('newtechnologies');
                }
                
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'file_title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    //print_r($content);
                    if($tbl == "knowledge")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li><a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editknowledge" id="editknow" class=" editknow"><i class="fa fa-edit"></i></a></li>
                                <li><button type="button" data-id="'.$content['id'].'" data-url="knowledge/delete" class="js-sweetalert " title="Delete" data-str="Knowledge" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                <li><a href="'. $this->base_url.'knowledge/view/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->file_title) .'</p>
                        </div>';
                    }
                    if($tbl == "leadership")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editleadership" class="editleadership" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deleteleadership" class=" js-sweetalert " title="Delete" data-str="Leadership & Entrepreneurship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                <li> <a href="'. $this->base_url.'knowledgeCenter/viewleadership/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                        </div>';
                    }
                    if($tbl == "mentorship")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editmentorship" class="editmentorship" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletementorship" class=" js-sweetalert " title="Delete" data-str="Mentorship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                <li> <a href="'. $this->base_url.'knowledgeCenter/viewmentorship/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                        </div>';
                    }
                    if($tbl == "internship")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editinternship" class="editinternship" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deleteinternship" class=" js-sweetalert " title="Delete" data-str="Internship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                <li> <a href="'. $this->base_url.'knowledgeCenter/viewinternship/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                        </div>';
                    }
                    if($tbl == "newtechnologies")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <ul id="right_icon">
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editnewtechnologies" class="editnewtechnologies" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletenewtechnologies" class=" js-sweetalert " title="Delete" data-str="New Technologies" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                <li> <a href="'. $this->base_url.'knowledgeCenter/viewnewtechnologies/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                            </ul>'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
                        </div>';
                    }
                }
                
                return $this->json($res);
            }



            
}

  


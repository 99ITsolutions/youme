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
class KindergartenController  extends AppController
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
        $kinderdash_table = TableRegistry::get('kinderdash');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_kinderdashval = $kinderdash_table->find()->where(['school_id' => $compid  ])->order(['id' => 'ASC'])->toArray() ;
            $this->set("kinderdash", $retrieve_kinderdashval); 
    		$this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function activity($id)
    {   
		$kinderlib_table = TableRegistry::get('kindergarten_library');
		$kinderdash_table = TableRegistry::get('kinderdash');
		$class_table = TableRegistry::get('class');
        $compid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_kinderlib = $kinderlib_table->find()->where(['school_id' => $compid, 'kinderdash_id' => $id  ])->toArray() ;
            
            $cls_table = TableRegistry::get('class');
            $sub_table = TableRegistry::get('subjects');
            foreach($retrieve_kinderlib as $kinderlib)
            {
                $retrieve_class = $cls_table->find()->where(['id' => $kinderlib['class_id']  ])->first() ;
                $retrieve_subj = $sub_table->find()->where(['id' => $kinderlib['subject_id']  ])->first() ;
                $kinderlib->subject = $retrieve_subj['subject_name'];
                $kinderlib->classname = $retrieve_class['c_name']."-".$retrieve_class['c_section']." (". $retrieve_class['school_sections'].")";
            }
            $retrieve_class = $class_table->find()->where(['school_id' => $compid])->toArray() ;
            $retrieve_kinderdash = $kinderdash_table->find()->where(['school_id' => $compid, 'id' => $id  ])->first() ;
            $dashname = $retrieve_kinderdash['dash_name'];
            $dashid = $retrieve_kinderdash['id'];
            $this->set("kinderlib", $retrieve_kinderlib); 
            $this->set("dashname", $dashname); 
            $this->set("classDetails", $retrieve_class); 
            $this->set("dashid", $dashid); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function virtualclass()
    {   
		$compid = $this->request->session()->read('company_id');
        $virtualcls_table = TableRegistry::get('kinder_virtualclass');
        $class_table = TableRegistry::get('class');
        if(!empty($compid))
        {
            $sectns = array("Creche", "Maternelle");
            $retrieve_cls = $class_table->find()->where([ 'active' => 1 , 'school_sections IN' => $sectns, 'school_id' => $compid])->order(['school_sections' => 'ASC'])->toArray();
            
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_links = $virtualcls_table->find()->where(['school_id' => $compid ])->order(['id' => 'desc'])->toArray();
            $this->set("link_details", $retrieve_links); 
            $this->set("class_details", $retrieve_cls); 
    		$this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    public function addimage()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;

            $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        $coverimg = date('ymdgis').'.png';
                        
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$coverimg; 
                        file_put_contents($uploadfile, $cropped_image);
                    }else{
                        $res = [ 'result' => 'Image size must be 2MB OR less than 2MB'  ];
                         return $this->json($res);
                    }
                }
            }
            else
            {
                $coverimg = $this->request->data('activity_img');
            }
            
            $kinderdash_table = TableRegistry::get('kinderdash');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $kid = $this->request->data('activity_id');
            if(!empty($compid))
            {
                $retrieve_kinderdash = $kinderdash_table->find()->select(['id' ])->where(['dash_name' => $this->request->data('activity_name'), 'id IS NOT' => $kid, 'school_id' => $compid  ])->count() ;
                
                if(!empty($coverimg))
                {
                    if($retrieve_kinderdash == 0 )
                    {
                        $dash_name = trim($this->request->data('activity_name'));
                        $image = $coverimg;
                        if($kinderdash_table->query()->update()->set([ 'dash_name' => $dash_name, 'image' => $image])->where([ 'id' => $kid  ])->execute())
                        {   
                            $id = $kid;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Image kinderdash updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($id); 
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
                         $res = [ 'result' => 'Activity already exist.'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'empty'  ];
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
    
    public function adddiscovery()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $kinderlib_table = TableRegistry::get('kindergarten_library');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid))
            {
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
                    
                    $filess = $file_link;
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
                    {   
                        if($this->request->data['file_upload']['size'] <= $max_pdffilezise){
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
                             $res = [ 'result' => 'Please upload 5MB OR less than 5MB file Only'  ];
                             return $this->json($res);
                        }   
                    }
                    else
                    {
                        $filess = "";
                    }
                }
               
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
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }else{
                            $res = [ 'result' => 'Cover Image size must be 2MB OR less than 2MB'  ];
                             return $this->json($res);
                        }
                    }
                }
                else
                {
                    $coverimg = "";
                }
                
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
                    
                    $kinderlib->class_id = $this->request->data('class');
                    $kinderlib->subject_id = $this->request->data('subjects');
                    //print_r($kinderlib); die;
                    
                                      
                    if($saved = $kinderlib_table->save($kinderlib) )
                    {   
                        $kid = $saved->id;
    
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
                    $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format. Size of file not more than 5MB'  ];
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
            $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $kinderlib_table = TableRegistry::get('kindergarten_library');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $id = $this->request->data('ekid');
            $file_type = $this->request->data('efile_type');
            $file_title = $this->request->data('etitle');
            $file_description = $this->request->data('edesc');
            if(!empty($compid))
            {
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
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }else{
                            $res = [ 'result' => 'Cover Image size must be 2MB OR less than 2MB'  ];
                             return $this->json($res);
                        }
                    }
                }
                else
                {
                    $coverimg = $this->request->data('ecoverimage');
                }
                
                
                
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
                    $filess = $file_link;
                    
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
                                $filess =  time().$this->request->data['efile_upload']['name'];
                                $filename = $filess;
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $filename = $filename; 
                                }
                            }  
                        }else{
                             $res = [ 'result' => 'Please upload 5MB OR less than 5MB file Only'  ];
                             return $this->json($res);
                        }   
                    }
                    else
                    {
                        $filess = $this->request->data['efileupload'];
                    }
                    
                }
            
                if(!empty($filess))
                {
    				$status = 0;
                    $class_id = $this->request->data('eclass');
                    $subject_id = $this->request->data('esubjects');
                    if($kinderlib_table->query()->update()->set(['class_id' => $class_id, 'subject_id' => $subject_id, 'file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                    {   
                        $knowid = $id;
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
                    $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format. Size of file not more than 5MB'  ];
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
    
    public function delete()
    {
        $kbid = $this->request->data('val') ;
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
    
    public function filterkinder()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $kinderlib_table = TableRegistry::get('kindergarten_library');
        $filter = $this->request->data('filter');
        $dashid = $this->request->data('dashid');
        $compid = $this->request->session()->read('company_id');
        $cls_id = $this->request->data('clsid');
        $sub_id = $this->request->data('subid');
        if(!empty($compid))
        {
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
            /*if($filter == "newest")
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
       */
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
                    <div class="set_icon">'.$icon.'</div>
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'] .'" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'.$this->base_url.'kindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                    $img.'
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
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('kindergarten_library_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->kinderlib_id = $this->request->data('kid');
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
    
    public function replycomments()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('kindergarten_library_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid))
            {
                $comments = $comments_table->newEntity();
                $comments->comments = $this->request->data('reply_text');
                $comments->kinderlib_id = $this->request->data('r_kid');
                $comments->created_date = time();
                $comments->parent = $this->request->data('comment_id');
                $comments->school_id = $compid;
                                      
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
    
    public function generatemeeting()
    {  
        if ( $this->request->is('post') && $this->request->is('ajax') )
        {
            $schoolid = $this->request->session()->read('company_id');
            $virtualcls_table = TableRegistry::get('kinder_virtualclass');
            $session_id = $this->Cookie->read('sessionid');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($session_id))
            {
                $meeting = $virtualcls_table->newEntity();
                $meeting->class_id = $this->request->data('classid');
                $meeting->subject_id = $this->request->data('subjectid');
                $meeting->schedule_date = $this->request->data('start_date');
                $meeting->schedule_datetime = strtotime($this->request->data('start_date'));
                $meeting->meeting_name = implode("+", explode(" ",$this->request->data('meeting_name')));
                $meeting->meeting_id = $this->request->data('meeting_id');
                $meeting->status = 0;
                $meeting->school_id = $schoolid;
                $meeting->session_id = $session_id;
                $meeting->created_date = time();
                $meeting->expirelink_datetime = strtotime($this->request->data('end_date'));
                       
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
                    $res = [ 'result' => 'Schedule date is less than Expiry link Date'  ];
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
        $school_table = TableRegistry::get('company');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $secret = "aTGBy6CgNh5xqxvUOMDIsPNh671fkcLGnkq8qrfYrA";
        $retrieve_links = $virtualcls_table->find()->where([ 'id' => $id ])->first();
        
        $sclid = $retrieve_links['school_id'];
        
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
        curl_close($ch);
        
        
        $retrieve_school = $school_table->find()->where([ 'id' => $sclid ])->first();
        
        $fname = implode("+", explode(" ", $retrieve_school['comp_name']));
        $sclname = trim($fname);
        $exresult = explode(" ", $result);
        //echo $exresult[0]; 
        
        
        $exresult1 = explode("YOUME", $exresult[0]);
        $ress = explode("<returncode>", $exresult1[0]); 
        
        $createresult = explode("</returncode>", $ress[1]); 
        
        
        if($exresult1[0] == "SUCCESS" || $createresult[0] == "SUCCESS")
        {
            //echo "hi";
            $update = $virtualcls_table->query()->update()->set(['meeting_status' => 1, 'meeting_id' => $meeting_id])->where([ 'id' => $id  ])->execute();
            if($update)
            {
                $this->Cookie->write('meetingid', $meeting_id,  time()+1000000000000000 );
                $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                $string4 = "joinmeetingID=".$meeting_id."&password=222&fullName=".$sclname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
                $sh4 = sha1($string4);
                $res['data'] = "success";
                $res['checksumm'] = $sh4;
                $res['tchrname'] = $sclname;
               
            }
            else
            {
                $res['data'] = "failed";
            }
        }
        else
        {
            $res['data'] = "failed";
        }
        return $this->json($res);
    }
    
    public function addactivity()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $coverimg = "";
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
                        $coverimg = date('ymdgis').'.png';
                        
                        $uploadpath = 'img/';
                        $uploadfile = $uploadpath.$coverimg; 
                        file_put_contents($uploadfile, $cropped_image);
                    }else{
                        $res = [ 'result' => 'Image size must be 2MB OR less than 2MB'  ];
                         return $this->json($res);
                    }
                }
            }
            
            $kinderdash_table = TableRegistry::get('kinderdash');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid))
            {
                $retrieve_kinderdash = $kinderdash_table->find()->select(['id' ])->where(['dash_name' => $this->request->data('activity_name'), 'school_id' => $compid  ])->count() ;
                $retrieve_kinderdashval = $kinderdash_table->find()->select(['id' ])->where(['dash_name' => $this->request->data('activity_name'), 'school_id' => $compid  ])->first() ;
                if(!empty($coverimg))
                {
                    if($retrieve_kinderdash == 0 )
                    {
                        $kinder = $kinderdash_table->newEntity();
                        $kinder->dash_name = trim($this->request->data('activity_name'));
                        $kinder->image = $coverimg;
                        $kinder->school_id = $compid;
                        $kinder->created_date = time();
                        if($saved = $kinderdash_table->save($kinder) )
                        {   
                            $id = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Activity kinderdash Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($id); 
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
                            $res = [ 'result' => 'Already Exist.'  ];
                    }    
                }
                else
                {
                    $res = [ 'result' => 'empty'  ];
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
    
    public function deleteactivity()
    {
        $kbid = $this->request->data('val') ;
        $kinderdash_table = TableRegistry::get('kinderdash');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $kid = $kinderdash_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
        if($kid)
        {   
			$del = $kinderdash_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
            if($del)
            {
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
    
    public function getsubjecttchr()
    {
        $clsid = $this->request->data('clsid');
        $subid = $this->request->data('subid');
        $subject_table = TableRegistry::get('subjects');
        $subcls_table = TableRegistry::get('class_subjects');
        $teacherid = $this->Cookie->read('tid');
        $data = "";
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $data .= '<option value="">Choose Subjects</option>';
        if($clsid != "all")
        {
            $retrieve_subjcts = $subcls_table->find()->where(['class_id' => $clsid ])->first();
            $subjectids = explode(",", $retrieve_subjcts['subject_id']);
            
            $data .= '<option value="all">All</option>';
            foreach($subjectids as $sids)
            {
                if($sids != "")
                {
                    $retrieve_subjcts = $subject_table->find()->where(['id' => $sids ])->first();
                    
                    $data .= '<option value="'.$retrieve_subjcts['id'].'">'.$retrieve_subjcts['subject_name'].'</option>';
                }
            }
           /* $retrieve_sub = $empcls_table->find()->select(['subjects.subject_name', 'subjects.id'])->join(
                [
    	            'subjects' => 
                    [
                        'table' => 'subjects',
                        'type' => 'LEFT',
                        'conditions' => 'subjects.id = employee_class_subjects.subject_id'
                    ]
    
                ])->where(['md5(employee_class_subjects.emp_id)'=> $teacherid, 'class_id' => $clsid ])->toArray();
                */
            
            
            /*if($subid == "")
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
            }*/
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
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'.$this->base_url.'kindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                    </ul>
                    '.
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
                        <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                        <li> <a href="'.$this->base_url.'kindergarten/view/'. md5($content['id']) .'" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                    </ul>
                    '.
                $img.'
                <p class="title" style="color:#000"><b>Title:</b>'. $content['title'].'</p>
                <p class="title" style="color:#000"><b>Class:</b>'. $classname.'</p>
                <p class="title" style="color:#000"><b>Subjects:</b>'. $subjectname.'</p>
            </div>';
            
        }
        
        return $this->json($res);
    }
}

  


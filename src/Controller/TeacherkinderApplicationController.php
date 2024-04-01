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
class TeacherkinderApplicationController   extends AppController
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
        $compid = $this->request->session()->read('company_id');
        $application_table = TableRegistry::get('application_data');
        $retrieve_app = $application_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
        $this->set("know_details", $retrieve_app); 
        $this->viewBuilder()->setLayout('user');
    }
            
    public function adddata()
    {
       
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $application_table = TableRegistry::get('application_data');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tchrid = $this->request->session()->read('tchr_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $filess = "";
            $nopages = 0;
            $dirname ="";
            if(!empty($this->request->data['file_upload']['name']))
            { 
                if($this->request->data['file_upload']['type'] == "application/pdf" )
                {
                    $filename = $this->request->data['file_upload']['name'];
                    $uploadpath = 'applications_data/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                    {
                        $filess =  $this->request->data['file_upload']['name'];
                        $this->request->data['file_upload'] = $filename; 
                    }
                    else
                    {
                        $filess =  "";
                    }
                    $im = new Imagick();
                    $im->pingImage('applications_data/'.$filename);
                    $nopages = $im->getNumberImages();
                    $dirname = "Ebook".time();
                }
                else
                {
                     $filess =  "";
                }
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
                    
                    $uploadpath = 'applications_data/';
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
                if($langlbl['id'] == '1951') { $puf = $langlbl['title'] ; } 
            } 
            if($nopages > 21)
                {
                    $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                }
                else {
            if(!empty($filess))
            {
                $knowledge = $application_table->newEntity();
                $knowledge->title = $this->request->data('title');
	            $knowledge->created_date = time();
                $knowledge->description = $this->request->data('desc');
                $knowledge->links = $filess;
				$knowledge->status = 1;
                $knowledge->school_id = $compid;
                $knowledge->image = $coverimg;
                $knowledge->teacher_id = $tchrid;
                $knowledge->numpages = $nopages;
                $knowledge->dirname = $dirname;
                    
                if($saved = $application_table->save($knowledge) )
                {   
                    $clsid = $saved->id;
                    
                    mkdir($dirname);
                    $numpages = $nopages-1;
                    for ($x = 0; $x <= $numpages; $x++) {
                        $save_to    = $dirname."/".$filename.$x.'.jpg';  
                        $im = new Imagick();
                        $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                        $im->readImage('applications_data/'.$filename.'['.$x.']');    //[0] for the first page
                        $im->setImageFormat('jpg');
                        $im = $im->flattenImages();
                        $im ->writeImages($save_to, true);
                        header('Content-Type: image/jpeg');
                        sleep(2);
                    }

                    $activity = $activ_table->newEntity();
                    $activity->action =  "Stand Alone Application Content Added"  ;
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
                $res = [ 'result' => $puf  ];
            } }
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
        $application_table = TableRegistry::get('application_data');
		$retrieve_application = $application_table->find()->where(['id' => $id])->toArray();
		return $this->json($retrieve_application);
    }
            
    public function editdata()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $application_table = TableRegistry::get('application_data');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $id = $this->request->data('ekid');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                    
                    $uploadpath = 'applications_data/';
                    $uploadfile = $uploadpath.$coverimg; 
                    file_put_contents($uploadfile, $cropped_image);
                }
            }
            else
            {
                $coverimg = $this->request->data('ecoverimage');
            }
            
            if(!empty($this->request->data['efile_upload']['name']))
            {   
                if($this->request->data['efile_upload']['type'] == "application/pdf" )
                {
                $filename = $this->request->data['efile_upload']['name'];
                $uploadpath = 'applications_data/';
                $uploadfile = $uploadpath.$filename;
                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                {
                    $filess =  $this->request->data['efile_upload']['name'];
                    $this->request->data['efile_upload'] = $filename; 
                }
                else
                {
                    $filess =  "";
                }
                $im = new Imagick();
                $im->pingImage('applications_data/'.$filename);
                $nopages = $im->getNumberImages();
                $dirname = "Ebook".time();
                }
                else
                {
                    $filess =  "";
                }
            }
            else
            {
                $filess = $this->request->data('efileupload');
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
                if($langlbl['id'] == '1951') { $puf = $langlbl['title'] ; } 
            } 
            if($nopages > 21)
            {
                $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
            }
            else {
            if(!empty($filess))
            {
			    if($application_table->query()->update()->set(['numpages' => $nopages, 'dirname' => $dirname,  'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title ])->where([ 'id' => $id  ])->execute())
                {   
                    $knowid = $id;
                    
                    mkdir($dirname);
                    $numpages = $nopages-1;

                    for ($x = 0; $x <= $numpages; $x++) {
                        $save_to    = $dirname."/".$filename.$x.'.jpg';  
                        $im = new Imagick();
                        $im->setResolution(300, 300);     //set the resolution of the resulting jpg
                        $im->readImage('applications_data/'.$filename.'['.$x.']');    //[0] for the first page
                        $im->setImageFormat('jpg');
                        $im = $im->flattenImages();
                        $im ->writeImages($save_to, true);
                        header('Content-Type: image/jpeg');
                        sleep(2);
                    }

                    $activity = $activ_table->newEntity();
                    $activity->action =  "Stand Alone Application Content Updated"  ;
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
                $res = [ 'result' => $puf  ];
            } }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];

        }
        return $this->json($res);
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
        $this->viewBuilder()->setLayout('user');
    }
    
    public function delete()
    {
        $kbid = $this->request->data('val') ;
        $app_table = TableRegistry::get('application_data');
        $apps_comments_table = TableRegistry::get('application_data_comments');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $kid = $app_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
        if($kid)
        {   
			$del = $app_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
            if($del)
            {
				$del_knowledge = $apps_comments_table->query()->delete()->where([ 'app_id' => $kbid ])->execute(); 
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
            $comments_table = TableRegistry::get('application_data_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tchrid = $this->request->session()->read('tchr_id');
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('reply_text');
            $comments->app_id = $this->request->data('r_kid');
            $comments->created_date = time();
            $comments->parent = $this->request->data('comment_id');
            $comments->added_by = "teacher";
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
    
    public function addcomment()
    {
        //print_r($_POST); die;
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $comments_table = TableRegistry::get('application_data_comments');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tchrid = $this->request->session()->read('tchr_id');
            
            $comments = $comments_table->newEntity();
            $comments->comments = $this->request->data('comment_text');
            $comments->app_id = $this->request->data('kid');
            $comments->created_date = time();
            $comments->parent = 0;
            $comments->added_by = "teacher";
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

            
}

  


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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SchoolLibraryController extends AppController
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
            public function index(){  
                
                //$tid = $this->Cookie->read('tid');
                $compid = $this->request->session()->read('company_id');
                $content_table = TableRegistry::get('school_library');
               
                $retrieve_content = $content_table->find()->where([ 'school_id' => $compid ])->toArray();
                
                $sclsub_table = TableRegistry::get('school_subadmin');
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
					$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
				}
				$class_table = TableRegistry::get('class');
                $subject_table = TableRegistry::get('subjects');
                foreach($retrieve_content as $content)
                {
                    $clsid = $content['class_id']; 
                    $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $clsid])->first() ;
                    $retsub = $subject_table->find()->where(['id' => $content['subject_id']])->first() ;
                    $content->classname = $retclass['c_name']."-".$retclass['c_section']. "(".$retclass['school_sections'] .")";
                    $content->subname = $retsub['subject_name'];
                    if($this->Cookie->read('logtype') == md5('School Subadmin'))
					{
                        if(strtolower($retclass['school_sections']) == "creche" || strtolower($retclass['school_sections']) == "maternelle") {
                            $clsmsg = "kindergarten";
                        }
                        elseif(strtolower($retclass['school_sections']) == "primaire") {
                            $clsmsg = "primaire";
                        }
                        else
                        {
                            $clsmsg = "secondaire";
                        }
                        
                        if(in_array($clsmsg, $subpriv)) { 
                            $content->show = 1;
                        }
                        else
                        {
                            $content->show = 0;
                        }
					}
					else
					{
					     $content->show = 1;
					}
                    
                }
                
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
        		
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                
                $this->set("class_details", $retrieve_class);
                $this->set("content_details", $retrieve_content); 
				$this->viewBuilder()->setLayout('user');
            }
            
            public function add(){  
                
                $tid = $this->Cookie->read('tid');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
        		$class_table = TableRegistry::get('class');
                $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
                
                $this->set("class_details", $retrieve_class);
				$this->viewBuilder()->setLayout('user');
            }
            
            public function getsubjects()
            {
                $lang = $this->Cookie->read('language');
                if($lang != "") { 
                    $lang = $lang; 
                } else { 
                    $lang = 2; 
                    
                }
                //echo $lang; die;
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
                foreach($retrieve_langlabel as $langlbl) { 
                            if($langlbl['id'] == '2096') { $alllbl = $langlbl['title'] ; } 
                            if($langlbl['id'] == '300') { $chossublbl = $langlbl['title'] ; } 
                }
                
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $classid = $this->request->data('classId');
                $clsex = explode("_", $classid);
                $id = $clsex[0];
                $compid = $this->request->session()->read('company_id');
                $clssub_table = TableRegistry::get('class_subjects');
                $class_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $retrieve_clssub = $clssub_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'status' => 1])->first() ;
                $subject_ids = $retrieve_clssub['subject_id'];
                $ids = explode(",", $subject_ids);
                $retrieve_cls = $class_table->find()->where(['id' => $id])->first() ;
                $section = $retrieve_cls['school_sections'];
                $data = '';
                $data .= '<option value="">'.$chossublbl.'</option>';
                if($clsex[1] == "teacher") {
                    if(($section == "Creche") || ($section == "Primaire") || ($section == "Maternelle")) {
                $data .= '<option value="all">'.$alllbl.'</option>'; }
                }
                foreach($ids as $sids)
                {
                    $retrieve_sub = $sub_table->find()->where(['id' => $sids])->first() ;
                    //$subids[] = $retrieve_sub['subject_name'];
                    $data .= '<option value="'.$sids.'">'.$retrieve_sub['subject_name'].'</option>';
                }
                
                
                return $this->json($data);
            }
            public function getsubjectskinder()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $classid = $this->request->data('classId');
                $subid = $this->request->data('subid');
                $clsex = explode("_", $classid);
                $id = $clsex[0];
                $compid = $this->request->session()->read('company_id');
                $clssub_table = TableRegistry::get('class_subjects');
                $class_table = TableRegistry::get('class');
                $sub_table = TableRegistry::get('subjects');
                $retrieve_clssub = $clssub_table->find()->where(['class_id' => $id, 'school_id' => $compid, 'status' => 1])->first() ;
                $subject_ids = $retrieve_clssub['subject_id'];
                $ids = explode(",", $subject_ids);
                $retrieve_cls = $class_table->find()->where(['id' => $id])->first() ;
                $section = $retrieve_cls['school_sections'];
                $data = '';
                $data .= '<option value="">Choose Subjects</option>';
                if($clsex[1] == "teacher") {
                    if(($section == "Creche") || ($section == "Primaire") || ($section == "Maternelle")) {
                $data .= '<option value="all">All</option>'; }
                }
                foreach($ids as $sids)
                {
                    $sel = '';
                    if($sids == $subid)
                    {
                        $sel = "selected";
                    }
                    $retrieve_sub = $sub_table->find()->where(['id' => $sids])->first() ;
                    $data .= '<option value="'.$sids.'" '.$sel.'>'.$retrieve_sub['subject_name'].'</option>';
                }
                
                
                return $this->json($data);
            }
            public function getstudent()
            {   
                if($this->request->is('post'))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $id = $this->request->data['classId'];
                    $compid = $this->request->session()->read('company_id');
                
                    $classsub_table = TableRegistry::get('class_subjects');
                    $subject_table = TableRegistry::get('subjects');
                    $get_subjects = $classsub_table->find()->where(['class_id' => $id, 'school_id' => $compid])->first(); 
                    
                    $html_content = '';
                    if(!empty($get_subjects)){
                        $subjects = explode(',',$get_subjects->subject_id);
                        
                        $html_content = '<option value="">Choose Subject</option>';
                        $html_content .= '<option value="all">All</option>';
                        foreach($subjects as $subject){
                            
                            $subjects_data = $subject_table->find()->where(['id' => $subject, 'school_id' => $compid])->first(); 
                            $html_content .= '<option value = "'.$subjects_data->id.'" >'.$subjects_data->subject_name.'</option>';
                        }
                    }
                    return $this->json($html_content);
                }  
            }
            public function view($id)
            {   
                $schoolid = $this->request->session()->read('company_id');
                $tid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $class_table = TableRegistry::get('class');
                $subjects_table = TableRegistry::get('subjects');
                $tutorialfee_table = TableRegistry::get('tutorial_fee');
                
                $retrieve_tutorialfee = $tutorialfee_table->find()->where(['md5(id)' => $id ])->first();
                //print_r($retrieve_tutorialfee);exit;
                $employee_table = TableRegistry::get('employee');
                $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
                
                foreach($retrieve_employees as $key =>$emp_coll)
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
        					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section'];				
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
        		
        		$current_year = date('Y');
        		$current_m_y = date('F Y');
        		$session_table = TableRegistry::get('session'); //->select(['id' ,'startyear','endyear'])
        		$retrieve_session = $session_table->find()->where([ 'startyear <= '=> $current_year, 'endyear >= '=> $current_year ])->order(['startyear' => 'ASC'])->toArray() ;
        		/*$get_s_array = array(); $i=0;
        		foreach($retrieve_session as $sess){
        		    $st_date = $sess->startmonth.' '.$sess->startyear;
        		    $en_date = $sess->endmonth.' '.$sess->endyear;
        		    
        		    if(strtotime($current_m_y) >= strtotime($st_date)  && strtotime($current_m_y) <= strtotime($en_date)){
        		        $get_s_array[$i]['id'] = $sess->id;
        		        $get_s_array[$i]['startyear'] = $sess->startyear;
        		        $get_s_array[$i]['endyear'] = $sess->endyear;
        		    }
        		    $i++;
        		}*/
        		
                $this->set("session_details", $retrieve_session);

				$this->set("employees_details", $retrieve_employees); 
                $this->set("tutorial_details", $retrieve_tutorialfee); 
                $this->viewBuilder()->setLayout('user');
            }
            public function delete()
            {
                $rid = $this->request->data('val') ;
                $library_table = TableRegistry::get('school_library');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
                    
                $del = $library_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                if($del)
				{
                    $activity = $activ_table->newEntity();
                    $activity->action =  "successfully Deleted!"  ;
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
            public function subjects(){   
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid');
                $employee_table = TableRegistry::get('employee');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
				
				$notification_table = TableRegistry::get('notification');
                $schoolid  = $retrieve_employees[0]['school_id'];
                $tchrid  = $retrieve_employees[0]['id'];
                
                $rcve_nottzCount = $notification_table->find()->where(['sent_notify' => 1, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'teachers']] ])->toArray() ;
                
                if(!empty($rcve_nottzCount))
                {
                    $countNotifctn = [];
                    foreach($rcve_nottzCount as $notzcount)
                    {
                        if($notzcount['notify_to'] == "all")
                        {
                            $countNotifctn[] = 1;
                        }
                        else
                        {
                            $tchrids = explode(",", $notzcount['teacher_ids']);
                          
                            if(in_array($tchrid, $tchrids))
                            {
                                $countNotifctn[] = 1;
                            }
                            else
                            {
                                $countNotifctn[] = 0;
                            }
                        }
                    }
                    $countNoti = array_sum($countNotifctn);
                    
                }
                else
                {
                    $countNoti = 0;
                }
                $this->set("total_count", $countNoti); 
				
    			foreach($retrieve_employees as $key =>$emp_coll)
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
        					$empgrades[$i] = $grad['c_name']. "-".$grad['c_section'];				
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

				$this->set("employees_details", $retrieve_employees); 
				

				$this->viewBuilder()->setLayout('user');
            }
            public function addlibrarycontent()
            {
               
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;
                    $scllib_table = TableRegistry::get('school_library');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $video_type = "";  $you_count = 0;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
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
                                $youex = explode("watch?v=",$link);
                                $you_count = count($youex);
                                if($you_count >1){
                                    $file_link  = $youex[0]."embed/".$youex[1];
                                    $video_type = "youtube";
                                }
                            }
                            
                            $file_vimeo =  strpos($link, "vimeo");
                            if($file_vimeo != false)
                            {
                                $file_link = $link;
                                $video_type = "vimeo";
                            }
                            $filess = $file_link;
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
                                        $this->request->data['file_upload'] = $filename; 
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
                                    $newfilename = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($this->request->data['file_upload']['name']));
                                    $filess =  time().$newfilename;
                                    $filename = $filess;
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['file_upload'] = $filename; 
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
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        //$nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                        
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
                  //  if($nopages > 1500)
                    //{
                      //  $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    //}
                    //else 
                    //{
                        if(!empty($filess))
                        {
                            $knowledge = $scllib_table->newEntity();
                            $knowledge->file_type = $this->request->data('file_type');
                            $knowledge->title = $this->request->data('title');
                            $knowledge->created_date = time();
                            $knowledge->description = $this->request->data('desc');
                            $knowledge->links = $filess;
                            $knowledge->class_id = $this->request->data('class_id');
                            $knowledge->subject_id = $this->request->data('subject_id');
                            $knowledge->image = $coverimg;
                            $knowledge->status = "1";
                            $knowledge->active = 0;
                            $knowledge->school_id = $compid;
                            $knowledge->video_type = $video_type;
                            $knowledge->numpages = $nopages;
                            $knowledge->dirname = $dirname;
                            
                            $class_id = $this->request->data('class_id');
                            $subject_id = $this->request->data('subject_id');
                            
                                if($saved = $scllib_table->save($knowledge) )
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
                                        $res = [ 'result' => 'success' , 'class'=> $class_id, 'subject'=> $subject_id];
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
                            $res = [ 'result' => 'Document is upload only in Pdf Format and Audio is Mp3 Format and In Video file link is mandatory. Size of file not more than 5MB '  ];
                        }
                    //}
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            public function deletecontent()
            {
                $rid = $this->request->data('val') ;
                $libcontent_table = TableRegistry::get('school_library');
                $activ_table = TableRegistry::get('activity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                $del = $libcontent_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
                if($del)
				{
                    $activity = $activ_table->newEntity();
                    $activity->action =  "successfully Deleted!"  ;
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
            
            public function editcontent(){   
                if($this->request->is('post'))
                {
                    $id = $this->request->data['id'];
                    $library_table = TableRegistry::get('school_library');
                    $retrieve_content_table = $library_table->find()->where([ 'id '=> $id])->first() ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $clsid = $retrieve_content_table['class_id'];
                    $compid = $this->request->session()->read('company_id');
                    $clssub_table = TableRegistry::get('class_subjects');
                    $sub_table = TableRegistry::get('subjects');
                    $retrieve_clssub = $clssub_table->find()->where(['class_id' => $clsid, 'school_id' => $compid, 'status' => 1])->first() ;
                    $subject_ids = $retrieve_clssub['subject_id'];
                    $ids = explode(",", $subject_ids);
                    
                   
                    $data = '';
                    foreach($ids as $sids)
                    {
                        $retrieve_sub = $sub_table->find()->where(['id' => $sids])->first() ;
                        //$subids[] = $retrieve_sub['subject_name'];
                        $data .= '<option value="'.$sids.'">'.$retrieve_sub['subject_name'].'</option>';
                    }
                    
                    $content['getdata'] = $retrieve_content_table;
                    $content['subjects'] = $data;
                    
                    
                    return $this->json($content);
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
            
            public function editlibcontent()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $max_allfilezise = 2000000; $max_pdffilezise = 5000000; $max_audiofilezise = 5000000;
                    $libcontent_table = TableRegistry::get('school_library ');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');

                    $classId = $this->request->data('class_id');
                    $subjectid = $this->request->data('subject_id');
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
                    
                    $nopages = 0;
                    $dirname = '';
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
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
                                        $this->request->data['efile_upload'] = $filename; 
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
                                        $this->request->data['efile_upload'] = $filename; 
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
                        
                        $im = new Imagick();
                        $im->pingImage('img/'.$filename);
                        $nopages = $im->getNumberImages();
                        $dirname = "Ebook".time();
                    }
                    
                    if($nopages > 21)
                    {
                        $res = [ 'result' => 'Maximum Pdf page limit is 20.'];
                    }
                    else {
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($libcontent_table->query()->update()->set(['numpages' => $nopages, 'dirname' => $dirname, 'file_type' => $file_type , 'video_type' => $video_type,  'class_id' => $classId, 'subject_id' => $subjectid, 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
                            
                            if($file_type == "pdf")
                            {
                                mkdir($dirname);
                                $numpages = $nopages-1;
    
                                for ($x = 0; $x <= $numpages; $x++) {
                                   // echo $x;
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
                            $activity->action =  "Library Content Updated Successfully!"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'  , 'class'=>$classId , 'subject'=>$subjectid ];
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
                    $comments_table = TableRegistry::get('library_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->lib_content_id = $this->request->data('kid');
		            $comments->created_date = time();
		            $comments->added_by = "school";
                    $comments->school_id = $this->request->data('schoolid');
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
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function replycomments(){
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                       $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    
                    $comments_table = TableRegistry::get('library_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->lib_content_id = $this->request->data('r_kid');
                    //$comments->teacher_id = $this->request->data('teacher_id');
                    $comments->school_id = $this->request->data('skul_id');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
                                          
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
                    echo $res; die;
                return $this->json($res);
            }
            
            public function viewfiltercontent()
            {
                $libcontent_table = TableRegistry::get('school_library');
                $cls_id = $this->request->data('class_id');
                $sub_id = $this->request->data('subject_id');
                $filter = $this->request->data('filter');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($cls_id != "" && $sub_id != "")
                {
                    if($sub_id == "all")
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                    else
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                }
                elseif($cls_id != "")
                {
                    if($cls_id == "all")
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where([ 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where([ 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where([ 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                    else
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                }
                
                $res = '';
                
                $class_table = TableRegistry::get('class');
                $subject_table = TableRegistry::get('subjects');
                foreach($retrieve_content as $content)
                {
                    $clsid = $content['class_id']; 
                    $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $clsid])->first() ;
                    $retsub = $subject_table->find()->where(['id' => $content['subject_id']])->first() ;
                    $classname = $retclass['c_name']."-".$retclass['c_section']. "(".$retclass['school_sections'] .")";
                    $subname = $retsub['subject_name'];
               
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editlibcontent" class="editlibcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="schoolLibrary/delete" class=" js-sweetalert " title="Delete" data-str="Library content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'schoolLibrary/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        <b>Classe</b>: '. ucfirst($classname) .'</br>
                        <b>Subject</b>: '. ucfirst($subname) .'</p>
                    </div>';
                }
                
                return $this->json($res);
            }
            
            public function getcontent()
            {
                $libcontent_table = TableRegistry::get('school_library');
                $filter = $this->request->data('filter');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());    
                if($filter == "newest")
                {
                    $retrieve_content = $libcontent_table->find()->where([ 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_content = $libcontent_table->find()->where([ 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_content = $libcontent_table->find()->where([ 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                }
                    
                
                
                $res = '';
                $class_table = TableRegistry::get('class');
                $subject_table = TableRegistry::get('subjects');
                foreach($retrieve_content as $content)
                {
                    $clsid = $content['class_id']; 
                    $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $clsid])->first() ;
                    $retsub = $subject_table->find()->where(['id' => $content['subject_id']])->first() ;
                    $classname = $retclass['c_name']."-".$retclass['c_section']. "(".$retclass['school_sections'] .")";
                    $subname = $retsub['subject_name'];
                    
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editlibcontent" class="editlibcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="schoolLibrary/delete" class=" js-sweetalert " title="Delete" data-str="Library content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'schoolLibrary/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        <b>Classe</b>: '. ucfirst($classname) .'</br>
                        <b>Subject</b>: '. ucfirst($subname) .'</p>
                    </div>';
                }
                
                return $this->json($res);
            }
            
            public function viewtutcontent()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $libcontent_table = TableRegistry::get('school_library');
                $filter = $this->request->data('filter');
                $cls_id = $this->request->data('class_id');
                $sub_id = $this->request->data('subject_id');
                $compid = $this->request->session()->read('company_id');
                if($cls_id != "" && $sub_id != "")
                {
                    if($sub_id == "all") 
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                    else
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'subject_id' => $sub_id, 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                    
                }
                elseif($cls_id != "")
                {
                    if($cls_id == "all")
                    {
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
                    }
                    else
                    {
                        if($filter == "newest")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id,  'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "pdf")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'pdf', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "audio")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'audio', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                        elseif($filter == "video")
                        {
                            $retrieve_content = $libcontent_table->find()->where(['class_id' => $cls_id, 'file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                        }
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_content = $libcontent_table->find()->where(['school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_content = $libcontent_table->find()->where(['file_type' => 'pdf',  'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_content = $libcontent_table->find()->where(['file_type' => 'audio',  'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_content = $libcontent_table->find()->where(['file_type' => 'video', 'school_id' => $compid])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                $res = '';
                $class_table = TableRegistry::get('class');
                $subject_table = TableRegistry::get('subjects');
                foreach($retrieve_content as $content)
                {
                    $clsid = $content['class_id']; 
                    $retclass = $class_table->find()->select(['id' ,'c_name', 'c_section', 'school_sections'])->where(['id' => $clsid])->first() ;
                    $retsub = $subject_table->find()->where(['id' => $content['subject_id']])->first() ;
                    $classname = $retclass['c_name']."-".$retclass['c_section']. "(".$retclass['school_sections'] .")";
                    $subname = $retsub['subject_name'];
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
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
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editlibcontent" class="editlibcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li><button type="button" data-id="'. $content['id'].'" data-url="SchoolLibrary/delete" class="js-sweetalert " title="Delete" data-str="Library content" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                        
                            <li> <a href="'. $this->base_url.'schoolLibrary/viewcontent/'. md5($content['id']) .'" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        <b>Classe</b>: '. ucfirst($classname) .'</br>
                        <b>Subject</b>: '. ucfirst($subname) .'</p>
                    </div>';
                }
                
                return $this->json($res);
            }
            
        

}

  


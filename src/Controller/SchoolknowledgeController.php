<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
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
class SchoolknowledgeController   extends AppController
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
            public function community()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }
            public function communityactivity($addedfor)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('knowledge_center');
                $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                $retrieve_knowtitle = $knowledge_table->find()->where(['added_for' => $addedfor])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                
                $fileName = "https://you-me-globaleducation.org/school/webroot/classes.csv";
                $csvData = file_get_contents($fileName);
                $lines = explode(PHP_EOL, $csvData);
                $retrieve_class = array();
                foreach ($lines as $row) 
                {
                    $data = str_getcsv($row);
                	$retrieve_class['c_name'] = $data[0] ;
                	$retrieve_class['school_sections'] = $data[1];
                	
                	$classes[] = $retrieve_class;
                }
                $this->set("title_list", $retrieve_knowtitle);
                $this->set("know_details", $retrieve_know);
                $this->set("cls_details", $classes); 
                $this->set("added_for", $addedfor); 
                $this->viewBuilder()->setLayout('user');
            }
            public function index()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->toArray() ;
                $this->set("countries_details", $retrieve_countries); 
                $this->viewBuilder()->setLayout('user');
            }
            public function localuniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $state_table = TableRegistry::get('states');
                $retrieve_states = $state_table->find()->where(['country_id' => 50])->toArray() ;
                $this->set("states_details", $retrieve_states); 
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univs = $univ_table->find()->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_state = $state_table->find()->where(['id' => $univss['state_id'] ])->first() ;
                    $state_name = $retrieve_state['name'];
                    $univss->state_name = $state_name;
                }
                $this->set("univ_details", $retrieve_univs); 
                $this->viewBuilder()->setLayout('user');
            }
            public function studyabroad()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->toArray() ;
                $this->set("countries_details", $retrieve_countries); 
                $univ_table = TableRegistry::get('univrsities');
                $retrieve_univs = $univ_table->find()->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_countries = $country_table->find()->where(['id' => $univss['country_id'] ])->first() ;
                    $country_name = $retrieve_countries['name'];
                    $univss->country_name = $country_name;
                }
                $this->set("univ_details", $retrieve_univs); 
                $this->viewBuilder()->setLayout('user');
            }
            public function view($id)
            {  
                $knowledge_table = TableRegistry::get('knowledge_center');
                $knowcomm_table = TableRegistry::get('knowledge_center_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
            public function listing()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id') ;
                $knowcomm_table = TableRegistry::get('knowledge_center_comments');
				$retrieve_comments = $knowcomm_table->find()->where(['parent' => $id])->toArray();
                return $this->json($retrieve_comments);
            }
            public function replycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('knowledge_center_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
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
                    $comments_table = TableRegistry::get('knowledge_center_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
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
            public function countryfilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter = $this->request->data('country');
                $univ_table = TableRegistry::get('univrsities');
                $country_table = TableRegistry::get('countries');
                $retrieve_univs = $univ_table->find()->where(['country_id' => $filter])->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_countries = $country_table->find()->where(['id' => $univss['country_id'] ])->first() ;
                    $country_name = $retrieve_countries['name'];
                    $univss->country_name = $country_name;
                }
                $res = '';
                foreach($retrieve_univs as $uni) 
                {
                    $email = "";
                    $contact = "";
                    $website= "";
                    $courses = "";
                    
                    if(!empty( $uni['country_name'] )) 
                    { 
                        $country = '<p><b>Country: </b><span>'.  $uni['country_name'] .' </span></p>';
                    } 
                    if(!empty( $uni['email'] )) 
                    { 
                        $email = '<p><b>Email: </b><span>'.  $uni['email'] .' </span></p>';
                    } 
                    
                    if(!empty( $uni['contact_number'] )) 
                    {
                        $contact = '<p><b>Contact No: </b><span>'.  $uni['contact_number'] .' </span></p>';
                    } 
                    if(!empty( $uni['website_link'] )) 
                    {
                        $website = '<p><b>Website Link: </b><span>'.  $uni['website_link'] .' </span></p>';
                    } 
                    if(!empty( $uni['academics'] )) 
                    {
                        $courses = '<p><b>Courses Offered: </b><span>'.  $uni['academics'] .' </span></p>';
                    } 
                    $res .= '<tr>
                            <td>
                                <img src="'. $this->base_url.'univ_logos/'. $uni['logo'] .'" class="avatar" alt=""style="width: 140px; height: 100px;">
                            </td>
                            <td>
                                <p>
                                    <span style="width:87%; float:left; margin-bottom:8px;"><b>'.  $uni['univ_name'] .'</b></span>
                                    <span style="width:13%; float:right;">
                                        <a href="javascript:void(0);" data-id="'. $uni['id'] .'" data-uniname="'. $uni['univ_name'] .'" data-logo = "'. $uni['logo'] .'" class="btn btn-success youmecontct" title="Contact Form">Contact Us</a>
                                    </span>
                                </p>
                                '.$country.$email.$contact.$website.$courses.'
                                <p><b>Description: </b><span id="unidesc_'.  $uni['id'] .' ">'.  substr($uni['about_univ'], 0, 250).' ...</span></p>
                                <p class="align-right" style="margin-right: 35px;"><span><a href="javascript:void(0);" class="see_more" id="see_more'.  $uni['id'] .' " data-id="'.  $uni['id'] .' ">See More</a></span</p>
                            </td>
                            
                        </tr>';
                } 
                return $this->json($res);
            }
            public function statefilter()
            {
                $filter = $this->request->data('state');
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univs = $univ_table->find()->where(['state_id' => $filter])->group(['city'])->toArray() ;
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $data = '';
                $data .= '<option value="">Choose City</option>';
                foreach($retrieve_univs as $uni)
                {
                    $data .= '<option value="'.$uni['city'].'">'.$uni['city'].'</option>';
                }
                return $this->json($data);
            }
            public function cityfilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter_state = $this->request->data('state');
                $filter_city = $this->request->data('city');
                $univ_table = TableRegistry::get('localuniversity');
                $state_table = TableRegistry::get('states');
                $retrieve_univs = $univ_table->find()->where(['state_id' => $filter_state, 'city' => $filter_city ])->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_state = $state_table->find()->where(['id' => $univss['state_id'] ])->first() ;
                    $state_name = $retrieve_state['name'];
                    $univss->state_name = $state_name;
                }
                $res = '';
                foreach($retrieve_univs as $uni) 
                {
                    $email = "";
                    $contact = "";
                    $website= "";
                    $courses = "";
                    
                    if(!empty( $uni['email'] )) 
                    { 
                        $email = '<p><b>Email: </b><span>'.  $uni['email'] .' </span></p>';
                    } 
                    
                    if(!empty( $uni['state_name'] )) 
                    { 
                        $state = '<p><b>Email: </b><span>'.  $uni['state_name'] .' </span></p>';
                    } 
                    
                    if(!empty( $uni['contact_number'] )) 
                    {
                        $contact = '<p><b>Contact No: </b><span>'.  $uni['contact_number'] .' </span></p>';
                    } 
                    if(!empty( $uni['website_link'] )) 
                    {
                        $website = '<p><b>Website Link: </b><span>'.  $uni['website_link'] .' </span></p>';
                    } 
                    if(!empty( $uni['academics'] )) 
                    {
                        $courses = '<p><b>Courses Offered: </b><span>'.  $uni['academics'] .' </span></p>';
                    } 
                    $res .= '<tr>
                            <td>
                                <img src="'. $this->base_url.'univ_logos/'. $uni['logo'] .'" class="avatar" alt=""style="width: 140px; height: 100px;">
                            </td>
                            <td>
                                <p>
                                    <span style="width:87%; float:left; margin-bottom:8px;"><b>'.  $uni['univ_name'] .', '.  $uni['city'] .'</b></span>
                                    <span style="width:13%; float:right;">
                                        <a href="javascript:void(0);" data-id="'. $uni['id'] .'" data-uniname="'. $uni['univ_name'] .'" data-logo = "'. $uni['logo'] .'" class="btn btn-success localyoumecontactus" title="Contact Form">Contact Us</a>
                                    </span>
                                </p>
                                '.$state.$email.$contact.$website.$courses.'
                                <p><b>Description: </b><span id="unidesc_'.  $uni['id'] .' ">'.  substr($uni['about_univ'], 0, 250).' ...</span></p>
                                <p class="align-right" style="margin-right: 35px;"><span><a href="javascript:void(0);" class="see_more" id="see_more'.  $uni['id'] .' " data-id="'.  $uni['id'] .' ">See More</a></span</p>
                            </td>
                            
                        </tr>';
                } 
                return $this->json($res);
            }
            public function youmeconatct()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    //$to = "nancy@outsourcingservicesusa.com";
                    $to = "contactus@you-me-globaleducation.org";
                    $username =  $this->request->data('name');
                    $from =  $this->request->data('email');
                    $budget =  $this->request->data('budget');
                    $number =  $this->request->data('number');
                    $academic_year = $this->request->data('academic_year');
                    $desc = $this->request->data('desc');
                    $uid = $this->request->data('univid');
                    
                    $univ_table = TableRegistry::get('univrsities');
                    $retrieve_univs = $univ_table->find()->where(['id' => $uid])->first() ;
                    
                    $uniname = $retrieve_univs['univ_name'];
                    
                    $name = "You-Me Global Education";
                    $subject = 'Study Abroad enquires for You-Me Global Education';
                    
                    $htmlContent = '
                    <tr>
                    <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                        <multiline>
                            This is to inform you that I '.$username.' ,  want to go for further studies for '. $uniname.'
                        </multiline>
                    </td>
                    </tr>
                    <tr>
                        <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                            <multiline>Please find the below details for contact.</multiline>
                        </td>
                    </tr>
                    <tr>
                        <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                            <multiline>
                            <p>Email: '.$from.' </p>
                            <p>Contact Number: '.$number.' </p>
                            <p>Budget: '.$budget.' </p>
                            <p>Academic Year: '.$academic_year.' </p>
                            <p>Description: '.$desc.' </p>
                            </multiline>
                        </td>
                    </tr>
                    
                    <p>Thanks</p>
                    <p>'.$username.'</p>';
                    
                    /*$headers = "MIME-Version: 1.0" . "\r\n";
                	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                	$headers .= 'From: Support <noreply@youmetechnologies.com>'. "\r\n";
                	$headers .= 'X-Priority: 3'. "\r\n";
                    $headers .= 'X-Mailer: PHP'. phpversion() ."\r\n";
                    $sendmail =  mail($to, $subject, $htmlContent, $headers);*/
        
                    $message = '<p>This is to inform you that I '.$username.' ,  want to go for further studies for '. $uniname.' </p>
                            <p>Please find the below details for contact.</p>
                            <p>Email: '.$from.' </p>
                            <p>Contact Number: '.$number.' </p>
                            <p>Budget: '.$budget.' </p>
                            <p>Academic Year: '.$academic_year.' </p>
                            <p>Description: '.$desc.' </p>';
                    
                    $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                    $queries_table = TableRegistry::get('message_queries');
                    $queries = $queries_table->newEntity();
                    
                    $queries->name =  $username  ;
                    $queries->message =  $message ;
                    $queries->local_abroad = 'abroad';
                    $queries->created_date = strtotime('now');
                    
                    $save = $queries_table->save($queries) ;

                    if($save)
                    {   
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = ['result' => 'failed'];
                    }
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);
            }
            public function youmeconatctlocal()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    //print_r($_POST);
                    //$to = "nancy@outsourcingservicesusa.com";
                    $to = "contactus@you-me-globaleducation.org";
                    $username =  $this->request->data('name');
                    $from =  $this->request->data('email');
                    $budget =  $this->request->data('budget');
                    $number =  $this->request->data('number');
                    $academic_year = $this->request->data('academic_year');
                    $desc = $this->request->data('desc');
                    $uid = $this->request->data('univid');
                    if (preg_match ("/^[0-9]*$/", $number) )
                    {
                        $univ_table = TableRegistry::get('localuniversity');
                        $retrieve_univs = $univ_table->find()->where(['id' => $uid])->first() ;
                        
                        $uniname = $retrieve_univs['univ_name'];
                        
                        $name = "You-Me Global Education";
                        $subject = 'Local universities enquires for You-Me Global Education';
                        
                        $htmlContent = '
                        <tr>
                        <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                            <multiline>
                                This is to inform you that I '.$username.' ,  want to go for further studies for '. $uniname.'
                            </multiline>
                        </td>
                        </tr>
                        <tr>
                            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                                <multiline>Please find the below details for contact.</multiline>
                            </td>
                        </tr>
                        <tr>
                            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                                <multiline>
                                <p>Email: '.$from.' </p>
                                <p>Contact Number: '.$number.' </p>
                                <p>Budget: '.$budget.' </p>
                                <p>Academic Year: '.$academic_year.' </p>
                                <p>Description: '.$desc.' </p>
                                </multiline>
                            </td>
                        </tr>
                    
                        <p>Thanks</p>
                        <p>'.$username.'</p>';
                        
                        /*$headers = "MIME-Version: 1.0" . "\r\n";
                    	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    	$headers .= 'From: Support <noreply@youmetechnologies.com>'. "\r\n";
                    	$headers .= 'X-Priority: 3'. "\r\n";
                        $headers .= 'X-Mailer: PHP'. phpversion() ."\r\n";
                        $sendmail =  mail($to, $subject, $htmlContent, $headers);*/
            
                        $message = '<p>This is to inform you that I '.$username.' ,  want to go for further studies for '. $uniname.' </p>
                                <p>Please find the below details for contact.</p>
                                <p>Email: '.$from.' </p>
                                <p>Contact Number: '.$number.' </p>
                                <p>Budget: '.$budget.' </p>
                                <p>Academic Year: '.$academic_year.' </p>
                                <p>Description: '.$desc.' </p>';
            
                        $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
                         $queries_table = TableRegistry::get('message_queries');
                        $queries = $queries_table->newEntity();
                        
                        $queries->name =  $username  ;
                        $queries->message =  $message ;
                        $queries->local_abroad = 'local';
                        $queries->created_date = strtotime('now');
                        
                        $save = $queries_table->save($queries) ;
    
                        if($save)
                        {   
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        {
                            $res = ['result' => 'failed'];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'Only digit allowed in contact number'  ];
                    }
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);
            }
            public function viewcommunity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('knowledge_center');
                $filter = $this->request->data('filter');
                $clsfilter = $this->request->data('clsfilter');
                $added_for = $this->request->data('addedfor');
                $title = $this->request->data('title');
                if($clsfilter != "" && $title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else if($clsfilter != "" && $title == "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else if($clsfilter == "" && $title != "")
                {
                    
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for,  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where([ 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
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
                    $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'schoolknowledge/view/'. md5($content['id']) .'" class="viewknow" target="_blank">
                        '.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        </a>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        <b>Classe: </b>'. ucfirst($content->classname) .'</p>
                    </div>';
                }
                
                return $this->json($res);
            }
            
            /******************* List Scholarship, Mentorship, Internship, Intensive English*************************/
            public function scholarship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('scholarship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
			public function mentorship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('mentorship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
			public function internship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('internship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
			public function intensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function kgIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'kinder'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->where(['added_for' => 'kinder'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'kinder'); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function primaryIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'primary'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->where(['added_for' => 'primary'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'primary'); 
                $this->viewBuilder()->setLayout('user');
            }
            
            public function highsclIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'highscl'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->where(['added_for' => 'highscl'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'highscl'); 
                $this->viewBuilder()->setLayout('user');
            }
            public function leadership()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('leadership');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function howitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function sclHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'school'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $howitworks_table->find()->where(['added_for' => 'school'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'school'); 
                $this->viewBuilder()->setLayout('user');
            }
            public function tchrHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'teacher'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $howitworks_table->find()->where(['added_for' => 'teacher'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'teacher'); 
                $this->viewBuilder()->setLayout('user');
            }
            public function studHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'student'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $howitworks_table->find()->where(['added_for' => 'student'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'student'); 
                $this->viewBuilder()->setLayout('user');
            }
            public function parentHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'parent'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $howitworks_table->find()->where(['added_for' => 'parent'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'parent'); 
                $this->viewBuilder()->setLayout('user');
            }
            public function newtechnologies()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $machine_table = TableRegistry::get('newtechnologies');
                $retrieve_know = $machine_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $machine_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function machinelearning()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $machine_table = TableRegistry::get('machine_learning');
                $retrieve_know = $machine_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $machine_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function kgMachinelearning($addedfor)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('machine_learning');
                $retrieve_know = $leadership_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $leadership_table->find()->where(['added_for' => $addedfor])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title); 
                $fileName = "classes.csv";
                $csvData = file_get_contents($fileName);
                $lines = explode(PHP_EOL, $csvData);
                $retrieve_class = array();
                foreach ($lines as $row) 
                {
                    $data = str_getcsv($row);
                	$retrieve_class['c_name'] = $data[0] ;
                	$retrieve_class['school_sections'] = $data[1];
                	
                	$classes[] = $retrieve_class;
                }
               
                $this->set("know_details", $retrieve_know);
                $this->set("cls_details", $classes); 
                $this->set("added_for", $addedfor); 
                $this->viewBuilder()->setLayout('user');
            }
            public function stateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('user');
            }
            public function latinphiloStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'latinphilo'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'latinphilo'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'latinphilo');
                $this->viewBuilder()->setLayout('user');
            }
            public function mathphyStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'mathphy'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'mathphy'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'mathphy');
                $this->viewBuilder()->setLayout('user');
            }
            public function chembioStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'chembio'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'chembio'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'chembio');
                $this->viewBuilder()->setLayout('user');
            }
            public function generalStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'general'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'general'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'general');
                $this->viewBuilder()->setLayout('user');
            }
            public function commercialeStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'commerciale'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'commerciale'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'commerciale');
                $this->viewBuilder()->setLayout('user');
            }
            public function techniquesStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'techniques'])->order(['id' => 'desc'])->toArray() ;
                $retrieve_title = $stateexam_table->find()->where(['added_for' => 'techniques'])->group(['title'])->order(['id' => 'desc'])->toArray() ;
                $this->set("title_details", $retrieve_title);
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'techniques');
                $this->viewBuilder()->setLayout('user');
            }
            
            /******************* Filters *********************/
            
            public function viewtechnologies()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('newtechnologies');
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewnewtechnologies/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewscholar()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('scholarship');
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewscholarship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewintensive()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('intensive_english');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                
                $title = $this->request->data('title');
                
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewintensiveenglish/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewintern()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('internship');
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewinternship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewmentor()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('mentorship');
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewmentorship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewhowworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('howitworks');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                $title = $this->request->data('title');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewhowitworks/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewleader()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('leadership');
                $filter = $this->request->data('filter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewleadership/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                         <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewmachine()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('machine_learning');
                $filter = $this->request->data('filter');
                $clsguide = $this->request->data('clsguide');
                $added_for = $this->request->data('added_for');
                $clse = explode("-",$this->request->data('clsguide'));
                
                $clsfilter = $clse[0]."-".$clse[1];
                $added_for = $this->request->data('added_for');
                $title = $this->request->data('title');
                
                if($clsfilter == "-"){
                    $clsfilter = '';
                }
                
                if($clsfilter != "" && $title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('added_for' => $added_for, 'title' => $title ,'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'pdf', 'added_for' => $added_for, 'title' => $title ,'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'audio', 'added_for' => $added_for, 'title' => $title ,'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'video', 'added_for' => $added_for, 'title' => $title ,'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                       // $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else if($clsfilter != "" && $title == "")
                {
                    if($filter == "newest")
                    {
                         $retrieve_know = $knowledge_table->find('all',array('conditions' => array('added_for' => $added_for, 'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'pdf','added_for' => $added_for, 'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'audio','added_for' => $added_for, 'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find('all',array('conditions' => array('file_type' => 'video','added_for' => $added_for, 'FIND_IN_SET(\''. $clsfilter .'\',classname)')))->order(['id' => 'desc']);
                        //$retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'classname' => $clsfilter])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else if($clsfilter == "" && $title != "")
                {
                    
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for,  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where([ 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewmachinelearning/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                        <b>Classe: </b>'. ucfirst($content->classname) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewstateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('state_exam');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "word")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
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
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <a href="'. $this->base_url.'Schoolknowledge/viewexamstate/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                        <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
						</a>
                    </div>';
                }
                
                return $this->json($res);
            }

            /********************* View Scholarship, Mentorship, Internship, Intensive English *******************/			 

            public function viewintensiveenglish($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('intensive_english');
                $knowcomm_table = TableRegistry::get('intensive_english_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewinternship($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('internship');
                $knowcomm_table = TableRegistry::get('internship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewmentorship($id)
            { 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('mentorship');
                $knowcomm_table = TableRegistry::get('mentorship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewscholarship($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('scholarship');
                $knowcomm_table = TableRegistry::get('scholarship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
            public function viewexamstate($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('state_exam');
                $knowcomm_table = TableRegistry::get('state_exam_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewmachinelearning($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('machine_learning');
                $knowcomm_table = TableRegistry::get('machine_learning_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
                $this->viewBuilder()->setLayout('userswj');
            }
			public function viewnewtechnologies($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('newtechnologies');
                $knowcomm_table = TableRegistry::get('newtechnologies_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewhowitworks($id)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('howitworks');
                $knowcomm_table = TableRegistry::get('howitworks_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
			public function viewleadership($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('leadership');
                $knowcomm_table = TableRegistry::get('leadership_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
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

            /********************* Comment Scholarship, Mentorship, Internship, Intensive English *******************/		

			public function addscholarshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('scholarship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Scholarship Comment Added"  ;
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
            public function addintensivecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('intensive_english_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Intensive English Comment Added"  ;
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
			public function addinternshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('internship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Internship Comment Added"  ;
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
			public function addmentorshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('mentorship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Mentorship Comment Added"  ;
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
			public function addstatecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('state_exam_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "State Exam Comment Added"  ;
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
			public function addmachinecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('machine_learning_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
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
			public function addtechnologiescomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('newtechnologies_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "New Technologies Comment Added"  ;
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
			public function addhowworkscomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('howitworks_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "How it works Comment Added"  ;
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
			public function addleadershipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('leadership_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "school";
                    $comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Leadership Comment Added"  ;
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
            
            /***************** Reply Comments *****************/
            
            public function stateexmreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('state_exam_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "State Exam Reply Comment Added"  ;
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
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
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
			public function technologiesreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('newtechnologies_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "New Technologies Reply Comment Added"  ;
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
            public function howworksreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('howitworks_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "How it works Reply Comment Added"  ;
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
                //return $this->json($res);
            }
			public function leadershipreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('leadership_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "leadership Reply Comment Added"  ;
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
			public function scholarreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('scholarship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Scholarship Reply Comment Added"  ;
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
			public function mentorreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('mentorship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Mentorship Reply Comment Added"  ;
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
			public function internreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('internship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Internship Reply Comment Added"  ;
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
			public function intensereplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('intensive_english_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "school";
					$comments->school_id = $compid;
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Intensive English Reply Comment Added"  ;
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
            
    /***********Mentorship********/
    public function mentorshipcontact()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            //$to = "nancy@outsourcingservicesusa.com";
            $to = "contactus@you-me-globaleducation.org";
            $username =  $this->request->data('name');
            $from =  $this->request->data('email');
            $mentortitle =  explode("~^", $this->request->data('mentor_title'));
            $number =  $this->request->data('number');
            $desc = $this->request->data('desc');
            $mentorid = $mentortitle[0];
            $title = $mentortitle[1];
            
            
            
            $name = "You-Me Global Education";
            $subject = 'Mentorship enquires for You-Me Global Education';
            
            $htmlContent = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                <multiline>
                    This is to inform you that I '.$username.' ,  want to go for further guidance for title - '. $title.'
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>Please find the below details for contact.</multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <p>Thanks</p>
                    <p>'.$username.'</p>
                 </td>
            </tr>';
   

            $message = '<p>This is to inform you that I '.$username.' ,   want to go for further guidance for title - '. $title.' </p>
                    <p>Please find the below details for contact.</p>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>';
            
            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
            $queries_table = TableRegistry::get('you_me_queries');
            $queries = $queries_table->newEntity();
            
            $queries->name =  $username  ;
            $queries->queries_for =  'mentor' ;
            $queries->email_message = $message;
            $queries->title = $title;
            $queries->contact = $number;
            $queries->email	= $from ;
            $queries->description = $desc;
            $queries->created_date = strtotime('now');
            
            $save = $queries_table->save($queries) ;

            if($save)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = ['result' => 'failed'];
            }
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
    
    /***********Scholarship********/
    public function scholarshipcontact()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            //$to = "nancy@outsourcingservicesusa.com";
            $to = "contactus@you-me-globaleducation.org";
            $username =  $this->request->data('sname');
            $from =  $this->request->data('semail');
            $mentortitle =  explode("~^", $this->request->data('scholar_title'));
            $number =  $this->request->data('snumber');
            $desc = $this->request->data('sdesc');
            $mentorid = $mentortitle[0];
            $title = $mentortitle[1];
            
            
            
            $name = "You-Me Global Education";
            $subject = 'Scholarship enquires for You-Me Global Education';
            
            $htmlContent = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                <multiline>
                    This is to inform you that I '.$username.' ,  want to go for further guidance for Scholarship title - '. $title.'
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>Please find the below details for contact.</multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <p>Thanks</p>
                    <p>'.$username.'</p>
                 </td>
            </tr>';
   

            $message = '<p>This is to inform you that I '.$username.' ,   want to go for further guidance for Scholarship title - '. $title.' </p>
                    <p>Please find the below details for contact.</p>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>';
            
            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
            $queries_table = TableRegistry::get('you_me_queries');
            $queries = $queries_table->newEntity();
            
            $queries->name =  $username  ;
            $queries->queries_for =  'scholar' ;
            $queries->email_message = $message;
            $queries->title = $title;
            $queries->contact = $number;
            $queries->email	= $from ;
            $queries->description = $desc;
            $queries->created_date = strtotime('now');
            
            $save = $queries_table->save($queries) ;

            if($save)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = ['result' => 'failed'];
            }
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }        
    /**********internship**********/
    public function internshipcontact()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            //$to = "nancy@outsourcingservicesusa.com";
            $to = "contactus@you-me-globaleducation.org";
            $username =  $this->request->data('iname');
            $from =  $this->request->data('iemail');
            $mentortitle =  explode("~^", $this->request->data('intern_title'));
            $number =  $this->request->data('inumber');
            $desc = $this->request->data('idesc');
            $mentorid = $mentortitle[0];
            $title = $mentortitle[1];
            
            
            
            $name = "You-Me Global Education";
            $subject = 'Internship enquires for You-Me Global Education';
            
            $htmlContent = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                <multiline>
                    This is to inform you that I '.$username.' ,  want to go for further guidance for Internship title - '. $title.'
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>Please find the below details for contact.</multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <p>Thanks</p>
                    <p>'.$username.'</p>
                 </td>
            </tr>';
   

            $message = '<p>This is to inform you that I '.$username.' ,   want to go for further guidance for Internship title - '. $title.' </p>
                    <p>Please find the below details for contact.</p>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>';
            
            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
            $queries_table = TableRegistry::get('you_me_queries');
            $queries = $queries_table->newEntity();
            
            $queries->name =  $username  ;
            $queries->queries_for =  'intern' ;
            $queries->email_message = $message;
            $queries->title = $title;
            $queries->contact = $number;
            $queries->email	= $from ;
            $queries->description = $desc;
            $queries->created_date = strtotime('now');
            
            $save = $queries_table->save($queries) ;

            if($save)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = ['result' => 'failed'];
            }
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }      
    
    /**********internship**********/
    public function leadershipcontact()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            //$to = "nancy@outsourcingservicesusa.com";
            $to = "contactus@you-me-globaleducation.org";
            $username =  $this->request->data('lname');
            $from =  $this->request->data('lemail');
            $mentortitle =  explode("~^", $this->request->data('leader_title'));
            $number =  $this->request->data('lnumber');
            $desc = $this->request->data('ldesc');
            $mentorid = $mentortitle[0];
            $title = $mentortitle[1];
            
            
            
            $name = "You-Me Global Education";
            $subject = 'Leadership and Entrepreneurship enquires for You-Me Global Education';
            
            $htmlContent = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px; padding-top:20px;  text-align:left;">
                <multiline>
                    This is to inform you that I '.$username.' ,  want to go for further guidance for Leadership and Entrepreneurship title - '. $title.'
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>Please find the below details for contact.</multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <multiline>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;">
                    <p>Thanks</p>
                    <p>'.$username.'</p>
                 </td>
            </tr>';
   

            $message = '<p>This is to inform you that I '.$username.' ,   want to go for further guidance for Leadership and Entrepreneurship title - '. $title.' </p>
                    <p>Please find the below details for contact.</p>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Description: '.$desc.' </p>';
            
            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
            $queries_table = TableRegistry::get('you_me_queries');
            $queries = $queries_table->newEntity();
            
            $queries->name =  $username  ;
            $queries->queries_for =  'leader' ;
            $queries->email_message = $message;
            $queries->title = $title;
            $queries->contact = $number;
            $queries->email	= $from ;
            $queries->description = $desc;
            $queries->created_date = strtotime('now');
            
            $save = $queries_table->save($queries) ;

            if($save)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = ['result' => 'failed'];
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
                $tbl = $this->request->data('tbl');
                if($tbl == "knowledgecenter")
                {
                    $knowledge_table = TableRegistry::get('knowledge_center');
                }
                if($tbl == "machinelearning")
                {
                    $knowledge_table = TableRegistry::get('machine_learning');
                }
                if($tbl == "howitworks")
                {
                    $knowledge_table = TableRegistry::get('howitworks');
                }
                if($tbl == "intensive")
                {
                    $knowledge_table = TableRegistry::get('intensive_english');
                }
                if($tbl == "stateexam")
                {
                    $knowledge_table = TableRegistry::get('state_exam');
                }
                //print_r($_POST);
                $filter = $this->request->data('filter');
                
                $added_for = $this->request->data('added_for');
                
                $clsfilter = $this->request->data('clsfilter');
                $title = $this->request->data('title');
                if($clsfilter != "" && $title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'classname' => $clsfilter, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else if($clsfilter == "" && $title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for,  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for, 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    //echo "weh";
                    //print_r($retrieve_know);
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
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
                    $clsse = '';
                    if($content->classname != "") {
                        
                        $clsse = '<b>Classe: </b>'. ucfirst($content->classname);
                    }
                    
                    
                    if($tbl == "knowledgecenter")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                                    <a href="'. $this->base_url.'schoolknowledge/view/'. md5($content['id']) .'" class="viewknow" target="_blank">
                                    '. $img.'
                                    <div class="set_icon">'.$icon.'</div>
                                    <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                                    '.$clsse.'</p>
                                    </a>
                                </div>';
                    }
                    if($tbl == "howitworks")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewhowitworks/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                                    '.$clsse.'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "machinelearning")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewmachinelearning/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                                    '.$clsse.'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "intensive")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewintensiveenglish/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                                    '.$clsse.'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "stateexam")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewexamstate/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</br>
                                    '.$clsse.'</p>
    						</a>
                        </div>';
                    }
                }
                
                return $this->json($res);
            }
            
            public function etitlefilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tbl = $this->request->data('tbl');
                if($tbl == "scholarship")
                {
                    $knowledge_table = TableRegistry::get('scholarship');
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
                
                //$added_for = $this->request->data('added_for');
                
                $clsfilter = $this->request->data('clsfilter');
                $title = $this->request->data('title');
                if($title != "")
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "pdf")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "audio")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio',  'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                    elseif($filter == "video")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'title' => $title])->order(['id' => 'desc'])->toArray() ;
                    }
                }
                else
                {
                    if($filter == "newest")
                    {
                        $retrieve_know = $knowledge_table->find()->where(['added_for' => $added_for])->order(['id' => 'desc'])->toArray() ;
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
                    if($tbl == "scholarship")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewscholarship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                            <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "leadership")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewleadership/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                             <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "mentorship")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewmentorship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                             <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "internship")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewinternship/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                             <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
    						</a>
                        </div>';
                    }
                    if($tbl == "newtechnologies")
                    {
                        $res .= '<div class="col-sm-2 col_img">
                            <a href="'. $this->base_url.'Schoolknowledge/viewnewtechnologies/'. md5($content['id']) .'" class="viewknow" target="_blank">'.
                            $img.'
                            <div class="set_icon">'.$icon.'</div>
                             <p class="title" style="color:#000"><b>Titre</b>: '. ucfirst($content->title) .'</p>
    						</a>
                        </div>';
                    }
                }
                
                return $this->json($res);
            }
            
}

  


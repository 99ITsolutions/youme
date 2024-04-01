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

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MeetingLinkController   extends AppController
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
                $subjects_table =  TableRegistry::get('subjects');
                $class_table = TableRegistry::get('class');
                $tid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($tid)) {
                $employee_table = TableRegistry::get('employee');
				$retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->toArray();
				
				
        		
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
                    

                ])->where([ 'md5(employee.id)' => $tid, 'employee.status' => 1 ])->toArray();

				$this->set("employees_details", $retrieve_empclses); 
				$this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;  
                }
            }
            
            public function meetingroom()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$this->viewBuilder()->setLayout('user');
            }
            
            public function updatemeetingsts()
            {
                $id = $this->request->data('id');
                $link = $this->request->data('link');
                $meetinglink_table = TableRegistry::get('meeting_link');
                $emp_table = TableRegistry::get('employee');
                
                $retrieve_links = $meetinglink_table->find()->where([ 'id' => $id ])->first();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                $urlLogout = 'https://you-me-globaleducation.org/school/conference/meetingleft/'.$meetingID;
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
                    $retrieve_teacher = $emp_table->find()->where([ 'id' => $tchrid ])->first();
                    $fname = implode("+", explode(" ", $retrieve_teacher['f_name']));
                    $lname = implode("+", explode(" ", $retrieve_teacher['l_name']));
                    $tchrnames = trim($fname).trim($lname);
    
                    $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');
    
                    $tchrname = strtr($tchrnames, $accents);
                    
                    $update = $meetinglink_table->query()->update()->set(['meeting_status' => 1, 'meeting_id' => $meetingID])->where([ 'id' => $id  ])->execute();
                    if($update)
                    {
                        
                        $password ='222' ;
                        $name= $tchrname;
                        $name = str_replace("+"," ", $name);
                        $this->Cookie->write('meetingid', $meetingID,  time()+1000000000000000 );
                        $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
                        $joinMeetingParams->setRedirect(true);
                        
                        $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                        
                        $res['data'] = $url;
                    }
                    else
                    {
                        $res['data'] = "failed";
                    }
                }
                
                return $this->json($res);
                
                /*$id = $this->request->data('id');
                $link = $this->request->data('link');
                $meetinglink_table = TableRegistry::get('meeting_link');
                $emp_table = TableRegistry::get('employee');
                $secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
                $retrieve_links = $meetinglink_table->find()->where([ 'id' => $id ])->first();
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tchrid = $retrieve_links['teacher_id'];
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
                
                
                $retrieve_teacher = $emp_table->find()->where([ 'id' => $tchrid ])->first();
                
                $fname = implode("+", explode(" ", $retrieve_teacher['f_name']));
                $lname = implode("+", explode(" ", $retrieve_teacher['l_name']));
                $tchrnames = trim($fname).trim($lname);
                $exresult = explode(" ", $result);

                $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');

                $tchrname = strtr($tchrnames, $accents);
                
                
                
                $exresult1 = explode("YOUME", $exresult[0]);
                $ress = explode("<returncode>", $exresult1[0]); 
                
                $createresult = explode("</returncode>", $ress[1]); 
                
                
                if($exresult1[0] == "SUCCESS" || $createresult[0] == "SUCCESS")
                {
                    $update = $meetinglink_table->query()->update()->set(['meeting_status' => 1, 'meeting_id' => $meeting_id])->where([ 'id' => $id  ])->execute();
                    if($update)
                    {
                        $this->Cookie->write('meetingid', $meeting_id,  time()+1000000000000000 );
                        $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                        $string4 = "joinmeetingID=".$meeting_id."&password=222&fullName=".$tchrname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
                        $sh4 = sha1($string4);
                        $res['data'] = "success";
                        $res['checksumm'] = $sh4;
                        $res['tchrname'] = $tchrname;
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
                return $this->json($res);*/
            }
            
            public function callback()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $meetingID = $this->request->query('meetingID');
                $meetinglink_table = TableRegistry::get('meeting_link');
                $update = $meetinglink_table->query()->update()->set(['meeting_status' => 2])->where([ 'id' => $meetingID  ])->execute();
                
            }
            
            public function linklist($classid, $subjectid)
            {
                $tid = $this->Cookie->read('tid');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($tid))
                {
                    $meetinglink_table = TableRegistry::get('meeting_link');
                    $subject_table = TableRegistry::get('subjects');
                    $class_table = TableRegistry::get('class');
                    
                    $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
                    $retrieve_sub = $subject_table->find()->where(['id' => $subjectid ])->first();
                    
                    $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']. "(".$retrieve_cls['school_sections'].")";	
                    $subjectname = $retrieve_sub['subject_name'];
                    
                    $retrieve_links = $meetinglink_table->find()->where([ 'md5(teacher_id)' => $tid, 'class_id' => $classid, 'subject_id' => $subjectid ])->order(['id' => 'desc'])->toArray();
                    $this->set("link_details", $retrieve_links); 
                    $this->set("classname", $classname); 
                    $this->set("subjectname", $subjectname); 
                    $this->set("subjectid", $subjectid); 
                    $this->set("classid", $classid); 
    				$this->viewBuilder()->setLayout('user');
                }
                else
                {
                     return $this->redirect('/login/') ;  
                }
            }
            
            
            
            public function generatemeeting(){  
                
                if ( $this->request->is('post') && $this->request->is('ajax') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $schoolid = $this->request->session()->read('company_id');
                    $tid = $this->Cookie->read('tid');
                    $meetinglink_table = TableRegistry::get('meeting_link');
                    $notify_table = TableRegistry::get('notification'); 
                    $class_table = TableRegistry::get('class');  
                    $subject_table = TableRegistry::get('subjects'); 
                    if(!empty($tid)) 
                    {
                        $employee_table = TableRegistry::get('employee');
                        $retrieve_employees = $employee_table->find()->where([ 'md5(id)' => $tid, 'status' => 1 ])->first();
                        $teacherid = $retrieve_employees['id'];
                        $lang = $this->Cookie->read('language');
                        if($lang != "")
                        {
                            $lang = $lang;
                        }
                        else
                        {
                            $lang = 2;
                        }
                        
                        //echo $lang;
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
                            if($langlbl['id'] == '1940') { $schdateexpiryless = $langlbl['title'] ; } 
                        } 
                        
                        
                        $session_id = $this->Cookie->read('sessionid');
                        
                        $activ_table = TableRegistry::get('activity');
                        
                        $meeting = $meetinglink_table->newEntity();
                        $meeting->class_id = $this->request->data('classid');
                        $meeting->subject_id = $this->request->data('subjectid');
                        $meeting->schedule_date = $this->request->data('start_date');
                        $meeting->schedule_datetime = strtotime($this->request->data('start_date'));
                        $meeting->generate_for = $this->request->data('link_for');
                        $meeting->meeting_name = implode("+", explode(" ",$this->request->data('meeting_name')));
                        $meeting->meeting_id = $this->request->data('meeting_id');
                        $meeting->status = 0;
                        $meeting->teacher_id = $teacherid;
                        $meeting->school_id = $schoolid;
                        $meeting->session_id = $session_id;
                        $meeting->created_date = time();
                        $meeting->expirelink_datetime = strtotime($this->request->data('end_date'));
                               
                        $st = strtotime($this->request->data('start_date'));
                        $et = strtotime($this->request->data('end_date'));
                        if($st < $et)       
                        {
                            if($saved = $meetinglink_table->save($meeting) )
                            {     
                                $strucid = $saved->id;
                                
                                $ret_sub = $subject_table->find()->where(['id'=> $this->request->data('subjectid')])->first();
                                $subname = $ret_sub['subject_name'];
                                
                                /*$title = "Notification for Online You-me Live Class of Subject - ".$subname;
                                $desc = "<p>Cher élève,</p>
                                <p>This is to inform you that meeting is generated for online class.</p>
                                <p>Meeting Name ".$this->request->data('meeting_name') .".</p>
                                <p>Subject ".$subname .".</p>
                                <p>Start Date & Timings:".date("d-m-Y h:i A", strtotime($this->request->data('start_date')))."</p>
                                <p>Please Join it.</p>
                                <p>Merci pour votre diligence. </p>
                                </p>Cordialement.</p>";
                                date_default_timezone_set('Africa/Kinshasa');
                                $notify = $notify_table->newEntity();
                                $notify->title = $title;
                                $notify->description = $desc;
                                $notify->notify_to = 'students';
                                $notify->created_date =  time();
                                $notify->added_by = 'teachers';
                                $notify->teacher_id = $teacherid;
                                $notify->status = '1';
                                
                                $notify->class_ids = $this->request->data('classid');
                                $notify->class_opt = 'multiple';
                                $scdate = date("d-m-Y H:i",time());
                                $notify->schedule_date = $scdate;
                                $notify->sent_notify = '1';
                                $notify->sc_date_time = time();
                                
                                $saved = $notify_table->save($notify);*/
                                
                                $activity = $activ_table->newEntity();
                                $activity->action =  "Link Generated Successfully!"  ;
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
                            $res = [ 'result' => $schdateexpiryless  ];
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
            
            
           
        public function edittut(){  
            if ($this->request->is('ajax') && $this->request->is('post') ){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $schoolid = $this->request->session()->read('company_id');
                $tid = $this->Cookie->read('tid');
                $tutorial_fee_table = TableRegistry::get('tutorial_fee');
                $activ_table = TableRegistry::get('activity');
            if(!empty($tid)) {    
                $retrieve_fee = $tutorial_fee_table->find()->select(['id' ])->where(['class_id' => $this->request->data('class') ,'subject_id' => $this->request->data('subjects') , 'md5(teacher_id)' => $tid , 'id IS NOT' => $this->request->data('eid') , 'school_id' => $schoolid  ])->count() ;

                if($retrieve_fee == 0 )
                {   
                    $id = $this->request->data('eid');
                    $class_id = $this->request->data('class');
                    $frequency = $this->request->data('frequency');
                    $amount = $this->request->data('amount');
                    $session = $this->request->data('start_year');
                    $frequency = $this->request->data('frequency');
                    
                        if( $tutorial_fee_table->query()->update()->set(['class_id' => $this->request->data('class') ,'subject_id' => $this->request->data('subjects') , 'fee' => $amount, 'frequency' => $frequency, 'session_id' => $session])->where([ 'id' => $id  ])->execute())
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fee updated successfully"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($id); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;

                            if($save)
                            {   
                                $res = [ 'result' => 'success', 'feesid' =>  $id   ];
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
                    $res = [ 'result' => 'exist'  ];
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
        $rid = $this->request->data('val') ;
        $meetinglink_table = TableRegistry::get('meeting_link');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
            
        $del = $meetinglink_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
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
            
              
}

  


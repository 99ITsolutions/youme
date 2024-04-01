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
class SchoolMeetController extends AppController
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
        $iid = $this->Cookie->read('id');
        $sid = $this->Cookie->read('sid');
        $subadmin = $this->Cookie->read('subid');
        $schoolid = $this->request->session()->read('company_id');
        
        $meetinglink_table = TableRegistry::get('school_meet');
        $retrieve_links = $meetinglink_table->find()->where([ 'school_id' => $schoolid]);
        if($subadmin != ''){
            $retrieve_links->where(['subadmin_id' => '']);
            $retrieve_links->orWhere(['subadmin_id' => $subadmin]);
        }
        $retrieve_links->order(['created_date' => 'desc'])->orderDesc('created_date')->toArray();
        
        $user_table = TableRegistry::get('company');//TableRegistry::get('users');
        $retrieve_user = $user_table->find()->where([ 'id' => $schoolid ])->first();
        $this->set("retrieve_user", $retrieve_user); 
        $this->set("link_details", $retrieve_links); 
        
        $this->set("iid", $iid); 
        $this->set("sid", $sid); 
        $this->set("subadmin", $subadmin); 
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$this->viewBuilder()->setLayout('user');
    }
            
    public function generatemeeting(){  
        
        if ( $this->request->is('post') && $this->request->is('ajax') )
        {
            $schoolid = $this->request->session()->read('company_id');
            $subadmin = $this->Cookie->read('subid');
            $meetinglink_table = TableRegistry::get('school_meet');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $meeting = $meetinglink_table->newEntity();
            $meeting->meeting_name = implode("+", explode(" ",$this->request->data('meeting_name')));
            $meeting->meeting_id = $this->request->data('meeting_id');
            $meeting->status = 1;
            if($subadmin != ''){
                $meeting->subadmin_id = $subadmin;
            }
            $meeting->school_id = $schoolid;
            $meeting->created_date = time();
                               
            if($saved = $meetinglink_table->save($meeting) )
            {     
                $strucid = $saved->id;
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
            $res = [ 'result' => 'invalid operation'  ];

        }

        return $this->json($res);
    }

    public function delete()
    {
        $rid = $this->request->data('val') ;
        $meetinglink_table = TableRegistry::get('school_meet');
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
    
    public function updatemeetingsts()
    {
        $schoolid = $this->request->session()->read('company_id');
        $id = $this->request->data('id');
        //$sid = $this->Cookie->read('sid');
        $meetinglink_table = TableRegistry::get('school_meet');
        $user_table = TableRegistry::get('company');//TableRegistry::get('users');
        $retrieve_user = $user_table->find()->where([ 'id' => $schoolid ])->first();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $fname = implode("+", explode(" ", $retrieve_user['comp_name']));
        $lname = implode("+", explode(" ", $retrieve_user['comp_name1']));
        $usernames = trim($fname).trim($lname);
        
        $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');

        $username = strtr($usernames, $accents);
        $retrieve_links = $meetinglink_table->find()->where([ 'id' => $id ])->first();

        $BBB_SECRET = 'ILsPVi1WZGMeThb0saOns0441E9HhqxyjjuJzWE2Eky';
        $BBB_URL = 'https://meeting.you-me-globaleducation.org/bigbluebutton/ymge/';
        $bbb = new BigBlueButton($BBB_URL,$BBB_SECRET);
        $meetingID = $retrieve_links['meeting_id'];
        $meetingName = $meetingID."-".$retrieve_links['meeting_name'];
        
        $meetingName = str_replace("+"," ", $meetingName);
        //print_r($meetingName);exit;
        $attendee_password = '321';
        $moderator_password = '123';
        $duration = '30';
        $urlLogout = 'https://you-me-globaleducation.org/school/schoolconference/endmeeting/'.$meetingID;
        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setEndCallbackUrl($urlLogout);
        $createMeetingParams->setLogoutUrl($urlLogout);
        /*if ($isRecordingTrue) {
        	$createMeetingParams->setRecord(true);
        	$createMeetingParams->setAllowStartStopRecording(true);
        	$createMeetingParams->setAutoStartRecording(true);
        }*/
        $response = $bbb->createMeeting($createMeetingParams);
        
        if ($response->getReturnCode() == 'FAILED') {
        	$res['data'] = "failed";
        } else{
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 1])->where([ 'id' => $id  ])->execute();
            if($update)
            {
                $password ='123' ;
                $name= $username;
                $name = str_replace("+"," ", $name);
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
    
    }
    
     public function deleteallmeetings()
        {
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data['val'] ; 
            $meetinglink_table = TableRegistry::get('school_meet');
            foreach($uid as $ids)
            {
                $stats = $meetinglink_table->query()->delete()->where([ 'id' => $ids ])->execute();
            }
        
            if($stats)
            {
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = [ 'result' => 'not delete'  ];
            }    
            

            return $this->json($res);
        }
            
              
}

  


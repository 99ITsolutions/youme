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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MeetController extends AppController
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
        $meetinglink_table = TableRegistry::get('meet');
        $retrieve_links = $meetinglink_table->find()->order(['created_date' => 'desc'])->orderDesc('created_date')->toArray();
        $this->set("link_details", $retrieve_links); 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
		$this->viewBuilder()->setLayout('usersa');
    }
            
    public function generatemeeting(){  
        
        if ( $this->request->is('post') && $this->request->is('ajax') )
        {
            $meetinglink_table = TableRegistry::get('meet');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $meeting = $meetinglink_table->newEntity();
            $meeting->meeting_name = implode("+", explode(" ",$this->request->data('meeting_name')));
            $meeting->meeting_id = $this->request->data('meeting_id');
            $meeting->status = 1;
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
        $meetinglink_table = TableRegistry::get('meet');
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
        $id = $this->request->data('id');
        $sid = $this->Cookie->read('sid');
        $meetinglink_table = TableRegistry::get('meet');
        $user_table = TableRegistry::get('users');
        $retrieve_user = $user_table->find()->where([ 'md5(id)' => $sid ])->first();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $fname = implode("+", explode(" ", $retrieve_user['fname']));
        $lname = implode("+", explode(" ", $retrieve_user['lname']));
        $usernames = trim($fname).trim($lname);
        //$username_encode = base64_encode($username);
        
        $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');

        $username = strtr($usernames, $accents);
        
        
        $secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
        $retrieve_links = $meetinglink_table->find()->where([ 'id' => $id ])->first();
        
        //echo $retrieve_links['meeting_name']; die;
        $meeting_id =   $retrieve_links['meeting_id'];
        $meeting_name = $meeting_id."-".$retrieve_links['meeting_name'];
        //$meeting_id = "YOUME".uniqid();
        $logo = "https://you-me-globaleducation.org/You-Me-live.png";
        //$logout = urlencode("https://you-me-globaleducation.org/ConferenceMeet/callback?meetingID=".$meeting_id);
        $logout = urlencode("https://you-me-globaleducation.org/video/callback.php?meetingID=".$meeting_id);
        
        $string = "createname=".$meeting_name."&meetingID=".$meeting_id."&meta_endCallbackUrl=".$logout."&attendeePW=321&moderatorPW=123&logo=".$logo.$secret;
        $sh = sha1($string);
    
        $url ='https://meeting.you-me-globaleducation.org/bigbluebutton/api/create?name='.$meeting_name.'&meetingID='.$meeting_id.'&meta_endCallbackUrl='.$logout.'&attendeePW=321&moderatorPW=123&logo='.$logo.'&checksum='.$sh;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = curl_exec($ch);
        curl_close($ch);
        
        
        $exresult = explode(" ", $result);
        //echo $exresult[0]; 
        
        $exresult1 = explode("YOUME", $exresult[0]);
        $ress = explode("<returncode>", $exresult1[0]); 
        
        $createresult = explode("</returncode>", $ress[1]); 
        
        
        if($exresult1[0] == "SUCCESS" || $createresult[0] == "SUCCESS")
        {
            //echo "hi";
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 1])->where([ 'id' => $id  ])->execute();
            if($update)
            {
                $this->Cookie->write('meetingid', $meeting_id,  time()+1000000000000000 );
                $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
                $string4 = "joinmeetingID=".$meeting_id."&password=123&fullName=".$username."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
                $sh4 = sha1($string4);
                $res['data'] = "success";
                $res['checksumm'] = $sh4;
                $res['tchrname'] = $username;
                $res['meetingID'] = $meeting_id;
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
    
     public function deleteallmeetings()
        {
			$this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data['val'] ; 
            $meetinglink_table = TableRegistry::get('meet');
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

  


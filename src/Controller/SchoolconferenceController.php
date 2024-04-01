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
use BigBlueButton\Parameters\IsMeetingRunningParameters;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SchoolconferenceController   extends AppController
{

    public function index()
    {  
        $this->viewBuilder()->setLayout('conference');
    }

    public function joinmeeting()
    {
        /*setcookie("id", "", time() - 3600);  
        setcookie("tid", "", time() - 3600);  
        setcookie("subid", "", time() - 3600);  
        setcookie("sid", "", time() - 3600); */ 
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            
            $name = $this->request->data('name');
            $meeting_id = $this->request->data('mid');
            
            $meet = TableRegistry::get('school_meet');
            $meet_detail = $meet->find()->where(['meeting_id' => $meeting_id ])->first();
             
            if(!empty($meet_detail)){
                //print_r();exit;
                if($meet_detail->meeting_status == 0){
                    $res = [ 'result' => 0  ];
                }else if($meet_detail->meeting_status == 2){
                    $res = [ 'result' => 2  ];
                }else{
                    
                    $BBB_SECRET = 'ILsPVi1WZGMeThb0saOns0441E9HhqxyjjuJzWE2Eky';
                    $BBB_URL = 'https://meeting.you-me-globaleducation.org/bigbluebutton/ymge/';
                    $bbb = new BigBlueButton($BBB_URL,$BBB_SECRET);
                    
                    $isMeetingRunningParams = new IsMeetingRunningParameters($meeting_id);
                    
                    $result = $url = $bbb->isMeetingRunning($isMeetingRunningParams); //$bbb->isMeetingRunning(new IsMeetingRunningParameters($this->faker->uuid));
                    //print_r();exit;
                    if($result->isRunning()){
                        $user_name = explode(" ",$name);
                        foreach($user_name as $name){
                        $full_name .= $name.'+'; 
                        }
                        $fname =  substr($full_name, 0, -1);
                        $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');
                        $f_name = strtr($fname, $accents);
                        
                        
                        $meetingID = $meeting_id;
                        $meetingName = $meeting_id;//.'-'.$retrieve_links['meeting_name'];
                        $password ='321' ;
                        $name= $f_name;
                        $name = str_replace("+"," ", $name);
                        $joinMeetingParams = new JoinMeetingParameters($meeting_id, $name, $password);
                        $joinMeetingParams->setRedirect(true);
                        
                        $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                        
                        $res = [ 'result' => $url  ];
                    }else{
                        $res = [ 'result' => 0  ];
                    }
                   // $this->redirect($url);
                }
            }else{
                $res = [ 'result' => 'failed'  ];
            }       
            
        }
        else{
            $res = [ 'result' => 'invalid'  ];
        }
        return $this->json($res);
    }
    
    
    public function meetingleft($meetingID)
    {
        $sid =$this->request->session()->read('student_id');
        $tid = $this->Cookie->read('tid'); 
        $meetinglink_table = TableRegistry::get('meeting_link');
        $retrieve_links = $meetinglink_table->find()->where([ 'meeting_id' => $meetingID ])->first();
            
        if($sid != ''){
            $this->redirect('https://you-me-globaleducation.org/school/meetings/links/'.$retrieve_links['class_id'].'/'.$retrieve_links['subject_id']);
        }else if($tid != ''){
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 2])->where([ 'meeting_id' => $meetingID])->execute();
            $this->redirect('https://you-me-globaleducation.org/school/meetingLink/linklist/'.$retrieve_links['class_id'].'/'.$retrieve_links['subject_id']);
        }else{
            $this->redirect('https://you-me-globaleducation.org');
        }
        //print_r("ajjsjs");exit;
        
    }
    
    public function endmeeting($meetingID)
    {
        $userid = $this->request->session()->read('company_id');
        
        $meetinglink_table = TableRegistry::get('school_meet');
        $retrieve_links = $meetinglink_table->find()->where([ 'meeting_id' => $meetingID ])->first();
        
        if($userid != ''){
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 2])->where([ 'meeting_id' => $meetingID])->execute();
            //print_r($update);exit;
            $this->redirect('https://you-me-globaleducation.org/school/school-meet/');
        }else{
            $this->redirect('https://you-me-globaleducation.org/');
        }
    }
    
    public function kmeetingleft($meetingID)
    {
        $sid =$this->request->session()->read('student_id');
        $tid = $this->Cookie->read('tid'); 
        $meetinglink_table = TableRegistry::get('kinder_virtualclass');
        $retrieve_links = $meetinglink_table->find()->where([ 'meeting_id' => $meetingID ])->first();
            
        if($sid != ''){
            $this->redirect('https://you-me-globaleducation.org/school/kinderdashboard/virtualclass/');
        }else if($tid != ''){
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 2])->where([ 'meeting_id' => $meetingID])->execute();
            $this->redirect('https://you-me-globaleducation.org/school/Teacherkindergarten/virtualclass/');
        }else{
            $this->redirect('https://you-me-globaleducation.org');
        }
        //print_r("ajjsjs");exit;
        
    }
    
    public function kendmeeting($meetingID)
    {
        $userid = $this->request->session()->read('users_id');
        
        $meetinglink_table = TableRegistry::get('kinder_virtualclass');
        $retrieve_links = $meetinglink_table->find()->where([ 'meeting_id' => $meetingID ])->first();
        
        if($userid != ''){
            $update = $meetinglink_table->query()->update()->set(['meeting_status' => 2])->where([ 'meeting_id' => $meetingID])->execute();
            //print_r($update);exit;
            $this->redirect('https://you-me-globaleducation.org/school/Teacherkindergarten/virtualclass/');
        }else{
            $this->redirect('https://you-me-globaleducation.org/');
        }
        
    }
            
              
}

  


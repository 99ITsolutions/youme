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
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MeetingsController   extends AppController
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
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
        $student_table = TableRegistry::get('student'); 
        $class_subjects_table = TableRegistry::get('class_subjects');
        $subject_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        if(!empty($stid)) 
        {    
            $retrieve_stud = $student_table->find()->where([ 'md5(id)' => $stid ])->first();
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $classid = $retrieve_stud['class'];
            $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
            $retrieve_clssub = $class_subjects_table->find()->where([ 'class_id' => $classid ])->first();
            $subjectid =  $retrieve_clssub['subject_id'];
            $subids = explode(",", $retrieve_clssub['subject_id']);
            $subjectname = [];
            foreach($subids as $sub) {
                $retrieve_sub = $subject_table->find()->where(['id' => $sub ])->first();
                $subjectname[] = $retrieve_sub['subject_name'];
            }
            
            $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section'];
            $subjects = implode(",", $subjectname);
            
            $this->set("classname", $classname); 
            $this->set("subjectname", $subjects); 
            $this->set("subjectid", $subjectid); 
            $this->set("classid", $classid); 
            
            $sctns = strtolower($retrieve_cls['school_sections']);
            if(($sctns == "creche") || ($sctns == "maternelle"))
            {
    		    $this->viewBuilder()->setLayout('kinder');
            }
            else
            {
    		    $this->viewBuilder()->setLayout('user');
            }
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function links($classid, $subjectid)
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $meetinglink_table = TableRegistry::get('meeting_link');
        $subject_table = TableRegistry::get('subjects');
        $class_table = TableRegistry::get('class');
        
        $retrieve_cls = $class_table->find()->where([ 'id' => $classid ])->first();
        $retrieve_sub = $subject_table->find()->where(['id' => $subjectid ])->first();
        
        $classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']. "(". $retrieve_cls['school_sections'].")";
        $subjectname = $retrieve_sub['subject_name'];
        
        $retrieve_links = $meetinglink_table->find()->where([ 'generate_for' => 'Class', 'class_id' => $classid, 'subject_id' => $subjectid ])->toArray();
        //print_r($retrieve_links); die;
        $this->set("link_details", $retrieve_links); 
        $this->set("classname", $classname); 
        $this->set("subjectname", $subjectname); 
        $this->set("subjectid", $subjectid); 
        $this->set("classid", $classid); 
        $sctns = strtolower($retrieve_cls['school_sections']);
        if(($sctns == "creche") || ($sctns == "maternelle"))
        {
		    $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
		    $this->viewBuilder()->setLayout('user');
        }
    }

    public function getmeetingsts()
    {
        $id = $this->request->data('id');
        $meetinglink_table = TableRegistry::get('meeting_link');
        $student_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $studid = $_SESSION['student_id'];
        $getstud = $student_table->find()->where([ 'id' => $studid  ])->first();
        $getdtaa = $meetinglink_table->find()->where([ 'id' => $id  ])->first();
        
        $BBB_SECRET = 'ILsPVi1WZGMeThb0saOns0441E9HhqxyjjuJzWE2Eky';
        $BBB_URL = 'https://meeting.you-me-globaleducation.org/bigbluebutton/ymge/';
        $bbb = new BigBlueButton($BBB_URL,$BBB_SECRET);
        $meetingID = $getdtaa['meeting_id'];
        
        $isMeetingRunningParams = new IsMeetingRunningParameters($meetingID);
        $result = $url = $bbb->isMeetingRunning($isMeetingRunningParams); 
        if($result->isRunning()){
        
            $fname = implode("+", explode(" ", $getstud['f_name']));
            $lname = implode("+", explode(" ", $getstud['l_name']));
            $studnames = trim($fname).trim($lname);
            
             $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');
    
            $studname = strtr($studnames, $accents);
            
            
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
        
        $meetingsts['studname'] = $studname;
        $meetingsts['meetingID'] = $getdtaa['meeting_id'];
        $meetingsts['url'] = $url;
        
        $this->Cookie->write('meetingid', $getdtaa['meeting_id'],  time()+1000000000000000 );
        
        return $this->json($meetingsts);
        
        /*$id = $this->request->data('id');
        $meetinglink_table = TableRegistry::get('meeting_link');
        $student_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $studid = $_SESSION['student_id'];
        $getstud = $student_table->find()->where([ 'id' => $studid  ])->first();
        $getdtaa = $meetinglink_table->find()->where([ 'id' => $id  ])->first();
        
        $fname = implode("+", explode(" ", $getstud['f_name']));
        $lname = implode("+", explode(" ", $getstud['l_name']));
        $studnames = trim($fname).trim($lname);
        
         $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');

        $studname = strtr($studnames, $accents);
        
        $meetingsts['data'] = $getdtaa['meeting_status'];
        $meetingsts['studname'] = $studname;
        $meetingsts['meetingID'] = $getdtaa['meeting_id'];
        
        $secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
        $style = "https://you-me-globaleducation.org/ConferenceMeet/css/bbb.css";
        $string4 = "joinmeetingID=". $getdtaa['meeting_id']."&password=111&fullName=".$studname."&userdata-bbb_display_branding_area=true&userdata-bbb_show_public_chat_on_login=false&userdata-bbb_custom_style_url=".$style.$secret;
        $sh4 = sha1($string4);
        $meetingsts['checksumm'] = $sh4;
        
        $this->Cookie->write('meetingid', $getdtaa['meeting_id'],  time()+1000000000000000 );
        
        return $this->json($meetingsts);*/
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

  


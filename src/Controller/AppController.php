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


use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    Public $base_url;
    

    public function initialize()
    {
        //return $this->redirect('/login');     
        parent::initialize();
        $this->base_url = Router::url("/",false);
        $this->full_url = Router::url("/",true);
        $this->getfull_url = Router::url(null, true);
        
        $controller = $this->request->getParam('controller');
        $action = $this->request->getParam('action');
        
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
	     date_default_timezone_set('Africa/Kinshasa');
        error_reporting(0);
        $www = $this->Cookie->read('www');
        $logtype = $this->Cookie->read('logtype');
        $session_id = $this->Cookie->read('sessionid');
        $meetingid = $this->Cookie->read('meetingid'); 
        
        //parentid
        $pid = $this->Cookie->read('pid'); 
        //superadminid
        $sid = $this->Cookie->read('sid'); 
        //schoolid
        $uid = $this->Cookie->read('id');
        //school subadmin id
        $subid = $this->Cookie->read('subid');
        //studentid
        $stid = $this->Cookie->read('stid');
        //teacherid
        $tid = $this->Cookie->read('tid');
        //canteen vendor id
        $cid = $this->Cookie->read('cid');
        $activitiesid = $this->Cookie->read('activitiesid'); 
        $lang = $this->Cookie->read('language');
        $superid = $this->Cookie->write('superid',  $sid,   time()+3600);
        //print_r($_COOKIE); die;
        //print_r($_SESSION); die(); 
        if($lang != "")
        {
            $lang = $lang;
        }
        else
        {
            $lang = 2;
        }
        
        /*if($controller == "Canteenvendors" && empty($sid))
        {
            //print_r($_SESSION); die();
            $sid = $superid;
        }*/
        $logtrack_table = TableRegistry::get('logged_tracking');
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
        
        $this->set("langua", $lang); 
        $this->set("lang_label", $retrieve_langlabel); 
        $controllerName = $this->request->getParam('controller');
        $actionName = $this->request->getParam('action');
        $this->set("baseurl", $this->base_url);
        $this->set("fullurl", $this->full_url);
        $baseurl= $this->base_url;
        
        foreach($retrieve_langlabel as $langlbl) 
        { 
            if($langlbl['id'] == '1224') { $studdash = $langlbl['title'] ; } 
            if($langlbl['id'] == '2374') { $cantdash = $langlbl['title'] ; } 
            if($langlbl['id'] == '2396') { $cantvdash = $langlbl['title'] ; } 
        }
       
        if( (($uid == "" || $uid == null) && ($cid == "" || $cid == null) && ($tid == "" || $tid == null) && ($pid == "" || $pid == null) && ($stid == "" || $stid == null) && ($subid == "" || $subid == null) && ($sid == "" || $sid == null)) &&  $controllerName != "Login" &&  $controllerName != "Conference" &&  $controllerName != "Schoolconference" && ($controllerName != "Pages" && $actionName != "form" && $actionName != "submitform" )   ) 
        {
            //echo "ewf"; die;
            setcookie('id', '', time()-1000  , $baseurl );
            return $this->redirect('/login');      
        }
        elseif($sid != "")
        {
            if($uid != "" && $sid != "")
            {
                $users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$emp_table = TableRegistry::get('employee');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
            	
            	$retrieve_users = $users_table->find()->select(['id' ,'fname' ,'lname' , 'phone'  , 'password'  , 'email', 'sesscode',  'picture','privilages', 'role', 'menus_privilages' ])->where(['md5(id)' => $sid ]) ; 
            	$user_details = $retrieve_users->toArray() ; 
         
            	$this->request->session()->write('users_id', $user_details[0]['id']);
            	
            	$retrieve_company = $company_table->find()->select(['id' ,'comp_no' ,'comp_name', 'scl_privilages' , 'ph_no'  , 'password' , 'email' ,'site_title' , 'sesscode' ,'user_name','logout_url' ,'comp_logo' , 'favicon', 'prv_cat'  , 'www' ,'comp_logo1', 'primary_color' , 'button_color' ])->where(['md5(id)' => $uid ]) ; 
    			$company_details = $retrieve_company->toArray() ;  
    		
    			$this->request->session()->write('company_id', $company_details[0]['id']);
    			$this->request->session()->write('slugs', $company_details[0]['logout_url']);
    			$this->request->session()->write('baseurl', $this->base_url);
    			$this->request->session()->write('loginuser', "superadmin");
                
                if(!empty($uid)) {
                    $controller = $this->request->getParam('controller');
                    $action = $this->request->getParam('action');
                    
                    if(strtolower($controller) == "schooldashboard" && $action == "index")
                    {
                        $control = "School Dashboard";
                    }
                    else
                    {
                        $control = ucfirst($controller);
                    }
    
                    if($action == "index")
                    {
                        $method = "";
                    }
                    else
                    {
                        $method = " (".ucfirst($action).")";
                    }
                    $trackmenu = $control.$method;
                    $tracks = 1;
                    if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logout") 
                    {
                        $tracks = 0;
                    }
                    
                    if($tracks == 1)
                    {
                        $trackid = $_SESSION['scl_tracklogintime']; 
                        $track = $logtrack_table->newEntity();
                        $track->type =  "school"  ;
                        $track->login_id =  $company_details[0]['id'] ;
                        $track->track_id =  $trackid;
                        $track->school_id = $company_details[0]['id'];
                        $track->menu_tracking = $control.$method;
                        $track->fullurl = $this->getfull_url; 
                        $track->time = time();
                        $saved = $logtrack_table->save($track) ;
                    }
                }
                
                if(!empty($sid)) {
                    $controller = $this->request->getParam('controller');
                    $action = $this->request->getParam('action');
                    
                    if(strtolower($controller) == "dashboard" && $action == "index")
                    {
                        $control = "Superadmin Dashboard";
                    }
                    else
                    {
                        $control = ucfirst($controller);
                    }
    
                    if($action == "index")
                    {
                        $method = "";
                    }
                    else
                    {
                        $method = " (".ucfirst($action).")";
                    }
                    $trackmenu = $control.$method;
                    $tracks = 1;
                    if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logouta") 
                    {
                        $tracks = 0;
                    }
                    
                    if($tracks == 1)
                    {
                        $trackid = $_SESSION['supersub_tracklogintime']; 
                        $track = $logtrack_table->newEntity();
                        $track->type =  "super_subadmin"  ;
                        $track->login_id =  $user_details[0]['id'] ;
                        $track->track_id =  $trackid;
                        //$track->school_id = $company_details[0]['id'];
                        $track->menu_tracking = $control.$method;
                        $track->fullurl = $this->getfull_url; 
                        $track->time = time();
                        $saved = $logtrack_table->save($track) ;
                    }
                }
                
    			$retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear' ])->order(['startyear' => 'DESC'])->toArray() ;
    			if( !empty($company_details[0])  && !empty($user_details[0])) 
        		{
			    	$this->set("user_details", $user_details);
                    $this->set("company_details", $company_details);
        			if($controllerName == "Dashboard")
        			{
        				$mainpage = "Admin <span class='notranslate'>Dashboard</span>" ;
        				$subpage = $mainpage ;
        			} 
        			elseif($controllerName == "Schooldashboard")
					{
						foreach($retrieve_langlabel as $langlbl) { if($langlbl['id'] == '1') { $dash = $langlbl['title'] ; } }
            			$mainpage =   $dash;
						$subpage = $mainpage ;
					} 
					else
					{
						$mainpage = $controllerName ;
						if($actionName == "index")
						{
							$subpage = $controllerName." List"  ;
						}
						else{
							$subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
						}
					}
        			
        			$sidebar_prefix = "";
        			$user_privilage = explode(',',$user_details[0]['privilages']) ;
        			$retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
        			$count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
        			$superadminid = $this->Cookie->read('sid');
        									 
        		
					$this->set("company_details", $company_details); 
					$this->set("actionName", $actionName);  
					$this->set("subpage", $subpage); 
					$this->set("mainpage", $mainpage); 
					$this->set("sidebar_prefix", $sidebar_prefix);
					$this->set("baseurl", $this->base_url); 
					$this->set("session_details", $retrieve_session);  
					$this->set('session_id', $this->Cookie->read('sessionid'));
        
        			$this->set("user_privilage", $user_privilage); 
        			$this->set("school_cat_priv", $school_cat_priv); 
        			$this->set("controllerName", $controllerName);  
        			$this->set("subpage", $subpage); 
        			$this->set("mainpage", $mainpage); 
        			
        			$notification_table = TableRegistry::get('notification');
                    $scl_table = TableRegistry::get('company');
                    $notify_recv_tbl = TableRegistry::get('received_notfiy');
                    
                    /*$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['md5(role_id)' => $uid, 'role_type' => 'school' ])->toArray() ;
                    if(!empty($notify_recv))
                    {
                        date_default_timezone_set('Africa/Kinshasa');
                        $lastdate = $notify_recv[0]['created_date'];
                        $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                        $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
   
                    }
                    else
                    {*/
                        $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1,  'OR' => [['added_by' => 'superadmin'], ['added_by' => 'teachers'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
                    //}
                    
                    $retrieve_scl = $scl_table->find()->select(['id'])->where([ 'md5(id)' => $uid])->toArray() ;
                    $sclid  = $retrieve_scl[0]['id'];
                    $retrieve_notify = $notification_table->find()->where(['sent_notify' => 0,  'added_by' => 'school', 'md5(school_id)' => $uid])->order(['id' => 'desc'])->toArray() ;
                    
                    if(!empty($rcve_nottzCount))
                    {
                        $countNotifctn = 0;
                        foreach($rcve_nottzCount as $notzcount)
                        {
                            if($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "superadmin")
                            {
                                $countNotifctn = 1;
                            }
                            else
                            {
                                $schoolids = explode(",", $notzcount['school_ids']);
                              
                                if(in_array($sclid, $schoolids))
                                {
                                    $countNotifctn = 1;
                                }
                                else
                                {
                                    $countNotifctn = 0;
                                }
                            }
                            if($countNotifctn == 1)
                            {
                                $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $sclid, 'notify_id' => $notzcount['id'], 'role_type' => 'school' ])->count() ;
                                if($notify_recv == 0)
                                {
                                    $chcked_notfy = $notify_recv_tbl->newEntity();
                                    $chcked_notfy->notify_id = $notzcount['id'];
                                    $chcked_notfy->status = 0;
                                    $chcked_notfy->role_id = $sclid;
                                    $chcked_notfy->role_type = 'school';
                                    $chcked_notfy->created_date = time();
                                    if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                    {   
                                        $res = [ 'result' => 'success'];
                                    }
                                    else
                                    {
                                        $res = [ 'result' => 'failed'  ];
                                    } 
                                }
                            }
                        }
                        //$countNoti = array_sum($countNotifctn);
                        //print_r($countNotifctn);
                        $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $sclid, 'status' => 0, 'role_type' => 'school' ])->count() ;
                        $countNoti = $notifyrecvb; 
                    }
                    else
                    {
                        $countNoti = 0;
                    }
                    $this->set("schoolnotfycount", $countNoti);
                    
                    $compid = $this->request->session()->read('company_id');
                    $company_table = TableRegistry::get('company');
                    
                    $message_table = TableRegistry::get('messages');
                    $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_msg_table))
                    { 
                        $student_table = TableRegistry::get('student');
                        foreach($retrieve_msg_table as $key =>$msg)
                		{
                		    $count_read = $message_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'student'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$count = $msg->read_count;
                    }
                    else
                    {
                        $count = 0;
                    }
                    $this->set("countmsg", $count);  
                    
                    $tmessage_table = TableRegistry::get('teacher_message');
                    $retrieve_tmsg_table = $tmessage_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_tmsg_table))
                    { 
                        foreach($retrieve_tmsg_table as $key =>$msg)
                		{
                		    $count_read = $tmessage_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'teacher'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$countt = $msg->read_count;
                    }
                    else
                    {
                        $countt = 0;
                    }
                    $this->set("counttmsg", $countt);  
                    
                    $parentmsg_table = TableRegistry::get('parent_message');
                    $retrieve_pmsg_table = $parentmsg_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_pmsg_table))
                    { 
                        foreach($retrieve_pmsg_table as $key =>$msg)
                		{
                		    $count_read1 = $parentmsg_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'parent'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read1; 
                		}
                		
                		$countpmsg = $msg->read_count;
                    }
                    else
                    {
                        $countpmsg = 0;
                    }
                    
                   
                    $this->set("countpmsg", $countpmsg);  
                    
                    $contactyoume_table = TableRegistry::get('contactyoume');
                    $retrieve_msg_table1 = $contactyoume_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_msg_table1))
                    { 
                        foreach($retrieve_msg_table1 as $key => $msg)
                		{
                		    $count_read2 = $contactyoume_table->find()->where([ 'school_id' => $compid, 'read_msg'=>'0', 'OR' => ['parent'=> $msg->id, 'parent'=> 0] , 'OR' => ['to_type'=>'school', 'from_type'=>'superadmin']])->count();
                		    $msg->read_count = $count_read2; 
                		}
                		
                		$countyoume = $msg->read_count;
                    }
                    else
                    {
                        $countyoume = 0;
                    }
                    $this->set("countmsgyoume", $countyoume);  
        		}
				else
				{
					setcookie('id', '', time()-1000  , $baseurl );
					return $this->redirect([  'controller' => 'login' ]);  
				} 
        	
            }
            elseif($sid != "")
            {
            	$users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$emp_table = TableRegistry::get('employee');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
           
            	
            	$retrieve_users = $users_table->find()->select(['id' ,'fname' ,'lname' , 'phone'  , 'password' , 'menus_privilages' , 'email', 'sesscode',  'picture','privilages', 'role' ])->where(['md5(id)' => $sid ]) ; 
            	$user_details = $retrieve_users->toArray() ; 
                $this->Cookie->write('sid',  md5($user_details[0]['id']) ,   time()+3600 );
            	$this->request->session()->write('users_id', $user_details[0]['id']);
            	$this->request->session()->write('baseurl', $this->base_url);
            	$this->request->session()->write('loginuser', "superadmin");
            	
            	
                    $controller = $this->request->getParam('controller');
                    $action = $this->request->getParam('action');
                    
                    if(strtolower($controller) == "dashboard" && $action == "index")
                    {
                        $control = "Superadmin Dashboard";
                    }
                    else
                    {
                        $control = ucfirst($controller);
                    }
    
                    if($action == "index")
                    {
                        $method = "";
                    }
                    else
                    {
                        $method = " (".ucfirst($action).")";
                    }
                    $trackmenu = $control.$method;
                    $tracks = 1;
                    if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logouta") 
                    {
                        $tracks = 0;
                    }
                    
                    if($tracks == 1)
                    {
                        $trackid = $_SESSION['supersub_tracklogintime']; 
                        $track = $logtrack_table->newEntity();
                        $track->type =  "super_subadmin"  ;
                        $track->login_id =  $user_details[0]['id'] ;
                        $track->track_id =  $trackid;
                        //$track->school_id = $company_details[0]['id'];
                        $track->menu_tracking = $control.$method;
                        $track->fullurl = $this->getfull_url; 
                        $track->time = time();
                        $saved = $logtrack_table->save($track) ;
                    }
                
            	if( !empty($user_details[0]) )
            	{
            		if($user_details)
            		{
            			$this->set("user_details", $user_details);
            
            			if($controllerName == "Dashboard")
            			{
            				$mainpage = "Admin <span class='notranslate'>Dashboard</span>" ;
            				$subpage = $mainpage ;
            			} 
            			else
            			{
            				$mainpage = $controllerName ;
            				if($actionName == "index")
            				{
            					$subpage = $controllerName." List"  ;
            
            				}
            				else{
            					
            					$subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
            
            				}
            
            			}
            			$sidebar_prefix = "";
            			$user_privilage = explode(',',$user_details[0]['privilages']) ;
            			$retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
            			$count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
            									
            			$spec_cond = md5($user_details[0]['id']) ;
            			
            			$userid = $this->Cookie->read('sid');
            
            			$this->set("user_privilage", $user_privilage); 
            			$this->set("controllerName", $controllerName);  
            			$this->set("actionName", $actionName);  
            			$this->set("subpage", $subpage); 
            			$this->set("mainpage", $mainpage); 
            			$this->set("sidebar_prefix", $sidebar_prefix);
            			$this->set("baseurl", $this->base_url);   
            		}
            	}
            	else
            	{
            		setcookie('sid', '', time()-1000  , $baseurl );
            		return $this->redirect('/login');  
            	} 
            }
        
        }
        elseif($subid != "" )
        {      
            if($subid != "")
            {
            	$sclsub_table = TableRegistry::get('school_subadmin');
            	$company_table = TableRegistry::get('company');
            	$emp_table = TableRegistry::get('employee');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
        
            	if(!empty($www))
            	{	
            		$retrieve_company_status = "";  
            		$newdate = date('Y-m-d',strtotime('now'));
            
            	    	$sclsub_details = $sclsub_table->find()->select(['company.id', 'school_subadmin.school_id', 'school_subadmin.jobtitle' ,'school_subadmin.id' , 'school_subadmin.scl_sub_priv' , 'school_subadmin.lname', 'school_subadmin.fname' , 'school_subadmin.password', 'school_subadmin.privilages' , 'school_subadmin.sub_privileges', 'school_subadmin.picture'  , 'school_subadmin.phone'  , 'school_subadmin.email',  'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'company.primary_color',  'company.button_color' , 'company.comp_name' ])->join(['company' => 
                            [
                                'table' => 'company',
                                'type' => 'LEFT',
                                'conditions' => 'company.id = school_subadmin.school_id'
                            ]
                        ])->where(['md5(school_subadmin.id)' => $subid ])->toArray(); 
                        
                        //print_r($sclsub_details); die;
                        $this->request->session()->write('company_id', $sclsub_details[0]['school_id']);    
                        $this->request->session()->write('privilages', $sclsub_details[0]['privilages']);
                        $this->request->session()->write('subprivilages', $sclsub_details[0]['sub_privileges']);
                        $this->request->session()->write('subadmin_id', $sclsub_details[0]['id']);
                        $this->request->session()->write('baseurl', $this->base_url);
                        $this->request->session()->write('loginuser', "school subadmin");
                        
                        
                        $controller = $this->request->getParam('controller');
                        $action = $this->request->getParam('action');
                        
                        if(strtolower($controller) == "subadmindashboard" && $action == "index")
                        {
                            $control = "School Subadmin Dashboard";
                        }
                        else
                        {
                            $control = ucfirst($controller);
                        }
        
                        if($action == "index")
                        {
                            $method = "";
                        }
                        else
                        {
                            $method = " (".ucfirst($action).")";
                        }
                        $trackmenu = $control.$method;
                        $tracks = 1;
                        if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logouts") 
                        {
                            $tracks = 0;
                        }
                        
                        if($tracks == 1)
                        {
                            $trackid = $_SESSION['sclsub_tracklogintime']; 
                            $track = $logtrack_table->newEntity();
                            $track->type =  "student"  ;
                            $track->login_id =  $sclsub_details[0]['id'] ;
                            $track->track_id =  $trackid;
                            $track->school_id = $sclsub_details[0]['school_id'];
                            $track->menu_tracking = $control.$method;
                            $track->fullurl = $this->getfull_url; 
                            $track->time = time();
                            $saved = $logtrack_table->save($track) ;
                        }
		
            	
            			$retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $sclsub_details[0]['school_id'] , 'status' => 0 ])->count();
            
            			$retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear' ])->order(['startyear' => 'DESC'])->toArray() ;
            			// if($retrieve_company_status == 0 && $olddate >= $newdate )
            			if($retrieve_company_status == 0 )
            			{
            				
            				if( !empty($sclsub_details[0])  ) 
            				{
            					if($sclsub_details)
            					{
            						if($controllerName == "Subadmindashboard")
            						{
            						    foreach($retrieve_langlabel as $langlbl) { if($langlbl['id'] == '2101') { $subaddash = $langlbl['title'] ; } }
            							$mainpage = "$subaddash" ;
            							$subpage = $mainpage ;
            						} 
            						else
            						{
            							$mainpage = $controllerName ;
            							if($actionName == "index")
            							{
            								$subpage = $controllerName." List"  ;
            							}
            							else{
            								$subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
            							}
            						}
            						$sidebar_prefix = "";
            
            						$this->set("controllerName", $controllerName); 
            						$this->set("sclsub_details", $sclsub_details);
            						$this->set("actionName", $actionName);  
            						$this->set("subpage", $subpage); 
            						$this->set("mainpage", $mainpage); 
            						$this->set("sidebar_prefix", $sidebar_prefix);
            						$this->set("baseurl", $this->base_url); 
            						$this->set("session_details", $retrieve_session);  
            						$this->set('session_id', $this->Cookie->read('sessionid'));
                                    
                                    $countNoti = 0;
                                    $count= 0;
                                     $message_table = TableRegistry::get('messages');
                    $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_msg_table))
                    { 
                        $student_table = TableRegistry::get('student');
                        foreach($retrieve_msg_table as $key =>$msg)
                		{
                		    $count_read = $message_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'student'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$count = $msg->read_count;
                    }
                    else
                    {
                        $count = 0;
                    }
                    $this->set("countmsg", $count);  
                    
                    $tmessage_table = TableRegistry::get('teacher_message');
                    $retrieve_tmsg_table = $tmessage_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_tmsg_table))
                    { 
                        foreach($retrieve_tmsg_table as $key =>$msg)
                		{
                		    $count_read = $tmessage_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'teacher'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$countt = $msg->read_count;
                    }
                    else
                    {
                        $countt = 0;
                    }
                    $this->set("counttmsg", $countt);  
                    
                    $contactyoume_table = TableRegistry::get('contactyoume');
                    $retrieve_msg_table1 = $contactyoume_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_msg_table1))
                    { 
                        $student_table = TableRegistry::get('student');
                        foreach($retrieve_msg_table1 as $key =>$msg)
                		{
                		    $count_read = $contactyoume_table->find()->where([ 'school_id' => $compid, 'read_msg'=>'0',  'OR' => ['parent'=> $msg->id, 'parent'=> 0] , 'OR' => ['to_type'=>'school', 'from_type'=>'superadmin']])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$countyoume = $msg->read_count;
                    }
                    else
                    {
                        $countyoume = 0;
                    }
                    $this->set("countmsgyoume", $countyoume); 
                                    $this->set("schoolnotfycount", $countNoti); 
                                    
                                     $parentmsg_table = TableRegistry::get('parent_message');
                    $retrieve_pmsg_table = $parentmsg_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                   
                    if(!empty($retrieve_pmsg_table))
                    { 
                        foreach($retrieve_msg_table as $key =>$msg)
                		{
                		    $count_read = $message_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'parent'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$countpmsg = $msg->read_count;
                    }
                    else
                    {
                        $countpmsg = 0;
                    }
                    $this->set("countpmsg", $countpmsg);  
            					}
            				}
            				else
            				{
            					setcookie('id', '', time()-1000  , $baseurl );
            					return $this->redirect([ 'controller' => 'login' ]);  
            				} 
            			}
            			else
            			{  
            				setcookie('id', '', time()-1000  , $baseurl );
            				setcookie('logtype', '', time()-1000  , $baseurl );
            				setcookie('www', '', time()-10  , $baseurl );		
            				return $this->redirect([ 'controller' => 'login' ]);	
            			}
            		//}
            	}
            }
        }
        elseif($uid != "" )
        {            
            if($uid != "")
            {
            	$users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$emp_table = TableRegistry::get('employee');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
        
        
            	if(!empty($www))
            	{	
            		$retrieve_company_status = "";  
            		$newdate = date('Y-m-d',strtotime('now'));
            
            			$retrieve_company = $company_table->find()->select(['id' ,'comp_no' ,'comp_name' , 'scl_privilages' , 'ph_no'  , 'password' , 'email' ,'site_title' , 'sesscode' ,'user_name','logout_url' ,'comp_logo' , 'favicon', 'prv_cat'  , 'www' ,'comp_logo1', 'primary_color' , 'button_color' ])->where(['md5(id)' => $uid ]) ; 
            			$company_details = $retrieve_company->toArray() ;  
            		
            			$this->request->session()->write('company_id', $company_details[0]['id']);
            			$this->request->session()->write('slugs', $company_details[0]['logout_url']);
            			$this->request->session()->write('baseurl', $this->base_url);
            			$this->request->session()->write('loginuser', "school");
            			
            			$controller = $this->request->getParam('controller');
                        $action = $this->request->getParam('action');
                        
                        if(strtolower($controller) == "schooldashboard" && $action == "index")
                        {
                            $control = "School Dashboard";
                        }
                        else
                        {
                            $control = ucfirst($controller);
                        }
        
                        if($action == "index")
                        {
                            $method = "";
                        }
                        else
                        {
                            $method = " (".ucfirst($action).")";
                        }
                        $trackmenu = $control.$method;
                        $tracks = 1;
                        if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logout") 
                        {
                            $tracks = 0;
                        }
                        
                        if($tracks == 1)
                        {
                            $trackid = $_SESSION['scl_tracklogintime']; 
                            $track = $logtrack_table->newEntity();
                            $track->type =  "school"  ;
                            $track->login_id =  $company_details[0]['id'] ;
                            $track->track_id =  $trackid;
                            $track->school_id = $company_details[0]['id'];
                            $track->menu_tracking = $control.$method;
                            $track->fullurl = $this->getfull_url; 
                            $track->time = time();
                            $saved = $logtrack_table->save($track) ;
                        }
            
            	
            			$retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $company_details[0]['id'] , 'status' => 0 ])->count();
            
            			$retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear' ])->order(['startyear' => 'DESC'])->toArray() ;
            			// if($retrieve_company_status == 0 && $olddate >= $newdate )
            			if($retrieve_company_status == 0 )
            			{
            				/*if( !empty($company_details[0])  &&  md5($company_details[0]['password']) == $encpw &&  $company_details[0]['sesscode'] == $sesscode ) */
            				if( !empty($company_details[0])  ) 
            				{
            					if($company_details)
            					{
            						$this->set("company_details", $company_details);
            
            						if($controllerName == "Schooldashboard")
            						{
            						    foreach($retrieve_langlabel as $langlbl) { if($langlbl['id'] == '1') { $dash = $langlbl['title'] ; } }
            							$mainpage =   $dash;
            							$subpage = $mainpage ;
            						} 
            						else
            						{
            							$mainpage = $controllerName ;
            							if($actionName == "index")
            							{
            								$subpage = $controllerName." List"  ;
            
            							}
            							else{
            								
            								$subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
            
            							}
            
            						}
            						$sidebar_prefix = "";
            						$scl_prv_cat = explode(',',$company_details[0]['prv_cat']) ;
            						$retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
            						$count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
            												
            						$spec_cond = md5($company_details[0]['id']) ;
            						
            						$userid = $this->Cookie->read('id');
            
            						$this->set("scl_prvs_cat", $scl_prv_cat);
            						$this->set("company_details", $company_details); 
            						$this->set("controllerName", $controllerName);  
            						$this->set("actionName", $actionName);  
            						$this->set("subpage", $subpage); 
            						$this->set("mainpage", $mainpage); 
            						$this->set("sidebar_prefix", $sidebar_prefix);
            						$this->set("baseurl", $this->base_url); 
            						$this->set("session_details", $retrieve_session);  
            						$this->set('session_id', $this->Cookie->read('sessionid'));
            						
            						
            						
                                    $notification_table = TableRegistry::get('notification');
                                    $scl_table = TableRegistry::get('company');
                                    $notify_recv_tbl = TableRegistry::get('received_notfiy');
                                    
                                    /*$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['md5(role_id)' => $uid, 'role_type' => 'school' ])->toArray() ;
                                    //print_r($notify_recv); die;
                                    if(!empty($notify_recv))
                                    {
                                        //echo "hi";
                                        date_default_timezone_set('Africa/Kinshasa');
                                        $lastdate = $notify_recv[0]['created_date'];
                                        $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                                        $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1,  'sc_date_time >=' => $notifytime, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
                                         
                                    }
                                    else
                                    {*/
                                        $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1,  'OR' => [['added_by' => 'superadmin'], ['added_by' => 'teachers'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
                    
                                    //}
                                    //print_r($rcve_nottzCount); die;
                                    $retrieve_scl = $scl_table->find()->select(['id'])->where([ 'md5(id)' => $uid])->toArray() ;
                                    $sclid  = $retrieve_scl[0]['id'];
                                    $retrieve_notify = $notification_table->find()->where(['sent_notify' => 0,  'added_by' => 'school', 'md5(school_id)' => $uid])->order(['id' => 'desc'])->toArray() ;
                                    
                                    //$rcve_nottzCount = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1,  'added_by' => 'superadmin', 'OR' => [['notify_to' => 'all'], ['notify_to' => 'schools']] ])->toArray() ;
                                     
                                    if(!empty($rcve_nottzCount))
                                    {
                                        $countNotifctn = 0;
                                        foreach($rcve_nottzCount as $notzcount)
                                        {
                                            if($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "superadmin")
                                            {
                                                $countNotifctn = 1;
                                            }
                                            else
                                            {
                                                $schoolids = explode(",", $notzcount['school_ids']);
                                              
                                                if(in_array($sclid, $schoolids))
                                                {
                                                    $countNotifctn = 1;
                                                }
                                                else
                                                {
                                                    $countNotifctn = 0;
                                                }
                                            }
                                            if($countNotifctn == 1)
                                            {
                                                $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $sclid, 'notify_id' => $notzcount['id'], 'role_type' => 'school' ])->count() ;
                                                if($notify_recv == 0)
                                                {
                                                    $chcked_notfy = $notify_recv_tbl->newEntity();
                                                    $chcked_notfy->notify_id = $notzcount['id'];
                                                    $chcked_notfy->status = 0;
                                                    $chcked_notfy->role_id = $sclid;
                                                    $chcked_notfy->role_type = 'school';
                                                    $chcked_notfy->created_date = time();
                                                    if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                                    {   
                                                        $res = [ 'result' => 'success'];
                                                    }
                                                    else
                                                    {
                                                        $res = [ 'result' => 'failed'  ];
                                                    } 
                                                }
                                            }
                                        }
                                        //$countNoti = array_sum($countNotifctn);
                                        $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $sclid, 'status' => 0, 'role_type' => 'school' ])->count() ;
                                        $countNoti = $notifyrecvb; 
                                        
                                    }
                                    else
                                    {
                                        $countNoti = 0;
                                    }
                                    $this->set("schoolnotfycount", $countNoti); 
                                    
                                    $compid = $this->request->session()->read('company_id');
                                    $company_table = TableRegistry::get('company');
                                    
                                    $message_table = TableRegistry::get('messages');
                                    $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                                    if(!empty($retrieve_msg_table)) {
                                        $student_table = TableRegistry::get('student');
                                        foreach($retrieve_msg_table as $key =>$msg)
                                		{
                                		    $count_read = $message_table->find()->where(['school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'student'], 'read_msg'=>'0'])->count();
                                		    //print_r($retrieve_msg_table);exit;
                                		    $msg->read_count = $count_read; 
                                		}
                                		
                                		$count = $msg->read_count;
                                    }
                                    else
                                    {
                                        $count = 0;
                                    }
                            		
                                    $this->set("countmsg", $count); 
                                    
                                    $contactyoume_table = TableRegistry::get('contactyoume');
                                    $retrieve_msg_table1 = $contactyoume_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                                   
                                    if(!empty($retrieve_msg_table1))
                                    { 
                                        $student_table = TableRegistry::get('student');
                                        foreach($retrieve_msg_table1 as $key =>$msg)
                                		{
                                		    //echo $msg->id;
                                		    $count_read = $contactyoume_table->find()->where([ 'school_id' => $compid, 'read_msg'=>'0',  'OR' => ['parent'=> $msg->id, 'parent'=> 0] , 'OR' => ['to_type'=>'school', 'from_type'=>'superadmin']])->count();
                                		     $msg->read_count = $count_read; 
                                		    //die;
                                		}
                                		
                                		$countyoume = $msg->read_count;
                                    }
                                    else
                                    {
                                        $countyoume = 0;
                                    }
                                    $this->set("countmsgyoume", $countyoume);  
                                    
                                    $tmessage_table = TableRegistry::get('teacher_message');
                                    $retrieve_tmsg_table = $tmessage_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                                   
                                    if(!empty($retrieve_tmsg_table))
                                    { 
                                        foreach($retrieve_tmsg_table as $key =>$msg)
                                		{
                                		    $count_read = $tmessage_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'teacher'], 'read_msg'=>'0'])->count();
                                		    $msg->read_count = $count_read; 
                                		}
                                		
                                		$countt = $msg->read_count;
                                    }
                                    else
                                    {
                                        $countt = 0;
                                    }
                                    $this->set("counttmsg", $countt);  
                                    
                                    $parentmsg_table = TableRegistry::get('parent_message');
                                    $retrieve_pmsg_table = $parentmsg_table->find()->where(['school_id' => $compid, 'parent'=> '0'])->order(['id' => 'DESC'])->toArray();
                                   
                                    if(!empty($retrieve_pmsg_table))
                                    { 
                                        foreach($retrieve_pmsg_table as $key =>$msg)
                                		{
                                		    $count_read = $parentmsg_table->find()->where([ 'school_id' => $compid, 'OR' => ['parent'=> $msg->id, 'parent'=> 0], 'OR' => ['to_type'=>'school', 'from_type'=>'parent'], 'read_msg'=>'0'])->count();
                                		    $msg->read_count = $count_read; 
                                		}
                                		
                                		$countpmsg = $msg->read_count;
                                    }
                                    else
                                    {
                                        $countpmsg = 0;
                                    }
                                    $this->set("countpmsg", $countpmsg);  
            					}
            				}
            				else
            				{
            			
            					setcookie('id', '', time()-1000  , $baseurl );
            					return $this->redirect([ 'controller' => 'login' ]);  
            				} 
            			}
            			else
            			{  
            			
            				setcookie('id', '', time()-1000  , $baseurl );
            				setcookie('logtype', '', time()-1000  , $baseurl );
            				setcookie('www', '', time()-10  , $baseurl );		
            				return $this->redirect([ 'controller' => 'login' ]);	
            			}
            		//}
            	}
            }
        }
        elseif($tid != "" )
        {
            if($logtype == md5('Employee') )
            {   
                $users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$emp_table = TableRegistry::get('employee');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session'); 
            	$empcls_table = TableRegistry::get('employee_class_subjects');
        	
                $emp_details = $emp_table->find()->select(['company.id', 'employee.school_id' ,'employee.id' ,'employee.e_name', 'employee.quali', 'employee.l_name', 'employee.f_name' , 'employee.password', 'employee.pict' , 'employee.mobile_no'  , 'employee.email',  'company.site_title' , 'company.comp_logo' ,  'company.comp_name' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'company.primary_color',  'company.button_color' ])->join(['company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = employee.school_id'
                    ]
                ])->where(['md5(employee.id)' => $tid ])->toArray(); 
                
                $empname = $emp_details[0]['f_name']." ".$emp_details[0]['l_name'];
                
                $this->request->session()->write('tchr_id', $emp_details[0]['id']);
                $this->request->session()->write('company_id', $emp_details[0]['school_id']);                    
                $this->request->session()->write('teacher_id', $emp_details[0]['id']);
                $this->request->session()->write('baseurl', $this->base_url);
                $this->request->session()->write('loginuser', "teacher");
               
             //  echo $this->getfull_url;
              
                $controller = $this->request->getParam('controller');
                $action = $this->request->getParam('action');
                
                

                if(strtolower($controller) == "teacherdashboard" && $action == "index")
                {
                    $control = "Teacher Dashboard";
                }
                else
                {
                    $control = ucfirst($controller);
                }

                if($action == "index")
                {
                    $method = "";
                }
                else
                {
                    $method = " (".ucfirst($action).")";
                }
                $trackmenu = $control.$method;
                $tracks = 1;
                if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logoutk") 
                {
                    $tracks = 0;
                }
                
                if($tracks == 1)
                {
                    $trackid = $_SESSION['teacher_tracklogintime']; 
                    $track = $logtrack_table->newEntity();
                    $track->type =  "teacher"  ;
                    $track->login_id =  $emp_details[0]['id'] ;
                    $track->track_id =  $trackid;
                    $track->school_id = $emp_details[0]['school_id'];
                    $track->menu_tracking = $control.$method;
                    $track->fullurl = $this->getfull_url; 
                    $track->time = time();
                    $saved = $logtrack_table->save($track) ;
                }
		
		        $retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $emp_details[0]['company']['id'] , 'status' => 0 ])->count();

                $retrieve_empcls = $empcls_table->find()->select(['class.school_sections'])->join(['class' => 
                    [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = employee_class_subjects.class_id'
                    ]
                ])->where(['employee_class_subjects.emp_id' => $emp_details[0]['id']  ])->toArray();

               
                
		        if($retrieve_company_status == 0)
                {  
                    if( !empty($emp_details[0]) ) 
                    {
                        if($emp_details)
                        {
                            $this->set("emp_details", $emp_details);
                            if($controllerName == "Dashboard")
                            {
                                $mainpage = "Employee <span class='notranslate'>Dashboard</span>" ;
                                $subpage = $mainpage ;
                            } 
                            else
                            {
                                $mainpage = $controllerName ;
                                if($actionName == "index")
                                {
                                    $subpage = $controllerName." List"  ;

                                }
                                else{
                                    
                                    $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                                }

                            }
                            $sidebar_prefix = "";
                            
		                    $emp_privilage = explode(',',$emp_details[0]['privilages']) ;		                                                       
                            $spec_cond = md5($emp_details[0]['id']) ;
                            $empemail = $emp_details[0]['email'];
                            $userid = $this->Cookie->read('id');
                            
                            $this->set("employeeclasses", $retrieve_empcls);
                            $this->set("meetingname", $meetingid); 
                            $this->set("conferenceuser", $empname);  
                            $this->set("conferenceuseremail", $empemail); 
                            $this->set("moderatorlive", "teacher"); 
			                $this->set("emp_privilages", $emp_privilage);  
                            $this->set("controllerName", $controllerName);  
                            $this->set("actionName", $actionName);  
                            $this->set("subpage", $subpage); 
                            $this->set("mainpage", $mainpage); 
                            $this->set("sidebar_prefix", $sidebar_prefix);
                            $this->set("baseurl", $this->base_url);   
                            
                        $notification_table = TableRegistry::get('notification');
                        $notify_recv_tbl = TableRegistry::get('received_notfiy');
                        
                        // $notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['md5(role_id)' => $tid, 'role_type' => 'teacher' ])->toArray() ;
                        // if(!empty($notify_recv))
                        // {
                        //     date_default_timezone_set('Africa/Kinshasa');
                        //     $lastdate = $notify_recv[0]['created_date'];
                        //     $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                        //     $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'teachers']] ])->toArray() ;
        
                        // }
                        // else
                        // {
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1,  'OR' => [['added_by' => 'superadmin'], ['added_by' => 'school']] , 'OR' => [['notify_to' => 'all'], ['notify_to' => 'teachers']] ])->toArray() ;
        
                        //}
                        
                        $tchrid = $emp_details[0]['id'];
                        if(!empty($rcve_nottzCount))
                        {
                            $countNotifctn = 0;
                            foreach($rcve_nottzCount as $notzcount)
                            {
                                if($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "superadmin")
                                {
                                    $countNotifctn = 1;
                                }
                                elseif(($notzcount['notify_to'] == "all") && ($notzcount['added_by'] == "school")  && ($notzcount['school_id'] == $emp_details[0]['school_id']))
                                {
                                    $countNotifctn = 1;
                                }
                                else
                                {
                                    $tchrids = explode(",", $notzcount['teacher_ids']);
                                    if(in_array($tchrid, $tchrids))
                                    {
                                        $countNotifctn = 1;
                                    }
                                    else
                                    {
                                        $countNotifctn = 0;
                                    }
                                }
                                
                                if($countNotifctn == 1)
                                {
                                    $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $emp_details[0]['id'], 'notify_id' => $notzcount['id'], 'role_type' => 'teacher' ])->count() ;
                                    if($notify_recv == 0)
                                    {
                                        $chcked_notfy = $notify_recv_tbl->newEntity();
                                        $chcked_notfy->notify_id = $notzcount['id'];
                                        $chcked_notfy->status = 0;
                                        $chcked_notfy->role_id = $emp_details[0]['id'];
                                        $chcked_notfy->role_type = 'teacher';
                                        $chcked_notfy->created_date = time();
                                        if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                        {   
                                            $res = [ 'result' => 'success'];
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'failed'  ];
                                        } 
                                    }
                                }
                            }
                            //$countNoti = array_sum($countNotifctn);
                            $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $emp_details[0]['id'], 'status' => 0, 'role_type' => 'teacher' ])->count() ;
                            $countNoti = $notifyrecvb; 
                            
                        }
                        else
                        {
                            $countNoti = 0;
                        }
                        $this->set("tchrntfctn_count", $countNoti); 
                        
                        $tmessage_table = TableRegistry::get('teacher_message');
                        $tchrsesid = $this->request->session()->read('tchr_id');
                        
                        $retrieve_tmmsg_table = $tmessage_table->find()->where([ 'OR' => ['to_id'=> $tchrsesid, 'from_id'=> $tchrsesid] ])->order(['id' => 'DESC'])->toArray();
                        
                        if(!empty($retrieve_tmmsg_table))   
                        {
                            foreach($retrieve_tmmsg_table as $key =>$msg)
                            {
                                $count_read = $tmessage_table->find()->where([ 'OR' => ['to_id'=> $tchrsesid, 'from_id'=> $tchrsesid] ])->where([ 'OR' => ['to_type'=>'teacher', 'from_type'=>'school'] ])->where(['read_msg'=>'0'])->count();
                                
                                $msg->read_count = $count_read; 
                            }
                            $counttm = $msg->read_count;
                        }
                        else
                        {
                            $counttm = 0;
                        }
                        
                        $this->set("counttmmsg", $counttm); 
                        }
                    }
                    else
                    {
                      
                        setcookie('id', '', time()-1000  , $baseurl );
                        return $this->redirect([ 'controller' => 'login' ]);  
                    } 
		        }
                else
                {  
                    
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
		            setcookie('www', '', time()-1000  , $baseurl );
		    		
		            return $this->redirect([ 'controller' => 'login' ]);	
                }
            }
        }
        elseif($pid != "" )
        {
            if($pid != "" && $stid != "")
            {
                $users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$parent_table = TableRegistry::get('parent_logindetails');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
                $student_sidebar_table = TableRegistry::get('student_sidebarmenu');
                
                $student_details = $student_table->find()->select(['student.id' ,'student.adm_no' , 'student.created_date' , 'student.contactyoume' , 'student.session_id', 'student.library_access' , 'student.class' ,'student.f_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'student.school_id', 'company.primary_color', 'company.button_color', 'company.comp_name'  ])->join(['company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ]
                ])->where(['md5(student.id)' => $stid ])->toArray(); 

                $this->request->session()->write('school_id', $student_details[0]['school_id']);
                $this->request->session()->write('company_id', $student_details[0]['school_id']);
                $this->request->session()->write('session_id', $student_details[0]['session_id']);
                $this->request->session()->write('class_id', $student_details[0]['class']);
                $this->request->session()->write('student_id', $student_details[0]['id']);
                $this->request->session()->write('baseurl', $this->base_url);
                $this->request->session()->write('loginuser', "student");
                $schoolid  = $student_details[0]['school_id'];
                $studid  = $student_details[0]['id'];
                $studclass  = $student_details[0]['class'];
	            $libaccess  = $student_details[0]['library_access'];
	            
	            if($stid != "")
	            {
	                $controller = $this->request->getParam('controller');
                    $action = $this->request->getParam('action');
                    
                    if(strtolower($controller) == "studentdashboard" && $action == "index")
                    {
                        $control = "Student Dashboard";
                    }
                    else
                    {
                        $control = ucfirst($controller);
                    }
    
                    if($action == "index")
                    {
                        $method = "";
                    }
                    else
                    {
                        $method = " (".ucfirst($action).")";
                    }
                    $trackmenu = $control.$method;
                    $tracks = 1;
                    if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logouts") 
                    {
                        $tracks = 0;
                    }
                    
                    if($tracks == 1)
                    {
                        $trackid = $_SESSION['student_tracklogintime']; 
                        $track = $logtrack_table->newEntity();
                        $track->type =  "student"  ;
                        $track->login_id =  $student_details[0]['id'] ;
                        $track->track_id =  $trackid;
                        $track->school_id = $student_details[0]['school_id'];
                        $track->menu_tracking = $control.$method;
                        $track->fullurl = $this->getfull_url; 
                        $track->time = time();
                        $saved = $logtrack_table->save($track) ;
                    }
	            }
                
                $parent_details = $parent_table->find()->select(['student.id' , 'student.created_date' , 'student.session_id' , 'student.class' ,'student.f_name' , 'student.s_f_name' ,  'student.s_m_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'student.school_id', 'company.primary_color', 'company.button_color' , 'company.comp_name', 'parent_logindetails.parent_email' , 'parent_logindetails.image' , 'parent_logindetails.id', 'parent_logindetails.parent_password', 'parent_logindetails.created_date' ])->join([
                    
                    'student' => 
                    [
                        'table' => 'student',
                        'type' => 'LEFT',
                        'conditions' => 'parent_logindetails.id = student.parent_id '
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ],
                ])->where(['md5(parent_logindetails.id)' => $pid, 'student.session_id' => $session_id ])->toArray(); 
	            
	            $sclsts = [];
	            foreach($parent_details as $parentchild)
	            {
	                $retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $parentchild['student']['school_id'] , 'status' => 0 ])->count();
	                if($retrieve_company_status == 0)
	                {
	                    $sclsts[] = 0;
	                }
	                else
	                {
	                    $sclsts[] = 1;
	                }
	            }
	            
	            if($pid != "")
	            {
	                $controller = $this->request->getParam('controller');
                    $action = $this->request->getParam('action');
                    
                    if(strtolower($controller) == "parentdashboard" && $action == "index")
                    {
                        $control = "Parent Dashboard";
                    }
                    else
                    {
                        $control = ucfirst($controller);
                    }
    
                    if($action == "index")
                    {
                        $method = "";
                    }
                    else
                    {
                        $method = " (".ucfirst($action).")";
                    }
                    $trackmenu = $control.$method;
                    $tracks = 1;
                    if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logoutp") 
                    {
                        $tracks = 0;
                    }
                    
                    if($tracks == 1)
                    {
                        $trackid = $this->request->session()->read('parent_tracklogintime'); 
                        $track = $logtrack_table->newEntity();
                        $track->type =  "parent"  ;
                        $track->login_id =  $parent_details[0]['id'] ;
                        $track->track_id =  $trackid;
                        $track->school_id = $parent_details[0]['student']['school_id'];
                        $track->menu_tracking = $control.$method;
                        $track->fullurl = $this->getfull_url; 
                        $track->time = time();
                        $saved = $logtrack_table->save($track) ;
                    }
	            }
     	        
	            if(in_array("0", $sclsts))
                {
                    if(!empty($parent_details) ) 
                    {
                        if($controllerName == "Parentdashboard")
                        {
                            $mainpage = "Parent Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        elseif($controllerName == "Studentdashboard")
                        {
                            $mainpage = $studdash ;
                            $subpage = $mainpage ;
                        } 
                        elseif($controllerName == "KinderDashboard")
                        {
                            $mainpage = "Kinder Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;
                            }
                            else{
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
                            }
                        }
                        
                        $sidebar_prefix = "";
                        $spec_cond = md5($student_details[0]['id']) ;
                        $studname = $student_details[0]['f_name']." ". $student_details[0]['l_name'];
                        $studemail = $student_details[0]['email'];
                        $userid = $this->Cookie->read('id');
                        
                        $this->set("libaccess", $libaccess); 
                        $this->set("conferenceuser", $studname); 
                        $this->set("conferenceuseremail", $studemail); 
                        $this->set("moderatorlive", "student"); 
                        $this->set("meetingname", $meetingid); 
                        
                        $this->set("controllerName", $controllerName);  
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url);   
                        $this->set("parent_details", $parent_details);
                        $this->set("student_details", $student_details);
                        $this->request->session()->write('parent_id', $parent_details[0]['id']);    
                        $this->request->session()->write('session_id', $session_id); 
                        
                        					
                        $notification_table = TableRegistry::get('notification');
                        $notify_recv_tbl = TableRegistry::get('received_notfiy');
                        
                        
                        
                        //$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['md5(role_id)' => $stid, 'role_type' => 'student' ])->toArray() ;
                        /*if(!empty($notify_recv))
                        {
                            date_default_timezone_set('Africa/Kinshasa');
                            $lastdate = $notify_recv[0]['created_date'];
                            $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']] ])->toArray() ;
                        }
                        else
                        {*/
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $student_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']] ])->toArray() ;
                        //}
                        $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'created_date >=' => $student_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']]  ])->toArray() ;
                        $tchrscholid = '';
                        
                        $emp_table = TableRegistry::get('employee');
                        $empcls_table = TableRegistry::get('employee_class_subjects');
                        foreach($retrieve_notify as $notify)
                        {
                            $notify->tchrscholid ='';
                            if($notify['notify_to'] == "all" && $notify['added_by'] == "teachers")
                            {
                                $tchrid = $notify['teacher_id'];
                                $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                                $notify->tchrscholid = $retrieve_emp['school_id'];
                                $tchrcls = [];
                                $tchrsubj = [];
                                $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->toArray() ;
                                foreach($retrieve_empcls as $empcls)
                                {
                                    $tchrcls[] = $empcls['class_id'];
                                    $tchrsubj[] = $empcls['subject_id'];
                                }
                                $notify->tchrcls = implode(",", $tchrcls);
                                $notify->tchrsubj = implode(",", $tchrsubj);
                                
                            }
                        }
                        $clssubjcts_table = TableRegistry::get('class_subjects');
                        $retrieve_clssub = $clssubjcts_table->find()->where(['class_id' => $student_details[0]['class'] ])->first() ;
                        
                        $countNotify = [];
                        //print_r($rcve_nottzCount);
                        if(!empty($rcve_nottzCount))
                        {
                            $countNotifctn = 0;
                            foreach($rcve_nottzCount as $notzcount)
                            {
                                if($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "superadmin")
                                {
                                    $countNotifctn = 1;
                                }
                                elseif($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "school" && $notzcount['school_id'] == $student_details[0]['school_id'])
                                {
                                    $countNotifctn = 1;
                                }
                                elseif($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "teachers" && $notzcount['tchrscholid'] == $student_details[0]['school_id'])
                                {
                                    $tchrscls = explode(",", $notzcount['tchrcls']);
                                    if(in_array($retrieve_clssub['class_id'], $tchrscls))
                                    {
                                        $countNotifctn = 1;
                                    }
                                    else
                                    {
                                        $countNotifctn= 0;
                                    }
                                }
                                else
                                {
                                    if($notzcount['class_opt'] == "multiple")
                                    {
                                        $clsids = explode(",", $notzcount['class_ids']);
                                        if(in_array($studclass, $clsids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                    else
                                    {
                                        $studids = explode(",", $notzcount['student_ids']);
                                        if(in_array($studid, $studids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                }
                                if($countNotifctn == 1)
                                {
                                    $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $student_details[0]['id'], 'notify_id' => $notzcount['id'], 'role_type' => 'student' ])->count() ;
                                    if($notify_recv == 0)
                                    {
                                        $chcked_notfy = $notify_recv_tbl->newEntity();
                                        $chcked_notfy->notify_id = $notzcount['id'];
                                        $chcked_notfy->status = 0;
                                        $chcked_notfy->role_id = $student_details[0]['id'];
                                        $chcked_notfy->role_type = 'student';
                                        $chcked_notfy->created_date = time();
                                        if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                        {   
                                            $res = [ 'result' => 'success'];
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'failed'  ];
                                        } 
                                    }
                                    $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $student_details[0]['id'], 'notify_id' => $notzcount['id'], 'status' => 0, 'role_type' => 'student' ])->count() ;
                                    if($notifyrecvb != 0):
                                        $countNotify[] = $notifyrecvb; 
                                    endif;
                                }
                                
                            }
                            $countNoti = array_sum($countNotify);
                              
                        }
                        else
                        {
                            $countNoti = 0;
                        }
                    
                        $this->set("studntnoti_count", $countNoti); 
                        
                        $compid = $this->request->session()->read('company_id');
                        $company_table = TableRegistry::get('company');
                        $message_table = TableRegistry::get('messages');
                        $studsessid = $this->request->session()->read('student_id');
                        
                        
                        $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid])->order(['id' => 'DESC'])->toArray();
                        if(!empty($retrieve_msg_table))   
                        {
                            $student_table = TableRegistry::get('student');
                            foreach($retrieve_msg_table as $key =>$msg)
                            {
                                $count_read = $message_table->find()->where(['school_id' => $compid, 'OR' => ['to_id'=> $studsessid, 'from_id'=> $studsessid], 'OR' => ['to_type'=>'student', 'from_type'=>'school'], 'read_msg'=>'0'])->count();
                                $msg->read_count = $count_read; 
                            }
                            $count = $msg->read_count;
                        }
                        else
                        {
                            $count = 0;
                        }
                        
                        $this->set("countunreadmsg", $count); 
                        
                        $pmessage_table = TableRegistry::get('parent_message');
                        $parentsesid = $this->request->session()->read('parent_id');
                        
                        $retrieve_pmsg_table = $pmessage_table->find()->where([ 'OR' => ['to_id'=> $parentsesid, 'from_id'=> $parentsesid] ])->order(['id' => 'DESC'])->toArray();
                        
                        
                        
                        if(!empty($retrieve_pmsg_table))   
                        {
                            foreach($retrieve_pmsg_table as $key =>$msg)
                            {
                                $count_read = $pmessage_table->find()->where([ 'OR' => ['to_id'=> $parentsesid, 'from_id'=> $parentsesid] ])->where([ 'OR' => ['to_type'=>'parent', 'from_type'=>'school'] ])->where(['read_msg'=>'0'])->count();
                               // print_r($count_read); die;
                                
                                $msg->read_count = $count_read; 
                            }
                            $countpp = $msg->read_count;
                        }
                        else
                        {
                            $countpp = 0;
                        }
                        
                        $this->set("countprmsg", $countpp); 
                        
                        $student_table = TableRegistry::get('student');
                        $studsidebar_table = TableRegistry::get('student_sidebarmenu');
                        
                        $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $stid ])->first();
                        
                        $sid = $stuid_retrieve['id'];
                        $getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
                        
                        if($getcount != 0)
                        {
                            $getside = $studsidebar_table->find()->where(['student_id'=> $sid ])->first();
                            if($getside['status'] == 1)
                            {     
                                $side = "style='display:block !important;'";
                            }
                            else
                            {
                                $side = "style='display:none !important;'";
                            }
                        }
                        else
                        {
                            $side = "style='display:none !important;'";
                        }
                        $this->set("showsidebar", $side); 
                        
                        $notification_table = TableRegistry::get('notification');
                        $notify_recv_tbl = TableRegistry::get('received_notfiy');
                        
                        /*$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['role_id' => $parentsesid, 'role_type' => 'parent' ])->first() ;
                        if(!empty($notify_recv))
                        {
                            date_default_timezone_set('Africa/Kinshasa');
                            $lastdate = $notify_recv['created_date'];
                            $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']] ])->toArray() ;
                        }
                        else
                        {*/
                            //$rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']] ])->toArray() ;
                        //}
                        /*$retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']]  ])->toArray() ;
                        $tchrscholid = '';
                        
                        $stud_table = TableRegistry::get('student');
                        $retrieve_stud = $stud_table->find()->where([ 'md5(parent_id)' => $pid, 'session_id' => $session_id])->toArray() ;
                        $schoolid= [];
                        $studid = [];
                        $studclass =[];
                        foreach($retrieve_stud as $stud)
                        {
                            $schoolid[]  = $stud['school_id'];
                            $studid[] = $stud['id'];
                            $studclass[]  = $stud['class'];
                        }
                       
                        $comp_table = TableRegistry::get('company'); 
                        $emp_table = TableRegistry::get('employee');
                        $empcls_table = TableRegistry::get('employee_class_subjects');
                        foreach($retrieve_notify as $notify)
                        {
                            $notify->tchrscholid ='';
                            if($notify['added_by'] == "teachers")
                            {
                                $tchrid = $notify['teacher_id'];
                                $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                                $notify->tchrscholid = $retrieve_emp['school_id'];
                                
                                $notify->sentby = $retrieve_emp['l_name']." ". $retrieve_emp['f_name'] ;
                                $retrieve_company = $comp_table->find()->where([ 'id' => $retrieve_emp['school_id']])->first() ;
                                $notify->sentlogo = $retrieve_company['comp_logo'];
                                
                                $tchrcls = [];
                                $tchrsubj = [];
                                $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->group(['class_id'])->toArray() ;
                                foreach($retrieve_empcls as $empcls)
                                {
                                    $tchrcls[] = $empcls['class_id'];
                                    $tchrsubj[] = $empcls['subject_id'];
                                }
                                $notify->tchrcls = implode(",", $tchrcls);
                                $notify->tchrsubj = implode(",", $tchrsubj);
                                
                                $snames = [];
                                foreach($retrieve_stud as $stud)
                                {
                                    if(in_array($stud['class'], $tchrcls))
                                    {
                                       $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                                        //$notify->sentlogo = $retrieve_company['comp_logo'];
                                    }
                                }
                                $studname = implode(",", $snames);
                                $notify->studentname = $studname;
                                
                            }
                            elseif($notify['added_by'] == "school")
                            {
                                 $retrieve_company = $comp_table->find()->where([ 'id' => $notify['school_id']])->first() ;
                                 
                                 $notify->sentby = $retrieve_company['comp_name'] ;
                                 $notify->sentlogo = $retrieve_company['comp_logo'];
                     
                                $clsids = explode(",", $notify['class_ids']);
                                $snames = [];
                                foreach($retrieve_stud as $stud)
                                {
                                    if(in_array($stud['class'], $clsids))
                                    {
                                       $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                                    }
                                }
                                $studname = implode(",", $snames);
                                $notify->studentname = $studname;
                                 
                            }
                        }       
                        $clssubjcts_table = TableRegistry::get('class_subjects');
                        $retrieve_clssub = $clssubjcts_table->find()->where(['class_id' => $student_details[0]['class'] ])->first() ;
                        
                        if(!empty($rcve_nottzCount))
                        {
                            $countNotifctn = 0;
                            foreach($rcve_nottzCount as $value)
                            {
                                //echo $value['id'].",";
                                if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
                                { 
                                    $countNotifctn = 1;
                                }
                                elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
                                { 
                                    $countNotifctn = 1;
                                }
                                elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
                                { 
                                    $tchrscls = explode(",", $value['tchrcls']);
                                    if(array_intersect($studclass, $tchrscls))
                                    {
                                        $countNotifctn = 1;
                                    }
                                    else
                                    {
                                        $countNotifctn = 0;
                                    }
                                }
                                else
                                {
                                    if($value['class_opt'] == "multiple")
                                    {
                                        $incls = [];
                                        $clsids = explode(",", $value['class_ids']);
                                        foreach($studclass as $sdcls)
                                        {
                                            if(in_array($sdcls, $clsids))
                                            {
                                                $incls[] = 1;
                                            }
                                            else
                                            {
                                                $incls[] = 0;
                                            }
                                        }
                                        $countNotifctn = in_array("1", $incls) ? "1" : "0";
                                    }
                                    else
                                    {
                                        $parids = explode(",", $value['parent_ids']);
                                        if(in_array($parntid, $parids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                    
                                }
                                
                                if($countNotifctn == 1)
                                {
                                    $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' ])->count() ;
                                    if($notify_recv == 0)
                                    {
                                        $chcked_notfy = $notify_recv_tbl->newEntity();
                                        $chcked_notfy->notify_id = $value['id'];
                                        $chcked_notfy->status = 0;
                                        $chcked_notfy->role_id = $parent_details[0]['id'];
                                        $chcked_notfy->role_type = 'parent';
                                        $chcked_notfy->created_date = time();
                                        if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                        {   
                                            $res = [ 'result' => 'success'];
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'failed'  ];
                                        } 
                                    }
                                }
                            }
                            //$countNoti = array_sum($countNotifctn);
                            $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $parent_details[0]['id'], 'status' => 0, 'role_type' => 'parent' ])->count() ;
                            $countNoti = $notifyrecvb; 
                            
                        }
                        else
                        {
                            $countNoti = 0;
                        }*/
$comp_table = TableRegistry::get('company');                         
$emp_table = TableRegistry::get('employee');  
$stud_table = TableRegistry::get('student');
$retrieve_stud = $stud_table->find()->where([ 'md5(parent_id)' => $pid, 'session_id' => $session_id])->toArray() ;
$tchrscholid = '';
$empcls_table = TableRegistry::get('employee_class_subjects');
$notify_recv_tbl = TableRegistry::get('received_notfiy');
$schoolid= [];
$studid = [];
$studclass =[];
foreach($retrieve_stud as $stud)
{
    $schoolid[]  = $stud['school_id'];
    $studid[] = $stud['id'];
    $studclass[]  = $stud['class'];
}
//print_r($schoolid);
$retrieve_notify = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $parent_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']]  ])->order(['id' => 'desc'])->toArray() ;
foreach($retrieve_notify as $notify)
{
    $notify->tchrscholid ='';
    $notify->sentlogo = 'you-meheaderlogo.png';
    if($notify['added_by'] == "teachers")
    {
        $tchrid = $notify['teacher_id'];
        $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
        $notify->tchrscholid = $retrieve_emp['school_id'];
        
        //$notify->sentby = $retrieve_emp['l_name']." ". $retrieve_emp['f_name'] ;
        $retrieve_company = $comp_table->find()->where([ 'id' => $retrieve_emp['school_id']])->first() ;
        //$notify->sentlogo = $retrieve_company['comp_logo'];
        
        $tchrcls = [];
        $tchrsubj = [];
        $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->group(['class_id'])->toArray() ;
        foreach($retrieve_empcls as $empcls)
        {
            $tchrcls[] = $empcls['class_id'];
            $tchrsubj[] = $empcls['subject_id'];
        }
        $notify->tchrcls = implode(",", $tchrcls);
        $notify->tchrsubj = implode(",", $tchrsubj);
        
        $snames = [];
        foreach($retrieve_stud as $stud)
        {
            if(in_array($stud['class'], $tchrcls))
            {
               $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                //$notify->sentlogo = $retrieve_company['comp_logo'];
            }
        }
        $studname = implode(",", $snames);
        $notify->studentname = $studname;
        
    }
    elseif($notify['added_by'] == "school")
    {
         $retrieve_company = $comp_table->find()->where([ 'id' => $notify['school_id']])->first() ;
         
         $notify->sentby = $retrieve_company['comp_name'] ;
         $notify->sentlogo = $retrieve_company['comp_logo'];
         
         
        $clsids = explode(",", $notify['class_ids']);
        $snames = [];
        foreach($retrieve_stud as $stud)
        {
            if(in_array($stud['class'], $clsids))
            {
               $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                //$notify->sentlogo = $retrieve_company['comp_logo'];
            }
        }
        $studname = implode(",", $snames);
        $notify->studentname = $studname;
         
    }
}  
$notifybold = [];
foreach($retrieve_notify as $value)
{
    
    if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
    { 
        $display = 1;
    }
    elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
    { 
        $display = 1;
    }
    elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
    { 
        $tchrscls = explode(",", $value['tchrcls']);
        if(array_intersect($studclass, $tchrscls))
        {
            $display = 1;
        }
        else
        {
            $display = 0;
        }
    }
    else
    {
        $display = 0;
        if($value['class_opt'] == "multiple")
        {
            $incls = [];
            $clsids = explode(",", $value['class_ids']);
            foreach($studclass as $sdcls)
            {
                if(in_array($sdcls, $clsids))
                {
                    $incls[] = 1;
                }
                else
                {
                    $incls[] = 0;
                }
            }
            $display = in_array("1", $incls) ? "1" : "0";
        }
        elseif($value['class_opt'] == "single")
        {
            $parids = explode(",", $value['parent_ids']);
          
            if(in_array($parntid, $parids))
            {
                $display = 1;
            }
            else
            {
                $display = 0;
            }
        }
        
    }
    
            
    //echo $value['id']."--".$display.","; 
    if($display == 1)
    {
        $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' ])->count() ;
        if($notify_recv == 0)
        {
            $chcked_notfy = $notify_recv_tbl->newEntity();
            $chcked_notfy->notify_id = $value['id'];
            $chcked_notfy->status = 0;
            $chcked_notfy->role_id = $parent_details[0]['id'];
            $chcked_notfy->role_type = 'parent';
            $chcked_notfy->created_date = time();
            if($saved = $notify_recv_tbl->save($chcked_notfy) )
            {   
                $res = [ 'result' => 'success'];
            }
            else
            {
                $res = [ 'result' => 'failed'  ];
            } 
            
        }
        $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' , 'status' => 0 ])->count() ;
        if($notifyrecvb != 0)
        {
            $notifybold[] = 1;
            //echo $value['id']." ,";
        }
        
    }
    
}

$countNoti = array_sum($notifybold);                    
$this->set("parntnoti_count", $countNoti); 
                    }
                    else
                    {
                        setcookie('id', '', time()-1000  , $baseurl );
    		            setcookie('logtype', '', time()-1000  , $baseurl );
       	                setcookie('www', '', time()-1000  , $baseurl );
                        return $this->redirect([ 'controller' => 'login' ]);  
                    } 
                }
                else
                { 
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
    	            setcookie('www', '', time()-1000  , $baseurl );
    	            return $this->redirect([ 'controller' => 'login' ]);	
                }
            }
            elseif($pid != "")
            {
                $users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$student_table = TableRegistry::get('student');	
            	$parent_table = TableRegistry::get('parent_logindetails');	
            	$session_table = TableRegistry::get('session');
                $student_sidebar_table = TableRegistry::get('student_sidebarmenu');
                
                $parent_details = $parent_table->find()->select(['student.id' , 'student.session_id' , 'student.class' ,'student.f_name' , 'student.s_f_name' ,  'student.s_m_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'student.school_id', 'company.primary_color', 'company.button_color' , 'company.comp_name', 'parent_logindetails.parent_email' , 'parent_logindetails.id', 'parent_logindetails.image', 'parent_logindetails.parent_password' , 'parent_logindetails.created_date' ])->join([
                    'student' => 
                    [
                        'table' => 'student',
                        'type' => 'LEFT',
                        'conditions' => 'parent_logindetails.id = student.parent_id '
                    ],
                    'company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ],
                ])->where(['md5(parent_logindetails.id)' => $pid, 'student.session_id' => $session_id ])->toArray(); 
	            
	            $sclsts = [];
	            foreach($parent_details as $parentchild)
	            {
	                $retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $parentchild['student']['school_id'] , 'status' => 0 ])->count();
	                if($retrieve_company_status == 0)
	                {
	                    $sclsts[] = 0;
	                }
	                else
	                {
	                    $sclsts[] = 1;
	                }
	            }
	            
	        
                $controller = $this->request->getParam('controller');
                $action = $this->request->getParam('action');
                if(strtolower($controller) == "parentdashboard" && $action == "index")
                {
                    $control = "Parent Dashboard";
                }
                else
                {
                    $control = ucfirst($controller);
                }

                if($action == "index")
                {
                    $method = "";
                }
                else
                {
                    $method = " (".ucfirst($action).")";
                }
                $trackmenu = $control.$method;
                $tracks = 1;
                if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logoutp") 
                {
                    $tracks = 0;
                }
                if($tracks == 1)
                {
                    $trackid = $this->request->session()->read('parent_tracklogintime'); 
                    $track = $logtrack_table->newEntity();
                    $track->type =  "parent"  ;
                    $track->login_id =  $parent_details[0]['id'] ;
                    $track->track_id =  $trackid;
                    $track->school_id = $parent_details[0]['student']['school_id'];
                    $track->menu_tracking = $control.$method;
                    $track->fullurl = $this->getfull_url; 
                    $track->time = time();
                    $saved = $logtrack_table->save($track) ;
                }
	           
     	        
	            if(in_array("0", $sclsts))
                {
                    if(!empty($parent_details) ) 
                    {
                        if($controllerName == "Parentdashboard")
                        {
                            $mainpage = "Parent Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        elseif($controllerName == "Studentdashboard")
                        {
                            $mainpage = $studdash ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;
                            }
                            else{
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
                            }
                        }
                        
                        
                        $pmessage_table = TableRegistry::get('parent_message');
                        $parentsesid = $this->request->session()->read('parent_id');
                        
                        $retrieve_pmsg_table = $pmessage_table->find()->where([ 'OR' => ['to_id'=> $parentsesid, 'from_id'=> $parentsesid] ])->order(['id' => 'DESC'])->toArray();
                        if(!empty($retrieve_pmsg_table))   
                        {
                            foreach($retrieve_pmsg_table as $key =>$msg)
                            {
                                $count_read = $pmessage_table->find()->where([ 'OR' => ['to_id'=> $parentsesid, 'from_id'=> $parentsesid] ])->where([ 'OR' => ['to_type'=>'parent', 'from_type'=>'school'] ])->where(['read_msg'=>'0'])->count();
                                $msg->read_count = $count_read; 
                            }
                            $countpp = $msg->read_count;
                        }
                        else
                        {
                            $countpp = 0;
                        }
                        
                        $this->set("countprmsg", $countpp); 
                        
                        $notification_table = TableRegistry::get('notification');
                        $notify_recv_tbl = TableRegistry::get('received_notfiy');
                        
                        /*$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['role_id' => $parentsesid, 'role_type' => 'parent' ])->first() ;
                        if(!empty($notify_recv))
                        {
                            date_default_timezone_set('Africa/Kinshasa');
                            $lastdate = $notify_recv['created_date'];
                            $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']] ])->toArray() ;
                        }
                        else
                        {
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $parent_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']] ])->toArray() ;
                        //}
                        $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'created_date >=' => $parent_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']]  ])->toArray() ;
                        */
                        /*foreach($rcve_nottzCount as $notify)
                        {
                            //echo $notify['id'].",";
                            $notify->tchrscholid ='';
                            if($notify['added_by'] == "teachers")
                            {
                                $tchrid = $notify['teacher_id'];
                                $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                                $notify->tchrscholid = $retrieve_emp['school_id'];
                                
                                $notify->sentby = $retrieve_emp['l_name']." ". $retrieve_emp['f_name'] ;
                                $retrieve_company = $comp_table->find()->where([ 'id' => $retrieve_emp['school_id']])->first() ;
                                $notify->sentlogo = $retrieve_company['comp_logo'];
                                
                                $tchrcls = [];
                                $tchrsubj = [];
                                $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->group(['class_id'])->toArray() ;
                                foreach($retrieve_empcls as $empcls)
                                {
                                    $tchrcls[] = $empcls['class_id'];
                                    $tchrsubj[] = $empcls['subject_id'];
                                }
                                $notify->tchrcls = implode(",", $tchrcls);
                                $notify->tchrsubj = implode(",", $tchrsubj);
                                
                                $snames = [];
                                foreach($retrieve_stud as $stud)
                                {
                                    if(in_array($stud['class'], $tchrcls))
                                    {
                                       $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                                        //$notify->sentlogo = $retrieve_company['comp_logo'];
                                    }
                                }
                                $studname = implode(",", $snames);
                                $notify->studentname = $studname;
                                
                            }
                            elseif($notify['added_by'] == "school")
                            {
                                 $retrieve_company = $comp_table->find()->where([ 'id' => $notify['school_id']])->first() ;
                                 
                                 $notify->sentby = $retrieve_company['comp_name'] ;
                                 $notify->sentlogo = $retrieve_company['comp_logo'];
                     
                                $clsids = explode(",", $notify['class_ids']);
                                $snames = [];
                                foreach($retrieve_stud as $stud)
                                {
                                    if(in_array($stud['class'], $clsids))
                                    {
                                       $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                                    }
                                }
                                $studname = implode(",", $snames);
                                $notify->studentname = $studname;
                                 
                            }
                        }     */
                        $clssubjcts_table = TableRegistry::get('class_subjects');
                        $retrieve_clssub = $clssubjcts_table->find()->where(['class_id' => $student_details[0]['class'] ])->first() ;
                        
                        /*if(!empty($rcve_nottzCount))
                        {
                            $countNotifctn = 0;
                            foreach($rcve_nottzCount as $value)
                            {
                                //echo $value['id'].",";
                                if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
                                { 
                                    $countNotifctn = 1;
                                }
                                elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
                                { 
                                    $countNotifctn = 1;
                                }
                                elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
                                { 
                                    $tchrscls = explode(",", $value['tchrcls']);
                                    if(array_intersect($studclass, $tchrscls))
                                    {
                                        $countNotifctn = 1;
                                    }
                                    else
                                    {
                                        $countNotifctn = 0;
                                    }
                                } 
                                else
                                {
                                    if($value['class_opt'] == "multiple")
                                    {
                                        $incls = [];
                                        $clsids = explode(",", $value['class_ids']);
                                        foreach($studclass as $sdcls)
                                        {
                                            if(in_array($sdcls, $clsids))
                                            {
                                                $incls[] = 1;
                                            }
                                            else
                                            {
                                                $incls[] = 0;
                                            }
                                        }
                                        $countNotifctn = in_array("1", $incls) ? "1" : "0";
                                    }
                                    else
                                    {
                                        $parids = explode(",", $value['parent_ids']);
                                        if(in_array($parntid, $parids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                    
                                }
                                if($countNotifctn == 1)
                                {
                                    $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' ])->count() ;
                                    if($notify_recv == 0)
                                    {
                                        $chcked_notfy = $notify_recv_tbl->newEntity();
                                        $chcked_notfy->notify_id = $value['id'];
                                        $chcked_notfy->status = 0;
                                        $chcked_notfy->role_id = $parent_details[0]['id'];
                                        $chcked_notfy->role_type = 'parent';
                                        $chcked_notfy->created_date = time();
                                        if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                        {   
                                            $res = [ 'result' => 'success'];
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'failed'  ];
                                        } 
                                    }
                                }
                            }
                            //$countNoti = array_sum($countNotifctn);
                            $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $parent_details[0]['id'], 'status' => 0, 'role_type' => 'parent' ])->count() ;
                            $countNoti = $notifyrecvb; 
                            
                        }
                        else
                        {
                            $countNoti = 0;
                        }*/
                        
$comp_table = TableRegistry::get('company');                         
$emp_table = TableRegistry::get('employee');  
$stud_table = TableRegistry::get('student');
$retrieve_stud = $stud_table->find()->where([ 'md5(parent_id)' => $pid, 'session_id' => $session_id])->toArray() ;
$tchrscholid = '';
$empcls_table = TableRegistry::get('employee_class_subjects');
$notify_recv_tbl = TableRegistry::get('received_notfiy');
$schoolid= [];
$studid = [];
$studclass =[];
foreach($retrieve_stud as $stud)
{
    $schoolid[]  = $stud['school_id'];
    $studid[] = $stud['id'];
    $studclass[]  = $stud['class'];
}
//print_r($schoolid);
$retrieve_notify = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $parent_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'parents']]  ])->order(['id' => 'desc'])->toArray() ;
foreach($retrieve_notify as $notify)
{
    $notify->tchrscholid ='';
    $notify->sentlogo = 'you-meheaderlogo.png';
    if($notify['added_by'] == "teachers")
    {
        $tchrid = $notify['teacher_id'];
        $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
        $notify->tchrscholid = $retrieve_emp['school_id'];
        
        //$notify->sentby = $retrieve_emp['l_name']." ". $retrieve_emp['f_name'] ;
        $retrieve_company = $comp_table->find()->where([ 'id' => $retrieve_emp['school_id']])->first() ;
        //$notify->sentlogo = $retrieve_company['comp_logo'];
        
        $tchrcls = [];
        $tchrsubj = [];
        $retrieve_empcls = $empcls_table->find()->where([ 'emp_id' => $tchrid])->group(['class_id'])->toArray() ;
        foreach($retrieve_empcls as $empcls)
        {
            $tchrcls[] = $empcls['class_id'];
            $tchrsubj[] = $empcls['subject_id'];
        }
        $notify->tchrcls = implode(",", $tchrcls);
        $notify->tchrsubj = implode(",", $tchrsubj);
        
        $snames = [];
        foreach($retrieve_stud as $stud)
        {
            if(in_array($stud['class'], $tchrcls))
            {
               $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                //$notify->sentlogo = $retrieve_company['comp_logo'];
            }
        }
        $studname = implode(",", $snames);
        $notify->studentname = $studname;
        
    }
    elseif($notify['added_by'] == "school")
    {
         $retrieve_company = $comp_table->find()->where([ 'id' => $notify['school_id']])->first() ;
         
         $notify->sentby = $retrieve_company['comp_name'] ;
         $notify->sentlogo = $retrieve_company['comp_logo'];
         
         
        $clsids = explode(",", $notify['class_ids']);
        $snames = [];
        foreach($retrieve_stud as $stud)
        {
            if(in_array($stud['class'], $clsids))
            {
               $snames[] = $stud['l_name']." ". $stud['f_name'] ;
                //$notify->sentlogo = $retrieve_company['comp_logo'];
            }
        }
        $studname = implode(",", $snames);
        $notify->studentname = $studname;
         
    }
}  
$notifybold = [];
foreach($retrieve_notify as $value)
{
    
    if($value['notify_to'] == "all" && $value['added_by'] == "superadmin")
    { 
        $display = 1;
    }
    elseif($value['notify_to'] == "all" && $value['added_by'] == "school" && in_array($value['school_id'], $schoolid))
    { 
        $display = 1;
    }
    elseif($value['added_by'] == "teachers" && in_array($value['tchrscholid'], $schoolid))
    { 
        $tchrscls = explode(",", $value['tchrcls']);
        if(array_intersect($studclass, $tchrscls))
        {
            $display = 1;
        }
        else
        {
            $display = 0;
        }
    }
    else
    {
        $display = 0;
        if($value['class_opt'] == "multiple")
        {
            $incls = [];
            $clsids = explode(",", $value['class_ids']);
            foreach($studclass as $sdcls)
            {
                if(in_array($sdcls, $clsids))
                {
                    $incls[] = 1;
                }
                else
                {
                    $incls[] = 0;
                }
            }
            $display = in_array("1", $incls) ? "1" : "0";
        }
        elseif($value['class_opt'] == "single")
        {
            $parids = explode(",", $value['parent_ids']);
          
            if(in_array($parntid, $parids))
            {
                $display = 1;
            }
            else
            {
                $display = 0;
            }
        }
        
    }
    
            
    //echo $value['id']."--".$display.","; 
    if($display == 1)
    {
        $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' ])->count() ;
        if($notify_recv == 0)
        {
            $chcked_notfy = $notify_recv_tbl->newEntity();
            $chcked_notfy->notify_id = $value['id'];
            $chcked_notfy->status = 0;
            $chcked_notfy->role_id = $parent_details[0]['id'];
            $chcked_notfy->role_type = 'parent';
            $chcked_notfy->created_date = time();
            if($saved = $notify_recv_tbl->save($chcked_notfy) )
            {   
                $res = [ 'result' => 'success'];
            }
            else
            {
                $res = [ 'result' => 'failed'  ];
            } 
            
        }
        $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $parent_details[0]['id'], 'notify_id' => $value['id'], 'role_type' => 'parent' , 'status' => 0 ])->count() ;
        if($notifyrecvb != 0)
        {
            $notifybold[] = 1;
            //echo $value['id']." ,";
        }
        
    }
    
}

$countNoti = array_sum($notifybold);                    
$this->set("parntnoti_count", $countNoti); 
                        
                        $sidebar_prefix = "";
                          
                        $this->set("controllerName", $controllerName);  
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url);   
                        $this->set("parent_details", $parent_details);
                        $this->request->session()->write('parent_id', $parent_details[0]['id']);    
                        $this->request->session()->write('session_id', $session_id);    
                    }
                    else
                    {
                        setcookie('id', '', time()-1000  , $baseurl );
    		            setcookie('logtype', '', time()-1000  , $baseurl );
       	                setcookie('www', '', time()-1000  , $baseurl );
                        return $this->redirect([ 'controller' => 'login' ]);  
                    } 
	            }
                else
                { 
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
    	            setcookie('www', '', time()-1000  , $baseurl );
    	            return $this->redirect([ 'controller' => 'login' ]);	
                }
    
            } 
        }
        elseif($stid != "" )
        {
            if($logtype == md5('Student') )
            {
                $users_table = TableRegistry::get('users');
            	$company_table = TableRegistry::get('company');
            	$student_table = TableRegistry::get('student');	
            	$session_table = TableRegistry::get('session');
                $student_sidebar_table = TableRegistry::get('student_sidebarmenu');
                
                $student_details = $student_table->find()->select(['student.id' , 'student.created_date' , 'student.adm_no' , 'student.contactyoume' , 'student.session_id', 'student.library_access' , 'student.class' ,'student.f_name' , 'student.l_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_for_sms', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'student.school_id', 'company.primary_color', 'company.button_color', 'company.comp_name'  ])->join(['company' => 
                    [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ]
                ])->where(['md5(student.id)' => $stid ])->toArray(); 

                //print_r($student_details); die;
                $this->request->session()->write('school_id', $student_details[0]['school_id']);
                $this->request->session()->write('company_id', $student_details[0]['school_id']);
                $this->request->session()->write('session_id', $student_details[0]['session_id']);
                $this->request->session()->write('class_id', $student_details[0]['class']);
                $this->request->session()->write('student_id', $student_details[0]['id']);
                
                $this->request->session()->write('baseurl', $this->base_url);
                $this->request->session()->write('loginuser', "student");
                
               
             //  echo $this->getfull_url;
              
                $controller = $this->request->getParam('controller');
                $action = $this->request->getParam('action');
                
                if(strtolower($controller) == "studentdashboard" && $action == "index")
                {
                    $control = "Student Dashboard";
                }
                else
                {
                    $control = ucfirst($controller);
                }

                if($action == "index")
                {
                    $method = "";
                }
                else
                {
                    $method = " (".ucfirst($action).")";
                }
                $trackmenu = $control.$method;
                $tracks = 1;
                if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logouts") 
                {
                    $tracks = 0;
                }
                
                if($tracks == 1)
                {
                    $trackid = $_SESSION['student_tracklogintime']; 
                    $track = $logtrack_table->newEntity();
                    $track->type =  "student"  ;
                    $track->login_id =  $student_details[0]['id'] ;
                    $track->track_id =  $trackid;
                    $track->school_id = $student_details[0]['school_id'];
                    $track->menu_tracking = $control.$method;
                    $track->fullurl = $this->getfull_url; 
                    $track->time = time();
                    $saved = $logtrack_table->save($track) ;
                }
		
                
                $schoolid  = $student_details[0]['school_id'];
                $studid  = $student_details[0]['id'];
                $studclass  = $student_details[0]['class'];
	            $libaccess  = $student_details[0]['library_access'];
	            
     	        $retrieve_company_status = $company_table->find()->select(['id'])->where(['id' => $student_details[0]['school_id'] , 'status' => 0 ])->count();
	            if($retrieve_company_status == 0)
                {
                if( !empty($student_details[0]) ) 
                {
                    if($student_details)
                    {
                        $this->set("student_details", $student_details);
                        if($controllerName == "Studentdashboard")
                        {
                            $mainpage = $studdash ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;

                            }
                            else{
                                
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                            }

                        }
                        $sidebar_prefix = "";
                                                                               
                        $spec_cond = md5($student_details[0]['id']) ;
                        $studname = $student_details[0]['f_name']." ". $student_details[0]['l_name'];
                        $studemail = $student_details[0]['email'];
                        $userid = $this->Cookie->read('id');
                       
                        $this->set("controllerName", $controllerName);  
                        $this->set("conferenceuser", $studname); 
                        $this->set("conferenceuseremail", $studemail); 
                        $this->set("moderatorlive", "student"); 
                        $this->set("meetingname", $meetingid); 
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url);   
                        $this->set("libaccess", $libaccess);  
                        //$this->set("stud_details", $student_details);  
                        
                        
                        $notification_table = TableRegistry::get('notification');
                        $notify_recv_tbl = TableRegistry::get('received_notfiy');
                        
                        //$notify_recv = $notify_recv_tbl->find()->select(['created_date'])->where(['md5(role_id)' => $stid, 'role_type' => 'student' ])->toArray() ;
                        /*if(!empty($notify_recv))
                        {
                            date_default_timezone_set('Africa/Kinshasa');
                            $lastdate = $notify_recv[0]['created_date'];
                            $notifytime = strtotime(date('d-m-Y h:i A', $lastdate)); 
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'sc_date_time >=' => $notifytime, 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']] ])->toArray() ;
                        }
                        else
                        {*/
                            $rcve_nottzCount = $notification_table->find()->where([ 'sent_notify' => 1, 'status' => 1, 'created_date >=' => $student_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']] ])->toArray() ;
                        //}
                        
                        
                        $retrieve_notify = $notification_table->find()->where(['sent_notify' => 1, 'status' => 1, 'created_date >=' => $student_details[0]['created_date'], 'OR' => [['notify_to' => 'all'], ['notify_to' => 'students']]  ])->toArray() ;
                        $tchrscholid = '';
                        $emp_table = TableRegistry::get('employee');
                        foreach($rcve_nottzCount as $notify)
                        {
                            $notify->tchrscholid = '';
                            if($notify['notify_to'] == "all" && $notify['added_by'] == "teachers")
                            {
                                $tchrid = $notify['teacher_id'];
                                $retrieve_emp = $emp_table->find()->where([ 'id' => $tchrid])->first() ;
                                $notify->tchrscholid = $retrieve_emp['school_id'];
                            }
                        }
                       // echo $student_details[0]['school_id'];
                        //print_r($rcve_nottzCount);
                        $countNotify = [];
                        if(!empty($rcve_nottzCount))
                        {
                            
                            $countNotifctn = 0; 
                            foreach($rcve_nottzCount as $notzcount)
                            { //echo $notzcount['id'];
                               // if($notzcount['id'] == "1142") {echo $notzcount['tchrscholid']; }
                                if($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "superadmin")
                                {
                                    $countNotifctn = 1;
                                }
                                elseif($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "school" && $notzcount['school_id'] == $student_details[0]['school_id'])
                                {
                                    $countNotifctn = 1;
                                }
                                elseif($notzcount['notify_to'] == "all" && $notzcount['added_by'] == "teachers" && trim($student_details[0]['school_id']) == $notzcount['tchrscholid'])
                                {
                                   // echo $notzcount['id'];
                                    $countNotifctn = 1;
                                }
                                else
                                {
                                    if($notzcount['class_opt'] == "multiple")
                                    {
                                        $clsids = explode(",", $notzcount['class_ids']);
                                        if(in_array($studclass, $clsids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                    else
                                    {
                                        $studids = explode(",", $notzcount['student_ids']);
                                  
                                        if(in_array($studid, $studids))
                                        {
                                            $countNotifctn = 1;
                                        }
                                        else
                                        {
                                            $countNotifctn = 0;
                                        }
                                    }
                                    
                                }
                                
                                if($countNotifctn == 1)
                                {
                                   
                                    $notify_recv = $notify_recv_tbl->find()->select(['id'])->where(['role_id' => $student_details[0]['id'], 'notify_id' => $notzcount['id'], 'role_type' => 'student' ])->count() ;
                                    if($notify_recv == 0)
                                    {
                                        $chcked_notfy = $notify_recv_tbl->newEntity();
                                        $chcked_notfy->notify_id = $notzcount['id'];
                                        $chcked_notfy->status = 0;
                                        $chcked_notfy->role_id = $student_details[0]['id'];
                                        $chcked_notfy->role_type = 'student';
                                        $chcked_notfy->created_date = time();
                                        if($saved = $notify_recv_tbl->save($chcked_notfy) )
                                        {   
                                            $res = [ 'result' => 'success'];
                                        }
                                        else
                                        {
                                            $res = [ 'result' => 'failed'  ];
                                        } 
                                    }
                                    $notifyrecvb = $notify_recv_tbl->find()->where(['role_id' => $student_details[0]['id'], 'notify_id' => $notzcount['id'], 'status' => 0, 'role_type' => 'student' ])->count() ;
                                    if($notifyrecvb != 0):
                                        $countNotify[] = $notifyrecvb; 
                                    endif;
                                }
                                
                            }
                            $countNoti = array_sum($countNotify);
                            
                        }
                        else
                        {
                            $countNoti = 0;
                        }
                    
                        $this->set("studntnoti_count", $countNoti); 
                        
                        
                        $compid = $this->request->session()->read('company_id');
                        $company_table = TableRegistry::get('company');
                        $studsessid = $this->request->session()->read('student_id');
                        $message_table = TableRegistry::get('messages');
                        $retrieve_msg_table = $message_table->find()->where(['school_id' => $compid])->order(['id' => 'DESC'])->toArray();
                        
                        //print_r($retrieve_msg_table); die;
                    if(!empty($retrieve_msg_table))   
                    {
                        $student_table = TableRegistry::get('student');
                        foreach($retrieve_msg_table as $key =>$msg)
                		{
                		    $count_read = $message_table->find()->where(['school_id' => $compid, 'OR' => ['to_id'=> $studsessid, 'from_id'=> $studsessid], 'OR' => ['to_type'=>'student', 'from_type'=>'school'], 'read_msg'=>'0'])->count();
                		    $msg->read_count = $count_read; 
                		}
                		
                		$count = $msg->read_count;
                    }
                    else
                    {
                        $count = 0;
                    }
                	//echo $count; die;
                        $this->set("countunreadmsg", $count); 
                        
                            $student_table = TableRegistry::get('student');
                            $studsidebar_table = TableRegistry::get('student_sidebarmenu');
                            
                            $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $stid ])->first();
                            
                            $sid = $stuid_retrieve['id'];
                    		$getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
                   
                            if($getcount != 0)
                            {
                                $getside = $studsidebar_table->find()->where(['student_id'=> $sid ])->first();
                                if($getside['status'] == 1)
                                {     
                                    $side = "style='display:block !important;'";
                                }
                                else
                                {
                                    $side = "style='display:none !important;'";
                                }
                            }
                            else
                            {
                               $side = "style='display:none !important;'";
                            }
                            
                        $this->set("showsidebar", $side); 
                    }
                }
                else
                {
                    setcookie('id', '', time()-1000  , $baseurl );
		            setcookie('logtype', '', time()-1000  , $baseurl );
   	                setcookie('www', '', time()-1000  , $baseurl );

                    return $this->redirect([ 'controller' => 'login' ]);  
                } 

	        }
            else
            {  
                setcookie('id', '', time()-1000  , $baseurl );
                setcookie('logtype', '', time()-1000  , $baseurl );
	            setcookie('www', '', time()-1000  , $baseurl );
	    		
	            return $this->redirect(['controller' => 'login']);	
            }

            } 
        }
        elseif($cid != "")
        {
        	$cv_table = TableRegistry::get('canteen_vendor');
        	$session_table = TableRegistry::get('session');
        	
        	$retrieve_cv = $cv_table->find()->where(['md5(id)' => $cid ]) ; 
        	$cvndr_details = $retrieve_cv->toArray() ; 
         
        	$this->request->session()->write('vendor_id', $cvndr_details[0]['id']);
        	$this->request->session()->write('baseurl', $this->base_url);
        	$this->request->session()->write('loginuser', "canteen vendor");
        	
            $controller = $this->request->getParam('controller');
            $action = $this->request->getParam('action');
            
            if(strtolower($controller) == "Cvendordashboard" && $action == "index")
            {
                $control = $cantvdash;
            }
            else
            {
                $control = ucfirst($controller);
            }

            if($action == "index")
            {
                $method = "";
            }
            else
            {
                $method = " (".ucfirst($action).")";
            }
            
            $trackmenu = $control.$method;
            $tracks = 1;
            if($trackmenu == "Login (Checkuser)" || $trackmenu == "Login (Usertime)" || $trackmenu == "Login (Logincheck)" || $trackmenu == "Login" || $trackmenu == "Logoutc") 
            {
                $tracks = 0;
            }
            
            if($tracks == 1)
            {
                $trackid = $_SESSION['canteen_tracklogintime']; 
                $track = $logtrack_table->newEntity();
                $track->type =  "canteen_vendor"  ;
                $track->login_id =  $cvndr_details[0]['id'] ;
                $track->track_id =  $trackid;
                $track->menu_tracking = $control.$method;
                $track->fullurl = $this->getfull_url; 
                $track->time = time();
                $saved = $logtrack_table->save($track) ;
            }
            
        	if(!empty($cvndr_details[0]))
        	{
    			$this->set("cvndr_details", $cvndr_details);
    
    			if($controllerName == "Cvendordashboard")
    			{
    				$mainpage = $cantdash;
    				$subpage = $mainpage ;
    			} 
    			else
    			{
    				$mainpage = $controllerName ;
    				if($actionName == "index")
    				{
    					$subpage = $controllerName." List"  ;
    				}
    				else{
    					$subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;
    				}
    			}
    			$sidebar_prefix = "";
    		    $this->set("cvndr_details", $cvndr_details); 
    			$this->set("controllerName", $controllerName);  
    			$this->set("actionName", $actionName);  
    			$this->set("subpage", $subpage); 
    			$this->set("mainpage", $mainpage); 
    			$this->set("sidebar_prefix", $sidebar_prefix);
    			$this->set("baseurl", $this->base_url);   
    		//	print_r($cvndr_details); die;
        	}
        	else
        	{
        	    //echo "dsc"; die;
        		setcookie('cid', '', time()-1000  , $baseurl );
        		return $this->redirect('/login');  
        	} 
            
        
        }
    }

    public function json($data){
        $this->response->type('json');
        $this->response->body(json_encode($data));
        return $this->response;
    }
    
    public function sendUserEmail($to,$from,$name,$subject,$msg,$sender,$attach){
       $email = new Email('default');
       $email
            ->transport('default')
            ->from([$from => $from])
            ->to($to)
            ->subject($subject)
            ->attachments($attach)
            ->replyTo($from)
             ->emailFormat('html')
            ->template('replymessage')
            ->viewVars(array('htmlContent' => $msg))
            ->send('Hi did you receive the mail');

    }

	public function sendEmailwithoutattach($to,$from,$from_name,$subject,$msg,$to_name,$template){
	    
        $email = new Email('default');
        $email
            ->transport('default')
            ->from([$from => $from])
            ->to($to)
            ->subject($subject)
            ->replyTo($from)
            ->emailFormat('html')
            ->template($template)
            ->viewVars(array('htmlContent' => $msg, 'username'=>$to_name))
            ->send('Hi did you receive the mail');
        //print_r($email); die;    

    }	
    
    public function senduserEmailwithoutattach($to,$from,$from_name,$subject,$msg,$to_name,$template){
     
       $email = new Email('default');
       $email
            ->transport('default')
            ->from([$from => $from])
            ->to($to)
            ->subject($subject)
            ->replyTo($from)
            ->emailFormat('html')
            ->template($template)
            ->viewVars(array('htmlContent' => $msg, 'username'=>$to_name))
            ->send('Hi did you receive the mail');

    }	
   
}

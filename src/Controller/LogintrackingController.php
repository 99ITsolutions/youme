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
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class LogintrackingController extends AppController
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
     
     
    public function schoolreport()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        //print_r($_COOKIE); die;
        $sid = $this->Cookie->read('sid'); 
        
        $session_table = TableRegistry::get('session');
        $logtrack_table = TableRegistry::get('track_logged');
        $comp_table = TableRegistry::get('company');
        
        if(!empty($sid)) {
		$session_id = $this->Cookie->read('sessionid');
		$retrieve_comp = $comp_table->find()->where(['status' => 1 ])->toArray() ;
		
		if(!empty($_POST))
		{
	        $clsid = '';
	        $sclid = $this->request->data('schoollist');
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
		    if($startdate1 < $enddate1) 
		    {
    		    $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $sclid, 'type' => 'school'])->order(['id' => 'DESC'])->toArray() ;
		        $error = "";
		    }
		    else
		    {
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
                    if($langlbl['id'] == '1611') { $strtend = $langlbl['title'] ; } 
                } 
		        $retrieve_meetings = '';
		        $error = $strtend;
		    }
		   
		}
		else
		{
		   
		    $retrieve_meetings = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $sclid = '';
	        $error = '';
		}
		$this->set('error', $error);
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("sclid", $sclid);
		$this->set('comp_details', $retrieve_comp);
	
		$this->set("logdetails", $retrieve_log);
        $this->viewBuilder()->setLayout('usersa');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function activitytracker()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sclid = $this->Cookie->read('id'); 
        if($sclid == "")
        {
            $compid = $this->request->session()->read('company_id');
            $sclid = md5($compid);
        }
        
        $trackid = $this->request->data('id');
        $logtrack_table = TableRegistry::get('logged_tracking');
        $tracklog_table = TableRegistry::get('track_logged');
        
        if(!empty($sclid)) {
    	    $retrieve_log = $logtrack_table->find()->where(['track_id' => $trackid])->order(['logged_tracking.id' => 'DESC'])->toArray() ;
	        $data = '';
		    foreach($retrieve_log as $value)
		    {
		        $data .= '<tr>
		            <td>
                        <span>'.date("h:i:s A", $value['time']).'</span>
                    </td>
                    <td>
                        <span class="mb-0 font-weight-bold">'.ucfirst($value['menu_tracking']).'</span>
                    </td>
                    <td>
                        <span>'.$value['fullurl'].' </span>
                    </td>
                    
                </tr>';
		    }
		    return $this->json($data);
    		    
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function subadminreport()
    { 
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sid = $this->Cookie->read('sid'); 
       
        $logtrack_table = TableRegistry::get('track_logged');
        $users_table = TableRegistry::get('users');
        
        if(!empty($sid)) {
		$session_id = $this->Cookie->read('sessionid');
		$retrieve_users = $users_table->find()->where(['role' => 3, 'status' => 1 ])->toArray() ;
		//print_r($retrieve_users); die;
		if(!empty($_POST))
		{
	        $subid = $this->request->data('listsclsub');
		    
		    $startdate1 = strtotime($this->request->data('startdate')." 12:00 AM");
		    $enddate1 = strtotime($this->request->data('enddate')." 11:59 PM");
		    if($startdate1 < $enddate1) 
		    {
    		    $retrieve_log = $logtrack_table->find()->where(['login_time >=' => $startdate1, 'login_time <=' => $enddate1, 'login_id' => $subid, 'type' => 'super_subadmin'])->order(['id' => 'DESC'])->toArray() ;
		    
    		    $error = "";
		    }
		    else
		    {
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
                    if($langlbl['id'] == '1611') { $strtend = $langlbl['title'] ; } 
                } 
		        $retrieve_log = '';
		        $error = $strtend;
		    }
		   
		}
		else
		{
		    $retrieve_log = '';
		    $startdate1 = '';
	        $enddate1 = '';
	        $subid = '';
	        $error = '';
		}
		$this->set('error', $error);
		$this->set('startdate1', $startdate1);
		$this->set("enddate1", $enddate1);
		$this->set("subid", $subid);
		
		$this->set('subsuper_details', $retrieve_users);
		$this->set("logdetails", $retrieve_log);
        $this->viewBuilder()->setLayout('usersa');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
}

  


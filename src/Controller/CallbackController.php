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
class CallbackController extends AppController
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
        
        if(empty($_SESSION))
        {
            $_SESSION['loginuser'] = '';
        }
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $hostname = "localhost";
        $username = "youmeglo_globaluser";
        $password = "DFmp)9_p%Kql";
        $database = "youmeglo_globalweb";
        
        $con = mysqli_connect($hostname, $username, $password, $database); 
        if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 
        
        //if(!empty($_GET['meetingID']) && isset($_SESSION['loginuser']) && ($_SESSION['loginuser'] == 'superadmin'))
        if(!empty($_GET['meetingID']) &&  ($_SESSION['loginuser'] == 'superadmin'))
        {
         
                $mid = $_GET['meetingID'];
                
                $update = mysqli_query($con, "UPDATE `meet` SET meeting_status = 2 WHERE meeting_id = '".$mid."'");        
                
                $secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
                $string7 = "endmeetingID=".$mid."&password=123".$secret;
                $sh7 = sha1($string7);    
                $url ="https://meeting.you-me-globaleducation.org/bigbluebutton/api/end?meetingID=".$mid."&password=123&checksum=".$sh7;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL,$url);
                $result = curl_exec($ch);
                curl_close($ch); 
                $this->redirect('https://you-me-globaleducation.org/thank-you');
        }
        elseif(!empty($_GET['meetingID'])  && ($_SESSION['loginuser'] == 'teacher'))
        {
        
                $mid = $_GET['meetingID'];
                
                $update = mysqli_query($con, "UPDATE `meeting_link` SET meeting_status = 2 WHERE meeting_id = '".$mid."'") ;        
                
                $secret = "cHKXirxzWlzDZiN7NhmoI9jh8d89L3d9806DquT8Lo";
                $string7 = "endmeetingID=".$mid."&password=222".$secret;
                $sh7 = sha1($string7);    
                $url ="https://meeting.you-me-globaleducation.org/bigbluebutton/api/end?meetingID=".$mid."&password=222&checksum=".$sh7;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL,$url);
                $result = curl_exec($ch);
                curl_close($ch); 
                $this->redirect('https://you-me-globaleducation.org/thank-you');
        }
        else
        {
            $this->redirect('https://you-me-globaleducation.org/thank-you');
        }



    }
            
              
}

  


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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class LogoutkController  extends AppController
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
                    
                    $baseurl=$this->request->session()->read('baseurl');
                    $usersid = $this->request->session()->read('tid');
                    
                    $emp_table = TableRegistry::get('employee');
                    
                    $activ_table = TableRegistry::get('activity');
                    $activity = $activ_table->newEntity();
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->created = strtotime('now');
                    
                    /*setcookie('tid', '', time()-1000  , $baseurl );
                    unset($_COOKIE['tid']);*/
                    $logtrack_table = TableRegistry::get('track_logged');
                    $tid = $this->Cookie->read('tid');
                    if(!empty($tid))
                    {
                        $update_company = $emp_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $tid ])->execute()  ;
                        
                        $trackingid =$this->request->session()->read('teacher_tracklogintime'); 
                        $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time()])->where(['id' => $trackingid ])->execute()  ;
                        
                        
                        $activity->value = $this->Cookie->read('tid')   ;
                        $activity->action =  "Employee Logout"  ;
                        $saved = $activ_table->save($activity) ;
                        setcookie('tid', '', time()-1000  , $baseurl );
                        unset($_COOKIE['tid']);
                        unset($_SESSION['teacher_tracklogintime']);
                    }
                    
                    return $this->redirect('/login/') ;       

            }
            
           
}

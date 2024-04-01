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
class LogoutController  extends AppController
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
                $baseurl=$this->request->session()->read('baseurl');
                
                $scl_table = TableRegistry::get('company');
                $sclsub_table = TableRegistry::get('school_subadmin');
                $logtrack_table = TableRegistry::get('track_logged');
                $activ_table = TableRegistry::get('activity');
                $activity = $activ_table->newEntity();
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->created = strtotime('now');
                
                
                
                $compid = $this->request->session()->read('company_id');
                $meetingid = $this->Cookie->read('meetingid');
                $id = $this->Cookie->read('id');
                $subid = $this->Cookie->read('subid');
                if(!empty($id))
                {
                    $activity->value = $this->Cookie->read('id')   ;
                    $activity->action =  "School Logout";
                    $saved = $activ_table->save($activity) ;
                    $update_company = $scl_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $id ])->execute()  ;
                    
                    $trackingid =$this->request->session()->read('scl_tracklogintime'); 
                    $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time()])->where(['id' => $trackingid ])->execute()  ;
                    
                    setcookie('id', '', time()-1000  , $baseurl );
                    unset($_SESSION['scl_tracklogintime']);
                }
                elseif(!empty($subid))
                {
                    $activity->value = $this->Cookie->read('subid')   ;
                    $activity->action =  "School Subadmin Logout"  ;
                    $saved = $activ_table->save($activity) ;
                    $update_company = $sclsub_table->query()->update()->set([ 'login_logout_key' => 0])->where(['md5(id)' => $subid ])->execute()  ;
                
                    $trackingid =$this->request->session()->read('sclsub_tracklogintime'); 
                    $update_tracklogout = $logtrack_table->query()->update()->set([ 'logout_time' => time()])->where(['id' => $trackingid ])->execute()  ;
                
                    setcookie('subid', '', time()-1000  , $baseurl );
                    unset($_SESSION['sclsub_tracklogintime']);
                }
               
                 
                setcookie('subid', '', time()-1000  , $baseurl );
                setcookie('meetingid', '', time()-1000  , $baseurl );
                setcookie('atoken', '', time()-1000  , $baseurl );
                setcookie('id', '', time()-1000  , $baseurl );
                setcookie('sesscode', '', time()-1000  , $baseurl );
                setcookie('www', '', time()-10  , $baseurl );
                
                
               

                return $this->redirect([
                    'controller' => 'login'
                ]);
            
            }
}

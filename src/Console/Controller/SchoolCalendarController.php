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
class SchoolCalendarController  extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $schoolid = $this->request->session()->read('company_id')   ;  
        $calendar_table = TableRegistry::get('calendar');
        if(!empty($schoolid))
        {
            $retrieve_calendar = $calendar_table->find()->where(['school_id' => $schoolid])->toArray() ;
            $this->set("calendar", $retrieve_calendar); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;  
        }
    }
    
    public function getevents()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $schoolid = $this->request->session()->read('company_id')   ;
        $calendar_table = TableRegistry::get('calendar');
        if(!empty($schoolid))
        {
            $retrieve_calendar = $calendar_table->find()->where(['school_id' => $schoolid])->toArray() ;
            return $this->json($retrieve_calendar);
        }
        else
        {
            return $this->redirect('/login/') ;  
        }
    }
    public function addevent()
    {
        if ($this->request->is('post') )
        {
            $calendar_table = TableRegistry::get('calendar');
            $company_table = TableRegistry::get('company');
            $activ_table =  TableRegistry::get('activity');
            
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $schoolid = $this->request->session()->read('company_id')   ;
            if(!empty($schoolid))
            {
                $retrieve_schoolid = $company_table->find()->select(['id'])->where(['id' => $schoolid])->toArray() ;
                $schoolID = $retrieve_schoolid[0]['id'];
                $now = date("d-m-Y h:i:s", strtotime('now'));
                
                $addevent = $calendar_table->newEntity();
                $addevent->title =  $this->request->data('title');
                $addevent->date =  date("Y-m-d", strtotime($this->request->data('start')));
                $addevent->status =  1  ;
                $addevent->created_date =  $now;
                $addevent->school_id =  $schoolID ;
                            
                if($saved = $calendar_table->save($addevent) )
                {   
                    $calendrid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Calendar Event Created"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                    $activity->value = md5($saved->id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) ){
                        $res = [ 'result' => 'success'  ];
    
                    }
                    else{
                        $res = [ 'result' => 'activity not saved'  ];
                    }
                }
                else{
                    $res = [ 'result' => 'calendar event not added'  ];
                }
            }
            else
            {
                return $this->redirect('/login/') ;  
            }        
        }
        else
        {
            $res = ['result' => 'invalid operation'];
        }
        return $this->json($res);
    }
    
    public function editevent(){   
        if ($this->request->is('post') ){
            $calendar_table = TableRegistry::get('calendar');
            $company_table = TableRegistry::get('company');
            $activ_table =  TableRegistry::get('activity');
            $title =  $this->request->data('title');
            $id =  $this->request->data('id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if($id)
            {   
                $stats = $calendar_table->query()->update()->set([ 'title' => $title])->where([ 'id' => $id  ])->execute();
    			
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Event changed Successfully!"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($id)    ;
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
                    $res = [ 'result' => 'Event Not changed'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }
    
            return $this->json($res);
        }
    }
        
        
    public function deleteevent(){
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $calendar_table = TableRegistry::get('calendar');
        $cid = (int) $this->request->data('id') ; 
        $activ_table = TableRegistry::get('activity');
        
        if($cid)
        {   
            $del = $calendar_table->query()->delete()->where([ 'id' => $cid ])->execute(); 
            if($del)
            { 
                $activity = $activ_table->newEntity();
                $activity->action =  "Calendar Event Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($cid)    ;
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
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }

        return $this->json($res);
    }
    
    
    
			
}

  


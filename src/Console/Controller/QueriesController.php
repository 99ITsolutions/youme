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
class QueriesController  extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     **/
     
    
    public function index()
    {   
        $queries_table = TableRegistry::get('message_queries');
        $queries_table1 = TableRegistry::get('you_me_queries');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_local = $queries_table->find()->where(['local_abroad' => 'local' , 'status' => 0 ])->count() ;
        $this->set("local_details", $retrieve_local); 
        $retrieve_abroad = $queries_table->find()->where(['local_abroad' => 'abroad', 'status' => 0  ])->count() ;
        $this->set("abroad_details", $retrieve_abroad);
        $retrieve_mentor = $queries_table1->find()->where(['queries_for' => 'mentor', 'status' => 0 ])->count();
        $this->set("mentor_details", $retrieve_mentor); 
        $retrieve_leader = $queries_table1->find()->where(['queries_for' => 'leader', 'status' => 0 ])->count();
        $this->set("leader_details", $retrieve_leader);
        $retrieve_scholar = $queries_table1->find()->where(['queries_for' => 'scholar', 'status' => 0 ])->count();
        $this->set("scholar_details", $retrieve_scholar); 
        $retrieve_intern = $queries_table1->find()->where(['queries_for' => 'intern', 'status' => 0 ])->count();
        $this->set("intern_details", $retrieve_intern); 
        
        $this->viewBuilder()->setLayout('usersa');
    }
            
    public function studyabroad()
    { 
        $queries_table = TableRegistry::get('message_queries');
        $retrieve_queries = $queries_table->find()->where(['local_abroad' => 'abroad' ])->order(['id' => 'desc'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'local_abroad' => 'abroad'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function localuniv()
    { 
        $queries_table = TableRegistry::get('message_queries');
        $retrieve_queries = $queries_table->find()->where(['local_abroad' => 'local' ])->order(['id' => 'desc'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'local_abroad' => 'local'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function mentorship()
    { 
        $queries_table = TableRegistry::get('you_me_queries');
        $retrieve_queries = $queries_table->find()->where(['queries_for' => 'mentor' ])->order(['id' => 'DESC'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'queries_for' => 'mentor'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    public function scholarship()
    { 
        $queries_table = TableRegistry::get('you_me_queries');
        $retrieve_queries = $queries_table->find()->where(['queries_for' => 'scholar' ])->order(['id' => 'DESC'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'queries_for' => 'scholar'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    public function internship()
    { 
        $queries_table = TableRegistry::get('you_me_queries');
        $retrieve_queries = $queries_table->find()->where(['queries_for' => 'intern' ])->order(['id' => 'DESC'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'queries_for' => 'intern'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    public function leadership()
    { 
        $queries_table = TableRegistry::get('you_me_queries');
        $retrieve_queries = $queries_table->find()->where(['queries_for' => 'leader' ])->order(['id' => 'DESC'])->toArray() ;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        $now = time();
        $update = $queries_table->query()->update()->set(['status' => 1 ])->where([ 'created_date <=' => $now, 'queries_for' => 'leader'])->execute();
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function delete()
    {
        $rid = $this->request->data('val') ;
        $youmequeries_table = TableRegistry::get('you_me_queries');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $catid = $youmequeries_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($catid)
        {                       
            $del = $youmequeries_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
            if($del)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Queries Deleted"  ;
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
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }
        return $this->json($res);
    }
    
    public function deleteall()
    {
		
        $uid = $this->request->data['val'] ; 
        $youmequeries_table = TableRegistry::get('you_me_queries');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        foreach($uid as $ids)
        {
            $stats = $youmequeries_table->query()->delete()->where([ 'id' => $ids ])->execute();
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
    
    public function deleteunivqueries()
    {
        $rid = $this->request->data('val') ;
        $msgqueries_table = TableRegistry::get('message_queries');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $catid = $msgqueries_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($catid)
        {                       
            $del = $msgqueries_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
            if($del)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Queries Deleted"  ;
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
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }
        return $this->json($res);
    }
    
    public function deleteallunivqueries()
    {
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        $uid = $this->request->data['val'] ; 
        $msgqueries_table = TableRegistry::get('message_queries');
        
        foreach($uid as $ids)
        {
            $stats = $msgqueries_table->query()->delete()->where([ 'id' => $ids ])->execute();
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

  


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
class MarketQueriesController  extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $queries_table = TableRegistry::get('market_place_queries');
        $retrieve_queries = $queries_table->find()->order(['id' => 'DESC'])->toArray() ;
        $this->set("queries_details", $retrieve_queries); 
        $this->viewBuilder()->setLayout('usersa');
    }
    
    public function getdesc()
    {
        $id = $this->request->data('id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $queries_table = TableRegistry::get('market_place_queries');
        $retrieve_queries = $queries_table->find()->where(['id' => $id])->order(['id' => 'DESC'])->first() ;
        $data = $retrieve_queries['description'];
        return $this->json($data);
    }
    
    public function delete()
    {
        $rid = $this->request->data('val') ;
        $marketqueries_table = TableRegistry::get('market_place_queries');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $catid = $marketqueries_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($catid)
        {                       
            $del = $marketqueries_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
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
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        $uid = $this->request->data['val'] ; 
        $marketqueries_table = TableRegistry::get('market_place_queries');
        
        foreach($uid as $ids)
        {
            $stats = $marketqueries_table->query()->delete()->where([ 'id' => $ids ])->execute();
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

  


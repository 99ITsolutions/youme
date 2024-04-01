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
class TermsController  extends AppController
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
                $termuser_table = TableRegistry::get('termuser');
                $terms_table = TableRegistry::get('terms');
                $empid=$this->request->session()->read('users_id');

                $retrieve_termuser = $termuser_table->find()->distinct('t.name')->select(['termuser.id','t.id' , 't.name','t.content' ])->join([  
                    't' => [
                        'table' => 'terms',
                        'type' => 'LEFT',
                        'conditions' =>  't.id =  termuser.termid' 
                    ]
                ])->where(['termuser.userid' => $empid ,'termuser.status'=> '0' ])->first() ;

                $this->set("retrieve_termuser_details", $retrieve_termuser); 
                $this->viewBuilder()->setLayout('login');
            }

            public function acceptform()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $termuser_table = TableRegistry::get('termuser');
                    $activ_table = TableRegistry::get('activity');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($this->request->data('accept') != ""   )
                    {   
                        $id = $this->request->data('id');
                        $accept = $this->request->data('accept') ;
                        if($accept == 'on')
                        {
                            // echo $this->Cookie->read('id');
                            $update_termuser = $termuser_table->query()->update()->set(['status' => 1 ])->where(['id' => $id ])->execute()  ;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Accept"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                            $activity->value = $id   ;
                            $activity->origin = $id   ;
                            $activity->created = strtotime('now');
                            $saved = $activ_table->save($activity) ;
                            $res = [ 'result' => 'success'  ];
                        }
                    }                                      
                    else
                    {
                        $res = [ 'result' => 'empty'  ];
                    }
                   
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);
            }
}

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
class QrcodepasscodeController  extends AppController
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
        $scl_table = TableRegistry::get('company');
        $compid =$this->request->session()->read('company_id');
        $sessionid = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
    		$retrieve_scl = $scl_table->find()->select(['qrcode_pin'])->where(['status' => 1, 'id' => $compid])->first() ;
            $this->set("scllist", $retrieve_scl);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
    
    public function qrpasscode()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {   
            $scl_table = TableRegistry::get('company');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
            $compid =$this->request->session()->read('company_id');
            $qrpass = $this->request->data('qrpass') ;
         
            if( $scl_table->query()->update()->set([  'qrcode_pin' => $qrpass])->where([ 'id' => $compid  ])->execute())
            {   
                $activity = $activ_table->newEntity();
                $activity->action =  "School Qrcode update"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('id');
                $activity->value = md5($compid); 
                $activity->created = strtotime('now');
                $save = $activ_table->save($activity) ;

                if($save)
                {   
                    $res = [ 'result' => 'success'  ];
                }
                else
                { 
                    $res = [ 'result' => 'activity'  ];
                }
            }
            else
            {
                $res = [ 'result' => 'failed'  ];
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }

        return $this->json($res);
    }  

}

  


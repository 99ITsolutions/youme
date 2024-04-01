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
class CodeconductController  extends AppController
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
            $codeconduct_table = TableRegistry::get('code_conduct');
            $sclid = $this->request->session()->read('company_id');
            
            $retrieve_code = $codeconduct_table->find()->where(['school_id' => $sclid])->first() ;
            $this->set("code_details", $retrieve_code); 
            $this->viewBuilder()->setLayout('user');
        }
        
        public function codeconductadd()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
           
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $codeconduct_table = TableRegistry::get('code_conduct');
                $codeagree_table = TableRegistry::get('code_agreement');
                $activ_table = TableRegistry::get('activity');
                $stud_table = TableRegistry::get('student');
                
                $sclid = $this->request->session()->read('company_id');
                $sessionid = $this->Cookie->read('sessionid'); 
                
                $retrieve_stud = $stud_table->find()->select(['id'])->where(['school_id' => $sclid, 'session_id' => $sessionid, 'status' => 1])->toArray() ;
                
                $retrieve_code = $codeconduct_table->find()->where(['school_id' => $sclid])->first() ;
                if(empty($retrieve_code))
                {
                    $cc = $codeconduct_table->newEntity();
                    $cc->school_id = $sclid;
                    $cc->title = $this->request->data('title');
                    $cc->content = $this->request->data('contentnotify');
                    $cc->created_date = time();
                    $update = $codeconduct_table->save($cc);       
                    $ccid = $update->id;
                }
                else
                {
                    $update = $codeconduct_table->query()->update()->set(['created_date' => time() , 'title' => $this->request->data('title') , 'content' =>  $this->request->data('contentnotify')])->where([ 'id' => $retrieve_code['id'] ])->execute();
                    $ccid = $retrieve_code['id'];
                    
                    $del_codeagree = $codeagree_table->query()->delete()->where([ 'code_id' => $ccid, 'school_id' => $sclid ])->execute(); 
                }
                
                if($update)
                {   
                    foreach($retrieve_stud as $stud_dtl)
                    {
                        $ca = $codeagree_table->newEntity();
                        $ca->school_id = $sclid;
                        $ca->code_id = $ccid;
                        $ca->student_id = $stud_dtl['id'];
                        $ca->status = 0;
                        $ca->read_date = time();
                        $stu_ca = $codeagree_table->save($ca);       
                        
                    }
                    
                    $galleryid = $ccid;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Codeof Conduct Added/Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('id');
                    $activity->value = md5($galleryid); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;
                    
                    if($save)
                    {   
                        $res = [ 'result' => 'success'];
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
        
        public function view()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $codeconduct_table = TableRegistry::get('code_conduct');
            $sclid = $this->request->session()->read('company_id');
            
            $retrieve_code = $codeconduct_table->find()->where(['school_id' => $sclid])->first() ;
            $this->set("code_details", $retrieve_code); 
            $this->viewBuilder()->setLayout('user');
        }
      
}

  




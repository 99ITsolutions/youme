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
class FeesController  extends AppController
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
                $fees_table = TableRegistry::get('fees');
                $compid = $this->request->session()->read('company_id');

                $retrieve_fees = $fees_table->find()->select(['fees.id', 'fees.sess_name', 'fees.adm_fee' , 'fees.status', 'class.c_name', 'class.c_section' ])->join(['class' => 
                        [
                        'table' => 'class',
                        'type' => 'LEFT',
                        'conditions' => 'class.id = fees.class'
                    ]
                ])->where([ 'fees.school_id '=> $compid])->toArray() ;
                
                $this->set("fees_details", $retrieve_fees); 
                $this->viewBuilder()->setLayout('user');
            }

            public function add()
            {   
                
                $compid = $this->request->session()->read('company_id');
                $class_table = TableRegistry::get('class');

                $retrieve_class = $class_table->find()->select(['id' , 'c_name' , 'c_section' ])->where(['school_id' => $compid ])->toArray();

                $this->set("class_details", $retrieve_class); 
                $this->viewBuilder()->setLayout('user');
            }

            public function addfee(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $compid = $this->request->session()->read('company_id');  
                    $fees_table = TableRegistry::get('fees');
                    $activ_table = TableRegistry::get('activity');
                    
                    $retrieve_fees = $fees_table->find()->select(['id'  ])->where(['class' => $this->request->data('class'), 'school_id' => $compid ])->count() ;
                   
                    if($retrieve_fees == 0 )
                    {   
                        
                        $fees = $fees_table->newEntity();

                        $fees->sess_name =  $this->request->data('sess_name')  ;
                        $fees->class =  $this->request->data('class')  ;
                        $fees->reg_fee = $this->request->data('reg_fee') ;
                        $fees->adm_fee = $this->request->data('adm_fee') ;
                        $fees->annual_d_fee = $this->request->data('annual_d_fee') ;
                        $fees->tuition_fee = $this->request->data('tuition_fee') ;
                        $fees->child_fund = $this->request->data('child_fund') ;
                        $fees->misc = $this->request->data('misc') ;
                        $fees->comp_fee = $this->request->data('comp_fee') ;
                        $fees->exm_fee = $this->request->data('exm_fee') ;
                        $fees->late_fee =  $this->request->data('late_fee');
                        $fees->sec_dep =  $this->request->data('sec_dep');
                        $fees->assig_fee =  $this->request->data('assig_fee');
                        $fees->science_fee =  $this->request->data('science_fee');
                        $fees->sports_fee =  $this->request->data('sports_fee');
                        $fees->library_fee =  $this->request->data('library_fee');
                        $fees->status =  $this->request->data('status');
                        $fees->repo_crd_fee =  $this->request->data('repo_crd_fee');
                        $fees->school_id =  $compid;
                    
                        if($saved = $fees_table->save($fees) )
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Fees Created"  ;
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
                            $res = [ 'result' => 'Fees not saved'  ];
                        }
                            
                    } 
                    else{
                        $res = [ 'result' => 'A fees structure with this class is already exist'  ];
                    }

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }


            public function delete()
            {
                $fid = $this->request->data('val') ;
                $fees_table = TableRegistry::get('fees');
                $activ_table = TableRegistry::get('activity');
                
                $feesid = $fees_table->find()->select(['id'])->where(['id'=> $fid ])->first();
                if($feesid)
                {   
                    
                    $del = $fees_table->query()->delete()->where([ 'id' => $fid ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Fees Deleted"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->value = md5($fid)    ;
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
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
class ParentsHolidayController  extends AppController
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
                $holiday_table = TableRegistry::get('holimas');
                $school_id = $this->request->session()->read('school_id');

                $retrieve_holiday = $holiday_table->find()->select(['id', 'sess_name', 'date','descs','holi_type'])->where(['school_id' => $school_id ])->toArray() ;
                
                 $this->set("holiday_details", $retrieve_holiday); 
                $this->viewBuilder()->setLayout('user');
            }

            public function add()
            {   
                $this->viewBuilder()->setLayout('user');
            }

            public function addholi(){
                if ($this->request->is('ajax') && $this->request->is('post') ){

                    $holiday_table = TableRegistry::get('holimas');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');

                    $retrieve_holiday = $holiday_table->find()->select(['id' ])->where(['holi_type' => $this->request->data('holi_type') , 'school_id'=> $compid])->count();
                    
                    if($retrieve_holiday == 0 )
                    {   
                        $holiday = $holiday_table->newEntity();

                        $holiday->sess_name =  $this->request->data('sess_name')  ;
                        $holiday->holi_type =  $this->request->data('holi_type')  ;
                        $holiday->date = date('Y-m-d',strtotime($this->request->data('date'))) ;
                        $holiday->descs = $this->request->data('descs') ;
                        $holiday->school_id = $compid ;

                        if($saved = $holiday_table->save($holiday) ){
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Holiday Created"  ;
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
                            $res = [ 'result' => 'holiday not saved'  ];
                        }
                    } 
                    else{
                        $res = [ 'result' => 'exist'  ];
                    }

                }
                else{
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }

            public function edit($id)
            {   
                $holiday_table = TableRegistry::get('holimas');
                
                $retrieve_holiday = $holiday_table->find()->select(['id' ,'sess_name' , 'holi_type' , 'date' ,'descs' ])->where([ 'md5(id)' => $id  ])->toArray() ;

                $this->set("holiday_details", $retrieve_holiday);  
                $this->viewBuilder()->setLayout('user');
            } 


            public function editholi()
            {   
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $holiday_table = TableRegistry::get('holimas');
                    $activ_table = TableRegistry::get('activity');
                    $compid =$this->request->session()->read('company_id');

                    $retrieve_holiday = $holiday_table->find()->select(['id'  ])->where(['holi_type' => $this->request->data('holi_type') , 'id IS NOT' => $this->request->data('id') , 'school_id'=> $compid ])->count() ;
                    
                    if($retrieve_holiday == 0 )
                    {   
                        $id =  $this->request->data('id') ;
                        $sess_name =  $this->request->data('sess_name') ;
                        $holi_type =  $this->request->data('holi_type')  ;
                        $date = date('Y-m-d',strtotime($this->request->data('date'))) ;
                        $descs = $this->request->data('descs') ;
                        
                        if( $holiday_table->query()->update()->set([ 'sess_name' => $sess_name , 'holi_type' => $holi_type, 'date' => $date , 'descs'=>$descs ])->where([ 'id' => $id  ])->execute())
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Holiday updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($id); 
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
                        $res = [ 'result' => 'exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            } 


            public function view($id)
            {   
                $holiday_table = TableRegistry::get('holimas');
                
                $retrieve_holiday = $holiday_table->find()->select(['id' ,'sess_name' , 'holi_type' , 'date' ,'descs' ])->where([ 'md5(id)' => $id  ])->toArray() ;

                $this->set("holiday_details", $retrieve_holiday);  
                $this->viewBuilder()->setLayout('user');
            }
			
			public function delete()
            {
				
                $id = $this->request->data('val') ;
                $holiday_table = TableRegistry::get('holimas');
                $activ_table = TableRegistry::get('activity');
                
                $holiid = $holiday_table->find()->select(['id'])->where(['id'=> $id ])->first();
                if($holiid)
                {   
                    
					$del = $holiday_table->query()->delete()->where([ 'id' => $id ])->execute(); 
                    if($del)
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Holiday Deleted"  ;
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

  


<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org) *
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
 * This controller will render views from Template/Pages/ *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class SessionController  extends AppController
{

    /**
     * Displays a view     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

    public function index()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('usersa');
    }

    public function addsession()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $session_table = TableRegistry::get('session');
            $activ_table = TableRegistry::get('activity');
        
            $retrieve_session = $session_table->find()->select(['id'])->where(['startmonth' => $this->request->data('startmonth') ,'startyear' => $this->request->data('startyear'),'endmonth' => $this->request->data('endmonth'), 'endyear' => $this->request->data('endyear') ])->count() ;
            
            if($retrieve_session == 0 )
            {
                $session = $session_table->newEntity();
                $session->startmonth =  $this->request->data('startmonth');
                $session->startyear = $this->request->data('startyear');
                $session->endmonth = $this->request->data('endmonth');
                $session->endyear =  $this->request->data('endyear');
                $session->added_date = strtotime('now');
                
                if($lang != "")
                {
                    $lang = $lang;
                }
                else
                {
                    $lang = 2;
                }
                
                //echo $lang;
                $language_table = TableRegistry::get('language_translation');
                if($lang == 1)
                {
                    $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                    
                    foreach($retrieve_langlabel as $langlabel)
                    {
                        $langlabel->title = $langlabel['english_label'];
                    }
                }
                else
                {
                    $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                    foreach($retrieve_langlabel as $langlabel)
                    {
                        $langlabel->title = $langlabel['french_label'];
                    }
                }
                
                foreach($retrieve_langlabel as $langlbl) 
                { 
                    if($langlbl['id'] == '1611') { $strtyr = $langlbl['title'] ; } 
                } 
                
                $st_yr = $this->request->data('startyear');
                $ed_yr = $this->request->data('endyear');
                if($st_yr <= $ed_yr)
                {
                    if($saved = $session_table->save($session) ){
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Session Created"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                        $activity->value = md5($saved->id)   ;
                        $activity->origin = $this->Cookie->read('id')   ;
                        $activity->created = strtotime('now');
                        if($saved = $activ_table->save($activity) ){
                            $res = [ 'result' => 'success'  ];

                        }
                        else{
                            $res = [ 'result' => 'activity'  ];
                        }
                    }
                    else{
                        $res = [ 'result' => 'error'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => $strtyr  ];
                }
            } 
            else
            {
                $res = [ 'result' => 'exist'  ];
            }    
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
            
		

    public function getdata()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $session_table = TableRegistry::get('session');
            $retrieve_session = $session_table->find()->select(['id' , 'startmonth' , 'endmonth' , 'startyear' , 'endyear' , 'added_date' ])->order(['startyear' => 'ASC'])->toArray();
            $data = "";
            $datavalue = array();

            foreach ($retrieve_session as $value) 
            {
                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsession" data-toggle="modal"  data-target="#editsession" title="Edit"><i class="fa fa-edit"></i></button>';
                $delete = '<button type="button" data-url="session/delete" data-id='. $value['id'].' data-str="Session" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
                
                $data .=  '<tr>
                            <td class="width45">
                            <label class="fancy-checkbox">
                                    <input class="checkbox-tick" type="checkbox" name="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <span class="mb-0 font-weight-bold">'.ucfirst($value['startmonth']).' '.$value['startyear'].'</span>
                            </td>
			                <td>
                                <span class="mb-0 font-weight-bold">'.ucfirst($value['endmonth']).' '.$value['endyear'].'</span>
                            </td>	
                           <td>
                                <span>'.date('d M, Y',$value['added_date']).'</span>
                            </td>
                            <td>
                                '.$edit.$delete.'
                            </td>
                        </tr>';
            }
            $datavalue['html']= $data;
            return $this->json($datavalue);
        }
    }

	
    public function update()
    {   
        if($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $id = $this->request->data['id'];
            $session_table = TableRegistry::get('session');
            $schoolprivilages_table = TableRegistry::get('schoolprivilages');
            $update_session = $session_table->find()->select(['startmonth' , 'startyear' , 'endmonth', 'endyear'])->where(['id' => $id])->toArray(); 
            $data = ['startmonth' => $update_session[0]['startmonth'] , 'startyear' => $update_session[0]['startyear'] , 'endmonth' => $update_session[0]['endmonth'] , 'endyear' => $update_session[0]['endyear'] ];
            return $this->json($data);
        }  
    }	

    public function editsession()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $session_table = TableRegistry::get('session');
            $activ_table = TableRegistry::get('activity');
            
            $retrieve_session = $session_table->find()->select(['id'])->where(['startmonth' => $this->request->data('startmonth') ,'startyear' => $this->request->data('startyear'),'endmonth' => $this->request->data('endmonth'), 'endyear' => $this->request->data('endyear'), 'id IS NOT' => $this->request->data('id')  ])->count() ;
                
            if($retrieve_session == 0 )
            {
                $id = $this->request->data('id');
                $startmonth =  $this->request->data('startmonth');
                $startyear = $this->request->data('startyear');
                $endmonth = $this->request->data('endmonth');
                $endyear =  $this->request->data('endyear');	
                    
                if( $session_table->query()->update()->set([ 'startmonth' => $startmonth, 'startyear' => $startyear, 'endmonth' => $endmonth, 'endyear' => $endyear])->where([ 'id' => $id  ])->execute())
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Session Updated"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

                    $activity->value = md5($id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) )
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
                    $res = [ 'result' => 'error'  ];
                }
            } 
            else
            {
                $res = [ 'result' => 'exist'  ];
            }
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }

	public function delete()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $rid = $this->request->data('val') ;
        $session_table = TableRegistry::get('session');
        $activ_table = TableRegistry::get('activity');
        
        $sessionid = $session_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($sessionid)
        {                       
            $del = $session_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
            if($del)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Session Deleted"  ;
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

}

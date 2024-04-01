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

class CategoriesController  extends AppController
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
        $this->viewBuilder()->setLayout('usersa');
    }


    public function addcategory()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $categories_table = TableRegistry::get('categories');
            $activ_table = TableRegistry::get('activity');
        
            $retrieve_categories = $categories_table->find()->select(['id'])->where(['name' => $this->request->data('name')  ])->count() ;
            
            if($retrieve_categories == 0 ){
    
                $categories = $categories_table->newEntity();
                $categories->name =  $this->request->data('name');
                $categories->status = 1;
                $categories->created_date = strtotime('now');
                if($saved = $categories_table->save($categories) )
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Categories Created"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                    $activity->value = md5($saved->id)   ;
                    $activity->origin = $this->Cookie->read('id')   ;
                    $activity->created = strtotime('now');
                    if($saved = $activ_table->save($activity) ){
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
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
            
		

    public function getdata()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $categories_table = TableRegistry::get('categories');
            $retrieve_categories = $categories_table->find()->order(['name' => 'ASC'])->toArray();
            $data = "";
            $datavalue = array();
    
            foreach ($retrieve_categories as $value) {
                
                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editcategory" data-toggle="modal"  data-target="#editcategory" title="Edit"><i class="fa fa-edit"></i></button>';
                $delete = '<button type="button" data-url="categories/delete" data-id='. $value['id'].' data-str="Category" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
            
                $data .=  '<tr>
                        <td>
                            <span class="mb-0 font-weight-bold">'.ucfirst($value['name']).'</span>
                        </td>
                       <td>
                            <span>'.date('d M, Y',$value['created_date']).'</span>
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
            $categories_table = TableRegistry::get('categories');
            $categories = $categories_table->find()->where(['id' => $id])->toArray(); 
            return $this->json($categories);
        }  
    }	

    public function editcategory()
    {
        if ($this->request->is('ajax') && $this->request->is('post')){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $categories_table = TableRegistry::get('categories');
            $activ_table = TableRegistry::get('activity');
            
            $retrieve_category = $categories_table->find()->select(['id'])->where(['name' => $this->request->data('name') , 'id IS NOT' => $this->request->data('id')  ])->count() ;
                
                if($retrieve_category == 0 )
                {
                    $id = $this->request->data('id');
	                $name =  $this->request->data('name');
                    	
                    
                    if( $categories_table->query()->update()->set([ 'name' => $name])->where([ 'id' => $id  ])->execute())
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Category Updated"  ;
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
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }



    public function delete()
    {
        $rid = $this->request->data('val') ;
        $categories_table = TableRegistry::get('categories');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $catid = $categories_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($catid)
        {                       
            $del = $categories_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
            if($del)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Category Deleted"  ;
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

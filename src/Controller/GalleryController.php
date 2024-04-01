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
class GalleryController  extends AppController
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
                $gallery_table = TableRegistry::get('gallery');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid))
                {
                    $retrieve_gallery = $gallery_table->find()->select(['id' ,'title' ])->where(['school_id' => $compid])->toArray() ;
                    $this->set("gallery_details", $retrieve_gallery); 
                    $this->viewBuilder()->setLayout('user');
                }
        		else
        		{
        		    return $this->redirect('/login/') ;    
        		}
            }

            public function add()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('user');
            }

            public function addgallery()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $gallery_table = TableRegistry::get('gallery');
                    $galleryimg_table = TableRegistry::get('gallery_images');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    if(!empty($compid))
                    {
                    
                        $gallery = $gallery_table->newEntity();
                        $gallery->title = $this->request->data('title');
                        $gallery->event_date = date("Y-m-d", strtotime($this->request->data('eventDate')));
                        $gallery->description = $this->request->data('desc');
    					$gallery->status = 1;
                        $gallery->school_id = $compid;
                        //$gallery->images = implode(",", $filenames);
                        $gallery->created_date = time();
                                              
                        if($saved = $gallery_table->save($gallery) )
                        {   
                            $galleryid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Gallery Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($galleryid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
                            
                            if($save)
                            {   
                                $res = [ 'result' => 'success' , 'galleryId' =>  $galleryid ];
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
            		    return $this->redirect('/login/') ;    
            		}
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }
            
            public function addgalleryimages()
            {  
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $gallery_table = TableRegistry::get('gallery');
                    $galleryimg_table = TableRegistry::get('gallery_images');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $images = $this->request->data['images'];
                    $filenames = [];
                    foreach($images as $img)
                    {
                        if(!empty($img['name'])) 
                        { 
                            if($img['type'] == "image/jpeg" || $img['type'] == "image/jpg" || $img['type'] == "image/png" )
                            {
                                $filess =  $img['name'];
                                $filename = $img['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($img['tmp_name'], $uploadfile))
                                {
                                    $filenames[] = $img['name'];
                                }
                            }
                        }
                        
                    }
                    
                    $gid = $this->request->data('galleryId');
                    $imgs = implode(",", $filenames);
                    
                    if(!empty($imgs)) 
                    {
                        $update = $update = $gallery_table->query()->update()->set(['images' => $imgs ])->where([ 'id' => $gid  ])->execute();                     
                        if($update )
                        {   
                            $galleryid = $gid;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Gallery Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($galleryid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
                            
                            if($save)
                            {   
                                $res = [ 'result' => 'success' ];
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
                        $res = [ 'result' => 'Images are mandatory.'  ];
                    }
                     
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }

                return $this->json($res);
            }

          
            public function editgallery()
            {  
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $gallery_table = TableRegistry::get('gallery');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($compid))
                    {
                        $gid = $this->request->data('eid');
                        $title = $this->request->data('etitle');
                        $desc = $this->request->data('edesc');
    					//$status = 0; 
                        
                        $update = $gallery_table->query()->update()->set(['title' => $title , 'description' => $desc ])->where([ 'id' => $gid  ])->execute();
                        
                        if($update)
                        {   
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Gallery update"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($gid); 
                            $activity->created = strtotime('now');
                            $save = $activ_table->save($activity) ;
    
                            if($save)
                            {   
                                $res = [ 'result' => 'success'  , 'galleryId' => $gid  ];
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
            		    return $this->redirect('/login/') ;    
            		}
                           
                    
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }

                return $this->json($res);
            }
            
            public function editgalleryimages()
            {  
               
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $gallery_table = TableRegistry::get('gallery');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                      
                    $gid = $this->request->data('egalleryId');
                    $images = $this->request->data['photos'];
                    $filenames = [];
                    
                   
                    if(!empty($images))
                    {
                        foreach($images as $img)
                        {
                            if(!empty($img['name'])) 
                            { 
                                if($img['type'] == "image/jpeg" || $img['type'] == "image/jpg" || $img['type'] == "image/png" )
                                {
                                    $filess =  $img['name'];
                                    $filename = $img['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($img['tmp_name'], $uploadfile))
                                    {
                                        $filenames[] = $img['name'];
                                    }
                                }
                            }
                            
                        }
                    }
                    $imgs = implode(",", $filenames);
                    if(!empty( $this->request->data['old']))
                    {
                        $old = $this->request->data['old'];
                        $oldimgs = implode(",", $old);
                        $cimgs = $oldimgs.",".$imgs;
                    }
                    else
                    {
                        $oldimgs = '';
                        $cimgs = $imgs;
                    }
                    
                    
                    
                    $update = $gallery_table->query()->update()->set(['images' => $cimgs ])->where([ 'id' => $gid  ])->execute();
                    
					
                    if($update)
                    {   
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Gallery update"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($gid); 
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

        public function delete()
        {
            $sid = $this->request->data('val') ;
            $gallery_table = TableRegistry::get('gallery');
            $galleryimg_table = TableRegistry::get('gallery_images');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $subid = $gallery_table->find()->select(['id'])->where(['id'=> $sid ])->first();
            if($subid)
            {   
                
                if($gallery_table->delete($gallery_table->get($sid)))
                {
                    //$del = $galleryimg_table->query()->delete()->where([ 'gallery_id' => $sid ])->execute();
                    
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Gallery Deleted"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = $sid    ;
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
		
	    public function update()
        {   
            if($this->request->is('post'))
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data['id'];
                $gallery_table = TableRegistry::get('gallery');
                $galleryimg_table = TableRegistry::get('gallery_images');
                $update_gallery = $gallery_table->find()->where(['id' => $id])->toArray();
                $update_galleryimg = $galleryimg_table->find()->where(['gallery_id' => $id])->toArray();
                $data = ['gallery_data' => $update_gallery, 'gallery_images' => $update_galleryimg];
                return $this->json($data);
            }  
        }
        
        public function view()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $gallery_table = TableRegistry::get('gallery');
            $compid = $this->request->session()->read('company_id');
            if(!empty($compid)) {
                $retrieve_gallery = $gallery_table->find()->where(['school_id' => $compid])->order(['id' => 'DESC'])->toArray();
               
                $this->set("gallery_details", $retrieve_gallery); 
                $this->viewBuilder()->setLayout('user');
            }
    		else
    		{
    		    return $this->redirect('/login/') ;    
    		}
        }
        
        
        public function status()
        {   

            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $gallery_table = TableRegistry::get('gallery');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $sid = $gallery_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($sid)
            {   
                $stats = $gallery_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Gallery status changed"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->value = md5($uid)    ;
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
                    $res = [ 'result' => 'Status Not changed'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }

            
               
	   public function getdata(){
			if ($this->request->is('ajax') && $this->request->is('post'))
			{		
				$gallery_table = TableRegistry::get('gallery');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if(!empty($compid)) {
                $retrieve_gallery = $gallery_table->find()->where(['school_id' => $compid])->order(['id' => 'DESC'])->toArray() ;
                
                $data = "";
                $datavalue = array();
                
                $sclsub_table = TableRegistry::get('school_subadmin');
				if($this->Cookie->read('logtype') == md5('School Subadmin'))
				{
					$sclsub_id = $this->Cookie->read('subid');
					$sclsub_table = $sclsub_table->find()->select(['sub_privileges' ])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
					$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
				}
				
                foreach ($retrieve_gallery as $value) 
				{
					$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editgallery" data-toggle="modal"  data-target="#editgal" title="Edit"><i class="fa fa-edit"></i></button>';
					$delete = '<button type="button" data-url="gallery/delete" data-id='.$value['id'].' data-str="Gallery" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
                    //$view = '<a href="gallery/view/'.$value['id'].'" class="btn btn-sm btn-outline-primary" title="View"><i class="fa fa-eye"></i></a>';
					
					if($this->Cookie->read('logtype') == md5('School Subadmin'))
					{
						$e = in_array(49, $scl_privilage) ? $edit : "";
						$d = in_array(51, $scl_privilage) ? $delete : "";
					}
					else
					{
						$e = $edit;
						$d = $delete;
					}
					//$v = $view;
					
					/*if(!empty($this->Cookie->read('sid')))
                    {*/
                        if( $value['status'] == 0)
                        {
                            $sts = '<a href="javascript:void()" data-url="gallery/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Gallery Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                        }
                        else 
                        { 
                            $sts = '<a href="javascript:void()" data-url="gallery/status" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Gallery Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                        }
                    /*}
                    else
                    {
                        if( $value['status'] == 0)
                        {
                            $sts = '<label class="switch"><input type="checkbox" disabled ><span class="slider round"></span></label>';
                        
                        }
                        else 
                        { 
                            $sts = '<label class="switch"><input type="checkbox" checked disabled ><span class="slider round"></span></label>';
                        }
                    }*/
                    
					$data .=    '<tr>
                                    <td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="mb-0 font-weight-bold">'.$value['title'].'</span>
                                    </td>
                                    <td>'.$sts.'</td>
                                    <td>
        							'.$e.$d.'
                                    </td>
                                   
                                </tr>';
                    
                }
                $datavalue['html']= $data;
                return $this->json($datavalue);
                }
        		else
        		{
        		    return $this->redirect('/login/') ;    
        		}
            }
        }
            
            
}

  




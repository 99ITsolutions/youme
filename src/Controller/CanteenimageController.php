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
class CanteenimageController  extends AppController
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
        $ci_table = TableRegistry::get('canteen_image');
        $retrieve_ci = $ci_table->find()->first() ;
        $this->set("ci_details", $retrieve_ci);
        $this->viewBuilder()->setLayout('usersa');
    }

    public function banerimgs()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $activ_table = TableRegistry::get('activity');
            $ci_table = TableRegistry::get('canteen_image');
            
            $image1 =  trim($this->request->data('pimg1'));
            $image2 =  trim($this->request->data('pimg2'));
            $image3 =  trim($this->request->data('pimg3'));
            if(!empty($this->request->data['image1']))
            {   
                if($this->request->data['image1']['type'] == "image/jpeg" || $this->request->data['image1']['type'] == "image/jpg" || $this->request->data['image1']['type'] == "image/png" )
                {
                    $image1 =  time().$this->request->data['image1']['name'];
                    $uploadpath = 'canteen_banners/';
                    $uploadfile = $uploadpath.$image1;
                    if(move_uploaded_file($this->request->data['image1']['tmp_name'], $uploadfile))
                    {
                        $image1 = $image1; 
                    }
                }  
            }
            if(!empty($this->request->data['image2']))
            {   
                if($this->request->data['image2']['type'] == "image/jpeg" || $this->request->data['image2']['type'] == "image/jpg" || $this->request->data['image2']['type'] == "image/png" )
                {
                    $image2 =  time().$this->request->data['image2']['name'];
                    $uploadpath = 'canteen_banners/';
                    $uploadfile = $uploadpath.$image2;
                    if(move_uploaded_file($this->request->data['image2']['tmp_name'], $uploadfile))
                    {
                        $image2 = $image2; 
                    }
                }  
            }
            if(!empty($this->request->data['image3']))
            {   
                if($this->request->data['image3']['type'] == "image/jpeg" || $this->request->data['image3']['type'] == "image/jpg" || $this->request->data['image3']['type'] == "image/png" )
                {
                    $image3 =  time().$this->request->data['image3']['name'];
                    $uploadpath = 'canteen_banners/';
                    $uploadfile = $uploadpath.$image3;
                    if(move_uploaded_file($this->request->data['image3']['tmp_name'], $uploadfile))
                    {
                        $image3 = $image3; 
                    }
                }  
            }
            
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
                            $uploadpath = 'canteen_banners/';
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
            if(!empty($imgs))
            {
                $imgs = ",".$imgs;
            }
            if(!empty( $this->request->data['old']))
            {
                $old = $this->request->data['old'];
                $oldimgs = implode(",", $old);
                $cimgs = $oldimgs.$imgs;
            }
            else
            {
                $oldimgs = '';
                $cimgs = $imgs;
            }
            
                //print_r($cimgs); die;
            if($update = $ci_table->query()->update()->set(['slider_banner_image' => $cimgs, 'meal_image1' => $image1, 'meal_image2' => $image2, 'meal_image3' => $image3  ])->where([ 'id' => 1  ])->execute())
            {   
                $activity = $activ_table->newEntity();
                $activity->action =  "Canteen Banner images updated"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5(1)   ;
                $activity->origin = $this->Cookie->read('id')   ;
                $activity->created = strtotime('now');
                if($saved = $activ_table->save($activity) )
                {
                    $res = [ 'result' => 'success'  ];
                }
                else{
                    $res = [ 'result' => 'activity not saved'  ];
                }
            }
            else{
                $res = [ 'result' => 'Images not updated. Please try again'  ];
            }
             
        }
        else{
            $res = [ 'result' => 'invalid operation'  ];

        }


        return $this->json($res);

    }
}

  


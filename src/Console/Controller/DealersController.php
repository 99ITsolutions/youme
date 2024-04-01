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
class DealersController  extends AppController
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
        public function index(){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $dealers_table = TableRegistry::get('dealers');
            $retrieve_dealer = $dealers_table->find()->toArray();
            $this->set("dealer_details", $retrieve_dealer);   
            //$this->set("school_details", $retrieve_scl);   
            $this->viewBuilder()->setLayout('usersa');

        }

        public function add()
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $country_table = TableRegistry::get('countries');
            $retrieve_country = $country_table->find()->select(['id' ,'name'])->toArray() ;
            $this->set("country_details", $retrieve_country);
            $categories_table = TableRegistry::get('categories');
            $scl_table = TableRegistry::get('company');
            $retrieve_categories = $categories_table->find()->where([ 'status' => '1'])->toArray() ;
            $retrieve_scl = $scl_table->find()->select([ 'id', 'comp_name'])->where(['status' => 1])->toArray();
            $this->set("school_details", $retrieve_scl);   
            $this->set("categories_details", $retrieve_categories); 
            $this->viewBuilder()->setLayout('usersa');
        }
        
        public function adddealer()
        {
            if ($this->request->is('ajax') && $this->request->is('post') )
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $dealer_table = TableRegistry::get('dealers');
                $activ_table = TableRegistry::get('activity');
                $language_table = TableRegistry::get('language_translation');
                $lang = $this->Cookie->read('language');	
                if($lang != "") { $lang = $lang; } else { $lang = 2; }
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
                    if($langlbl['id'] == '1630') { $dealemil = $langlbl['title'] ; } 
                } 
                
                
                $retrieve_dealers = $dealer_table->find()->select(['id'  ])->where(['email' => $this->request->data('email') , 'name' => $this->request->data('company_name')  , 'status' => '1' ])->count() ;
                if($retrieve_dealers == 0 )
                {
                    $categories = implode(",", $this->request->data['categories']);
                   
                    $dealer = $dealer_table->newEntity();

                    $dealer->email =  $this->request->data('email')  ;
                    $dealer->name = $this->request->data('company_name') ;
                    $dealer->phone_no =  $this->request->data('phone')  ;
                    $dealer->fname =  $this->request->data('fname') ;
                    $dealer->categories =  $categories  ;
                    $dealer->lname =  $this->request->data('lname') ;
                    $dealer->created_date = strtotime('now');
                    $dealer->status = 1;
                    
                    $dealer->city = $this->request->data('dealercity') ;
                    $dealer->state =  $this->request->data('dealerstate')  ;
                    $dealer->country =  $this->request->data('dealercountry') ;
                    $dealer->address =  $this->request->data('dealeraddress') ;
                
                    if($saved = $dealer_table->save($dealer) )
                    {   
                        $usrid = $saved->id;
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Dealer Created"  ;
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
                    else
                    {
                        $res = [ 'result' => 'user not saved'  ];
                    }
                } 
                else
                {
                    $res = [ 'result' => $dealemil ];
                }
            }
            else{
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);
        } 

        public function edit($uid){

            $category_table = TableRegistry::get('categories');
            $dealers_table = TableRegistry::get('dealers');
            $state_table = TableRegistry::get('states');
            $countries_table = TableRegistry::get('countries');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_dealer = $dealers_table->find()->where(['md5(id)' => $uid   ])->toArray();
            $retrieve_category = $category_table->find()->where([ 'status' => '1'  ])->toArray();
            
            $retrieve_state = $state_table->find()->select(['id' ,'name'])->where([ 'country_id' => $retrieve_dealer[0]['country']  ])->toArray() ;
            $retrieve_country = $countries_table->find()->select(['id' ,'name'])->toArray() ;

            $this->set("state_details", $retrieve_state);
            $this->set("country_details", $retrieve_country);
            $this->set("dealer_details", $retrieve_dealer);
            $this->set("category_details", $retrieve_category); 
            $this->viewBuilder()->setLayout('usersa');
        }            

        public function editdealer()
        {
            if ($this->request->is('ajax') && $this->request->is('post') ){
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $dealers_table = TableRegistry::get('dealers');
                $activ_table = TableRegistry::get('activity');
                $lang = $this->Cookie->read('language');	
                if($lang != "") { $lang = $lang; } else { $lang = 2; }
                
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
                    if($langlbl['id'] == '1630') { $dealemil = $langlbl['title'] ; } 
                } 
                
                $retrieve_dealers = $dealers_table->find()->select(['id'])->where(['email' => $this->request->data('email'), 'name' => $this->request->data('company_name'), 'id <> ' => $this->request->data('id') ])->count() ;
                
                if($retrieve_dealers == 0 )
                {
                    $uid = $this->request->data('id');
                    $fname =  $this->request->data('fname')  ;
                    $lname =  $this->request->data('lname')  ;
                    $email =  $this->request->data('email')  ;
                    $password = $this->request->data('password') ;
                    $phone =  $this->request->data('phone')  ;
                    $name =  $this->request->data('name')  ;

                    $city = $this->request->data('dealercity') ;
                    $state =  $this->request->data('dealerstate')  ;
                    $country =  $this->request->data('dealercountry') ;
                    $address =  $this->request->data('dealeraddress') ;
                    
                    $privilages = implode(",", $this->request->data['categories']);
  
                    if( $dealers_table->query()->update()->set(['city' => $city, 'state' => $state, 'country' => $country, 'address' => $address, 'fname' => $fname , 'lname' => $lname , 'email' => $email,  'phone_no' => $phone, 'name' => $name, 'categories' => $privilages])->where([ 'id' => $uid  ])->execute())
                    {   
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Dealer update"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($uid); 
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
                    $res = [ 'result' => $dealemil  ];
                }
            }
            else{
                $res = [ 'result' => 'invalid operation'  ];
            }
            return $this->json($res);

        }  
           
        public function delete()
        {   
            $uid = $this->request->data('val') ;
            $dealers_table = TableRegistry::get('dealers');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $userid = $dealers_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			
            if($userid)
            {   
				$del = $dealers_table->query()->delete()->where([ 'id' => $uid ])->execute(); 
                if($del)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Dealer Deleted"  ;
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
                    $res = [ 'result' => 'not delete'  ];
                }    
            }
            else
            {
                $res = [ 'result' => 'error'  ];
            }

            return $this->json($res);
        }
        
        public function status()
        {   
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $uid = $this->request->data('val') ;
            $sts = $this->request->data('sts') ;
            $dealers_table = TableRegistry::get('dealers');
            $activ_table = TableRegistry::get('activity');

            $userid = $dealers_table->find()->select(['id'])->where(['id'=> $uid ])->first();
			$status = $sts == 1 ? 0 : 1;
            if($userid)
            {   
                $stats = $dealers_table->query()->update()->set([ 'status' => $status])->where([ 'id' => $uid  ])->execute();
				
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Dealer status changed"  ;
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

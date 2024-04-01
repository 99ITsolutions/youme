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
use Dompdf\Dompdf;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class KnowledgeCenterController   extends AppController
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
                $country_table = TableRegistry::get('countries');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $retrieve_countries = $country_table->find()->toArray() ;
                
                $this->set("countries_details", $retrieve_countries); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function getuniv()
            { 
                if ($this->request->is('ajax') && $this->request->is('post') )
                { 
                    $univ_table = TableRegistry::get('univrsities');
    				$country = $this->request->data('country');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_univs = $univ_table->find()->where(['country_id' => $country])->toArray() ;
                    if(!empty($retrieve_univs))
                    {
                       
                        $res = [ 'result' => 'success',  'country_id' => $country ];
                    }
                    else
                    {
                        $res = [ 'result' => 'failed'];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];
                }
                return $this->json($res);
            }
            
            public function studyabroad()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->toArray() ;
                $this->set("countries_details", $retrieve_countries); 
                $univ_table = TableRegistry::get('univrsities');
                $retrieve_univs = $univ_table->find()->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_countries = $country_table->find()->where(['id' => $univss['country_id'] ])->first() ;
                    $country_name = $retrieve_countries['name'];
                    $univss->country_name = $country_name;
                }
                $this->set("univ_details", $retrieve_univs); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function localuniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $state_table = TableRegistry::get('states');
                $retrieve_states = $state_table->find()->where(['country_id' => 50])->toArray() ;
                $this->set("states_details", $retrieve_states); 
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univs = $univ_table->find()->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_state = $state_table->find()->where(['id' => $univss['state_id'] ])->first() ;
                    $state_name = $retrieve_state['name'];
                    $univss->state_name = $state_name;
                }
                $this->set("univ_details", $retrieve_univs); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function countryfilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter = $this->request->data('country');
                $univ_table = TableRegistry::get('univrsities');
                $country_table = TableRegistry::get('countries');
                $retrieve_univs = $univ_table->find()->where(['country_id' => $filter])->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_countries = $country_table->find()->where(['id' => $univss['country_id'] ])->first() ;
                    $country_name = $retrieve_countries['name'];
                    $univss->country_name = $country_name;
                }
                
                if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                    if($langlbl['id'] == '646') { $countrytitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '629') { $emailtitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1269') { $contcttitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1270') { $linktitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1271') { $courstitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1272') { $desctitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1273') { $seemrtitle = $langlbl['title'] ; } 
                    
                } 
                
                $res = '';
                foreach($retrieve_univs as $uni) 
                {
                    $email = "";
                    $contact = "";
                    $website= "";
                    $courses = "";
                    
                    if(!empty( $uni['country_name'] )) 
                    { 
                        $country = '<p><b>'.$countrytitle.': </b><span>'.  $uni['country_name'] .' </span></p>';
                    } 
                    if(!empty( $uni['email'] )) 
                    { 
                        $email = '<p><b>'.$emailtitle.': </b><span>'.  $uni['email'] .' </span></p>';
                    }
                    
                    if(!empty( $uni['contact_number'] )) 
                    {
                        $contact = '<p><b>'.$contcttitle.': </b><span>'.  $uni['contact_number'] .' </span></p>';
                    } 
                    if(!empty( $uni['website_link'] )) 
                    {
                        $website = '<p><b> '.$linktitle.': </b><span>'.  $uni['website_link'] .' </span></p>';
                    } 
                    if(!empty( $uni['academics'] )) 
                    {
                        $courses = '<p><b>'.$courstitle.': </b><span>'.  $uni['academics'] .' </span></p>';
                    } 
                    $res .= '<tr>
                            <td>
                                <img src="'. $this->base_url.'univ_logos/'. $uni['logo'] .'" class="avatar" alt=""style="width: 140px; height: 100px;">
                            </td>
                            <td>
                                <p>
                                    <span style="width:90%; float:left; margin-bottom:8px;"><b>'.  $uni['univ_name'] .'</b></span>
                                    <span style="width:10%; float:right;">
                                        <a href="'.  $this->base_url .' knowledgeCenter/edituniv/'.  $uni['id'] .' " class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>
                                        <button type="button" data-id="'. $uni['id'].' " data-url="deleteuniv" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="University" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                    </span>
                                </p>
                                '.$country.$email.$contact.$website.$courses.'
                                <p><b>'.$desctitle.': </b><span id="unidesc_'.  $uni['id'] .' ">'.  substr($uni['about_univ'], 0, 250).' ...</span></p>
                                <p class="align-right" style="margin-right: 35px;"><span><a href="javascript:void(0);" class="see_more" id="see_more'.  $uni['id'] .' " data-id="'.  $uni['id'] .' ">'.$seemrtitle.'</a></span</p>
                            </td>
                            
                        </tr>';
                } 
                return $this->json($res);
            }
            
            public function statefilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter = $this->request->data('state');
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univs = $univ_table->find()->where(['state_id' => $filter])->group(['city'])->toArray() ;
                
                $data = '';
                $data .= '<option value="">Choose City</option>';
                foreach($retrieve_univs as $uni)
                {
                    $data .= '<option value="'.$uni['city'].'">'.$uni['city'].'</option>';
                }
                return $this->json($data);
            }
            
            public function cityfilter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter_state = $this->request->data('state');
                $filter_city = $this->request->data('city');
                $univ_table = TableRegistry::get('localuniversity');
                $state_table = TableRegistry::get('states');
                $retrieve_univs = $univ_table->find()->where(['state_id' => $filter_state, 'city' => $filter_city ])->order(['id' => 'desc'])->toArray() ;
                foreach($retrieve_univs as $univss)
                {
                    $retrieve_state = $state_table->find()->where(['id' => $univss['state_id'] ])->first() ;
                    $state_name = $retrieve_state['name'];
                    $univss->state_name = $state_name;
                }
                
                if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                    if($langlbl['id'] == '648') { $statetitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '629') { $emailtitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1269') { $contcttitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1270') { $linktitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1271') { $courstitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1272') { $desctitle = $langlbl['title'] ; } 
                    if($langlbl['id'] == '1273') { $seemrtitle = $langlbl['title'] ; } 
                } 
                
                $res = '';
                foreach($retrieve_univs as $uni) 
                {
                    $email = "";
                    $contact = "";
                    $website= "";
                    $courses = "";
                    
                    if(!empty( $uni['email'] )) 
                    { 
                        $email = '<p><b>'.$emailtitle.': </b><span>'.  $uni['email'] .' </span></p>';
                    } 
                     if(!empty( $uni['state_name'] )) 
                    { 
                        $state = '<p><b>'.$statetitle.': </b><span>'.  $uni['state_name'] .' </span></p>';
                    } 
                    
                    if(!empty( $uni['contact_number'] )) 
                    {
                        $contact = '<p><b>'.$contcttitle.': </b><span>'.  $uni['contact_number'] .' </span></p>';
                    } 
                    if(!empty( $uni['website_link'] )) 
                    {
                        $website = '<p><b>'.$linktitle.': </b><span>'.  $uni['website_link'] .' </span></p>';
                    } 
                    if(!empty( $uni['academics'] )) 
                    {
                        $courses = '<p><b>'.$courstitle.': </b><span>'.  $uni['academics'] .' </span></p>';
                    } 
                    $res .= '<tr>
                            <td>
                                <img src="'. $this->base_url.'univ_logos/'. $uni['logo'] .'" class="avatar" alt=""style="width: 140px; height: 100px;">
                            </td>
                            <td>
                                <p>
                                    <span style="width:90%; float:left; margin-bottom:8px;"><b>'.  $uni['univ_name'] .', '.  $uni['city'] .'</b></span>
                                    <span style="width:10%; float:right;">
                                        <a href="'.  $this->base_url .' knowledgeCenter/editlocaluniv/'.  $uni['id'] .' " class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>
                                        <button type="button" data-id="'. $uni['id'].' " data-url="deletelocaluniv" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="University" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                    </span>
                                </p>
                                '.$state.$email.$contact.$website.$courses.'
                                <p><b>'.$desctitle.': </b><span id="unidesc_'.  $uni['id'] .' ">'.  substr($uni['about_univ'], 0, 250).' ...</span></p>
                                <p class="align-right" style="margin-right: 35px;"><span><a href="javascript:void(0);" class="see_more" id="see_more'.  $uni['id'] .' " data-id="'.  $uni['id'] .' ">'.$seemrtitle.'</a></span</p>
                            </td>
                            
                        </tr>';
                } 
                return $this->json($res);
            }
                
            
            public function listuniversity($id)
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $univ_table = TableRegistry::get('univrsities');
                $retrieve_univs = $univ_table->find()->where(['country_id' => $id])->toArray() ;
                $this->set("univ_details", $retrieve_univs); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function community()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('knowledge_center');
                $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function viewcommunity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('knowledge_center');
                $filter = $this->request->data('filter');
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "audio")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'audio'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'audio')
                    {
                        $icon = '<i class="fa fa-headphones"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editknowledge" class="editknowcenter" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="delete" class=" js-sweetalert " title="Delete" data-str="Knowledge Community" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/view/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
            
            public function add()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('usersa');
            }

            public function adduniv()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $retrieve_countries = $country_table->find()->toArray() ;
                $this->set("countries_details", $retrieve_countries); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function addlocaluniv()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $state_table = TableRegistry::get('states');
                $retrieve_states = $state_table->find()->where(['country_id' => 50])->toArray() ;
                $this->set("states_details", $retrieve_states); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function edituniv($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $country_table = TableRegistry::get('countries');
                $univ_table = TableRegistry::get('univrsities');
                $retrieve_countries = $country_table->find()->toArray() ;
                $retrieve_univ = $univ_table->find()->where(['id' => $id])->toArray() ;
                $this->set("countries_details", $retrieve_countries); 
                $this->set("univ_details", $retrieve_univ); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function editlocaluniv($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $state_table = TableRegistry::get('states');
                $retrieve_states = $state_table->find()->where(['country_id' => 50])->toArray() ;
                $this->set("states_details", $retrieve_states); 
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univ = $univ_table->find()->where(['id' => $id])->toArray() ;
                $this->set("univ_details", $retrieve_univ); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function edituni()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $see = $this->request->data('see');
                
                $univ_table = TableRegistry::get('univrsities');
                $retrieve_univ = $univ_table->find()->where(['id' => $id])->first() ;
                if($see == "more")
                {
                    $desc = $retrieve_univ['about_univ'];
                }
                else
                {
                    $desc = substr($retrieve_univ['about_univ'], 0, 250);
                }
                return $this->json($desc);
            }
            
            public function editlouni()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $see = $this->request->data('see');
                
                $univ_table = TableRegistry::get('localuniversity');
                $retrieve_univ = $univ_table->find()->where(['id' => $id])->first() ;
                if($see == "more")
                {
                    $desc = $retrieve_univ['about_univ'];
                }
                else
                {
                    $desc = substr($retrieve_univ['about_univ'], 0, 250);
                }
                return $this->json($desc);
            }
            
            public function adduniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                 
                    $univ_table = TableRegistry::get('univrsities');
                    $activ_table = TableRegistry::get('activity');
                    
                    if(!empty($this->request->data['logo']['name']))
                    {
                        if($this->request->data['logo']['type'] == "image/png" || $this->request->data['logo']['type'] == "image/jpeg"  || $this->request->data['logo']['type'] == "image/jpg" )
                        {
                            
                            $coverimg = $this->request->data['logo']['name'];
                            $uploadpath = 'univ_logos/';
                            $uploadfile = $uploadpath.$coverimg;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $coverimg; 
                            }
                        }   
                        else
                        {
                            $coverimg = "";
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    if(!empty($coverimg))
                    {
                        $univ = $univ_table->newEntity();
                        $univ->country_id = $this->request->data('country');
                        $univ->univ_name = $this->request->data('uni_name');
    		            $univ->about_univ = $this->request->data('desc');
                        $univ->logo = $coverimg;
                        $univ->academics = $this->request->data('courses');
    					$univ->website_link = $this->request->data('website');
                        $univ->contact_number = $this->request->data('contact');
                        $univ->email = $this->request->data('email');
                        
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1632') { $logoimg = $langlbl['title'] ; } 
                        } 
                    
                                          
                        if($saved = $univ_table->save($univ) )
                        {   
                            $clsid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "University Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $logoimg  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            
            public function addlocaluniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                 
                    $univ_table = TableRegistry::get('localuniversity');
                    $activ_table = TableRegistry::get('activity');
                    
                    if(!empty($this->request->data['logo']['name']))
                    {
                        if($this->request->data['logo']['type'] == "image/png" || $this->request->data['logo']['type'] == "image/jpeg"  || $this->request->data['logo']['type'] == "image/jpg" )
                        {
                            
                            $coverimg = $this->request->data['logo']['name'];
                            $uploadpath = 'univ_logos/';
                            $uploadfile = $uploadpath.$coverimg;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $coverimg; 
                            }
                        }   
                        else
                        {
                            $coverimg = "";
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    if(!empty($coverimg))
                    {
                        $univ = $univ_table->newEntity();
                        $univ->state_id = $this->request->data('state');
                        $univ->city = $this->request->data('city');
                        $univ->univ_name = $this->request->data('uni_name');
    		            $univ->about_univ = $this->request->data('desc');
                        $univ->logo = $coverimg;
                        $univ->academics = $this->request->data('courses');
    					$univ->website_link = $this->request->data('website');
                        $univ->contact_number = $this->request->data('contact');
                        $univ->email = $this->request->data('email');
                        
                        
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1632') { $logoimg = $langlbl['title'] ; } 
                        } 
                                          
                        if($saved = $univ_table->save($univ) )
                        {   
                            $clsid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Local University Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $logoimg  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function edituniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                 
                    $univ_table = TableRegistry::get('univrsities');
                    $activ_table = TableRegistry::get('activity');
                    
                    if(!empty($this->request->data['logo']['name']))
                    {
                        if($this->request->data['logo']['type'] == "image/png" || $this->request->data['logo']['type'] == "image/jpeg"  || $this->request->data['logo']['type'] == "image/jpg" )
                        {
                            
                            $coverimg = $this->request->data['logo']['name'];
                            $uploadpath = 'univ_logos/';
                            $uploadfile = $uploadpath.$coverimg;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $coverimg; 
                            }
                        }   
                        else
                        {
                            $coverimg = "";
                        }
                    }
                    elseif(!empty($this->request->data('elogo')))
                    {
                        $coverimg = $this->request->data('elogo');
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    if(!empty($coverimg))
                    {
                        $country_id = $this->request->data('country');
                        $univ_name = $this->request->data('uni_name');
    		            $about_univ = $this->request->data('desc');
                        $logo = $coverimg;
                        $academics = $this->request->data('courses');
    					$website_link = $this->request->data('website');
                        $contact_number = $this->request->data('contact');
                        $email = $this->request->data('email');
                        $id = $this->request->data('uid');
                        
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1632') { $logoimg = $langlbl['title'] ; } 
                        } 
                        
                        $update = $univ_table->query()->update()->set(['website_link' => $website_link, 'academics' => $academics, 'country_id' => $country_id , 'univ_name' => $univ_name, 'about_univ' => $about_univ, 'logo' => $logo, 'email' => $email , 'contact_number' => $contact_number ])->where([ 'id' => $id  ])->execute();         
                        if($update)
                        {   
                            //$clsid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "University Updated"  ;
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
                        $res = [ 'result' => $logoimg  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }

            public function editlocaluniversity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                 
                    $univ_table = TableRegistry::get('localuniversity');
                    $activ_table = TableRegistry::get('activity');
                    
                    if(!empty($this->request->data['logo']['name']))
                    {
                        if($this->request->data['logo']['type'] == "image/png" || $this->request->data['logo']['type'] == "image/jpeg"  || $this->request->data['logo']['type'] == "image/jpg" )
                        {
                            
                            $coverimg = $this->request->data['logo']['name'];
                            $uploadpath = 'univ_logos/';
                            $uploadfile = $uploadpath.$coverimg;
                            if(move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                            {
                                $this->request->data['logo'] = $coverimg; 
                            }
                        }   
                        else
                        {
                            $coverimg = "";
                        }
                    }
                    elseif(!empty($this->request->data('elogo')))
                    {
                        $coverimg = $this->request->data('elogo');
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    if(!empty($coverimg))
                    {
                        $state_id = $this->request->data('state');
                        $city = $this->request->data('city');
                        $univ_name = $this->request->data('uni_name');
    		            $about_univ = $this->request->data('desc');
                        $logo = $coverimg;
                        $academics = $this->request->data('courses');
    					$website_link = $this->request->data('website');
                        $contact_number = $this->request->data('contact');
                        $email = $this->request->data('email');
                        $id = $this->request->data('uid');
                        
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1632') { $logoimg = $langlbl['title'] ; } 
                        } 
                        
                        $update = $univ_table->query()->update()->set(['website_link' => $website_link, 'academics' => $academics, 'state_id' => $state_id , 'city' => $city,  'univ_name' => $univ_name, 'about_univ' => $about_univ, 'logo' => $logo, 'email' => $email , 'contact_number' => $contact_number ])->where([ 'id' => $id  ])->execute();         
                        if($update)
                        {   
                            //$clsid = $saved->id;
                            $activity = $activ_table->newEntity();
                            $activity->action =  "University Updated"  ;
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
                        $res = [ 'result' => $logoimg  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function addcommunity()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                 
                    $knowledge_table = TableRegistry::get('knowledge_center');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    elseif($this->request->data('file_type') == "audio")
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if($this->request->data['file_upload']['type'] == "application/pdf" )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                  
                   if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                        if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1633') { $doccondt = $langlbl['title'] ; } 
                        } 
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Knowledge Community Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }

            public function edit()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $knowledge_table = TableRegistry::get('knowledge_center');
				$retrieve_knowledge = $knowledge_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function editcommunity()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('knowledge_center');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                   
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1633') { $doccondt = $langlbl['title'] ; } 
                    } 
                        
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        
                        
                        $filess = $file_link;
                        
                    }
                    elseif($this->request->data('efile_type') == "audio")
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['type'] == "audio/mpeg" )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename; 
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if($this->request->data['efile_upload']['type'] == "application/pdf" )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Knowledge Community Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
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
                $knowledge_table = TableRegistry::get('knowledge_center');
                $knowcomm_table = TableRegistry::get('knowledge_center_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        			
        			
        			
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function listing()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id') ;
                $knowcomm_table = TableRegistry::get('knowledge_center_comments');
				$retrieve_comments = $knowcomm_table->find()->where(['parent' => $id])->toArray();
                return $this->json($retrieve_comments);
            }

            public function deleteuniv()
            {
				
                $kbid = $this->request->data('val') ;
                $univ_table = TableRegistry::get('univrsities');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $univ_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $univ_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $univ_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
                        
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
            
            public function deletelocaluniv()
            {
				
                $kbid = $this->request->data('val') ;
                $univ_table = TableRegistry::get('localuniversity');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $univ_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $univ_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $univ_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
                        
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

			public function delete()
            {
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kbid = $this->request->data('val') ;
                $knowledge_table = TableRegistry::get('knowledge_center');
                
                $kid = $knowledge_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $knowledge_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
                        
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
            
            public function replycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('knowledge_center_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function addcomment()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('knowledge_center_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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

            /****************************State Exam************************/
            
            public function stateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function latinphiloStateexam()
            { 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'latinphilo'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'latinphilo');
                $this->viewBuilder()->setLayout('usersa');
            }
            public function mathphyStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'mathphy'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'mathphy');
                $this->viewBuilder()->setLayout('usersa');
            }
            public function chembioStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'chembio'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'chembio');
                $this->viewBuilder()->setLayout('usersa');
            }
            public function generalStateexam()
            { 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'general'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'general');
                $this->viewBuilder()->setLayout('usersa');
            }
            public function commercialeStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'commerciale'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know);
                $this->set("added_for",'commerciale');
                $this->viewBuilder()->setLayout('usersa');
            }
            public function techniquesStateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $stateexam_table = TableRegistry::get('state_exam');
                $retrieve_know = $stateexam_table->find()->where(['added_for' => 'techniques'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for",'techniques');
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addstateexam()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $knowledge_table = TableRegistry::get('state_exam');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            if(($this->request->data['file_upload']['type'] == "application/msword") || ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        $knowledge->added_for = $this->request->data('data_addedfor');
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "State Exam Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function deletestateexam()
            {
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kbid = $this->request->data('val') ;
                $stateexam_table = TableRegistry::get('state_exam');
                
                $kid = $stateexam_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $stateexam_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $stateexam_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            public function editstate()
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $knowledge_table = TableRegistry::get('state_exam');
				$retrieve_knowledge = $knowledge_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function editstateexam()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $knowledge_table = TableRegistry::get('state_exam');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1633') { $doccondt = $langlbl['title'] ; } 
                    } 
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    $added_for = $this->request->data('edata_addedfor');
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['added_for' => $added_for, 'file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "State Exam Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function viewstateexam()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('state_exam');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editstateexam" class="editstateexam" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletestateexam" class=" js-sweetalert " title="Delete" data-str="State Exam" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewexamstate/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }

            public function viewexamstate($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('state_exam');
                $knowcomm_table = TableRegistry::get('state_exam_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        			
        			
        			
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addstatecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('state_exam_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "State Exam Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function stateexmreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('state_exam_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "State Exam Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            
            /**************************Machine Learning*****************************/
            
            public function machinelearning()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $machine_table = TableRegistry::get('machine_learning');
                $retrieve_know = $machine_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addmachinelearning()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('machine_learning');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Machine Learning Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function deletemachinelearning()
            {
				
                $kbid = $this->request->data('val') ;
                $machinelearn_table = TableRegistry::get('machine_learning');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $machinelearn_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $machinelearn_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $machinelearn_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            public function editmachine()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $machinelearn_table = TableRegistry::get('machine_learning');
				$retrieve_knowledge = $machinelearn_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function editmachinelearning()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('machine_learning');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                            if($langlbl['id'] == '1633') { $doccondt = $langlbl['title'] ; } 
                        } 
                        
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Machine Learning Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function viewmachine()
            {
                $knowledge_table = TableRegistry::get('machine_learning');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $filter = $this->request->data('filter');
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editmachinelearning" class="editmachinelearning" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletemachinelearning" class=" js-sweetalert " title="Delete" data-str="Machine Learning" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewmachinelearning/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }

            public function viewmachinelearning($id)
            {  
                $knowledge_table = TableRegistry::get('machine_learning');
                $knowcomm_table = TableRegistry::get('machine_learning_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addmachinecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('machine_learning_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Machine Learning Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function machinelearnreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('machine_learning_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Machine Learning Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            
            /*********************New Technologies***************************/
            public function newtechnologies()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $machine_table = TableRegistry::get('newtechnologies');
                $retrieve_know = $machine_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addnewtechnologies()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('newtechnologies');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "New Technologies Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function deletenewtechnologies()
            {
				
                $kbid = $this->request->data('val') ;
                $machinelearn_table = TableRegistry::get('newtechnologies');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $machinelearn_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $machinelearn_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $machinelearn_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            public function edittechnology()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $machinelearn_table = TableRegistry::get('newtechnologies');
				$retrieve_knowledge = $machinelearn_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function editnewtechnologies()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $knowledge_table = TableRegistry::get('newtechnologies');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Machine Learning Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function viewtechnologies()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('newtechnologies');
                $filter = $this->request->data('filter');
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editnewtechnologies" class="editnewtechnologies" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletenewtechnologies" class=" js-sweetalert " title="Delete" data-str="New Technologies" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewnewtechnologies/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }

            public function viewnewtechnologies($id)
            {  
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $knowledge_table = TableRegistry::get('newtechnologies');
                $knowcomm_table = TableRegistry::get('newtechnologies_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addtechnologiescomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('newtechnologies_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "New Technologies Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function technologiesreplycomments()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('newtechnologies_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "New Technologies Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            /*********************How it works***************************/
            
            
            public function howitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $this->viewBuilder()->setLayout('usersa');
            }
            public function sclHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'school'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'school'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function tchrHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'teacher'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'teacher'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function studHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'student'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'student'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function parentHowitworks()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $howitworks_table = TableRegistry::get('howitworks');
                $retrieve_know = $howitworks_table->find()->where(['added_for' => 'parent'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("addedfor", 'parent'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addhowitworks()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('howitworks');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if($this->request->data('file_type') == "word")
                        {
                            if(!empty($this->request->data['file_upload']['name']))
                            {   
                                
                                if(($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($this->request->data['file_upload']['type'] == "application/msword") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                                {
                                    $filess =  $this->request->data['file_upload']['name'];
                                    $filename = $this->request->data['file_upload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['file_upload'] = $filename; 
                                    }
                                }    
                            }
                            else
                            {
                                $filess = "";
                            }
                        }
                        elseif($this->request->data('file_type') == "pdf")
                        {
                            if(!empty($this->request->data['file_upload']['name']))
                            {   
                                
                                if($this->request->data['file_upload']['type'] == "application/pdf")
                                {
                                    $filess =  $this->request->data['file_upload']['name'];
                                    $filename = $this->request->data['file_upload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['file_upload'] = $filename; 
                                    }
                                }    
                            }
                            else
                            {
                                $filess = "";
                            }
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        $knowledge->added_for = $this->request->data('data_addedfor');
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "How it works Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function deletehowitworks()
            {
				
                $kbid = $this->request->data('val') ;
                $howitworks_table = TableRegistry::get('howitworks');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $howitworks_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $howitworks_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $howitworks_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            public function edithowworks()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $machinelearn_table = TableRegistry::get('howitworks');
				$retrieve_knowledge = $machinelearn_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function edithowitworks()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('howitworks');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if($this->request->data('efile_type') == "word")
                        {
                            if(!empty($this->request->data['efile_upload']['name']))
                            {   
                                if(($this->request->data['efile_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                                {
                                    $filess =  $this->request->data['efile_upload']['name'];
                                    $filename = $this->request->data['efile_upload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['efile_upload'] = $filename; 
                                    }
                                }    
                            }
                            else
                            {
                                $filess = $this->request->data['efileupload'];
                            }
                        }
                        elseif($this->request->data('efile_type') == "pdf")
                        {
                            if(!empty($this->request->data['efile_upload']['name']))
                            {   
                                if($this->request->data['efile_upload']['type'] == "application/pdf") 
                                {
                                    $filess =  $this->request->data['efile_upload']['name'];
                                    $filename = $this->request->data['efile_upload']['name'];
                                    $uploadpath = 'img/';
                                    $uploadfile = $uploadpath.$filename;
                                    if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                    {
                                        $this->request->data['efile_upload'] = $filename; 
                                    }
                                }    
                            }
                            else
                            {
                                $filess = $this->request->data['efileupload'];
                            }
                        }
                        
                        
                    }
                    $addedfor = $this->request->data('edata_addedfor');
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['added_for' => $addedfor, 'file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "How it works Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function viewhowworks()
            {
                $knowledge_table = TableRegistry::get('howitworks');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#edithowitworks" class="edithowitworks" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletehowitworks" class=" js-sweetalert " title="Delete" data-str="How it works" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewhowitworks/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }

            public function viewhowitworks($id)
            {  
                $knowledge_table = TableRegistry::get('howitworks');
                $knowcomm_table = TableRegistry::get('howitworks_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addhowworkscomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('howitworks_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "How it works Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function howworksreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('howitworks_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "How it works Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            /*********************Leadership and Entrepreneurship *******************/
            
            
            public function leadership()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('leadership');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addleadership()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('leadership');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Leadership & Entrepreneurship Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function deleteleadership()
            {
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kbid = $this->request->data('val') ;
                $leadership_table = TableRegistry::get('leadership');
                
                $kid = $leadership_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {   
                    
					$del = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            public function editleader()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $leadership_table = TableRegistry::get('leadership');
				$retrieve_knowledge = $leadership_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
            
            public function editleadership()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('leadership');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Leadership & Entrepreneurship Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            public function viewleader()
            {
                $knowledge_table = TableRegistry::get('leadership');
                $filter = $this->request->data('filter');
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editleadership" class="editleadership" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deleteleadership" class=" js-sweetalert " title="Delete" data-str="Leadership & Entrepreneurship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewleadership/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                return $this->json($res);
            }

            public function viewleadership($id)
            {  
                $knowledge_table = TableRegistry::get('leadership');
                $knowcomm_table = TableRegistry::get('leadership_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            public function addleadershipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('leadership_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Leadership Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            public function leadershipreplycomments()
            {
                //print_r($_POST); die;
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    
                    $comments_table = TableRegistry::get('leadership_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "leadership Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            /******************* List Scholarship, Mentorship, Internship, Intensive English*************************/
            public function scholarship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('scholarship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function mentorship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('mentorship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function internship()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('internship');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function intensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function kgIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'kinder'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'kinder'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function primaryIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'primary'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'primary'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            public function highsclIntensiveEnglish()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $leadership_table = TableRegistry::get('intensive_english');
                $retrieve_know = $leadership_table->find()->where(['added_for' => 'highscl'])->order(['id' => 'desc'])->toArray() ;
                $this->set("know_details", $retrieve_know); 
                $this->set("added_for", 'highscl'); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            /********************** Add Scholarship, Mentorship, Internship, Intensive English *************************/
            public function addintensive()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('intensive_english');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        $knowledge->added_for = $this->request->data('data_addedfor');
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Intensive English Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
			
			public function addinternship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('internship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Internship Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
			public function addmentorship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('mentorship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Mentorship Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
			
            public function addscholarship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $knowledge_table = TableRegistry::get('scholarship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    
                    $video_type = "";
                    if($this->request->data('file_type') == "video")
                    {
                        $link = $this->request->data('file_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            //print_r($youex);
                            $file_link  = $youex[0]."embed/".$youex[1];
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['file_upload']['name']))
                        {   
                            
                            if(($this->request->data['file_upload']['type'] == "application/msword") ||  ($this->request->data['file_upload']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")||  ($this->request->data['file_upload']['type'] == "application/pdf") || ($this->request->data['file_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['file_upload']['name'];
                                $filename = $this->request->data['file_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['file_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['file_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = "";
                        }
                        
                    }
                    if(!empty($_POST['slim'][0]))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = "";
                    }
                    
                    if(!empty($filess))
                    {
                        $knowledge = $knowledge_table->newEntity();
                        $knowledge->file_type = $this->request->data('file_type');
                        $knowledge->title = $this->request->data('title');
    		            $knowledge->created_date = time();
                        $knowledge->description = $this->request->data('desc');
                        $knowledge->links = $filess;
    					$knowledge->status = 1;
                        //$knowledge->school_id = $compid;
                        $knowledge->video_type = $video_type;
                        $knowledge->image = $coverimg;
                        
                        //print_r($knowledge); die;
                        
                                          
                        if($saved = $knowledge_table->save($knowledge) )
                        {   
                            $clsid = $saved->id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Scholarship Added"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($clsid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            /********************* Delete Scholarship, Mentorship, Internship, Intensive English *******************/
            
			public function deleteintensive()
            {				
                $kbid = $this->request->data('val') ;
                $leadership_table = TableRegistry::get('intensive_english');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $leadership_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {                       
					$del = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            public function deleteinternship()
            {				
                $kbid = $this->request->data('val') ;
                $leadership_table = TableRegistry::get('internship');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $leadership_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {                       
					$del = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
			public function deletementorship()
            {				
                $kbid = $this->request->data('val') ;
                $leadership_table = TableRegistry::get('mentorship');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $leadership_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {                       
					$del = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
			public function deletescholarship()
            {				
                $kbid = $this->request->data('val') ;
                $leadership_table = TableRegistry::get('scholarship');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $kid = $leadership_table->find()->select(['id'])->where(['id'=> $kbid ])->first();
                if($kid)
                {                       
					$del = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                    if($del)
                    {
						$del_knowledge = $leadership_table->query()->delete()->where([ 'id' => $kbid ])->execute(); 
                        $res = [ 'result' => 'success'  ];
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
            
            /********************* Fetch Edit Data Scholarship, Mentorship, Internship, Intensive English *******************/
            
			public function editscholar()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $leadership_table = TableRegistry::get('scholarship');
				$retrieve_knowledge = $leadership_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
			public function editmentor()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $leadership_table = TableRegistry::get('mentorship');
				$retrieve_knowledge = $leadership_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
			public function editintern()
            {   
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $leadership_table = TableRegistry::get('internship');
				$retrieve_knowledge = $leadership_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
			public function editintense()
            { 
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $id = $this->request->data('id');
                $leadership_table = TableRegistry::get('intensive_english');
				$retrieve_knowledge = $leadership_table->find()->where(['id' => $id])->toArray();
				return $this->json($retrieve_knowledge);
            }
			
						
            /********************* Update Scholarship, Mentorship, Internship, Intensive English *******************/			
            
            public function editintensive()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('intensive_english');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $addedfor = $this->request->data('edata_addedfor');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['added_for' -> $addedfor, 'file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Intensive English Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
			public function editinternship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('internship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload']; 
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Internship Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
			public function editmentorship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $knowledge_table = TableRegistry::get('mentorship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Mentorship Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
			public function editscholarship()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $knowledge_table = TableRegistry::get('scholarship');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $id = $this->request->data('ekid');
                    $file_type = $this->request->data('efile_type');
                    $file_title = $this->request->data('etitle');
                    $file_description = $this->request->data('edesc');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
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
                        if($langlbl['id'] == '1634') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    
                    if(!empty($_POST['slim'][0] ))
                    {
                        foreach($_POST['slim'] as $slim)
                        {
                            $abc = json_decode($slim);
                             
                            $cropped_image = $abc->output->image;
                            list($type, $cropped_image) = explode(';', $cropped_image);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $coverimg = date('ymdgis').'.png';
                            
                            $uploadpath = 'img/';
                            $uploadfile = $uploadpath.$coverimg; 
                            file_put_contents($uploadfile, $cropped_image);
                        }
                    }
                    else
                    {
                        $coverimg = $this->request->data('ecoverimage');
                    }
                    
                    
                    
                    if($this->request->data('efile_type') == "video")
                    {
                       // $filess = $this->request->data('efile_link');
                        $link = $this->request->data('efile_link');
                        $file_youtube = strpos($link, "youtube");
                        if($file_youtube != false)
                        {
                            $youex = explode("watch?v=",$link);
                            if(!empty($youex[1]))
                            {
                                $file_link  = $youex[0]."embed/".$youex[1];
                            }
                            else
                            {
                                $file_link = $link;
                            }
                            $video_type = "youtube";
                        }
                        
                        $file_vimeo =  strpos($link, "vimeo");
                        if($file_vimeo != false)
                        {
                            $file_link = $link;
                            $video_type = "vimeo";
                        }
                        $filess = $file_link;
                    }
                    else
                    {  
                        if(!empty($this->request->data['efile_upload']['name']))
                        {   
                            if(($this->request->data['efile_upload']['type'] == "application/msword") || ($this->request->data['efile_upload']['type'] == "application/pdf") || ($this->request->data['efile_upload']['type'] == "application/octet-stream") )
                            {
                                $filess =  $this->request->data['efile_upload']['name'];
                                $filename = $this->request->data['efile_upload']['name'];
                                $uploadpath = 'img/';
                                $uploadfile = $uploadpath.$filename;
                                if(move_uploaded_file($this->request->data['efile_upload']['tmp_name'], $uploadfile))
                                {
                                    $this->request->data['efile_upload'] = $filename; 
                                }
                            }    
                        }
                        else
                        {
                            $filess = $this->request->data['efileupload'];
                        }
                        
                    }
                    
                    if(!empty($filess))
                    {
    					$status = 0;
                                              
                        if($knowledge_table->query()->update()->set(['file_type' => $file_type , 'image' => $coverimg, 'description' => $file_description, 'links' => $filess, 'title' => $file_title , 'status' => $status ])->where([ 'id' => $id  ])->execute())
                        {   
                            $knowid = $id;
    
                            $activity = $activ_table->newEntity();
                            $activity->action =  "Scholarship Updated"  ;
                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                            $activity->origin = $this->Cookie->read('id');
                            $activity->value = md5($knowid); 
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
                        $res = [ 'result' => $doccondt  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }
                return $this->json($res);
            }
            
            			
            /********************* Filter Scholarship, Mentorship, Internship, Intensive English *******************/			           
            
            public function viewintensive()
            {
                $knowledge_table = TableRegistry::get('intensive_english');
                $filter = $this->request->data('filter');
                $addedfor = $this->request->data('filterfor');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->where(['added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video', 'added_for' => $addedfor])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editintensive" class="editintensive" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deleteintensive" class=" js-sweetalert " title="Delete" data-str="Intensive English" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewintensiveenglish/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewintern()
            {
                $knowledge_table = TableRegistry::get('internship');
                $filter = $this->request->data('filter');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editinternship" class="editinternship" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deleteinternship" class=" js-sweetalert " title="Delete" data-str="Internship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewinternship/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewmentor()
            {
                $knowledge_table = TableRegistry::get('mentorship');
                $filter = $this->request->data('filter');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editmentorship" class="editmentorship" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletementorship" class=" js-sweetalert " title="Delete" data-str="Mentorship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewmentorship/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
			public function viewscholar()
            {
                $knowledge_table = TableRegistry::get('scholarship');
                $filter = $this->request->data('filter');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                if($filter == "newest")
                {
                    $retrieve_know = $knowledge_table->find()->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "pdf")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'pdf'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "word")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'word'])->order(['id' => 'desc'])->toArray() ;
                }
                elseif($filter == "video")
                {
                    $retrieve_know = $knowledge_table->find()->where(['file_type' => 'video'])->order(['id' => 'desc'])->toArray() ;
                }
                $res = '';
                foreach($retrieve_know as $content)
                {
                    if(!empty($content['image'])) 
                    { 
                        $img = '<img src ="'. $this->base_url .'img/'. $content->image .'" >';
                    } else { 
                        $img = '<img src ="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">';
                    }
                    
                    if($content->file_type == 'video')
                    { 
                        $icon = '<i class="fa fa-video-camera"></i>';
                    }
                    elseif($content->file_type == 'word')
                    {
                        $icon = '<i class="fa fa-file-word-o"></i>';
                    } 
                    else
                    { 
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                    }
                    $res .= '<div class="col-sm-2 col_img">
                        <ul id="right_icon">
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-toggle="modal" data-target="#editscholarship" class="editscholarship" id="editknow" ><i class="fa fa-edit"></i></a></li>
                            <li> <a href="javascript:void(0)" data-id="'. $content['id'].'" data-url="deletescholarship" class=" js-sweetalert " title="Delete" data-str="Scholarship" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                            <li> <a href="'. $this->base_url.'knowledgeCenter/viewscholarship/'. md5($content['id']) .'" class="viewknow"><i class="fa fa-eye"></i></a></li>
                        </ul>'.
                        $img.'
                        <div class="set_icon">'.$icon.'</div>
                    </div>';
                }
                
                return $this->json($res);
            }
			
			/********************* View Scholarship, Mentorship, Internship, Intensive English *******************/			 

            public function viewintensiveenglish($id)
            {  
                $knowledge_table = TableRegistry::get('intensive_english');
                $knowcomm_table = TableRegistry::get('intensive_english_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function viewinternship($id)
            {  
                $knowledge_table = TableRegistry::get('internship');
                $knowcomm_table = TableRegistry::get('internship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function viewmentorship($id)
            {  
                $knowledge_table = TableRegistry::get('mentorship');
                $knowcomm_table = TableRegistry::get('mentorship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
			public function viewscholarship($id)
            {  
                $knowledge_table = TableRegistry::get('scholarship');
                $knowcomm_table = TableRegistry::get('scholarship_comments');
                $student_table = TableRegistry::get('student');
                $employee_table = TableRegistry::get('employee');
                $company_table = TableRegistry::get('company');
                $compid = $this->request->session()->read('company_id');
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
				$retrieve_knowledge = $knowledge_table->find()->where(['md5(id)' => $id])->toArray();
				$retrieve_comments = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent' => 0])->toArray();
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_comments as $key =>$comm)
        		{
        		    $addedby = $comm['added_by'];
        		    $schoolid = $comm['school_id'];
        			$userid = $comm['user_id'];
        			$teacherid = $comm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $comm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$comm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$comm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$comm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
				$retrieve_replies = $knowcomm_table->find()->where(['md5(knowledge_id)' => $id, 'parent !=' => 0])->toArray();
				
				foreach($retrieve_replies as $rkey => $replycomm)
        		{
        		    $addedby = $replycomm['added_by'];
        			$schoolid = $replycomm['school_id'];
        			$userid = $replycomm['user_id'];
        			$teacherid = $replycomm['teacher_id'];
        			//$i = 0;
        			$subjectsname = [];
        			if($addedby == "superadmin")
        			{
        			    $replycomm->user_name = "You-Me Global Education";
        			}
        			else
        			{
            			if($schoolid != null)
            			{
            			    $retrieve_school = $company_table->find()->select(['id' ,'comp_name' ])->where(['id' => $schoolid])->toArray() ;	
            				$replycomm->user_name = $retrieve_school[0]['comp_name'];
            				
            			}
            			if($userid != null)
            			{
            			    $retrieve_student = $student_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $userid])->toArray() ;	
            				$replycomm->user_name = $retrieve_student[0]['f_name']. " ". $retrieve_student[0]['l_name'];
            			}
            			if($teacherid != null)
            			{
            			    $retrieve_employee = $employee_table->find()->select(['id' ,'f_name', 'l_name' ])->where(['id' => $teacherid])->toArray() ;	
            				$replycomm->user_name = $retrieve_employee[0]['f_name']. " ". $retrieve_employee[0]['l_name'];
            			}
        			}
        		}
        		
        		$this->set("school_id", $compid); 
				$this->set("knowledge_details", $retrieve_knowledge); 
				$this->set("comments_details", $retrieve_comments); 
				$this->set("replies_details", $retrieve_replies); 
                $this->viewBuilder()->setLayout('usersa');
            }
            
            /********************* Comment Scholarship, Mentorship, Internship, Intensive English *******************/		

			public function addscholarshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('scholarship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Scholarship Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            public function addintensivecomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('intensive_english_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Intensive English Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
			public function addinternshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('internship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Internship Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
			public function addmentorshipcomment()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $comments_table = TableRegistry::get('mentorship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('comment_text');
                    $comments->knowledge_id = $this->request->data('kid');
		            $comments->created_date = time();
                    $comments->parent = 0;
                    $comments->added_by = "superadmin";
                    //$comments->school_id = $this->request->data('schoolid');
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Mentorship Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
            
            /*********************  Reply Comment Scholarship, Mentorship, Internship, Intensive English *******************/	
			
            public function scholarreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('scholarship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Scholarship Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
			public function mentorreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('mentorship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Mentorship Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
			public function internreplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('internship_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Internship Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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
			public function intensereplycomments()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {  
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $comments_table = TableRegistry::get('intensive_english_comments');
                    $activ_table = TableRegistry::get('activity');
                    $compid = $this->request->session()->read('company_id');
                    
                    $comments = $comments_table->newEntity();
                    $comments->comments = $this->request->data('reply_text');
                    $comments->knowledge_id = $this->request->data('r_kid');
		            $comments->created_date = time();
                    $comments->parent = $this->request->data('comment_id');
                    $comments->added_by = "superadmin";
                                          
                    if($saved = $comments_table->save($comments) )
                    {   
                        $clsid = $saved->id;

                        $activity = $activ_table->newEntity();
                        $activity->action =  "Intensive English Reply Comment Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($clsid); 
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

  

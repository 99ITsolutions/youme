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

//use \stdClass;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class IdentityController extends AppController
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


        $school_id = $this->request->session()->read('company_id');
        $session_id = $this->Cookie->read('sessionid');
        $identity_table = TableRegistry::get('identity');

        $retrieve_identity = $identity_table->find()->select(['id', 'id_title', 'school_name', 'logo', 'orientation'])->where(['school_id' => $school_id, 'session_id' => $session_id])->toArray();

        $this->set("identity_details", $retrieve_identity);
        $this->viewBuilder()->setLayout('user');
    }

    public function add()
    {

        	$school_table = TableRegistry::get('company');
			$school_id =$this->request->session()->read('company_id');

        	$school_details = $school_table->find()->select(['comp_name' , 'add_1' , 'email' , 'ph_no'])->where(['id' => $school_id ])->first();
			
			$this->set("school_details", $school_details);   

                        
                        
                        
                        
        $school_id = $this->request->session()->read('company_id');

        $identity_table = TableRegistry::get('identity');

        $retrieve_identity_layout = $identity_table->find()->where(['school_id' => $school_id])->toArray();

        $this->set("layout_details", $retrieve_identity_layout);

        $this->viewBuilder()->setLayout('user');
    }

    public function addcard()
    {

        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $school_id = $this->request->session()->read('company_id');
            $session_id = $this->Cookie->read('sessionid');
            $identity_table = TableRegistry::get('identity');
            $activ_table = TableRegistry::get('activity');

            $logo = "";
            $background = "";
            $signature = "";


            $retrieve_identity = $identity_table->find()->select(['id'])->where(['school_id' => $school_id])->count();



            if (!empty($this->request->data['logo']['name']))
            {
                if ($this->request->data['logo']['type'] == "image/jpeg" || $this->request->data['logo']['type'] == "image/jpg" || $this->request->data['logo']['type'] == "image/png")
                {
                    $logo = $this->request->data['logo']['name'];
                    $filename = $this->request->data['logo']['name'];
                    $uploadpath = 'img/';
                    $uploadfile = $uploadpath . $filename;
                    if (move_uploaded_file($this->request->data['logo']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['logo'] = $filename;
                    }
                }
            }

            if (!empty($this->request->data['background']['name']))
            {
                if ($this->request->data['background']['type'] == "image/jpeg" || $this->request->data['background']['type'] == "image/jpg" || $this->request->data['background']['type'] == "image/png")
                {
                    $background = $this->request->data['background']['name'];
                    $filename = $this->request->data['background']['name'];
                    $uploadpath = 'img/';
                    $uploadfile = $uploadpath . $filename;
                    if (move_uploaded_file($this->request->data['background']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['background'] = $filename;
                    }
                }
            }

            if (!empty($this->request->data['signature']['name']))
            {
                if ($this->request->data['signature']['type'] == "image/jpeg" || $this->request->data['signature']['type'] == "image/jpg" || $this->request->data['signature']['type'] == "image/png")
                {
                    $signature = $this->request->data['signature']['name'];
                    $filename = $this->request->data['signature']['name'];
                    $uploadpath = 'img/';
                    $uploadfile = $uploadpath . $filename;
                    if (move_uploaded_file($this->request->data['signature']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['signature'] = $filename;
                    }
                }
            }

            if (!empty($logo) && !empty($signature))
            {

                if ($retrieve_identity == 0)
                {

                    $identity = $identity_table->newEntity();
                    $identity->side = $this->request->data('sideradio');
                    $identity->orientation = $this->request->data('idtype');
                    $identity->school_name = $this->request->data('school_name');
                    $identity->school_address = $this->request->data('school_address');
                    $identity->school_email = $this->request->data('school_email');
                    $identity->school_phone = $this->request->data('school_phone');
                    $identity->id_title = $this->request->data('id_card_title');
                    $identity->color = $this->request->data('header_color');
                    $identity->admission_no = $this->request->data('stdnt_adm_no');
                    $identity->name = $this->request->data('stdnt_name');
                    $identity->class = $this->request->data('stdnt_class');
                    $identity->father_name = $this->request->data('stdnt_father_name');
                    $identity->mother_name = $this->request->data('stdnt_mother_name');
                    $identity->address = $this->request->data('stdnt_address');
                    $identity->phone = $this->request->data('stdnt_phone');
                    $identity->dob = $this->request->data('stdnt_dob');
                    $identity->blood_group = $this->request->data('stdnt_blood');
                    $identity->remarks = $this->request->data('remarks');
                    $identity->logo = $logo;
                    $identity->background = $background;
                    $identity->signature = $signature;
                    $identity->school_id = $school_id;
                    $identity->session_id = $session_id;


                    if ($saved = $identity_table->save($identity))
                    {

                        $activity = $activ_table->newEntity();
                        $activity->action = "ID Card Layout Added";
                        $activity->ip = $_SERVER['REMOTE_ADDR'];
                        $activity->origin = md5($this->Cookie->read('id'));
                        $activity->value = md5($saved->id);
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity);

                        if ($save)
                        {
                            $res = ['result' => 'success'];
                        }
                        else
                        {
                            $res = ['result' => 'activity'];
                        }
                    }
                    else
                    {
                        $res = ['result' => 'failed'];
                    }
                }
                else
                {
                   

                    //$identity = $identity_table->newEntity();
                    $side = $this->request->data('sideradio');
                    $orientation = $this->request->data('idtype');
                    $school_name = $this->request->data('school_name');
                    $school_address = $this->request->data('school_address');
                    $school_email = $this->request->data('school_email');
                    $school_phone = $this->request->data('school_phone');
                    $id_title = $this->request->data('id_card_title');
                    $color = $this->request->data('header_color');
                    $admission_no = $this->request->data('stdnt_adm_no');
                    $name = $this->request->data('stdnt_name');
                    $class = $this->request->data('stdnt_class');
                    $father_name = $this->request->data('stdnt_father_name');
                    $mother_name = $this->request->data('stdnt_mother_name');
                    $address = $this->request->data('stdnt_address');
                    $phone = $this->request->data('stdnt_phone');
                    $dob = $this->request->data('stdnt_dob');
                    $blood_group = $this->request->data('stdnt_blood');
                    $remarks = $this->request->data('remarks');
//                    $logo = $logo;
//                    $background = $background;
//                    $signature = $signature;
//                    $school_id = $school_id;
//                    $session_id = $session_id;

              //$query =     $identity->query()->update()->set(['side' => $side, 'orientation' => $orientation, 'school_name' => $school_name, 'school_address' => $school_address, 'school_email' => $school_email, 'school_phone' => $school_phone, 'id_title' => $id_title, 'color' => $color, 'admission_no' => $admission_no, 'name' => $name, 'class' => $class, 'father_name' => $father_name, 'mother_name' => $mother_name, 'address' => $address, 'phone' => $phone, 'dob' => $dob, 'blood_group' => $blood_group, 'remarks' => $remarks, 'logo' => $logo, 'background' => $background, 'signature' => $signature, 'session_id' => $session_id])->where(['school_id' => $school_id])->execute();
                
               //dd($query);
               
                //die("aaa");
                
         /*       
                 `id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `background` varchar(100) DEFAULT NULL,
  `signature` varchar(100) DEFAULT NULL,
  `school_name` varchar(150) DEFAULT NULL,
  `school_address` varchar(250) DEFAULT NULL,
  `school_email` varchar(150) DEFAULT NULL,
  `school_phone` varchar(50) DEFAULT NULL,
  `id_title` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `admission_no` varchar(150) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `class` varchar(500) DEFAULT NULL,
  `father_name` varchar(150) DEFAULT NULL,
  `mother_name` varchar(150) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(150) DEFAULT NULL,
  `dob` varchar(150) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `remarks` longtext,
  `side` varchar(20) DEFAULT NULL,
  `orientation` varchar(50) DEFAULT NULL,
  `school_font` varchar(100) DEFAULT NULL
            */             
                            if ($saved = $identity_table->query()->update()->set(['side' => $side, 'orientation' => $orientation, 'school_name' => $school_name, 'school_address' => $school_address, 'school_email' => $school_email, 'school_phone' => $school_phone, 'id_title' => $id_title, 'color' => $color, 'admission_no' => $admission_no, 'name' => $name, 'class' => $class, 'father_name' => $father_name, 'mother_name' => $mother_name, 'address' => $address, 'phone' => $phone, 'dob' => $dob, 'blood_group' => $blood_group, 'remarks' => $remarks, 'logo' => $logo, 'background' => $background, 'signature' => $signature, 'session_id' => $session_id])->where(['school_id' => $school_id])->execute())
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action = "ID Card Layout Added";
                        $activity->ip = $_SERVER['REMOTE_ADDR'];
                        $activity->origin = md5($this->Cookie->read('id'));
                        $activity->value = md5($saved->id);
                        $activity->created = strtotime('now');
                        $save = $activ_table->save($activity);

                        if ($save)
                        {
                            $res = ['result' => 'success'];
                        }
                        else
                        {
                            $res = ['result' => 'activity'];
                        }
                    }
                    else
                    {
                        //die("bbb");
                        $res = ['result' => 'failed'];
                    } 
                }
            }
            else
            {
                $res = ['result' => 'extension'];
            }
        }
        else
        {
            $res = ['result' => 'invalid operation'];
        }


        return $this->json($res);
    }

    public function delete()
    {
        $cid = $this->request->data('val');
        $identity_table = TableRegistry::get('identity');
        $activ_table = TableRegistry::get('activity');

        $clid = $identity_table->find()->select(['id'])->where(['id' => $cid])->first();
        if ($clid)
        {

            if ($identity_table->delete($identity_table->get($cid)))
            {
                $activity = $activ_table->newEntity();
                $activity->action = "Identity Layout Deleted";
                $activity->ip = $_SERVER['REMOTE_ADDR'];
                $activity->value = md5($cid);
                $activity->origin = md5($this->Cookie->read('id'));
                $activity->created = strtotime('now');

                if ($saved = $activ_table->save($activity))
                {
                    $res = ['result' => 'success'];
                }
                else
                {
                    $res = ['result' => 'failed'];
                }
            }
            else
            {
                $res = ['result' => 'not delete'];
            }
        }
        else
        {
            $res = ['result' => 'error'];
        }

        return $this->json($res);
    }

}

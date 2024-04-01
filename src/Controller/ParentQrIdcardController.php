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
 * This controller will render views from Template/Pages/$edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editsubject" data-toggle="modal"  data-target="#editsub" title="Edit"><i class="fa fa-edit"></i></button>';
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ParentQrIdcardController extends AppController
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
        $stid = $this->Cookie->read('pid'); 
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $message_table = TableRegistry::get('parent_message');
        $company_table = TableRegistry::get('company');
        $sid =$this->request->session()->read('parent_id');
        //$compid = $this->request->session()->read('company_id');
        $sessionid = $this->request->session()->read('session_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($sid))
        {
    		$retrieve_stud = $student_table->find()->where(['session_id' => $sessionid, 'parent_id' => $sid])->toArray() ;
            $this->set("studlist", $retrieve_stud);
            $this->set("message_details", $retrieve_msg_table); 
            $this->viewBuilder()->setLayout('usersa');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
    
    public function getscl()
    {
        $studid = $this->request->data('studid');
        $sid = $this->request->data('studid');
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $comp_table = TableRegistry::get('company');
        $message_table = TableRegistry::get('parent_message');
        $parentdtl_table = TableRegistry::get('parent_logindetails');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_stud = $student_table->find()->where(['id' => $studid])->first() ;
        
        $retrieve_scl = $comp_table->find()->select(['comp_name'])->where(['id' => $retrieve_stud['school_id']])->first() ;
        $data['sclname'] = $retrieve_scl['comp_name'];
        $listmsgs = '';
        
        $pid =$this->request->session()->read('parent_id');
        $sessionid = $this->request->session()->read('session_id');
        $compid = $retrieve_stud['school_id'];
        $imgqr = "";
        $downloadimg = "";
        $retrieve_stud = $student_table->find()->where(['id' => $sid ])->first();
        if($retrieve_stud['qrcode_img'] != "")
        {
            $imgqr = '<a class="example-image-link" href="'.$this->base_url.'codeqr/'.$retrieve_stud['qrcode_img'].'" data-lightbox="example-1"><img src="'.$this->base_url.'codeqr/'.$retrieve_stud['qrcode_img'].'" alt="" width="350px"></a>';
            $downloadimg = '<a href="'.$this->base_url.'codeqr/'.$retrieve_stud['qrcode_img'].'" target="_blank" class="btn btn-success" download>Download QR Code</a>';
        }
        $data['list'] = $imgqr;
        $data['downloadimg'] = $downloadimg;
        $data['studid'] = $studid;
        
        return $this->json($data);
        
    }


}

  


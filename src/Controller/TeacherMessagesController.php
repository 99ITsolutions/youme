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
class TeacherMessagesController extends AppController
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
        $tid = $this->Cookie->read('tid'); 
        $emp_table = TableRegistry::get('employee');
        $tchrid =$this->request->session()->read('tchr_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
        $company_table = TableRegistry::get('company');
        
        $message_table = TableRegistry::get('teacher_message');
        if(!empty($tid))
        {
            $retrieve_msg_table = $message_table->find()->where(['from_type' => 'teacher', 'from_id' => $tchrid,'parent'=>'0' ])->orWhere(['to_id' => $tchrid, 'to_type' => 'teacher','parent'=>'0'])->order(['id' => 'DESC'])->toArray();
            foreach($retrieve_msg_table as $key =>$msg)
    		{
    		    if($msg->from_type == 'teacher'){
    		        $retrieve_emp = $emp_table->find()->where(['id'=> $tchrid ])->first();
    		        $msg->sender = $retrieve_emp->f_name.' '.$retrieve_emp->l_name; 
    		        $msg->email = $retrieve_emp->email; 
    		       
    		    }else if($msg->from_type == 'school'){
    		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
    		        $msg->sender = $retrieve_comp->comp_name; 
    		        $msg->email = "";
    		        
    		    }
    		    $count_read = $message_table->find()->where(['parent'=> $msg->id, 'to_type'=>'teacher', 'read_msg'=>'0'])->count();
    		    $msg->read_count = $count_read; 
    		}
    
            $this->set("message_details", $retrieve_msg_table); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
            
    public function add(){   
        $compid = $this->request->session()->read('company_id');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        $this->viewBuilder()->setLayout('user');
    }
            
    public function addmessage()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $message_table = TableRegistry::get('teacher_message');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tid =$this->request->session()->read('tchr_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($tid))
            {
                $messg = $message_table->newEntity();
                
                $messg->subject = $this->request->data('subject');
                $messg->to_type = 'school';
                $messg->to_id = $compid;
                $messg->from_id = $tid;
                $messg->from_type ='teacher';
                $messg->school_id = $compid;
                $messg->created_date = time();
                $mesge = $this->request->data('descmessage');
                
                $company_table = TableRegistry::get('company');
                $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
                $to = $retrieve_comp->email; 
                $username = $retrieve_comp->comp_name;
                
                $emp_table = TableRegistry::get('employee');
                $retrieve_emp = $emp_table->find()->where(['id'=> $tid])->first();
                $from = $retrieve_emp->email;
                $name = $retrieve_emp->f_name.' '.$retrieve_emp->l_name;
                $subject = $this->request->data('subject');
                
                $htmlContent = '<tr><td class="text" style="color:#191919; font-size:16px; line-height:30px;padding-bottom:20px;  text-align:left;"><multiline>'.$mesge.'</multiline></td></tr>';
                $attach = array();
                if(!empty($this->request->data['upload_file'])){   
                    $filename = time().$this->request->data['upload_file']['name'];
                    $uploadpath = 'messages/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['upload_file']['tmp_name'], $uploadfile))
                    {
                        $messg->attachment = $filename; 
                    }
                    $attach[] = $uploadfile;
                }
                $messg->message = $mesge;     
             
                if($saved = $message_table->save($messg) ){     
                    $strucid = $saved->id;
                    $this->sendUserEmail($to,$from,$name,$subject,$htmlContent,$username,$attach);
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Message Send"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('tid');
                    $activity->value = md5($strucid); 
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
                 return $this->redirect('/login/') ;
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
        $id = base64_decode($id);
        $compid = $this->request->session()->read('company_id');
        $message_table = TableRegistry::get('teacher_message');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($compid))
        {
            $retrieve_unreadmsg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id, 'to_type'=> 'teacher'])->toArray();
                foreach($retrieve_unreadmsg as $unread)
                {
                    if($unread['read_msg'] == 0 && $unread['parent'] != 0)
                    {
                        $message_table->query()->update()->set([ 'read_msg'=> '1', 'read_datetime' => time() ])->where([ 'id' => $unread['id']  ])->execute();
                
                    }
                }
            
            $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
    		$all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();
    		
    		$company_table = TableRegistry::get('company');
    		$emp_table = TableRegistry::get('employee');
    		foreach($all_msg as $key =>$msg){
    		    if($msg->from_type == 'teacher'){
    		        $retrieve_emp = $emp_table->find()->where(['id'=> $msg->from_id ])->first();
    		        $msg->sender = $retrieve_emp->f_name.' '.$retrieve_emp->l_name; 
    		        $msg->email = $retrieve_emp->email; 
    		    }else if($msg->from_type == 'school'){
    		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
    		        $msg->sender = $retrieve_comp->comp_name; 
    		        $msg->email = "";
    		    }
    		}
    		$this->set("id", $id); 
            $this->set("all_messages", $all_msg); 
            $this->set("get_messages", $retrieve_msg); 
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
            
    public function addreply()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $message_table = TableRegistry::get('teacher_message');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $tid =$this->request->session()->read('tchr_id');
            if(!empty($tid))
            {
                $msg = $message_table->newEntity();
                if($this->request->data['id']){
                    $id=$this->request->data('id');
                    $msg->parent = $this->request->data('id');	 
                    $msg->subject = '' ;
                    $msg->to_type = 'school';
                    $msg->to_id = $compid;
                    $msg->from_id = $tid;
                    $msg->from_type ='teacher';
                    $msg->message = $this->request->data('message');	
                    $msg->school_id = $compid;
                    $msg->created_date = time();
                }
            
                if(!empty($this->request->data['upload_file'])){   
                    $filename = time().$this->request->data['upload_file']['name'];
                    $uploadpath = 'messages/';
                    $uploadfile = $uploadpath.$filename;
                    if(move_uploaded_file($this->request->data['upload_file']['tmp_name'], $uploadfile))
                    {
                        $msg->attachment = $filename; 
                    }
                }
                               
                if($saved = $message_table->save($msg) ){     
                    $strucid = $saved->id;
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Message Send"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('tid');
                    $activity->value = md5($strucid); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;
    
                    if($save)
                    {   
                        $res = [ 'result' => 'success'  , 'msgid' => $strucid ];
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


}

  


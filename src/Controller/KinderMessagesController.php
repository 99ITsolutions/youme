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
class KinderMessagesController extends AppController
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
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
        $student_table = TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $sid =$this->request->session()->read('student_id');
        
        $compid = $this->request->session()->read('company_id');
        $company_table = TableRegistry::get('company');
        
        $message_table = TableRegistry::get('messages');
        if(!empty($sid))
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_msg_table = $message_table->find()->where(['from_type' => 'student', 'from_id' => $sid,'parent'=>'0' ])->orWhere(['to_id' => $sid, 'to_type' => 'student','parent'=>'0'])->order(['id' => 'DESC'])->toArray();
            foreach($retrieve_msg_table as $key =>$msg)
    		{
    		    if($msg->from_type == 'student'){
    		        $retrieve_student = $student_table->find()->where(['id'=> $sid ])->first();
    		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_student->class ])->first();
    		        $msg->sender = $retrieve_student->f_name.' '.$retrieve_student->l_name; 
    		        $msg->student_no = $retrieve_student->adm_no; 
    		        $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section. "(". $retrieve_class->school_sections.")"; 
    		    }else if($msg->from_type == 'school'){
    		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
    		        $msg->sender = $retrieve_comp->comp_name; 
    		        $msg->student_no = "";
    		        $msg->classname = "";
    		    }
    		    $count_read = $message_table->find()->where(['parent'=> $msg->id, 'to_type'=>'student', 'read_msg'=>'0'])->count();
    		    $msg->read_count = $count_read; 
    		}
    
            $this->set("message_details", $retrieve_msg_table); 
            $this->viewBuilder()->setLayout('kinder');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
            
    public function add(){   
        $compid = $this->request->session()->read('company_id');
		$class_table = TableRegistry::get('class');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		if(!empty($compid))
		{
            $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
            
            $this->set("class_details", $retrieve_class);
            $this->viewBuilder()->setLayout('kinder');
		}
		else
		{
		     return $this->redirect('/login/') ;
		}
    }
            
    public function addmessage()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $message_table = TableRegistry::get('messages');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $sid =$this->request->session()->read('student_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($sid))
            {
                $messg = $message_table->newEntity();
                
                $messg->subject = $this->request->data('subject');
                $messg->to_type = 'school';
                $messg->to_id = $compid;
                $messg->from_id = $sid;
                $messg->from_type ='student';
                $messg->school_id = $compid;
                $messg->created_date = time();
                $mesge = $this->request->data('descmessage');
                
                $company_table = TableRegistry::get('company');
                $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
                $to = $retrieve_comp->email; 
                $username = $retrieve_comp->comp_name;
                
                $student_table = TableRegistry::get('student');
                $retrieve_student = $student_table->find()->where(['id'=> $sid])->first();
                $from = $retrieve_student->email;
                $name = $retrieve_student->f_name.' '.$retrieve_student->l_name;
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
             
                // print_r($messg); die;
                if($saved = $message_table->save($messg) ){     
                    $strucid = $saved->id;
                    
                    $this->sendUserEmail($to,$from,$name,$subject,$htmlContent,$username,$attach);
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Message Send"  ;
                    $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                    $activity->origin = $this->Cookie->read('id');
                    $activity->value = md5($strucid); 
                    $activity->created = strtotime('now');
                    $save = $activ_table->save($activity) ;
    
                    if($save)
                    {   
                        $res = [ 'result' => 'success'  , 'feesid' => $strucid ];
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
        $message_table = TableRegistry::get('messages');
        if(!empty($compid))
        {
            $retrieve_unreadmsg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id, 'to_type'=> 'student'])->toArray();
            foreach($retrieve_unreadmsg as $unread)
            {
                //print_r($unread); 
                if($unread['read_msg'] == 0 && $unread['parent'] != 0)
                {
                    $message_table->query()->update()->set([ 'read_msg'=> '1', 'read_datetime' => time() ])->where([ 'id' => $unread['id']  ])->execute();
            
                }
            }
            //$message_table->query()->update()->set([ 'read_msg'=> '1' ])->where([ 'id' => $id  ])->orWhere(['parent' => $id, 'to_type'=> 'student'])->execute();
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
    		$all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();
    		
    		$company_table = TableRegistry::get('company');
    		$student_table = TableRegistry::get('student');
    		$class_table = TableRegistry::get('class');
    		foreach($all_msg as $key =>$msg){
    		    if($msg->from_type == 'student'){
    		        $retrieve_student = $student_table->find()->where(['id'=> $msg->from_id ])->first();
    		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_student->class ])->first();
    		        $msg->sender = $retrieve_student->f_name.' '.$retrieve_student->l_name; 
    		        $msg->student_no = $retrieve_student->adm_no; 
    		        $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section. "(". $retrieve_class->school_sections.")"; 
    		    }else if($msg->from_type == 'school'){
    		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
    		        $msg->sender = $retrieve_comp->comp_name; 
    		        $msg->student_no = "";
    		        $msg->classname = "";
    		    }
    		}
    		$this->set("id", $id); 
            $this->set("all_messages", $all_msg); 
            $this->set("get_messages", $retrieve_msg); 
            $this->viewBuilder()->setLayout('kinder');
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
            $message_table = TableRegistry::get('messages');
            $activ_table = TableRegistry::get('activity');
            $compid = $this->request->session()->read('company_id');
            $sid =$this->request->session()->read('student_id');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            if(!empty($sid))
            {
                $msg = $message_table->newEntity();
                if($this->request->data['id']){
                    $id=$this->request->data('id');
                    $msg->parent = $this->request->data('id');	 
                    $msg->subject = '' ;
                    $msg->to_type = 'school';
                    $msg->to_id = $compid;
                    $msg->from_id = $sid;
                    $msg->from_type ='student';
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
                    $activity->origin = $this->Cookie->read('id');
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


    public function delete()
    {
        $rid = $this->request->data('val') ;
        $fee_structure_table = TableRegistry::get('fee_structure');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
            
        $del = $fee_structure_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
        if($del)
		{
            $activity = $activ_table->newEntity();
            $activity->action =  "successfully Deleted!"  ;
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
        return $this->json($res);
    }

}

  


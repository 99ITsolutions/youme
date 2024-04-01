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
class ParentMessagesController extends AppController
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
            
    public function add($studid)
    { 
        $parentid = $this->request->session()->read('parent_id');
        $sessionid = $this->request->session()->read('session_id');
        $stud_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_stud = $stud_table->find()->select(['school_id'])->where(['session_id' => $sessionid, 'parent_id' => $parentid])->first() ;
        $compid = $retrieve_stud['school_id'];
		$class_table = TableRegistry::get('class');
		if(!empty($compid))
		{
            $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['school_id' => $compid, 'active' => 1])->toArray() ;
            $this->set("class_details", $retrieve_class);
            $this->set("stu_id", $studid);
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
        
        $retrieve_msg_table = $message_table->find()->where(['from_type' => 'parent', 'from_id' => $pid,'parent'=>'0' ])->orWhere(['to_id' => $pid, 'to_type' => 'parent','parent'=>'0'])->order(['id' => 'DESC'])->toArray();
        foreach($retrieve_msg_table as $key =>$msg)
		{
		    if($msg->from_type == 'parent'){
		        $retrieve_parent = $parentdtl_table->find()->where(['id'=> $pid ])->first();
		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_stud->class ])->first();
		        $msg->sender = $retrieve_stud->f_name.' '.$retrieve_stud->l_name; 
		        $msg->student_no = $retrieve_stud->adm_no; 
		        $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section. "(". $retrieve_class->school_sections.")"; 
		    }else if($msg->from_type == 'school'){
		        $retrieve_comp = $company_table->find()->where(['id'=> $compid ])->first();
		        $msg->sender = $retrieve_comp->comp_name; 
		        $msg->student_no = "";
		        $msg->classname = "";
		    }
		    $count_read = $message_table->find()->where(['parent'=> $msg->id, 'to_type'=>'parent', 'read_msg'=>'0'])->count();
		    $msg->read_count = $count_read; 
		}
        
        foreach($retrieve_msg_table as $value){
            if($value['read_count'] > 0){  $read ='class="font-weight-bold"'; }else{ $read =''; }
            if($value['read_count'] > 0){  $abc =  '<b>Re: </b>'; } 
            $listmsgs .= '<tr>
                <td>
                    <span '. $read .'>'.$value['sender']." - ". $value['student_no']. " (Class: ". $value['classname'] . ")".'</span>
                </td>
                <td>
                    '.$abc.' <span '. $read .'>'. $value['subject'] .'</span>
                </td>
                 <td>
                    <span>'. date('M d, Y H:i A', $value->created_date).'</span>
                </td>
                <td>
                    <a href="'.$baseurl.'ParentMessages/view/'. base64_encode($value['id']) .'"  class="btn btn-sm btn-outline-secondary" title="View"><i class="fa fa-eye"></i></a>
                </td>
            </tr>';
            
            $n++;
        }
        
        $data['list'] = $listmsgs;
        $data['studid'] = $studid;
        
        return $this->json($data);
        
    }
            
    public function addmessage()
    {   
        if ($this->request->is('ajax') && $this->request->is('post') )
        { 
            $message_table = TableRegistry::get('parent_message');
            $activ_table = TableRegistry::get('activity');
            $stud_table = TableRegistry::get('student');
            $this->request->session()->write('LAST_ACTIVE_TIME', time()); 
            $pid =$this->request->session()->read('parent_id');
            $sessionid =$this->request->session()->read('session_id');
            $studid = $this->request->data('stu_id');
            $retrieve_stud = $stud_table->find()->select(['school_id'])->where(['id' => $studid])->first() ;
            
            $compid = $retrieve_stud['school_id'];
            
            if(!empty($pid))
            {
                $messg = $message_table->newEntity();
                
                $messg->subject = $this->request->data('subject');
                $messg->to_type = 'school';
                $messg->to_id = $compid;
                $messg->from_id = $pid;
                $messg->from_type ='parent';
                $messg->student_id = $studid;
                $messg->school_id = $compid;
                $messg->created_date = time();
                $messg->message = $this->request->data('descmessage');
                
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
                if($saved = $message_table->save($messg) ){     
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
        $parentid = $this->request->session()->read('parent_id');
        $message_table = TableRegistry::get('parent_message');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($parentid))
        {
            $retrieve_unreadmsg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id, 'to_type'=> 'parent'])->toArray();
            foreach($retrieve_unreadmsg as $unread)
            { 
                if($unread['read_msg'] == 0)
                {
                    $message_table->query()->update()->set([ 'read_msg'=> '1', 'read_datetime' => time() ])->where([ 'id' => $unread['id']  ])->execute();
                }
            }
            $retrieve_msg = $message_table->find()->where(['id'=> $id ])->first();
    		$all_msg = $message_table->find()->where(['id'=> $id ])->orWhere(['parent' => $id])->order(['id' => 'ASC'])->toArray();
    		
    		$company_table = TableRegistry::get('company');
    		$student_table = TableRegistry::get('student');
    		$class_table = TableRegistry::get('class');
    		foreach($all_msg as $key =>$msg){
    		    if($msg->from_type == 'parent'){
    		        $retrieve_student = $student_table->find()->where(['id'=> $msg->student_id ])->first();
    		        $retrieve_class = $class_table->find()->where(['id'=> $retrieve_student->class ])->first();
    		        $msg->sender = $retrieve_student->f_name.' '.$retrieve_student->l_name; 
    		        $msg->student_no = $retrieve_student->adm_no; 
    		        $msg->classname = $retrieve_class->c_name."-".$retrieve_class->c_section. "(". $retrieve_class->school_sections.")"; 
    		    }else if($msg->from_type == 'school'){
    		        $retrieve_comp = $company_table->find()->where(['id'=> $msg->school_id ])->first();
    		        $msg->sender = $retrieve_comp->comp_name; 
    		        $msg->student_no = "";
    		        $msg->classname = "";
    		    }
    		}
    		$this->set("id", $id); 
            $this->set("all_messages", $all_msg); 
            $this->set("get_messages", $retrieve_msg); 
            $this->viewBuilder()->setLayout('usersa');
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
            $message_table = TableRegistry::get('parent_message');
            $activ_table = TableRegistry::get('activity');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $pid = $this->request->session()->read('parent_id');
            $id = $this->request->data('id');
            
            $retrieve_msg = $message_table->find()->where(['id' => $id])->first() ;
            
            if(!empty($pid))
            {
                $msg = $message_table->newEntity();
                if($this->request->data['id']){
                    
                    $msg->parent = $this->request->data('id');	 
                    $msg->subject = '' ;
                    $msg->to_type = 'school';
                    $msg->to_id = $retrieve_msg->school_id;
                    $msg->student_id = $retrieve_msg->student_id;
                    $msg->from_id = $pid;
                    $msg->from_type ='parent';
                    $msg->message = $this->request->data('message');	
                    $msg->school_id = $retrieve_msg->school_id;
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

  


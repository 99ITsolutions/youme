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
class ReporteditrequestController  extends AppController
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
		if($this->Cookie->read('logtype') == md5('School Subadmin'))
		{
			$sclsub_id = $this->Cookie->read('subid');
			$sclsub_table = $sclsub_table->find()->select(['sub_privileges' , 'scl_sub_priv'])->where(['md5(id)'=> $sclsub_id, 'school_id'=> $compid ])->first() ; 
			$scl_privilage = explode(',',$sclsub_table['sub_privileges']) ;
			$subpriv = explode(",", $sclsub_table['scl_sub_priv']); 
		}
		
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $reportmm_list = TableRegistry::get('reportmax_marks');
        $activ_table = TableRegistry::get('activity');
        $employee_table = TableRegistry::get('employee');
        $class_table = TableRegistry::get('class');  
        $subject_table = TableRegistry::get('subjects');
        
		$sessionid = $this->Cookie->read('sessionid');
		$compid = $this->request->session()->read('company_id');
		
		$retrieve_reportmm = $reportmm_list->find()->where(['school_id' => $compid, 'session_id' => $sessionid ])->distinct('subject_id')->order(['id' => 'ASC'])->toArray() ;
        foreach($retrieve_reportmm as $reportmm)
        {
            $retrieve_emp = $employee_table->find()->where(['id' => $reportmm['teacher_id'] ])->first() ;
            $reportmm->tchrnam = $retrieve_emp['l_name']." ".$retrieve_emp['f_name'];
            
            $retrieve_cls = $class_table->find()->where(['id' => $reportmm['class_id'] ])->first() ;
            $reportmm->clsnam = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']. " (".$retrieve_cls['school_sections'].")";
            
            $retrieve_sub = $subject_table->find()->where(['id' => $reportmm['subject_id'] ])->first() ;
            $reportmm->subjnam = $retrieve_sub['subject_name'];
        }
        
        $this->set("reportmmdtls", $retrieve_reportmm); 
        $this->viewBuilder()->setLayout('user');
    }
	    
	public function status()
    {   
        $uid = $this->request->data('val') ;
        $sts = $this->request->data('sts') ;
        $reportmm_list = TableRegistry::get('reportmax_marks');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sid = $reportmm_list->find()->where(['id'=> $uid ])->first();
        
		$status = $sts == 1 ? 0 : 1;
        if(!empty($sid))
        {   
            
           $all_period = $reportmm_list->find()->where(['school_id' => $sid->school_id, 'session_id'=> $sid->session_id,'class_id'=> $sid->class_id, 'subject_id'=> $sid->subject_id, 'teacher_id'=> $sid->teacher_id])->toArray() ; 
           foreach($all_period as $period){
                $stats = $reportmm_list->query()->update()->set([ 'request_status' => $status])->where([ 'id' => $period->id  ])->execute();
           
                if($stats)
                {
                    $activity = $activ_table->newEntity();
                    $activity->action =  "Edit request status changed"  ;
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
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }

        return $this->json($res);
    }

}

  


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
class StudentdairyController extends AppController
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
        $stid = $this->Cookie->read('stid'); 
        $sessionid = $this->Cookie->read('sessionid'); 
        $compid = $this->request->session()->read('company_id');
        $studid = $this->request->session()->read('student_id');
        $class_id = $this->request->session()->read('class_id');
        
        $class_table = TableRegistry::get('class');
        $classsub_table = TableRegistry::get('class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $student_table = TableRegistry::get('student');
        $studentdairy_table = TableRegistry::get('student_class_dairy');
        if(!empty($stid)) 
        {
    	    $retrieve_student = $student_table->find()->where(['md5(id)' => $stid ])->first() ;
    	    $class = $retrieve_student['class'];
    		$retrieve_class = $classsub_table->find()->where(['class_id' => $class ])->first() ;
    	    $subid = explode(",", $retrieve_class['subject_id']);
    	    $retrieve_subject = $subjects_table->find()->where(['id IN' => $subid ])->toArray() ;
    	    
    	    $dairydate = '';
    		$get_dairy = '';
    		if(!empty($_POST))
    		{
    		    $dairydate = $this->request->data('enddate'); 
    		    $get_dairy = $studentdairy_table->find()->where(['date' => $dairydate, 'class_id' => $class_id, 'student_id' => $studid ])->toArray() ;
    		    foreach($get_dairy as $gd)
    		    {
    		        $subid = $gd['subject_id'];
    		        $retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		        $gd->subject_name = $retrieve_subj['subject_name'];
    		    }
    		    
    		    $dairycount = count($get_dairy);
    		}
    		else
    		{
    		    $dairydate = date("d-m-Y", time());
    		    $get_dairy = $studentdairy_table->find()->where(['date' => $dairydate, 'class_id' => $class_id, 'student_id' => $studid ])->toArray() ;
    		    foreach($get_dairy as $gd)
    		    {
    		        $subid = $gd['subject_id'];
    		        $retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		        $gd->subject_name = $retrieve_subj['subject_name'];
    		    }
    		    $dairycount = count($get_dairy);
    		}
    		
    		$this->set("subjectdtl", $retrieve_subject);
            $this->set("clsid", $class);
    		$this->set("dairydate", $dairydate);
    		$this->set("dairycount", $dairycount);
    		$this->set("dairydtl", $get_dairy);
            $this->viewBuilder()->setLayout('user');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function addstudentdairy()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $stid = $this->Cookie->read('stid'); 
            $sessionid = $this->Cookie->read('sessionid'); 
            
            $compid = $this->request->session()->read('company_id');
            $studid = $this->request->session()->read('student_id');
            $class_id = $this->request->session()->read('class_id');
            
            $studentdairy_table = TableRegistry::get('student_class_dairy');
            $activ_table = TableRegistry::get('activity');
            
            $getdairy_note = $studentdairy_table->find()->where(['date' => $this->request->data('datedairy'), 'note' => 'dairy_note', 'school_id' => $compid, 'class_id' => $class_id, 'student_id' => $studid ])->first() ;
            if(empty($getdairy_note))
            {
                $sdn = $studentdairy_table->newEntity();
                $sdn->date = $this->request->data('datedairy');
                $sdn->str_date = strtotime($this->request->data('datedairy'));
                $sdn->note = $this->request->data('dairy_note');
                $sdn->subject_content = $this->request->data('note');
                $sdn->class_id = $this->request->data('clsid');
        		$sdn->student_id = $studid;
                $sdn->school_id = $compid;
                //$sdn->added_by = "student";
                $sdn->created_date = time();
                                      
                $saved = $studentdairy_table->save($sdn);
            }
            /*else
            {
                $update = $studentdairy_table->query()->update()->set(['note' => $this->request->data('dairy_note') , 'subject_content' => $this->request->data('note')])->where([ 'id' => $getdairy_note['id']  ])->execute();
            }*/
            
            $subids = $this->request->data['subject_id'];
            foreach($subids as $key => $sids)
            {
                //print_r($sids); die;
                $sub_content = $this->request->data['subject_content'][$key];
                $getdairy_content = $studentdairy_table->find()->where(['date' => $this->request->data('datedairy'), 'subject_id' => $sids, 'school_id' => $compid, 'class_id' => $class_id, 'student_id' => $studid ])->first() ;
                if(empty($getdairy_content))
                {
                    $sd = $studentdairy_table->newEntity();
                    $sd->date = $this->request->data('datedairy');
                    $sd->str_date = strtotime($this->request->data('datedairy'));
                    $sd->subject_id = $sids;
                    $sd->subject_content = $this->request->data['subject_content'][$key];
                    $sd->class_id = $this->request->data('clsid');
            		$sd->student_id = $studid;
                    $sd->school_id = $compid;
                    //$sd->added_by = "student";
                    $sd->created_date = time();
                                          
                    if($saved = $studentdairy_table->save($sd) )
                    {   
                        $sdid = $saved->id;
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Student dairy Added"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($sdid); 
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
                }
                else
                {
                    $update = $studentdairy_table->query()->update()->set([ 'subject_content' => $sub_content])->where([ 'id' => $getdairy_content['id']  ])->execute();
                    if($update)
                    {   
                        $sdid = $getdairy_content['id'];
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Student dairy updated"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                        $activity->origin = $this->Cookie->read('id');
                        $activity->value = md5($getdairy_content['id']); 
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
                    
                }
            }
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
}

  


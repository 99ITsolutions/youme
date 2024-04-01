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
 * @link      https://cakephp.org CakePHP(tm) Projectr
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
class StudentdairyreportController  extends AppController
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
        $classteacher_table =  TableRegistry::get('classteacher');
        $studcls_table =  TableRegistry::get('student_class_dairy');
        $stud_table =  TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        //$tid = $this->Cookie->read('tid');
        $sessionid = $this->Cookie->read('sessionid');
        $compid = $this->request->session()->read('company_id');
        if(!empty($compid))
        {
			$retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1 ])->toArray();
            
            $studids = '';
            $clsids ='';
            $date = '';
            if(!empty($_POST))
            {
                
                $studids = explode("~^", $this->request->data['studentsel'][0]);
                if($studids[1] == "All")
                {
                    $stdids = explode(",", $studids[0]);
                }
                else 
                {
                    $stdids = $this->request->data['studentsel'];
                }
                $clsids = $this->request->data('class_sel');
                $date = $this->request->data('enddate');
                $reportdiary = [];
                $studentid = $stdids;
                foreach($stdids as $stuid) {
                    $getreport = $stud_table->find()->select(['student.f_name', 'student.id', 'student.adm_no', 'student.l_name', 'student.email', 'parent_logindetails.parent_email'])->join([
                        'parent_logindetails' => 
                        [
                            'table' => 'parent_logindetails',
                            'type' => 'LEFT',
                            'conditions' => 'parent_logindetails.id = student.parent_id        '
                        ]
                    ])->where(['student.id' => $stuid])->first();
                       
                    
                    $getreportd = $studcls_table->find()->where(['student_class_dairy.class_id' => $clsids,  'student_class_dairy.date' => $date, 'student_class_dairy.student_id' => $getreport['id'], 'student_class_dairy.note' => 'dairy_note' ])->first();
                    if(!empty($getreportd))
                    {
                        $getreport['parent_signature'] = $getreportd['parent_signature'];
                        $getreport['parent_id'] = $getreportd['parent_id'];
                        $getreport['diaryid'] = $getreportd['id'];
                    }
                    else
                    {
                        $getreport['parent_signature'] = '';
                        $getreport['parent_id'] = '';
                        $getreport['diaryid'] = '';
                    }
                    
                    $reportdiary[] = $getreport;
                }
            }
            
            $this->set("reportdiaries", $reportdiary);    
            $this->set("studentid", $studentid);     
            $this->set("clsids", $clsids);   
            $this->set("diarydate", $date);
            $this->set("classes_details", $retrieve_class);     
            $this->set("sessionid", $sessionid);     
            $this->viewBuilder()->setLayout('user'); 
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }

    public function getstudent()
    {
   	    $student_list = TableRegistry::get('student');
        $class_list = TableRegistry::get('class');
		$classsub = $this->request->data('classId');
		$sessionid = $this->Cookie->read('sessionid');
        $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
		$retrieve_stdnt = $student_list->find()->select([ 'id', 'f_name', 'l_name', 'adm_no', 'email'])->where(['class' => $classsub, 'session_id' => $sessionid, 'status' => 1 ])->toArray();
		$stu = '';
		foreach($retrieve_stdnt as $stud)
		{
		    $stuids[] = $stud['id'];
		}
		$idstus = implode(",", $stuids)."~^All";
		$stu .= '<option value="'.$idstus.'">All</option>';
		foreach($retrieve_stdnt as $stud)
		{
		    $stu .= '<option value="'.$stud['id'].'">'.$stud['l_name']." ". $stud['f_name'].' ('. $stud['email'].') </option>';
		}
		
		return $this->json($stu);
    }
    
    public function createdairy()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $classteacher_table =  TableRegistry::get('classteacher');
        $studcls_table =  TableRegistry::get('student_class_dairy');
        $stud_table =  TableRegistry::get('student');
        $class_table = TableRegistry::get('class');
        $classsub_table = TableRegistry::get('class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $tid = $this->Cookie->read('id');
        $sessionid = $this->Cookie->read('sessionid');
        $compid = $this->request->session()->read('company_id'); 
        if(!empty($compid))
        {
			$retrieve_class = $class_table->find()->where(['school_id' => $compid, 'active' => 1  ])->toArray();
            
            $stuid = '';
            $clsid ='';
            $date = '';
            $clsid1 ='';
            $date1 = '';
            $retrieve_subject = '';
            $note = '';
            $getdairy_note2 = '';
            if(!empty($_POST))
            {
                $sf = $this->request->data('searchfilter');
                if($sf == 1)
                {
                    $clasid = explode("all_", $this->request->data['class_sel'][0]);
                    //print_r($clasid);
                    if($clasid[0] == "")
                    {
                        $clsid = explode(",", $clasid[1]);
                    }
                    else
                    {
                        $clsid = $this->request->data['class_sel'];
                    }
                   // print_r($clsid);
                    //echo count($clsid); die;
                    $date = $this->request->data('enddate');
                    $getdairy_note = $studcls_table->find()->where(['date' => $date, 'note' => 'dairy_note', 'school_id' => $compid, 'class_id IN' => $clsid ])->first() ;
                    $note = $getdairy_note['subject_content'];
                    
                    $dairycount = count($getdairy_note);
                }
                /*if($sf == 2)
                {
                    $clsid1 = $this->request->data('class_sel');
                    $date1 = $this->request->data('enddate');
                    //$stuid = $this->request->data['studentsel'];
                    $studids = explode("~^", $this->request->data['studentsel'][0]);
                    if($studids[1] == "All")
                    {
                        $stuid = explode(",", $studids[0]);
                    }
                    else 
                    {
                        $stuid = $this->request->data['studentsel'];
                    }
                    foreach($stuid as $sid) {
                        
                        $getstudent = $stud_table->find()->where(['id' => $sid])->first();
                        $studlist[] = $getstudent;
                    }
                    //print_r($stuid[0]);
            		$retrieve_classes = $classsub_table->find()->where(['class_id' => $clsid1 ])->first() ;
            	    $subid = explode(",", $retrieve_classes['subject_id']);
            	    $retrieve_subject = $subjects_table->find()->where(['id IN' => $subid ])->toArray() ;
            	    
            	    $getdairy_note2 = $studcls_table->find()->where(['date' => $date1, 'school_id' => $compid, 'class_id' => $clsid1, 'student_id' => $stuid[0], 'added_by' => 'teacher' ])->toArray() ;
                    foreach($getdairy_note2 as $gd)
        		    {
        		        $subid = $gd['subject_id'];
        		        $retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first() ;
        		        $gd->subject_name = $retrieve_subj['subject_name'];
        		    }
        		    $dairycount = count($getdairy_note2);
                    //print_r($getdairy_note2); die;
                }*/
                
            }
            
            $this->set("sf", $sf); 
            $this->set("dairycount", $dairycount); 
            $this->set("note", $note);
            $this->set("subjectdtl", $retrieve_subject); 
            $this->set("dairydtl", $getdairy_note2); 
            $this->set("studlist", $studlist); 
            $this->set("clsid1", $clsid1); 
            $this->set("diarydate1", $date1);
            $this->set("stuid", $stuid); 
            $this->set("clsid", $this->request->data['class_sel']); 
            $this->set("diarydate", $date);
            $this->set("classes_details", $retrieve_class);     
            $this->set("sessionid", $sessionid);     
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
            $tid = $this->Cookie->read('tid'); 
            $sessionid = $this->Cookie->read('sessionid'); 
            
            $compid = $this->request->session()->read('company_id');
            $tchrid = $this->request->session()->read('tchr_id');
            
            //print_r($_POST); die;
            $studentdairy_table = TableRegistry::get('student_class_dairy');
            $activ_table = TableRegistry::get('activity');
            $class_table = TableRegistry::get('class');
            $student_table = TableRegistry::get('student');
            
            $sf = $this->request->data('sfilter');
            if($sf == 1)
            {
                $ddate = $this->request->data('date');
                $cid = explode(",", $this->request->data('classid'));
                
                foreach($cid as $cls)
                {
                    $getstudents = $student_table->find()->select(['id'])->where(['class' => $cls, 'school_id' => $compid, 'session_id' => $sessionid, 'status' => 1 ])->toArray() ;
                    if(!empty($getstudents))
                    {
                        foreach($getstudents as $stu)
                        {
                            $getdairy_note = $studentdairy_table->find()->where(['date' => $ddate, 'note' => 'dairy_note', 'school_id' => $compid, 'class_id' => $cls, 'student_id' => $stu['id'] ])->first() ;
                            if(empty($getdairy_note))
                            {
                                $sdn = $studentdairy_table->newEntity();
                                $sdn->date = $ddate;
                                $sdn->str_date = strtotime($ddate);
                                $sdn->note = 'dairy_note';
                                $sdn->subject_content = $this->request->data('note');
                                $sdn->class_id = $cls;
                        		$sdn->student_id = $stu['id'];
                                $sdn->school_id = $compid;
                                $sdn->added_by = "school";
                                $sdn->created_date = time();
                                $savedup = $studentdairy_table->save($sdn);
                            }
                            else
                            {
                                $savedup = $studentdairy_table->query()->update()->set(['note' => 'dairy_note' , 'subject_content' => $this->request->data('note')])->where([ 'id' => $getdairy_note['id']  ])->execute();
                            }
                        }
                    }
                }
            }
            
            /*if($sf == 2)
            {
                //print_r($_POST); die;
                $ddate = $this->request->data('date');
                $class_id = $this->request->data('classid');
                $stid = explode(",", $this->request->data('studentid'));
                foreach($stid as $studid)
                {
                    $getdairy_note = $studentdairy_table->find()->where(['date' => $ddate, 'note' => 'dairy_note', 'school_id' => $compid, 'class_id' => $class_id, 'student_id' => $studid ])->first() ;
                    if(empty($getdairy_note))
                    {
                        $sdn = $studentdairy_table->newEntity();
                        $sdn->date = $ddate;
                        $sdn->str_date = strtotime($ddate);
                        $sdn->note = $this->request->data('dairy_note');
                        $sdn->subject_content = $this->request->data('note');
                        $sdn->class_id = $class_id;
                		$sdn->student_id = $studid;
                        $sdn->school_id = $compid;
                        $sdn->added_by = "teacher";
                        $sdn->created_date = time();
                                              
                        $saved = $studentdairy_table->save($sdn);
                    }
                    else
                    {
                        $update = $studentdairy_table->query()->update()->set(['note' => $this->request->data('dairy_note') , 'subject_content' => $this->request->data('note')])->where([ 'id' => $getdairy_note['id']  ])->execute();
                    }
                    
                    $subids = $this->request->data['subject_id'];
                    foreach($subids as $key => $sids)
                    {
                        $sub_content = $this->request->data['tsubject_content'][$key];
                        $getdairy_content = $studentdairy_table->find()->where([ 'date' => $ddate, 'subject_id' => $sids, 'school_id' => $compid, 'class_id' => $class_id, 'student_id' => $studid ])->first() ;
                        if(empty($getdairy_content))
                        {
                            $sd = $studentdairy_table->newEntity();
                            $sd->date = $ddate;
                            $sd->str_date = strtotime($ddate);
                            $sd->subject_id = $sids;
                            $sd->tsubject_content = $this->request->data['tsubject_content'][$key];
                            $sd->class_id = $class_id;
                    		$sd->student_id = $studid;
                            $sd->school_id = $compid;
                            //$sd->added_by = "teacher";
                            $sd->created_date = time();
                                                  
                            $savedup = $studentdairy_table->save($sd);
                        }
                        else
                        {
                            $savedup = $studentdairy_table->query()->update()->set([ 'tsubject_content' => $sub_content])->where([ 'id' => $getdairy_content['id']  ])->execute();
                        }
                    }
                }
            }*/
            
            if($savedup)
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
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }

}

  


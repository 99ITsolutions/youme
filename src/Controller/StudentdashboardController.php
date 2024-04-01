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
class StudentdashboardController  extends AppController
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
        //print_r($_SESSION); die;
        $stid = $this->Cookie->read('stid'); 
        $school_table = TableRegistry::get('company');
        $codeagree_table = TableRegistry::get('code_agreement');
        $codeconduct_table = TableRegistry::get('code_conduct');
        $report_table = TableRegistry::get('reportcard');
		$student_table = TableRegistry::get('student');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		
		$clsid = $this->request->session()->read('class_id');
		$studid = $this->request->session()->read('student_id');
		$sclid = $this->request->session()->read('company_id');
		$codeconduct = $codeconduct_table->find()->where(['school_id' => $sclid])->first();
		if(!empty($codeconduct)) {
		$codeagree = $codeagree_table->find()->where([ 'student_id'=> $studid, 'school_id' => $sclid, 'code_id' => $codeconduct['id'] ])->first();
		//print_r($codeconduct); die;
		if(empty($codeagree))
		{
		    $ca = $codeagree_table->newEntity();
            $ca->student_id =  $studid;
            $ca->school_id =  $sclid;
            $ca->code_id = $codeconduct['id']   ;
            $ca->status = 0 ;
            $ca->read_date = time();
            $saved = $codeagree_table->save($ca);
		}
		
		$codeagree = $codeagree_table->find()->where([ 'student_id'=> $studid, 'school_id' => $sclid, 'code_id' => $codeconduct['id'] ])->first();
		}
		else
		{
		    $codeagree = [];
		}
		$this->set("codeconduct", $codeconduct);
		$this->set("codeagree", $codeagree);
		
		$report_data = $report_table->find()->where([ 'md5(stuid)'=> $stid, 'classids' => $clsid ])->first();
		if(empty($report_data))
		{
		    $publish = 0;
		    $stusts = 0;
		}
		else
		{
		    if($report_data['status'] == "2" && $report_data['publish'] == "1")
		    {
		        
		        $publish_date =date('Y-m-d', $report_data['publish_date']);
                $NewDate = strtotime($publish_date . " +7 days");
                $now = time();
                
                if($NewDate >= $now )
                {
                    $publish = 1;
                    if($report_data['student_status'] == "1") {
    		            $stusts = 1;
    		        }
    		        else {
    		            $stusts = 0;
    		        }
                }
                else
    		    {
    		        $publish = 0;
    		        $stusts = 0;
    		    }
		    }
		    else
		    {
		        $publish = 0;
		        $stusts = 0;
		    }
		}
		$retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
				[
				'table' => 'class',
				'type' => 'LEFT',
				'conditions' => 'class.id = student.class'
			]
		])->where(['md5(student.id)' => $stid ])->toArray() ;
		
	    if(!empty($retrieve_students))
	    {
			$this->set("students_details", $retrieve_students); 
			$this->set("publish", $publish); 
			$this->set("stusts", $stusts); 
			$this->viewBuilder()->setLayout('user');
	    }
	    else
	    {
	        return $this->redirect('/login/') ;
	    }
    }
    
    public function myschool()
    {
        $this->viewBuilder()->setLayout('user');
    }
            
    public function studentprofile()
    {   
        $stid = $this->Cookie->read('stid'); 
        $student_table = TableRegistry::get('student');
        $sid =$this->request->session()->read('student_id');
		$session_id = $this->Cookie->read('sessionid');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($stid))
        {
            $retrieve_student_table = $student_table->find()->select(['student.id' , 'student.status', 'student.adm_no' , 'student.l_name', 'student.f_name','student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section'])->join(['class' => 
				[
					'table' => 'class',
					'type' => 'LEFT',
					'conditions' => 'class.id = student.class'
				]
			])->where(['md5(student.id)' => $stid ])->toArray() ;
			
            
            $this->set("studentprofile_details", $retrieve_student_table);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }
	
	public function changesession()
	{
        if ($this->request->is('ajax') && $this->request->is('post') ){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $activ_table = TableRegistry::get('activity');    
            $session_id = $this->Cookie->read('sessionid');

            if(!empty($this->request->data('currntsesssion')))
            {   
                $newsessionid = $this->request->data('currntsesssion');    
                $this->Cookie->write('sessionid',  $newsessionid);
	
                if($newsessionid)
                {
                      
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Cookie Updated"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
    
                        $activity->value = $newsessionid   ;
                        $activity->origin = md5($this->Cookie->read('id')) ;
                        $activity->created = strtotime('now');
                        if($saved = $activ_table->save($activity) ){
                            $res = [ 'result' => 'success'  ];
                        }
                        else{
                            $res = [ 'result' => 'activity'  ];
                        }

                }
                else{
                    $res = [ 'result' => 'error'  ];
                }
            } 
            else{
                $res = [ 'result' => 'empty'  ];
            }

        }
        else{
            $res = [ 'result' => 'invalid operation'  ];

        }

        return $this->json($res);
    } 	
            
    public function getsidebar()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $stid = $this->Cookie->read('stid'); 
            $student_table = TableRegistry::get('student');
            $activ_table = TableRegistry::get('activity');
            $studsidebar_table = TableRegistry::get('student_sidebarmenu');
            if(!empty($stid))
            {
                $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $stid ])->first();
                
                $sid = $stuid_retrieve['id'];
        		$getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
    
                if($getcount == 0)
                {
                    $sidebarstu = $studsidebar_table->newEntity();
                    
                    $sidebarstu->status = 1;
                    $sidebarstu->student_id = $sid;
                    $sidebarstu->created_date = time();
                    if($saved = $studsidebar_table->save($sidebarstu) )
                    {     
                        $stusidebarid = $saved->id;
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'error occured.'  ];
                    }
                }
                else
                {
                    $update = $studsidebar_table->query()->update()->set([ 'status'=> '1' , 'created_date' => time() ])->where([ 'student_id' => $sid  ])->execute();
                    if($update)
                    {     
                        $res = [ 'result' => 'success'  ];
                    }
                    else
                    {
                        $res = [ 'result' => 'error occured.'  ];
                    }
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
    
    public function updateagreement()
    {
       // print_r($_POST); die;
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $id = $this->request->data('id'); 
        $sts = $this->request->data('agreement'); 
        $stid = $this->Cookie->read('stid'); 
        $codeagree_table = TableRegistry::get('code_agreement');
        $activ_table = TableRegistry::get('activity');
        if(!empty($stid))
        {
            
            $update = $codeagree_table->query()->update()->set([ 'status'=> $sts , 'read_date' => time() ])->where([ 'id' => $id  ])->execute();
            if($update)
            {     
                $res = [ 'result' => "success"  ];
            }
            else
            {
                $res = [ 'result' => 'error occured.'  ];
            }
        }
        else
        {
             return $this->redirect('/login/') ;
        }
        return $this->json($res);
    }
    
    public function sidebarmenu()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $stid = $this->Cookie->read('stid'); 
        $student_table = TableRegistry::get('student');
        $studsidebar_table = TableRegistry::get('student_sidebarmenu');
        if(!empty($stid))
        {
            $stuid_retrieve = $student_table->find()->select(['id'])->where(['md5(id)'=> $stid ])->first();
            
            $sid = $stuid_retrieve['id'];
    		$getcount = $studsidebar_table->find()->where(['student_id'=> $sid ])->count();
    
            if($getcount == 0)
            {
                $getside = $studsidebar_table->find()->where(['student_id'=> $sid ])->first();
                if($getside['status'] = 1)
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
               $res = [ 'result' => 'failed'  ];
            }
            return $this->json($res);
        }
        else
        {
             return $this->redirect('/login/') ;
        }
    }

    public function returnreport()
    {
        $clsid = $this->request->query('classid'); 
        $studid = $this->request->query('studentid'); 
        
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        if(!empty($studid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid');                
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $clss = $retrieve_class['c_name']."-".$retrieve_class['school_sections'];
            
            $update = $reportcard->query()->update()->set([ 'student_status'=> '1'])->where([ 'stuid' => $studid, 'classids' => $clsid ])->execute();
            
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();  
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['l_name'];
            $this->set("stydent_name", $stydent_name);  
            $this->set("city", $city);  
                   
            if($clss == "8ème-Cycle Terminal de l'Education de Base (CTEB)")
            {
                return $this->redirect('/Studentdashboard/editreport?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "5ème-Primaire")
            {
                return $this->redirect('/Studentdashboard/fifthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "7ème-Cycle Terminal de l'Education de Base (CTEB)")
            {
                return $this->redirect('/Studentdashboard/seventhclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème-Primaire")
            {
                return $this->redirect('/Studentdashboard/fourthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème-Primaire")
            {
                return $this->redirect('/Studentdashboard/secondclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème-Maternelle")
            {
                return $this->redirect('/Studentdashboard/threeema?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Maternelle")
            {
                return $this->redirect('/Studentdashboard/firsterec?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Studentdashboard/firstereannee?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanités Scientifiques")
            {
                return $this->redirect('/Studentdashboard/twoerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Primaire")
            {
                return $this->redirect('/Studentdashboard/firstclasspremire?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème-Maternelle")
            {
                return $this->redirect('/Studentdashboard/twoemematernel?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème-Primaire")
            {
                return $this->redirect('/Studentdashboard/thiredclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "6ème-Primaire")
            {
                return $this->redirect('/Studentdashboard/sixthclass?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Studentdashboard/threeemezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Studentdashboard/twomezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Commerciale & Gestion")
            {
                return $this->redirect('/Studentdashboard/fouremezestion?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère-Creche")
            {
                return $this->redirect('/Studentdashboard/oneèrecreche?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanités Scientifiques")
            {
                return $this->redirect('/Studentdashboard/oneerehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Littéraire")
            {
                //echo "DScsd"; die;
                return $this->redirect('/Studentdashboard/fouremeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Littéraire")
            {
                return $this->redirect('/Studentdashboard/threeemeanneHumanitelitere?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Littéraire")
            {
                return $this->redirect('/Studentdashboard/firsterehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Littéraire")
            {
                return $this->redirect('/Studentdashboard/twoemeanneehumanitieliter?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "1ère Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Studentdashboard/firstpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "2ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Studentdashboard/secondpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Studentdashboard/thiredpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Pedagogie générale")
            {
                return $this->redirect('/Studentdashboard/fourthpedagogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Chimie - Biologie")
            {
                 return $this->redirect('/Studentdashboard/chime?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanité Math - Physique")
            {
                return $this->redirect('/Studentdashboard/threehumanitiemath?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Math - Physique")
            {
                return $this->redirect('/Studentdashboard/fourememath?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanité Chimie - Biologie")
            {
                 return $this->redirect('/Studentdashboard/fouremebilogie?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "3ème Année-Humanités Scientifiques")
            {
                 return $this->redirect('/Studentdashboard/threeanneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
            elseif($clss == "4ème Année-Humanités Scientifiques")
            {
                 return $this->redirect('/Studentdashboard/fouranneehumanitiescientifices?classid='.$classsub.'&studentid='.$studentsub);
            }
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function fouremeanneHumanitelitere()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        $pid = $this->Cookie->read('pid');
        if(!empty($tid) || !empty($pid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            $class_list = TableRegistry::get('class');
            $classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
			$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
            $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
            $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
            
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
            $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
            $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            $this->set("nine_period", $nine_period);
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
            $this->set("twelve_period", $twelve_period);
            $this->set("threteen_period", $threteen_period);
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }     
    }
    
    public function editreport()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
             $report_list = TableRegistry::get('reportcard');   
             
             $retrieve_data = $report_list->find()->where(['stuid' => $stuid])->first();
           
            // temp
            
             $class_list = TableRegistry::get('class');
			  $classsub = $this->request->query('classid'); 
             $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class']; 
            // dd($retrieve_studentinfo);
            // temp
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $retrieve_1st = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE' ])->toArray();
            $retrieve_2nd = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE' ])->toArray();
            // dd($retrieve_1st);
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("retrieve_data", $retrieve_data);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class); 
            
            // prem_1
             $first_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
         $this->set("first_gemotry", $first_gemotry); 
         $this->set("second_gemotry", $second_gemotry); 


             $first_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
         $this->set("first_algebra", $first_algebra); 
         $this->set("second_algebra", $second_algebra); 


        $first_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
         $this->set("first_ARITHMÉTIQUE", $first_ARITHMÉTIQUE); 
         $this->set("second_ARITHMÉTIQUE", $second_ARITHMÉTIQUE); 
            
            
            
        $first_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
         $this->set("first_STATISTIQUE", $first_STATISTIQUE); 
         $this->set("second_STATISTIQUE", $second_STATISTIQUE); 
         
             
        $first_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
         $this->set("first_Anatomie", $first_Anatomie); 
         $this->set("second_Anatomie", $second_Anatomie); 
            
               
        $first_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Botanique", $first_Botanique); 
         $this->set("second_Botanique", $second_Botanique);
            
            $first_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Zoologie", $first_Zoologie); 
         $this->set("second_Zoologie", $second_Zoologie);
         
            $first_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_sp", $first_sp); 
         $this->set("second_sp", $second_sp);
            
            
            
            $first_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Technologie", $first_Technologie); 
         $this->set("second_Technologie", $second_Technologie);
            
            
            $first_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();

         $this->set("first_TECHN", $first_TECHN); 
         $this->set("second_TECHN", $second_TECHN);
            
            
             $first_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Anglais", $first_Anglais); 
         $this->set("second_Anglais", $second_Anglais);
            
            
             $first_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Français", $first_Français); 
         $this->set("second_Français", $second_Français);
         
         $first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Religion", $first_Religion); 
         $this->set("second_Religion", $second_Religion);
         
         
         
             $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_edu", $first_edu); 
         $this->set("second_edu", $second_edu);
         
         
          $first_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_moral", $first_moral); 
         $this->set("second_moral", $second_moral);
         
         $first_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Géographie", $first_Géographie); 
         $this->set("second_Géographie", $second_Géographie);
         
         
         $first_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
         
         $this->set("first_Histoire", $first_Histoire); 
         $this->set("second_Histoire", $second_Histoire);
         
         
            
         $first_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
             $second_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
        //  dd($first_ep);
         $this->set("first_ep", $first_ep); 
         $this->set("second_ep", $second_ep);
         
         
         $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();
    
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Statistique'])->toArray();
          
           $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
      $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();
          
          $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
           $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();
           
           $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
            $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
          
                $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
           $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
             $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
           $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
          
        //   done   
      
      
       
           $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
           $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
           $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
           $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
           $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
            $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
            $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
           $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
           
            // dd($sixth_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
             $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
              $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
                 
            $this->set("twelve_period", $twelve_period);
                 
            $this->set("threteen_period", $threteen_period);
                 
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            $this->set("sixteen_period", $sixteen_period);
            $this->set("seventeen_period", $seventeen_period);
            $this->set("eightteen_period", $eightteen_period);
            $this->set("nineteen_period", $nineteen_period);
            $this->set("twenty_period", $twenty_period);
            $this->set("twentyone_period", $twentyone_period);
            $this->set("twentytwo_period", $twentytwo_period);
            
            $this->set("twentythree_period", $twentythree_period);
            
            $this->set("twentyfour_period", $twentyfour_period);
            $this->set("twentyfive_period", $twentyfive_period);
            $this->set("twentysix_period", $twentysix_period);
           
            
            
            $this->set("retrieve_1st", $retrieve_1st); 
            $this->set("retrieve_2nd", $retrieve_2nd); 
            
            
            $this->set("retrieve_record", $retrieve_record); 
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }

    public function fifthclass()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
          
            
            // dd($studentsub);
            // temp
            
            $class_list = TableRegistry::get('class');
			$classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
			$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            //  dd($retrieve_studentinfo);
            // temp
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            
            
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocabulaire'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orthographe'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédaction'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langue Congolaises'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langue Francaise'])->toArray();
$ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
$eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
$twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
$threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
$forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
$fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->toArray();
$sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
$seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
$eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
$nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
$twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
$twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
$twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
$twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
$twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
$twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
$twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
           
            // dd($sixth_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
             $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
              $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
                 
            $this->set("twelve_period", $twelve_period);
                 
            $this->set("threteen_period", $threteen_period);
                 
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            $this->set("sixteen_period", $sixteen_period);
            $this->set("seventeen_period", $seventeen_period);
            $this->set("eightteen_period", $eightteen_period);
            $this->set("nineteen_period", $nineteen_period);
            $this->set("twenty_period", $twenty_period);
            $this->set("twentyone_period", $twentyone_period);
            $this->set("twentytwo_period", $twentytwo_period);
            
            $this->set("twentythree_period", $twentythree_period);
            
            $this->set("twentyfour_period", $twentyfour_period);
            $this->set("twentyfive_period", $twentyfive_period);
            $this->set("twentysix_period", $twentysix_period);
           
         
         
            
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function firstclass()
    {
         $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            
            // temp
            
             $class_list = TableRegistry::get('class');
			  $classsub = $this->request->query('classid'); 
             $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            // dd($stydent_name);
            // temp
                   $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
            
    public function secondaryChild()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            
            // temp
            
             $class_list = TableRegistry::get('class');
			  $classsub = $this->request->query('classid'); 
             $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            // dd($stydent_name);
            // temp
                   $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function seventhclass ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            $report_list = TableRegistry::get('reportcard');   
             
             $retrieve_data = $report_list->find()->where(['stuid' => $stuid])->first();
           
            // temp
            
            $class_list = TableRegistry::get('class');
            $classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class']; 
            
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $retrieve_1st = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE' ])->toArray();
            $retrieve_2nd = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE' ])->toArray();
            // dd($retrieve_1st);
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("retrieve_data", $retrieve_data);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class); 
            
            // prem_1
$first_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_gemotry = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géométrie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$this->set("first_gemotry", $first_gemotry); 
$this->set("second_gemotry", $second_gemotry); 


$first_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_algebra = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Algèbre','semester_name'=>'PREMIER SEMESTRE' ])->first();
$this->set("first_algebra", $first_algebra); 
$this->set("second_algebra", $second_algebra); 


$first_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_ARITHMÉTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Arithmétique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$this->set("first_ARITHMÉTIQUE", $first_ARITHMÉTIQUE); 
$this->set("second_ARITHMÉTIQUE", $second_ARITHMÉTIQUE); 



$first_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_STATISTIQUE = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Statistique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$this->set("first_STATISTIQUE", $first_STATISTIQUE); 
$this->set("second_STATISTIQUE", $second_STATISTIQUE); 


$first_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Anatomie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anatomie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$this->set("first_Anatomie", $first_Anatomie); 
$this->set("second_Anatomie", $second_Anatomie); 
            
               
$first_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Botanique = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Botanique','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Botanique", $first_Botanique); 
$this->set("second_Botanique", $second_Botanique);

$first_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Zoologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Zoologie','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Zoologie", $first_Zoologie); 
$this->set("second_Zoologie", $second_Zoologie);

$first_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_sp = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Sciences Physiques','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_sp", $first_sp); 
$this->set("second_sp", $second_sp);



$first_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Technologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Technologie','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Technologie", $first_Technologie); 
$this->set("second_Technologie", $second_Technologie);


$first_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_TECHN = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>"Techn. d'Info. & Com. (TIC)",'semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_TECHN", $first_TECHN); 
$this->set("second_TECHN", $second_TECHN);


$first_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Anglais = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Anglais','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Anglais", $first_Anglais); 
$this->set("second_Anglais", $second_Anglais);


$first_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Français = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Français','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Français", $first_Français); 
$this->set("second_Français", $second_Français);

$first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Religion", $first_Religion); 
$this->set("second_Religion", $second_Religion);



$first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education à la vie (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_edu", $first_edu); 
$this->set("second_edu", $second_edu);


$first_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_moral = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Civique et Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_moral", $first_moral); 
$this->set("second_moral", $second_moral);

$first_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Géographie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Géographie','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Géographie", $first_Géographie); 
$this->set("second_Géographie", $second_Géographie);


$first_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_Histoire = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Histoire','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_Histoire", $first_Histoire); 
$this->set("second_Histoire", $second_Histoire);



$first_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();
$second_ep = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Education Physique','semester_name'=>'PREMIER SEMESTRE' ])->first();

$this->set("first_ep", $first_ep); 
$this->set("second_ep", $second_ep);


$first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
$second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
$third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre'])->toArray();

$fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arithmétique'])->toArray();

$fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géométrie'])->toArray();
$sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Statistique'])->toArray();

$sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie'])->toArray();
$eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Botanique'])->toArray();
$nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie'])->toArray();

$ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences Physiques'])->toArray();
$eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
$twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>"Techn. d'Info. & Com. (TIC)"])->toArray();

$threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
$forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();

$fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
$sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
$seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Civique et Morale'])->toArray();
$eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();

$nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
$twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
$twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
$twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
$twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
$twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
$twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
$twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();

// dd($sixth_period);

$this->set("first_period", $first_period); 
$this->set("second_period", $second_period);
$this->set("third_period", $third_period);
$this->set("fourth_period", $fourth_period);
$this->set("fifth_period", $fifth_period);
$this->set("sixth_period", $sixth_period);
$this->set("sevent_period", $sevent_period);
$this->set("eight_period", $eight_period);
$this->set("nine_period", $nine_period);
$this->set("ten_period", $ten_period);
$this->set("eleven_period", $eleven_period);
$this->set("twelve_period", $twelve_period);
$this->set("threteen_period", $threteen_period);
$this->set("forteen_period", $forteen_period);
$this->set("fifteen_period", $fifteen_period);
$this->set("sixteen_period", $sixteen_period);
$this->set("seventeen_period", $seventeen_period);
$this->set("eightteen_period", $eightteen_period);
$this->set("nineteen_period", $nineteen_period);
$this->set("twenty_period", $twenty_period);
$this->set("twentyone_period", $twentyone_period);
$this->set("twentytwo_period", $twentytwo_period);
$this->set("twentythree_period", $twentythree_period);
$this->set("twentyfour_period", $twentyfour_period);
$this->set("twentyfive_period", $twentyfive_period);
$this->set("twentysix_period", $twentysix_period);

            $this->set("retrieve_1st", $retrieve_1st); 
            $this->set("retrieve_2nd", $retrieve_2nd); 
            $this->set("retrieve_record", $retrieve_record); 
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
    
    public function fourthclass ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            
            // temp
            
            $class_list = TableRegistry::get('class');
            $classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
            $studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            //  dd($retrieve_studentinfo);
            // temp
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'GRAM. - CONJ. - ANALYSE'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->toArray();
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
            $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
            $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->toArray();
            $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
            $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
            $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
            $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
            $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
            $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sport'])->toArray();
            $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
            $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
//$twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
//$twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
//$twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
           
            // dd($third_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
             $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
              $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
                 
            $this->set("twelve_period", $twelve_period);
                 
            $this->set("threteen_period", $threteen_period);
                 
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            $this->set("sixteen_period", $sixteen_period);
            $this->set("seventeen_period", $seventeen_period);
            $this->set("eightteen_period", $eightteen_period);
            $this->set("nineteen_period", $nineteen_period);
            $this->set("twenty_period", $twenty_period);
            $this->set("twentyone_period", $twentyone_period);
            $this->set("twentytwo_period", $twentytwo_period);
            
            $this->set("twentythree_period", $twentythree_period);
            
            $this->set("twentyfour_period", $twentyfour_period);
            $this->set("twentyfive_period", $twentyfive_period);
            $this->set("twentysix_period", $twentysix_period);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
    
    public function secondclass ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            
            // temp
            
            $class_list = TableRegistry::get('class');
			$classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
			$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            //  dd($retrieve_studentinfo);
            // temp
                   $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            
    $first_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Religion','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Religion = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Religion','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Religion", $first_Religion); 
    $this->set("second_Religion", $second_Religion); 
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Ecrite'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Vocabulaire'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed civ & morale'])->toArray();
    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phys.- Zool.- Info'])->toArray();
    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie-Botanique'])->toArray();
    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
            $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Calligraphie'])->toArray();
    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
            $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
            $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
            $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
            $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
            $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();

            // dd($second_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
             $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
              $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
                 
            $this->set("twelve_period", $twelve_period);
                 
            $this->set("threteen_period", $threteen_period);
                 
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            $this->set("sixteen_period", $sixteen_period);
            $this->set("seventeen_period", $seventeen_period);
            $this->set("eightteen_period", $eightteen_period);
            $this->set("nineteen_period", $nineteen_period);
            $this->set("twenty_period", $twenty_period);
            $this->set("twentyone_period", $twentyone_period);
            $this->set("twentytwo_period", $twentytwo_period);
            
            $this->set("twentythree_period", $twentythree_period);
            
            $this->set("twentyfour_period", $twentyfour_period);
            $this->set("twentyfive_period", $twentyfive_period);
            $this->set("twentysix_period", $twentysix_period);
            
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }

    public function firsterec ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            $class_list = TableRegistry::get('class');
			$classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("city", $city);
            
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de langage'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
           
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
            $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
            $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            $this->set("nine_period", $nine_period);
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
            $this->set("twelve_period", $twelve_period);
            
            $this->set("gender", $gender);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        } 
    }
    
    public function threeema ()
    {
       $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            
            // temp
            
             $class_list = TableRegistry::get('class');
			  $classsub = $this->request->query('classid'); 
             $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
			$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            //  dd($retrieve_studentinfo);
            // temp
                   $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            
              $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
        //   dd($first_period);
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
            
            
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
            
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();

            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
            
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
           
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
           
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
           
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
           
           
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
           
           
           $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
          
          
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
            
            $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
         
            // dd($second_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
             $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
              $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
                 
            $this->set("twelve_period", $twelve_period);
                 
            $this->set("threteen_period", $threteen_period);
                 
            $this->set("forteen_period", $forteen_period);
           
            
            
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }
    }
    
    public function firstereannee ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            // temp
            
            $class_list = TableRegistry::get('class');
            $classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');//student table call kiya
			$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            $gender = $retrieve_studentinfo['gender'];
            $class = $retrieve_studentinfo['class'];
            
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Droit'])->toArray();
    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->toArray();
    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->toArray();
    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->toArray();
    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
$twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();

       
            $this->set("twentyone_period", $twentyone_period);
			$this->set("first_period", $first_period); 
			$this->set("second_period", $second_period);
			$this->set("third_period", $third_period);
			$this->set("fourth_period", $fourth_period);
			$this->set("fifth_period", $fifth_period);
			$this->set("sixth_period", $sixth_period);
			$this->set("sevent_period", $sevent_period);
			$this->set("eight_period", $eight_period);
			$this->set("nine_period", $nine_period);
			$this->set("ten_period", $ten_period);
			$this->set("eleven_period", $eleven_period);
			$this->set("twelve_period", $twelve_period);
			$this->set("threteen_period", $threteen_period);
			
			$this->set("forteen_period", $forteen_period);
			$this->set("fifteen_period", $fifteen_period);
			$this->set("sixteen_period", $sixteen_period);
			
			$this->set("seventeen_period", $seventeen_period);
			$this->set("eightteen_period", $eightteen_period);
			$this->set("nineteen_period", $nineteen_period);
			$this->set("twenty_period", $twenty_period);
            
            $this->set("gender", $gender);
            $this->set("class", $class);
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }   
    }
 
    public function firstclasspremire ()
    {
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $tid = $this->Cookie->read('stid');
        if(!empty($tid))
        {
            $student_list = TableRegistry::get('student');
            $class_list = TableRegistry::get('class');
            $subject_list = TableRegistry::get('class_subjects');
            $subject_name = TableRegistry::get('subjects');
            $school_name = TableRegistry::get('company');
            $reportcard = TableRegistry::get('reportcard');
            $classsub = $this->request->query('classid'); 
            $studentsub = $this->request->query('studentid'); 
            
            // temp
            
            $class_list = TableRegistry::get('class');
			$classsub = $this->request->query('classid'); 
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $school_id = $retrieve_class['school_id'];
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
            $city = $retrieve_schoolinfo['city'];
            $student_list = TableRegistry::get('student');
			$studentsub = $this->request->query('studentid'); 
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
            $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
            
            $subject_report_recorder = TableRegistry::get('subject_report_recorder');
            $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
            $this->set("retrieve_record", $retrieve_record); 
            
            $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
            $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
            $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
            $subjectids = explode(',', $retrieve_subjectid['subject_id']);
            $retrieve_subjectid['subject_id'];
            $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
            $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
            $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
            $this->set("subject_names", $retrieve_subjectname);
            
            
            $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (Langues Congolaises)'])->toArray();
            $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Ecrite'])->toArray();
            $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Vocabulaire'])->toArray();
            $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Expression Orale (FRANCAIS)'])->toArray();
            $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
            $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
            $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
            $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
            $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
            $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
            $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sciences d\'éveil'])->toArray();
            $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
            $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed civ & morale'])->toArray();
            
            $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
            $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
            $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
            $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
            $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
            $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
           
            // dd($second_period);
         
            $this->set("first_period", $first_period); 
            $this->set("second_period", $second_period);
            $this->set("third_period", $third_period);
            $this->set("fourth_period", $fourth_period);
            $this->set("fifth_period", $fifth_period);
            $this->set("sixth_period", $sixth_period);
            $this->set("sevent_period", $sevent_period);
            $this->set("eight_period", $eight_period);
            
            $this->set("nine_period", $nine_period);
            
            $this->set("ten_period", $ten_period);
            $this->set("eleven_period", $eleven_period);
             
            $this->set("twelve_period", $twelve_period);
             
            $this->set("threteen_period", $threteen_period);
             
            $this->set("forteen_period", $forteen_period);
            $this->set("fifteen_period", $fifteen_period);
            $this->set("sixteen_period", $sixteen_period);
            $this->set("seventeen_period", $seventeen_period);
            $this->set("eightteen_period", $eightteen_period);
            $this->set("nineteen_period", $nineteen_period);
            $this->set("twenty_period", $twenty_period);
            $this->set("twentyone_period", $twentyone_period);
            $this->set("twentytwo_period", $twentytwo_period);
            
            $this->set("twentythree_period", $twentythree_period);
            
            $this->set("twentyfour_period", $twentyfour_period);
            $this->set("twentyfive_period", $twentyfive_period);
            $this->set("twentysix_period", $twentysix_period);
            
            $this->set("city", $city);
            $this->set("classes_name", $retrieve_class);   
            $this->set("student_name", $retrieve_studentinfo);   
            $this->set("school_name", $retrieve_schoolinfo);
            $this->set("report_marks", $retrieve_reportcard);
            $this->viewBuilder()->setLayout('user');    
        }
        else
        {
            return $this->redirect('/login/') ;   
        }  
    }
    
    public function twoemematernel ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    
                      $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                //   dd($first_period);
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de language'])->toArray();
                    
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();

                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratiques'])->toArray();
                    
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schémas corporels'])->toArray();
                   
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                   
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                   
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                   
                   
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                   
                   
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                  
                  
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de latéralité'])->toArray();
                    
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de schéma corporel'])->toArray();
                 
                    // dd($second_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                   
                    
                    
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function thiredclass ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grammaire & Conj.'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Rédaction'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale - Récit. - Voc'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. phras. Ecrit. & réd'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. Conj. Analyse'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues Congolaises'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grandeurs'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();

                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Zoologie - botanique & Info.'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Technologie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Educ. civ & morale'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé Env.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sports'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                  
                    // dd($third_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    
                    $this->set("twelve_period", $twelve_period);
                    
                    $this->set("threteen_period", $threteen_period);
                    
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
                public function sixthclass ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Langues Congolaises)'])->toArray();      
                    $this->set("first_period", $first_period); 

                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $this->set("second_period", $second_period);
                    
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts plastiques'])->toArray();
                    $this->set("third_period", $third_period);
                    
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $this->set("fourth_period", $fourth_period);
                    
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. phys. & sportive'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Conj.'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orth. & Ecrit. /Calligr.'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Rédaction'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Vocab.'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Lecture (Français)'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Gram. & Analyse'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Exp. Orale & Récitation'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Phras. Ecrite & Réda.'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Orthographe'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comp. Orale & Vocab.'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Problèmes'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique / Zoologie'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Formes géométriques'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mesures de grandeurs'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Numération'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anatomie / Botanique'])->toArray();
                    $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Techno. / Informatique (1)'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $twentyseven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Santé & Env.'])->toArray();
                    $twentyeight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $twentynine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $this->set("twentynine_period", $twentynine_period);                  
                   
                    // dd($ten_period);
                    
                    
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    $this->set("twentythree_period", $twentythree_period);
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    $this->set("twentyseven_period", $twentyseven_period);
                    $this->set("twentyeight_period", $twentyeight_period);
                   
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            
            public function threeemezestion ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Opérations des banques et des crédits'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'DROIT'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'CORRESPONDANCE COM. FRANÇAISE'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Entreprenariat'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                   $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                     $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                   $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
                 public function twomezestion ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Droit'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie économique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Angl.'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Franc.'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Financières'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Documentation Commerciale'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                   $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                     $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                   $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                   
                    // dd($twelve_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
             public function fouremezestion ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Anglaise'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Correspondance Com. Française'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Déontologie Professionnelle'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Finances Publiques'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Compl. (Visites guidées)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'DROIT'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie de développement'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques Générales'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Organisations des entreprises'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Fiscalité'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Analytique'])->toArray();
                   $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                     $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                   $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Comptabilité Générale'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique Professionnelle'])->toArray();
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }       
            }
            
            public function oneèrecreche ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités d\'arts plastiques'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de comportement'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités musicales'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités Physiques'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités sensorielles'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de structuration spatiale'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités exploratrices'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de langage'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités mathematiques'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités libres'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Activités de vie pratique'])->toArray();
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("ten_period", $ten_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
             public function oneerehumanitiescientifices ()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                         $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Algèbre. Stat. et Analy.'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géom. et Trigo'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie Générale'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géologie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Techn. d\'Info. & Com. (TIC)'])->toArray();
$ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
$eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
$twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. et Morale'])->toArray();
    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->toArray();
    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            public function twoerehumanitiescientifices ()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Soc. Afri. / Ecopol (1)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();

                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
            }
            
            
           
            
            public function threeemeanneHumanitelitere()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }  
            }
            
            public function firsterehumanitieliter()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais (1)'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grec (1)'])->toArray();
$fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                    
    $first_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Latin", $first_Latin); 
    $this->set("second_Latin", $second_Latin); 
    
    $first_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_grc", $first_grc); 
    $this->set("second_grc", $second_grc); 

    $first_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Microbiologie", $first_Microbiologie); 
    $this->set("second_Microbiologie", $second_Microbiologie); 

    $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_edu", $first_edu); 
    $this->set("second_edu", $second_edu);

    $first_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_edus", $first_edus); 
    $this->set("second_edus", $second_edus);
                 
                 
                             
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }
            
            public function twoemeanneehumanitieliter()
            {
                 $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 

                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Microbiologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques (1)'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
$eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie / Ecopol (1)'])->toArray();
$twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais (1)'])->toArray();
$forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Grec (1)'])->toArray();
$fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
$sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
$seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Latin'])->toArray();
                  
    $first_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_Latin = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Latin','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_Latin", $first_Latin); 
    $this->set("second_Latin", $second_Latin); 
    
    $first_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $second_grc = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Grec (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
    $this->set("first_grc", $first_grc); 
    $this->set("second_grc", $second_grc); 
    
                    $first_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_Microbiologie = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Microbiologie','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_Microbiologie", $first_Microbiologie); 
                    $this->set("second_Microbiologie", $second_Microbiologie);
    
                    $first_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_edu = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Ed. Civ. & Morale','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_edu", $first_edu); 
                    $this->set("second_edu", $second_edu);
                    
                    $first_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '1ère PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $second_edus = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1, 'period_name' => '2ème PERIODE','subject_name'=>'Informatique (1)','semester_name'=>'PREMIER SEMESTRE' ])->first();
                    $this->set("first_edus", $first_edus); 
                    $this->set("second_edus", $second_edus);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }       
            }
            
            public function firstpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Travaux Man./Ecriture'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Sociologie Africaine'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
               
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }          
            }
            
            public function secondpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
					$classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie/Micro'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
               
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }   
                
            }
            
            public function thiredpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                   
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale /théâtrale'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->toArray();
                                       
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }          
            }
            
            public function fourthpedagogie()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                   
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musicale/théâtrale'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Langues nationales'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                    $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                    $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                    $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                    $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav./Man./Ecriture'])->toArray();
                    $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pratique d\'enseignement'])->toArray();

                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                    $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                    $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    $this->set("nine_period", $nine_period);
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                    $this->set("twelve_period", $twelve_period);
                    $this->set("threteen_period", $threteen_period);
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
            public function chime()
            {
                $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    $class_list = TableRegistry::get('class');
                    $classsub = $this->request->query('classid'); 
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    
                    $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                    
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
                  public function threehumanitiemath()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            // threeanneehumanitiescientifices//
            
            
             public function threeanneehumanitiescientifices()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            
              public function threehumanitiebilogie()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Scientifique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Esthétique'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
            
              public function fouranneehumanitiescientifices()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                      
                     $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }
            
                   public function fouremebilogie()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    
                     $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion (1)'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie (1)'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. civ & morale (1)'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie/Micro'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin Pédagogique'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education musica/théâtrale'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Economie Politique'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                   $sixteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'FRANÇAIS'])->toArray();
                     $seventeen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique générale'])->toArray();
                   $eightteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Didactique des disciplines'])->toArray();
                   $nineteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Psychologie'])->toArray();
                   $twenty_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                   $twentyone_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Trav. Man./Ecriture'])->toArray();
                   $twentytwo_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Pédagogie'])->toArray();
                   $twentythree_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Arts dramatiques'])->toArray();
                    $twentyfour_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Init. Trav. Prod.'])->toArray();
                    $twentyfive_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Phys. & Sport'])->toArray();
                   $twentysix_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    $this->set("sixteen_period", $sixteen_period);
                    $this->set("seventeen_period", $seventeen_period);
                    $this->set("eightteen_period", $eightteen_period);
                    $this->set("nineteen_period", $nineteen_period);
                    $this->set("twenty_period", $twenty_period);
                    $this->set("twentyone_period", $twentyone_period);
                    $this->set("twentytwo_period", $twentytwo_period);
                    
                    $this->set("twentythree_period", $twentythree_period);
                    
                    $this->set("twentyfour_period", $twentyfour_period);
                    $this->set("twentyfive_period", $twentyfive_period);
                    $this->set("twentysix_period", $twentysix_period);
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }     
            }  
            
            
               public function fourememath()
            {
               $this->request->session()->write('LAST_ACTIVE_TIME', time());
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid'); 
                    
                    
                    // temp
                    
                     $class_list = TableRegistry::get('class');
					  $classsub = $this->request->query('classid'); 
                     $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $school_id = $retrieve_class['school_id'];
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $school_id ])->first();
                    $city = $retrieve_schoolinfo['city'];
                    $student_list = TableRegistry::get('student');//student table call kiya
					$studentsub = $this->request->query('studentid'); //stid session id get krte hai query 
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first(); 
                    $stydent_name = $retrieve_studentinfo['f_name']." ".$retrieve_studentinfo['f_name'];
                    $gender = $retrieve_studentinfo['gender'];
                    $class = $retrieve_studentinfo['class'];
                    //  dd($retrieve_studentinfo);
                    // temp
                           $subject_report_recorder = TableRegistry::get('subject_report_recorder');
                    $retrieve_record = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1 ])->toArray();
                    $this->set("retrieve_record", $retrieve_record); 
                    
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);
                    
                    
                    $first_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Religion'])->toArray();
                    $second_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education à la vie'])->toArray();
                    $third_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Ed. Civ. & Morale'])->toArray();
                    $fourth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Informatique (1)'])->toArray();
                    $fifth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Dessin'])->toArray();
                    $sixth_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Education Physique'])->toArray();
                    $sevent_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Géographie'])->toArray();
                    $eight_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Histoire'])->toArray();
                    $nine_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Philosophie'])->toArray();
                    $ten_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Anglais'])->toArray();
                    $eleven_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Chimie'])->toArray();
                   $twelve_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Biologie'])->toArray();
                    $threteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Physique'])->toArray();
                    $forteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Français'])->toArray();
                    $fifteen_period = $subject_report_recorder->find()->where(['student_id' => $studentsub,'class_id' => $classsub, 'status' => 1,'subject_name'=>'Mathématiques'])->toArray();
                 
                   
                   
                    // dd($eightteen_period);
                 
                    $this->set("first_period", $first_period); 
                    $this->set("second_period", $second_period);
                    $this->set("third_period", $third_period);
                     $this->set("fourth_period", $fourth_period);
                    $this->set("fifth_period", $fifth_period);
                      $this->set("sixth_period", $sixth_period);
                    $this->set("sevent_period", $sevent_period);
                    $this->set("eight_period", $eight_period);
                    
                    $this->set("nine_period", $nine_period);
                    
                    $this->set("ten_period", $ten_period);
                    $this->set("eleven_period", $eleven_period);
                         
                    $this->set("twelve_period", $twelve_period);
                         
                    $this->set("threteen_period", $threteen_period);
                         
                    $this->set("forteen_period", $forteen_period);
                    $this->set("fifteen_period", $fifteen_period);
                    
                    
                    $this->set("gender", $gender);
                    $this->set("class", $class);
                    $this->set("city", $city);
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }    
            }
            
            
            

            public function editreportSubject()
            {
                $tid = $this->Cookie->read('stid');
                if(!empty($tid))
                {
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $student_list = TableRegistry::get('student');
                    $class_list = TableRegistry::get('class');
                    $subject_list = TableRegistry::get('class_subjects');
                    $subject_name = TableRegistry::get('subjects');
                    $school_name = TableRegistry::get('company');
                    $reportcard = TableRegistry::get('reportcard');
                    $teachersubject = TableRegistry::get('employee_class_subjects');
                    $classsub = $this->request->query('classid'); 
                    $studentsub = $this->request->query('studentid');       
                    $tchrid = $this->request->session()->read('tchr_id');
                    $retrieve_teachersub = $teachersubject->find()->where(['emp_id' => $tchrid, 'class_id' => $classsub])->toArray(); 
                    $subsids = [];          
                    foreach ($retrieve_teachersub as $subids) {
                        $subsids[] = $subids['subject_id'];
                     } 
                    $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
                    $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
                    $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
                    $subjectids = explode(',', $retrieve_subjectid['subject_id']);
                    $retrieve_subjectid['subject_id'];
                    
                    $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
                    $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
                    $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
                    $this->set("subject_names", $retrieve_subjectname);   
                    $this->set("classes_name", $retrieve_class);   
                    $this->set("student_name", $retrieve_studentinfo);   
                    $this->set("school_name", $retrieve_schoolinfo);
                    $this->set("report_marks", $retrieve_reportcard);
                    $this->set("subjectsids", $subsids);
                    $this->viewBuilder()->setLayout('user');    
                }
                else
                {
                    return $this->redirect('/login/') ;   
                }
            }

    
    public function reportcard(){
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $student_list = TableRegistry::get('student');
        $class_list = TableRegistry::get('class');
        $subject_list = TableRegistry::get('class_subjects');
        $subject_name = TableRegistry::get('subjects');
        $school_name = TableRegistry::get('company');
        $reportcard = TableRegistry::get('reportcard');
        $classsub = $this->request->query('classid'); 
        $studentsub = $this->request->query('studentid');                     
        $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
        $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
        $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
        $subjectids = explode(',', $retrieve_subjectid['subject_id']);
        $retrieve_subjectid['subject_id'];
        $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
        $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
        $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
        $this->set("subject_names", $retrieve_subjectname);   
        $this->set("classes_name", $retrieve_class);   
        $this->set("student_name", $retrieve_studentinfo);   
        $this->set("school_name", $retrieve_schoolinfo);
        $this->set("report_marks", $retrieve_reportcard);
        $this->viewBuilder()->setLayout('user');
    } 
            
    public function downloadreport(){
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $student_list = TableRegistry::get('student');
        $class_list = TableRegistry::get('class');
        $subject_list = TableRegistry::get('class_subjects');
        $subject_name = TableRegistry::get('subjects');
        $school_name = TableRegistry::get('company');
        $reportcard = TableRegistry::get('reportcard');
        $classsub = $this->request->query('classid'); 
        $studentsub = $this->request->query('studentid');                     
        $retrieve_subjectid = $subject_list->find()->where(['class_id' => $classsub ])->first();
        $retrieve_reportcard = $reportcard->find()->where(['stuid' => $studentsub ])->first();
        $retrieve_studentinfo = $student_list->find()->where(['id' => $studentsub ])->first();
        $subjectids = explode(',', $retrieve_subjectid['subject_id']);
        $retrieve_subjectid['subject_id'];
        $retrieve_subjectname = $subject_name->find()->where(['id IN' => $subjectids ])->toArray();
        $retrieve_class = $class_list->find()->where(['id' => $classsub ])->first();
        $retrieve_schoolinfo = $school_name->find()->where(['id' => $retrieve_class['school_id'] ])->first();
               
        $content = '<!DOCTYPE html>
<html>
<head>
    <title>Demo</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style type="text/css">
  body {
    margin: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #fff;
  }
.body-bg{
    border: 2px solid #333;
    margin: 20px;   
}
.logo img{
    width: 100%;
}
.main-head{
   text-align: center; 
}
.header-sec {
    padding: 12px 0;
}
:focus-visible {
    outline: none;
}
.body-bg input{
    border: none;    width: 30px;        height: 38px;
}
.head-bot tr {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
}
.head-bot tr td {
    border-right: 2px solid #333;
    padding: 8px 8px !important;
    border-left: 2px solid #333;
    background: #fff;
}
.head-bot tr td input{
    border-bottom: none;
}
table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
    background-color: #f1f1f1;
}
table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
    background-color: #f0f2ff !important;
}

table.dataTable.row-border tbody tr:first-child th, table.dataTable.row-border tbody tr:first-child td, table.dataTable.display tbody tr:first-child th, table.dataTable.display tbody tr:first-child td {
    border-top: none;
}
table.dataTable.order-column tbody tr>.sorting_1, table.dataTable.order-column tbody tr>.sorting_2, table.dataTable.order-column tbody tr>.sorting_3, table.dataTable.display tbody tr>.sorting_1, table.dataTable.display tbody tr>.sorting_2, table.dataTable.display tbody tr>.sorting_3 {
    background-color: #fafafa;
}
table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
    border-top: 1px solid #ddd;
}
table.dataTable.nowrap th, table.dataTable.nowrap td {
    white-space: nowrap;
}
table.dataTable.nowrap th, table.dataTable.nowrap td {
    font-size: 13px;
}
table.dataTable tbody th, table.dataTable tbody td {
    padding: 8px 10px;
}

table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
    background-color: #f9f9f9;
}
table.dataTable tbody tr {
    background-color: #fff;
}
table.dataTable tbody tr {
    font-size: 12px;
    font-weight: 500;
}
.prov-td{
      border: none !important;  
}
.prov-td td{
    border-right: 0 !important;
}
.prov-td td input {
    border: none;
    width: 100%;
}

.ville-bot tr td{
    border-top:none !important;
}
.ville-bot input{
    border-bottom:1px dashed #333;    width: 100%;
}
.head-code{

}
.head-code tr {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
}
.head-code tr td {
    border-right: 2px solid #333;
    padding: 4px 8px;
}
.pol {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    /*margin-right: -15px;
    margin-left: -15px;*/
}
.pol-md-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .pol-md-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .pol-md-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .pol-md-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .pol-md-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .pol-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .pol-md-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .pol-md-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .pol-md-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .pol-md-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .pol-md-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .pol-md-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
/*  .up-header{
    overflow: auto;
  }*/
.up-header .lable{
font-size: 14px;font-weight: 600;    margin-top: 10px;
}
.lable {
    float: left;
    width: 200px;
}
  .inline{
    display: flex;
  }
  .ville-bot {
    width: 100%;
    margin-left: 5px;
}
.border-main-left{
     border-left: 2px solid #333;    padding-bottom: 12px;   
}
.smal-sec {
    text-align: center;
    border: 2px solid #333;
    padding: 10px 0px 2px 0px;
}
.smal-sec sub {
    bottom: 0.75em;
}
.big-section {
    border-bottom: 2px solid #333;
    border-right: 2px solid #333;
    border-left: 2px solid #333;
}
.big-section label{
        font-weight: 600;
    padding: 0 10px;  
}
.border-inner {
    border: 2px solid #333;
    border-bottom: none;
    border-top: none;
}
.border-inner1 {
    border-bottom: none;
    border-top: 2px solid #333;
}
.border-inner2 {
    border-bottom: none;
    border-top: 2px solid #333;
    
    font-size: 13px;
}
.border-left-inn{
 border-left: 2px solid #333;   
}
.domain-green {
    text-align: center;
    background: #bfbea5 !important;
    border-left: 2px solid #333;
    border-right: 2px solid #333;
}
.dark-green {
    text-align: center;
    background: #071a2a !important;
    border-right: 2px solid #333;
}
.subject-td tr td {
    border-left: 1px solid #333;
    border-bottom: 1px solid #333 !important;
}
.subject-td tr {
    background: #fff !important;
}
.border-inner-subect{

}
.border-inner-subect2{
    
}
.border-left-sub {
    border-left: 1px solid #333;
}
.border-inner-subect {
    border: 1px solid #333;
    border-bottom: none;
    border-top: none;    text-align: center;
}
.big-section-subect {
    border-bottom: 1px solid #333;
    border-right: 1px solid #333;
    border-left: 1px solid #333;
}
.border-left-subhalf{
 border-right: 2px solid #333;   
}
.subect-sec label{
      padding-top: 5px;  
}
.font-weight{
    font-weight: 600;
}
.smal-sec1 {
    padding: 40px 36px 0px 36px;
}
.smal-sec1 ul li{
   list-style: circle;
}
.smal-sec1 input {
    border: none;
    width: 100%;
    height: 22px;
}
.head-bot tr td input {
    border: none;
    width: 22px;
    height: 28px;
}
</style>    
</head>
<body class="body-bg">
<div class="container">
<div class="row header-sec">
<div class="col-md-3">
 <div class="logo">
 <img src="https://you-me-globaleducation.org/school/img/R-icon-2.png">  
 </div> 
</div>
<div class="col-md-6">
  <div class="main-head">
  <h3>Republique Democratique Du Congo
  Ministere De Lenseignement Primaire, Secondaire Et Technique</h3>
</div>
</div>
<div class="col-md-3"></div>  
</div>   
</div>
<div class="container">
<div class="up-header">
<div class="pol-md-12">
  <table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%;" role="grid" aria-describedby="example_info">
                        <thead> </thead>
                        <tbody>
                        <tr class="odd">
                        <td style="width: 141px;">N ID.</td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                      </tr>

                    </tbody>

                                    </table>
</div>

<div class="pol-md-12">
  <table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%; border-bottom: 2px solid #333;" role="grid" aria-describedby="example_info">
<tbody>
<tr class="prov-td">
  <td style="width: 30px;">Province:</td>
  <td style="border-right: 2px solid #333 !important;"><input type="text" name=""></td>
</tr>
</tbody>
                                    </table>
</div>

<div class="row">

<div class="col-md-6">
<div class="inline">  
<div class="lable">
  Ville:
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>

<div class="inline">  
<div class="lable">
 Commune / Ter.(1)
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>

<div class="inline">  
<div class="lable">
Ecole
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>

<div class="inline">  
<div class="lable" style="margin-top: 22px;">
Code
</div>
<div class="ville-bot">
<table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%;    margin-top: 6px;" role="grid" aria-describedby="example_info">
                        <thead> </thead>
                        <tbody>
                        <tr class="odd">
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                      </tr>

                    </tbody>

                                    </table>
</div>
</div>

</div>

<div class="col-md-6 border-main-left">
<div class="row">
<div class="col-md-9">

<div class="inline">  
<div class="lable">
Eleve
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>
</div>

<div class="col-md-3">

<div class="inline">  
<div class="lable">
Sexe
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>
</div>

<div class="col-md-8">

<div class="inline">  
<div class="lable">
NE (E)A:
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>
</div>

<div class="col-md-4">

<div class="inline">  
<div class="lable">
LE
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
<div class="lable">
/
</div>
<div class="ville-bot">
<input type="text" name="">
</div>

<div class="lable">
/
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>
</div>

<div class="col-md-12">

<div class="inline">  
<div class="lable">
Eleve
</div>
<div class="ville-bot">
<input type="text" name="">
</div>
</div>
</div>
<div class="col-md-12">
<div class="inline">  
<div class="lable" style="margin-top: 22px;">
N* PERM
</div>
<div class="ville-bot">
<table id="example" class="display nowrap dataTable no-footer head-bot" style="width: 100%;    margin-top: 6px;" role="grid" aria-describedby="example_info">
                        <thead> </thead>
                        <tbody>
                        <tr class="odd">
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                        <td><input type="text" name=""></td>
                      </tr>

                    </tbody>

                                    </table>
</div>
</div>
</div>
</div>
</div>


</div>



<div class="col-md-12 p-0">
  <div class="smal-sec">
  <h5>Bullentin LA 8<sub>times</sub> Annee cycle terminal DE Base (CTEB) Anne Scolaire 20
    <input type="text" name="" style="border-bottom: 1px dashed #333;">
    20
    <input type="text" name="" style="border-bottom: 1px dashed #333;">
  </h5>
</div>
</div>
</div>
</div>


<div class="container">
<div class="pol big-section">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4 text-center"><label>Branches</label></div>

 <div class="pol-md-8 border-inner text-center" style="
    border-right: none;
"><label>Premier Semestre</label>
 <div class="pol border-inner1">
 <div class="pol-md-2 ">
  <label>MAX.</label>
 </div>
<div class="pol-md-4 border-left-inn">
  <label style="margin-bottom: 0;">Travauk</label><br>
   <label>Journal</label>
   <div class="pol border-inner2">
 <div class="pol-md-6 ">
  <label>1 ere P</label>
 </div>
 <div class="pol-md-6 border-left-inn">
   <label>2nd P</label>
 </div>
</div>


 </div>
<div class="pol-md-3 border-left-inn">
  <label>Max Exam</label>
 </div>
 <div class="pol-md-3 border-left-inn">
  <label>Total</label>
 </div>
</div>
 </div>  
 </div> 
</div> 

<div class="pol-md-6">
 <div class="pol">

 <div class="pol-md-8 border-inner text-center " ><label>Second Semestre</label>
 <div class="pol border-inner1">
 <div class="pol-md-2 ">
  <label>MAX.</label>
 </div>
<div class="pol-md-4 border-left-inn">
  <label style="margin-bottom: 0;">Travauk</label><br>
   <label>Journal</label>
   <div class="pol border-inner2">
 <div class="pol-md-6 ">
  <label>3 rd P</label>
 </div>
 <div class="pol-md-6 border-left-inn">
   <label>4 th P</label>
 </div>
</div>


 </div>
<div class="pol-md-3 border-left-inn">
  <label>Max Exam</label>
 </div>
 <div class="pol-md-3 border-left-inn">
  <label>Total General</label>
 </div>
</div>
 </div>

 <div class="pol-md-4 text-center">
 <div class="pol">
 <div class="pol-md-2 " style="
    background: #071a2a;
">
  <label></label>
 </div>
 <div class="pol-md-10 border-left-inn">
   <label style="    padding: 17px 10px;">Examen De Repechage</label>
   <div class="pol border-inner2">
 <div class="pol-md-4 ">
  <label>%</label>
 </div>
 <div class="pol-md-8 border-left-inn">
   <label>Sign. Prof.</label>
 </div>
</div>
 </div>
</div>  

 </div>  
 </div> 
</div>

</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label>Dessin</label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="40">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" >
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="80">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="60">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="20">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-4 border-inner-subect">
    <input type="text" name="">
 </div>
  <div class="pol-md-6" >
    <input type="text" name="">
 </div>
</div>   
   </div> 
 </div> 
</div>


</div>  
</div>



<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label>Musique</label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="40">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" >
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="80">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="60">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="20">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-4 border-inner-subect">
    <input type="text" name="">
 </div>
  <div class="pol-md-6" >
    <input type="text" name="">
 </div>
</div>   
   </div> 
 </div> 
</div>


</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label>Education Physique</label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="40">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" >
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="80">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="60">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="20">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-4 border-inner-subect">
    <input type="text" name="">
 </div>
  <div class="pol-md-6" >
    <input type="text" name="">
 </div>
</div>   
   </div> 
 </div> 
</div>


</div>  
</div>




<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Sous - Total</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="40" class="font-weight">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="80" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="60" class="font-weight">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="20" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-4 border-inner-subect">
    <input type="text" name="">
 </div>
  <div class="pol-md-6" >
    <input type="text" name="">
 </div>
</div>   
   </div> 
 </div> 
</div>


</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Maxima Generaux</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="220" class="font-weight">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="210" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="" value="60" class="font-weight">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
 <input type="text" name="" value="20" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 ">
<input type="text" name="" value="160" class="font-weight">
 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4" style="background: #071a2a;margin-top: -1px;height: 39px;">
  
</div> 
 </div> 
</div>


</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Totaux</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">
  
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-10 border-inner-subect">
    <label>* Passe(1)</label>
 </div>

</div>   
   </div> 
 </div> 
</div>


</div>  
</div>





<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Pourcentage</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">
  
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-10 border-inner-subect">
    <label>* Double(1)</label>
 </div>

</div>   
   </div> 
 </div> 
</div>
</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Place/ Near Deleves</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">
  
 </div>
<div class="pol-md-2 border-left-sub">
/
 </div>
 <div class="pol-md-2 border-left-sub">
/
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

 </div>
<div class="pol-md-2 border-left-sub">
  /
 </div>
 <div class="pol-md-2 border-left-sub">
  /
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
 <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub">
   <input type="text" name="" class="font-weight">
 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-10 border-inner-subect">
    <label>LE..../ .../ 20</label>
 </div>

</div>   
   </div> 
 </div> 
</div>
</div>  
</div>

<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Application</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">
  
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf" >
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px;">

 </div>
  <div class="pol-md-10 border-inner-subect">
    <label>Sceau de</label>
 </div>

</div>   
   </div> 
 </div> 
</div>
</div>  
</div>


<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 1px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label><strong>Conduite</strong></label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">
  
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;margin-top: -1px;">

 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="" class="font-weight">
 </div>
<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf" >
  <div class="pol border-inner-subect2">
 <div class="pol-md-6 " style="background: #071a2a;margin-top: -1px;">

 </div>
 <div class="pol-md-6 border-left-sub" style="background: #071a2a;margin-top: -1px;    height: 39px;">

 </div>
</div>
 </div>
</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2" style="background: #071a2a;    margin-top: -1px; height: 39px;">

 </div>
  <div class="pol-md-10 border-inner-subect">
    <label></label>
 </div>

</div>   
   </div> 
 </div> 
</div>
</div>  
</div>



<div class="container">
<div class="pol big-section subect-sec" style="border-bottom: 2px solid #333;">
<div class="pol-md-6">
 <div class="pol">
 <div class="pol-md-4"><label>Signature</label></div>

 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;
">
<div class="pol">
 <div class="pol-md-6">
  <input type="text" name="">
 </div>

<div class="pol-md-3 border-left-sub">
  <div class="pol border-inner-subect2">
 
 
</div>
 </div>
 <div class="pol-md-3 border-left-sub border-left-subhalf">
  
 </div>
</div>
 </div>  
 </div> 
</div> 


<div class="pol-md-6">
 <div class="pol">


 <div class="pol-md-8 border-inner-subect text-center" style="
    border-right: none;    border-left: 0;
">
 <div class="pol">
 <div class="pol-md-2">
  <input type="text" name="">
 </div>
<div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
 <div class="pol-md-2 border-left-sub">
  <input type="text" name="">
 </div>
<div class="pol-md-6 border-left-sub">
  <div class="pol border-inner-subect2">
 <div class="pol-md-12 ">
 <input type="text" name="">
 </div>

</div>
 </div>

</div>
 </div>

   <div class="pol-md-4">
  <div class="pol">
 <div class="pol-md-2">

 </div>
<div class="pol-md-10 border-inner-subect" style="
    border-left: none;">
    <label></label>
 </div>
</div>   
   </div> 
 </div> 
</div>


</div>  
</div>



<div class="container">
<div class="row">
<div class="col-md-12">
  <div class="smal-sec1">
    <ul>
      <li><p>Bullentin terminal DE Base LA 8 Annee cycle terminal DE Base (CTEB) Anne Scolaire <input type="text" name="" style="border-bottom: 1px dashed #333;width: 500px;">
    <input type="text" name="" style="border-bottom: 1px dashed #333;">
  </p></li>
  <li>Sous domaine des Sciences de la Vle et de la Terre</li>
  <li>Sous domaine des Sciences de 
</li>
    </ul>
  
</div>
</div>  
</div>

<div class="row">
  <div class="col-md-5"></div>
<div class="col-md-7">
  <div class="smal-sec1">
    
      <p>Falt a <input type="text" name="" style="border-bottom: 1px dashed #333;width: 230px;">
    LE <input type="text" name="" style="border-bottom: 1px dashed #333;width: 60px;">
    / <input type="text" name="" style="border-bottom: 1px dashed #333;width: 60px;">
   /20 <input type="text" name="" style="border-bottom: 1px dashed #333;width: 60px;">
  </p>
  
</div>
</div>  
</div>

<div class="row text-center mt-5">
  <div class="col-md-4">
    <strong>Signature</strong>
  </div>
<div class="col-md-4">
  <strong>Sceau</strong>
</div> 
<div class="col-md-4">
  <strong>Le Chef Esteblishment</strong>
</div> 
</div>

<div class="row">
<div class="col-md-12">
  <div class="smal-sec1">
    <ul>
      
  <li>Sous domaine des Sciences de la Vle et de la Terre</li>
  <li>Sous domaine des Sciences de 
</li>
    </ul>
  
</div>
</div>  
</div>
</div>
</body>

</html>';
                $title = $retrieve_studentinfo['f_name']."-".$retrieve_studentinfo['l_name'];
                $viewpdf = '<div style=" width:100%; font-family: Times New Roman; font-size: 16px;">'.$content.'</div>';
                //print_r($viewpdf); die;
                $dompdf = new Dompdf();
                $dompdf->loadHtml($viewpdf);    
                
                $dompdf->setPaper('A4', 'Portrait');
                $dompdf->render();
                $dompdf->stream($title.".pdf", array("Attachment" => false));
                 exit(0);
        }   
        
    

}

  


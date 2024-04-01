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
                $stid = $this->Cookie->read('stid'); 
                $school_table = TableRegistry::get('company');
				$student_table = TableRegistry::get('student');
				$this->request->session()->write('LAST_ACTIVE_TIME', time());
				//$retrieve_students = $student_table->find()->where([ 'md5(id)'=> $stid ])->toArray();
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
    				$this->viewBuilder()->setLayout('user');
			    }
			    else
			    {
			        return $this->redirect('/login/') ;
			    }
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
	
			public function changesession(){
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

  


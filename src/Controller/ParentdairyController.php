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
class ParentdairyController extends AppController
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
        $pid = $this->Cookie->read('pid'); 
        $sessionid = $this->Cookie->read('sessionid'); 
        
        $parntid = $this->request->session()->read('parent_id');
        $class_table = TableRegistry::get('class');
        $classsub_table = TableRegistry::get('class_subjects');
        $subjects_table = TableRegistry::get('subjects');
        $student_table = TableRegistry::get('student');
        $studentdairy_table = TableRegistry::get('student_class_dairy');
        $class_table = TableRegistry::get('class');
        
        $retrieve_student = $student_table->find()->where(['parent_id' => $parntid, 'session_id' => $sessionid ])->toArray() ;
        foreach($retrieve_student as $stddtl)
        {
            $retrieve_cls = $class_table->find()->where(['id' => $stddtl['class'] ])->first() ;
            $stddtl->classname = $retrieve_cls['c_name']."-".$retrieve_cls['c_section']." (". $retrieve_cls['school_sections'].")";
        }
        $this->set("studdtl", $retrieve_student);
        
        
        if(!empty($pid)) 
        {
    	    $dairydate = '';
    		$get_dairy = '';
    		$retrieve_subject = '';
    		if(!empty($_POST))
    		{
    		    $dairydate = $this->request->data('enddate'); 
                $studid = $this->request->data('liststudent');
                
                $retrieve_studnt = $student_table->find()->where(['id' => $studid ])->first() ;
        	    $class = $retrieve_studnt['class'];
        	    $compid = $retrieve_studnt['school_id'];
        		$retrieve_class = $classsub_table->find()->where(['class_id' => $class ])->first() ;
        	    $subid = explode(",", $retrieve_class['subject_id']);
        	    $retrieve_subject = $subjects_table->find()->where(['id IN' => $subid ])->toArray() ;
	   
    		    $get_dairy = $studentdairy_table->find()->where(['date' => $dairydate, 'class_id' => $class, 'student_id' => $studid ])->toArray() ;
    		    foreach($get_dairy as $gd)
    		    {
    		        $subid = $gd['subject_id'];
    		        $retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		        $gd->subject_name = $retrieve_subj['subject_name'];
    		    }
    		}
    		else
    		{
    		    /*$dairydate = date("d-m-Y", time());
    		    $get_dairy = $studentdairy_table->find()->where(['date' => $dairydate, 'class_id' => $class_id, 'student_id' => $studid ])->toArray() ;
    		    foreach($get_dairy as $gd)
    		    {
    		        $subid = $gd['subject_id'];
    		        $retrieve_subj = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		        $gd->subject_name = $retrieve_subj['subject_name'];
    		    }*/
    		}
    		
    		$this->set("studid", $studid);
    		$this->set("subjectdtl", $retrieve_subject);
            $this->set("clsid", $class);
    		$this->set("dairydate", $dairydate);
    		$this->set("dairydtl", $get_dairy);
            $this->viewBuilder()->setLayout('usersa');      
        }
        else
        {
             return $this->redirect('/login/') ;  
        }
    }
    
    public function uploadsignature()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $pid = $this->Cookie->read('pid'); 
            $sessionid = $this->Cookie->read('sessionid'); 
            $studentdairy_table = TableRegistry::get('student_class_dairy');
            $activ_table = TableRegistry::get('activity');
            $parntid = $this->request->session()->read('parent_id');
            
            $dairyid = $this->request->data('dairyid');
            $now = time();
            if(!empty($this->request->data['signature_image']))
            {   
                $folderPath = "dairy_signature/";
                $image_parts = explode(";base64,", $_POST['signature_image']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = uniqid() . '.'.$image_type; 
                $file = $folderPath . $filename;
                file_put_contents($file, $image_base64);
            }
            
            $update = $studentdairy_table->query()->update()->set([ 'parent_signature' => $filename, 'parent_id' => $parntid])->where([ 'id' => $dairyid  ])->execute();
            if($update)
            {   
                $sdid = $dairyid;
                $activity = $activ_table->newEntity();
                $activity->action =  "Student dairy signature uploaded"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->origin = $this->Cookie->read('id');
                $activity->value = md5($dairyid); 
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
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
}

  


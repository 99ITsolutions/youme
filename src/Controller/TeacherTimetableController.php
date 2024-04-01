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
class TeacherTimetableController  extends AppController
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
        $tid = $this->Cookie->read('tid'); 
        $class_table = TableRegistry::get('class');
        $subjects_table = TableRegistry::get('subjects');
        $session_table = TableRegistry::get('session');
        $timetable_table = TableRegistry::get('time_table');
        $emp_table = TableRegistry::get('employee');
        $empcls_table = TableRegistry::get('employee_class_subjects');
        $scl_table = TableRegistry::get('company');
        $scltimeslot_table = TableRegistry::get('school_timetable');
        if(!empty($tid))
        {
            $retrieve_emp = $emp_table->find()->where(['md5(id)' => $tid ])->first() ;
            
            $sclid = $retrieve_emp['school_id'];
            $retrieve_empcls = $empcls_table->find()->where(['md5(emp_id)' => $tid ])->group(['class_id'])->toArray() ;
            //$grades = explode(",", $retrieve_emp['grades']);
            
            $classes = [];
            foreach($retrieve_empcls as $grad)
            {
    		    $retrieve_class = $class_table->find()->where(['id' => $grad['class_id'] ])->first() ;
    		    $classname[] = $retrieve_class['c_name']."-".$retrieve_class['c_section']. "(".$retrieve_class['school_sections'].")";	
    		    $classids[] = $retrieve_class['id'];
            }
            
            
            $retrieve_school = $scl_table->find()->where(['id' => $sclid ])->first() ;
            
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_session = $session_table->find()->toArray() ;
    		
    		
    		if(!empty($_POST))
    		{
    		    $classid = $this->request->data('class_fil');
    		    $session_id = $this->request->data('session');
        		$retrieve_timetable = $timetable_table->find()->where(['school_id' => $sclid, 'session_id' => $session_id, 'class_id' => $classid ])->order(['start_time' => 'ASC'])->toArray() ;
        		foreach($retrieve_timetable as $timetbl)
        		{
        		    $subid = $timetbl['subject_id'];
        		    $retrieve_subject = $subjects_table->find()->where(['id' => $subid ])->first() ;
        		    $timetbl->subjectName = $retrieve_subject['subject_name'];
        		}
        		
        		$class_table = TableRegistry::get('class');
                $get_class = $class_table->find()->where(['id' => $classid])->first();
                $class_sectnname = strtolower($get_class['school_sections']);
        		$sclinfo = [];
                if($class_sectnname == "creche" || $class_sectnname == "maternelle")
                {
                    $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'KinderGarten'])->order(['id' => 'ASC'])->toArray();
                    $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['kinderscl_strttimings']));
                    $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['kinderscl_endtimings']));
                    
                }
                elseif($class_sectnname == "primaire")
                {
                    $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['primaryscl_strttimings']));
                    $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['primaryscl_endtimings']));
                    $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'Primary'])->order(['id' => 'ASC'])->toArray();
                }
                else
                {
                    $sclinfo['school_stym'] =  date("H:i", strtotime($retrieve_school['seniorscl_strttimings']));
                    $sclinfo['school_etym'] =  date("H:i", strtotime($retrieve_school['seniorscl_endtimings']));
                    $get_slot = $scltimeslot_table->find()->where(['school_id' => $sclid, 'added_for' => 'Senior'])->order(['id' => 'ASC'])->toArray();
                }
        		if(empty($get_slot))
        		{
        		    $error = "No data Exist";
        		}
        		else
        		{
        		     $error = "";
        		}
        		$this->set("classid", $classid);
    		    $this->set("sessionid", $session_id);
            }
    		else
    		{
    		    $sclinfo = '';
    		    $classid = '';
    		    $session_id = '';
    		    $retrieve_timetable = '';
    		    $error = '';
    		    $get_slot ='';
    		    $this->set("classid", $classid);
    		    $this->set("sessionid", $session_id);
    		} 
    		$this->set("get_slot", $get_slot);
    		$this->set("clsnames", $classname);
    		$this->set("sclinfo", $sclinfo);
    		$this->set("classids", $classids);
    		$this->set("scl_details", $retrieve_school);
    		$this->set("timetbl_details", $retrieve_timetable);
            $this->set("session_details", $retrieve_session);
            //$this->set("class_details", $retrieve_class);
            $this->set("error", $error);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
   
}

  


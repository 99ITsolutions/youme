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
class StudentTimetableController extends AppController
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
        else
        {
             $stid = $this->Cookie->read('pid'); 
        }
        if(!empty($stid))
        { 
            $student_table = TableRegistry::get('student');
            $subjects_table = TableRegistry::get('subjects');
            $cls_table = TableRegistry::get('class');
            $timetable_table = TableRegistry::get('time_table');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
    		$session_id = $this->Cookie->read('sessionid');
    		$retrieve_student = $student_table->find()->where(['md5(id)' => $stid ])->first() ;
    		
    	    $classid = $retrieve_student['class'];
    	    $sclid = $retrieve_student['school_id'];
    	    $retrieve_class = $cls_table->find()->where(['id' => $classid ])->first() ;
    	    
    	    $scl_table = TableRegistry::get('company');
            
    		$retrieve_school = $scl_table->find()->where(['id' => $sclid ])->first() ;
    		$retrieve_timetable = $timetable_table->find()->where(['school_id' => $sclid, 'session_id' => $session_id, 'class_id' => $classid ])->order(['start_time' => 'ASC'])->toArray() ;
    		foreach($retrieve_timetable as $timetbl)
    		{
    		    $subid = $timetbl['subject_id'];
    		    $retrieve_subject = $subjects_table->find()->where(['id' => $subid ])->first() ;
    		    $timetbl->subjectName = $retrieve_subject['subject_name'];
    		}
            $this->set("scl_details", $retrieve_school);
            $this->set("cls_details", $retrieve_class);
    		$this->set("timetbl_details", $retrieve_timetable);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
    
    
}

  


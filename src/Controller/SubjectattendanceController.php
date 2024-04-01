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
class SubjectattendanceController   extends AppController
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
        $classid = $this->request->data('classId');
	    $subjectid = $this->request->data('subId');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
        $compid = $this->request->session()->read('company_id');
		$content_table = TableRegistry::get('school_library');
		if(!empty($compid))
		{
            $retrieve_content = $content_table->find()->where([ 'school_id' => $compid , 'class_id' => $classid, 'subject_id' => $subjectid])->toArray();
            
    		$class_table = TableRegistry::get('class');
            $retrieve_class = $class_table->find()->select(['id' ,'c_name', 'c_section'])->where(['id' => $classid, 'active' => 1])->first() ;
            $classname = $retrieve_class['c_name']."-".$retrieve_class['c_section'];
            
            $subjects_table = TableRegistry::get('subjects');
            $retrieve_sub = $subjects_table->find()->select(['id' ,'subject_name' ])->where(['id' => $subjectid, 'status' => 1])->first() ;
            $subname = $retrieve_sub['subject_name'];
            
            $this->set("cls_name", $classname);
            $this->set("sub_name", $subname);
            $this->set("classid", $classid);
            $this->set("subjectid", $subjectid);
            $this->set("content_details", $retrieve_content); 
    		$this->viewBuilder()->setLayout('user');
		}
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function getattendance()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($this->Cookie->read('stid')))
        {
            $stid = $this->Cookie->read('stid'); 
        }
        elseif(!empty($this->Cookie->read('pid')))
        {
            $stid = $this->Cookie->read('pid'); 
        }
		$stucls = $this->request->query('classid');
		$subjctid = $this->request->query('subjectid');
        $attndnc_table = TableRegistry::get('attendance');
        if(!empty($stid))
        {
            $retrieve_attndnc = $attndnc_table->find()->where(['subject_id' => $subjctid, 'class_id' => $stucls, 'md5(student_id)' => $stid])->toArray() ;
            return $this->json($retrieve_attndnc);   
        }
        else
        {
            return $this->redirect('/login/') ;    
        }
    }
            
}

  


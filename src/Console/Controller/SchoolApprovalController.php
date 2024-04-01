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
class SchoolApprovalController  extends AppController
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
        $schoolid = $this->Cookie->read('id');
        $exams_assessment_table = TableRegistry::get('exams_assessments');
        $subjects_table = TableRegistry::get('subjects');
        $employee_table = TableRegistry::get('employee');
        $class_table = TableRegistry::get('class');
        if(!empty($schoolid))
        {
            $retrieve_examsassessment = $exams_assessment_table->find()->where(['status' => 0 , 'md5(school_id)'=> $schoolid ])->toArray() ; 
    			
    		foreach($retrieve_examsassessment as $key =>$approvecoll)
    		{
    			$retrieve_subject = $subjects_table->find()->select(['subject_name'])->where(['id' => $retrieve_examsassessment[$key]['subject_id'] ])->toArray();
    			$approvecoll->subject_name = $retrieve_subject[0]['subject_name'];
    			
    			$retrieve_class = $class_table->find()->select(['c_name', 'c_section'])->where(['id' => $retrieve_examsassessment[$key]['class_id']  ])->toArray();
    			$approvecoll->class_name = $retrieve_class[0]['c_name']."-".$retrieve_class[0]['c_section'];
    			
    			$retrieve_employee = $employee_table->find()->select(['f_name', 'l_name'])->where(['id' => $retrieve_examsassessment[$key]['teacher_id']  ])->toArray();
    			$approvecoll->teacher_name = $retrieve_employee[0]['f_name']."-".$retrieve_employee[0]['l_name'];
    		}
    			
    		
    		
            $this->set("approvedetails", $retrieve_examsassessment);
            $this->viewBuilder()->setLayout('user');
        }
        else
        {
            return $this->redirect('/login/') ;  
        }
    }
       
            
}

  




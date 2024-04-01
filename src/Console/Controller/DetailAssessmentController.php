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
class DetailAssessmentController  extends AppController
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
        $classid = $this->request->data('classId');
		$subjectid = $this->request->data('subId');
        $subjects_table = TableRegistry::get('subjects');
        $retrieve_subjectname = $subjects_table->find()->where(['id' => $subjectid ])->toArray() ;
        $this->set("classid", $classid); 
        $this->set("subjectid", $subjectid); 
        $this->set("subject_details", $retrieve_subjectname); 
        $this->viewBuilder()->setLayout('user');
    }
    
    public function subjectdtl()
    {   
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $employee_table = TableRegistry::get('employee');
		$subjectid = $this->request->data('classid');
		$classid = $this->request->data('subjectid');
        $retrieve_employee = $employee_table->find()->where(['subjects LIKE' => "%".$subjectid."%", 'grades LIKE' => "%".$classid."%" ])->toArray() ;
		$data = ['emp_dtl' => $retrieve_employee, 'subId' => $subjectid, 'clsId' => $classid];
        return $this->json($data);
    }
			
}

  


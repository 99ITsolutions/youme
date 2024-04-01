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
class SchoolLibraryAccessReportController extends AppController
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
        $error = '';
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $session_table = TableRegistry::get('session');
		$retrieve_sessions = $session_table->find()->order(['startyear' => 'ASC'])->toArray();
		
		$student_table = TableRegistry::get('student');
		$sclid = $this->request->session()->read('company_id');
		$retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
			[
    			'table' => 'class',
    			'type' => 'LEFT',
    			'conditions' => 'class.id = student.class'
			]
		])->where(['student.school_id' => $sclid, 'student.library_access' => 1])->order(['student.f_name' => 'ASC'])->toArray() ;
		
		$class_table = TableRegistry::get('class');
        $retrieve_class = $class_table->find()->where(['school_id' => $sclid])->order(['c_name' => 'ASC'])->toArray() ;
        
		$this->set("error", $error);
		$this->set("cls_details", $retrieve_class);
		$this->set("students_details", $retrieve_students);
		$this->set("sessiondetails", $retrieve_sessions);
        $this->viewBuilder()->setLayout('user');  
    }
    
    public function getsessstud()
    {
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessionid = $this->request->data('sessionid');
        $sclid = $this->request->session()->read('company_id');
                    
        $retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
			[
    			'table' => 'class',
    			'type' => 'LEFT',
    			'conditions' => 'class.id = student.class'
			]
		])->where(['student.school_id' => $sclid, 'student.session_id' => $sessionid, 'student.library_access' => 1])->order(['student.f_name' => 'ASC'])->toArray() ;
            $tabledata = "";
            $datavalue = array();
            foreach ($retrieve_students as $value) 
			{
				$tabledata .=  '<tr>
				        <td>
                            <span class="mb-0 font-weight-bold">'.$value['adm_no'].'</span>
                        </td>
                        <td>
                            <span>'.$value['password'].'</span>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$value['f_name']." ".$value['l_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['s_f_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['s_m_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['emergency_number'].'</span>
                        </td>
                        <td>
                            <span>'.$value['class']['c_name'].'-'.$value['class']['c_section'].' ('.$value['class']['school_sections'].' )</span>
                        </td>
                        
                    </tr>';
            }
            $datavalue['tabledata'] = $tabledata;
            return $this->json($datavalue);
       
    }
    
    public function getclsstud()
    {
        $class_table = TableRegistry::get('class');
        $student_table = TableRegistry::get('student');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $sessionid = $this->request->data('sessionid');
        $sclid = $this->request->session()->read('company_id');
        $clsid =  $this->request->data('classid');
        if($clsid == "all")
        {
            $retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
    			[
        			'table' => 'class',
        			'type' => 'LEFT',
        			'conditions' => 'class.id = student.class'
    			]
    		])->where(['student.school_id' => $sclid, 'student.session_id' => $sessionid, 'student.library_access' => 1])->order(['student.f_name' => 'ASC'])->toArray() ;
        }
        else
        {
            $retrieve_students = $student_table->find()->select(['student.id' , 'student.status', 'student.library_access', 'student.del_req_reason', 'student.adm_no' , 'student.l_name', 'student.f_name', 'student.password', 'student.delete_request', 'student.mobile_for_sms' , 'student.s_f_name' ,'student.s_m_name' , 'student.dob', 'class.c_name', 'class.c_section', 'class.school_sections'])->join(['class' => 
    			[
        			'table' => 'class',
        			'type' => 'LEFT',
        			'conditions' => 'class.id = student.class'
    			]
    		])->where(['student.school_id' => $sclid, 'student.session_id' => $sessionid, 'student.class' => $clsid, 'student.library_access' => 1])->order(['student.f_name' => 'ASC'])->toArray() ;
        }
            $tabledata = "";
            $datavalue = array();
            foreach ($retrieve_students as $value) 
			{
				$tabledata .=  '<tr>
				        <td>
                            <span class="mb-0 font-weight-bold">'.$value['adm_no'].'</span>
                        </td>
                        <td>
                            <span>'.$value['password'].'</span>
                        </td>
                        <td>
                            <span class="mb-0 font-weight-bold">'.$value['f_name']." ".$value['l_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['s_f_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['s_m_name'].'</span>
                        </td>
                        <td>
                            <span>'.$value['emergency_number'].'</span>
                        </td>
                        <td>
                            <span>'.$value['class']['c_name'].'-'.$value['class']['c_section'].' ('.$value['class']['school_sections'].' )</span>
                        </td>
                        
                    </tr>';
            }
            $datavalue['tabledata'] = $tabledata;
            return $this->json($datavalue);
       
    }
    
    
}

  


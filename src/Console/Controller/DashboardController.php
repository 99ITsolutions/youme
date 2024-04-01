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
class DashboardController  extends AppController
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
        $school_table = TableRegistry::get('company');
		$student_table = TableRegistry::get('student');
		$employee_table = TableRegistry::get('employee');
		$users_table = TableRegistry::get('users');
		$userid = $this->request->session()->read('users_id');
		$this->request->session()->write('LAST_ACTIVE_TIME', time());
		$retrieve_userdtl = $users_table->find()->where(['id' => $userid ])->first();
		$role = $retrieve_userdtl['role'];
		if($retrieve_userdtl['role'] == 2) 
        {
			$retrieve_users = $users_table->find()->select(['id' ])->where(['status' => '1', 'role !=' => '2' ])->count();
			$retrieve_school = $school_table->find()->select(['id' ])->where(['status' => '1' ])->count();
			$retrieve_students = $student_table->find()->select(['id' ])->where([ 'status'=> 1 ])->count();
			$retrieve_employees = $employee_table->find()->select(['id' ])->where([ 'status' => '1' ])->count();
			/*$retrieve_vehicles = $vehicle_table->find()->select(['id' ])->where([ 'school_id '=> $compid , 'session_id' => $session_id ])->count();
			$retrieve_class = $class_table->find()->select(['id' ])->where([ 'school_id '=> $compid , 'session_id' => $session_id ])->count();*/
        }
        else
        {
            $retrieve_users = '';
            $sclids = explode(",", $retrieve_userdtl['privilages']);
			$retrieve_school = count($sclids);
			$retrieve_students = $student_table->find()->select(['id' ])->where([ 'status'=> 1, 'school_id IN' => $sclids ])->count();
			$retrieve_employees = $employee_table->find()->select(['id' ])->where([ 'status' => '1', 'school_id IN' => $sclids ])->count();
        }
		$this->set("role", $role); 
		$this->set("students_details", $retrieve_students); 
		$this->set("employees_details", $retrieve_employees); 
		$this->set("users_details", $retrieve_users); 
		$this->set("school_details", $retrieve_school); 
		$this->viewBuilder()->setLayout('usersa');
    }

}

  


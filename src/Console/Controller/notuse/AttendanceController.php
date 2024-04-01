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
class AttendanceController  extends AppController
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
                $users_table = TableRegistry::get('users');

                $users_details = $users_table->find()->select([ 'name' , 'id' ])->where([ 'status' => 1  ])->order(['name' => 'asc' ])->toArray();

                // $today_date = strtotime('today midnight');
                // $users_attendance_details = $users_table->find()->select([ 'users.name' , 'users.id' , 'emp.id' , 'emp.userid' , 'emp.login' , 'emp.logout' , 'emp.day'  ])->join([
                //         'emp' => [
                //             'table' => 'emp_attendance',
                //             'type' => 'LEFT',
                //             'conditions' =>  'emp.userid =  users.id'
                                                        
                //         ]
                //     ])->where([ 'users.status' => 1  ])->toArray();   
                
                // $this->set("today_date",$today_date); 
                $this->set("users_details", $users_details); 
                // $this->set("users_attendance", $users_attendance_details); 
                $this->viewBuilder()->setLayout('user');

            }

            public function addatt()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $users_table = TableRegistry::get('users');
                    $attendace_table = TableRegistry::get('emp_attendance');

                    $day = strtotime($this->request->data('date'));
                    $login = strtotime($this->request->data('logintime'));
                    $logout = strtotime($this->request->data('logouttime'));

                    $retrieve_attendace = $attendace_table->find()->select(['id' ])->where(['userid' => $this->request->data('name')  , 'day' => $day ])->count() ;
                    
                    if($retrieve_attendace == 0 )
                    {
                        $attendace = $attendace_table->newEntity();
                        $attendace->userid =  $this->request->data('name')  ;
                        $attendace->day = $day ;
                        $attendace->login = $login ;
                        $attendace->logout = $logout ;
                        $attendace->added = strtotime('now');
                        if($saved = $attendace_table->save($attendace) )
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
                        $res = [ 'result' => 'exist'  ];
                    }
                }
                else
                {
                    $res = [ 'result' => 'invalid operation'  ];

                }


                return $this->json($res);

            }


            public function update($aid)
            {  
                $users_table = TableRegistry::get('users');
                $attendance_table = TableRegistry::get('emp_attendance');

                $users_attendance_details = $attendance_table->find()->select([ 'u.name' , 'emp_attendance.userid' , 'emp_attendance.login' , 'emp_attendance.logout' , 'emp_attendance.day' , 'emp_attendance.id' ])->join([
                        'users' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' =>  'u.id =  emp_attendance.userid' 
                        ]
                    ])->where(['md5(emp_attendance.id)' => $aid  ])->toArray();

                $this->set("users_attendance", $users_attendance_details);  
               // print_r($users_attendance_details);
               $this->viewBuilder()->setLayout('user');
            }

            public function updateatt()
            {
                if ($this->request->is('ajax') && $this->request->is('post') )
                {
                    $user_table = TableRegistry::get('users');
                    $attendace_table = TableRegistry::get('emp_attendance');
                    
                    // $day = strtotime($this->request->data('date')) ;
                    $login = strtotime($this->request->data('logintime')) ;
                    $logout = NULL ;
                    if($this->request->data('logouttime') != "")
                    {
                        $logout =  strtotime($this->request->data('logouttime')) ;
                    }
                    
                    $aid = $this->request->data('aid') ;
                    $now = strtotime('now');
                    
                    if( $attendace_table->query()->update()->set([ 'login' => $login  , 'logout' => $logout , 'modified' => $now  ])->where([ 'md5(id)' => $aid  ])->execute())
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
                    $res = [ 'result' => 'error'  ];   
                }
                
                return $this->json($res);
            }

            public function view($userid,$mon=null,$year=null)
            {  
                $users_table = TableRegistry::get('users');
                $attendance_table = TableRegistry::get('emp_attendance');

                // $month = date('M', strtotime('now'));
                // $year = date('y', strtotime('now'));
                
              //  $first = strtotime("first day of this month");
                //$last = strtotime("last day of this month");

                if($mon == "")
                {
                    $first = strtotime("first day of this month");
                    $last = strtotime("last day of this month");
                }
                elseif($mon == 0)
                {   $currentyear = $year;

                    if($currentyear)
                    {
                        $first = strtotime("first day of ".$currentyear);
                        $last = strtotime("last day of ".$currentyear);
                    }
                }
                else
                {
                    if($mon == 1){
                        $currentmonth = "January ".$year ;
                    }
                    else if($mon == 2){
                        $currentmonth = "February ".$year ;
                    }else if($mon == 3){
                        $currentmonth = "March ".$year ;
                    }else if($mon == 4){
                        $currentmonth = "April ".$year ;
                    }else if($mon == 5){
                        $currentmonth = "May ".$year ;
                    }else if($mon == 6){
                        $currentmonth = "June ".$year ;
                    }else if($mon == 7){
                        $currentmonth = "July ".$year ;
                    }else if($mon == 8){
                        $currentmonth = "August ".$year ;
                    }else if($mon == 9){
                        $currentmonth = "September ".$year ;
                    }else if($mon == 10){
                        $currentmonth = "October ".$year ;
                    }
                    else if($mon == 11){
                        $currentmonth = "November ".$year ;
                    }
                    else if($mon == 12){
                        $currentmonth = "December ".$year ;
                    }
                    $first = strtotime("first day of ".$currentmonth);
                    $last = strtotime("last day of ".$currentmonth);
                }
                
                $user_attendance_list = $attendance_table->find()->select([ 'u.name' , 'emp_attendance.userid' , 'emp_attendance.login' , 'emp_attendance.logout' , 'emp_attendance.day' , 'emp_attendance.id' ])->join([
                        'u' => [
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' =>  'u.id =  emp_attendance.userid' 
                        ]
                    ])->where(['md5(emp_attendance.userid)' => $userid , 'emp_attendance.day >=' => $first, 'emp_attendance.day <=' => $last   ])->order(['emp_attendance.day' => 'ASC' ])->toArray();
                //, 'date("%M","emp_attendance.day")' => $month 
                $this->set("user_attendance", $user_attendance_list); 
                 $this->set("usersid", $userid); 
                 $this->set("currentmonth", $currentmonth); 
                 $this->set("month", $mon); 
                 $this->set("year", $year); 
                 
               $this->viewBuilder()->setLayout('user');
            }

            public function search()
            {
                
                $month = $this->request->data('month');
                
                if ($month !='' && $month != 0) 
                {
                    $month = $this->request->data('month');
                }
                else
                {
                    $month = 0;
                }

                $year = $this->request->data('year');
                $user = $this->request->data('user');
                
                return $this->redirect('/attendance/view/'.$user.'/'.$month.'/'.$year);

            }


}

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



    



class PaymentController  extends AppController

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

                $balance_table = TableRegistry::get('balance');

                $school_id = $this->request->session()->read('school_id');

		$stdid =  $this->Cookie->read('id')   ;

                $retrieve_balance = $balance_table->find()->select(['id' , 'acc_no', 's_name','s_f_name' , 'bal_amt', 'opening'])->join(['student' => 

                        [

                        'table' => 'student',

                        'type' => 'LEFT',

                        'conditions' => 'student.acc_no = balance.acc_no'

                    ]])->where([ 'balance.school_id '=> $school_id, 'md5(student.id)' => $stdid ])->toArray() ;

                

                $this->set("balance_details", $retrieve_balance); 

                $this->viewBuilder()->setLayout('user');

            }



            public function update(){

                if($this->request->is('ajax') && $this->request->is('post'))

                {

                    $id = $this->request->data['id'];

                    

                    $balance_table = TableRegistry::get('balance');

                    $activ_table = TableRegistry::get('activity');

                    

                    $retrieve_balance = $balance_table->find()->select(['id'])->where(['opening' => 1, 'id' => $id , ])->count() ;

                    

                    if($retrieve_balance == 0 )

                    {

                        $opening = 1;

                        

                        if( $balance_table->query()->update()->set([ 'opening' =>  $opening ])->where([ 'id' => $id  ])->execute())

                        {

                            $activity = $activ_table->newEntity();

                            $activity->action =  "Balance Updated"  ;

                            $activity->ip =  $_SERVER['REMOTE_ADDR'] ;

        

                            $activity->value = md5($id)   ;

                            $activity->origin = $this->Cookie->read('id')   ;

                            $activity->created = strtotime('now');

                            if($saved = $activ_table->save($activity) )

                            {

                                $res = [ 'result' => 'success'  ];

    

                            }

                            else

                            {

                                $res = [ 'result' => 'activity not saved'  ];

                            }

    

                        }

                        else

                        {

                            $res = [ 'result' => 'balance not updated'  ];

                        }

                    } 

                    else

                    {

                        $res = [ 'result' => 'exist'  ];

                    }



                }

                else{

                    $res = [ 'result' => 'invalid operation'  ];



                }





                return $this->json($res);



            }



            public function print($id)

            {   

                $balance_table = TableRegistry::get('balance');

                $school_table = TableRegistry::get('company');

                $school_id = $this->request->session()->read('school_id');



                $this->load->library('M_pdf');



                $retrieve_balance = $balance_table->find()->select(['id' , 'acc_no', 's_name','s_f_name' , 'bal_amt', 'opening'])->where(['md5(id)'=>$id, 'school_id '=> $school_id])->toArray() ;



                $retrieve_school = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name' ])->join([

                    'states' => 

                        [

                        'table' => 'states',

                        'type' => 'LEFT',

                        'conditions' => 'states.id = company.state'

                    ],

                    'cities' => 

                        [

                        'table' => 'cities',

                        'type' => 'LEFT',

                        'conditions' => 'cities.id = company.city'

                    ]

                ])->where(['company.id '=> $school_id])->toArray() ;

                

                $this->set("balance_details", $retrieve_balance);

                $this->set("school_details", $retrieve_school); 

                $this->viewBuilder()->setLayout('user');

            }

             



            public function printfee($id)

            {   

                Configure::write('CakePdf.download', true);

                Configure::write('CakePdf.filename', "Receipt.pdf");



/*

                $this->viewBuilder()

                ->className('Dompdf.Pdf')

                ->layout('Dompdf.default')

                ->options(['config' => [

                    'filename' => "Hello.pdf",

                    'render' => 'browser',

                ]]);*/



                $balance_table = TableRegistry::get('balance');

                $school_table = TableRegistry::get('company');

                $school_id = $this->request->session()->read('school_id');



                $retrieve_balance = $balance_table->find()->select(['balance.id' , 'balance.s_name','balance.s_f_name' , 'balance.bal_amt', 'stdnt.adm_no','stdnt.acc_no','c.c_name','c.c_section'])->join([

                    'stdnt' => 

                        [

                        'table' => 'student',

                        'type' => 'LEFT',

                        'conditions' => 'stdnt.s_name = balance.s_name'

                    ],

                    'c' => 

                        [

                        'table' => 'class',

                        'type' => 'LEFT',

                        'conditions' => 'c.id = stdnt.class'

                    ]

                ])->where(['md5(balance.id)'=>$id, 'balance.school_id '=> $school_id])->first() ;



                $retrieve_school = $school_table->find()->select(['company.id','company.principal_name', 'company.comp_name', 'company.add_1' ,'company.email', 'company.ph_no', 'company.www', 'company.comp_logo', 'states.name' , 'cities.name', 'company.zipcode' ])->join([

                    'states' => 

                        [

                        'table' => 'states',

                        'type' => 'LEFT',

                        'conditions' => 'states.id = company.state'

                    ],

                    'cities' => 

                        [

                        'table' => 'cities',

                        'type' => 'LEFT',

                        'conditions' => 'cities.id = company.city'

                    ]

                ])->where(['company.id '=> $school_id])->first() ;

                

                $this->set("balance_details", $retrieve_balance);

                $this->set("school_details", $retrieve_school);                 



            }



            public function cakePdf()

            {

                $CakePdf = new \CakePdf\Pdf\CakePdf();

                $CakePdf->template('cake_pdf', 'default');

                $CakePdf->viewVars($this->viewVars);

                $pdf = $CakePdf->write(APP . 'Files' . DS . 'Output.pdf');

                echo $pdf;

                die();



            }











}
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
class FetchdatarequestController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
    **/
    protected $connection;

    public function __construct()
    {
        $this->connection = ConnectionManager::get('archive');
    }
    public function index()
    {   
        $attn_table = TableRegistry::get('attendance');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
	
        $retrieve_class = $attn_table->find()->where(['att_date' => '2021-01-04'])->toArray() ;
        print_r($retrieve_class);
        die;
        $retrieve_subjects = $subjects_table->find()->where(['school_id' => $compid, 'status' => 1])->toArray() ;
        $retrieve_session = $session_table->find()->toArray() ;
        $this->set("country_details", $retrieve_country);
        $this->set("subject_details", $retrieve_subjects); 
        $this->set("class_details", $retrieve_class);
        
        $this->set("session_details", $retrieve_session);
        $this->viewBuilder()->setLayout('usersa');
	
    }
    

}

  


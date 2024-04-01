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
class ViewSchoolGalleryController  extends AppController
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
            //$id = $this->request->data['id'];
            $gallery_table = TableRegistry::get('gallery');
            $employee_table = TableRegistry::get('employee');
            $tid = $this->Cookie->read('tid');
		    $this->request->session()->write('LAST_ACTIVE_TIME', time());
			$school_id = $this->request->session()->read('company_id');
            $retrieve_gallery = $gallery_table->find()->where(['status'=> 1, 'school_id' => $school_id ])->order(['id' => 'DESC'])->toArray();
           
            $this->set("gallery_details", $retrieve_gallery); 
            $this->viewBuilder()->setLayout('user');
             
        }
        
       
            
}

  




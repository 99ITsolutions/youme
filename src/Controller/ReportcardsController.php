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
class ReportcardsController  extends AppController
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
                $class_table = TableRegistry::get('class');
                $compid = $this->request->session()->read('company_id');
                if(!empty($compid))
                {
                    $retrieve_class = $class_table->find()->select(['id' ,'c_name','c_section' ,'active' , 'school_sections' ])->where(['school_id' => $compid])->toArray() ;
                    $this->request->session()->write('LAST_ACTIVE_TIME', time());
                    $retrieve_classgrade = $class_table->find()->select(['id' ,'c_name', 'school_sections' ])->where(['school_id' => $compid])->group(['c_name'])->toArray() ;
                    $error = "";
                    $filters = '';
                    if(!empty($_POST))
                    {
                        $filters = "filters";
                        $cname = $this->request->data('grades') ;
                        $sclsectn = $this->request->data('sections') ;
                        $classes = $this->request->data('classes') ;
                        
                        if( ($cname != "") && ($sclsectn != "") && ($classes != "") )
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'c_section' => $classes, 'school_sections' => $sclsectn])->toArray() ;
                        }
                        elseif( ($cname != "") && ($sclsectn != ""))
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname, 'school_sections' => $sclsectn])->toArray() ;
                        }
                        elseif($cname != "")
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid, 'c_name' => $cname])->toArray() ;
                        }
                        else
                        {
                            $retrieve_class = $class_table->find()->where(['school_id' => $compid ])->toArray() ;
                        }
                    }
                    
                    $this->set("filters", $filters); 
                    $this->set("error", $error); 
                    $this->set("grade_details", $retrieve_classgrade); 
                    $this->set("class_details", $retrieve_class); 
                    $this->viewBuilder()->setLayout('user');
                }
                else
                {
                    return $this->redirect('/login/') ;  
                }
            }        

}

  


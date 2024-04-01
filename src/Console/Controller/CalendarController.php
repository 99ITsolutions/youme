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
class CalendarController  extends AppController
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
        $sclid = $this->request->session()->read('company_id');
        $calendar_table = TableRegistry::get('calendar');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if(!empty($sclid)) {
            $retrieve_calendar = $calendar_table->find()->where(['school_id' => $sclid])->toArray() ;
            $this->set("calendar", $retrieve_calendar); 
            $this->viewBuilder()->setLayout('user');
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    public function getevents()
    {   
        $sclid = $this->request->session()->read('company_id');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $calendar_table = TableRegistry::get('calendar');
        if(!empty($sclid)) {
            $retrieve_calendar = $calendar_table->find()->where(['school_id' => $sclid])->toArray() ;
            return $this->json($retrieve_calendar);
        }
		else
		{
		    return $this->redirect('/login/') ;    
		}
    }
    
    
    
			
}

  


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
use Cake\View\ClientHelper;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
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
    public function display(...$path)
    {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
	
	public function form($fid)
    {
		//echo $fid;
		$enquiry_form = TableRegistry::get('enquiry_form');
		$form_fields = TableRegistry::get('enquiry_form_fields');
		
		$retrieve_form = $enquiry_form->find()->select([ 'id', 'title', 'link', 'logo', 'background', 'instruction', 'notes', 'created_date'])->where(['md5(id)' => $fid   ])->toArray(); 
		$retrieve_fields = $form_fields->find()->select([ 'id', 'form_id', 'fields', 'name', 'type', 'options'])->where(['md5(form_id)' => $fid   ])->toArray();

		$this->set("form_details", $retrieve_form);
		$this->set("form_fields", $retrieve_fields);

		$this->viewBuilder()->setLayout('form');
		
	}
	
	public function submitform()
    {
		if($this->request->is('post'))
		{
			//print_r($_POST);
			$form_values = TableRegistry::get('form_values');
						
			$field_values = $form_values->newEntity();					
			$field_values->form_id = $this->request->data('form_id');
			$field_values->unique_id = $this->request->data('unique_id');
			
			
			//print_r($field_values);
			$array = $_POST;
			foreach ($array as $key => $value):
				$table = $form_values->newEntity();
				if ($key != 'form_id' AND $key != 'unique_id' AND $key != '_method' AND $key != '_csrfToken'):
		
				    $table->field_id  = $key;
				   $table->value     = is_array($value) ? implode(',', $value) : $value;
				   $table->form_id   = $array['form_id'];
				   $table->unique_id = $array['unique_id'];
				   $table->created_date = time();
				   $add = $form_values->save($table);
				   
				   if($add)
				   {
					   $this->viewBuilder()->setLayout('submitform');
				   }

				endif;
			 endforeach;
		}
		else
		{
			$res = [ 'result' => 'invalid operation'  ];

		}
	}
	
	
}

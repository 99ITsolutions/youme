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


use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    Public $base_url;

    public function initialize()
    {
        parent::initialize();
        $this->base_url = Router::url("/",false);
        $this->full_url = Router::url("/",true);
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        date_default_timezone_set("Asia/Kolkata");
	

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');


            $cook_var = $this->Cookie->read('atoken');
            $sid = $this->Cookie->read('sid'); 
            $uid = $this->Cookie->read('id');
            $www = $this->Cookie->read('www');
	        $logtype = $this->Cookie->read('logtype');
            $session_id = $this->Cookie->read('sessionid');
         

         $controllerName = $this->request->getParam('controller');
   
         $actionName = $this->request->getParam('action');

         $this->set("baseurl", $this->base_url);
         $this->set("fullurl", $this->full_url);
         
         $baseurl= $this->base_url;
         
         if(($cook_var == "" || $cook_var ==  null  ) &&  $controllerName != "Login" && ($controllerName != "Pages" && $actionName != "form" && $actionName != "submitform" )   ) 
         {
         /*if(($cook_var == "" || $cook_var ==  null || (($uid == "" || $uid == null) && ($sid == "" || $sid == null)) ) &&  $controllerName != "Login" && ($controllerName != "Pages" && $actionName != "form" && $actionName != "submitform" )   ) 
         {*/
            setcookie('atoken', '', time()-1000 , $baseurl  );
            setcookie('id', '', time()-1000  , $baseurl );
        
            return $this->redirect('/login');      
        }
        elseif( $cook_var != "" && $sid != "" )
        { 
           
            /*if($controllerName  == "Login" || ($controllerName == "Pages"  && $actionName != "form" && $actionName != "submitform") )
            {
                return $this->redirect('/dashboard');
            }*/
			
            $users_table = TableRegistry::get('users');
            $company_table = TableRegistry::get('company');
	        $emp_table = TableRegistry::get('employee');
            $student_table = TableRegistry::get('student');	
            $privilage_table = TableRegistry::get('privilages');
	        $session_table = TableRegistry::get('session');

            $encpw = $this->Cookie->read('atoken') ;
            $sesscode = $this->Cookie->read('sesscode') ;
            
                $retrieve_users = $users_table->find()->select(['id' ,'fname' ,'lname' , 'phone'  , 'password'  , 'email', 'sesscode',  'picture','privilages' ])->where(['md5(id)' => $sid ]) ; 
                $user_details = $retrieve_users->toArray() ; 
             
                $this->request->session()->write('users_id', $user_details[0]['id']);
               // $this->request->session()->write('slugs', $company_details[0]['www']);
                $this->request->session()->write('baseurl', $this->base_url);

                if( !empty($user_details[0])  &&  md5($user_details[0]['password']) == $encpw &&  $user_details[0]['sesscode'] == $sesscode )
                {
     
                 
                    if($user_details)
                    {
                        $this->set("user_details", $user_details);

                        if($controllerName == "Dashboard")
                        {
                            $mainpage = "Admin Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;

                            }
                            else{
                                
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                            }

                        }
                        $sidebar_prefix = "";
                        $user_privilage = explode(',',$user_details[0]['privilages']) ;
                        $retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
                        $count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
                                                
                        $spec_cond = md5($user_details[0]['id']) ;
                        
                        $userid = $this->Cookie->read('sid');
                                                 
                        $scl_priv_get = $privilage_table->find()->select(['id' ])->where(['category' => 'School Management' ])->toArray() ;

                        foreach($scl_priv_get as $sclpriv){
                            $school_cat_priv[] = $sclpriv['id'];
                        }

                        $this->set("user_privilage", $user_privilage); 
                        $this->set("school_cat_priv", $school_cat_priv); 
                        $this->set("controllerName", $controllerName);  
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url);   
                    }
                }
                else
                {
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('sid', '', time()-1000  , $baseurl );
                    return $this->redirect('/login');  
                }
            
        }
        elseif( $cook_var != "" && $uid != "" )
        {
           
           /* if($controllerName  == "Login" || ($controllerName == "Pages"  && $actionName != "form" && $actionName != "submitform") )
            {
                return $this->redirect('/schooldashboard');
            }
			*/
            $users_table = TableRegistry::get('users');
            $company_table = TableRegistry::get('company');
	        $emp_table = TableRegistry::get('employee');
            $student_table = TableRegistry::get('student');	
            $privilage_table = TableRegistry::get('privilages');
	        $session_table = TableRegistry::get('session');

            $encpw = $this->Cookie->read('atoken') ;
            $sesscode = $this->Cookie->read('sesscode') ;
                    
            
            if(!empty($www))
            {	
		        $retrieve_company_status = "";  
                $newdate = date('Y-m-d',strtotime('now'));
	
	            if($logtype == md5('Company') )
                {
               	   $retrieve_company = $company_table->find()->select(['id' ,'comp_no' ,'comp_name' , 'ph_no'  , 'password' , 'email' ,'site_title' , 'sesscode' ,'user_name','logout_url' ,'comp_logo' , 'favicon', 'prv_cat'  , 'www' ,'comp_logo1', 'primary_color' , 'button_color' ])->where(['md5(id)' => $uid ]) ; 
                   $company_details = $retrieve_company->toArray() ;  
                
                   $this->request->session()->write('company_id', $company_details[0]['id']);
                   $this->request->session()->write('slugs', $company_details[0]['logout_url']);
                   $this->request->session()->write('baseurl', $this->base_url);
		
	           $retrieve_company_expired = $company_table->find()->select(['expiry_date'])->where([
                                'id' => $company_details[0]['id'] ])->toArray() ;
                    $company_expired = $retrieve_company_expired[0]['expiry_date'];
                            
                    $olddate = date('Y-m-d', strtotime( $company_expired));
            
                
                    $retrieve_company_status = $company_table->find()->select(['id'])->where([
                                'id' => $company_details[0]['id'] , 'status' => 0 ])->count();
		
		   $retrieve_session = $session_table->find()->select(['id' ,'startyear','endyear' ])->order(['startyear' => 'DESC'])->toArray() ;
	       // if($retrieve_company_status == 0 && $olddate >= $newdate )
               if($retrieve_company_status == 0 )
               {


                  if( !empty($company_details[0])  &&  md5($company_details[0]['password']) == $encpw &&  $company_details[0]['sesscode'] == $sesscode ) 
                  {
     		     
	
                    if($company_details)
                    {
                        $this->set("company_details", $company_details);

                        if($controllerName == "Dashboard")
                        {
                            $mainpage = "School Admin Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;

                            }
                            else{
                                
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                            }

                        }
                        $sidebar_prefix = "";
                        $scl_prv_cat = explode(',',$company_details[0]['prv_cat']) ;
                        $retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
                        $count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
                                                
                        $spec_cond = md5($company_details[0]['id']) ;
                        
                        $userid = $this->Cookie->read('id');

                        $this->set("scl_prvs_cat", $scl_prv_cat);
                        $this->set("company_details", $company_details); 
                        $this->set("controllerName", $controllerName);  
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url); 
			$this->set("session_details", $retrieve_session);  
			$this->set('session_id', $this->Cookie->read('sessionid'));

						


                    }
                  }
                  else
                  {
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('id', '', time()-1000  , $baseurl );
                    return $this->redirect([
                                'controller' => 'login?slug='.$company_details[0]['logout_url']
                            ]);  
                  } 
		}
                else
                {  
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
		    setcookie('www', '', time()-10  , $baseurl );
		    		
		    return $this->redirect([
                                'controller' => 'login?slug='.$company_details[0]['logout_url']
                            ]);	
                 }
	     }
           else if($logtype == md5('Employee') )
            {   
                    $emp_details = $emp_table->find()->select(['company.id', 'employee.school_id' ,'employee.id' ,'employee.e_name' , 'employee.password', 'employee.pict' , 'employee.mobile_no'  ,'employee.privilages', 'employee.email','employee.sesscode',  'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'company.primary_color',  'company.button_color' ])->join(['company' => 
                        [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = employee.school_id'
                    ]
                ])->where(['md5(employee.id)' => $uid ])->toArray(); 

                    $this->request->session()->write('company_id', $emp_details[0]['school_id']);                    
                    $this->request->session()->write('teacher_id', $emp_details[0]['id']);
                    $this->request->session()->write('slugs', $emp_details[0]['company']['logout_url']);
                    $this->request->session()->write('baseurl', $this->base_url);
		
		$retrieve_company_status = $company_table->find()->select(['id'])->where([
                                'id' => $emp_details[0]['company']['id'] , 'status' => 0 ])->count();

                $retrieve_company_expired = $company_table->find()->select(['expiry_date'])->where([
                                'id' => $emp_details[0]['company']['id'] ])->toArray() ;
                $company_expired = $retrieve_company_expired[0]['expiry_date'];
                                    
                $olddate = date('Y-m-d', strtotime( $company_expired));	


		if($retrieve_company_status == 0 && $olddate >= $newdate )
                 {  

                    if( !empty($emp_details[0])  &&  md5($emp_details[0]['password']) == $encpw &&  $emp_details[0]['sesscode'] == $sesscode ) 
                    {
         
                        if($emp_details)
                        {
                            $this->set("emp_details", $emp_details);
                            if($controllerName == "Dashboard")
                            {
                                $mainpage = "Employee Dashboard" ;
                                $subpage = $mainpage ;
                            } 
                            else
                            {
                                $mainpage = $controllerName ;
                                if($actionName == "index")
                                {
                                    $subpage = $controllerName." List"  ;

                                }
                                else{
                                    
                                    $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                                }

                            }
                            $sidebar_prefix = "";
                            
		            $emp_privilage = explode(',',$emp_details[0]['privilages']) ;		                                                       
                            $spec_cond = md5($emp_details[0]['id']) ;
                            
                            $userid = $this->Cookie->read('id');
                           
			    $this->set("emp_privilages", $emp_privilage);  
                            $this->set("controllerName", $controllerName);  
                            $this->set("actionName", $actionName);  
                            $this->set("subpage", $subpage); 
                            $this->set("mainpage", $mainpage); 
                            $this->set("sidebar_prefix", $sidebar_prefix);
                            $this->set("baseurl", $this->base_url);   
                        }
                    }
                    else
                    {
                        setcookie('atoken', '', time()-1000   , $baseurl );
                        setcookie('id', '', time()-1000  , $baseurl );
                        return $this->redirect([
                                'controller' => 'login?slug='.$emp_details[0]['company']['logout_url']
                            ]);  
                    } 
		}
                else
                {  
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
		    setcookie('www', '', time()-1000  , $baseurl );
		    		
		    return $this->redirect([
                                'controller' => 'login?slug='.$emp_details[0]['company']['logout_url']
                            ]);	
                 }

                } 
                else if($logtype == md5('Student') )
                {
                    $student_details = $student_table->find()->select(['student.id' ,'student.s_name' , 'student.password' , 'student.pic', 'student.school_id' , 'student.mobile_no', 'student.email','student.sesscode', 'company.site_title' , 'company.comp_logo' , 'company.comp_logo1', 'company.logout_url'  , 'company.favicon', 'student.school_id', 'company.primary_color', 'company.button_color' ])->join(['company' => 
                        [
                        'table' => 'company',
                        'type' => 'LEFT',
                        'conditions' => 'company.id = student.school_id '
                    ]
                ])->where(['md5(student.id)' => $uid ])->toArray(); 
                    
                    $this->request->session()->write('school_id', $student_details[0]['school_id']);                    
                    $this->request->session()->write('student_id', $student_details[0]['id']);
                    $this->request->session()->write('slugs', $student_details[0]['company']['logout_url']);
                    $this->request->session()->write('baseurl', $this->base_url);
		
         	$retrieve_company_status = $company_table->find()->select(['id'])->where([
                                'id' => $student_details[0]['school_id'] , 'status' => 0 ])->count();

                $retrieve_company_expired = $company_table->find()->select(['expiry_date'])->where([
                                'id' => $student_details[0]['school_id'] ])->toArray() ;
                $company_expired = $retrieve_company_expired[0]['expiry_date'];
                                    
                $olddate = date('Y-m-d', strtotime( $company_expired));	

		if($retrieve_company_status == 0 && $olddate >= $newdate )
                 {

                    if( !empty($student_details[0])  &&  md5($student_details[0]['password']) == $encpw &&  $student_details[0]['sesscode'] == $sesscode ) 
                    {
         
                        if($student_details)
                        {
                            $this->set("student_details", $student_details);
                            if($controllerName == "Dashboard")
                            {
                                $mainpage = "Student Dashboard" ;
                                $subpage = $mainpage ;
                            } 
                            else
                            {
                                $mainpage = $controllerName ;
                                if($actionName == "index")
                                {
                                    $subpage = $controllerName." List"  ;

                                }
                                else{
                                    
                                    $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                                }

                            }
                            $sidebar_prefix = "";
                                                                                   
                            $spec_cond = md5($student_details[0]['id']) ;
                            
                            $userid = $this->Cookie->read('id');
                           
                            $this->set("controllerName", $controllerName);  
                            $this->set("actionName", $actionName);  
                            $this->set("subpage", $subpage); 
                            $this->set("mainpage", $mainpage); 
                            $this->set("sidebar_prefix", $sidebar_prefix);
                            $this->set("baseurl", $this->base_url);   
                        }
                    }
                    else
                    {
                        setcookie('atoken', '', time()-1000   , $baseurl );
                        setcookie('id', '', time()-1000  , $baseurl );
			            setcookie('logtype', '', time()-1000  , $baseurl );
   		                setcookie('www', '', time()-1000  , $baseurl );

                        return $this->redirect([
                                'controller' => 'login?slug='.$student_details[0]['company']['logout_url']
                            ]);  
                    } 

		}
                else
                {  
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('id', '', time()-1000  , $baseurl );
                    setcookie('logtype', '', time()-1000  , $baseurl );
		            setcookie('www', '', time()-1000  , $baseurl );
		    		
		            return $this->redirect([
                                'controller' => 'login?slug='.$student_details[0]['company']['logout_url']
                            ]);	
                    }
	
                } 

 	    
            }
           /* else
            {    
            
                $retrieve_users = $users_table->find()->select(['id' ,'fname' ,'lname' , 'phone'  , 'password'  , 'email', 'sesscode',  'picture','privilages' ])->where(['md5(id)' => $uid ]) ; 
                $user_details = $retrieve_users->toArray() ; 
                
                $this->request->session()->write('users_id', $user_details[0]['id']);
               // $this->request->session()->write('slugs', $company_details[0]['www']);
                $this->request->session()->write('baseurl', $this->base_url);

                if( !empty($user_details[0])  &&  md5($user_details[0]['password']) == $encpw &&  $user_details[0]['sesscode'] == $sesscode )
                {
     
                    
                    if($user_details)
                    {
                        $this->set("user_details", $user_details);

                        if($controllerName == "Dashboard")
                        {
                            $mainpage = "Admin Dashboard" ;
                            $subpage = $mainpage ;
                        } 
                        else
                        {
                            $mainpage = $controllerName ;
                            if($actionName == "index")
                            {
                                $subpage = $controllerName." List"  ;

                            }
                            else{
                                
                                $subpage = $controllerName." / ".ucfirst($actionName)." ".$controllerName  ;

                            }

                        }
                        $sidebar_prefix = "";
                        $user_privilage = explode(',',$user_details[0]['privilages']) ;
                        $retrieve_emp = $users_table->find()->select(['id' , 'fname'  ])->where([ 'status' => '1' ])->order(['fname' => 'asc'])->toArray() ;
                        $count_emp = $users_table->find()->select(['id'   ])->where([ 'status' => '1' ])->count() ;     
                                                
                        $spec_cond = md5($user_details[0]['id']) ;
                        
                        $userid = $this->Cookie->read('id');
                                                 
                        $scl_priv_get = $privilage_table->find()->select(['id' ])->where(['category' => 'School Management' ])->toArray() ;

                        foreach($scl_priv_get as $sclpriv){
                            $school_cat_priv[] = $sclpriv['id'];
                        }

                        $this->set("user_privilage", $user_privilage); 
                        $this->set("school_cat_priv", $school_cat_priv); 
                        $this->set("controllerName", $controllerName);  
                        $this->set("actionName", $actionName);  
                        $this->set("subpage", $subpage); 
                        $this->set("mainpage", $mainpage); 
                        $this->set("sidebar_prefix", $sidebar_prefix);
                        $this->set("baseurl", $this->base_url);   
                    }
                }
                else
                {
                    setcookie('atoken', '', time()-1000   , $baseurl );
                    setcookie('id', '', time()-1000  , $baseurl );
                    return $this->redirect('/login');  
                }
            }*/
            
        }
    }

    public function json($data){
        $this->response->type('json');
        $this->response->body(json_encode($data));
        return $this->response;
    }

		

}

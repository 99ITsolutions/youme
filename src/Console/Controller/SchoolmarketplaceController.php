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

class SchoolmarketplaceController  extends AppController
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
        $dealer_table = TableRegistry::get('dealers');
        $category_table = TableRegistry::get('categories');
        $retrieve_dealer = $dealer_table->find()->where(['status' => 1])->toArray() ;
        $retrieve_categories = $category_table->find()->where(['status' => 1])->order(['name' => 'ASC'])->toArray() ;
        $products_table = TableRegistry::get('market_place_product');
        $retrieve_products = $products_table->find()->toArray();
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        foreach ($retrieve_products as $value) {
            
            $catid = $value['category_id'];
            $dealerid = $value['dealer_id'];
            
            $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
            $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
            
            $value->catname = $retrieve_category['name'];
            $value->dealname = $retrieve_dealer['name'];
        }
        $this->set("dealers", $retrieve_dealer);
        $this->set("products", $retrieve_products);
        $this->set("categories", $retrieve_categories);
        $this->viewBuilder()->setLayout('user');
    }
    
    public function getdealers()
    {
        $id = $this->request->data('catid');
        $dealers_table = TableRegistry::get('dealers');
        $categories_table = TableRegistry::get('categories');
        $products_table = TableRegistry::get('market_place_product');
        $category_table = TableRegistry::get('categories');
        $dealer_table = TableRegistry::get('dealers');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        
        if($id == "all")
        {
            $catss = "all";
            $data = "";
            $dealer = '';
            $retrieve_products = $products_table->find()->toArray();
            foreach ($retrieve_products as $value) 
            {
                
                $catid = $value['category_id'];
                $dealerid = $value['dealer_id'];
                
                $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
                $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
                
                $catname = $retrieve_category['name'];
                $dealname = $retrieve_dealer['name'];
                
                $conatct = '<button type="button" data-id='.$value['id'].' data-prodname ="'. $value['product_name'] .'" data-logo="'. $value['product_image'] .'" class="btn btn-sm btn-secondary contactform" data-toggle="modal" title="Contact Us">Contact Us</button>';
                
                $data .=  '<tr>
                        <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                        <td><span>$'.$value['price'].'</span></td>
                        <td><span>'.$value['quantity'].'</span></td>
                       
                        <td>'.$conatct.'</td>
                    </tr>';
            }
        }
        else
        {
            $catss = "noall";
            $retrieve_dealer = $dealers_table->find()->where(['categories LIKE' => '%'.$id.'%' ])->toArray() ;
            
            $data = "";
            $dealer = '';
            $dealer .= '<option value="">Choose Dealer</option>';
            $dealer .= '<option value="all">All</option>';
            foreach($retrieve_dealer as $dealr)
            {
                $dealer .= '<option value="'.$dealr['id'].'">'.$dealr['name'].'</option>';
                
            }
            
            $retrieve_products = $products_table->find()->where(['category_id' => $id])->toArray();
            foreach ($retrieve_products as $value) 
            {
                
                $catid = $value['category_id'];
                $dealerid = $value['dealer_id'];
                
                $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
                $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
                
                $catname = $retrieve_category['name'];
                $dealname = $retrieve_dealer['name'];
                
                $conatct = '<button type="button" data-id='.$value['id'].' data-prodname ="'. $value['product_name'] .'" data-logo="'. $value['product_image'] .'" class="btn btn-sm btn-secondary contactform" data-toggle="modal" title="Contact Us">Contact Us</button>';
                
                $data .=  '<tr>
                        <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                        <td><span>$'.$value['price'].'</span></td>
                        <td><span>'.$value['quantity'].'</span></td>
                        <td>'.$conatct.'</td>
                    </tr>';
            }
        }
        
        $listing['dataa'] = $data;
        $listing['dealer'] = $dealer;
        $listing['catss'] = $catss;
        return $this->json($listing);
    }
    
    public function getcategory()
    {
        $id = $this->request->data('dealerid');
        $dealers_table = TableRegistry::get('dealers');
        $categories_table = TableRegistry::get('categories');
        $retrieve_dealer = $dealers_table->find()->where(['id' => $id, 'status' => 1])->first() ;
        $categories = explode(",", $retrieve_dealer['categories']);
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $data = "";
        $data .= '<option value="">Choose Category</option>';
        foreach($categories as $cat)
        {
            $retrieve_categories = $categories_table->find()->where(['id' => $cat ])->first() ;
            $data .= '<option value="'.$retrieve_categories['id'].'">'.$retrieve_categories['name'].'</option>';
            
        }
        return $this->json($data);
    }

    public function getproducts()
    {
        $did = $this->request->data('dealerid');
        $cid = $this->request->data('categoryid');
        $category_table = TableRegistry::get('categories');
        $dealer_table = TableRegistry::get('dealers');
        $product_table = TableRegistry::get('market_place_product');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        if($did != "all")
        {
            $retrieve_products = $product_table->find()->where(['dealer_id' => $did, 'category_id' => $cid])->toArray() ;
        }
        else
        {
            $retrieve_products = $product_table->find()->where([ 'category_id' => $cid])->toArray() ;
        }
        
        $datapro = "";
        $product = array();
        $datapro .= '<option value="">Choose Products</option>';
        foreach($retrieve_products as $val)
        {
           $datapro .= '<option value="'.$val['id'].'">'.$val['product_name'].'</option>';
        }
        
        $data = "";
        foreach ($retrieve_products as $value) 
        {
            
            $catid = $value['category_id'];
            $dealerid = $value['dealer_id'];
            
            $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
            $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
            
            $catname = $retrieve_category['name'];
            $dealname = $retrieve_dealer['name'];
            
            $conatct = '<button type="button" data-id='.$value['id'].' data-prodname ="'. $value['product_name'] .'" data-logo="'. $value['product_image'] .'" class="btn btn-sm btn-secondary contactform" data-toggle="modal" title="Contact Us">Contact Us</button>';
                
            $data .=  '<tr>
                    <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                    <td><span>$'.$value['price'].'</span></td>
                    <td><span>'.$value['quantity'].'</span></td>
                    <td>'.$conatct.'</td>
                </tr>';
        }
        
        $product['list'] = $datapro;
        $product['products'] = $data;
        return $this->json($product);
    }
    
    public function getproductslist()
    {
        $pid = $this->request->data('productid');
        $did = $this->request->data('dealerid');
        $cid = $this->request->data('categoryid');
        $product_table = TableRegistry::get('market_place_product');
        $category_table = TableRegistry::get('categories');
        $dealer_table = TableRegistry::get('dealers');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $retrieve_products = $product_table->find()->where(['id' => $pid, 'dealer_id' => $did, 'category_id' => $cid])->toArray() ;
        
        $data = "";
        $datavalue = array();

        foreach ($retrieve_products as $value) {
            
            $catid = $value['category_id'];
            $dealerid = $value['dealer_id'];
            
            $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
            $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
            
             $catname = $retrieve_category['name'];
             $dealname = $retrieve_dealer['name'];
            
            $conatct = '<button type="button" data-id='.$value['id'].' data-prodname ="'. $value['product_name'] .'" data-logo="'. $value['product_image'] .'" class="btn btn-sm btn-secondary contactform" data-toggle="modal" title="Contact Us">Contact Us</button>';
            
            $data .=  '<tr>
                    <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                    <td><span>$'.$value['price'].'</span></td>
                    <td><span>'.$value['quantity'].'</span></td>
                    <td>'.$conatct.'</td>
                </tr>';
        }
        $datavalue['html']= $data;
        return $this->json($datavalue);
    }


    public function marketprocontact()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {  
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            //$to = "nancy@outsourcingservicesusa.com";
            $to = "contactus@you-me-globaleducation.org";
                $username =  $this->request->data('name');
                $from =  $this->request->data('email');
                $quantity =  $this->request->data('prodquantity');
                $number =  $this->request->data('number');
                $desc = $this->request->data('desc');
                $prodid = $this->request->data('prodcid');
                
                $product_table = TableRegistry::get('market_place_product');
                $category_table = TableRegistry::get('categories');
                $dealer_table = TableRegistry::get('dealers');
                
                $retrieve_products = $product_table->find()->where(['id' => $prodid])->first() ;
                
                $catid = $retrieve_products['category_id'];
                $dealerid = $retrieve_products['dealer_id'];
                $prodname = $retrieve_products['product_name'];
                $proprice = $retrieve_products['price'];
                
                $retrieve_categories = $category_table->find()->where(['id' => $catid])->first() ;
                $retrieve_dealers = $dealer_table->find()->where(['id' => $dealerid])->first() ;
                
                $catname = $retrieve_categories['name'];
                $dealername = $retrieve_dealers['name'];
                $dname = $retrieve_dealers['fname']." ".$retrieve_dealers['lname'];
                $dealemail = $retrieve_dealers['email'];
                $dealcontact = $retrieve_dealers['phone_no'];
            
            
            $name = "You-Me Global Education";
            $subject = 'Mail regarding for Market Place Product - You-Me Global Education';
            
            $htmlContent = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                <multiline>
                    This mail is to notify you that one of your users shows interest in ordering something. Below are the details of his/her order status. 
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px;  text-align:left;">
                    <multiline>
                    <p>Name: '.$username.' </p>
                    <p>Email: '.$from.' </p>
                    <p>Contact Number: '.$number.' </p>
                    <p>Quantity: '.$quantity.' </p>
                    <p>Description: '.$desc.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; padding-bottom:10px;  text-align:left;">
                    <multiline><b>Product Details</b> </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                    <p>Product Name: '.$prodname.' </p>
                    <p>Category: '.$catname.' </p>
                    <p>Price: $'.$proprice.' </p>
                    <p>Dealer Company Name: '.$dealername.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; padding-bottom:10px; text-align:left;">
                    <multiline><b>Shopper\'s Details</b> </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                    <p>Dealer Name: '.$dname.' </p>
                    <p>Email: '.$dealemail.' </p>
                    <p>Contact Number: '.$dealcontact.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                        <p>Thanks</p>
                        <p>'.$username.'</p>
                    </multiline>
                </td>
            </tr>';
            
            $htmlContentuser = '
            <tr>
            <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                <multiline>
                    This mail is to notify you that you have shown interest in ordering something. Below are the details of his/her order status and our support team will contact you within 24-48 hours.
                </multiline>
            </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; padding-bottom:10px;  text-align:left;">
                    <multiline><b>Product Details</b> </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                    <p>Product Name: '.$prodname.' </p>
                    <p>Category: '.$catname.' </p>
                    <p>Price: $'.$proprice.' </p>
                    <p>Dealer Company Name: '.$dealername.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; padding-bottom:10px; text-align:left;">
                    <multiline><b>Shopper\'s Details</b> </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                    <p>Dealer Name: '.$dname.' </p>
                    <p>Email: '.$dealemail.' </p>
                    <p>Contact Number: '.$dealcontact.' </p>
                    </multiline>
                </td>
            </tr>
            <tr>
                <td class="text" style="color:#191919; font-size:16px; text-align:left;">
                    <multiline>
                        <p>Thanks</p>
                        <p>'.$name.'</p>
                    </multiline>
                </td>
            </tr>';


             $message = '<p>This mail is to notify you that one of your users shows interest in ordering something. Below are the details of his/her order status. </p>
                            <p>Name: '.$username.' </p>
                            <p>Email: '.$from.' </p>
                            <p>Contact Number: '.$number.' </p>
                            <p>Quantity: '.$quantity.' </p>
                            <p>Description: '.$desc.' </p>
                            <p><b>Product Details</b></p>
                            <p>Product Name: '.$prodname.' </p>
                            <p>Category: '.$catname.' </p>
                            <p>Price: '.$proprice.' </p>
                            <p>Dealer Company Name: '.$dealername.' </p>
                            <p><b>Shopper\'s Details</b></p>
                            <p>Dealer Name: '.$dname.' </p>
                            <p>Email: '.$dealemail.' </p>
                            <p>Contact Number: '.$dealcontact.' </p>';
                            
            $sendmail = $this->sendEmailwithoutattach($to,$from,$username,$subject,$htmlContent,$name,'formalmessage');
            
            $sendusermail = $this->senduserEmailwithoutattach($from,$to,$name,$subject,$htmlContentuser,$username,'formalmessage');
            
            
            $queries_table = TableRegistry::get('market_place_queries');
            $queries = $queries_table->newEntity();
            
            $queries->name =  $username  ;
            $queries->description =  $message ;
            $queries->created_date = strtotime('now');
            $save = $queries_table->save($queries) ;
            if($save)
            {   
                $res = [ 'result' => 'success'  ];
            }
            else
            {
                $res = ['result' => 'failed'];
            }
            
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }

}

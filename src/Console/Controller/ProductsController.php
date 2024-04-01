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

class ProductsController  extends AppController
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
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $dealers_table = TableRegistry::get('dealers');
        $categories_table = TableRegistry::get('categories');
        $retrieve_dealer = $dealers_table->find()->where(['status' => 1])->toArray() ;
        $retrieve_categories = $categories_table->find()->where(['status' => 1])->order(['name' => 'ASC'])->toArray() ;
        $this->set("dealers", $retrieve_dealer);
        $this->set("categories", $retrieve_categories);
        $this->viewBuilder()->setLayout('usersa');
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
            $retrieve_products = $products_table->find()->toArray();
            foreach ($retrieve_products as $value) 
            {
                
                $catid = $value['category_id'];
                $dealerid = $value['dealer_id'];
                
                $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
                $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
                
                $catname = $retrieve_category['name'];
                $dealname = $retrieve_dealer['name'];
                
                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editproduct" data-toggle="modal"  data-target="#editproduct" title="Edit"><i class="fa fa-edit"></i></button>';
                $delete = '<button type="button" data-url="products/delete" data-id='. $value['id'].' data-str="Product" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
            
                $data .=  '<tr>
                        <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                        <td><span>$'.$value['price'].'</span></td>
                        <td><span>'.$value['quantity'].'</span></td>
                        <td><span>'.date('d M, Y',$value['created_date']).'</span></td>
                        <td>'.$edit.$delete.'</td>
                    </tr>';
            }
        }
        else
        {
            $catss = "noall";
            $retrieve_dealer = $dealers_table->find()->where(['categories LIKE' => '%'.$id.'%' ])->toArray() ;
            
            $data = "";
            $data .= '<option value="">Choose Dealer</option>';
            $data .= '<option value="all">All</option>';
            foreach($retrieve_dealer as $dealr)
            {
                $data .= '<option value="'.$dealr['id'].'">'.$dealr['name'].'</option>';
                
            }
        }
        
        $listing['dataa'] = $data;
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
            
            $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editproduct" data-toggle="modal"  data-target="#editproduct" title="Edit"><i class="fa fa-edit"></i></button>';
            $delete = '<button type="button" data-url="products/delete" data-id='. $value['id'].' data-str="Product" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
        
            $data .=  '<tr>
                    <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                    <td><span>$'.$value['price'].'</span></td>
                    <td><span>'.$value['quantity'].'</span></td>
                    <td><span>'.date('d M, Y',$value['created_date']).'</span></td>
                    <td>'.$edit.$delete.'</td>
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
            
            $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editproduct" data-toggle="modal"  data-target="#editproduct" title="Edit"><i class="fa fa-edit"></i></button>';
            $delete = '<button type="button" data-url="products/delete" data-id='. $value['id'].' data-str="Product" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
        
            $data .=  '<tr>
                    <td><span><img src="'.$this->base_url.'/productimages/'.$value['product_image'].'" width="70px" height="70px"></span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                    <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                    <td><span>$'.$value['price'].'</span></td>
                    <td><span>'.$value['quantity'].'</span></td>
                    <td><span>'.date('d M, Y',$value['created_date']).'</span></td>
                    <td>'.$edit.$delete.'</td>
                </tr>';
        }
        $datavalue['html']= $data;
        return $this->json($datavalue);
    }


    public function addproduct()
    {
        if ($this->request->is('ajax') && $this->request->is('post') )
        {
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $product_table = TableRegistry::get('market_place_product');
            $activ_table = TableRegistry::get('activity');
        
            $retrieve_product = $product_table->find()->select(['id'])->where(['product_name' => $this->request->data('name')  ])->count() ;
            
            if(!empty($this->request->data['proimage']['name']))
            {
                if($this->request->data['proimage']['type'] == "image/png" || $this->request->data['proimage']['type'] == "image/jpeg"  || $this->request->data['proimage']['type'] == "image/jpg" )
                {
                    
                    $coverimg = $this->request->data['proimage']['name'];
                    $uploadpath = 'productimages/';
                    $uploadfile = $uploadpath.$coverimg;
                    if(move_uploaded_file($this->request->data['proimage']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['proimage'] = $coverimg; 
                    }
                }   
                else
                {
                    $coverimg = "";
                }
            }
            else
            {
                $coverimg = "";
            }
            if(!empty($coverimg))
            {
                if($retrieve_product == 0 ){
        
                    $products = $product_table->newEntity();
                    $products->product_name =  $this->request->data('name');
                    $products->category_id =  $this->request->data('dealrcat');
                    $products->dealer_id =  $this->request->data('dealers');
                    $products->price =  $this->request->data('price');
                    $products->quantity =  $this->request->data('quantity');
                    $products->status = 1;
                    $products->created_date = strtotime('now');
                    $products->product_image = $coverimg;
                    
                    
                    
                    if($lang != "") { $lang = $lang; } else { $lang = 2; }
                    
                    $language_table = TableRegistry::get('language_translation');
                    if($lang == 1)
                    {
                        $retrieve_langlabel = $language_table->find()->select(['id', 'english_label'])->toArray() ; 
                        
                        foreach($retrieve_langlabel as $langlabel)
                        {
                            $langlabel->title = $langlabel['english_label'];
                        }
                    }
                    else
                    {
                        $retrieve_langlabel = $language_table->find()->select(['id', 'french_label'])->toArray() ; 
                        foreach($retrieve_langlabel as $langlabel)
                        {
                            $langlabel->title = $langlabel['french_label'];
                        }
                    }
                    
                    foreach($retrieve_langlabel as $langlbl) 
                    { 
                        if($langlbl['id'] == '1631') { $prodimg = $langlbl['title'] ; } 
                    } 
                    
                    if($saved = $product_table->save($products) )
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Product Created"  ;
                        $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
        
                        $activity->value = md5($saved->id)   ;
                        $activity->origin = $this->Cookie->read('id')   ;
                        $activity->created = strtotime('now');
                        if($saved = $activ_table->save($activity) ){
                            $res = [ 'result' => 'success'  ];
                        }
                        else
                        {
                            $res = [ 'result' => 'activity'  ];
                        }
                    }
                    else
                    {
                        $res = [ 'result' => 'error'  ];
                    }
                } 
                else
                {
                    $res = [ 'result' => 'exist'  ];
                }    
            }
            else
            {
                $res = [ 'result' => $prodimg ];
            }
        }
        else
        {
            $res = [ 'result' => 'invalid operation'  ];
        }
        return $this->json($res);
    }
            
		

    public function getdata()
    {
        if ($this->request->is('ajax') && $this->request->is('post'))
        {
            $category_table = TableRegistry::get('categories');
            $dealer_table = TableRegistry::get('dealers');
            $products_table = TableRegistry::get('market_place_product');
            $retrieve_products = $products_table->find()->toArray();
            $data = "";
            $datavalue = array();
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            foreach ($retrieve_products as $value) {
                
                $catid = $value['category_id'];
                $dealerid = $value['dealer_id'];
                
                $retrieve_category = $category_table->find()->where(['id' => $catid])->first();
                $retrieve_dealer = $dealer_table->find()->where(['id' => $dealerid])->first();
                
                 $catname = $retrieve_category['name'];
                 $dealname = $retrieve_dealer['name'];
                
                $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editproduct" data-toggle="modal"  data-target="#editproduct" title="Edit"><i class="fa fa-edit"></i></button>';
                $delete = '<button type="button" data-url="products/delete" data-id='. $value['id'].' data-str="Product" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
            
                $data .=  '<tr>
                        <td><span>
                        <a class="example-image-link" href="'.$this->base_url.'productimages/'.$value['product_image'].'" data-lightbox="example-1"><img class="example-image" src="'.$this->base_url.'productimages/'.$value['product_image'].'" alt="image-1"  width="70px" height="70px"  /></a>
                        </span></td>
                        
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($value['product_name']).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($dealname).'</span></td>
                        <td><span class="mb-0 font-weight-bold">'.ucfirst($catname).'</span></td>
                        <td><span>$'.$value['price'].'</span></td>
                        <td><span>'.$value['quantity'].'</span></td>
                        <td><span>'.date('d M, Y',$value['created_date']).'</span></td>
                        <td>'.$edit.$delete.'</td>
                    </tr>';
            }
            $datavalue['html']= $data;
            return $this->json($datavalue);
        }
    }

	
    public function update()
    {   
        if($this->request->is('ajax') && $this->request->is('post'))
        {
            $id = $this->request->data['id'];
            $categories_table = TableRegistry::get('categories');
            $products_table = TableRegistry::get('market_place_product');
            $dealers_table = TableRegistry::get('dealers');
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $products = $products_table->find()->where(['id' => $id])->first();
            
            $dealerid = $products['dealer_id'];
            $retrieve_dealer = $dealers_table->find()->where(['id' => $dealerid, 'status' => 1])->first() ;
            $categories = explode(",", $retrieve_dealer['categories']);
            
            $data = "";
            $data .= '<option value="">Choose Category</option>';
            foreach($categories as $cat)
            {
                $retrieve_categories = $categories_table->find()->where(['id' => $cat ])->first() ;
                if( $products['category_id'] == $retrieve_categories['id'])
                {
                    $sel = "selected";
                }
                else
                {
                    $sel = "";
                }
                $data .= '<option value="'.$retrieve_categories['id'].'" '.$sel.'>'.$retrieve_categories['name'].'</option>';
            }
            
            $productdata['products'] = $products;
            $productdata['categories'] = $data;
            
            return $this->json($productdata);
        }  
    }	

    public function editproduct(){
        if ($this->request->is('ajax') && $this->request->is('post')){
            $this->request->session()->write('LAST_ACTIVE_TIME', time());
            $product_table = TableRegistry::get('market_place_product');
            $activ_table = TableRegistry::get('activity');
            
            $retrieve_product = $product_table->find()->select(['id'])->where(['product_name' => $this->request->data('name') , 'id IS NOT' => $this->request->data('id')  ])->count() ;
            if(!empty($this->request->data['eproimage']['name']))
            {
                if($this->request->data['eproimage']['type'] == "image/png" || $this->request->data['eproimage']['type'] == "image/jpeg"  || $this->request->data['eproimage']['type'] == "image/jpg" )
                {
                    
                    $coverimg = $this->request->data['eproimage']['name'];
                    $uploadpath = 'productimages/';
                    $uploadfile = $uploadpath.$coverimg;
                    if(move_uploaded_file($this->request->data['eproimage']['tmp_name'], $uploadfile))
                    {
                        $this->request->data['eproimage'] = $coverimg; 
                    }
                }   
                else 
                {
                    $coverimg = "";
                }
            }
            elseif(!empty($this->request->data('prodimg')))
            {
                $coverimg = $this->request->data('prodimg') ;
            }
            else
            {
                $coverimg = "";
            }    
            
                if($retrieve_product == 0 )
                {
                    $id = $this->request->data('id');
	                $product_name =  $this->request->data('name');
                    $category_id =  $this->request->data('dealrcat');
                    $dealer_id =  $this->request->data('dealers');
                    $price =  $this->request->data('price');
                    $quantity =  $this->request->data('quantity');
                    $status = 1;
                    $created_date = strtotime('now');
                    
                    if( $product_table->query()->update()->set(['product_image' => $coverimg, 'dealer_id' => $dealer_id, 'status' => $status, 'quantity' => $quantity, 'price' => $price, 'product_name' => $product_name, 'category_id' => $category_id])->where([ 'id' => $id  ])->execute())
                    {
                        $activity = $activ_table->newEntity();
                        $activity->action =  "Product Updated"  ;
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
                            $res = [ 'result' => 'activity'  ];
                        }

                    }
                    else
                    {
                        $res = [ 'result' => 'error'  ];
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



    public function delete()
    {
        $rid = $this->request->data('val') ;
        $products_table = TableRegistry::get('market_place_product');
        $activ_table = TableRegistry::get('activity');
        $this->request->session()->write('LAST_ACTIVE_TIME', time());
        $catid = $products_table->find()->select(['id'])->where(['id'=> $rid ])->first();
        
        if($catid)
        {                       
            $del = $products_table->query()->delete()->where([ 'id' => $rid ])->execute(); 
            if($del)
            {
                $activity = $activ_table->newEntity();
                $activity->action =  "Product Deleted"  ;
                $activity->ip =  $_SERVER['REMOTE_ADDR'] ;
                $activity->value = md5($rid)    ;
                $activity->origin = $this->Cookie->read('id')   ;
                $activity->created = strtotime('now');

                if($saved = $activ_table->save($activity) )
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
                $res = [ 'result' => 'not delete'  ];
            }    
        }
        else
        {
            $res = [ 'result' => 'error'  ];
        }

        return $this->json($res);
    }

}

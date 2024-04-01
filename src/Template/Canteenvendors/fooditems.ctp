<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2207') { echo $langlbl['title'] ; } } ?></h2>
                    <h2 class="align-right col-md-6">
                        <a href="<?= $baseurl ?>Canteenvendors/assignfood" title="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2211') { echo $langlbl['title'] ; } } ?>" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2211') { echo $langlbl['title'] ; } } ?></a>
                        <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addfood"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2206') { echo $langlbl['title'] ; } } ?></a>
                        <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                    </h2>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem fitem_table" id="fitem_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?></th>
                                <th>Image</th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2229') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '110') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="fitembody" class="modalrecdel"> 
                        <?php foreach($food_details as $value) {
                        $edit = '<button type="button" data-id='.$value['id'].' class="btn btn-sm btn-outline-secondary editfood" data-toggle="modal"  data-target="#editfood" title="Edit"><i class="fa fa-edit"></i></button>';
					    $delete = '<button type="button" data-url="deleteitem" data-id='.$value['id'].' data-str="Food Item" class="btn btn-sm btn-outline-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fa fa-trash-o"></i></button>';
    		        
                        echo '<tr>
                                
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['food_name'].'</span>
                                </td>
                                <td>
                                    <img src="../c_food/'.$value['food_img'].'" width="50px" />
                                </td>
                                <td>
                                    <span class="mb-0 font-weight-bold">'.$value['details'].'</span>
                                </td>
                                <td>
    							'.$edit.$delete.'
                                </td>
                            </tr>';
                        } ?>
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

 <!------------------ Add Class --------------------->

    
<div class="modal classmodal animated zoomIn" id="addfood" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2206') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addfood'] , 'id' => "addfoodform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="food_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2209') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" required name="food_img" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2209') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2229') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <textarea class="form-control" required name="food_detail" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2229') { echo $langlbl['title'] ; } } ?>*"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="fierror"></div>
                        <div class="success" id="fisuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addfibtn" id="addfibtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '116') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '117') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



    <!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editfood"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2210') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editfood'] , 'id' => "editfoodform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label>
                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?>
                        </label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" id="efood_name" required name="efood_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2208') { echo $langlbl['title'] ; } } ?>*">
                            <input type="hidden" id="eid"  name="eid" >
                            <input type="hidden" id="efimg"  name="efimg" >
                        </div>
                    </div>
		            <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2209') { echo $langlbl['title'] ; } } ?></label>
                        <span id="foodimage"></span>
                        <div class="form-group">                                    
                            <input type="file" class="form-control" id="efood_img" name="efood_img" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2209') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2229') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <textarea class="form-control" required name="efood_detail" id="efood_detail" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2229') { echo $langlbl['title'] ; } } ?>*"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="efierror"></div>
                        <div class="success" id="efisuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editfibtn" id="editfibtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '117') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

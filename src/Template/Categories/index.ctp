
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '792') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addcategory"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '793') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem category_table" id="category_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '797') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '798') { echo $langlbl['title'] ; } } ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categorybody" class="modalrecdel">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!------------------ Extra HTML --------------------->

    
<div class="modal animated zoomIn" id="addcategory"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '800') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addcategory'] , 'id' => "addcategoryform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
		            <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '801') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '801') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="caterror"></div>
                        <div class="success" id="catsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addcategbtn" id="addcategbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '802') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '803') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                </div>
                
            <?php echo $this->Form->end(); ?>
            </div>
             
        </div>
    </div>
</div>




    <!------------------ Edit Session --------------------->

    
<div class="modal animated zoomIn" id="editcategory"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1414') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editcategory'] , 'id' => "editcategoryform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="id"  name="id" >
		            <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '801') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="category_name" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '801') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="caterror"></div>
                        <div class="success" id="catsuccess"></div>
                    </div>
                    
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editcatbtn" id="editcatbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '803') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
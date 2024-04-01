
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '323') { echo $langlbl['title'] ; } } ?> </h2>
                            <ul class="header-dropdown">
                                <?php if(!empty($sclsub_details[0]))
                                { 
                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                    if(in_array("34", $roles)) { ?>
                                        <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeehead"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '327') { echo $langlbl['title'] ; } } ?> </a></li>
                                    <?php }
                                } else { ?>
                                    <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addfeehead"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '327') { echo $langlbl['title'] ; } } ?> </a></li>
                                <?php } ?>
                                
                                <li><a href="<?=$baseurl?>fees"  class="btn btn-info" title="Back"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '328') { echo $langlbl['title'] ; } } ?> </a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem feehead_table" id="feehead_table" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '331') { echo $langlbl['title'] ; } } ?> </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '332') { echo $langlbl['title'] ; } } ?> </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '333') { echo $langlbl['title'] ; } } ?> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="feeheadbody" class="modalrecdel">
                
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!------------------ Add Fee Head  --------------------->

    
<div class="modal animated zoomIn" id="addfeehead"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1549') { echo $langlbl['title'] ; } } ?></h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>

            </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addfeehead'] , 'id' => "addfeeheadform" , 'method' => "post"  ]); ?>

			<div class="row clearfix">
				
				<div class="col-md-12">
					<div class="form-group">                                    
						<input type="text" class="form-control" required name="head_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1551') { echo $langlbl['title'] ; } } ?>">
					</div>
				</div>
				<div class="col-md-12">
					<div class="error" id="feeerror"></div>
					<div class="success" id="feesuccess"></div>
				</div>
				<div class="button_row" >
					<hr>
					<button type="submit" class="btn btn-primary" id="addfeeheadbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></button>
					<button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
				</div>
						
					   <?php echo $this->Form->end(); ?>
					   
					</div>
				</div>
             
        </div>
    </div>
</div>



    <!------------------ Edit Fee Head --------------------->

    
<div class="modal animated zoomIn" id="editfeehead" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1550') { echo $langlbl['title'] ; } } ?></h6>
		<button type="button" class="close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
         	</button>

            </div>
            <div class="modal-body">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editfeehead'] , 'id' => "editfeeheadform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <div class="form-group">                                    
                            <input type="text" class="form-control" id="head_name" required name="head_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1551') { echo $langlbl['title'] ; } } ?>">
                            <input type="hidden" id="id"  name="id" >
                        </div>
                    </div>
					
                    <div class="col-md-12">
                        <div class="error" id="efeeerror"></div>
                        <div class="success" id="efeesuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary" id="editfeeheadbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
        
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>
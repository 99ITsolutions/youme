<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="heading col-md-6"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '10') { echo $langlbl['title'] ; } } ?></h2>
                    <h2 class="align-right col-md-6">
                        <?php if(!empty($sclsub_details[0]))
                        { 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                            if(in_array("4", $roles)) { ?>
                                <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addsubject"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '104') { echo $langlbl['title'] ; } } ?></a>
                            <?php }
                        } else { ?>
                            <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addsubject"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '104') { echo $langlbl['title'] ; } } ?></a>
                        <?php } ?>
                        
                        <a  href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a>
                    </h2>
                </div>
                
               
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem subjects_table" id="subjects_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '109') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '111') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '110') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="subjectsbody" class="modalrecdel"> 
                       
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

 <!------------------ Add Class --------------------->

    
<div class="modal classmodal animated zoomIn" id="addsubject" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '115') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addsub'] , 'id' => "addsubform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '114') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" required name="subject_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '109') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="error" id="suberror"></div>
                        <div class="success" id="subsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addsubbtn" id="addsubbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '116') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '117') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              



    <!------------------ Edit Class --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editsub"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1525') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editsub'] , 'id' => "editsubform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    
                    <div class="col-md-12">
                        <label>
                            <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '114') { echo $langlbl['title'] ; } } ?>
                        </label>
                        <div class="form-group">                                    
                            <input type="text" class="form-control" id="esubject_name" required name="esubject_name" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '109') { echo $langlbl['title'] ; } } ?>*">
                            <input type="hidden" id="eid"  name="eid" >
                        </div>
                    </div>
		            
                    <div class="col-md-12">
                        <div class="error" id="editsuberror"></div>
                        <div class="success" id="editsubsuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editsubbtn" id="editsubbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '117') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

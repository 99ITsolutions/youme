<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '27') { echo $langlbl['title'] ; } } ?>    </h2>
                <ul class="header-dropdown">
                    <?php if(!empty($sclsub_details[0]))
                    { 
                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                        if(in_array("48", $roles)) { ?>
                            <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addgallery"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '407') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } if(in_array("50", $roles)) { ?>   
                            <li><a href="<?=$baseurl?>gallery/view" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '408') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php }
                    } else { ?>
                        <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addgallery"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '407') { echo $langlbl['title'] ; } } ?></a></li>
                        <li><a href="<?=$baseurl?>gallery/view" title="Add" class="btn btn-info"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '408') { echo $langlbl['title'] ; } } ?></a></li>
                    <?php } ?>
                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                </ul>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem gallery_table" id="gallery_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '403') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '402') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '410') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="gallerybody" class="modalrecdel"> 
                       
	                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

 <!------------------ Add Class --------------------->


<div class="modal classmodal animated zoomIn" id="addgallery" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '411') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addgallery'] , 'id' => "addgalleryform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '412') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '412') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '413') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control backdatepicker" id="eventDate" data-date-format="dd-mm-yyyy" name="eventDate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '413') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '414') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '414') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addgallerybtn" id="addgallerybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '415') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '416') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>         

<div class="modal classmodal animated zoomIn" id="addgalleryimages" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '411') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addgalleryimages'] , 'id' => "addgalleryimagesform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" name="galleryId" id="galleryId" >
                    <!--<div class="col-sm-12" id="upload_file">
                        <label>File Upload*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload[]" id="file_upload" multiple>
                        </div>
                    </div>-->
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1520') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="input-images"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="imggalleryerror"></div>
                        <div class="success" id="imggallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addgalleryimgbtn" id="addgalleryimgbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '124') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '416') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>           

    <!------------------ Edit gallery --------------------->

    
<div class="modal classmodal  animated zoomIn" id="editgal"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1529') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editgallery'] , 'id' => "editgalleryform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="eid"  name="eid" >
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '412') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '412') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '413') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control backdatepicker" id="edeventDate" data-date-format="dd-mm-yyyy" name="edeventDate"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '413') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '415') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '414') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="editgalleryerror"></div>
                        <div class="success" id="editgallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editgallerybtn" id="editgallerybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1530') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '416') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>

<div class="modal classmodal animated zoomIn" id="editgalleryimages" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1529') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'editgalleryimages'] , 'id' => "editgalleryimagesform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" name="egalleryId" id="egalleryId" >
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1520') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="input-images-2" style="padding-top: .5rem;"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="eimggalleryerror"></div>
                        <div class="success" id="eimggallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editgalleryimgbtn" id="editgalleryimgbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '416') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

<style>
    h5
    {
        color:#191c21 !important;
        margin-left:15px !important;
    }
</style>

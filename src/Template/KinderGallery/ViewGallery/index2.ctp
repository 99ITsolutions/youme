<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2 class="heading">Gallery    </h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#addgallery">Add New</a></li>
                    <li><a href="<?=$baseurl?>gallery/view" title="Add" class="btn btn-info">View Gallery</a></li>
                </ul>
                            
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="gallery_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
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

<!--    
<div class="modal classmodal animated zoomIn" id="addgallery122" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Add Gallery</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php //	echo $this->Form->create(false , ['url' => ['action' => 'addgallery'] , 'id' => "addgalleryform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-sm-12">
                        <label>Event Name*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="Event Name *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Event Description*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Event Description *" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="upload_file">
                        <label>File Upload*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload[]" id="file_upload" multiple>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="galleryerror"></div>
                        <div class="success" id="gallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addgallerybtn" id="addgallerybtn">Next</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                    </div>
                    
                   <?php // echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>              -->

<div class="modal classmodal animated zoomIn" id="addgallery" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Add Gallery</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addgallery'] , 'id' => "addgalleryform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-sm-12">
                        <label>Event Name*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="Event Name *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Event Description*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="Event Description *" ></textarea>
                        </div>
                    </div>
                    <!--<div class="col-sm-12" id="upload_file">
                        <label>File Upload*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload[]" id="file_upload" multiple>
                        </div>
                    </div>-->
                    <!--<div class="col-md-12">
                        <div class="error" id="galleryerror"></div>
                        <div class="success" id="gallerysuccess"></div>
                    </div>-->
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addgallerybtn" id="addgallerybtn">Next</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
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
                <h6 class="title" id="defaultModalLabel">Add Gallery</h6>
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
                        <label>Images Upload*</label>
                        <div class="input-images"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="imggalleryerror"></div>
                        <div class="success" id="imggallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addgalleryimgbtn" id="addgalleryimgbtn">Save</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
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
                <h6 class="title" id="defaultModalLabel">Edit Gallery</h6>
		        <button type="button" class="close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
         	    </button>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editgallery'] , 'id' => "editgalleryform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="eid"  name="eid" >
                    <div class="col-sm-12">
                        <label>Event Name*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="Event Title *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label>Event Description*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="Event Description *" ></textarea>
                        </div>
                    </div>
                    <!--<div class="col-sm-12" id="upload_file">
                        <label>File Upload*</label>
                        <div class="form-group">
                            <p><b>Images - </b><span id="images"></span></p>
                            <input type="file" class="form-control" name="efile_upload[]" id="efile_upload" multiple>
                        </div>
                    </div>-->
                    <div class="col-md-12">
                        <div class="error" id="editgalleryerror"></div>
                        <div class="success" id="editgallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editgallerybtn" id="editgallerybtn">Edit Images</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
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
                <h6 class="title" id="defaultModalLabel">Edit Gallery</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'editgalleryimages'] , 'id' => "editgalleryimagesform" ,  'enctype' => "multipart/form-data", 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" name="egalleryId" id="egalleryId" >
                    <div class="col-sm-12">
                        <label>Images Upload*</label>
                        <div class="input-images-2" style="padding-top: .5rem;"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="eimggalleryerror"></div>
                        <div class="success" id="eimggallerysuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editgalleryimgbtn" id="editgalleryimgbtn">Update</button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
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

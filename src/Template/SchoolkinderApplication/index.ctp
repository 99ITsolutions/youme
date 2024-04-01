<style>
.col {
  display: table-cell;
  padding: 16px;
}
.col_img img{
    height:100%;
    width:100%;
    max-height:200px;
    min-height:170px;
}
.col_img {
    margin-bottom:20px !important;
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 0px;
    left: 10px;
    padding: 5px 16px;
}
#right_icon {
    list-style:none;
    position: absolute;
    right: 15px;
}
#right_icon li {
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 1px solid #fff;
}
#right_icon li a i{
    color: #fff;
    border-top: 1px solid #fff;
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1824') { echo $langlbl['title'] ; } } ?></h2>
				    <ul class="header-dropdown">
				        <?php if(!empty($sclsub_details[0]))
                        { 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                            if(in_array("100", $roles)) { ?>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#addknowledge" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
                            <?php }
                        } else { ?>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#addknowledge" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } ?>
                        
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        {
                        ?>
                        <div class="col-sm-3 col_img" style="height:215px !important">
                            <ul id="right_icon">
                                <?php if(!empty($sclsub_details[0]))
                                { 
                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                    if(in_array("101", $roles)) { ?>
                                        <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editknowledge" class="editdatapps" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                    <?php } if(in_array("102", $roles)) { ?> 
                                        <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-url="SchoolkinderApplication/delete" class=" js-sweetalert " title="Delete" data-str="Application Data" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                    <?php } if(in_array("103", $roles)) { ?> 
                                        <li> <a href="<?=$baseurl?>SchoolkinderApplication/view/<?php echo md5($content['id']) ?>" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                                    <?php }
                                } else { ?>
                                    <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editknowledge" class="editdatapps" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                    <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-url="SchoolkinderApplication/delete" class=" js-sweetalert " title="Delete" data-str="Application Data" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                    <li> <a href="<?=$baseurl?>SchoolkinderApplication/view/<?php echo md5($content['id']) ?>" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                                <?php } ?>
                                
                            </ul>
                            <?php if(!empty($content->image )) { ?>
                            <img src ="<?= $baseurl ?>applications_data/<?= $content->image ?>" >
                            <?php } else { ?>
                            <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 75px 0;border: 1px solid #cccccc;">
                            <?php } ?>
                            <p class="title" style="color:#000"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>: <?= ucfirst($content->title) ?></p>
                        </div>
                        
                        <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->create(false , ['method' => "post"  ]); ?>
<?php echo $this->Form->end(); ?>
</div>
</div>


 <!------------------ Add Knowledge --------------------->

    
<div class="modal classmodal animated zoomIn" id="addknowledge" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '483') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'adddata'] , 'id' => "adddataform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                           
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12" id="upload_file" >
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload" id="file_upload">
                        </div>
                    </div>
                            
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '769') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '770') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '772') { echo $langlbl['title'] ; } } ?></label>
                        
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/gif"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="adddataerror">
                        </div>
                        <div class="success" id="adddatasuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="adddatabtn" class="btn btn-primary adddatabtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '771') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '774') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

<!------------------ End --------------------->

 <!------------------ Edit Knowledge --------------------->

    
<div class="modal classmodal animated zoomIn" id="editknowledge" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel">Edit Content</h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'editdata'] , 'id' => "editdataform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                   
                           
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12" id="eupload_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="efile_upload"  id="efile_upload"   placeholder="Upload File *">
                            <input type="hidden" class="form-control" name="efileupload"  id="efileupload"   placeholder="Upload File *">
                            <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2098') { echo $langlbl['title'] ; } } ?> - </b></span><span id="file_name"></span></p>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="ekid"  id="ekid">   
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '769') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '770') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '772') { echo $langlbl['title'] ; } } ?>*</label><span id="coverimg"></span>
                        <div class="form-group">
                            <!--<input type="file" class="form-control" name="ecover_image" id="ecover_image" accept=".jpg,.png,.jpeg">-->
                            <input type="hidden" class="form-control" name="ecoverimage"  id="ecoverimage"   placeholder="Upload File *">
                        </div>
                    </div>
                    <div class="slim" data-label=<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/jpg"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="editdataerror">
                        </div>
                        <div class="success" id="editdatasuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="editdatabtn" class="btn btn-primary editdatabtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '774') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>  

<!------------------ End --------------------->


<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    

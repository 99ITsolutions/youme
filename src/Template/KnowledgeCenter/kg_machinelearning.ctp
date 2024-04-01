<style>
.col-sm-2 {
    flex: 0 0 20%;
    max-width: 20%;
}
.edit {
	margin-top: 7px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 0;
	display: none;
}

.delete {
	margin-top: 32px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 5px;
	display: none;
}
fa-lg {
    font-size: 1.2em;
}

.col-container {
  display: table;
  width: 100%;
}
.col {
  display: table-cell;
  padding: 16px;
}
.col_img img{
    height:100%;
    width:100%;
    max-height:250px;
    min-height:220px;
}
.col_img {
    margin-bottom:20px !important;
   /* max-width:200px;
    min-width:180px;*/
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    top: 200px;
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
#left_icon {
    list-style:none;
    position: absolute;
    left: -22px;
}
#left_icon li {
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 0px solid #fff;
}
#left_icon li a i{
    color: #fff;
    border-top: 1px solid #fff;
}
@media only screen and (max-width: 767px)
{
.header-page{
position: relative !important;
    left: 5px;     width: 100%;   
}
.class-gap{

}
.gap-width{
       width: 40% !important;
}
.col-sm-2 {
    flex: 0 0 100%;
    max-width: 100%;
}
.space-width{
  padding: 10px 0px 0 10px;     
}
}
</style>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <input type="hidden" id="addedfor" name="addedfor" value="<?= $added_for ?>">
				    <input type="hidden" id="added_for" name="added_for" value="<?= $added_for ?>" > 
				    <?php
				    if($added_for == "kinder")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1519') { $label =  $langlbl['title'] ; } }
				    }
				    elseif($added_for == "primary")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1577') { $label =  $langlbl['title'] ; } }
				    }
				    elseif($added_for == "highscl")
				    {
				        foreach($lang_label as $langlbl) { if($langlbl['id'] == '1578') { $label =  $langlbl['title'] ; } }
				    }
				    ?>
				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2079') { echo $langlbl['title'] ; } } ?> -> <?= $label ?></h2>
				    <ul class="header-dropdown header-page">
                        <li style="width:160px; padding:0px 10px" class="gap-width">
                            <select class=" form-control class_s" id="clsfilter" onchange="guidecls_filter(this.value)">
                                <option value="">Choose Class</option>
                                <?php foreach($cls_details as $clsdtl) {
                                    
                                    //print_r($clsdtl); 
                                    if($added_for == "kinder" && ($clsdtl['school_sections'] == "Creche" || $clsdtl['school_sections'] == "Maternelle"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "primary" && ($clsdtl['school_sections'] == "Primaire"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "highscl" && ($clsdtl['school_sections'] != "Creche" && $clsdtl['school_sections'] != "Maternelle" && $clsdtl['school_sections'] != "Primaire" ) )
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }   
            				    } ?>
					        </select>
                        </li>
                        <li style="width:160px; padding:0px 10px" class="gap-width">
                            <select class=" form-control community_filter" id="comm_filter" onchange="machinelearning_filter(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <!--<option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>-->
					        </select>
                        </li>
                        <li class="space-width"><a href="javascript:void(0)" data-toggle="modal" data-target="#addmachinelearning" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
                        <li class="space-width"><a href="javascript:void(0)" data-contnt ="Teacher Guides!"data-str= "Data" data-url = "knowledgeCenter/deleteall"  id="checkid" title="Delete All" class="btn btn-sm btn-success">Delete All</a></li>
                        <li class="space-width"><a href="<?= $baseurl ?>knowledgeCenter/machinelearning" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3 ">
                        <select class="form-control js-states chosetitle" id="title_filter" onchange="title_filter(this.value, 'machinelearning')">
                            <option value="">Choose Title</option>
				            <?php foreach($title_details as $title) { ?>
				            <option value="<?= $title['title'] ?>"><?= $title['title'] ?></option>
				            <?php } ?>
				        </select>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewcommunity">
                        <?php
                        foreach($know_details as $content)
                        {
                        ?>
                        <div class="col-sm-2 col_img">
                                <ul id="left_icon">
                                    <li><input type="checkbox" name="checkbox" id="<?= $content['id']?>"></li>
                                </ul>
                                <ul id="right_icon">
                                    <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editmachinelearning" class="editmachinelearning" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                    <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-url="../deletemachinelearning" class=" js-sweetalert " title="Delete" data-str="Machine Learning" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                    <li> <a href="<?=$baseurl?>knowledgeCenter/viewmachinelearning/<?php echo md5($content['id']) ?>" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                                </ul>
                                <?php if(!empty($content->image )) { ?>
                                <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                <?php } else { ?>
                                <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                <?php } ?>
                                <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'word'){ ?><i class="fa fa-file-word-o"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></br>
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


 <!------------------ Add State Exam --------------------->

    
<div class="modal classmodal animated zoomIn" id="addmachinelearning" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2080') { echo $langlbl['title'] ; } } ?> -> <?= $label ?> </h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addmachinelearning'] , 'id' => "addcommunityform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <!--<input type="hidden" name="school_id" value="<?=$school_id?>">-->
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '761') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '762') { echo $langlbl['title'] ; } } ?></option>
                                <!--<option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>-->
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '764') { echo $langlbl['title'] ; } } ?></option>
                            
                            </select> 
                        </div>
                    </div>
                    <input type="hidden" id="data_addedfor" name="data_addedfor" value="<?= $added_for ?>">
                    <div class="col-sm-6" id="typevideo">
                        <label>Video Types*</label>
                        <div class="form-group">
                            <select class="form-control" name="videotypes" id="videotypes" onchange="video_type(this.value)">
                                <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                                <option value="cupload">Custom Upload</option>
                            </select>
                        </div>
                    </div>           
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class=" form-control class_s" id="guid_class" name="guid_class[]" multiple required>
                                <?php foreach($cls_details as $clsdtl) {
                                    if($added_for == "kinder" && ($clsdtl['school_sections'] == "Creche" || $clsdtl['school_sections'] == "Maternelle"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "primary" && ($clsdtl['school_sections'] == "Primaire"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "highscl" && ($clsdtl['school_sections'] != "Creche" && $clsdtl['school_sections'] != "Maternelle" && $clsdtl['school_sections'] != "Primaire" ) )
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }   
            				    } ?>
					        </select>
					    </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?>  *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="link_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?> </label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="file_link" id="file_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?>  " >
                        </div>
                    </div>
                    <div class="col-sm-12" id="dtubevideo"  style="display:none">
                        <label>D-Tube Embed Code</label>
                        <div class="form-group">
                            <textarea class="form-control" name="dtube_video" id="dtube_video"  placeholder="Enter Dtube embedded code" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="upload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*
                        <small id="file_size_up">(Note: File should be less than 5 MB)</small>
                        </label>
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
                        <div class="error" id="addknowldgeerror">
                        </div>
                        <div class="success" id="addknowldgesuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addknowledgebtn" class="btn btn-primary addknowledgebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '771') { echo $langlbl['title'] ; } } ?></button>
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

    
<div class="modal classmodal animated zoomIn" id="editmachinelearning" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2081') { echo $langlbl['title'] ; } } ?> -> <?= $label ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'editmachinelearning'] , 'id' => "editstateexamform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <input type="hidden" id="edata_addedfor" name="edata_addedfor" value="<?= $added_for ?>">
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '761') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="efile_type" name="efile_type" required onchange="efile_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '762') { echo $langlbl['title'] ; } } ?></option>
                                <!--<option value="word"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1470') { echo $langlbl['title'] ; } } ?></option>-->
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '764') { echo $langlbl['title'] ; } } ?></option>
                            
                            </select> 
                        </div>
                    </div>
                    <div class="col-sm-6" id="etypevideo">
                        <label>Video Types*</label>
                        <div class="form-group">
                            <select class="form-control" name="evideotypes" id="evideotypes" onchange="evideo_type(this.value)">
                                <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                                <option value="cupload">Custom Upload</option>
                            </select>
                        </div>
                    </div>           
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class=" form-control class_s" id="eguid_class" name="eguid_class[]" multiple required>
                                <?php foreach($cls_details as $clsdtl) {
                                    if($added_for == "kinder" && ($clsdtl['school_sections'] == "Creche" || $clsdtl['school_sections'] == "Maternelle"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "primary" && ($clsdtl['school_sections'] == "Primaire"))
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }
                				    elseif($added_for == "highscl" && ($clsdtl['school_sections'] != "Creche" && $clsdtl['school_sections'] != "Maternelle" && $clsdtl['school_sections'] != "Primaire" ) )
                				    {
                				        echo '<option value="'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'">'.$clsdtl['c_name'].'-'.$clsdtl['school_sections'].'</option>';
                				    }   
            				    } ?>
					        </select>
					    </div>
                    </div>
                    <div class="col-sm-12" id="elink_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="efile_link"   id="efile_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    <div class="col-sm-12" id="edtubevideo"  style="display:none">
                        <label>D-Tube Embed Code</label>
                        <div class="form-group">
                            <textarea class="form-control" name="edtube_video" id="edtube_video"  placeholder="Enter Dtube embedded code" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="eupload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="efile_upload"  id="efile_upload"   placeholder="Upload File *">
                            <input type="hidden" class="form-control" name="efileupload"  id="efileupload"   placeholder="Upload File *">
                            <p><span><b>File Uploaded - </b></span><span id="file_name"></span></p>
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
                    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/jpg"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="editknowldgeerror">
                        </div>
                        <div class="success" id="editknowldgesuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="editknowledgebtn" class="btn btn-primary editknowledgebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
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

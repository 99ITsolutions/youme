<style>
.col-sm-2 {
    flex: 0 0 20%;
    max-width: 20%;
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
    max-height:230px;
    min-height:230px;
    margin-bottom:20px;
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 130px;
    left: 10px;
    padding: 5px 16px;
}
#right_icon {
    list-style:none;
    position: absolute;
    right: 15px;
}
#right_icon li{
    background: #444;
    color: #fff;
    padding: 5px;
    border-top: 1px solid #fff;
}
.col_img button, .col_img a{
    background:none;
    color:#fff;
    box-shadow:none;
    border:none;
    text-align:center;
}
.col_img a{
    margin-left:5px;
}
.crop_preview {
	background:#e1e1e1;
	width:300px;
	padding:30px;
	height:300px;
	margin-top:30px
}
.card .body
{
    padding: 50px 21px !important;
}

@media screen and (max-width: 5200px) and (min-width: 200px) 
{
    .btnknowpadd
    {
        padding-top:3px !important;
    }
}
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
            				<div class="row clearfix">
            				    <ul class="header-dropdown">
                                    <li style="width:160px; padding:0px 10px">
                                        <select class="form-control class" id="class" name="class" title="Choose Class" required onchange="getclass_subject(this.value)">
                                            <option value="">Choose Class</option>
                                            <option value="all">All</option>
                                            <?php
                                            foreach($class_details as $key => $val)
                                            {
                                                if(!empty($sclsub_details[0]))
                                                { 
                                                    //echo "subadmin";
                                                    if(strtolower($val['school_sections']) == "creche" || strtolower($val['school_sections']) == "maternelle") {
                                                        $clsmsg = "kindergarten";
                                                    }
                                                    elseif(strtolower($val['school_sections']) == "primaire") {
                                                        $clsmsg = "primaire";
                                                    }
                                                    else
                                                    {
                                                        $clsmsg = "secondaire";
                                                    }
                                                    $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                                    //print_r($subpriv);
                                                    $clsmsg = trim($clsmsg);
                                                    if(in_array($clsmsg, $subpriv)) { 
                                                        $show = 1;
                                                    }
                                                    else
                                                    {
                                                        $show = 0;
                                                    }
                                                } else { 
                                                    $show = 1;
                                                }
                                                if($show == 1) {
                                                ?>
                                                  <option  value="<?=$val['id']?>" ><?php echo $val['c_name'] ."-" . $val['c_section'] ." (". $val['school_sections'] . ")";?> </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>                                    
                                    </li>
                                    <li style="width:160px; padding:0px 10px">
                                        <select class="form-control subj_s" id="subject" name="subject" onchange="getlibrary_content(this.value)" placeholder="Choose Subjects" required>
                                            <option value="">Choose Subject</option>
                                        </select>
                                    </li>
                                    <li style="width:160px; padding:0px 10px">
                                        <select class=" form-control community_filter" id="tut_filter" onchange="library_filter(this.value)">
            					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '479') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '480') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '481') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '482') { echo $langlbl['title'] ; } } ?></option>
            					        </select>
                                    </li>
                                    <?php if(!empty($sclsub_details[0]))
                                    { 
                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                        if(in_array("74", $roles)) { ?>
                                            <li style="padding:0px 10px" class="btnknowpadd"><a href="javascript:void(0)" data-toggle="modal" data-target="#addtutcontent" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '76') { echo $langlbl['title'] ; } } ?></a></li>
                                        <?php }
                                    } else { ?>
                                        <li style="padding:0px 10px" class="btnknowpadd"><a href="javascript:void(0)" data-toggle="modal" data-target="#addtutcontent" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '76') { echo $langlbl['title'] ; } } ?></a></li>
                                    <?php } ?>
                                    
                                    <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row viewtutcontent" id="viewtutcontent">
                                <?php foreach($content_details as $content){ if($content->show == 1) { ?>
                                <div class="col-sm-2 col_img">
                                    <ul id="right_icon">
                                        <?php if(!empty($sclsub_details[0]))
                                        { 
                                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                            if(in_array("75", $roles)) { ?>
                                                <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editlibcontent" class="editlibcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                            <?php } if(in_array("77", $roles)) { ?>
                                                <li><button type="button" data-id="<?=$content['id']?>" data-url="SchoolLibrary/delete" class="js-sweetalert " title="Delete" data-str="Library content" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                            <?php } if(in_array("76", $roles)) { ?>
                                                <li><a href="<?=$baseurl?>SchoolLibrary/viewcontent/<?php echo md5($content['id']) ?>" title="view" target="_blank" ><i class="fa fa-eye"></i></a></li>
                                            <?php }
                                        } else { ?>
                                            <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editlibcontent" class="editlibcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                            <li><button type="button" data-id="<?=$content['id']?>" data-url="SchoolLibrary/delete" class="js-sweetalert " title="Delete" data-str="Library content" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                            <li><a href="<?=$baseurl?>SchoolLibrary/viewcontent/<?php echo md5($content['id']) ?>" title="view" target="_blank" ><i class="fa fa-eye"></i></a></li>
                                        <?php } ?>
                                        
                                        
                                    </ul>
                                    <?php if($content->image != '' || $content->image != null) { ?>
                                        <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                                    <?php } else { ?>
                                        <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                    <?php } ?>
                                    <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                    <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></br>
                                    <b>Classe</b>: <?= ucfirst($content->classname) ?></br>
                                    <b>Subject</b>: <?= ucfirst($content->subname) ?></p>
                            
                                </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
           

        </div>
    </div>


 <!------------------ Add Knowledge --------------------->

    
<div class="modal classmodal animated zoomIn" id="addtutcontent" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '483') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'addlibrarycontent'] , 'id' => "addlibcontentform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '484') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control class_s" id="classID" name="class_id" required onchange="subjctcls(this.value)">
                                <option value="" >Choose Class</option>
                                <?php foreach($class_details as $cls) {
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        //echo "subadmin";
                                        if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($cls['school_sections']) == "primaire") {
                                            $clsmsg = "primaire";
                                        }
                                        else
                                        {
                                            $clsmsg = "secondaire";
                                        }
                                        $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                        //print_r($subpriv);
                                        $clsmsg = trim($clsmsg);
                                        if(in_array($clsmsg, $subpriv)) { 
                                            $show = 1;
                                        }
                                        else
                                        {
                                            $show = 0;
                                        }
                                    } else { 
                                        $show = 1;
                                    }
                                    if($show == 1) {
                                    ?>
                                    <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-".$cls['c_section']." (". $cls['school_sections'] . ")" ?></option>
                                    <?php } 
                                }?>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '486') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control subj_s" id="cls_sub" name="subject_id" required >
                                <option value="" >Choose Subject</option>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '488') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '481') { echo $langlbl['title'] ; } } ?></option>
                                <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '482') { echo $langlbl['title'] ; } } ?></option>
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '480') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-sm-6" id="typevideo">
                        <label>Video Types*</label>
                        <div class="form-group">
                            <select class="form-control" name="videotypes" id="videotypes" onchange="video_type(this.value)">
                                 <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                            </select>
                        </div>
                    </div>          
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '489') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '490') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="link_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '491') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="file_link" id="file_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '492') { echo $langlbl['title'] ; } } ?> " >
                        </div>
                    </div>
                    <div class="col-sm-12" id="dtubevideo"  style="display:none">
                        <label>D-Tube Embed Code</label>
                        <div class="form-group">
                            <textarea class="form-control" name="dtube_video" id="dtube_video"  placeholder="Enter Dtube embedded code" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="upload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload" id="file_upload">
                        </div>
                    </div>
                            
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '493') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '494') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    
                    <!--<div class="col-sm-12">
                        <label>Cover Image*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="cover_image" id="cover_image" accept=".jpg,.png,.jpeg">
                        </div>
                    </div>-->
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '495') { echo $langlbl['title'] ; } } ?></label>
                        
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '496') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/gif"/>
                    </div><br>
                    
                    <div class="col-sm-12">
                        <div class="error" id="addtutconterror">
                        </div>
                        <div class="success" id="addtutcontsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addtutcontbtn" class="btn btn-primary addtutcontbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '497') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '498') { echo $langlbl['title'] ; } } ?></button>
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

    
<div class="modal classmodal animated zoomIn" id="editlibcontent" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1531') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['url' => ['action' => 'editlibcontent'] , 'id' => "editlibcontentform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '484') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control class_s" id="eclassID" name="class_id" required onchange="subjctcls(this.value)">
                                <option value="" >Choose Class</option>
                                <?php foreach($class_details as $cls) {
                                    if(!empty($sclsub_details[0]))
                                    { 
                                        //echo "subadmin";
                                        if(strtolower($cls['school_sections']) == "creche" || strtolower($cls['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($cls['school_sections']) == "primaire") {
                                            $clsmsg = "primaire";
                                        }
                                        else
                                        {
                                            $clsmsg = "secondaire";
                                        }
                                        $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                        //print_r($subpriv);
                                        $clsmsg = trim($clsmsg);
                                        if(in_array($clsmsg, $subpriv)) { 
                                            $show = 1;
                                        }
                                        else
                                        {
                                            $show = 0;
                                        }
                                    } else { 
                                        $show = 1;
                                    }
                                    if($show == 1) {
                                ?>
                                <option value="<?= $cls['id'] ?>"><?= $cls['c_name']."-".$cls['c_section']." (". $cls['school_sections'] . ")" ?></option>
                                <?php } } ?>
                            </select> 
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '486') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control subj_s" id="ecls_sub" name="subject_id" required >
                                <option value="" >Choose Subject</option>
                            </select> 
                        </div>
                    </div>
                   
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '488') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="efile_type" name="efile_type" required onchange="efile_typess(this.value)">
                                <option value="video" >Video</option>
                                <option value="audio">Audio</option>
                                <option value="pdf">PDF</option>
                            </select> 
                          
                        </div>
                    </div>
                    <div class="col-sm-4" id="etypevideo">
                        <label>Video Types*</label>
                        <div class="form-group">
                            <select class="form-control" name="evideotypes" id="evideotypes" onchange="evideo_type(this.value)">
                                 <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                            </select>
                        </div>
                    </div>           
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '489') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '490') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="elink_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '491') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="efile_link"   id="efile_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '492') { echo $langlbl['title'] ; } } ?> *" >
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
                            <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2098') { echo $langlbl['title'] ; } } ?> - </b></span><span id="file_name"></span></p>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="ekid"  id="ekid">   
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '493') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '494') { echo $langlbl['title'] ; } } ?> *" >Video Description</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '495') { echo $langlbl['title'] ; } } ?>*</label><span id="coverimg"></span>
                        
                        <!--<div class="form-group">
                            <input type="file" class="form-control" name="ecover_image" id="ecover_image" accept=".jpg,.png,.jpeg">
                        </div>-->
                    </div>
                    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '496') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/jpg"/>
                    </div><br>
                    <input type="hidden" name="ecoverimage" id="ecoverimage"/>
                    
                    <div class="col-sm-12">
                        <div class="error" id="editlibcontenterror">
                        </div>
                        <div class="success" id="editlibcontentsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="editlibcontentgebtn" class="btn btn-primary editlibcontentgebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '498') { echo $langlbl['title'] ; } } ?></button>
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


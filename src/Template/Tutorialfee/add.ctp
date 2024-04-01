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
    bottom: 60px;
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
</style>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
            				<div class="row">
            				    <input type="hidden" id="clsid" value="<?= $classId ?>">
            				    <input type="hidden" id="subsid" value="<?= $subjectid ?>">
            				    <h2 class="col-lg-6 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '540') { echo $langlbl['title'] ; } } ?>-> <?= $classname ?> -> <?= $subname ?></h2>
            				    <ul class="header-dropdown">
                				     <li style="width:160px; padding:0px 10px">
                                        <select class=" form-control community_filter" id="tut_filter" onchange="tutorial_filter(this.value)">
            					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '974') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '975') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '976') { echo $langlbl['title'] ; } } ?></option>
            					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '977') { echo $langlbl['title'] ; } } ?></option>
            					        </select>
                                    </li>
                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#addtutcontent" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1075') { echo $langlbl['title'] ; } } ?></a></li>
                                    <li><a href="<?=$baseurl?>tutorialfee/subjects/" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row" id="viewtutcontent">
                                <?php foreach($content_details as $content){ ?>
                                <div class="col-sm-2 col_img">
                                    <ul id="right_icon">
                                        <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#edittutcontent" class="edittutcontent" id="editknow" ><i class="fa fa-edit"></i></a></li>
                                        <li><button type="button" data-id="<?=$content['id']?>" data-url="<?= $baseurl; ?>tutorialfee/deletecontent" class="js-sweetalert " title="Delete" data-str="Tutoring content" data-type="confirm"><i class="fa fa-trash"></i></button></li>
                                        <li><a href="<?=$baseurl?>tutorialfee/viewcontent/<?php echo md5($content['id']) ?>" title="view" ><i class="fa fa-eye"></i></a></li>
                                    </ul>
                                    <?php if($content->image != '' || $content->image != null) { ?>
                                        <img src ="<?= $baseurl ?>img/<?= $content->image ?>" ?>
                                    <?php } else { ?>
                                        <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                    <?php } ?>
                                    <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                    <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></b>: <?= ucfirst($content->title) ?></p>
                                </div>
                                <?php } ?>
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
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '546') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'addtutorialcontent'] , 'id' => "addtutcontentform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <!--<input type="hidden" name="school_id" value="<?=$school_id?>">-->
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '384') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '385') { echo $langlbl['title'] ; } } ?></option>
                                <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '386') { echo $langlbl['title'] ; } } ?></option>
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '387') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                            <input type="hidden" value="<?= $classId ?>" name="class_id" >
                            <input type="hidden" value="<?= $subjectid ?>" name="subject_id" >
                        </div>
                    </div>
                     <div class="col-sm-6" id="typevideo">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2097') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control" name="videotypes" id="videotypes" onchange="video_type(this.value)">
                                <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                            </select>
                        </div>
                    </div>       
                     <div class="col-sm-6" >
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '571') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control js-example-tokenizer studentchose" name="studid[]" multiple="multiple" id="studid">
                                <option value="">Choose Student</option>
                                <?php foreach($studtut as $tutstud) { ?>
                                <option value="<?= $tutstud['student']['id'] ?>"><?= $tutstud['student']['l_name']." ".$tutstud['student']['f_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>       
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?> *">
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="link_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '390') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="file_link" id="file_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '391') { echo $langlbl['title'] ; } } ?> " >
                        </div>
                    </div>
                    <div class="col-sm-12" id="dtubevideo"  style="display:none">
                        <label>D-Tube Embed Code</label>
                        <div class="form-group">
                            <textarea class="form-control" name="dtube_video" id="dtube_video"  placeholder="Enter Dtube embedded code" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="upload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload" id="file_upload">
                        </div>
                    </div>
                            
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '392') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '393') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    
                    <!--<div class="col-sm-12">
                        <label>Cover Image*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="cover_image" id="cover_image" accept=".jpg,.png,.jpeg">
                        </div>
                    </div>-->
                    
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '394') { echo $langlbl['title'] ; } } ?></label>
                        
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
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
                        <button type="submit" id="addtutcontbtn" class="btn btn-primary addtutcontbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
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

    
<div class="modal classmodal animated zoomIn" id="edittutcontent" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1507') { echo $langlbl['title'] ; } } ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'edittutcontent'] , 'id' => "edittutcontentform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <!--<input type="hidden" name="school_id" value="<?=$school_id?>">-->
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '384') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="efile_type" name="efile_type" required onchange="efile_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '385') { echo $langlbl['title'] ; } } ?></option>
                                <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '386') { echo $langlbl['title'] ; } } ?></option>
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '387') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                            <input type="hidden" value="<?= $classId ?>" name="class_id" >
                            <input type="hidden" value="<?= $subjectid ?>" name="subject_id" >
                        </div>
                    </div>
                    <div class="col-sm-6" id="etypevideo">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2097') { echo $langlbl['title'] ; } } ?>s*</label>
                        <div class="form-group">
                            <select class="form-control" name="evideotypes" id="evideotypes" onchange="evideo_type(this.value)">
                                <option value="youtube">YT</option>
                                <option value="vimeo">VM</option>
                                <option value="d.tube">DT</option>
                            </select>
                        </div>
                    </div>               
                    <div class="col-sm-6" >
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '571') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <select class="form-control js-example-tokenizer studentchose" name="estudid[]" multiple="multiple" id="estudid">
                                <option value="">Choose Student</option>
                                <?php foreach($studtut as $tutstud) { ?>
                                <option value="<?= $tutstud['student']['id'] ?>"><?= $tutstud['student']['l_name']." ".$tutstud['student']['f_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>   
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="elink_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '390') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="efile_link"   id="efile_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '391') { echo $langlbl['title'] ; } } ?>*" >
                        </div>
                    </div>
                    <div class="col-sm-12" id="edtubevideo"  style="display:none">
                        <label>D-Tube Embed Code</label>
                        <div class="form-group">
                            <textarea class="form-control" name="edtube_video" id="edtube_video"  placeholder="Enter Dtube embedded code" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="eupload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="efile_upload"  id="efile_upload"   placeholder="Upload File *">
                            <input type="hidden" class="form-control" name="efileupload"  id="efileupload"   placeholder="Upload File *">
                            <p><span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2098') { echo $langlbl['title'] ; } } ?> - </b></span><span id="file_name"></span></p>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="ekid"  id="ekid">   
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '392') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '393') { echo $langlbl['title'] ; } } ?> *" >Video Description</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '394') { echo $langlbl['title'] ; } } ?>*</label><span id="coverimg"></span>
                        
                    </div>
                    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/jpg"/>
                    </div><br>
                    <input type="hidden" name="ecoverimage" id="ecoverimage"/>
                    <div class="col-sm-12">
                        <div class="error" id="edittutcontenterror">
                        </div>
                        <div class="success" id="edittutcontentsuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="edittutcontentgebtn" class="btn btn-primary edittutcontentgebtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
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

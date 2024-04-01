<style>
.col_img img{
    height:100%;
    width:100%;
    max-height:200px;
    min-height:180px;
}
.col_img {
    margin-bottom:20px !important;
}
.set_icon{
    color: #fff;
    background: #444;
    position: absolute;
    bottom: 122px;
    left: 10px;
    padding: 5px 11px;
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
p {
    margin-bottom: 5px !important;
}
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2037') { $discvrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2038') { $animllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2039') { $allactlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2040') { $scinclbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2041') { $fruitveglbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2042') { $alphanumlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '483') { $addcontntlbl = $langlbl['title'] ; }
}

if($dashname == "Alphabets & Numbers")
{
    $dash_name = $alphanumlbl; 
}
elseif($dashname == "Coding")
{
    $dash_name = $dashname;
}
elseif($dashname == "Fruits & Vegetables")
{
    $dash_name = $fruitveglbl;
}
elseif($dashname == "Science")
{
    $dash_name = $scinclbl;
}
elseif($dashname == "All Activities")
{
    $dash_name = $allactlbl;
}
elseif($dashname == "Discovery")
{
    $dash_name = $discvrlbl;
}
elseif($dashname == "Animals")
{
    $dash_name = $animllbl;
}
else
{
    $dash_name = ucfirst($dashname);
}
?>
                                            
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
				<div class="row">
				    <input type="hidden" value="<?= $dashid ?>" id="dashid">
				    <h2 class="col-lg-6 heading"><?= $dash_name ?></h2>
				    <ul class="header-dropdown">
                        <li style="width:160px; padding:0px 10px">
                            <select class="form-control class" id="classkinder" name="classkinder" title="Choose Class" required  onchange="getsclclsfilter(this.value, this.id)">
                                <option value="">Choose Class</option>
                                <option value="all">All</option>
                                <?php foreach($classDetails as $class)
        	                    { if($class['school_sections'] == "Maternelle"  || $class['school_sections'] == "Creche" ) {
            	                    ?>
            	                    <option  value="<?= $class['id']  ?>" ><?= $class['c_name'] . " " . $class['c_section']." (". $class['school_sections'].")" ?> </option>
                                    <?php
        	                    } } ?>
                            </select>                                    
                        </li>
                        <li style="width:160px; padding:0px 10px">
                            <select class="form-control subj_s" id="subjectkinder" onchange="filterkindersclsubj(this.value)" name="subjectkinder" placeholder="Choose Subjects" required>
                                <option value="">Choose Subject</option>
                            </select>
                        </li>
                        <li style="width:160px; padding:0px 10px">
                            <select class=" form-control community_filter" id="comm_filter" onchange="filterkinder(this.value)">
					            <option value="newest"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '754') { echo $langlbl['title'] ; } } ?></option>
					            <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '755') { echo $langlbl['title'] ; } } ?></option>
					            <option value="video"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '756') { echo $langlbl['title'] ; } } ?></option>
					            <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '757') { echo $langlbl['title'] ; } } ?></option>
					        </select>
                        </li>
                        <?php if(!empty($sclsub_details[0]))
                        { 
                            $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                            if(in_array("91", $roles)) { ?>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#adddiscovery" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
    		                <?php }
                        } else { ?>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#adddiscovery" title="Add" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '758') { echo $langlbl['title'] ; } } ?></a></li>
                       <?php } ?>
                        
                        <li><a href="<?= $baseurl ?>Kindergarten" class="btn btn-info" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '759') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="body">
                <div class="row col-sm-12" id="viewdiscovery">
                        <?php
                        foreach($kinderlib as $content)
                        {
                        ?>
                        <div class="col-sm-3 col_img">
                                <div class="set_icon"><?php if($content->file_type == 'video'){ ?> <i class="fa fa-video-camera"></i><?php } else if($content->file_type == 'audio'){ ?><i class="fa fa-headphones"></i><?php } else{ ?><i class="fa fa-file-pdf-o"></i><?php } ?></div>
                                
                                <ul id="right_icon">
                                    <?php if(!empty($sclsub_details[0]))
                                    { 
                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                        if(in_array("92", $roles)) { ?>
                                            <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                                        <?php } if(in_array("93", $roles)) { ?>
                                            <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-url="../delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                        <?php } if(in_array("94", $roles)) { ?>
                                            <li> <a href="<?=$baseurl?>kindergarten/view/<?php echo md5($content['id']) ?>" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                		                <?php }
                                    } else { ?> 
                                        <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-toggle="modal" data-target="#editdiscovery" class="editdiscovery" id="editdisc" ><i class="fa fa-edit"></i></a></li>
                                        <li> <a href="javascript:void(0)" data-id="<?php echo $content['id']?>" data-url="../delete" class=" js-sweetalert " title="Delete" data-str="Content" data-type="confirm"><i class="fa fa-trash"></i></a></li>
                                        <li> <a href="<?=$baseurl?>kindergarten/view/<?php echo md5($content['id']) ?>" class="viewknow" ><i class="fa fa-eye"></i></a></li>
                                   <?php } ?>
                                    
                                </ul>
                                <?php if(!empty($content->image )) { ?>
                                <img src ="<?= $baseurl ?>img/<?= $content->image ?>" >
                                <?php } else { ?>
                                <img src ="https://you-me-globaleducation.org/youme-logo.png" style="padding: 83px 0;border: 1px solid #cccccc;">
                                <?php } ?>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?>:</b> <?php echo $content['title']?></p>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '136') { echo $langlbl['title'] ; } } ?>:</b> <?php echo $content['classname']?></p>
                                <p class="title" style="color:#000"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '365') { echo $langlbl['title'] ; } } ?>:</b> <?php echo $content['subject']?></p>
                            
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

    
<div class="modal classmodal animated zoomIn" id="adddiscovery" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                  <h6 class="title" id="defaultModalLabel"><?= $addcontntlbl ?></h6>
		  <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                           <?php   
                echo $this->Form->create(false , ['url' => ['action' => 'adddiscovery'] , 'id' => "addactivityform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <input type="hidden" value="<?= $dashid ?>" id="activityid" name="activityid">
                    <div class="col-md-6">
                            <div class="form-group">     
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                                <select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required onchange="subjctcls(this.value)">
                                    <option value="">Choose Class</option>
                                    <?php foreach($classDetails as $class)
            	                    { if($class['school_sections'] == "Maternelle"  || $class['school_sections'] == "Creche" ) {
                	                    ?>
                	                    <option  value="<?= $class['id']  ?>" ><?= $class['c_name'] . " " . $class['c_section']." (". $class['school_sections'].")" ?> </option>
                                        <?php
            	                    } } ?>
                                </select> 
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control subj_s" name="subjects" id="cls_sub" placeholder="Choose Subjects" required>
                                <option value="">Choose Subjects</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '761') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="file_type" name="file_type" required onchange="file_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '762') { echo $langlbl['title'] ; } } ?></option>
                                <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '763') { echo $langlbl['title'] ; } } ?></option>
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '764') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                           
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="title"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?>*">
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="link_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?></label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="file_link" id="file_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?> " >
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="upload_file" style="display:none">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="file" class="form-control" name="file_upload" id="file_upload">
                        </div>
                    </div>
                            
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '769') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="desc" name="desc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2043') { echo $langlbl['title'] ; } } ?> *" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '772') { echo $langlbl['title'] ; } } ?></label>
                        
                    </div>
        	  	    <div class="slim" data-label="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '395') { echo $langlbl['title'] ; } } ?>" style="margin: 0 auto;width: 350px;" data-ratio="3:3.5" data-fetcher="fetch.php">
                        <input type="file" name="slim[]" id="pcrop" accept="image/jpeg, image/png, image/gif"/>
                    </div><br>
                    <div class="col-sm-12">
                        <div class="error" id="addactivityerror">
                        </div>
                        <div class="success" id="addactivitysuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="addactivitybtn" class="btn btn-primary addactivitybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '771') { echo $langlbl['title'] ; } } ?></button>
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

    
<div class="modal classmodal animated zoomIn" id="editdiscovery" role="dialog">
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
                echo $this->Form->create(false , ['url' => ['action' => 'editdiscovery'] , 'id' => "editactivityform" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="eclass" name="eclass" placeholder="Choose Class" required  onchange="subjctcls(this.value)">
                                <option value="">Choose Class</option>
                                <?php foreach($classDetails as $class)
        	                    {  if($class['school_sections'] == "Maternelle"  || $class['school_sections'] == "Creche" ) {
            	                    ?>
            	                    <option  value="<?= $class['id']  ?>" ><?= $class['c_name'] . " " . $class['c_section'] ." (". $class['school_sections'].")"?> </option>
                                    <?php
        	                    } } ?>
                                
                                
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control subj_s" name="esubjects" id="ecls_sub" placeholder="Choose Subjects" required>
                                <option value="">Choose Subjects</option>
                                <?php foreach($subjectDetails as $subjects)
        	                    { 
            	                    ?>
            	                    <option  value="<?= $subjects['id']  ?>" ><?= $subjects['subject_name'] ?> </option>
                                    <?php
        	                    } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '761') { echo $langlbl['title'] ; } } ?> *</label>
                        <div class="form-group">
                            <select class="form-control file_type" id="efile_type" name="efile_type" required onchange="efile_typess(this.value)">
                                <option value="video" ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '762') { echo $langlbl['title'] ; } } ?></option>
                                <option value="audio"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '763') { echo $langlbl['title'] ; } } ?></option>
                                <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '764') { echo $langlbl['title'] ; } } ?></option>
                            </select> 
                        </div>
                    </div>
                           
                    <div class="col-sm-6">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '765') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="etitle" id="etitle"  required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '766') { echo $langlbl['title'] ; } } ?> *" >
                        </div>
                    </div>
                    
                    <div class="col-sm-12" id="elink_file">
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="efile_link"   id="efile_link"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '767') { echo $langlbl['title'] ; } } ?> *" >
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
                        <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '769') { echo $langlbl['title'] ; } } ?>*</label>
                        <div class="form-group">
                            <textarea class="form-control" id="edesc" name="edesc" rows="2" required placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2043') { echo $langlbl['title'] ; } } ?> *" ></textarea>
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
                        <div class="error" id="editactivityerror">
                        </div>
                        <div class="success" id="editactivitysuccess">
                        </div>
                    </div>
                    
                    <div class="button_row" >
                        <hr>
                        <button type="submit" id="editactivitybtn" class="btn btn-primary editactivitybtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
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
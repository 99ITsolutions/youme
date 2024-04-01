<style>
    .comments_ht
    {
        max-height:315px;
        overflow-y:auto;
    }
    .reply { margin-left: 30px; }
    .reply_form {
    	margin-left: 40px;
    	display: none;
    }
    #comment_form { margin-top: 10px; }
    #left-sidebar { left: -250px; }
    #main-content { width:100%;}
   .nGY2 .nGY2GThumbnail {
        display: none !important;
    }
    .nGY2 .nGY2GThumbnail:first-child {
        display: block!important;
    }
</style>
<link href="https://unpkg.com/nanogallery2/dist/css/nanogallery2.min.css" rel="stylesheet">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <?php if($knowledge_details[0]['file_type'] == "pdf")
                { ?>
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                
                <?php }
                elseif($knowledge_details[0]['file_type'] == "word")
                { ?>
                <div class="row">
                    <h2 class="col-lg-8 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download Document" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1493') { echo $langlbl['title'] ; } } ?></a></h2>
                    
                <?php } 
                else {?>    
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                <?php }  ?>
                
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>dropbox" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                
                <?php if(!empty($knowledge_details[0]['description'])) {?>
                <p class="mt-3"><b>Description - </b><?= ucfirst($knowledge_details[0]['description']) ?></p>
                <?php } ?>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <?php if($knowledge_details[0]['file_type'] == "video")
                    { ?>
                        <div class="col-sm-12 text-center">
                            <?php
                            if($knowledge_details[0]['video_type']  == "youtube" )
                            {
                            ?>
                            <iframe width="860" height="350" src="<?= $knowledge_details[0]['links'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php
                            }
                            elseif($knowledge_details[0]['video_type']  == "vimeo" )
                            {
                            ?>
                            <iframe src="<?= $knowledge_details[0]['links'] ?>" width="860" height="350" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                            <?php
                            }elseif($knowledge_details[0]['video_type']  == "custom upload" )
                            {
                            ?>
                            <video width="850" height="350" controls>
                              <source src="https://you-me-globaleducation.org/school/video_tchr_guide/<?php echo $knowledge_details[0]['links']?>" type="video/mp4">
                              <source src="movie.ogg" type="video/ogg">
                            </video>
                            <?php
                            }
                            else { ?>
                                <div width="860" height="350"><?= $knowledge_details[0]['links'] ?> </div>
                            <?php    }
                            ?>
                            
                        </div>
                    <?php
                    }
                    if(($knowledge_details[0]['file_type'] == "word"))
                    {
                         $valpag = $knowledge_details[0]['numpages'];
                        ?>
                        <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                        </div>
                        <?php
                    }else if(($knowledge_details[0]['file_type'] == "pdf") )
                    {
                         $valpag = $knowledge_details[0]['numpages'];
                        ?>
                        <div class="col-sm-6  offset-4">
                            <div id="nanogallery2"
                                // gallery configuration
                                data-nanogallery2 = '{ 
                                  "thumbnailWidth":   "auto",
                              	  "thumbnailHeight":  600,
                                  "itemsBaseURL":     "http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/"
                                }'
                              >
                              <!-- content of the gallery -->
                              <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?> 
                              <a href="<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" data-ngthumb="<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg"></a>
                              <?php  } ?>
                            </div>
                        </div>
                        <!--<div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                        </div>-->
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>    

<div class="modal fade bd-example-modal-lg" id="viewpdffile" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-header header">
                <!--<h6 class="title" id="defaultModalLabel">Passcode</h6>-->
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div id="viewfile"></div>
	        </div>
        </div>
    </div>
</div>   

<!------------------ Submit Request --------------------->
    
<div class="modal classmodal animated zoomIn" id="shareguides" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Teacher Guide Content Share</h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'sharerequest'] , 'id' => "sharerequestform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">
                                <option value="">Choose Class</option>
                                <?php foreach($empclses_details as $empdtl)
        	                    { ?>
            	                        <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control" name="subjects" id="subjects" placeholder="Choose Subjects" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="start_date" id="start_date" required/>
                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end_date" id="end_date" required>
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="display:block;">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                            <input type="text" name="title" id="title" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control" value="<?= ucwords($knowledge_details[0]['title']) ?>">
                        </div>
                    </div>
                    <div class="col-md-12" id="guideinstr">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                            <textarea name="instruction" id="instruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required><?= ucwords($knowledge_details[0]['description']) ?> </textarea>
                        </div>
                    </div>
                     <input type="hidden" name="gid" id="gid" value="<?= $knowledge_details[0]['id'] ?>">
                    
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error" id="sharereqerror"></div>
                            <div class="success" id="sharereqsuccess"></div>
                        </div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary sharereqbtn" id="sharereqbtn">Share</button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
        
<?php if(($knowledge_details[0]['file_type'] == "pdf")) { $valpag = $knowledge_details[0]['numpages']; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/nanogallery2/dist/jquery.nanogallery2.js"></script>
<script>
/*    jQuery(document).ready(function () {
        
  jQuery("#my_nanogallery2").nanogallery2({
  	items:[
  	    <?php for ($x = 0; $x < $valpag; $x++) { ?> 
      { src: '<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg',      srct: '<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg' },
      <?php  } ?>
		],
    thumbnailWidth:  'auto',
  	thumbnailHeight: 170,
    itemsBaseURL:    'http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/',
		locationHash:    false
  });
});*/
</script>
<script>
  $(document).ready(function(){
    // Toggle the dropdown on click
    $('.dropdown-toggle').click(function(){
      $('.dropdown-menu').toggle();
    });

    // Hide the dropdown when clicking outside of it
    $(document).click(function(event) {
      if (!$(event.target).closest('.dropdown').length) {
        $('.dropdown-menu').hide();
      }
    });
  });
</script>
<?php }else{ ?>
<script type="text/javascript" src="<?=$baseurl?>js_3rdparty/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<?php } ?>
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
   .abcds{
       display:none;
   }
   .nGY2 .nGY2GThumbnail {
        display: none !important;
    }
    .nGY2 .nGY2GThumbnail:first-child {
        display: block!important;
    }
    @media only screen and (max-width: 767px)
{    
    .mobile-offset{
         margin-left: 0;   
    }
    .btn{
        margin-bottom:10px;
    }
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
                    <h2 class="col-lg-9 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                
                <?php }
                elseif($knowledge_details[0]['file_type'] == "word")
                { ?>
                <div class="row">
                    <h2 class="col-lg-7 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download Document" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1493') { echo $langlbl['title'] ; } } ?></a></h2>
                    
                <?php } 
                else {?>    
                <div class="row">
                    <h2 class="col-lg-9 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                <?php }  
                //if($knowledge_details[0]['shared'] == "shared") { ?>
                    <!--<h2 class="col-lg-2 align-right"><span class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2131') { echo $langlbl['title'] ; } } ?></span></h2>-->
                <?php //} else { ?>
                    <h2 class="col-lg-1 align-right"><?php if(($knowledge_details[0]['file_type'] == "pdf")){ ?><a href="<?= $baseurl ?>Teacherknowledge/pdfmachinelearning/<?= $guid_id ?>"  class="btn btn-sm btn-success"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2130') { echo $langlbl['title'] ; } } ?></a><?php }else { ?><a href="javascript:void(0);" data-toggle="modal" data-target="#shareguides"  class="btn btn-sm btn-success"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2130') { echo $langlbl['title'] ; } } ?></a><?php } ?></h2>
                <?php //} ?>
                
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>teacherknowledge/kg_machinelearning/<?= $knowledge_details[0]['added_for'] ?>" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                
                <div class="row">
                    <h2 class="col-lg-12 align-left heading" style="font-size:20px !important; color:#ffa812 !important; ">( <?= ucfirst($knowledge_details[0]['classname']) ?> )</h2>
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
                            <video width="100%" height="350" controls>
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
                          
                        <!--<iframe src='http://docs.google.com/gview?embedded=true&url=https://you-me-globaleducation.org/school/img/<?php echo $knowledge_details[0]['links'] ?>' height='600px' width="100%" style="left:0px !important; position:inherit !important;"></iframe>-->
                        </div>
                        <?php
                    }else if(($knowledge_details[0]['file_type'] == "pdf")) { $valpag = $knowledge_details[0]['numpages'];
                        ?>
                        <div class="col-sm-6 mobile-offset offset-4">
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
                        <?php
                    }
                    ?>
                   <!-- <div class="col-sm-12 mt-3">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1490') { echo $langlbl['title'] ; } } ?></h2>
                        
                        <div class="col-sm-12 col-sm-offset-1 comments-section">
                			
                			<?php echo $this->Form->create(false , ['url' => ['action' => 'addmachinecomment'] , 'id' => "comment_form" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                		
                				<h4><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1489') { echo $langlbl['title'] ; } } ?>:</h4>
                				<div class="col-sm-12 clearfix">
                                    <div class="error" id="submitCommenterror">
                                    </div>
                                    <div class="success" id="submitCommentsuccess">
                                    </div>
                                </div>
                				<div class="col-sm-9" style="float:left">
                				    <textarea name="comment_text" id="comment_text" class="form-control" rows="2"></textarea>
                				    <input type="hidden" name="schoolid" id="school_id" value="<?= $knowledge_details[0]['school_id'] ?>" >
                				    <input type="hidden" name="kid" id="kid" value="<?= $knowledge_details[0]['id'] ?>" >
                				    <input type="hidden" name="commentId" id="commentId" value="0" >
                				</div>
                				
                				<div class="col-sm-3"  style="float:left">
                				    <button class="btn btn-primary btn-sm submit_comment" id="submit_comment"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1491') { echo $langlbl['title'] ; } } ?></button>
                				</div>
                			<?php echo $this->Form->end(); ?>
                            <div class="clearfix"></div>
                            
                			<h2 class="heading mt-4"><span id="comments_count"><?= count($comments_details) ?></span> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1487') { echo $langlbl['title'] ; } } ?>(s)</h2>
                			<hr>
                			<div id="comments-wrapper">
                				
                				<?php if (count($comments_details) != 0): ?>
                    				<?php foreach ($comments_details as $comment): ?>
                    				<div class="comment clearfix">
                    					<div class="comment-details">
                    						<span class="comment-name"><b><?php echo $comment["user_name"] ?></b> - </span>
                    						<span class="comment-date"><?php echo date("F j, Y ", $comment["created_date"]); ?></span>
                    						<p><?php echo $comment['comments']; ?></p>
                    						<a class="tchrmlcomm_reply-btn" href="javascript:void(0)" data-id="<?php echo $comment['id']; ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1488') { echo $langlbl['title'] ; } } ?></a>
                    					</div>
                    					<div class="col-sm-12 clearfix">
                                            <div class="error" id="replyCommenterror">
                                            </div>
                                            <div class="success" id="replyCommentsuccess">
                                            </div>
                                        </div>
                    					<form class="reply_form clearfix" id="tchrmachinelearncomment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
                    				        <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                    					    <input type="hidden" name="r_kid" id="r_kid" value="<?= $knowledge_details[0]['id'] ?>" >
                    					    <input type="hidden" name="skulid" id="skulid" value="<?= $knowledge_details[0]['school_id'] ?>" >
                    						<button type="submit" class="btn btn-primary btn-xs pull-right submit-reply"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1494') { echo $langlbl['title'] ; } } ?></button>
                    					</form>
                    
                    					
                    					<?php 
                    				$hostname = "localhost";
    $username = "youmeglo_globaluser";
    $password = "DFmp)9_p%Kql";
    $database = "youmeglo_globalweb";
                                        
                                        $con = mysqli_connect($hostname, $username, $password, $database); 
                                        if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 
                                       
                                        
                    					$replies = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `machine_learning_comments` WHERE parent = '".$comment['id']."'")) ?>
                    					<div class="replies_wrapper_<?php echo $comment['id']; ?>">
                    						<?php if (isset($replies_details)): ?>
                    							<?php foreach ($replies_details as $reply): 
                    							if($reply['parent'] == $comment['id'])
                    							{
                    							?>
                    								
                    								<div class="comment reply clearfix">
                    									<div class="comment-details">
                    										<span class="comment-name"><b><?php echo $reply["user_name"] ?></b> - </span>
                    										<span class="comment-date"><?php echo date("F j, Y ", $reply["created_date"]); ?></span>
                    										<p><?php echo $reply['comments']; ?></p>
                    									</div>
                    								</div>
                    							<?php
                    							}
                    							endforeach ?>
                    						<?php endif ?>
                    					</div>
                    				</div>
                    				<?php endforeach ?>
                    			<?php else: ?>
                    				<h4>Be the first to comment on this post</h4>
                    			<?php endif ?>
                				
                			</div>
                		</div>
                        
                       
                    </div>-->
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2132') { echo $langlbl['title'] ; } } ?></h6>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'sharerequest'] , 'id' => "sharerequestform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                
                
                <div class="wrapper">
                    <div class="row clearfix" style-"margin-top:10px; margin-bottom:10px;">
                        <div class="col-md-6">
                            <select class="form-control clsgrade class_s" id="clsgrade1" name="grades[]" placeholder="Choose Class" required  style="margin-right:15px !important;">
                                <?php foreach($empclses_details as $empdtl) { ?>
                	               <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php  $class_kin = array('Maternelle', 'Creche');  if(in_array($empclses_details[0]['class']['school_sections'], $class_kin)){  $display = "none";  $require = "";} else{ $display = "block"; $require = "required";} ?>
                        <div class="col-md-4" id="subdiv1"  style="display:<?= $display ?>">
                            <select class="form-control subgrade" id="subgrade1" name="subjects[]" placeholder="Choose Subjects" style="display:<?= $display ?>" <?= $require ?>>
                                <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '300') { echo $langlbl['title'] ; } } ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn add-btn"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <!--<div class="col-md-6">
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
                    </div>-->
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                            <div class="input-group date" id="sfdatetimepicker1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#sfdatetimepicker1"  name="start_date" id="start_date" required/>
                                <div class="input-group-append" data-target="#sfdatetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                            <div class="input-group date" id="stdatetimepicker2" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#stdatetimepicker2" name="end_date" id="end_date" required>
                                <div class="input-group-append" data-target="#stdatetimepicker2" data-toggle="datetimepicker">
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
                        <button type="submit" class="btn btn-primary sharereqbtn" id="sharereqbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2130') { echo $langlbl['title'] ; } } ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
</div>
<?php if(($knowledge_details[0]['file_type'] == "pdf")) { $valpag = $knowledge_details[0]['numpages']; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/nanogallery2/dist/jquery.nanogallery2.js"></script>

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
<?php } else{ ?>
<script type="text/javascript" src="<?=$baseurl?>js_3rdparty/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
 
  $('#sfdatetimepicker1').datetimepicker({
     format: 'DD-MM-YYYY HH:mm',
     minDate:new Date()
 });
 
  $('#stdatetimepicker2').datetimepicker({
    format: 'DD-MM-YYYY HH:mm',
    minDate:new Date()
 });
 
      // allowed maximum input fields
      var max_input = 5;
 
      // initialize the counter for textbox
      var x = 1;
 
      // handle click event on Add More button
      $('.add-btn').click(function (e) {
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          
          $('.wrapper').append(`
                <div class="row clearfix" style="margin-top:10px; margin-bottom:10px;">
                    <div class="col-md-6">
                        <select class="form-control clsgrade class_s" id="clsgrade`+x+`" name="grades[]" placeholder="Choose Class" style="margin-right:15px !important;" required>
                            <?php foreach($empclses_details as $empdtl) { ?>
                	               <option  value="<?= $empdtl['class']['id'] ?>" ><?= $empdtl['class']['c_name']."-".$empdtl['class']['c_section']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                        </select>
                    </div>
                    <?php  $class_kin = array('Maternelle', 'Creche');  if(in_array($empclses_details[0]['class']['school_sections'], $class_kin)){  $display = "none";  $require = "";} else{ $display = "block"; $require = "required";} ?>
                    <div class="col-md-4" id="subdiv`+x+`" style="display:<?= $display ?>">
                        <select class="form-control subgrade" id="subgrade`+x+`"  name="subjects[]"  style="display:<?= $display ?>" <?= $require ?>>
                            <option value=""><?php echo $subjectssch ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                       <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
         `); // add input field
        }
      });
 
      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
      })
      
      $('.wrapper').on("change", ".clsgrade", function (e) {
        
        var gradeid = $(this).val();
        var baseurl = window.location.pathname.split('/')[1];
        var baseurl = "/" + baseurl;
        var id = this.id;
        var splitid = id.split("clsgrade");
        //alert(splitid[1]);
        $("#subgrade"+splitid[1]).html("");
        
        var subid = '';
        $.ajax({
            type:'POST',
            url: baseurl + '/teacherdashboard/getsubjecttchrnew',
            data:{'clsid':gradeid, 'subid':subid},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success:function(html){
                if(html)
                {    
                    if(html.subjectdisplay == 1){
                        $("#subdiv"+splitid[1]).show();
                        $("#subgrade"+splitid[1]).show();
                        $("#subgrade"+splitid[1]).prop('required',true);
                    }else{
                        $("#subdiv"+splitid[1]).hide();
                        $("#subgrade"+splitid[1]).hide();
                        $("#subgrade"+splitid[1]).prop('required',false);
                    }
                    $("#subgrade"+splitid[1]).html(html.subjectname);
                }
          
            }

        });
  
      })
 
    });
</script>
<?php } ?>
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
     #left-sidebar { left: -250px; }
    #main-content { width:100%;}
    #comment_form { margin-top: 10px; }
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
                <?php
                
                if($knowledge_details[0]['file_type'] == "pdf")
                { ?>
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <!--<h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download PDF" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1492') { echo $langlbl['title'] ; } } ?></a></h2>-->
                    <!--<h2 class="col-lg-2 align-right"><a href="javascript:void(0);"  data-file="<?php echo $knowledge_details[0]['links']?>" title="View PDF" class="btn btn-sm btn-success viewpdffile"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1498') { echo $langlbl['title'] ; } } ?></a></h2>-->
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>Schoolknowledge/kg_machinelearning/<?= $knowledge_details[0]['added_for'] ?>" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <div class="row">
                    <h2 class="col-lg-12 align-left heading" style="font-size:20px !important; color:#ffa812 !important; ">( <?= ucfirst($knowledge_details[0]['classname']) ?> )</h2>
                </div>
                <?php }
                elseif($knowledge_details[0]['file_type'] == "word")
                { ?>
                <div class="row">
                    <h2 class="col-lg-9 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $knowledge_details[0]['links']?>" title="Download Document" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1493') { echo $langlbl['title'] ; } } ?></a></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>Schoolknowledge/kg_machinelearning/<?= $knowledge_details[0]['added_for'] ?>" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                <?php } 
                else {?>    
                <div class="row">
                    <h2 class="col-lg-11 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>Schoolknowledge/kg_machinelearning/<?= $knowledge_details[0]['added_for'] ?>" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                    <h2 class="col-lg-12 align-left heading" style="font-size:20px !important; color:#ffa812 !important; ">( <?= ucfirst($knowledge_details[0]['classname']) ?> )</h2>
                </div>
                <?php } 
                if(!empty($knowledge_details[0]['description'])) {?>
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
                        <!--<iframe src='http://docs.google.com/gview?embedded=true&url=https://you-me-globaleducation.org/school/img/<?php echo $knowledge_details[0]['links']?>' height='600px' width="100%"></iframe>-->
                        </div>
                        <?php
                    }else if(($knowledge_details[0]['file_type'] == "pdf"))
                    {
                        $valpag = $knowledge_details[0]['numpages'];
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
                       <!-- <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                            <?php
                            for ($x = 0; $x < $valpag; $x++) {
                            ?>    
                            <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links'] ?><?=$x;?>.jpg" style="width: 100%;">
                            <?php  } ?>
                        </div>-->
                        <?php
                    }
                    ?>
                    <div class="col-sm-12 mt-3">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1490') { echo $langlbl['title'] ; } } ?></h2>
                        
                        
                        
                        <div class="col-sm-12 col-sm-offset-1 comments-section">
                			<!-- comment form -->
                			
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
                            
                			<!-- Display total number of comments on this post  -->
                			<h2 class="heading mt-4"><span id="comments_count"><?= count($comments_details) ?></span> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1487') { echo $langlbl['title'] ; } } ?>(s)</h2>
                			<hr>
                			<!-- comments wrapper -->
                			<div id="comments-wrapper">
                				
                				<?php if (count($comments_details) != 0): ?>
                    				<!-- Display comments -->
                    				<?php foreach ($comments_details as $comment): ?>
                    				<!-- comment -->
                    				<?php //print_r($comment); ?>
                    				<div class="comment clearfix">
                    					<!--<img src="profile.png" alt="" class="profile_pic">-->
                    					<div class="comment-details">
                    						<span class="comment-name"><b><?php echo $comment["user_name"] ?></b> - </span>
                    						<span class="comment-date"><?php echo date("F j, Y ", $comment["created_date"]); ?></span>
                    						<p><?php echo $comment['comments']; ?></p>
                    						<a class="sclmlcomm_reply-btn" href="javascript:void(0)" data-id="<?php echo $comment['id']; ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1488') { echo $langlbl['title'] ; } } ?></a>
                    					</div>
                    					<div class="col-sm-12 clearfix">
                                            <div class="error" id="replyCommenterror">
                                            </div>
                                            <div class="success" id="replyCommentsuccess">
                                            </div>
                                        </div>
                    					<!-- reply form -->
                    					<form class="reply_form clearfix" id="sclmachinelearncomment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
                    					<!--	<input type="text" class="form-control" name="reply_text" id="reply_text" >-->
                    					    <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                    					    <input type="hidden" name="r_kid" id="r_kid" value="<?= $knowledge_details[0]['id'] ?>" >
                    					    <input type="hidden" name="skulid" id="skulid" value="<?= $knowledge_details[0]['school_id'] ?>" >
                    						<button type="submit" class="btn btn-primary btn-xs pull-right submit-reply"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1494') { echo $langlbl['title'] ; } } ?></button>
                    					</form>
                    
                    					<!-- GET ALL REPLIES -->
                    					
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
                    								<!-- reply -->
                    								
                    								<div class="comment reply clearfix">
                    									<!--<img src="profile.png" alt="" class="profile_pic">-->
                    									<div class="comment-details">
                    										<span class="comment-name"><b><?php echo $reply["user_name"] ?></b> - </span>
                    										<span class="comment-date"><?php echo date("F j, Y ", $reply["created_date"]); ?></span>
                    										<p><?php echo $reply['comments']; ?></p>
                    										<!--<a class="reply-btn" href="#">reply</a>-->
                    									</div>
                    								</div>
                    							<?php
                    							}
                    							endforeach ?>
                    						<?php endif ?>
                    					</div>
                    				</div>
                    					<!-- // comment -->
                    				<?php endforeach ?>
                    			<?php else: ?>
                    				<h4>Be the first to comment on this post</h4>
                    			<?php endif ?>
                				
                			</div>
                			<!-- // comments wrapper -->
                		</div>
                		<!-- // comments section -->
                        
                       
                    </div>
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
<?php } else{ ?>
<script type="text/javascript" src="<?=$baseurl?>js_3rdparty/popper.min.js"></script>
<script src="<?=$baseurl?>js/libscripts.bundle.js"></script>
<script src="<?=$baseurl?>js/index.js"></script>
<?php } ?>
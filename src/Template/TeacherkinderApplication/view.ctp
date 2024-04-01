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
</style>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row clearfix container"><h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '888') { echo $langlbl['title'] ; } } ?>*: </h2></div>
                <div class="row clearfix container mt-2 mb-4">1. <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1977') { echo $langlbl['title'] ; } } ?> <a href="https://chrome.google.com/webstore/detail/docs-online-viewer/gmpljdlgcdkljlppaekciacdmdlhfeon?hl=en" target="_blank"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1980') { echo $langlbl['title'] ; } } ?></a></div>
                <?php
                $fileext = explode(".", $knowledge_details[0]['links']);
                $count = count($fileext);
                $key = $count-1;
                //echo $fileext[$key];
                ?>
                <div class="row">
                    
                    <h2 class="col-lg-6 heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <ul class="header-dropdown">
                        <!--<li><a href="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['links']?>" title="Download Data" download class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1979') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php if (($fileext[$key] == "doc") || ($fileext[$key] == "docx") || ($fileext[$key] == "ppt") || ($fileext[$key] == "pptx") || ($fileext[$key] == "xls") || ($fileext[$key] == "xlsx"))
                        { ?>
                        <li><a href="https://docs.google.com/viewer?url=https://you-me-globaleducation.org/school/applications_data/<?php echo $knowledge_details[0]['links']?>&embedded=true&chrome=false&dov=1" original-url="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['links']?>" title="View Data" target="_blank" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1978') { echo $langlbl['title'] ; } } ?></a></li>
                        <?php } else { ?>
                        <li><a href="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['links']?>" title="View Data" target="_blank" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1978') { echo $langlbl['title'] ; } } ?></a></li>
                        
                        <?php } ?>-->
                        <li><a href="<?=$baseurl?>TeacherkinderApplication" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                    <!--<h2 class="col-lg-8 align-left heading" style="font-size:20px !important; "><?= ucwords($knowledge_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['links']?>" title="Download Data" download class="btn btn-sm btn-success">Download</a></h2>
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['links']?>" title="View Data" target="_blank" class="btn btn-sm btn-success">View</a></h2>
                    
                    <h2 class="col-lg-1 align-right"><a href="<?=$baseurl?>TeacherkinderApplication" title="Back"  class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>-->
               </div>
                <?php 
                if(!empty($knowledge_details[0]['description'])) {?>
                <p class="mt-3"><b>Description - </b><?= ucfirst($knowledge_details[0]['description']) ?></p>
                <?php } ?>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <!--<?php if(!empty($knowledge_details[0]['image'] )) { ?>
                    <div class="col-sm-12 text-center">
                        <img src="<?=$baseurl?>applications_data/<?php echo $knowledge_details[0]['image']?>" width="350px" height="400px">
                    </div>
                    <?php } else { ?>
                    <div class="col-sm-12 text-center">
                        <img src="https://you-me-globaleducation.org/youme-logo.png" style="padding: 135px 0;border: 1px solid #cccccc;" width="350px" height="400px">
                    </div>
                    <?php } ?>
                    ?>-->
                    <!--<div class="col-sm-12 text-center">
                    <iframe src='http://docs.google.com/gview?embedded=true&url=https://you-me-globaleducation.org/school/img/<?php echo $knowledge_details[0]['links']?>' height='600px' width="100%"></iframe>
                    </div>-->
                    <?php $valpag = $knowledge_details[0]['numpages'];  ?>
                    <div class="col-sm-12 text-center" style="margin: auto;height: 500px;overflow: scroll;">
                        <?php
                        for ($x = 0; $x < $valpag; $x++) {
                        ?>    
                        <img src="http://you-me-globaleducation.org/school/<?=$knowledge_details[0]['dirname'];?>/<?= $knowledge_details[0]['links']; ?><?=$x;?>.jpg" style="width: 100%;">
                        <?php  } ?>
                    </div>
                        
                        
                    <div class="col-sm-12 mt-3">
                        <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1490') { echo $langlbl['title'] ; } } ?></h2>
                        
                        
                        <div class="col-sm-12 col-sm-offset-1 comments-section">
                			<!-- comment form -->
                			
                			<?php echo $this->Form->create(false , ['url' => ['action' => 'addcomment'] , 'id' => "comment_form" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                		
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
                    						<a class="tchrappcomm_reply-btn" href="javascript:void(0)" data-id="<?php echo $comment['id']; ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1488') { echo $langlbl['title'] ; } } ?></a>
                    					</div>
                    					<div class="col-sm-12 clearfix">
                                            <div class="error" id="replyCommenterror">
                                            </div>
                                            <div class="success" id="replyCommentsuccess">
                                            </div>
                                        </div>
                    					<!-- reply form -->
                    					<form class="reply_form clearfix" id="tchrappcomment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
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
                                       
                                        
                    					$replies = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `knowledge_center_comments` WHERE parent = '".$comment['id']."'")) ?>
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



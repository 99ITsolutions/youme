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
</style>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <?php if($content_details[0]['file_type'] == "pdf")
                { ?>
                <div class="row">
                    <h2 class="col-lg-10 align-left heading" style="font-size:20px !important; "><?= ucwords($content_details[0]['title']) ?></h2>
                    <h2 class="col-lg-2 align-right"><a href="<?=$baseurl?>img/<?php echo $content_details[0]['links']?>" title="Download PDF" download class="btn btn-sm btn-success">Download PDF</a></h2>
                </div>
                <?php } else {?>    
                    <h2 class="heading" style="font-size:20px !important; "><?= ucwords($content_details[0]['title']) ?></h2>
                <?php } 
                if(!empty($content_details[0]['description'])) {?>
                <p class="mt-3"><b>Description - </b><?= ucfirst($content_details[0]['description']) ?></p>
                <?php } ?>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <?php if($content_details[0]['file_type'] == "video")
                    { ?>
                        <div class="col-sm-12 text-center">
                            <?php
                            if($content_details[0]['video_type']  == "youtube" )
                            {
                            ?>
                            <iframe width="860" height="350" src="<?= $content_details[0]['links'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php
                            }
                            elseif($content_details[0]['video_type']  == "vimeo" )
                            {
                            ?>
                            <iframe src="<?= $content_details[0]['links'] ?>" width="860" height="350" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                            <?php
                            }
                            ?>
                            
                        </div>
                    <?php
                    }
                    if($content_details[0]['file_type'] == "audio")
                    {
                        ?>
                        <div class="col-sm-4 text-center"></div>
                        
                        <?php
                        if(!empty($content_details[0]['image'] )) { ?>
                        <div class="col-sm-4 text-center">
                             <div id="audio" style="position:absolute; padding-top:150px; text-align: center !important;">
                                <audio controls src="<?=$baseurl?>img/<?php echo $content_details[0]['links']?>" type="audio/mpeg" style="margin-left:20px !important;">
                                    Your browser does not support the
                                    <code>audio</code> element.
                                </audio>
                            </div>
                            <img src="<?= $baseurl ?>/webroot/img/<?= $content_details[0]['image']?>" style="min-width:350px; min-height:400px; max-width:350px; max-height:400px;border: 1px solid; text-align: center !important;">
                            <!--<div class="col-sm-4 text-center" style="padding-top:150px; background: url(<?= $baseurl ?>/webroot/img/<?= $content_details[0]['image']?>)no-repeat center; max-width: 350px; height: 400px; border: 1px solid; text-align: center !important;">-->
                           
                        </div>
                        <?php } else { ?>
                            <div class="col-sm-4 text-center" style="padding-top:150px; background: url(http://www.you-me-globaleducation.org/youme-logo.png)no-repeat center; max-width: 350px; height: 400px; border: 1px solid; text-align: center !important;">
                                <audio controls src="<?=$baseurl?>img/<?php echo $content_details[0]['links']?>" type="audio/mpeg">
                                    Your browser does not support the
                                    <code>audio</code> element.
                                </audio>
                            </div>
                        <?php } ?>
                        <?php
                    }
                    if($content_details[0]['file_type'] == "pdf")
                    {
                        if($content_details[0]['image'] != '') { ?>
                        <div class="col-sm-12 text-center">
                             <img src="<?=$baseurl?>img/<?php echo $content_details[0]['image']?>" style="padding: 135px 0;border: 1px solid #cccccc;" width="350px" height="400px">
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-12 text-center">
                            <img src="http://www.you-me-globaleducation.org/youme-logo.png" style="padding: 135px 0;border: 1px solid #cccccc;" width="350px" height="400px">
                        </div>
                        <?php } ?>
                       
                        <!--<iframe src="<?=$baseurl?>img/<?php echo $content_details[0]['links']?>" width="80%" height="400px"></iframe>-->
                      
                        <?php
                    }
                    ?>
                    
                    <div class="col-sm-12 mt-3">
                        <h2 class="heading">Comments & Reviews</h2>
                        
                        
                        
                        <div class="col-sm-12 col-sm-offset-1 comments-section">
                			<!-- comment form -->
                			
                			<?php echo $this->Form->create(false , ['url' => ['action' => 'addcomment'] , 'id' => "comment_form" , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                		
                				<h4>Post a comment:</h4>
                				<div class="col-sm-12 clearfix">
                                    <div class="error" id="submitCommenterror">
                                    </div>
                                    <div class="success" id="submitCommentsuccess">
                                    </div>
                                </div>
                				<div class="col-sm-9" style="float:left">
                				    <textarea name="comment_text" id="comment_text" class="form-control" rows="2"></textarea>
                				    <input type="hidden" name="schoolid" id="school_id" value="<?= $content_details[0]['school_id'] ?>" >
                				    <input type="hidden" name="kid" id="kid" value="<?= $content_details[0]['id'] ?>" >
                				    <input type="hidden" name="commentId" id="commentId" value="0" >
                				</div>
                				
                				<div class="col-sm-3"  style="float:left">
                				    <button class="btn btn-primary btn-sm submit_comment" id="submit_comment">Submit comment</button>
                				</div>
                			<?php echo $this->Form->end(); ?>
                            <div class="clearfix"></div>
                            
                			<!-- Display total number of comments on this post  -->
                			<h2 class="heading mt-4"><span id="comments_count"><?= count($comments_details) ?></span> Comment(s)</h2>
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
                    						<a class="studentlib_reply-btn" href="javascript:void(0)" data-id="<?php echo $comment['id']; ?>">reply</a>
                    					</div>
                    					<div class="col-sm-12 clearfix">
                                            <div class="error" id="replyCommenterror">
                                            </div>
                                            <div class="success" id="replyCommentsuccess">
                                            </div>
                                        </div>
                    					<!-- reply form -->
                    					<form class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
                    					<!--	<input type="text" class="form-control" name="reply_text" id="reply_text" >-->
                    					    <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                    					    <input type="hidden" name="r_kid" id="r_kid" value="<?= $content_details[0]['id'] ?>" >
                    					    <input type="hidden" id="mdkid" value="<?= md5($content_details[0]['id']) ?>" >
                    					    <input type="hidden" name="skulid" id="sclid" value="<?= $content_details[0]['school_id'] ?>" >
                    						<button type="submit" class="btn btn-primary btn-xs pull-right submit-reply">Submit reply</button>
                    					</form>
                    
                    					<!-- GET ALL REPLIES -->
                    					
                    					<?php 
                    					$hostname = "localhost";
                                        $username = "you_me_global";
                                        $password = "Pass!234";
                                        $database = "you_me_global";
                                        
                                        $con = mysqli_connect($hostname, $username, $password, $database); 
                                        if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 
                                       
                                        
                    					$replies = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `library_comments` WHERE parent = '".$comment['id']."'")) ?>
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



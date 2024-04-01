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
<?php
foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '2089') { $lbl2089 = $langlbl['title'] ; } 
}
?>



<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6  heading align-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '942') { echo $langlbl['title'] ; } } ?> > <?= $class_details['c_name']."-".$class_details['c_section'] ?> (<?= $class_details['school_sections'] ?>) (<?= $subject_details['subject_name'] ?>) > <?= $lbl2089 ?></h2>
                    <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>TeacherSubject?studentdetails=0&subid=<?= $subjectid ?>&gradeid=<?= $classid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                </div>
                
            </div>
            <div class="body">
                <div class="row clearfix mb-4">
                    <div class="col-sm-12 mt-3">
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
                				    <input type="hidden" name="classid" value="<?= $classid ?>">
                				    <input type="hidden" name="subjectid" value="<?= $subjectid ?>">
                				    <input type="hidden" name="schoolid" value="<?= $schoolid ?>">
                				</div>
                				
                				<div class="col-sm-3"  style="float:left">
                				    <button class="btn btn-primary btn-sm submit_comment" id="submit_comment"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1491') { echo $langlbl['title'] ; } } ?></button>
                				</div>
                			<?php echo $this->Form->end(); ?>
                            <div class="clearfix"></div>
                            <?php //print_r($comments_details); ?>
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
                    						<span class="comment-date"><?php echo date("F j, Y h:i A", $comment["created_date"]); ?></span>
                    						<p><?php echo $comment['comments']; ?></p>
                    						<a class="teacherpost_reply-btn" href="javascript:void(0)" data-id="<?php echo $comment['id']; ?>"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1488') { echo $langlbl['title'] ; } } ?></a>
                    					</div>
                    					<div class="col-sm-12 clearfix">
                                            <div class="error" id="replyCommenterror">
                                            </div>
                                            <div class="success" id="replyCommentsuccess">
                                            </div>
                                        </div>
                                        
                                        <?php //echo $this->Form->create(false , ['url' => ['action' => 'replycomments'] , 'id' => "comment_reply_form_.$comment['id']." , 'enctype' => "multipart/form-data" , 'method' => "post"  ]); ?>
                		
                    					<form class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
                    					    <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                    					    <input type="hidden" id="gradeid" name="gradeid" value="<?= $classid ?>">
                        				    <input type="hidden" id="subid" name="subid" value="<?= $subjectid ?>">
                    						<button type="submit" class="btn btn-primary btn-xs pull-right tpsubmit-reply"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1494') { echo $langlbl['title'] ; } } ?></button>
                    					</form>
                    					
                    					
                    					<?php 
                    					$hostname = "localhost";
    $username = "youmeglo_globaluser";
    $password = "DFmp)9_p%Kql";
    $database = "youmeglo_globalweb";
                                        
                                        $con = mysqli_connect($hostname, $username, $password, $database); 
                                        if(mysqli_connect_error($con)){ echo "Connection Error."; die();} 
                                       
                                        
                    					$replies = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `discussion` WHERE parent = '".$comment['id']."'")) ?>
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
                    										<span class="comment-date"><?php echo date("F j, Y h:i A", $reply["created_date"]); ?></span>
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
                    				<h4><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1733') { echo $langlbl['title'] ; } } ?></h4>
                    			<?php endif ?>
                				
                			</div>
                			<!-- // comments wrapper -->
                		<!-- // comments section -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    



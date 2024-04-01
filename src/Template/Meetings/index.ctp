<style>
  /*  .bg-dash
    {
        background-color:#242E3B !important;
    }*/
</style>

    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
	           <div class="body">
	                <div class="row clearfix">
	                    <?php 
	                    $subjectss = explode(",", $subjectname);
	                    $subjectids = explode(",", $subjectid);
	                    //echo $subjectname;
	                    //echo $subjectid;
	                    foreach($subjectss as $key => $subs)
	                    { 
	                        if(!empty($subs)) 
	                        {
    	                    ?>
    	                    <div class="col-lg-3 col-md-6 col-sm-6 schedulemeeting">
                                <div class="card text-center bg-dash ">
                                    <a  class="colorBtn" href="<?=$baseurl?>meetings/links/<?= $classid ?>/<?= $subjectids[$key] ?>" class="meetinglist" >
                                        <div class="body" style="height:90px !important;">
                                            <div class="p-15 text-light teachersubdtlss">
                                                <span><b> <?= $subs ?></b></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
	                        }
	                    } ?>
                        
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>


 <!------------------ Submit Request --------------------->
 

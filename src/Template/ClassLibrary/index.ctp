<style>
    /*.bg-dash
    {
        background-color:#242E3B !important;
    }*/
</style>
<?php 
if(!empty($student_subjects))
{ 
    if($student_subjects[0]['library_access'] == 0) { ?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <ul class="header-dropdown">
                        <li><a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></li>
                    </ul>
                </div>
	            <div class="body row  text center">
                    <h2 class="col-md-12" style="font-size: 1.2rem; color:#1B0951 !important; float:left;"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2111') { echo $langlbl['title'] ; } } ?></h2>
                </div>
	        </div>
	    </div>
    </div>
    <?php } else { ?>
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
	                    <?php foreach($student_subjects as  $subj)
	                    { 
	                       // print_r($subj);
	                        $subjects = explode(",", $subj['subjects_name']);
	                        $subids = explode(",", $subj['subjects_ids']);
	                        $gradeids = $subj['class'];
	                        $subjectids = explode(",", $subj['class_subjects']['subject_id']);
	                        foreach($subjects as $key => $sub) {
	                            ?>
    	                    <div class="col-lg-3 col-md-6 col-sm-6 schedulemeeting">
                                <div class="card text-center bg-dash ">
                                    <a  class="colorBtn" href="<?=$baseurl?>classLibrary/library/<?= $gradeids ?>/<?= $subids[$key] ?>" class="meetinglist" >
                                        <div class="body" style="height:90px !important;">
                                            <div class="p-15 text-light teachersubdtlss">
                                                <span><b> <?= $sub ?></b></span>
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
	<?php
	}
	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 <?php
}
?>


 
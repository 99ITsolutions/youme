<style>
	    /*.bg-dash
	    {
	        background-color:#242E3B !important;
	    }*/
	</style>
<?php //print_r($emp_details);
if(!empty($emp_details))
{ ?>
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
	                    <?php foreach($employees_details as $empdtl)
                        { 
                            $classname =    $empdtl['class']['c_name']."-". $empdtl['class']['c_section']." (". $empdtl['class']['school_sections'] .")";
    	                    ?>
    	                    <div class="col-lg-3 col-md-6 col-sm-6 schedulemeeting">
                                <div class="card text-center bg-dash ">
                                    <a  class="colorBtn" href="<?=$baseurl?>meetingLink/linklist/<?= $empdtl['class']['id'] ?>/<?= $empdtl['subjects']['id'] ?>" class="meetinglist" >
                                        <div class="body" style="height:90px !important;">
                                            <div class="p-15 text-light teachersubdtlss">
                                                <span><b> <?= $classname ." (". $empdtl['subjects']['subject_name'] . ")"?></b></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                        } ?>
	                    <?php /*foreach($employees_details as $empdtl)
	                    { 
	                        $grades = explode(",", $empdtl['gradesName']);
	                        $subjects = explode(",", $empdtl['subjectName']);
	                        $gradeids = explode(",", $empdtl['grades']);
	                        $subjectids = explode(",", $empdtl['subjects']);
	                        
	                        foreach($grades as $key=>$val):
    	                    ?>
    	                    <div class="col-lg-3 col-md-6 col-sm-6 schedulemeeting">
                                <div class="card text-center bg-dash ">
                                    <!--<a style="color:#FFFFFF !important" href="javascript:void(0);" data-classname="<?= $grades[$key] ?>" data-subname="<?= $subjects[$key] ?>" data-class="<?= $gradeids[$key] ?>" data-subject ="<?= $subjectids[$key] ?>" class="subclsmeeting" >-->
                                    <a  class="colorBtn" href="<?=$baseurl?>meetingLink/linklist/<?= $gradeids[$key] ?>/<?= $subjectids[$key] ?>" class="meetinglist" >
                                        <div class="body" style="height:90px !important;">
                                            <div class="p-15 text-light teachersubdtlss">
                                                <span><b> <?= $grades[$key] ." (". $subjects[$key] . ")"?></b></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            endforeach; */
	                    //} ?>
                        
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<?php	echo $this->Form->create(false , ['method' => "post"  ]); ?>
	<?php echo $this->Form->end(); ?>
 <?php
}
?>

 <!------------------ Submit Request --------------------->
 

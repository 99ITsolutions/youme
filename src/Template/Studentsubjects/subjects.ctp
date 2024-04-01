<style>
	    .bg-dash
	    {
	        background-color:#242E3B !important;
	    }
	    h2.heading a
	    {
	        color:#242E3B !Important;
	    }
	    
	</style>
<?php 
if(!empty($student_subjects))
{ ?>
    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <div class=" row">
                        <h2 class="col-md-6 heading text-left">StudentProfile > Subjects</h2>
                        <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success">Back</a></h2>
                    </div>
                </div>
                    
	           <div class="body">
	               <div class="row clearfix">
	                    <?php
	                    foreach($student_subjects as $s_sub)
	                    {
	                        //print_r($s_sub);
	                        if(!empty($s_sub['subjects_name']))
	                        {
	                            $subjects = explode(",", $s_sub['subjects_name']);
	                            $subIds = explode(",", $s_sub['class_subjects']['subject_id']);
	                            //print_r($subjects);
	                            foreach($subjects as $key => $sub)
	                            {
            	                    ?>
            	                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card text-center bg-dash ">
                                            <div class="body" style="height:90px !important;">
                                                <div class="p-15 text-light subjctsdata">
                                                    <span><b><a style="color:#FFFFFF !important" href="javascript:void(0);" class="subjectdtl" data-subid="<?= $subIds[$key] ?>" data-clsid="<?= $s_sub['class'] ?>"><?= $sub ?> </a></b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
	                            }
	                        }
                        }
                        ?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<?php
}
?>
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>




<div class="modal bd-example-modal-lg classmodal animated zoomIn" aria-labelledby="myLargeModalLabel" id="subjectdetails" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 940px;">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Subject Details</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
            <div class="modal-body">
                

    <div class="row clearfix">
	    <div class="col-lg-12">
	        <div class="card">
	           <div class="body">
                    <div class="col-md-12 col-sm-12" style="border:1px solid #242e3b; border-radius:5px; margin:15px 0">
                        <div class="row clearfix">
                            
    	                        <div class="col-lg-2 col-md-2 col-sm-6 mt-2">
                                    <div class="card text-center bg-dash ">
                                        <div class="body" style="padding:9px !important;">
                                            <div class="text-light">
                                                <span id="subjectName"><b></b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6 mt-2">
                                    <div class="card text-center bg-dash ">
                                        <div class="body" style="padding:9px !important;">
                                            <div class="text-light">
                                                <span><b><a id="attendance_std" class="attendance_std">Attendance</a></b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7"></div>
                                
    	                    
        	                    <!--<div class="col-lg-4 col-md-4 col-sm-12 mt-2 mb-2">
                                    <img src="<?= $baseurl ?>/img/cal.png" width="300px" style="border:1px solid #242e3b">
                                </div>-->
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span><b>Teacher Details</b></span>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-4 mt-2 mb-2">
                                <span id="teacherpic"></span>
                            </div>
                            
                            <div class="col-lg-5 col-md-6 col-sm-6 mt-2 mb-2">
                                <p><span>Name: </span><span id="teachername"></span></p>
                                <p><span>Email: </span><span id="teacheremail"></span></p>
                                <p><span>Mobile: </span><span id="teachermobile"></span></p>
                                <p><span>Qualification: </span><span id="teacherqual"></span></p>
                            </div>
                        
                        </div>
	               </div>
	                
	                <div class="row clearfix">
	                    
	                    <div class="col-lg-3 col-md-6 col-sm-6" style="max-width: 20% !important;">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../subjectLibrary'] , 'id' => "subjectlibraryform" , 'method' => "post"  ]);  ?>
                                <button type="submit" class="card text-center bg-dash ">
                                    <div class="body" style="height:60px !important;">
                                        <div class=" text-light subjctsdata">
                                            <span><b>Library</b></span>
                                        </div>
                                    </div>
                                </button>
                                <input type="hidden" id="library_classId" name="classId">          
                                <input type="hidden" id="library_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6" style="max-width: 20% !important;">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../studentDiscussion'] , 'id' => "studentdiscussionform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="height:60px !important;">
                                    <div class=" text-light subjctsdata">
                                        <span><b>Discussion</b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="discussion_classId" name="classId">          
                            <input type="hidden" id="discussion_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6" style="max-width: 20% !important;">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../assessments'] , 'id' => "assessmentsform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="height:60px !important;">
                                    <div class=" text-light subjctsdata">
                                        <span><b>Assessments</b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="assessments_classId" name="classId">          
                            <input type="hidden" id="assessments_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6" style="max-width: 20% !important;">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../examListing'] , 'id' => "examListingform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="height:60px !important;">
                                    <div class=" text-light subjctsdata text-center">
                                        <span><b>Exams</b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="exams_classId" name="classId">          
                            <input type="hidden" id="exams_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6" style="max-width: 20% !important;">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../SubjectGrade'] , 'id' => "SubjectGradeform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="height:60px !important;">
                                    <div class=" text-light subjctsdata text-center">
                                        <span><b>Grades</b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="grade_classId" name="classId">          
                            <input type="hidden" id="grade_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
	                </div>
            </div>
             
        </div>
    </div>
</div>     

<div class="modal bd-example-modal-lg classmodal animated zoomIn" id="attendnce_dtl" role="dialog">
    <div class="modal-dialog modal-lg" role="document"  style="max-width: 940px;">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Attendance</h6>
		        <button type="button" class=" close" id="studentclose" data-dismiss="modal">
  		   <span aria-hidden="true">&times;</span>
		  </button>
	    </div>
        <div class="modal-body">
            <div class="row clearfix">
        	    <div class="col-lg-12">
                    <div class="row clearfix">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-2 mb-2">
                            <img src="<?= $baseurl ?>/img/cal.png" width="628px" style="border:1px solid #242e3b">
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>     

            
    
<style>
	    /*.bg-dash
	    {
	        background-color:#242E3B !important;
	    }*/
	    h2.heading a
	    {
	        color:#242E3B !Important;
	    }
	    
.col-lg-2 {
    -ms-flex: 0 0 14%;
    flex: 0 0 14%;
    max-width: 14%;
    text-align:center;
    padding-right:7px !important;
    padding-left:7px !important;
}

.page-loader-wrapper{
    display:none !important;
}

.card .loader{
   margin-left: 400px;
   overflow-y: hidden;
   overflow-x: hidden;
  
}


.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 3s linear infinite; /* Safari */
  animation: spin 3s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
 


	</style>
<?php 
if(!empty($student_subjects))
{ ?>
    <div class="row clearfix disabled">
	    <div class="col-lg-12">
	        <div class="card">
	            <div class="header">
                    <div class=" row">
                        <h2 class="col-md-6 heading text-left"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1718') { echo $langlbl['title'] ; } } ?></h2>
                        <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
                    </div>
                </div>
                    
	           
	           <div class="body" id="subrow">
	               <div class="row clearfix">
	                    <?php
	                    foreach($student_subjects as $s_sub)
	                    {
	                        //print_r($s_sub);
	                        if(!empty($s_sub['subjects_name']))
	                        {
	                            $subjects = explode(",", $s_sub['subjects_name']);
	                            $subIds = explode(",", $s_sub['subjects_ids']);
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1721') { echo $langlbl['title'] ; } } ?></h6>
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
                                    <!--<div class="card text-center bg-dash ">-->
                                        <!--<div class="body" style="padding:9px !important;">
                                            <div class="text-light">
                                                <span><b><a href="javascript:void(0)" id="attendance_std" class="attendance_std">Attendance</a></b></span>
                                            </div>
                                        </div>-->
                                        <?php echo $this->Form->create(false , [ 'url' => ['action' => '../subjectattendance'] , 'id' => "subjectattendanceform" , 'method' => "post"  ]);  ?>
                                            <button type="submit" class="card text-center bg-dash ">
                                                <div class="body" style="padding:9px !important; ">
                                                    <div class=" text-light subjctsdata">
                                                        <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '14') { echo $langlbl['title'] ; } } ?></b></span>
                                                    </div>
                                                </div>
                                            </button>
                                            <input type="hidden" id="attendnce_classId" name="classId">          
                                            <input type="hidden" id="attendnce_subId" name="subId">
                                        <?php  echo $this->Form->end(); ?>
                                    <!--</div>-->
                                    
                                </div>
                                <div class="col-lg-7"></div>
                                
    	                    
        	                    <!--<div class="col-lg-4 col-md-4 col-sm-12 mt-2 mb-2">
                                    <img src="<?= $baseurl ?>/img/cal.png" width="300px" style="border:1px solid #242e3b">
                                </div>-->
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1720') { echo $langlbl['title'] ; } } ?></b></span>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-4 mt-2 mb-2">
                                <span id="teacherpic"></span>
                            </div>
                            
                            <div class="col-lg-5 col-md-6 col-sm-6 mt-2 mb-2">
                                <p><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '81') { echo $langlbl['title'] ; } } ?>: </span><span id="teachername"></span></p>
                                <p><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '82') { echo $langlbl['title'] ; } } ?>: </span><span id="teacheremail"></span></p>
                                <p><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '49') { echo $langlbl['title'] ; } } ?>: </span><span id="teachermobile"></span></p>
                                <p><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '287') { echo $langlbl['title'] ; } } ?>: </span><span id="teacherqual"></span></p>
                            </div>
                        
                        </div>
	               </div>
	                
	                <div class="row clearfix">
	                    
	                    <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../subjectLibrary'] , 'id' => "subjectlibraryform" , 'method' => "post"  ]);  ?>
                                <button type="submit" class="card text-center bg-dash ">
                                    <div class="body" style="padding:9px !important;">
                                        <div class=" text-light subjctsdata">
                                            <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '23') { echo $langlbl['title'] ; } } ?></b></span>
                                        </div>
                                    </div>
                                </button>
                                <input type="hidden" id="library_classId" name="classId">          
                                <input type="hidden" id="library_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../studentDiscussion'] , 'id' => "studentdiscussionform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1722') { echo $langlbl['title'] ; } } ?></b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="discussion_classId" name="classId">          
                            <input type="hidden" id="discussion_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../assessments'] , 'id' => "assessmentsform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1723') { echo $langlbl['title'] ; } } ?></b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="assessments_classId" name="classId">          
                            <input type="hidden" id="assessments_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../examListing'] , 'id' => "examListingform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata text-center">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1724') { echo $langlbl['title'] ; } } ?></b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="exams_classId" name="classId">          
                            <input type="hidden" id="exams_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../studyguide'] , 'id' => "studyguideform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata text-center">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1725') { echo $langlbl['title'] ; } } ?></b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="study_classId" name="classId">          
                            <input type="hidden" id="study_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../quiz'] , 'id' => "quizform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata text-center">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1727') { echo $langlbl['title'] ; } } ?></b></span>
                                    </div>
                                </div>
                            </button>
                            <input type="hidden" id="quiz_classId" name="classId">          
                            <input type="hidden" id="quiz_subId" name="subId">
                            <?php  echo $this->Form->end(); ?>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <?php echo $this->Form->create(false , [ 'url' => ['action' => '../SubjectGrade'] , 'id' => "SubjectGradeform" , 'method' => "post"  ]);  ?>
                            <button type="submit" class="card text-center bg-dash ">
                                <div class="body" style="padding:9px !important; ">
                                    <div class=" text-light subjctsdata text-center">
                                        <span class="text-center"><b><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1726') { echo $langlbl['title'] ; } } ?></b></span>
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
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '14') { echo $langlbl['title'] ; } } ?></h6>
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

            
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php

if(!empty($student_details)){
    if(!empty($_GET))
    {
        if($_GET['openmodal'] == 1)
        {
            ?>
            <script>
            var baseurl = window.location.pathname.split('/')[1];
            var baseurl = "/" + baseurl;
            var classid = <?php echo $_GET['classid']; ?>;
            var subjectid = <?php echo $_GET['subjectid']; ?>;
            var refscrf = $("input[name='_csrfToken']").val();
            $(function(){
                var row = document.getElementById("subrow");
                row.classList.add("loader");
                
                 // $("#subjectdetails").modal("show");
                 $.ajax({
                    url: baseurl +"/Studentsubjects/subjectdtl", 
                    data: {"classid":classid,"subjectid":subjectid,_csrfToken : refscrf}, 
                    type: 'post',
                    success: function(response){
                        
                        row.classList.remove("loader");
                        
                        if((response.emp_dtl[0]['email'] != "")) {
                            $("#subjectdetails").modal("show");
                            console.log(response);
                            $("#subjectName b").html(response.subname);
                            $("#teacherpic").html("<img src='"+origin+"/school/webroot/img/"+response.emp_dtl[0]['pict']+"' width='150px'>");
                            $("#teachername").html(response.emp_dtl[0]['f_name']+" "+response.emp_dtl[0]['l_name']);
                            $("#teacheremail").html(response.emp_dtl[0]['email']);
                            $("#teachermobile").html(response.emp_dtl[0]['mobile_no']);
                            $("#teacherqual").html(response.emp_dtl[0]['quali']);
                            $("#assessments_subId").val(response.subId);
                            $("#assessments_classId").val(classid);
                            $("#discussion_subId").val(response.subId);
                            $("#discussion_classId").val(classid);
                            $("#exams_subId").val(response.subId);
                            $("#exams_classId").val(classid);
                            $("#grade_subId").val(response.subId);
                            $("#grade_classId").val(classid);
                            $("#library_subId").val(response.subId);
                            $("#library_classId").val(classid);
                            $("#study_subId").val(subjectid);
                            $("#study_classId").val(classid);
                            $("#quiz_subId").val(subjectid);
                            $("#quiz_classId").val(classid);
                            $("#attendnce_subId").val(subjectid);
                            $("#attendnce_classId").val(classid);
                        }
                        else
                        {
                            swal("Error!", notchrass, "error");
                        }
                    }
                });
            });
            </script>
        <?php         
        }
        elseif($_GET['openmodal'] == 0)
        {
            ?>
            <script>
               $("#subjectdetails").modal("hide");
            </script>
            <?php
        }
    }
}
?>
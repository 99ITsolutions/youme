<?php
    $statusarray = array('Inactive','Active' );
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <h2 class="col-md-6 text-left heading">Teacher Dashboard > <span id="class"></span> (<span id="subject"></span>)   </h2>
                    <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>teacherdashboard" title="Back" class="btn btn-sm btn-success">Back</a></h2>
                </div>
                
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3">
                        <h5>Student List</h5>   
                        <ul>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#studentdata">Nancy Singla</a></li>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#studentdata">Amit Ahuja</a></li>
                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#studentdata">Beauty Demo</a></li>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash ">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF !important" href="<?= $baseurl ?>teacherPost"> Posts </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classGrade"> Grades </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classAssessment"> Assessments </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card text-center bg-dash">
                                    <div class="body" style="padding: 15px; !important;">
                                        <div class="p-10 text-light">
                                            <span><b><a style="color:#FFFFFF" href="<?= $baseurl ?>classExams"> Exams/Final </a></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

 <!------------------ Student Data --------------------->

    
<div class="modal classmodal animated zoomIn bd-example-modal-lg" id="studentdata" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
		        <h6 class="title" id="defaultModalLabel">Nancy Singla: I-B(English)</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
               <div class="row clearfix container">
                    <div class="table-responsive">
                        <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="stdinfodata_table" data-page-length='50'>
                            <thead class="thead-dark">
                                <tr>
                                    <th>Assessments</th>
                                    <th>Tests</th>
                                    <th>Exams</th>
                                </tr>
                            </thead>
                            <tbody id="stdinfobody" class="modalrecdel"> 
                                <tr>
                                   <td><a href="<?= $baseurl ?>detailAssessment">Assessment 1</a></td>
                                   <td>Test 1</td>
                                   <td>Exam 1</td>
                                </tr>
                                <tr>
                                   <td>Assessment 2</td>
                                   <td>Test 2</td>
                                   <td>Exam 2</td>
                                </tr>
                                
				            </tbody>
                        </table>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div>              

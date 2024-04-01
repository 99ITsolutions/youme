<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
            <div class="row">
                <h2 class="col-md-6  heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '942') { echo $langlbl['title'] ; } } ?> > <?= $class_details[0]['c_name'] ?> - <?= $class_details[0]['c_section'] ?> (<?= $class_details[0]['school_sections'] ?>) (<?= $subject_details[0]['subject_name'] ?>) > <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1859') { echo $langlbl['title'] ; } } ?> </h2>
                <h2 class="col-md-6 text-right"><a href="<?= $baseurl ?>TeacherSubject?studentdetails=0&gradeid=<?= $classid ?>&subid=<?= $subjectid ?>" title="Back" class="btn btn-sm btn-success"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '41') { echo $langlbl['title'] ; } } ?></a></h2>
            </div>
            <input type="hidden" name="classid" id="classid" value="<?= $classid ?>">
            <input type="hidden" name="subjectid" id="subjectid" value="<?= $subjectid ?>">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'index?studentdetails=0&subid='.$subjectid.'&gradeid='.$classid ] , 'id' => "examfilter" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                   
                <div class=" row text-right mt-2">
                    
                    <div class="form-group col-md-3">
                        <select class="form-control" id="all_exam_names" name="all_exam_names" required>
                            <option value="all" <?php if($EXMId == "all") { echo "Selected";} ?> ><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1051') { echo $langlbl['title'] ; } } ?></option>
                            <?php
                            foreach($exam_dtls as $exmtitles)
                            {
                            ?>
                                <option value="<?= $exmtitles['id'] ?>" <?php if($EXMId == $exmtitles['id']) { echo "Selected"; } ?> ><?= ucfirst($exmtitles['title']). " (". $exmtitles['exam_type'].")"  ?> </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="submit" name="submit" id="submit" class="btn btn-success">
                    </div>
                    
                   <!-- <div class="form-group col-md-8 align-right">
                        <a href="#" title="Back" class="btn btn-sm btn-success">Upload Exams</a>
                    </div>-->
                </div>
                <?php echo $this->Form->end(); ?>
                
                
            </div>
            
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '1876') { $lbl1876 = $langlbl['title'] ; }
    if($langlbl['id'] == '1881') { $claimresolved = $langlbl['title'] ; }
    if($langlbl['id'] == '2002') { $claim = $langlbl['title'] ; }  
    if($langlbl['id'] == '2091') { $lbl2091 = $langlbl['title'] ; }  
}

if($postdata != "") { $iconimgs = "../"; } else { $iconimgs = "";  }?>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem classexam_table" id="classexam_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>Code</th>-->
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1051') { echo $langlbl['title'] ; } } ?></th>
                                <th>Exams Periode</th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '130') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '147') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1762') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '539') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1246') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2090') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1763') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1888') { echo $langlbl['title'] ; } } ?> </th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2105') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1866') { echo $langlbl['title'] ; } } ?></th>
                                <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1874') { echo $langlbl['title'] ; } } ?></th>
                            </tr>
                        </thead>
                        <tbody id="examlisting_body">
                            <?php foreach($exams_sts_dtl as $student_exams) 
                            { 
                                foreach($student_exams as $sub_ex ) 
                                {
                                  // print_r($sub_ex);
                                ?>
                                <tr>
                                   <!-- <td><?= $sub_ex['exam_id'] ?></td>-->
                                    <td><?= $sub_ex['title'] ?></td>
                                    <td><?= $sub_ex['exam_type'] ?></td>
                                    <td><?= $sub_ex['exam_period'] ?></td>
                                    <td><?= $sub_ex['student_adm_no'] ?></td>
                                    <td><?= $sub_ex['student_f_name']. " ". $sub_ex['student_l_name'] ?></td>
                                    <td><?= $sub_ex['end_date'] ?></td>
                                    <td>
                                    <?php 
                                    if(!empty($sub_ex['created_date']))
                                    {
                                        echo date("d-m-Y" , $sub_ex['created_date']);
                                    }
                                    else
                                    {
                                        echo '';
                                    } ?>
                                    </td>
                                    <td><?php
                                    if(!empty($sub_ex['marks'] ))
                                    {
                                        echo $sub_ex['marks']."/".$sub_ex['max_marks'];
                                    }
                                    ?></td>
                                    <td><?= $sub_ex['grade'] ?></td>
                                    <td>
                                        <a href="javascript:void(0)" title="View Question Exams" class="btn btn-sm btn-success-outline viewquestionexam" data-examid = "<?= $sub_ex['exam_id'] ?>" data-id="<?= $sub_ex['file_name'] ?>"><img src="<?= $iconimgs ?>icons/viewexam.png" width="35px"></a>
                                    </td>
                                    <td>
                                        <?php 
                                        if($sub_ex['file_type'] == "pdf")
                                        {
                                            if(!empty($sub_ex['upload_exams']))
                                            { ?>
                                                <a href="javascript:void(0)" title="View Submitted Exam" class="btn btn-sm btn-success-outline viewsubmittedexam" data-id="<?= $sub_ex['upload_exams'] ?>" ><img src="<?= $iconimgs ?>icons/submittedanswers.png" width="30px"></a>
                                            <?php 
                                            }
                                        }
                                        else
                                        {
                                            if(!empty($sub_ex['upload_exams']))
                                            { ?>
                                            <a href="<?= $baseurl ?>classExams/pdf/<?= $sub_ex['id'] ?>" title="Submitted Exams" class="btn btn-sm" target="_blank"><img src="<?= $iconimgs ?>icons/submittedanswers.png" width="30px"></a>
                                            <?php
                                            }
                                            else
                                            {
                                                echo "";
                                            }
                                        }?>
                                    </td>
                                    <td>
                                        <?php if(!empty($sub_ex['submit_answersheet']))
                                        { ?>
                                            <a href="javascript:void(0)" title="View Evaluates Answersheet" class="btn btn-sm btn-successs-outline viewevaluatedexam" data-id="<?= $sub_ex['submit_answersheet'] ?>" ><img src="<?= $iconimgs ?>icons/evaluatedanswers.png" width="30px"></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($sub_ex['raise_claim']) && $sub_ex['claim_status'] == 0)
                                        { ?>
                                            <a href="javascript:void(0)" title="View Claim Raised" class="btn btn-sm btn-success viewclaim" data-id="<?= $sub_ex['id'] ?>" data-claim="<?= $sub_ex['raise_claim'] ?>" ><i class="fa fa-eye"></i> <?= $claim ?></a>
                                        <?php } 
                                        elseif(!empty($sub_ex['raise_claim']) && $sub_ex['claim_status'] == 1) { ?>
                                            <a href="javascript:void(0)" title="Resolve Claim" class="btn btn-sm btn-success viewclaim" data-id="<?= $sub_ex['id'] ?>" data-claim="<?= $sub_ex['raise_claim'] ?>" ><?= $claimresolved ?></a>
                                        <?php }?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" title="Upload Exam Reviews" class="btn btn-sm btn-success-outline updateexamreviews" data-claimraise="<?= $sub_ex['raise_claim'] ?>" data-maxmarks ="<?= $sub_ex['max_marks'] ?>" data-claimsts ="<?= $sub_ex['claim_status'] ?>" data-id="<?= $sub_ex['id'] ?>" ><img src="<?= $iconimgs ?>icons/uploadremarks.png" width="30px"></a>
                                        <!--<?php if($sub_ex['teacher_checked_status'] == 0)
                                        { ?>
                                            <a href="javascript:void(0)" title="Update Exam Reviews" class="btn btn-sm btn-success updateexamreviews" data-id="<?= $sub_ex['id'] ?>" ><i class="fa fa-upload"></i> Update Remarks</a>
                                        <?php }
                                        else { 
                                            ?>
                                            <a href="javascript:void(0)" title="Submitted Reviews" class="btn btn-sm btn-success"  >Remarks Submitted</a>
                                            <?php
                                        }?>-->
                                    </td>
                                </tr>
                                <?php 
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
            
<div class="row clearfix">
    <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
</div>
    </div>
</div>
   
<div class="modal classmodal animated zoomIn" id="viewclaim" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1880') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div id="viewclaimrais"></div>
                <input type="hidden" id="subexid" name="subexid">
            </div>
        </div>
    </div>
</div>     

<div class="modal classmodal animated zoomIn" id="viewsubmittedexam" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1888') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div id="viewfile"></div>
            </div>
        </div>
    </div>
</div>     

<div class="modal classmodal animated zoomIn" id="viewevaluatedexam" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1886') { echo $langlbl['title'] ; } } ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div id="viewcheckedfile"></div>
            </div>
        </div>
    </div>
</div>     


<div class="modal classmodal animated zoomIn" id="viewquestionexam" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width:925px">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1887') { echo $langlbl['title'] ; } } ?> </h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <div id="viewquesfile"></div>
            </div>
        </div>
    </div>
</div>         

<div class="modal classmodal animated zoomIn" id="updateexamreviews" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1874') { echo $langlbl['title'] ; } } ?> </h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                
                    <?php	echo $this->Form->create(false , ['url' => ['action' => 'updateexamreviews'] , 'id' => "updateexamreviewsform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?>*</label>
                                <input type="text" name="maxmarks" id="maxmarks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?>"  class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1867') { echo $langlbl['title'] ; } } ?>*</label>
                                <input type="number" name="marks" id="marks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1809') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2090') { echo $langlbl['title'] ; } } ?>*</label>
                                <input type="text" name="grade" id="grade" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1882') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12" id="claim_sts" style="display:none">
                            <div class="form-group">  
                                <input type="checkbox" name="claim_sts" id="claim_sts" > <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1881') { echo $langlbl['title'] ; } } ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1870') { echo $langlbl['title'] ; } } ?> </label>
                                <!--<input type="file" name="answersheet" id="answersheet"class="form-control" >-->
                                <div class="wrapper1">
                                    <div class="input-box row container mb-2">
                                        <input type="file" class="col-sm-9 form-control" name="answersheet[]" id="answersheet" >
                                        <a class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1487') { echo $langlbl['title'] ; } } ?></label>
                                <textarea class="form-control" name="comments" id="comments" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1487') { echo $langlbl['title'] ; } } ?>"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="sub_exm_id" id="sub_exm_id">
                        <input type="hidden" name="classid" id="classid" value="<?= $classid ?>">
                        <input type="hidden" name="subjectid" id="subjectid" value="<?= $subjectid ?>">
                        <div class="col-sm-12">
                            <div class="error" id="examreviewerror"></div>
                            <div class="success" id="examreviewsuccess"></div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary updateexamreviewsbtn" id="updateexamreviewsbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1869') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '108') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>         
            
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    var max_input1 = 5;
    var y = 1;
    $('.add-btn1').click(function (e) {
    e.preventDefault();
        if (y < max_input1) {
            y++;
            $('.wrapper1').append(`
                <div class="input-box row container mb-2">
                        <input type="file" class="col-sm-9 form-control" name="answersheet[]" id="answersheet" >
                        <a href="#" class="col-sm-2 remove-lnk form-control"><i class="fa fa-minus"></i></a>
                </div>
            `); // add input field
        }
    });
    
    // handle click event of the remove link
    $('.wrapper1').on("click", ".remove-lnk", function (e) {
    e.preventDefault();
    
    $(this).parent('div.input-box').remove();  // remove input field
    y--; // decrement the counter
    });
});
</script>
        
<?php foreach($lang_label as $langlbl) { 
if($langlbl['id'] == '1727') { $quiz = $langlbl['title'] ; } 
if($langlbl['id'] == '1995') { $submtquiz = $langlbl['title'] ; } 
if($langlbl['id'] == '1990') { $ntdone = $langlbl['title'] ; } 
if($langlbl['id'] == '1991') { $done = $langlbl['title'] ; } 
if($langlbl['id'] == '1730') { $lbl1730 = $langlbl['title'] ; }
if($langlbl['id'] == '41') { $bcklbl =  $langlbl['title'] ; }
if($langlbl['id'] == '1996')  {  $dwldfileacc = $langlbl['title'] ;  } 
if($langlbl['id'] == '373')  {  $spclinstlabl = $langlbl['title'] ;  } 
if($langlbl['id'] == '1765') { $lbl1765 = $langlbl['title'] ; }
if($langlbl['id'] == '1741') { $lbl1741 =  $langlbl['title'] ; }
if($langlbl['id'] == '1766') { $lbl1766 = $langlbl['title'] ; }
if($langlbl['id'] == '1767') { $lbl1767 = $langlbl['title'] ; }
if($langlbl['id'] == '1768') { $lbl1768 = $langlbl['title'] ; }
if($langlbl['id'] == '1738') { $lbl1738 = $langlbl['title'] ; }
if($langlbl['id'] == '1769') { $lbl1769 = $langlbl['title'] ; }
if($langlbl['id'] == '1770') { $lbl1770 = $langlbl['title'] ; }
if($langlbl['id'] == '1994') { $selecttype =  $langlbl['title'] ; }
if($langlbl['id'] == '1992') { $lbl1992 = $langlbl['title'] ; }
if($langlbl['id'] == '1993') { $lbl1993 = $langlbl['title'] ; }
if($langlbl['id'] == '108') { $labl108 =  $langlbl['title'] ; } 
if($langlbl['id'] == '1964') { $labl1964 =  $langlbl['title'] ; } 
if($langlbl['id'] == '2215') { $missd = $langlbl['title'] ; }
} ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
                <div class="row">
                    <h2 class="col-md-6 heading align-left"><?= $lbl1730 ?>  <?= $subject_details['subject_name']?> > <?= $quiz ?></h2>
                    <input type="hidden" name="subid" id="subid" value="<?= $subjectid ?>">
                     <input type="hidden" name="clsid" id="clsid" value="<?= $classid ?>">
                    <div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" ><?= $bcklbl ?> </a></div>
                </div>
                
                                                
                
            </div>
            
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem assessment_table" id="assessment_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th style="display:none"></th> 
                                <th><?= $lbl1765 ?></th>
                                <th>Type</th>
                                <th>Periode</th>
                                <th><?= $labl1964 ?></th>
                                <th><?= $lbl1766 ?></th>
                                <th><?= $lbl1767 ?></th>
                                <th><?= $lbl1768 ?></th>
                                <th><?= $lbl1738 ?></th>
                                <th><?= $lbl1769 ?></th>
                                <th><?= $lbl1770 ?></th>
                                <?php if($_SESSION['parent_id'] == '') { ?>
                                <th><?= $lbl1741 ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $examids =[];
                            foreach($submitex_details as $subex)
                            {
                                $examids[] = $subex['exam_id'];
                            }
                            
                            foreach($assessment_details as $ass)
                            {
                                if($ass['upload_exams'] != "")
                                {
                                    $sts = $done;
                                }
                                else
                                {
                                    $sts = $ntdone;
                                }
                                $todaydt = time();
                                $startdate = strtotime($ass['start_date']);
                                $enddate = strtotime($ass['end_date']);
                                if(($todaydt >= $startdate) && ($todaydt <= $enddate))
                                { 
                                    ?>
                                    <tr>
                                        <td style="display:none"><?= $ass['id'] ?></td>
                                        <td><?= $ass['title'] ?></td>
                                        <td><?= $ass['exam_type'] ?></td>
                                        <td><?= $ass['exam_period'] ?></td>
                                        <td><?= $ass['max_marks'] ?></td>
                                        <td><button type="button" data-id="<?= $ass['id'] ?>" class="btn btn-sm btn-outline-secondary viewinstruction" data-toggle="modal"  data-target="#viewinstruction" title="View Special Instruction">Instructions</button></td>
                                        <td><?= date("M d, Y h:i A", strtotime($ass['start_date'])) ?></td>
                                        <td><?= date("M d, Y h:i A", strtotime($ass['end_date'])) ?></td>
                                        <td><?= $sts ?></td>
                                        <td>
                                            <?php if($ass['file_name'] != "")
                                            { ?>
                                                <a href="webroot/img/<?= $ass['file_name'] ?>" title="Download/View <?= $quiz ?>" class="btn btn-sm btn-success" download onclick="assignmnt_popup()"><i class="fa fa-download"></i> <?= $quiz ?></a>
                                            <?php
                                            } 
                                            else { ?>
                                                <a href="<?=$baseurl?>examAssessment/pdf/<?= $ass['id'] ?>" title="Download/View <?= $quiz ?>" class="btn btn-sm btn-success" download onclick="assignmnt_popup()"><i class="fa fa-download"></i> <?= $quiz ?></a>
                                            <?php } ?>
                                            
                                        </td>
                                        <td>
                                            <?php 
                                            if($ass['file_type'] == "pdf")
                                            {
                                                if($ass['upload_exams'] != "")
                                                { ?>
                                                <!--<a href="webroot/uploadExams/<?= $ass['upload_exams'] ?>" title="<?= $submtquiz ?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-eye"></i> <?= $submtquiz ?></a> -->
                                                <a href="javascript:void(0)" title="<?= $submtquiz ?>" class="btn btn-sm btn-success viewsubmitdass" data-id="<?= $ass['upload_exams'] ?>" ><i class="fa fa-eye"></i> <?= $submtquiz ?></a>
                                                <?php
                                                } 
                                            }
                                            elseif($ass['file_type'] == "images")
                                            {
                                                ?>
                                                <a href="<?= $baseurl ?>assessments/pdf/<?= $ass['submit_id'] ?>" title="<?= $submtquiz ?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-eye"></i> <?= $submtquiz ?></a>
                                                
                                                <?php
                                            }
                                            else
                                            {
                                            }?>
                                            
                                        </td>
                                        <?php if($_SESSION['parent_id'] == '') { ?>
                                        <td>
                                    		<a href="javascript:void(0)" title="Upload" class="btn btn-sm btn-success assUpload" data-id="<?= $ass['id'] ?>" data-stuid ="<?= $studId ?>"><i class="fa fa-upload"></i></a>
                                    	</td>
                                    	<?php } ?>
                                    </tr>
                                    <?php
                                } elseif($todaydt > $enddate) { ?>
                                    <tr>
                                        <td style="display:none"><?= $ass['id'] ?></td>
                                        <td disabled><?= $ass['title'] ?></td>
                                        <td><?= $ass['exam_type'] ?></td>
                                        <td><?= $ass['exam_period'] ?></td>
                                        <td><?= $ass['max_marks'] ?></td>
                                        <td><button type="button" data-id="<?= $ass['id'] ?>" class="btn btn-sm btn-outline-secondary viewinstruction" data-toggle="modal"  data-target="#viewinstruction" title="View Special Instruction">Instructions</button></td>
                                        <td><?= date("M d, Y h:i A", strtotime($ass['start_date'])) ?></td>
                                        <td><?= date("M d, Y h:i A", strtotime($ass['end_date'])) ?></td>
                                        <td><?= $sts ?></td>
                                        <td>
                                            <?php if($ass['file_name'] != "")
                                            { ?>
                                                <a href="webroot/img/<?= $ass['file_name'] ?>" title="Download/View <?= $quiz ?>" class="btn btn-sm btn-success" download onclick="assignmnt_popup()"><i class="fa fa-download"></i> <?= $quiz ?></a>
                                            <?php
                                            } 
                                            else { ?>
                                                <a href="<?=$baseurl?>examAssessment/pdf/<?= $ass['id'] ?>" title="Download/View <?= $quiz ?>" class="btn btn-sm btn-success" download onclick="assignmnt_popup()"><i class="fa fa-download"></i> <?= $quiz ?></a>
                                            <?php } ?>
                                            
                                        </td>
                                        <td>
                                            <?php 
                                            if($ass['file_type'] == "pdf")
                                            {
                                                if($ass['upload_exams'] != "")
                                                { ?>
                                                <!--<a href="webroot/uploadExams/<?= $ass['upload_exams'] ?>" title="<?= $submtquiz ?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-eye"></i> <?= $submtquiz ?></a> -->
                                                <a href="javascript:void(0)" title="<?= $submtquiz ?>" class="btn btn-sm btn-success viewsubmitdass" data-id="<?= $ass['upload_exams'] ?>" ><i class="fa fa-eye"></i> <?= $submtquiz ?></a>
                                                <?php
                                                } 
                                            }
                                            elseif($ass['file_type'] == "images")
                                            {
                                                ?>
                                                <a href="<?= $baseurl ?>assessments/pdf/<?= $ass['submit_id'] ?>" title="<?= $submtquiz ?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-eye"></i> <?= $submtquiz ?></a>
                                                
                                                <?php
                                            }
                                            else
                                            {
                                            }?>
                                            
                                        </td>
                                        <?php if($_SESSION['parent_id'] == '') { ?>
                                        <td>
                                    	    <?php if($ass['upload_exams'] == "")
                                            { 
                                                echo $missd;
                                            } ?>
                                    	</td>
                                    	<?php } ?>
                                    </tr>
                                <?php }
                            }
                            ?>
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

<div class="modal classmodal animated zoomIn" id="viewevaluatedass" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $submtquiz ?></h6>
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

<!------------------ Pop up for View Instructions --------------------->

<div class="modal fade " id="viewinstruction" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $spclinstlabl ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <div id="instructions"></div>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</div> 


<!------------------ Pop up for Upload Assessment --------------------->

<div class="modal classmodal animated zoomIn" id="AssessmentUpload" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $lbl1992 ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
               <?php   echo $this->Form->create(false , ['url' => ['action' => 'quizupload'] , 'id' => "quizuploadform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]);   ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <label><?= $lbl1993 ?></label>
                        <div class="form-group">                                    
                            <select name="type" class="form-control js-states" id="upload_type" required onchange="image_pdf(this.value,)">
                                <option value="">-- <?= $selecttype ?> --</option>
                                <option value="pdf">Pdf</option>
                                <option value="images">Images</option>
                            </select>
                        </div>
                        <div class="form-group pdf_up" style="display:none">                                    
                            <div class="wrapper1">
                                <div class="input-box row container mb-2">
                                    <input type="file" name="assessment_upload[]" class="col-sm-9 form-control" id="pdf_up" accept=".pdf">
                                    <a class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group image_up" style="display:none">
                            <div class="wrapper2">
                                <div class="input-box row container mb-2">
                                    <input type="file" name="assessment_upload[]" class="col-sm-9 form-control"  id="image_up" accept=".jpg, .jpeg, .png">
                                    <a class="col-sm-2 btn add-btn2"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="student_id" id="student_id">
                    <input type="hidden" name="exam_id" id="exam_id">
                    <div class="col-md-12">
                        <div class="error" id="assuploaderror"></div>
                        <div class="success" id="assuploadsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                         
    
                        <button type="submit" class="btn btn-primary uploadassbtn" id="uploadassbtn"><?= $lbl1992 ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $labl108 ?></button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
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
                    <input type="file" name="assessment_upload[]" class="col-sm-9 form-control" id="pdf_up" accept=".pdf">
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
    
    var x = 1;
    $('.add-btn2').click(function (e) {
        e.preventDefault();
        if (x < max_input1) {
            x++;
            $('.wrapper2').append(`
                <div class="input-box row container mb-2">
                    <input type="file" name="assessment_upload[]" multiple class="col-sm-9 form-control"  id="image_up" accept=".jpg, .jpeg, .png">
                    <a href="#" class="col-sm-2 remove-lnk1 form-control"><i class="fa fa-minus"></i></a>
                </div>
            `); // add input field
        }
    });
    
    // handle click event of the remove link
    $('.wrapper2').on("click", ".remove-lnk1", function (e) {
        e.preventDefault();
        $(this).parent('div.input-box').remove();  // remove input field
        x--; // decrement the counter
    });
});

function assignmnt_popup()
{
    setTimeout(function(){ swal("<?php echo $dwldfileacc ?>"); }, 4000);
}

function image_pdf(get_val){
    if(get_val == 'pdf'){
        $(".image_up").hide();
        $('.pdf_up').show();
        $("#pdf_up").attr('required', ''); 
        $("#image_up").removeAttr('required');
        $('#image_up').val('');
        
    }else if(get_val == 'images'){
        $(".pdf_up").hide();
        $('.image_up').show();
        $("#image_up").attr('required', ''); 
        $("#pdf_up").removeAttr('required');
        $('#pdf_up').val('');
    }
}
</script>
<style>
    .example-class
    {
        color:#ff0000 !important;
    }
</style>

<?php
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '373')  {  $spclinstlabl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1996')  {  $dwldfileacc = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1763')  {  $viewexmlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1805')  {  $viewexmfrmtlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '243')  {  $submtlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '2000')  {  $entrpasslbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1807')  {  $downpdflbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1806')  {  $editexmlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '108')  {  $closelbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '2001')  {  $upexmlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '41')  {  $bcklbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1987') { $submt = $langlbl['title'] ; } 
    if($langlbl['id'] == '1988') { $ntsubmt = $langlbl['title'] ; } 
    if($langlbl['id'] == '1994')  {  $seltyplbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1730') { $lbl1730 = $langlbl['title'] ; }
    if($langlbl['id'] == '1724') { $lbl1724 = $langlbl['title'] ; } 
    if($langlbl['id'] == '388') { $labl388 = $langlbl['title'] ; }
    if($langlbl['id'] == '1759') { $labl1759 = $langlbl['title'] ; }
    if($langlbl['id'] == '1760') { $labl1760 = $langlbl['title'] ; }
    if($langlbl['id'] == '1761') { $labl1761 = $langlbl['title'] ; }
    if($langlbl['id'] == '1762') { $labl1762 = $langlbl['title'] ; }
    if($langlbl['id'] == '1763') { $labl1763 = $langlbl['title'] ; }
    if($langlbl['id'] == '1738') { $labl1738 = $langlbl['title'] ; }
    if($langlbl['id'] == '1764') { $labl1764 = $langlbl['title'] ; }
    
    if($langlbl['id'] == '1964') { $labl1964 = $langlbl['title'] ; }
    if($langlbl['id'] == '2215') { $missd = $langlbl['title'] ; }
    
     
}
?>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
            <div class="row">
                <h2 class="col-md-6  heading"><?= $lbl1730 ?> <?= $subject_details[0]['subject_name'] ?>  > <?= $lbl1724 ?> </h2>
                <!--<h2 class="text-right"><a href="<?= $baseurl ?>studentsubjects/" title="Back" class="btn btn-sm btn-success">Back</a></h2>	-->
                <input type="hidden" name="subid" id="subid" value="<?= $subjectid ?>">
                <input type="hidden" name="clsid" id="clsid" value="<?= $classid ?>">
                <div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" ><?= $bcklbl ?></a></div>  
            </div>
            </div>
            <input type="hidden" name="stdid" id="stdid" value="<?= $studentid ?> "> 
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem student_table" id="examlisting_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <th style="display:none"></th>
                                <th><?= $labl388 ?></th>
                                <th><?= $labl1759 ?></th>
                                <th>Periode</th>
                                <th><?= $labl1964 ?></th>
                                <th><?= $labl1760 ?></th>
                                <th><?= $labl1761 ?></th>
                                <th><?= $labl1762 ?></th>
                                <th><?= $labl1763 ?></th>
                                <th><?= $labl1738 ?></th>
                                <?php if($_SESSION['parent_id'] == '') { ?>
                                <th><?= $labl1764 ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="examlisting_body">
                            <?php 
                            foreach($examass_detail as $examass)
                            {  //print_r($examass);
                                $todaydt = time();
                                $startdate = strtotime($examass['start_date']);
                                $enddate = strtotime($examass['end_date']);
                                if(($todaydt >= $startdate) && ($todaydt <= $enddate))
                                { ?>
                                    <tr>
                                        <td style="display:none"><?= $examass['id'] ?></td>
                                        <td><?= ucfirst($examass['title']) ?></td>
                                        <td><?= $examass['exam_type'] ?></td>
                                        <td><?= $examass['exam_period'] ?></td>
                                        <td><?= $examass['max_marks'] ?></td>
                                        <td><?= $examass['class_name'] ?></td>
                                        <td><?= date("M d, Y h:i A" , strtotime($examass['start_date'])) ?></td>
                                        <td><?= date("M d, Y h:i A" , strtotime($examass['end_date'])) ?></td>
                                        <td>
                                            <?php 
                                            $kpclass = array('maternelle','creche','primaire');
                                            $sc_sec = strtolower($examass['school_sections']); 
                                            if(!in_array($sc_sec, $kpclass)){ ?>
                                                <button type="button" data-passcode="<?= $examass['passcode'] ?>" data-examfile="<?= $examass['file_name'] ?>" data-examformt="<?= $examass['examformt'] ?>" data-submitexamid = "<?= $examass['submitexam_id'] ?>" data-examid="<?= $examass['id'] ?>" class="btn btn-sm btn-outline-secondary viewpasscode" data-toggle="modal"  data-target="#viewpasscode" title="<?= $viewexmlbl ?>"><?= $viewexmlbl ?></button>
                                            <?php }else{ 
                                                if($examass['examformt'] == "pdf")
                                                { ?>
                                                <button type="button" data-examfile="<?= $examass['file_name'] ?>" data-examformt="<?= $examass['examformt'] ?>" data-submitexamid = "<?= $examass['submitexam_id'] ?>" data-examid="<?= $examass['id'] ?>" class="btn btn-sm btn-outline-secondary viewexampdf" data-toggle="modal" title="<?= $viewexmlbl ?>"><?= $viewexmlbl ?></button>
                                                <?php }
                                                else
                                                { ?>
                                                <a href="<?= $baseurl?>examListing/viewexam/<?= $examass['submitexam_id'] ?>" class="btn btn-sm btn-outline-secondary" title="<?= $viewexmlbl ?>"><?= $viewexmlbl ?></a>
                                            <?php } } ?>
                                        </td>
                                        <?php if($examass['exam_sts'] == 0) { ?>
                                            <td><?= $ntsubmt ?></td>
                                        <?php } else { ?>
                                            <td><?= $submt ?></td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                            if($examass['exam_sts'] == 0)
                                            { ?>
                                                <a href="javascript:void(0)" title="Upload" class="btn btn-sm btn-success examUpload" data-id="<?= $examass['submitexam_id'] ?>"><i class="fa fa-upload"></i></a>
                                            <?php }
                                            else { 
                                                if($examass['file_type'] == "pdf")
                                                { 
                                                    if($examass['uploadfile'] != "")
                                                    { ?>
                                                    <a href="javascript:void(0)" title="Submitted Exams" class="viewsubmitdass" data-id="<?= $examass['uploadfile'] ?>" ><img src="icons/submittedanswers.png" width="35px"></a>
                                                    <?php
                                                    } 
                                                }
                                                else
                                                { ?>
                                                    <a href="<?= $baseurl ?>examListing/pdf/<?= $examass['submitexam_id'] ?>" title="Submitted Exams" class="btn btn-sm" target="_blank"><img src="icons/submittedanswers.png" width="35px"></a>
                                                <?php }
                                            } ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                elseif($todaydt > $enddate)
                                { ?>
                                    <tr>
                                        <td style="display:none"><?= $examass['id'] ?></td>
                                        <td><?= ucfirst($examass['title']) ?></td>
                                        <td><?= $examass['exam_type'] ?></td>
                                        <td><?= $examass['exam_period'] ?></td>
                                        <td><?= $examass['max_marks'] ?></td>
                                        <td><?= $examass['class_name'] ?></td>
                                        <td><?= date("M d, Y h:i A" , strtotime($examass['start_date'])) ?></td>
                                        <td><?= date("M d, Y h:i A" , strtotime($examass['end_date'])) ?></td>
                                        <td>
                                            <button type="button" data-passcode="<?= $examass['passcode'] ?>" data-examfile="<?= $examass['file_name'] ?>" data-examformt="<?= $examass['examformt'] ?>" data-submitexamid = "<?= $examass['submitexam_id'] ?>" data-examid="<?= $examass['id'] ?>" class="btn btn-sm btn-outline-secondary viewpasscode" data-toggle="modal"  data-target="#viewpasscode" title="<?= $viewexmlbl ?>"><?= $viewexmlbl ?></button>
                                        </td>
                                        <?php if($examass['exam_sts'] == 0) { ?>
                                            <td><?= $ntsubmt ?></td>
                                        <?php } else { ?>
                                            <td><?= $submt ?></td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                            if($examass['exam_sts'] == 0)
                                            { 
                                                echo $missd;
                                            }
                                            else { 
                                                if($examass['file_type'] == "pdf")
                                                {
                                                    if($examass['uploadfile'] != "")
                                                    { ?>
                                                    <a href="javascript:void(0)" title="Submitted Exams" class="viewsubmitdass" data-id="<?= $examass['uploadfile'] ?>" ><img src="icons/submittedanswers.png" width="35px"></a>
                                                    <?php
                                                    } 
                                                }
                                                else
                                                { ?>
                                                    <a href="<?= $baseurl ?>examListing/pdf/<?= $examass['submitexam_id'] ?>" title="Submitted Exams" class="btn btn-sm" target="_blank"><img src="icons/submittedanswers.png" width="35px"></a>
                                                <?php }
                                            } ?>
                                        </td>
                                    </tr>
                                <?php
                                }
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
<!------------------ Pop up for status approval --------------------->

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

<div class="modal fade " id="viewpasscode" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $viewexmlbl ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                <?php echo $this->Form->create(false , ['id' => "passcodesubmit", 'enctype' => "multipart/form-data"  ,  'method' => "post"]);   ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">   
                            <label><?= $entrpasslbl ?></label>
                            <div class="form-group">      
                                <div class="row container">
                                    <input type="text" name="passcode" id="passcode" maxlength="6" minlength="6" class="form-control col-md-10" >
                                    <input type="hidden" name="gen_passcode" id="gen_passcode" >
                                    <input type="hidden" name="exam_file" id="exam_file" >
                                    <input type="hidden" name="id_exam" id="id_exam" >
                                    <input type="hidden" name="id_submitexam" id="id_submitexam" >
                                    <input type="hidden" name="formatexam" id="formatexam" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="examformat" style="display:none">
                        <div class="form-group">   
                            <label><?= $viewexmfrmtlbl ?></label>
                            <div class="form-group">      
                                <div class="row container">
                                    <span class="mr-2"><input type="radio" name="exam_format" value="custom" class="mr-1"><?= $editexmlbl ?></span>
                                    <span class="mr-2"><input type="radio" name="exam_format" value="pdf" class="mr-1"> <?= $downpdflbl ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="error" id="passcodeerror"></div>
                        <div class="success" id="passcodesuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" name="submit_passcode" id="submit_passcode" class="btn btn-primary mr-2"><?= $submtlbl ?> </button>
                    </div>
                </div>
            </div>
             <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>    


<div class="modal fade bd-example-modal-lg" id="viewexam" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-header header">
                <!--<h6 class="title" id="defaultModalLabel">Passcode</h6>-->
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div id="viewexams"></div>
	        </div>
        </div>
    </div>
</div>    

<div class="modal classmodal animated zoomIn" id="uploadExams" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $upexmlbl ?></h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
               <?php   echo $this->Form->create(false , ['url' => ['action' => 'examupload'] , 'id' => "examuploadform" , 'enctype' => "multipart/form-data"  , 'method' => "post"  ]);   ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <label><?= $upexmlbl ?></label>
                        <div class="form-group">                                    
                            <select name="type" class="form-control js-states" id="upload_type" required onchange="image_pdf(this.value)">
                                <option value="">-- <?= $seltyplbl ?> --</option>
                                <option value="pdf">Pdf</option>
                                <option value="images">Images</option>
                            </select>
                        </div>
                        <div class="form-group pdf_up" style="display:none">    
                            <!--<input type="file" name="exam_upload" id="pdf_up" class="form-control"  accept=".pdf">-->
                            <div class="wrapper1">
                                <div class="input-box row container mb-2">
                                    <input type="file" name="exam_upload[]" class="col-sm-9 form-control" id="pdf_up" accept=".pdf">
                                    <a class="col-sm-2 btn add-btn1"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group image_up" style="display:none">                                    
                            <!--<input type="file" name="exam_upload[]" multiple class="form-control"  id="image_up" accept=".jpg, .jpeg, .png">-->
                            <div class="wrapper2">
                                <div class="input-box row container mb-2">
                                    <input type="file" name="exam_upload[]" class="col-sm-9 form-control"  id="image_up" accept=".jpg, .jpeg, .png">
                                    <a class="col-sm-2 btn add-btn2"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="student_id" id="student_id">
                    <input type="hidden" name="exam_id" id="exam_id">
                    <div class="col-md-12">
                        <div class="error" id="examuploaderror"></div>
                        <div class="success" id="examuploadsuccess"></div>
                    </div>
                    <div class="button_row" >
                        <hr>
                        <button type="submit" class="btn btn-primary uploadexambtn" id="uploadexambtn"><?= $upexmlbl ?></button>
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?= $closelbl ?></button>
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
                    <input type="file" name="exam_upload[]" class="col-sm-9 form-control" id="pdf_up" accept=".pdf">
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
                    <input type="file" name="exam_upload[]" multiple class="col-sm-9 form-control"  id="image_up" accept=".jpg, .jpeg, .png">
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

function popup()
{
    setTimeout(function(){ swal("<?php echo $dwldfileacc ?>"); }, 4000);
    //alert(".");
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

$('#examlisting_table tbody').on("click",".viewexampdf",function(){
    $("#viewexam").modal("show");
    var examfile = $(this).data('examfile');
    $("#viewexams").html("<iframe src='"+origin+"/school/webroot/img/"+examfile+"' width='780' height='550'></iframe>");
});
</script>

            
    
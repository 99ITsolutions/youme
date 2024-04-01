<?php 
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1773')  {  $raiseclaimlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2002')  {  $claimlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1391')  {  $sndlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1887')  {  $quesexmlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1888')  {  $subexmlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1886')  {  $evanshetlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '2003')  {  $claimsolvlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1866')  {  $claimraisedlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '2004')  {  $entrclaimlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '1730') { $lbl1730 = $langlbl['title'] ; }
    if($langlbl['id'] == '1771') { $lbl1771 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $backlbl = $langlbl['title'] ; }
    if($langlbl['id'] == '1759') { $lbl1759 = $langlbl['title'] ; }
    if($langlbl['id'] == '1776') { $lbl1776 = $langlbl['title'] ; }
    if($langlbl['id'] == '1246') { $lbl1246 = $langlbl['title'] ; }
    if($langlbl['id'] == '1229') { $lbl1229 = $langlbl['title'] ; }
    if($langlbl['id'] == '1775') { $lbl1775 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1772') { $lbl1772 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1773') { $lbl1773 = $langlbl['title'] ; } 
    
    if($langlbl['id'] == '1964') { $lbl1964 = $langlbl['title'] ; } 
}
?>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header"> <?php //print_r($subject_details);?>
            <div class="row">
                <h2 class="col-md-6 heading"><?= $lbl1730 ?> <?= $subject_details[0]['subject_name'] ?> > <?= $lbl1771 ?></h2>
                <!--<h2 class="text-right"><a href="<?= $baseurl ?>studentdashboard/studentprofile/" title="Back" class="btn btn-sm btn-success">Back</a></h2>	-->
                <input type="hidden" name="subid" id="subid" value="<?= $subjectid ?>">
                <input type="hidden" name="clsid" id="clsid" value="<?= $classid ?>">
                <div class="col-md-6  align-right"><a href="javascript:void(0)" title="Back"  id="assbackbutton" class=" btn btn-primary" ><?= $backlbl ?> </a></div>  
            </div>
            
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem subjectgrade_table" id="subjectgrade_table" data-page-length='50'>
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>Code</th>-->
                                <!--<th>
                                    <label class="fancy-checkbox">
                                        <input class="select-all" type="checkbox" name="checkbox">
                                        <span></span>
                                    </label>
                                </th>-->
                                <th>Type</th>
                                <th>Title</th>
                                <th>Request Type</th>
                                <th>Request Period</th>
                                <th><?= $lbl1776 ?></th>
                                <th><?= $lbl1246 ?></th>
                                <th><?= $lbl1229 ?></th>
                                <th><?= $lbl1775 ?> </th>
                                <th><?= $lbl1772 ?>  </th>
                                <th><?= $lbl1773 ?> </th>
                            </tr>
                        </thead>
                        <tbody id="allgrade_body">
                            
                            <?php foreach($exams_sts_dtl as $student_exams) 
                            { 
                                foreach($student_exams as $sub_ex ) 
                                {
                                    //print_r($sub_ex);
                                  if(!empty($sub_ex['upload_exams'])) {
                                ?>
                                <tr>
                                    <!--<td><?= $sub_ex['exam_id'] ?></td>-->
                                    <!--<td class="width45">
                                        <label class="fancy-checkbox">
                                            <input class="checkbox-tick" type="checkbox" name="checkbox" value="<?= $sub_ex['id'] ?>">
                                            <span></span>
                                        </label>
                                    </td>-->
                                    <td><?php if( $sub_ex['type'] == "Assessment") { echo "Assignment"; } else { echo $sub_ex['type']; } ?></td>
                                    <td><?= $sub_ex['title'] ?></td>
                                    <td><?= $sub_ex['exam_type'] ?></td>
                                    <td><?= $sub_ex['exam_period'] ?></td>
                                    <td>
                                    <?php 
                                    if(!empty($sub_ex['created_date']))
                                    {
                                        echo date("d-m-Y h:i A" , $sub_ex['created_date']);
                                    }
                                    else
                                    {
                                        echo '';
                                    } ?>
                                    </td>
                                    <td><?php if(!empty($sub_ex['marks'])) { echo $sub_ex['marks']."/".$sub_ex['max_marks'] ; }?></td>
                                    <td><?= $sub_ex['grade'] ?></td>
                                    <!--<td>
                                        <a href="javascript:void(0)" title="View Question Exams" class="btn btn-sm btn-success-outline viewquestionexam"  data-examid="<?= $sub_ex['exam_id'] ?>" data-id="<?= $sub_ex['file_name'] ?>"><img src="icons/viewexam.png" width="35px"></a>
                                    </td>-->
                                    <td>
                                        <?php 
                                        if($sub_ex['file_type'] == "pdf")
                                        {
                                            if(!empty($sub_ex['upload_exams']))
                                            { ?>
                                                <a href="javascript:void(0)" title="View Submitted Exam" class="btn btn-sm btn-success-outline viewsubmittedexam" data-id="<?= $sub_ex['upload_exams'] ?>" ><img src="icons/submittedanswers.png" width="30px"></a>
                                            <?php 
                                            }
                                        }
                                        else
                                        {
                                            if(!empty($sub_ex['upload_exams']))
                                            { ?>
                                            <a href="<?= $baseurl ?>subjectGrade/pdf/<?= $sub_ex['id'] ?>" title="Submitted Exams" class="btn btn-sm" target="_blank"><img src="icons/submittedanswers.png" width="30px"></a>
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
                                            <a href="javascript:void(0)" title="View Evaluated Exam" class="btn btn-sm btn-success-outline viewevaluatedexam" data-id="<?= $sub_ex['submit_answersheet'] ?>" ><img src="icons/evaluatedanswers.png" width="30px"></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($sub_ex['raise_claim']) && $sub_ex['claim_status'] == 0)
                                        { ?>
                                            <a href="javascript:void(0)" title="<?= $claimraisedlbl ?>" class="btn btn-sm btn-success raiseclaimed" data-id="<?= $sub_ex['id'] ?>" ><?= $claimraisedlbl ?></a>
                                        <?php } 
                                        elseif(!empty($sub_ex['raise_claim']) && $sub_ex['claim_status'] == 1)
                                        { ?>
                                            <a href="javascript:void(0)" title="<?= $claimsolvlbl ?>" class="btn btn-sm btn-success raiseclaim" data-id="<?= $sub_ex['id'] ?>" ><?= $claimsolvlbl ?></a>
                                        <?php } 
                                        else { ?>
                                            <a href="javascript:void(0)" title="<?= $raiseclaimlbl ?>" class="btn btn-sm btn-success raiseclaim" data-id="<?= $sub_ex['id'] ?>" ><?= $raiseclaimlbl ?></a>
                                        <?php }  ?>
                                    </td>
                                </tr>
                                <?php 
                                } }
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
   


<div class="modal classmodal animated zoomIn" id="viewsubmittedexam" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $subexmlbl ?></h6>
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
                <h6 class="title" id="defaultModalLabel"><?= $evanshetlbl ?></h6>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $quesexmlbl ?></h6>
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


<div class="modal classmodal animated zoomIn" id="raiseClaim" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?= $raiseclaimlbl ?> </h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'updateclaim'] , 'id' => "updateclaimform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">  
                                <label><?= $claimlbl ?></label>
                                <textarea name="claim" id="claim" placeholder="<?= $entrclaimlbl ?>"  class="form-control" required></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="sub_exm_id" id="sub_exm_id">
                        <input type="hidden" name="classid" id="classid">
                        <input type="hidden" name="subjectid" id="subjectid">
                        <div class="col-sm-12">
                            <div class="error" id="claimerror"></div>
                            <div class="success" id="claimsuccess"></div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary updateclaimbtn" id="updateclaimbtn"><?= $sndlbl ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>         
            
    
<style>
    .badge { 
        position: relative;
        top: -12px !important;
        left: -3px !important;
        border: 1px solid;
        border-radius: 50%;
        background: #6c757d;
        color: #fff;
    }
    .bg-dash
    {
        max-height:65px !important;
    }
</style>`
<?php // print_r($school_details); ?>

   
           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <h2 class="col-md-4 heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1432') { echo $langlbl['title'] ; } } ?></h2>
                                <h2 class="col-md-4 heading"><span><img src="<?= $baseurl ?>webroot/img/<?= $schoolDetails[0]['comp_logo']?>" width="50px"/></span><span><?= $schoolDetails[0]['comp_name']?><span></h2>
                                <!--<div class="col-md-6 align-right"><a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#submitrequest">Add Exams/Assessment</a></div>-->
                                <h2 class="col-md-4  align-right">
                                    <a class="btn btn-success" href="<?= $baseurl ?>schools"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '881') { echo $langlbl['title'] ; } } ?></a>
                                </h2>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem" id="schoolexamstable" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <!--<th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>-->
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '897') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '690') { echo $langlbl['title'] ; } } ?></th>
                                            <!--<th>Request For</th>-->
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '364') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '726') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '486') { echo $langlbl['title'] ; } } ?></th><!--
                                            <th>Title</th>
                                            <th>Instructions</th>-->
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '366') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1433') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '665') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1058') { echo $langlbl['title'] ; } } ?></th>
                                            
                                            <!--<th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($approvedetails as $value)
                                    { 
                                        if($value['addquestion'] > 0 )
                                        {
                                            /*if( $value['status'] == 0)
                                            {
                                                $sts = '<a href="javascript:void()" data-url="examAssessment/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assessment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                            }
                                            else 
                                            { 
                                                $sts = '<a href="javascript:void()" data-url="examAssessment/approvestatus/'.$value['id'].'" data-id = '.$value['id'].' data-status='.$value['status'].' data-str="Exam/Assessment Status" class="btn btn-sm  js-sweetalert" title="Status" data-type="approve_status"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                            }*/
                                           ?>
                                            <?php
                                            if( $value['status'] == 0)
                                            {
                                                $sts = "Inactive";
                                                $stss = '<a href="javascript:void()"  class="btn btn-sm" title="Status" ><label class="switch"><input type="checkbox" disabled><span class="slider round"></span></label></a>';
                                            }
                                            else 
                                            { 
                                                $sts = "Active";
                                                $stss = '<a href="javascript:void()"  class="btn btn-sm" title="Status" ><label class="switch"><input type="checkbox" checked disabled><span class="slider round"></span></label></a>';
                                            }
                                                        
                                            ?>
                                            <tr> 
                                                <!--<td class="width45">
                                                <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="checkbox" value="<?= $value['id'] ?>"> 
                                                        <span></span>
                                                    </label>
                                                </td>-->
                                                <td>
                                                    <span><?=date('m-d-Y', $value['created_date'])?></span>
                                                </td>
                                                <td>
                                                    <span ><?= $value['teacher_name'] ?></span>
                                                </td>
                                                <!--<td>
                                                    <span ><?= $value['type'] ?></span>
                                                </td>-->
                                                <td>
                                                    <span><?= $value['exam_type'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['class_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['subject_name'] ?></span>
                                                </td>
                                                <!--<td>
                                                    <span><?= ucfirst($value['title']) ?> </span>
                                                </td>
                                                <td>
                                                    <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary viewinstruction" data-toggle="modal"  data-target="#viewinstruction" title="View Special Instruction">View Instructions</button>
                                                </td>-->
                                                <td>
                                                    <?php if(!empty($value['file_name']))
                                                    { ?>
                                                        <a href="../../webroot/img/<?= $value['file_name'] ?>" target="_blank" ><span><?= ucfirst($value['file_name']) ?></span></a>
                                                    <?php } else { ?>
                                                        <a href="<?=$baseurl?>schools/pdf/<?= $value['id'] ?>" target="_blank"><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1434') { echo $langlbl['title'] ; } } ?></span></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php $kpclass = array('maternelle','creche','primaire'); $sc_sec = strtolower($value['school_sections']); if(!in_array($sc_sec, $kpclass)){ ?>
                                                    <a href="<?=  $baseurl ?>schools/export/<?= $value['id'] ?>"  class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1435') { echo $langlbl['title'] ; } } ?> </a>
                                                    <?php } ?>
                                                </td>
                                                 <td>
                                                    <span><?= $sts ?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('m-d-Y H:i', strtotime($value['start_date']))?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('m-d-Y H:i', strtotime($value['end_date']))?></span>
                                                </td>
                                                
                                                <!--<td>
                                                    <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editexamass" data-toggle="modal"  data-target="#editexamass" title="Edit"><i class="fa fa-edit"></i></button>
                                                    
                                                    <button type="button" data-id="<?=$value['id']?>" data-url="examAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                </td>-->
                                            </tr>
                                            <?php
                                            $n++;
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
                
            </div>

        
    <div>
            <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
    </div>               


<!------------------ Pop up for status approval --------------------->

<div class="modal fade " id="viewinstruction" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">Special Instructions</h6>
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

<script>
    function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>    



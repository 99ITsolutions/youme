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

    @media screen and (max-width: 450px) and (min-width: 200px)
    {
        .sclexamassmodule>.col-md-6
        {
           text-align:left !important;
        }
        .sclstuattmodule>.col-md-8>.btn
        {
           display:block !important;
        }
    }
    
</style>
<?php foreach($lang_label as $langlbl) { 
    if($langlbl['id'] == '635') { $scllbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1796') { $lbl1796 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1797') { $lbl1797 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1798') { $quizlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1799') { $lbl1799 = $langlbl['title'] ; } 
    if($langlbl['id'] == '1742') { $asslbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1724') { $exmlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1799') { $studgulbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1046') { $lbl1046 = $langlbl['title'] ; }
    if($langlbl['id'] == '1018') { $yerlylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1019') { $hlfyrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1020') { $quatrlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1021') { $mnthlbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '1800') { $weklylbl = $langlbl['title'] ; } 
    if($langlbl['id'] == '2076') { $lbl2076 = $langlbl['title'] ; }
    if($langlbl['id'] == '76') { $lbl76 = $langlbl['title'] ; }
    if($langlbl['id'] == '41') { $lbl41 = $langlbl['title'] ; }
    if($langlbl['id'] == '361') { $lbl361 = $langlbl['title'] ; }
    if($langlbl['id'] == '362') { $lbl362 = $langlbl['title'] ; }
    if($langlbl['id'] == '363') { $lbl363 = $langlbl['title'] ; }
    if($langlbl['id'] == '364') { $lbl364 = $langlbl['title'] ; }
    if($langlbl['id'] == '355') { $lbl355 = $langlbl['title'] ; }
    if($langlbl['id'] == '365') { $lbl365 = $langlbl['title'] ; }
    if($langlbl['id'] == '2082') { $lbl2082 = $langlbl['title'] ; }
    if($langlbl['id'] == '388') { $lbl388 = $langlbl['title'] ; }
    if($langlbl['id'] == '366') { $lbl366 = $langlbl['title'] ; }
    if($langlbl['id'] == '85') { $lbl85 = $langlbl['title'] ; }
    if($langlbl['id'] == '367') { $lbl367 = $langlbl['title'] ; }
    if($langlbl['id'] == '368') { $lbl368 = $langlbl['title'] ; }
    if($langlbl['id'] == '369') { $lbl369 = $langlbl['title'] ; }
    if($langlbl['id'] == '333') { $lbl333 = $langlbl['title'] ; }
    if($langlbl['id'] == '1964') { $lbl1964 = $langlbl['title'] ; }
    if($langlbl['id'] == '2086') { $lbl2086 = $langlbl['title'] ; }
    if($langlbl['id'] == '2087') { $lbl2087 = $langlbl['title'] ; }
} ?>

           <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row sclexamassmodule">
                                <?php if(!empty($sclsub_details[0])) { ?>
                                <h2 class="col-md-6 heading"><?= $lbl1046 ?></h2>
                                <?php } else { ?>
                                <h2 class="col-md-6 heading"><?= $lbl2076 ?></h2>
                                <?php } ?>
                                <div class="col-md-6 text-right">
                                    <?php if(!empty($sclsub_details[0]))
                                    { 
                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                        if(in_array("41", $roles)) { ?>
                                            <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#submitrequest"><?= $lbl76 ?></a>
                                        <?php }
                                    } else { ?>
                                        <a href="javascript:void(0);" title="Add" class="btn btn-info" data-toggle="modal" data-target="#submitrequest"><?= $lbl76 ?></a>
                                    <?php } ?>
                                    
                                    <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success" onclick="goBack()"><?= $lbl41 ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem examasstable" id="examasstable" data-page-length='50'>
                                    <thead class="thead-dark">
                                        <tr>
                                            <!--<th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>-->
                                            <th style="display:none">ID</th>
                                            <th><?= $lbl361 ?></th>
                                            <th><?= $lbl363 ?></th>
                                            <th><?= $lbl2086 ?></th>
                                            <th><?= $lbl2087 ?></th>
                                            <th><?= $lbl355 ?></th>
                                            <th><?= $lbl365 ?></th>
                                            <th><?= $lbl388 ?></th>
                                            <th><?= $lbl1964 ?></th>
                                            <th><?= $lbl366 ?></th>
                                            <th><?= $lbl85 ?></th>
                                            <th><?= $lbl367 ?></th>
                                            <th><?= $lbl368 ?></th>
                                            <th><?= $lbl369 ?></th>
                                            <th><?= $lbl333 ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n=1;
                                    foreach($approvedetails as $value)
                                    {
                                        if($value['addquestion'] > 0 )
                                        {
                                            /*if( $value['exam_type'] == "Quarterly" ) { 
                                                $typexm = $quatrlbl; 
                                            }
                                            elseif( $value['exam_type'] == "Monthly" ) { 
                                                $typexm = $mnthlbl; 
                                            }
                                            elseif( $value['exam_type'] == "Weekly" ) { 
                                                $typexm = $weklylbl; 
                                            } 
                                            elseif( $value['exam_type'] == "Yearly" ) { 
                                                $typexm = $yerlylbl; 
                                            }
                                            elseif( $value['exam_type'] == "Half-Yearly" ) {  
                                                $typexm =  $hlfyrlbl ;
                                            } */
                                            /*
                                            if( $value['status'] == 0)
                                            {
                                                $sts = '<a href="javascript:void()"  class="btn btn-sm" title="Status Approval Pending from Superadmin" ><label class="switch"><input type="checkbox" disabled><span class="slider round"></span></label></a>';
                                            }
                                            else 
                                            { 
                                                $sts = '<a href="javascript:void()"  class="btn btn-sm" title="Status Approved from Superadmin" ><label class="switch"><input type="checkbox" checked disabled><span class="slider round"></span></label></a>';
                                            }*/
                                            
                                                if(!empty($sclsub_details[0]['id']))
                                                {
                                                    $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                    if((in_array("107", $roles)) &&  ($value['type'] != "Exams")) {
                                                    
                                                        if( $value['status'] == 0)
                                                        {
                                                            $sts = '<a href="javascript:void()" data-url="ExamAssessment/status" data-id="'.$value['id'].'" data-status="'. $value['status'].'" data-str="Guide Status" class="btn btn-sm  js-sweetalert sts_approval" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                                        }
                                                        else 
                                                        {
                                                            $sts = '<a href="javascript:void()" data-url="ExamAssessment/status" data-id="'. $value['id'].'" data-status="'. $value['status'] .'" data-str="Guide Status" class="btn btn-sm js-sweetalert sts_approval" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if( $value['status'] == 0)
                                                        {
                                                            $sts = '<label class="switch"><input type="checkbox" disabled><span class="slider round"></span></label>';
                                                        }
                                                        else 
                                                        {
                                                            $sts = '<label class="switch"><input type="checkbox" checked disabled><span class="slider round"></span></label>';
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if( $value['type'] == "Exams")
                                                    {
                                                        if( $value['status'] == 0)
                                                        {
                                                            $sts = '<label class="switch"><input type="checkbox" disabled><span class="slider round"></span></label>';
                                                        }
                                                        else 
                                                        {
                                                            $sts = '<label class="switch"><input type="checkbox" checked disabled><span class="slider round"></span></label>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if( $value['status'] == 0)
                                                        { 
                                                            $sts = '<a href="javascript:void()" data-url="ExamAssessment/status" data-id="'.$value['id'].'" data-status="'. $value['status'].'" data-str="Guide Status" class="btn btn-sm sts_approval js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox"><span class="slider round"></span></label></a>';
                                                        }
                                                        else 
                                                        { 
                                                            $sts = '<a href="javascript:void()" data-url="ExamAssessment/status" data-id="'. $value['id'].'" data-status="'. $value['status'] .'" data-str="Guide Status" class="btn btn-sm sts_approval js-sweetalert" title="Status" data-type="status_change"><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label></a>';
                                                        }
                                                    }
                                                }
                                            
                                            if($value['teacher_name'] == "School")
                                            {
                                                $addby = $scllbl;
                                            }
                                            else
                                            {
                                                $addby = $value['teacher_name'];
                                            }
                                                   
                                            if( $value['type'] == "Exams" ) { 
                                                $exmtyp = $exmlbl; 
                                            }
                                            elseif( $value['type'] == "Quiz" ) { 
                                                $exmtyp = $quizlbl; 
                                            }
                                            elseif( $value['type'] == "Assessment" ) { 
                                                $exmtyp = $asslbl; 
                                            }
                                            else { 
                                                $exmtyp =  $studgulbl ;
                                            } 
                                            
                                            if(!empty($sclsub_details[0])) { 
                                                if($value['show'] == 1) {
                                            ?>
                                            <tr> 
                                                <!--<td class="width45">
                                                <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                        <span></span>
                                                    </label>
                                                </td>-->
                                                <td style="display:none"><?= $value['id'] ?></td>
                                                <td>
                                                    <span ><?= $addby ?></span>
                                                </td>
                                                <td>
                                                    <span ><?= $exmtyp ?></span>
                                                </td>
                                                <td>
                                                    <span><?=  $value['exam_type']  ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['exam_period'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['class_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['subject_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= ucfirst($value['title']) ?> </span>
                                                </td>
                                                <td>
                                                    <span><?= $value['max_marks'] ?></span>
                                                </td>
                                                <td>
                                                    <?php if(!empty($value['file_name']))
                                                    { ?>
                                                    <a href="webroot/img/<?= $value['file_name'] ?>" target="_blank"><span><?= ucfirst($value['file_name']) ?></span></a>
                                                    <?php } else { ?>
                                                    <a href="<?=$baseurl?>examAssessment/pdf/<?= $value['id'] ?>" target="_blank"><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1434') { echo $langlbl['title'] ; } } ?></span></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span><?= $sts ?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y', $value['created_date'])?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y H:i', strtotime($value['start_date']))?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y H:i', strtotime($value['end_date']))?></span>
                                                </td>
                                                
                                                
                                                <td>
                                                    <?php   
                                                    $sd = strtotime($value['start_date']);
                                                    $now = time();
                                                    
                                                    if(!empty($sclsub_details[0]))
                                                    { 
                                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                        if(in_array("42", $roles)) { 
                                                            if($now <= $sd) {
                                                            ?>
                                                            <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editexamass" data-toggle="modal"  data-target="#editexamass" title="Edit"><i class="fa fa-edit"></i></button>
                                                            <?php }
                                                        }
                                                        if(in_array("43", $roles)) { 
                                                            ?>
                                                            <button type="button" data-id="<?=$value['id']?>" data-url="examAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                            <?php
                                                        }
                                                    } else { 
                                                        if($now <= $sd) {
                                                        ?>
                                                        <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editexamass" data-toggle="modal"  data-target="#editexamass" title="Edit"><i class="fa fa-edit"></i></button>
                                                        <?php } ?>
                                                        <button type="button" data-id="<?=$value['id']?>" data-url="examAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                    <?php } ?>
                                                    
                                                </td>
                                            </tr>
                                            <?php }
                                            } else { if($value['type'] != "Exams" ) { ?>
                                            <tr> 
                                                <!--<td class="width45">
                                                <label class="fancy-checkbox">
                                                        <input class="checkbox-tick" type="checkbox" name="checkbox">
                                                        <span></span>
                                                    </label>
                                                </td>-->
                                                <td style="display:none"><?= $value['id'] ?></td>
                                                <td>
                                                    <span ><?= $addby ?></span>
                                                </td>
                                                <td>
                                                    <span ><?= $exmtyp ?></span>
                                                </td>
                                                <td>
                                                    <span><?=  $value['exam_type']  ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['exam_period'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['class_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= $value['subject_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span><?= ucfirst($value['title']) ?> </span>
                                                </td>
                                                <td>
                                                    <span><?= $value['max_marks'] ?></span>
                                                </td>
                                                <td>
                                                    <?php if(!empty($value['file_name']))
                                                    { ?>
                                                    <a href="webroot/img/<?= $value['file_name'] ?>" target="_blank"><span><?= ucfirst($value['file_name']) ?></span></a>
                                                    <?php } else { ?>
                                                    <a href="<?=$baseurl?>examAssessment/pdf/<?= $value['id'] ?>" target="_blank"><span><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1434') { echo $langlbl['title'] ; } } ?></span></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span><?= $sts ?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y', $value['created_date'])?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y H:i', strtotime($value['start_date']))?></span>
                                                </td>
                                                <td>
                                                    <span><?=date('d-m-Y H:i', strtotime($value['end_date']))?></span>
                                                </td>
                                                
                                                
                                                <td>
                                                    <?php   
                                                    $sd = strtotime($value['start_date']);
                                                    $now = time();
                                                    
                                                    if(!empty($sclsub_details[0]))
                                                    { 
                                                        $roles = explode(",", $sclsub_details[0]['sub_privileges']); 
                                                        if(in_array("42", $roles)) { 
                                                            if($now <= $sd) {
                                                            ?>
                                                            <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editexamass" data-toggle="modal"  data-target="#editexamass" title="Edit"><i class="fa fa-edit"></i></button>
                                                            <?php }
                                                        }
                                                        if(in_array("43", $roles)) { 
                                                            ?>
                                                            <button type="button" data-id="<?=$value['id']?>" data-url="examAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                            <?php
                                                        }
                                                    } else { 
                                                        if($now <= $sd) {
                                                        ?>
                                                        <button type="button" data-id="<?= $value['id'] ?>" class="btn btn-sm btn-outline-secondary editexamass" data-toggle="modal"  data-target="#editexamass" title="Edit"><i class="fa fa-edit"></i></button>
                                                        <?php } ?>
                                                        <button type="button" data-id="<?=$value['id']?>" data-url="examAssessment/delete" class="btn btn-sm btn-outline-danger js-sweetalert " title="Delete" data-str="Exam/Assignment" data-type="confirm"><i class="fa fa-trash-o"></i></button>
                                                    <?php } ?>
                                                    
                                                </td>
                                            </tr>
                                            <?php } }
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

<div class="modal fade bd-example-modal-lg" id="approval_status" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">   
        <div class="modal-content">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel">School Approval Status Notifications</h6>
		        <button type="button" class=" close" data-dismiss="modal">
  		            <span aria-hidden="true">&times;</span>
		        </button>
	        </div>
	        
            <div class="modal-body">
                
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



 <!------------------Add Submit Request --------------------->

    
<div class="modal classmodal animated zoomIn" id="submitrequest" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                <?php if(!empty($sclsub_details[0])) { ?>
                <h6 class="title" id="defaultModalLabel"><?= $lbl362 ?></h6>
                <?php } else { ?>
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2077') { echo $langlbl['title'] ; } } ?></h6>
                <?php }?>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'exmassadd'] , 'id' => "addexamassform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="error" id="examasserror"></div>
                            <div class="success" id="examasssuccess"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">     
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                                <!--<select class="form-control class_s" id="class" name="class" placeholder="Choose Class" required onchange="subjctcls(this.value)">-->
                                <select class="form-control" id="s_listclass" name="grade" placeholder="Choose Class" required onchange="getgradecls(this.value, 'add')">
                                    <option value="">Choose Class</option>
                                    <?php foreach($classDetails as $class)
            	                    { 
            	                        if(!empty($sclsub_details[0]))
                                        { 
                                            if(strtolower($class['school_sections']) == "creche" || strtolower($class['school_sections']) == "maternelle") {
                                                $clsmsg = "kindergarten";
                                            }
                                            elseif(strtolower($class['school_sections']) == "primaire") {
                                                $clsmsg = "primaire";
                                            }
                                            else
                                            {
                                                $clsmsg = "secondaire";
                                            }
                                            $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                            if(in_array($clsmsg, $subpriv)) { 
                                                $show = 1;
                                            }
                                            else
                                            {
                                                $show = 0;
                                            }
                                        } else { 
                                            $show = 1;
                                        }
                                        if($show == 1) {
                	                    $classkey = $class['c_name']."_". $class['school_sections']; ?>
                	                    <option  value="<?= $classkey  ?>" ><?= $class['c_name'] ." (". $class['school_sections'].")"?> </option>
                	                    <!--<option  value="<?= $class['id']  ?>" ><?= $class['c_name'] . " " . $class['c_section']." (". $class['school_sections'].")" ?> </option>-->
                                        <?php
                                        }
            	                    } ?>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">     
                                <label>Section(s)</label>
                                <!--<select class="form-control class_s" id="class" name="class[]" multiple placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                                <select class="form-control class_s" id="examselclssctn" name="class[]" multiple placeholder="Choose Class" onchange="getstdnt(this.value, 'add')" required>
                                    <option value="">Choose sections</option>
                                </select> 
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">     
                                <label>Student(s)</label>
                                <select class="form-control stdnt_s" id="examselstdnt" name="student[]" multiple placeholder="Choose Students" required>
                                    <option value="">Choose students</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                                <select class="form-control subj_s" name="subjects" id="subjects" placeholder="Choose Subjects" required>
                                    <option value="">Choose Subjects</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">  
                                <label><?= $lbl363 ?></label>
                                <select class="form-control request_opt" name="request_for" id="request_for" placeholder="Choose Options" onchange="getexamtype(this.value)" required>
                                    <option value="" >Choose Option</option>
                                    <option value="Assessment"><?= $lbl1796 ?></option>
                                    <?php if(!empty($sclsub_details[0]))
                                    { ?>
                                    <option value="Exams"><?= $lbl1797 ?></option>
                                    <?php } ?>
                                    <option value="Quiz"><?= $quizlbl ?></option>
                                    <option value="Study Guide"><?= $lbl1799 ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="examtypes" style="display:none;">
                            <div class="form-group">  
                                <label>Semestre/Trimestre</label>
                                <select class="form-control request_opt" name="exam_type" id="exam_type" placeholder="Choose Options" onchange="getperiod(this.value)">
                                    <option value="">Choose Option</option>
                                    <!--<option value="Premier Trimestre">Premier Trimestre</option>
                                    <option value="Deuxieme Trimestre">Deuxieme Trimestre</option>
                                    <option value="Troisieme Trimestre">Troisieme Trimestre</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="examperiod" style="display:none;">
                            <div class="form-group">  
                                <label><?= $lbl2082 ?></label>
                                <select class="form-control request_opt" name="exam_period" id="exam_period" placeholder="Choose Options">
                                    <option value="">Choose Option</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"  name="start_date" id="start_date" required/>
                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                               <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="end_date" id="end_date" required>
                                  <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" id="maxmarks">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?></label>
                                <input type="number" name="max_marks" id="maxmrks"  placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '372') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12" id="" style="display:block;">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                                <input type="text" name="title" id="title" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-12" id="guideinstr">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                                <textarea name="instruction" id="instruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required> </textarea>
                            </div>
                        </div>
                        <div class="col-md-12" id="contentuplod" >
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '374') { echo $langlbl['title'] ; } } ?> *</label>
                                <select class="form-control" required  name="contentupload" id="contentupload" onchange="contntupd(this.value)">
                                    <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '375') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '376') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="custom"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '377') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="pdfupload" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?></label>
                                <input type="file" name="fileupload" id="fileupload" placeholder="File Upload" class="form-control" >
                            </div>
                        </div>
                        
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary addexamassbtn" id="addexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '396') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                    <div class="row clearfix" id="customize" style="display:none">
                        <div class="col-md-12" id="examformat" style="display:none">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1805') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">      
                                    <div class="row container">
                                        <span class="mr-2"><input type="radio" name="exam_format" checked value="custom" class="mr-1"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1806') { echo $langlbl['title'] ; } } ?></span>
                                        <span class="mr-2"><input type="radio" name="exam_format" value="pdf" class="mr-1"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1807') { echo $langlbl['title'] ; } } ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-md-12">
                            <div class="form-group">  
                                <label>File Upload</label>
                                <input type="file" name="fileupload" id="fileupload" placeholder="File Upload"  class="form-control" required>
                            </div>
                        </div>-->
                        <!--<div class="col-md-12">
                            <div class="error" id="examasserror"></div>
                            <div class="success" id="examasssuccess"></div>
                        </div>-->
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary addexamassbtn" id="addexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '354') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                   <?php echo $this->Form->end(); ?>
                </div>
                </div>
            </div>
        </div>
    </div>






 <!------------------Edit Submit Request --------------------->

    
<div class="modal classmodal animated zoomIn" id="editsubmitreq" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header">
                 <?php if(!empty($sclsub_details[0])) { ?>
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1517') { echo $langlbl['title'] ; } } ?></h6>
                <?php } else { ?>
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '2078') { echo $langlbl['title'] ; } } ?></h6>
                <?php }?>
                <button type="button" class=" close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
	        </div>
            <div class="modal-body">
                <?php	echo $this->Form->create(false , ['url' => ['action' => 'exmassedit'] , 'id' => "editexamassform" , 'method' => "post", 'enctype' => "multipart/form-data" ]); ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="error" id="editexamasserror"></div>
                        <div class="success" id="editexamasssuccess"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <!--<select class="form-control" id="eclass" name="eclass" disabled placeholder="Choose Class" required onchange="subjctcls(this.value)">-->
                             <select class="form-control" id="m_listclass" name="egrade" placeholder="Choose Class" disabled required onchange="getgradecls(this.value, 'edit')">
                                <option value="">Choose Class</option>
                                <?php foreach($classDetails as $class)
        	                    { 
        	                        if(!empty($sclsub_details[0]))
                                    { 
                                        if(strtolower($class['school_sections']) == "creche" || strtolower($class['school_sections']) == "maternelle") {
                                            $clsmsg = "kindergarten";
                                        }
                                        elseif(strtolower($class['school_sections']) == "primaire") {
                                            $clsmsg = "primaire";
                                        }
                                        else
                                        {
                                            $clsmsg = "secondaire";
                                        }
                                        $subpriv = explode(",", $sclsub_details[0]['scl_sub_priv']); 
                                        if(in_array($clsmsg, $subpriv)) { 
                                            $show = 1;
                                        }
                                        else
                                        {
                                            $show = 0;
                                        }
                                    } else { 
                                        $show = 1;
                                    }
                                    if($show == 1) {
            	                    $classkey = $class['c_name']."_". $class['school_sections']; ?>
        	                        <!--<option  value="<?= $classkey ?>" ><?= $empdtl['class']['c_name']." (". $empdtl['class']['school_sections'].")" ?> </option>-->
            	                    <option  value="<?= $classkey  ?>" ><?= $class['c_name'] ." (". $class['school_sections'].")"?> </option>
                                    <?php
                                    }
        	                    } ?>
                                
                                
                            </select> 
                        </div>
                    </div>
                    <!--<div class="col-md-4">
                        <div class="form-group">     
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1029') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control" id="s_listclass" name="grade" placeholder="Choose Class" disabled required onchange="getgradecls(this.value, 'edit')">
                                <option value="">Choose Class</option>
                                <?php foreach($empcls_details as $empdtl) 
                                {
        	                        $classkey = $empdtl['class']['c_name']."_". $empdtl['class']['school_sections']; ?>
        	                        <option  value="<?= $classkey ?>" ><?= $empdtl['class']['c_name']." (". $empdtl['class']['school_sections'].")" ?> </option>
                                <?php } ?>
                            </select> 
                        </div>
                    </div>-->
                    <div class="col-md-4">
                        <div class="form-group">     
                            <label>Section(s)</label>
                            <!--<select class="form-control class_s" id="class" name="class[]" multiple placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                            <select class="form-control eclass_s" id="eexamselclssctn" name="eclass[]" multiple placeholder="Choose Class" required >
                                <option value="">Choose sections</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">     
                            <label>Student(s)</label>
                            <!--<select class="form-control class_s" id="class" name="class[]" multiple placeholder="Choose Class" required  onchange="getsubcls(this.value, this.id)">-->
                            <select class="form-control eclass_s" id="eexamselclssstdnt" name="estdnt[]" multiple placeholder="Choose Student" required >
                                <option value="">Choose Students</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1030') { echo $langlbl['title'] ; } } ?></label>
                            <select class="form-control subj_s" name="esubjects" id="esubjects" placeholder="Choose Subjects" required>
                                <option value="">Choose Subjects</option>
                                <?php foreach($subjectDetails as $subjects)
        	                    { 
            	                    ?>
            	                    <option  value="<?= $subjects['id']  ?>" ><?= $subjects['subject_name'] ?> </option>
                                    <?php
        	                    } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?= $lbl363 ?></label>
                            <select class="form-control request_opt" name="erequest_for" id="erequest_for" placeholder="Choose Options" onchange="egetexamtype(this.value)" required>
                                <option value="">Choose Option</option>
                                <option value="Assessment"><?= $lbl1796 ?></option>
                                <?php if(!empty($sclsub_details[0]))  { ?>
                                <option value="Exams"><?= $lbl1797 ?></option>
                                <?php } ?>
                                <option value="Quiz"><?= $quizlbl ?></option>
                                <option value="Study Guide"><?= $lbl1799 ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="eexamtypes" style="display:none;">
                        <div class="form-group">  
                            <label>Semestre/Trimestre</label>
                            <select class="form-control request_opt" name="eexam_type" id="eexam_type" placeholder="Choose Options" onchange="egetperiod(this.value, this.id)">
                                <option value="">Choose Option</option>
                                <!--<option value="Premier Trimestre">Premier Trimestre</option>
                                <option value="Deuxieme Trimestre">Deuxieme Trimestre</option>
                                <option value="Troisieme Trimestre">Troisieme Trimestre</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="eexamperiod" style="display:none;">
                        <div class="form-group">  
                            <label><?= $lbl2082 ?></label>
                            <select class="form-control request_opt" name="eexam_period" id="eexam_period" placeholder="Choose Options">
                                <option value="">Choose Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '368') { echo $langlbl['title'] ; } } ?></label>
                            <div class="input-group date" id="edatetimepicker1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#edatetimepicker1"  name="estart_date" id="estart_date" required/>
                                <div class="input-group-append" data-target="#edatetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '369') { echo $langlbl['title'] ; } } ?></label>
                           <!-- <input type="date" class="form-control" name="end_date" id="end_date" required>-->
                            <div class="input-group date" id="edatetimepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#edatetimepicker2" name="eend_date" id="eend_date" required>
                              <div class="input-group-append" data-target="#edatetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"  id="emaxmarks">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '371') { echo $langlbl['title'] ; } } ?></label>
                            <input type="number" name="emax_marks" id="max_marks" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '372') { echo $langlbl['title'] ; } } ?>"  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12" style="display:block;">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '388') { echo $langlbl['title'] ; } } ?></label>
                            <input type="text" name="etitle" id="etitle" placeholder="<?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '389') { echo $langlbl['title'] ; } } ?>"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12" id="eguideinstr">
                        <div class="form-group">  
                            <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '373') { echo $langlbl['title'] ; } } ?></label>
                            <textarea name="einstruction" id="einstruction" placeholder="Enter Instruction"   class="form-control"  rows="3" required> </textarea>
                        </div>
                    </div>
                    <input type="hidden" name="exam_assid" id="exam_assid" >
                    <div class="col-md-12"  id="econtentuplod" >
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '374') { echo $langlbl['title'] ; } } ?> *</label>
                                <select class="form-control" required  name="econtentupload" id="econtentupload" onchange="econtntupd(this.value)">
                                    <option value=""><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '375') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="pdf"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '376') { echo $langlbl['title'] ; } } ?></option>
                                    <option value="custom"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '377') { echo $langlbl['title'] ; } } ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="epdfupload" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">  
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1465') { echo $langlbl['title'] ; } } ?></label> <p id="fileName"></p>
                                <input type="file" name="efileupload" id="efileupload" placeholder="File Upload"  class="form-control" >
                                <input type="hidden" name="pre_file" id="pre_file" >
                            </div>
                        </div>
                        
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary editexamassbtn" id="editexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                    <div class="row clearfix" id="ecustomize" style="display:none">
                        <div class="col-md-12" id="eexamformat" style="display:none">
                            <div class="form-group">   
                                <label><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1805') { echo $langlbl['title'] ; } } ?></label>
                                <div class="form-group">      
                                    <div class="row container">
                                        <span class="mr-2"><input type="radio" id="editable" name="exam_format" checked value="custom" class="mr-1"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1806') { echo $langlbl['title'] ; } } ?></span>
                                        <span class="mr-2"><input type="radio" id="pdfex" name="exam_format" value="pdf" class="mr-1"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1807') { echo $langlbl['title'] ; } } ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button_row" >
                            <hr>
                            <button type="submit" class="btn btn-primary editexamassbtn" id="editexamassbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '354') { echo $langlbl['title'] ; } } ?></button>
                            <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '397') { echo $langlbl['title'] ; } } ?></button>
                        </div>
                    </div>
                   <?php echo $this->Form->end(); ?>
                </div>
                    
                   
            </div>
             
        </div>
    </div>
</div>    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<?php
if(!empty($company_details))
{
    if(!empty($_GET))
    {
        if($_GET['examAssmodal'] == 1)
        {
            ?>
            <script>
            var baseurl = window.location.pathname.split('/')[1];
            var baseurl = "/" + baseurl;
            var examid = <?php echo $_GET['examid']; ?>;
            var refscrf = $("input[name='_csrfToken']").val();
            $(function(){
                 // $("#subjectdetails").modal("show");
                 $.ajax({ 
                    url: baseurl +"/examAssessment/edit", 
                    data: {"id":examid, _csrfToken : refscrf}, 
                    type: 'post',
                    success: function (result) 
                    {       
                        if(result) 
                        {
                           // console.log(result);
                            $("#editsubmitreq").modal("show");
                            $("#exam_assid").val(examid);
                            $("#einstruction").val(result[0].special_instruction);
                            $("#etitle").val(result[0].title);
                            $("#eend_date").val(result[0].end_date);
                            $("#estart_date").val(result[0].start_date);
                            $("#pre_file").val(result[0].file_name);
                            $("#max_marks").val(result[0].max_marks);
                            $("#emax_marks").val(result[0].max_marks);
                            if(result[0].file_name != null)
                            {
                                $("#fileName").html("<a href='webroot/img/"+result[0].file_name+"' target='_blank'>"+result[0].file_name+"</a>");
                            }
                            $("#eclass").select2().val(result[0].class_id).trigger('change.select2');
                            $("#econtentupload").select2().val(result[0].exam_format).trigger('change.select2');
                            if(result[0].exam_format != "")
                            {
                                $("#epdfupload").css("display", "block");
                            }
                            $("#esubjects").select2().val(result[0].subject_id).trigger('change.select2');
                            $("#erequest_for").select2().val(result[0].type).trigger('change.select2');
                            $("#eexam_type").select2().val(result[0].exam_type).trigger('change.select2');
                        }
                    }
                });
            });
            </script>
        <?php         
        }
        elseif($_GET['examAssmodal'] == 0)
        {
            ?>
            <script>
               $("#editsubmitreq").modal("hide");
            </script>
            <?php
        }
    }
}

?>


